<?php
/*
Plugin Name: CFS Options Pages
Plugin URI: https://www.vanpattenmedia.com/
Description: Create custom options pages for use with Custom Field Suite
Version: 1.0
Author: Van Patten Media Inc.
Author URI: https://www.vanpattenmedia.com/
*/

class CfsOptions {
	public $singular = 'Options Page';
	public $plural   = 'Options Pages';

	public $screen;

	public $options_pages = array(
		'Options',
	);

	public function __construct() {
		add_action( 'init',                  array( $this, 'register_post_type' ) );
		add_action( 'init',                  array( $this, 'add_options_pages' ) );
		add_action( 'admin_menu',            array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_cleanup' ) );

		add_filter( 'post_type_labels_cfs_options', array( $this, 'rewrite_labels' ) );
	}

	public function register_post_type() {
		if ( ! post_type_exists( 'cfs_options' ) ) {
			$args = array(
				'public'          => false,
				'show_ui'         => true,
				'show_in_menu'    => false,
				'capability_type' => 'page',
				'query_var'       => false,
				'menu_icon'       => 'dashicons-admin-settings',
				'supports'        => array(
					'title',
					'revisions',
				),
				'labels'          => array(
					'name'               => 'Options',
					'all_items'          => 'All ' . $this->plural,
					'add_new'            => 'New ' . $this->singular,
					'add_new_item'       => 'New ' . $this->singular,
					'edit_item'          => 'Edit ',
					'new_item'           => 'New ' . $this->singular,
					'view_item'          => 'View ' . $this->singular,
					'search_item'        => 'Search ' . $this->plural,
					'not_found'          => 'No ' . $this->plural . ' found',
					'not_found_in_trash' => 'No ' . $this->plural . ' found in Trash',
				),
			);

			register_post_type( 'cfs_options', $args );
		}
	}

	public function page( $title = 'Options' ) {
		$options_page = get_page_by_title( $title, 'OBJECT', 'cfs_options' );

		if ( is_null( $options_page ) ) {
			return false;
		} else {
			return $options_page->ID;
		}
	}

	public function admin_menu() {
		// Get the first options page
		$main_options_page_title = reset( $this->options_pages );

		// If we have one options page...
		if ( $main_options_page_id = $this->page( $main_options_page_title ) ) {
			// Add the top level item
			add_menu_page(
				$main_options_page_title,
				$main_options_page_title,
				'edit_posts',
				'/post.php?post=' . $main_options_page_id . '&action=edit'
			);

			// Then add the sub-items
			foreach ( $this->options_pages as $secondary_options_page_title ) {
				// If the secondary page has been created...
				if ( $secondary_options_page_id = $this->page( $secondary_options_page_title ) ) {
					// Add its submenu page
					add_submenu_page(
						'/post.php?post=' . $main_options_page_id . '&action=edit',
						$secondary_options_page_title,
						$secondary_options_page_title,
						'edit_posts',
						'/post.php?post=' . $secondary_options_page_id . '&action=edit'
					);
				}
			}
		}
	}

	public function add_options_pages() {
		$this->options_pages = apply_filters( 'cfs_options_pages', $this->options_pages );

		// Loop through the defined pages
		foreach ( $this->options_pages as $options_page ) {
			// If the page does not exist...
			if ( ! $this->page( $options_page ) ) {
				// ...create it!
				$options_page = array(
					'post_title'     => $options_page,
					'post_type'      => 'cfs_options',
					'post_status'    => 'private',
					'ping_status'    => 'closed',
					'comment_status' => 'closed',
				);

				wp_insert_post( $options_page );
			}
		}
	}

	public function admin_cleanup() {
		$this->screen = get_current_screen();

		if ( $this->screen->post_type == 'cfs_options' ) {
			wp_enqueue_style( 'cfs_options_cleanup', plugins_url( 'assets/style.css', __FILE__ ) );
		}
	}

	public function rewrite_labels( $labels ) {
		if ( ! isset( $_GET['post'] ) )
			return $labels;

		if ( ! is_numeric( $postID = $_GET['post'] ) )
			return $labels;

		$labels->edit_item .= get_the_title( $postID );
		return $labels;
	}
}

function setup_cfs_options() {
	if ( function_exists( 'CFS' ) && class_exists( 'CfsOptions' ) ) {
		CFS()->options = new CfsOptions;
	}
}

add_action( 'plugins_loaded', 'setup_cfs_options' );
