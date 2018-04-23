<?php

abstract class td_module {

    var $post;



    var $title_attribute;

    var $title;             // by default the WordPress title is not escaped on twenty fifteen

    var $href;





    /**

     * @var mixed the review metadata - we get it for each $post

     */

    protected $td_review;



	/**

	 * @var bool is true if we have a review for this $post

	 */

	protected $is_review = false;



    /**

     * @var int|null Contains the id of the current $post thumbnail. If no thumbnail is found, the value is NULL

     */

    protected $post_thumb_id = NULL;





    /**

     * @param $post WP_Post

     * @throws ErrorException

     */

    function __construct($post) {

        if (gettype($post) != 'object' or get_class($post) != 'WP_Post') {

            td_util::error(__FILE__, 'td_module: ' . get_Class($this) . '($post): $post is not WP_Post');

        }





        //this filter is used by td_unique_posts.php - to add unique posts to the array for the datasource

        apply_filters("td_wp_booster_module_constructor", $this, $post);



        $this->post = $post;



        // by default the WordPress title is not escaped on twenty fifteen

        $this->title = get_the_title($post->ID);

        $this->title_attribute = esc_attr(strip_tags($this->title));

        $this->href = esc_url(get_permalink($post->ID));



        if (has_post_thumbnail($this->post->ID)) {

            $tmp_get_post_thumbnail_id = get_post_thumbnail_id($this->post->ID);

            if (!empty($tmp_get_post_thumbnail_id)) {

                // if we have a wrong id, leave the post_thumb_id NULL

                $this->post_thumb_id = $tmp_get_post_thumbnail_id;

            }

        }



        //get the review metadata

        //$this->td_review = get_post_meta($this->post->ID, 'td_review', true); @todo $this->td_review variable name must be replaced and the 'get_quotes_on_blocks', 'get_category' methods also

	    $this->td_review = get_post_meta($this->post->ID, 'td_post_theme_settings', true);



	    if (!empty($this->td_review['has_review']) and (

			    !empty($this->td_review['p_review_stars']) or

			    !empty($this->td_review['p_review_percents']) or

			    !empty($this->td_review['p_review_points'])

		    )

	    ) {

		    $this->is_review = true;

	    }

    }





    /**

     * @deprecated - google changed the structured data requirements and we no longer use them on modules

     */

    function get_item_scope() {

        return '';

    }





    /**

     * @deprecated - google changed the structured data requirements and we no longer use them on modules

     */

    function get_item_scope_meta() {

        return '';

    }





    function get_module_classes($additional_classes_array = '') {

        //add the wrap and module id class

        $buffy = get_class($this);





	    // each module setting has a 'class' key to customize css

	    $module_class = td_api_module::get_key(get_class($this), 'class');



	    if ($module_class != '') {

		    $buffy .= ' ' . $module_class;

	    }





        //show no thumb only if no thumb is detected and image placeholders are disabled

        if (is_null($this->post_thumb_id) and td_util::get_option('tds_hide_featured_image_placeholder') == 'hide_placeholder') {

            $buffy .= ' td_module_no_thumb';

        }



        // fix the meta info space when all options are off

        if (td_util::get_option('tds_m_show_author_name') == 'hide' and td_util::get_option('tds_m_show_date') == 'hide' and td_util::get_option('tds_m_show_comments') == 'hide') {

            $buffy .= ' td-meta-info-hide';

        }



	    if ($additional_classes_array != '' && is_array($additional_classes_array)) {

		    $buffy .= ' ' . implode(' ', $additional_classes_array);

	    }



	    // the following case could not be checked

	    // $buffy = implode(' ', array_unique(explode(' ', $buffy)));



        return $buffy;

    }





    function get_author() {

        $buffy = '';



        if ($this->is_review === false) {

            if (td_util::get_option('tds_m_show_author_name') != 'hide') {

                $buffy .= '<span class="td-post-author-name">';

                $buffy .= '<a href="' . get_author_posts_url($this->post->post_author) . '">' . get_the_author_meta('display_name', $this->post->post_author) . '</a>' ;

                if (td_util::get_option('tds_m_show_author_name') != 'hide' and td_util::get_option('tds_m_show_date') != 'hide') {

                    $buffy .= ' <span>-</span> ';

                }

                $buffy .= '</span>';

            }



        }

        return $buffy;



    }





