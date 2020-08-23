<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       mailto:ialvsconcelos@gmail.com
 * @since      1.0.0
 *
 * @package    WC_Br_Shipping_Only_With_Cep
 * @subpackage WC_Br_Shipping_Only_With_Cep/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    WC_Br_Shipping_Only_With_Cep
 * @subpackage WC_Br_Shipping_Only_With_Cep/includes
 * @author     Alvaro Vasconcelos - @alvsconcelos <mailto:ialvsconcelos@gmail.com>
 */
class WC_Br_Shipping_Only_With_Cep_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wc-br-shipping-only-with-cep',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
