<?php

class cod {
	public $title = 'Cash On Delivery';
	public $icon = 'assets/images/payments/cod.png';
	public $website = '';

	function __construct($api){ $this->api = $api; }

	public function getConfirm($data) {
		$view = APPPATH.'payments/views/cod.php';
		require $view;
	}

	public function confirm($data) {
		$json['success'] = true;
		$json['redirect'] = $data['thankyou_url'];
		
		$this->api->confirm_order_api($data['order_info']['id'],7);
		return $json;
	}

	public function getMethod($data){
		return array(
			'html' => '<h3>Cash On Delivery</h3>',
		);
	}
}