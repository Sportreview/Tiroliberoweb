<?php
/**
 * Created by ra.
 * Date: 9/2/2015
 * CSS generator for this specific demo
 */


function td_css_demo_gen() {
	$td_demo_custom_css = "
	<style>
		/* @theme_color */
		.td-book-club-demo .sf-menu > .current-menu-item > a,
        .td-book-club-demo .sf-menu > .current-menu-ancestor > a,
        .td-book-club-demo .sf-menu > .current-category-ancestor > a,
        .td-book-club-demo .sf-menu > li:hover > a,
        .td-book-club-demo .sf-menu > .sfHover > a {
		 	color: @theme_color !important;
		 }

	</style>
	";

	$td_demo_css_compiler = new td_css_compiler($td_demo_custom_css);


	$td_demo_css_compiler->load_setting('theme_color');

	return $td_demo_css_compiler->compile_css();
}
