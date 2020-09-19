<?php

class bank_transfer {
	public $title = 'Bank Transfer';
	public $icon = 'assets/images/payments/bank-transfer.png';
	public $website = '';

	function __construct($api){ $this->api = $api; }

	public function getConfirm($data) {
		$setting_data = $data['setting_data'];
		$view = APPPATH.'payments/views/bank_transfer.php';
		require $view;
	}

	public function confirm($data) {
		$json['success'] = true;
		$json['redirect'] = $data['thankyou_url'];

		$setting = $this->api->Product_model->getSettings('storepayment_bank_transfer');

		$bank_details = [];
		$bank_details[] = $setting['bank_details'];
		if(isset($setting['additional_bank_details'])){
			$additional_bank_details = (array)json_decode($setting['additional_bank_details'],1);
			foreach ($additional_bank_details as $key => $value) {
				$bank_details[]= $value;
			}
		}

		$selected_index= (int)$this->api->session->userdata('bank_method_index');
		$selected_bank_details = isset($bank_details[$selected_index]) ? $bank_details[$selected_index] : '';

		$this->api->confirm_order_api($data['order_info']['id'],7,'',$selected_bank_details);

		return $json;
	}

	public function getMethod($data){
		return array(
			'html' => '<h3>Bank Transfer</h3>',
			'image' => '',
		);
	}
}