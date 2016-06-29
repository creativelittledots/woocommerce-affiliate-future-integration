<?php
/**
 *
 * @package  WC_AF_Integration_Functions
 * @category Integration
 * @author   WooThemes
 */

if ( ! class_exists( 'WC_AF_Integration_Functions' ) ) :

class WC_AF_Integration_Functions {

	/**
	 * Init and hook in the integration.
	 */
	public function __construct() {

        add_action( 'init', array($this, 'add_payment_methods_action'), 20 );

	}

    public function add_payment_methods_action() {
        	
    	global $af_settings;
    	
    	$payment_method_added = false;
    	
    	foreach($af_settings as $payment_method => $option) {
        	
        	if( strpos($payment_method, 'payment_gateway_') !== false && $option == 'yes' ) {
            	
            	$payment_method_added = true;
            	
            	add_action( 'woocommerce_thankyou_' . str_replace('payment_gateway_', '', $payment_method), array($this, 'add_payment_method_action') );
            	
            	add_action( 'woocommerce_thankyou_' . str_replace('payment_gateway_', '', $payment_method), array($this, 'print_image') );
            	
        	}
        	
    	}
    	
    	if( ! $payment_method_added ) {
        	
        	add_action( 'woocommerce_thankyou', array($this, 'add_payment_method_action') );
        	
        	add_action( 'woocommerce_thankyou', array($this, 'print_image') );
        	
    	}
    	
	}
	
    public function add_payment_method_action($order_id) {
        
        global $af_settings;
        
        if( $merchant_id = $af_settings['merchant_id'] ) {
            
            $order = wc_get_order($order_id);
        
            wp_enqueue_script( 'af-js', 'http://scripts.affiliatefuture.com/AFFunctions.js', array(), '1.0.0', true );
            
            wp_enqueue_script( 'af-js-process', WC_AF_Integration()->plugin_url() . '/assets/js/af-js-process.js', array('jquery', 'af-js'), '1.0.0', true );
            
            wp_localize_script( 'af-js-process', 'afOptions', array(
                'merchantID' => $merchant_id,
                'payoutCodes' => '',
                'offlineCode' => '',
                'orderValue' => $af_settings['total_type'] === 'total' ? round($order->get_total(), 2) : round($order->get_subtotal(), 2),
                'orderRef' => $order->get_order_number(),
            ));
            
            wp_enqueue_script( 'af-js-process' );
            
        }
    	 
    }
    
    public function print_image($order_id) {
        
        global $af_settings;
        
        if( $merchant_id = $af_settings['merchant_id'] ) {
        
            $order = wc_get_order($order_id);
            
            wc_get_template( 'image.php', array(
                'order_number' => $order->get_order_number(),
                'order_value' => $af_settings['total_type'] === 'total' ? round($order->get_total(), 2) : round($order->get_subtotal(), 2),
                'merchant_id' => $merchant_id
            ), '', WC_AF_Integration()->plugin_path() . '/templates/' );
            
        }
        
    }

}

endif;

?>