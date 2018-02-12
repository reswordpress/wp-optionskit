<?php
/**
 * WP OptionsKit.
 *
 * Copyright (c) 2018 Alessandro Tesoro
 *
 * WP OptionsKit. is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WP OptionsKit. is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * @author     Alessandro Tesoro
 * @version    1.0.0
 * @copyright  (c) 2018 Alessandro Tesoro
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU LESSER GENERAL PUBLIC LICENSE
 * @package    wp-optionskit
*/

namespace TDP;

// Make sure this file is only run from within WordPress.
defined( 'ABSPATH' ) or die();

class OptionsKit {
	/**
	 * Version of the class.
	 *
	 * @var string
	 */
	private $version = '1.0.0';

	/**
	 * The slug of the options panel.
	 *
	 * @var string
	 */
	private $slug;

	/**
	 * The slug for the function names of this panel.
	 *
	 * @var string
	 */
	private $func;

	/**
	 * The title of the page.
	 *
	 * @var string
	 */
	private $page_title;

	/**
	 * Get things started.
	 *
	 * @param boolean $slug
	 */
	public function __construct( $slug = false ) {

		if ( ! $slug ) {
			return;
		}

		$this->slug = $slug;
		$this->func = str_replace( '-', '_', $slug );

		$this->hooks();

	}

	/**
	 * Hook into WordPress and run things.
	 *
	 * @return void
	 */
	private function hooks() {

		add_action( 'admin_menu', array( $this, 'add_settings_page' ), 10 );
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );

	}

	/**
	 * Add settings page to the WordPress menu.
	 *
	 * @return void
	 */
	public function add_settings_page() {

		$menu = apply_filters( $this->func . '_menu', array(
			'parent'     => 'options-general.php',
			'page_title' => 'Settings Panel',
			'menu_title' => 'Settings Panel',
			'capability' => 'manage_options',
		) );

		$page = add_submenu_page(
			$menu['parent'],
			$menu['page_title'],
			$menu['menu_title'],
			$menu['capability'],
			$this->slug . '-settings',
			array( $this, 'render_settings_page' )
		);

	}

	/**
	 * Add a new class to the body tag.
	 * The class will be used to adjust the layout.
	 *
	 * @param string $classes
	 * @return void
	 */
	public function admin_body_class( $classes ) {

		$screen = get_current_screen();
		$check  = $this->slug . '-settings';

		if ( preg_match( "/{$check}/", $screen->base ) ) {
			$classes .= 'optionskit-panel-page';
		}

		return $classes;

	}

	/**
	 * Renders the settings page.
	 *
	 * @return void
	 */
	public function render_settings_page() {

		ob_start();
		include_once 'includes/views/settings-page.php';
		echo ob_get_clean();

	}

}