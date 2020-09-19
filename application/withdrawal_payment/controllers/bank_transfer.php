<?php

class bank_transfer {
	public $title = 'Bank Transfer';
	public $icon = 'assets/images/withdrawal_payment/bank-transfer.png';
	public $website = '';

	function __construct($api){ $this->api = $api; }

	public function onInstall() {

	}

	public function onUnInstall() {
	}

	public function saveUserSubmit(){
		$data = $this->api->input->post(null,true);
		$json = [];

		if (!isset($data['bank_details']) || trim($data['bank_details']) == '') {
			$json['errors']['bank_details'] = "Bank details is required";
		}

		if (!isset($json['errors'])) {
			$this->api->load->model('Withdrawal_payment_model');
			$saveSetting = [
				'bank_details' => $data['bank_details'],
			];

			$status = $this->api->Withdrawal_payment_model->apiAddWithdrwalRequest($data['code'],$data['ids'],$saveSetting);
			
			if((int)$status['status'] == 1){
				$json['success'] = 1;
			} else{
				$json['errors']['bank_details'] = $status['error_message'];
			}
		}

		return $json;
	}
	
}