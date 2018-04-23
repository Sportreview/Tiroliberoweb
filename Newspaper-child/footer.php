<!-- Instagram -->

<?php if (td_util::get_option('tds_footer_instagram') == 'show') { ?>

<div class="td-main-content-wrap td-footer-instagram-container">
    <?php
    //get the instagram id from the panel
    $tds_footer_instagram_id = td_util::get_option('tds_footer_instagram_id');
    ?>

    <div class="td-instagram-user">
        <h4 class="td-footer-instagram-title">
            <?php echo  __td('Follow us on Instagram', TD_THEME_NAME); ?>
            <a class="td-footer-instagram-user-link" href="https://www.instagram.com/<?php echo $tds_footer_instagram_id ?>" target="_blank">@<?php echo $tds_footer_instagram_id ?></a>
        </h4>
    </div>

    <?php
    //get the other panel seetings
    $tds_footer_instagram_nr_of_row_images = intval(td_util::get_option('tds_footer_instagram_on_row_images_number'));
    $tds_footer_instagram_nr_of_rows = intval(td_util::get_option('tds_footer_instagram_rows_number'));
    $tds_footer_instagram_img_gap = td_util::get_option('tds_footer_instagram_image_gap');
    $tds_footer_instagram_header = td_util::get_option('tds_footer_instagram_header_section');

    //show the insta block
    echo td_global_blocks::get_instance('td_block_instagram')->render(
        array(
            'instagram_id' => $tds_footer_instagram_id,
            'instagram_header' => /*td_util::get_option('tds_footer_instagram_header_section')*/ 1,
            'instagram_images_per_row' => $tds_footer_instagram_nr_of_row_images,
            'instagram_number_of_rows' => $tds_footer_instagram_nr_of_rows,
            'instagram_margin' => $tds_footer_instagram_img_gap
        )
    );

    ?>
</div>

<?php } ?>


<!-- Footer -->
<?php
if (td_util::get_option('tds_footer') != 'no') {
    td_api_footer_template::_helper_show_footer();
}
?>


<!-- Sub Footer -->
<?php if (td_util::get_option('tds_sub_footer') != 'no') { ?>
    <div class="td-sub-footer-container">
        <div class="td-container">
            <div class="td-pb-row">
                <div class="td-pb-span7 td-sub-footer-menu">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer-menu',
                            'menu_class'=> 'td-subfooter-menu',
                            'fallback_cb' => 'td_wp_footer_menu'
                        ));

                        //if no menu
                        function td_wp_footer_menu() {
                            //do nothing?
                        }
                        ?>
                </div>

                <div class="td-pb-span5 td-sub-footer-copy">
                    <?php
                    $tds_footer_copyright = stripslashes(td_util::get_option('tds_footer_copyright'));
                    $tds_footer_copy_symbol = td_util::get_option('tds_footer_copy_symbol');

                    //show copyright symbol
                    if ($tds_footer_copy_symbol == '') {
                        echo '&copy; ';
                    }

                    echo $tds_footer_copyright;
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div><!--close td-outer-wrap-->



<div class="newtekadv-mobile-bottom">
  <?php if (function_exists('adv_bottom_mobile')) {
    adv_bottom_mobile();
  } ?>
</div>


<?php if (function_exists('adv_in_article_page')) {
  adv_in_article_page();
} ?>


<?php wp_footer(); ?>

<script type="text/javascript">
  WebFontConfig = {
    google: { families: [ 'Open Sans:300,400,700', 'Oswald:300,400,700', 'Roboto:300,400,700' ] }
  };
  (function() {
    var wf = document.createElement('script');
    wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js';
    wf.type = 'text/javascript';
    wf.async = 'true';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(wf, s);
  })();
</script>


<?php if (function_exists('floor_ad')) {
  floor_ad();
} ?>


<!-- Facebook SDK -->
<div id="fb-root"></div>
<script>
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/it_IT/sdk.js#xfbml=1&version=v2.11&appId=318800015274646';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>