    function get_date($show_stars_on_review = true) {

        $visibility_class = '';

        if (td_util::get_option('tds_m_show_date') == 'hide') {

            $visibility_class = ' td-visibility-hidden';

        }



        $buffy = '';

        if ($this->is_review and $show_stars_on_review === true) {

            //if review show stars

            $buffy .= '<div class="entry-review-stars">';

            $buffy .=  td_review::render_stars($this->td_review);

            $buffy .= '</div>';



        } else {

            if (td_util::get_option('tds_m_show_date') != 'hide') {

                $td_article_date_unix = get_the_time('U', $this->post->ID);

                $buffy .= '<span class="td-post-date">';

                    $buffy .= '<time class="entry-date updated td-module-date' . $visibility_class . '" datetime="' . date(DATE_W3C, $td_article_date_unix) . '" >' . get_the_time(get_option('date_format'), $this->post->ID) . '</time>';

                $buffy .= '</span>';

            }

        }



        return $buffy;

    }



    function get_comments() {

        $buffy = '';

        if (td_util::get_option('tds_m_show_comments') != 'hide') {

            $buffy .= '<div class="td-module-comments">';

                $buffy .= '<a href="' . get_comments_link($this->post->ID) . '">';

                    $buffy .= get_comments_number($this->post->ID);

                $buffy .= '</a>';

            $buffy .= '</div>';

        }



        return $buffy;

    }







    /**

     * get image - v 3.0  23 ian 2015

     * @param $thumbType

     * @return string

     */

