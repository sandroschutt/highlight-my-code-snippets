<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://https://github.com/sandroschutt
 * @since      1.0.0
 *
 * @package    Dev_Code_Snippets
 * @subpackage Dev_Code_Snippets/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Dev_Code_Snippets
 * @subpackage Dev_Code_Snippets/includes
 * @author     Sandro Schutt <sandro@email.com>
 */
class Dev_Code_Snippets_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'dev-code-snippets',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