<?php if(is_single()): ?>
    <!-- Facebook share -->
    <script>

        function GetShareTargetCMS(){
            var token = '318800015274646|07d196e9d8572e9cb0569f1ae0f81c3d';
                jQuery.ajax({
                    url: 'https://graph.facebook.com/?id='+'<?php the_permalink(); ?>',
                    dataType: 'jsonp',
                    type: 'POST',
                    success: function(data){
                        console.log(data.share);
                            if(typeof data.share != 'undefined'){
                                jQuery('.results_share').empty().html('<span>'+data.share.share_count+'</span>');
                            }else{
                                jQuery('.results_share').empty().html('<span>0</span>');
                            }//else
                    }//success
             });
        }//GetShareTargetCMS
        jQuery(document).ready(function() {
            GetShareTargetCMS();
        });

    </script>

    <script>
        document.getElementById('shareBtn').onclick = function() {
            FB.ui({
                method: 'share',
                mobile_iframe: true,
                href: "<?php the_permalink(); ?>",
            }, function(response){ setTimeout(function(){GetShareTargetCMS()},1000); });
        }
    </script>

<?php endif; ?>

<!-- JavaScript Cookie v2.2.0 -->
<script>
  ;(function(factory){var registeredInModuleLoader=false;if(typeof define==='function'&&define.amd){define(factory);registeredInModuleLoader=true;}
if(typeof exports==='object'){module.exports=factory();registeredInModuleLoader=true;}
if(!registeredInModuleLoader){var OldCookies=window.Cookies;var api=window.Cookies=factory();api.noConflict=function(){window.Cookies=OldCookies;return api;};}}(function(){function extend(){var i=0;var result={};for(;i<arguments.length;i++){var attributes=arguments[i];for(var key in attributes){result[key]=attributes[key];}}
return result;}
function init(converter){function api(key,value,attributes){var result;if(typeof document==='undefined'){return;}
if(arguments.length>1){attributes=extend({path:'/'},api.defaults,attributes);if(typeof attributes.expires==='number'){var expires=new Date();expires.setMilliseconds(expires.getMilliseconds()+attributes.expires*864e+5);attributes.expires=expires;}
attributes.expires=attributes.expires?attributes.expires.toUTCString():'';try{result=JSON.stringify(value);if(/^[\{\[]/.test(result)){value=result;}}catch(e){}
if(!converter.write){value=encodeURIComponent(String(value)).replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g,decodeURIComponent);}else{value=converter.write(value,key);}
key=encodeURIComponent(String(key));key=key.replace(/%(23|24|26|2B|5E|60|7C)/g,decodeURIComponent);key=key.replace(/[\(\)]/g,escape);var stringifiedAttributes='';for(var attributeName in attributes){if(!attributes[attributeName]){continue;}
stringifiedAttributes+='; '+attributeName;if(attributes[attributeName]===true){continue;}
stringifiedAttributes+='='+attributes[attributeName];}
return(document.cookie=key+'='+value+stringifiedAttributes);}
if(!key){result={};}
var cookies=document.cookie?document.cookie.split('; '):[];var rdecode=/(%[0-9A-Z]{2})+/g;var i=0;for(;i<cookies.length;i++){var parts=cookies[i].split('=');var cookie=parts.slice(1).join('=');if(!this.json&&cookie.charAt(0)==='"'){cookie=cookie.slice(1,-1);}
try{var name=parts[0].replace(rdecode,decodeURIComponent);cookie=converter.read?converter.read(cookie,name):converter(cookie,name)||cookie.replace(rdecode,decodeURIComponent);if(this.json){try{cookie=JSON.parse(cookie);}catch(e){}}
if(key===name){result=cookie;break;}
if(!key){result[name]=cookie;}}catch(e){}}
return result;}
api.set=api;api.get=function(key){return api.call(api,key);};api.getJSON=function(){return api.apply({json:true},[].slice.call(arguments));};api.defaults={};api.remove=function(key,attributes){api(key,'',extend(attributes,{expires:-1}));};api.withConverter=init;return api;}
return init(function(){});}));
</script>


<?php if ( is_single() && in_category('video') ) :
  //
  else:
?>
  <script type="text/javascript">
    // get cookie value
    var ck = Cookies.get('rb');
    if (ck) {
      console.log("RB cookie set");
    } else {
      console.log("RB cookie not set");  
        function RedirectBoost() {
        // set cookie timeout
        var threehours = new Date(new Date().getTime() + 180 * 60 * 1000);
        // set cookie
        Cookies.set('rb', 'refresh', { expires: threehours });
        // change url
        location.href = window.location.href;
      }
      setTimeout('RedirectBoost()', 120000);
    }
  </script>
<?php endif; ?>


</body>

</html>