    function get_image($thumbType, $css_image = false) {

        $buffy = ''; //the output buffer

        $tds_hide_featured_image_placeholder = td_util::get_option('tds_hide_featured_image_placeholder');

        //retina image

        $srcset_sizes = '';



        // do we have a post thumb or a placeholder?

        if (!is_null($this->post_thumb_id) or ($tds_hide_featured_image_placeholder != 'hide_placeholder')) {



            if (!is_null($this->post_thumb_id)) {

                //if we have a thumb

                // check to see if the thumb size is enabled in the panel, we don't have to check for the default wordpress

                // thumbs (the default ones are already cut and we don't have  a panel setting for them)

                if (td_util::get_option('tds_thumb_' . $thumbType) != 'yes' and $thumbType != 'thumbnail') {

                    //the thumb is disabled, show a placeholder thumb from the theme with the "thumb disabled" message

                    global $_wp_additional_image_sizes;



                    if (empty($_wp_additional_image_sizes[$thumbType]['width'])) {

                        $td_temp_image_url[1] = '';

                    } else {

                        $td_temp_image_url[1] = $_wp_additional_image_sizes[$thumbType]['width'];

                    }



                    if (empty($_wp_additional_image_sizes[$thumbType]['height'])) {

                        $td_temp_image_url[2] = '';

                    } else {

                        $td_temp_image_url[2] = $_wp_additional_image_sizes[$thumbType]['height'];

                    }



					// For custom wordpress sizes (not 'thumbnail', 'medium', 'medium_large' or 'large'), get the image path using the api (no_image_path)

	                $thumb_disabled_path = td_global::$get_template_directory_uri;

	                if (strpos($thumbType, 'td_') === 0) {

			            $thumb_disabled_path = td_api_thumb::get_key($thumbType, 'no_image_path');

		            }

			        $td_temp_image_url[0] = $thumb_disabled_path . '/images/thumb-disabled/' . $thumbType . '.png';



                    $attachment_alt = 'alt=""';

                    $attachment_title = '';



                } else {

                    // the thumb is enabled from the panel, it's time to show the real thumb

                    $td_temp_image_url = wp_get_attachment_image_src($this->post_thumb_id, $thumbType);

                    $attachment_alt = get_post_meta($this->post_thumb_id, '_wp_attachment_image_alt', true );

                    $attachment_alt = 'alt="' . esc_attr(strip_tags($attachment_alt)) . '"';

                    $attachment_title = ' title="' . esc_attr(strip_tags($this->title)) . '"';



                    if (empty($td_temp_image_url[0])) {

                        $td_temp_image_url[0] = '';

                    }



                    if (empty($td_temp_image_url[1])) {

                        $td_temp_image_url[1] = '';

                    }



                    if (empty($td_temp_image_url[2])) {

                        $td_temp_image_url[2] = '';

                    }



                    //retina image

                    $srcset_sizes = td_util::get_srcset_sizes($this->post_thumb_id, $thumbType, $td_temp_image_url[1], $td_temp_image_url[0]);



                } // end panel thumb enabled check







            } else {

                //we have no thumb but the placeholder one is activated

                global $_wp_additional_image_sizes;



                if (empty($_wp_additional_image_sizes[$thumbType]['width'])) {

                    $td_temp_image_url[1] = '';

                } else {

                    $td_temp_image_url[1] = $_wp_additional_image_sizes[$thumbType]['width'];

                }



                if (empty($_wp_additional_image_sizes[$thumbType]['height'])) {

                    $td_temp_image_url[2] = '';

                } else {

                    $td_temp_image_url[2] = $_wp_additional_image_sizes[$thumbType]['height'];

                }



                /**

                 * get thumb height and width via api

                 * first we check the global in case a custom thumb is used

                 *

                 * The api thumb is checked only for additional sizes registered and if at least one of the settings (width or height) is empty.

                 * This should be enough to avoid getting a non existing id using api thumb.

                 */

	            if (!empty($_wp_additional_image_sizes) && array_key_exists($thumbType, $_wp_additional_image_sizes) && ($td_temp_image_url[1] == '' || $td_temp_image_url[2] == '')) {

                    $td_thumb_parameters = td_api_thumb::get_by_id($thumbType);

	                $td_temp_image_url[1] = $td_thumb_parameters['width'];

                    $td_temp_image_url[2] = $td_thumb_parameters['height'];

                }



	            // For custom wordpress sizes (not 'thumbnail', 'medium', 'medium_large' or 'large'), get the image path using the api (no_image_path)

	            $no_thumb_path = td_global::$get_template_directory_uri;

	            if (strpos($thumbType, 'td_') === 0) {

		            $no_thumb_path = rtrim(td_api_thumb::get_key($thumbType, 'no_image_path'), '/');

	            }

		        $td_temp_image_url[0] = $no_thumb_path . '/images/no-thumb/' . $thumbType . '.png';



                $attachment_alt = 'alt=""';

                $attachment_title = '';

            } //end    if ($this->post_has_thumb) {







            $buffy .= '<div class="td-module-thumb">';

                if (current_user_can('edit_posts')) {

                    $buffy .= '<a class="td-admin-edit" href="' . get_edit_post_link($this->post->ID) . '">edit</a>';

                }





                $buffy .= '<a href="' . $this->href . '" rel="bookmark" title="' . $this->title_attribute . '">';



                    // css image

                    if ($css_image === true) {

                        // retina image

                        if (td_util::get_option('tds_thumb_' . $thumbType . '_retina') == 'yes' && !empty($td_temp_image_url[1])) {

                            $retina_url = wp_get_attachment_image_src($this->post_thumb_id, $thumbType . '_retina');

                            if (!empty($retina_url[0])) {

                                $td_temp_image_url[0] = $retina_url[0];

                            }

                        }

                        $buffy .= '<span class="entry-thumb td-thumb-css" style="background-image: url(' . $td_temp_image_url[0] . ')"></span>';



                    // normal image

                    } else {

                        $buffy .= '<img width="' . $td_temp_image_url[1] . '" height="' . $td_temp_image_url[2] . '" class="entry-thumb" src="' . $td_temp_image_url[0] . '"' . $srcset_sizes . ' ' . $attachment_alt . $attachment_title . '/>';

                    }



                    // on videos add the play icon

                    if (get_post_format($this->post->ID) == 'video') {



                        $use_small_post_format_icon_size = false;

                        // search in all the thumbs for the one that we are currently using here and see if it has post_format_icon_size = small

                        foreach (td_api_thumb::get_all() as $thumb_from_thumb_list) {

                            if ($thumb_from_thumb_list['name'] == $thumbType and $thumb_from_thumb_list['post_format_icon_size'] == 'small') {

                                $use_small_post_format_icon_size = true;

                                break;

                            }

                        }



                        // load the small or medium play icon

                        if ($use_small_post_format_icon_size === true) {

                            $buffy .= '<span class="td-video-play-ico td-video-small"><img width="20" height="20" class="td-retina" src="' . td_global::$get_template_directory_uri . '/images/icons/video-small.png' . '" alt="video"/></span>';

                        } else {

                            $buffy .= '<span class="td-video-play-ico"><img width="40" height="40" class="td-retina" src="' . td_global::$get_template_directory_uri . '/images/icons/ico-video-large.png' . '" alt="video"/></span>';

                        }

                    } // end on video if



                $buffy .= '</a>';

            $buffy .= '</div>'; //end wrapper



            return $buffy;

        }

    }







    /**

     * This function returns the title with the appropriate markup.

     * @param string $cut_at - if provided, the method will just cut at that point

     * and it will cut after that. If not setting is in the database the function will cut at the default value

     * @return string

     */



