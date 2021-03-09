<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       mailto:ialvsconcelos@gmail.com
 * @since      1.0.0
 *
 * @package    WC_Br_Shipping_Only_With_Cep
 * @subpackage WC_Br_Shipping_Only_With_Cep/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    WC_Br_Shipping_Only_With_Cep
 * @subpackage WC_Br_Shipping_Only_With_Cep/public
 * @author     Alvaro Vasconcelos - @alvsconcelos <mailto:ialvsconcelos@gmail.com>
 */
class WC_Br_Shipping_Only_With_Cep_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		// Load inline styles only if is cart.
		if (!is_cart()) return;

		// Register a dummy stylesheet and loads it to insert inline styles on cart.
		wp_register_style($this->plugin_name, false);
		wp_enqueue_style($this->plugin_name);
		wp_add_inline_style($this->plugin_name, '.shipping-calculator-form{display:block!important; padding-top:0 !important;}.shipping-calculator-button {display:none!important;}');

		if ('yes' === get_option('shippingonlywithcep_mask')) {
			wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/shipping-only-with-cep.min.js', array('jquery'), $this->version, false);
		}
	}

	public function apply_correct_state()
	{
		$country = 'BR';
		$cep = isset($_POST['calc_shipping_postcode']) ? wc_clean($_POST['calc_shipping_postcode']) : '';
		$cep = wc_format_postcode($cep, $country);
		$state = $this->get_state_by_cep($cep);

		if (!empty($state) && !empty($cep)) {
			WC()->shipping->reset_shipping();
			WC()->customer->set_location($country, $state, $cep, '');
			WC()->customer->save();
		}
	}

	public function get_state_by_cep($cep)
	{
		// Faixas de cep: https://help.commerceplus.com.br/hc/pt-br/articles/115008224967-Faixas-de-CEP-por-Estado
		// HTML Table to JSON: https://www.convertjson.com/html-table-to-json.htm
		// JSON to PHP Array: https://wtools.io/convert-json-to-php-array

		$cep_ranges = array(
			'Acre(AC)' =>
			array(
				'state' => 'AC',
				'min' => '69900000',
				'max' => '69999999',
			),
			'Alagoas(AL)' =>
			array(
				'state' => 'AL',
				'min' => '57000000',
				'max' => '57999999',
			),
			'Amazonas(AM) 1' =>
			array(
				'state' => 'AM',
				'min' => '69000000',
				'max' => '69299999',
			),
			'Amazonas(AM) 2' =>
			array(
				'state' => 'AM',
				'min' => '69400000',
				'max' => '69899999',
			),
			'Amapá(AP)' =>
			array(
				'state' => 'AP',
				'min' => '68900000',
				'max' => '68999999',
			),
			'Bahia(BA)' =>
			array(
				'state' => 'BA',
				'min' => '40000000',
				'max' => '48999999',
			),
			'Ceará(CE)' =>
			array(
				'state' => 'CE',
				'min' => '60000000',
				'max' => '63999999',
			),
			'Distrito Federal(DF) 1' =>
			array(
				'state' => 'DF',
				'min' => '70000000',
				'max' => '72799999',
			),
			'Distrito Federal(DF) 2' =>
			array(
				'state' => 'DF',
				'min' => '73000000',
				'max' => '73699999',
			),
			'Espírito Santo(ES)' =>
			array(
				'state' => 'ES',
				'min' => '29000000',
				'max' => '29999999',
			),
			'Goiás(GO) 1' =>
			array(
				'state' => 'GO',
				'min' => '72800000',
				'max' => '72999999',
			),
			'Goiás(GO) 2' =>
			array(
				'state' => 'GO',
				'min' => '73700000',
				'max' => '76799999',
			),
			'Maranhão(MA)' =>
			array(
				'state' => 'MA',
				'min' => '65000000',
				'max' => '65999999',
			),
			'Minas Gerais(MG)' =>
			array(
				'state' => 'MG',
				'min' => '30000000',
				'max' => '39999999',
			),
			'Mato Grosso do Sul(MS)' =>
			array(
				'state' => 'MS',
				'min' => '79000000',
				'max' => '79999999',
			),
			'Mato Grosso(MT)' =>
			array(
				'state' => 'MT',
				'min' => '78000000',
				'max' => '78899999',
			),
			'Pará(PA)' =>
			array(
				'state' => 'PA',
				'min' => '66000000',
				'max' => '68899999',
			),
			'Paraíba(PB)' =>
			array(
				'state' => 'PB',
				'min' => '58000000',
				'max' => '58999999',
			),
			'Pernambuco(PE)' =>
			array(
				'state' => 'PE',
				'min' => '50000000',
				'max' => '56999999',
			),
			'Piauí(PI)' =>
			array(
				'state' => 'PI',
				'min' => '64000000',
				'max' => '64999999',
			),
			'Paraná(PR)' =>
			array(
				'state' => 'PR',
				'min' => '80000000',
				'max' => '87999999',
			),
			'Rio de Janeiro(RJ)' =>
			array(
				'state' => 'RJ',
				'min' => '20000000',
				'max' => '28999999',
			),
			'Rio Grande do Norte(RN)' =>
			array(
				'state' => 'RN',
				'min' => '59000000',
				'max' => '59999999',
			),
			'Rondônia(RO)' =>
			array(
				'state' => 'RO',
				'min' => '76800000',
				'max' => '76999999',
			),
			'Roraima(RR)' =>
			array(
				'state' => 'RR',
				'min' => '69300000',
				'max' => '69399999',
			),
			'Rio Grande do Sul(RS)' =>
			array(
				'state' => 'RS',
				'min' => '90000000',
				'max' => '99999999',
			),
			'Santa Catarina(SC)' =>
			array(
				'state' => 'SC',
				'min' => '88000000',
				'max' => '89999999',
			),
			'Sergipe(SE)' =>
			array(
				'state' => 'SE',
				'min' => '49000000',
				'max' => '49999999',
			),
			'São Paulo(SP)' =>
			array(
				'state' => 'SP',
				'min' => '01000000',
				'max' => '19999999',
			),
			'Tocantins(TO)' =>
			array(
				'state' => 'TO',
				'min' => '77000000',
				'max' => '77999999',
			),
		);

		foreach ($cep_ranges as $range) {
			if ($this->cep_is_between($cep, $range['min'], $range['max'])) {
				return $range['state'];
			}
		}
	}

	public function cep_is_between($cep, $min, $max)
	{
		$cep = preg_replace('([^0-9])', '', sanitize_text_field($cep));
		return ($cep >= $min && $cep <= $max);
	}
}
