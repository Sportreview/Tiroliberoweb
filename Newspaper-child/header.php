<!doctype html >
<!--[if IE 8]>    <html class="ie8" lang="it"> <![endif]-->
<!--[if IE 9]>    <html class="ie9" lang="it"> <![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
  <title><?php wp_title('|', true, 'right'); ?></title>
  <meta charset="<?php bloginfo( 'charset' );?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />


  <?php if (function_exists('adv_tag_manager')) {
    adv_tag_manager();
  } ?>

  
  <?php
  wp_head(); /** we hook up in wp_booster @see td_wp_booster_functions::hook_wp_head */
  ?>

  <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/isMobileNewtek.js"></script>
  
  <script src="//cdn.sportreview.it/radiantmp/latest/js/rmp.min.js"></script>


  <?php if (function_exists('adv_libs')) {
    adv_libs();
  } ?>


  <?php if (function_exists('adsense_vignetta')) {
    adsense_vignetta();
  } ?>


</head>


<body <?php body_class() ?> itemscope="itemscope" itemtype="<?php echo td_global::$http_or_https?>://schema.org/WebPage">


  <?php if (function_exists('adv_tag_manager_noscript')) {
    adv_tag_manager_noscript();
  } ?>


  <?php if (function_exists('adv_skin')) {
    adv_skin();
  } ?>



  <?php if (function_exists('adv_high_impact')) {
    adv_high_impact();
  } ?>


  <?php /* scroll to top */?>
  <div class="td-scroll-up"><i class="td-icon-menu-up"></i></div>

  <?php locate_template('parts/menu-mobile.php', true);?>
  <?php locate_template('parts/search.php', true);?>

<div class="contenitore"> <!-- contenitore fix skin, si chiude nel footer -->

  <div id="td-outer-wrap">
  <?php //this is closing in the footer.php file ?>

  <?php
  /*
   * loads the header template set in Theme Panel -> Header area
   * the template files are located in ../parts/header
   */
  td_api_header_style::_helper_show_header();

  do_action('td_wp_booster_after_header'); //used by unique articles
