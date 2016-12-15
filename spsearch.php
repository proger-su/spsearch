<?php

/*
  Plugin Name: SP Search
  Description: Ajax search for wp
  Author: Sergey Pererva
  Plugin URI: http://proger.su
  Author URI: http://proger.su
  Version: 1.0
 */

defined('ABSPATH') or die();

class spsearch {

	function __construct() {
		//Scripts
		add_action('wp_enqueue_scripts', array($this, 'addScripts'));

		//Form shortcode
		add_shortcode('spsearch', array($this, 'addFormShortcode'));
		add_action('spsearch', function(){
			echo $this->addFormShortcode();
		}, 10, 0);

		//Form handler
		add_action('wp_ajax_ajax_search', array($this, 'registerSearchHandler'));
		add_action('wp_ajax_nopriv_ajax_search', array($this, 'registerSearchHandler'));
	}

	public function addScripts() {
		wp_enqueue_style('spsearch', plugin_dir_url(__FILE__) . 'css/spsearch.css');
		wp_enqueue_script('spsearch', plugin_dir_url(__FILE__) . 'js/spsearch.js', array('jquery'));
	}

	public function addFormShortcode() {
		return '<div class="sp-ajax-search"><input type="search"  class="search-field" placeholder="Search …" value="" name="s" autocomplete="off"></div>';
	}

	public function registerSearchHandler() {
		$s = filter_input(INPUT_GET, 's');

		$result = new WP_Query(array(
			's' => $s,
			'post_type' => array('post', 'page'),
			'posts_per_page' => 5,
		));

		if ($result->posts) {
			foreach ($result->posts as $item) {
				echo '<li><a href="' . get_the_permalink($item->ID) . '">' . $item->post_title . '</a></li>';
			}
			echo '<li><a class="all-results" href="' . get_search_link($s) . '">View all results</a></li>';
		}

		die();
	}

}

new spsearch;