    function get_title($cut_at = '') {

        $buffy = '';

        $buffy .= '<h3 class="entry-title td-module-title">';

        $buffy .='<a href="' . $this->href . '" rel="bookmark" title="' . $this->title_attribute . '">';



        //see if we have to cut the title and if we have the title lenght in the panel for ex: td_module_6__title_excerpt

        if ($cut_at != '') {

            //cut at the hard coded size

            $buffy .= td_util::excerpt($this->title, $cut_at, 'show_shortcodes');



        } else {

            $current_module_class = get_class($this);



            //see if we have a default setting for this module, and if so only apply it if we don't get other things form theme panel.

            if (td_api_module::_helper_check_excerpt_title($current_module_class)) {

                $db_title_excerpt = td_util::get_option($current_module_class . '_title_excerpt');

                if ($db_title_excerpt != '') {

                    //cut from the database settings

                    $buffy .= td_util::excerpt($this->title, $db_title_excerpt, 'show_shortcodes');

                } else {

                    //cut at the default size

                    $module_api = td_api_module::get_by_id($current_module_class);

                    $buffy .= td_util::excerpt($this->title, $module_api['excerpt_title'], 'show_shortcodes');

                }

            } else {

                /**

                 * no $cut_at provided and no setting in td_config -> return the full title

                 * @see td_global::$modules_list

                 */

                $buffy .= $this->title;

            }



        }

        $buffy .='</a>';

        $buffy .= '</h3>';

        return $buffy;

    }





    /**

     * This method is used by modules to get content that has to be excerpted (cut)

     * IT RETURNS THE EXCERPT FROM THE POST IF IT'S ENTERED IN THE EXCERPT CUSTOM POST FIELD BY THE USER

     * @param string $cut_at - if provided the method will just cat at that point

     * @return string

     */

    function get_excerpt($cut_at = '') {



        //If the user supplied the excerpt in the post excerpt custom field, we just return that

        if ($this->post->post_excerpt != '') {

            return $this->post->post_excerpt;

        }



        $buffy = '';

        if ($cut_at != '') {

            // simple, $cut_at and return

            $buffy .= td_util::excerpt($this->post->post_content, $cut_at);

        } else {

            $current_module_class = get_class($this);



            //see if we have a default setting for this module, and if so only apply it if we don't get other things form theme panel.

            if (td_api_module::_helper_check_excerpt_content($current_module_class)) {

                $db_content_excerpt = td_util::get_option($current_module_class . '_content_excerpt');

                if ($db_content_excerpt != '') {

                    //cut from the database settings

                    $buffy .= td_util::excerpt($this->post->post_content, $db_content_excerpt);

                } else {

                    //cut at the default size

                    $module_api = td_api_module::get_by_id($current_module_class);

                    $buffy .= td_util::excerpt($this->post->post_content, $module_api['excerpt_content']);

                }

            } else {

                /**

                 * no $cut_at provided and no setting in td_config -> return the full $this->post->post_content

                 * @see td_global::$modules_list

                 */

                $buffy .= $this->post->post_content;

            }

        }

        return $buffy;

    }







    function get_category() {

    // SHOW YOAST PRIMARY CATEGORY, OR FIRST CATEGORY
    $category = get_the_category();
    $useCatLink = true;

        $category_display = '';
        $category_link = '';
        if ( class_exists('WPSEO_Primary_Term') )
        {
            // Show the post's 'Primary' category, if this Yoast feature is available, & one is set
            $wpseo_primary_term = new WPSEO_Primary_Term( 'category', $this->post->ID );
            $wpseo_primary_term = $wpseo_primary_term->get_primary_term();
            $term = get_term( $wpseo_primary_term );
            if (is_wp_error($term)) { 
                // Default to first category (not Yoast) if an error is returned
                $category_display = $category[0]->name;
                $category_link = get_category_link( $category[0]->term_id );
            } else { 
                // Yoast Primary category
                $category_display = $term->name;
                $category_link = get_category_link( $term->term_id );
            }
        } 
        else {
            // Default, display the first category in WP's list of assigned categories
            $category_display = $category[0]->name;
            $category_link = get_category_link( $category[0]->term_id );
        }
             
        // Display category
        if ( !empty($category_display) ){
            if ( $useCatLink == true && !empty($category_link) ){
           $buffy .= '<a href="' . $category_link . '" class="td-post-category">'  . htmlspecialchars($category_display) . '</a>' ;
            } else {
            $buffy .= '<a href="' . $category_link . '" class="td-post-category">'  . htmlspecialchars($category_display) . '</a>' ;
            }
        }

            //return print_r($post, true);
            return $buffy;

    }





    //get quotes on blocks

    function get_quotes_on_blocks() {



        // do not show the quote on WordPress loops

        if (td_global::$is_wordpress_loop === true or td_global::vc_get_column_number() != 1) {

            return '';

        }





        //get quotes data from database

        $post_data_from_db = get_post_meta($this->post->ID, 'td_post_theme_settings', true);



        if(!empty($post_data_from_db['td_quote_on_blocks'])) {

            return '<div class="td_quote_on_blocks">' . $post_data_from_db['td_quote_on_blocks'] . '</div>';

        }

    }

}