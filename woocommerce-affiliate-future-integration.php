<?php
/*
* Plugin Name: WooCommerce Affiliate Future Integration
* Description: A plugin built for Affiliate Future, allowing Wordpress & Woocommerce blog owners to track sales on the Woocommerce Order Thank You page.
* Version: 1.0
* Author: Creative Little Dots
* Author URI: http://creativelittledots.co.uk
* Text Domain: woocommerce-affiliate-future-integration
* Domain Path: /languages/
*
* Requires at least: 3.8
* Tested up to: 4.1.1
*
* Copyright: © 2009-2015 Creative Little Dots
* License: GNU General Public License v3.0
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! class_exists( 'WC_AF_Integration' ) ) :

    class WC_AF_Integration {
        
        /**
    	 * The single instance of the class.
    	 *
    	 */
        protected static $_instance = null;
        
        /**
    	 * Main Instance.
    	 *
    	 * Ensures only one instance of WooCommerce is loaded or can be loaded.
    	 *
    	 * @since 2.1
    	 * @static
    	 * @see WC()
    	 * @return WooCommerce - Main instance.
    	 */
    	public static function instance() {
    		if ( is_null( self::$_instance ) ) {
    			self::$_instance = new self();
    		}
    		return self::$_instance;
    	}
    
    	/**
    	* Construct the plugin.
    	*/
    	public function __construct() {
    		
    		add_action( 'plugins_loaded', array( $this, 'init' ) );
    		
    		add_action( 'init', array($this, 'global_af_settings') );
    		
    	}
    	
    	public function plugin_url() {
    		
    		return plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
    		
    	}
    
    	public function plugin_path() {
    		
    		return untrailingslashit( plugin_dir_path( __FILE__ ) );
    		
    	}
    
    	/**
    	* Initialize the plugin.
    	*/
    	public function init() {
    
    		// Checks if WooCommerce is installed.
    		if ( class_exists( 'WC_AF_Integration' ) ) {
    			
    			// Include our classes.
    			include_once 'includes/class-wc-af-integration-settings.php';
    			include_once 'includes/class-wc-af-integration-functions.php';
    			
    			$this->functions = new WC_AF_Integration_Functions();
    
    			// Register the integration.
    			add_filter( 'woocommerce_integrations', array( $this, 'add_integration' ) );
    			
    		} else {
    			
    			// throw an admin error if you like
    			
    		}
    		
    	}
    	
    	public function global_af_settings() {
    		
    		$GLOBALS['af_settings'] = array_merge(array(
        		'merchant_id' => '',
        		'total_type' => 'total',
    		), is_array(get_option('woocommerce_af_settings')) ? get_option('woocommerce_af_settings') : array());
    		
    	}
    
    	/**
    	 * Add a new integration to WooCommerce.
    	 */
    	public function add_integration( $integrations ) {
    		
    		$integrations[] = 'WC_AF_Integration_Settings';
    		
    		return $integrations;
    		
    	}
    	
    	
    
    }
    
    function WC_AF_Integration() {
        return WC_AF_Integration::instance();
    }
    
    WC_AF_Integration();

endif;

?>