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
        
        add_action( 'woocommerce_init', array($this, 'maybe_set_cookie') );
        
        add_action( 'wp_login', array($this, 'maybe_destroy_cookie') );
        
        add_action( 'wp_logout', array($this, 'destroy_cookie' ) );
        
        add_action( 'init', array($this, 'add_payment_methods_action'), 20 );

	}
	
	public function maybe_set_cookie() {
        	
    	if( ! current_user_can( 'manage_woocommerce' ) && ! empty( $_REQUEST['adnetwork'] ) ) {
        	
        	wc_setcookie( 'adnetwork', $_REQUEST['adnetwork'], time() + 86400, true );
        	
        	wp_redirect( remove_query_arg('adnetwork') );
        	
        	exit();
        	
    	}

	}
	
	public function maybe_destroy_cookie() {
    	
    	if ( current_user_can( 'manage_woocommerce' ) ) {
        
            $this->destroy_cookie();
        	
        }
    	
	}
	
	public function destroy_cookie() {
    	
    	if ( isset( $_COOKIE['adnetwork'] ) ) {
        	
            unset( $_COOKIE['adnetwork'] );
            
            wc_setcookie('adnetwork', '', time() - 3600, '/'); // empty value and old timestamp
            
        }
    	
	}

    public function add_payment_methods_action() {
        
        if( $_COOKIE['adnetwork'] === 'AF' || 1 == 1 ) {
        	
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
            	
            	add_action( 'woocommerce_thankyou', array($this, 'print_image') );
            	
        	}
        	
        }
    	
	}
    
    public function print_image($order_id) {
        
        global $af_settings;
        
        if( $merchant_id = $af_settings['merchant_id'] ) {
        
            $order = wc_get_order($order_id);
            
            $url = $this->build_url(apply_filters('wc_af_sale_v2_query_args', array(
                'orderID' => $order->get_order_number(),
                'orderValue' => $af_settings['total_type'] === 'total' ? round($order->get_total(), 2) : round($order->get_subtotal(), 2),
                'merchant' => $merchant_id,
                'offlineCode' => '',
                'voucher' => implode(',', $order->get_used_coupons()),
                'payoutCodes' => '',
                'products' => '',
                'r' => '',
                'img' => 'yes'
            ), $af_settings, $order), '//scripts.affiliatefuture.com/AFSaleV2.asp');
            
            wc_get_template( 'image.php', compact('url'), '', WC_AF_Integration()->plugin_path() . '/templates/' );
            
        }
        
    }
    
    public function build_url($args, $url) {
        
        return $url . '?' . urldecode(http_build_query($args));
        
    }

}

endif;

?>