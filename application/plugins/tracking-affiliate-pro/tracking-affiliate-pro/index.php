<?php
/*
Plugin Name: Tracking affiliate Pro
Plugin URI: 
Description: Tracking affiliate Pro.
Author: All4Bussiness
Version: 1.0.0
Author URI: https://affiliatepro.org/
*/

add_action('init','affi_init_fun');
function affi_init_fun(){
	add_action('wp_head','affi_wp_head_fun',10);
	add_action('woocommerce_thankyou','affi_woocommerce_thankyou_fun');
	add_action( "woocommerce_before_single_product", "fun_affiliate", 10 );
}
function affi_wp_head_fun(){ ?>
	<script type="text/javascript" src="__baseurl__integration/script"></script>
<?php }

if(!function_exists('call_affiliate_api')){
	function call_affiliate_api($endpoint, $data){
		global $wp;
	    $data['current_page_url'] = base64_encode(home_url( $wp->request ));
		$context_options = stream_context_create(array(
			'http'=>array(
				'method'=>"GET",
				'header'=> "User-Agent: ". $_SERVER['HTTP_USER_AGENT'] ."\r\n"."Referer: ". $current_page_url ."\r\n"
			)
		));
		
		file_get_contents("__baseurl__{$endpoint}?".http_build_query($data), false, $context_options);
	}
}

function affi_woocommerce_thankyou_fun($order_ID){ 
	$order = new WC_Order($order_ID);

	$ipaddress = "";
	if (getenv("HTTP_CLIENT_IP")) $ipaddress = getenv("HTTP_CLIENT_IP");
	else if(getenv("HTTP_X_FORWARDED_FOR")) $ipaddress = getenv("HTTP_X_FORWARDED_FOR");
	else if(getenv("HTTP_X_FORWARDED")) $ipaddress = getenv("HTTP_X_FORWARDED");
	else if(getenv("HTTP_FORWARDED_FOR")) $ipaddress = getenv("HTTP_FORWARDED_FOR");
	else if(getenv("HTTP_FORWARDED")) $ipaddress = getenv("HTTP_FORWARDED");
	else if(getenv("REMOTE_ADDR")) $ipaddress = getenv("REMOTE_ADDR");
	else $ipaddress = "UNKNOWN";
	
	$affiliateData = array(
		"order_id"       => $order->get_id(),
		"order_currency" => $order->get_order_currency(),
		"order_total"    => ($order->order_total - $order->order_shipping - $order->total_tax),
		"product_ids"    => array(),
		"af_id"          => $_COOKIE["af_id"],
		"ip"             => $ipaddress,
		"base_url"       => base64_encode(get_site_url()),
		"script_name"    => "woocommerce",
	);
	foreach ($order->get_items() as $item) {
		$_product = $order->get_product_from_item($item);
		$affiliateData["product_ids"][] = $_product->id;
	}

	call_affiliate_api('integration/addOrder', $affiliateData);
}

function fun_affiliate(){
	global $post;

	if($post && $post->post_type == "product" && is_single()){
		$ipaddress = "";
	    if (getenv("HTTP_CLIENT_IP")) $ipaddress = getenv("HTTP_CLIENT_IP");
	    else if(getenv("HTTP_X_FORWARDED_FOR")) $ipaddress = getenv("HTTP_X_FORWARDED_FOR");
	    else if(getenv("HTTP_X_FORWARDED")) $ipaddress = getenv("HTTP_X_FORWARDED");
	    else if(getenv("HTTP_FORWARDED_FOR")) $ipaddress = getenv("HTTP_FORWARDED_FOR");
	    else if(getenv("HTTP_FORWARDED")) $ipaddress = getenv("HTTP_FORWARDED");
	    else if(getenv("REMOTE_ADDR")) $ipaddress = getenv("REMOTE_ADDR");
	    else $ipaddress = "UNKNOWN";

		$affiliateData = array(
			"product_id"  => $post->ID,
			"af_id"       => $_COOKIE["af_id"],
			"ip"          => $ipaddress,
			"base_url"    => base64_encode(get_site_url()),
		    "script_name" => "woocommerce",
		);
		
		call_affiliate_api('integration/addClick', $affiliateData);
	}
}