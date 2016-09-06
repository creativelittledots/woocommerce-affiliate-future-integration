## WooCommerce Affiliate Future Integration

Affiliate Future integration with Woocommerce, allowing shop owners to track sales via tracking on the Woocommerce Order Received page

Simply set you your Affiliate Future settings in **WooCommerce > Settings > Integrations > Affiliate Future** and all of the required order information will back tracked back to your Affiliate Future account.

Currently using the AFSaleV2 facility, there is a filter provided that you can use called **wc_af_sale_v2_query_args** to customise the parameters in the URL should you need to customise some the parameters:

```php
$url = add_query_arg(apply_filters('wc_af_sale_v2_query_args', array(
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
```

Which can be used in functions.php like this:

```php
add_filter( 'wc_af_sale_v2_query_args', 'custom_af_args', 10, 3 );

function custom_af_args( $args, $af_settings, $order ) {
	
	return array_merge($args, array(
		'some_arbitrary' => 'text'
	));
	
	// results in a request to http://scripts.affiliatefuture.com/AFSaleV2.asp?orderID={{order_id}}&orderValue={{order_value}}&merchant={{merchant_id}}&voucher={{voucher_codes}}payoutCodes=&offlineCode=&r=&img=yes&some_arbitrary=text
	
}
```

## Installation

1. Upload the plugin to the **/wp-content/plugins/** directory
2. Activate the plugin through the 'Plugins' menu in WordPress

## Requirements

An account with [Affiliate Future](http://www.affiliatefuture.co.uk/register/publishers)

PHP 5.4+

Wordpress 4+

WooCommerce 2.5+

## License

[GNU General Public License v3.0](http://www.gnu.org/licenses/gpl-3.0.html)