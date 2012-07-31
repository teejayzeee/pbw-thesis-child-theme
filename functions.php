<?php

if (! defined('ABSPATH'))
	die('Please do not directly access this file');

include_once(TEMPLATEPATH . '/functions.php');

class purelybalancedwellness_child_theme extends thesis_custom_loop {
		
	public function __construct() {
		parent::__construct();
		add_action('init', array($this, 'init'));
	}
	
	public function init() {
		// actions and filters that will run on init. you could put other things here if you need.
		$this->actions();
		$this->filters();
	}
	
	public function actions() {
		// add and remove actions here

		// this will force thesis to generate CSS when the user switches to the child
		add_action('after_switch_theme', 'thesis_generate_css');
		// needed to scale the site down on mobile
		add_action('wp_head', array($this, 'meta_tags'), 1);
	}
	
	public function filters() {
		// add and remove filters here
		
		/* 
		*	Filter out the standard thesis style.css. 
		*	Run this with a priority of 11 if you want to make sure the gravity forms css gets added.
		*/
		add_filter('thesis_css', array($this, 'css'), 11, 5);
	}
	
	public function css($contents, $thesis_css, $style, $multisite, $child) {
		
		// filter the Thesis generated css. in this example we're removing all the nav styles related to color
		$generated_css = $this->filter_css($thesis_css->css);
		
		/* 
		*	You can access the thesis_css object, which contains a variety of settings. 
		*/
		$responsive_css = "\n/*---:[ responsive resets ]:---*/\n"
			. "@media screen and (max-width: " . round((($thesis_css->widths['container'] + ($thesis_css->base['page_padding'] * 2)) / $thesis_css->base['num']) * 10, 1) . "px) {\n"
			. "\t.full_width > .page, #container, #page, #column_wrap, #content, #sidebars, #sidebar_1, #sidebar_2 { width: " . round((($thesis_css->widths['container']) / $thesis_css->base['num']) / 1.6, 1) . "em; }\n"
			. "\t#content_box, #column_wrap { background: none; }\n"
			. "\t#sidebar_1 { border: 0; }\n"
			. "\t#column_wrap, #content, #sidebars, #sidebar_1, #sidebar_2, .teaser { float: none; }\n"
			. "\t#comments { margin-right: 0; }\n"
			. "\t#multimedia_box #image_box img { height: auto; width: " . round(($thesis_css->widths['container'] - ($thesis_css->widths['mm_box_padding'] * 2)) / $thesis_css->base['num'] / 1.6, 1) . "em; }\n"
			. "\t.teasers_box { margin: 0 " .  round(($thesis_css->widths['post_box_margin_right'] / $thesis_css->base['num']) / 2, 1) . "em; padding-bottom: 0; }\n"
			. "\t.teasers_box, .teaser { width: auto; }\n"
			. "\t.teaser { padding-bottom: " . round(($thesis_css->line_heights['content'] / $thesis_css->base['num']), 1) . "em; }\n"
			. "\t.custom .wp-caption { width: auto!important; } /* overrides inline style */\n"
			. "}\n"
			. "\n@media screen and (max-width: " . round((($thesis_css->widths['container'] + ($thesis_css->base['page_padding'] * 2)) / $thesis_css->base['num']) * 10 / 1.6, 1) . "px) {\n"
			. "\t.full_width > .page, #page { padding: 0; }\n"
			. "\t.full_width > .page, #container, #page, #column_wrap, #content, #multimedia_box #image_box img, #sidebars, #sidebar_1, #sidebar_2 { width: 100%; }\n"
			. "\t.custom img.alignleft, .custom img.left, .custom img.alignright, .custom img.right, .custom img[align=\"left\"], .custom img[align=\"right\"] { display: block; margin-left: auto; margin-right: auto; float: none; clear: both; }\n"
			. "}\n";

		// put in everything except the main thesis style.css. also add an initial css reset
		$css = $thesis_css->fonts_to_import . $style . $this->css_reset . $generated_css . $child . $responsive_css;
		
		// return it
		return $css;
	}

	public function meta_tags()
	{
		// scales site for mobile devices
		echo '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">' . "\n";
		echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\n";
	}

	public function filter_css($css) {
		if (! empty($css)) {
			// you could add filtering here
		}		
		return $css;
	}
}

new purelybalancedwellness_child_theme;