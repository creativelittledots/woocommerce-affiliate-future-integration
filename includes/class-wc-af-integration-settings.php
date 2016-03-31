<?php
/**
 * Integration Demo Integration.
 *
 * @package  WC_AF_Integration_Settings
 * @category Integration
 * @author   WooThemes
 */

if ( ! class_exists( 'WC_AF_Integration_Settings' ) ) :

class WC_AF_Integration_Settings extends WC_Integration {

	/**
	 * Init and hook in the integration.
	 */
	public function __construct() {
		global $woocommerce;

		$this->id                 = 'af';
		$this->method_title       = __( 'Affiliate Future&trade;', 'woocommerce-parkit-integration' );
		$this->method_description = __( 'Integration with Affiliate Future&trade;', 'woocommerce-parkit-integration' );

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables.
		$this->debug            = $this->get_option( 'debug' );

		// Actions.
		add_action( 'woocommerce_update_options_integration_' .  $this->id, array( $this, 'process_admin_options' ) );

	}


	/**
	 * Initialize integration settings form fields.
	 *
	 * @return void
	 */
	public function init_form_fields() {
		
		if( is_admin() ) {
			
			$fields = array('merchant_id' => array(
				'title' => __('Merchant ID', 'woocommerce-af-integration'),
				'type' => 'text',
				'description' => __('Please enter the Marchant ID for Affiliate Future', 'woocommerce-af-integration'),
				'desc_tip'          => true,
			));
			
			foreach(WC()->payment_gateways()->get_available_payment_gateways() as $gateway) {
    			
    			$fields['payment_gateway_' . $gateway->id] = array(
					'title' => __($gateway->id, 'woocommerce-af-integration'),
					'type' => 'checkbox',
					'descriptions' => __('Please choose the payment method(s) to track sales', 'woocommerce-af-integration'),
					'desc_tip'          => true,	
				);
    			
			}
			
			$fields['total_type'] = array(
				'title' => __('Total Type', 'woocommerce-af-integration'),
				'type' => 'select',
				'description' => __('Please choose weather the tracking should be of the total or subtotal (before tax)', 'woocommerce-af-integration'),
				'desc_tip'          => true,
				'default'           => '',
				'options' => array(
    				'total' => 'Total',
    				'subtotal' => 'Subtotal'
				)
			);
			
			$this->form_fields = apply_filters('woocoomerce_af_integration_settings_fields', $fields);	
			
		}
		
	}


}

endif;

?>