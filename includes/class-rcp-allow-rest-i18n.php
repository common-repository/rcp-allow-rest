<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.maxizone.fr
 * @since      1.0.0
 *
 * @package    Rcp_Allow_Rest
 * @subpackage Rcp_Allow_Rest/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Rcp_Allow_Rest
 * @subpackage Rcp_Allow_Rest/includes
 * @author     Termel <admin@termel.fr>
 */
class Rcp_Allow_Rest_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'rcp-allow-rest',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
