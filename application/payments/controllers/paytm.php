<?php

class paytm {
	public $title = 'Paytm';
	public $icon = 'assets/images/payments/paytm.png';
	public $website = 'https://paytm.com/';


	function __construct($api){ $this->api = $api; }

	public function getConfirm($data) {
		require_once(APPPATH . 'core/paytm/encdec_paytm.php');
		$setting_data = $data['setting_data'];
		$order_info = $data['order_info'];
	
		$mobile_no = "";
		if($order_info['phone']){
			$mobile_no = preg_replace('/\D/', '', $order_info['phone']);
		}

		$cust_id = "";
		$email = "";
		if(isset($order_info['email']) && trim($order_info['email']) != ""){
			$cust_id = $email = $order_info['email'];
		} else if($order_info['user_id'] > 0){
			$cust_id = $order_info['user_id'];
		}

		$amount = $order_info['total'];

		$parameters = array(
			"MID"              => $setting_data['merchant_id'],
			"WEBSITE"          => $setting_data['website_name'],
			"INDUSTRY_TYPE_ID" => $setting_data['industry_type'],
			"CALLBACK_URL"     => base_url('store/callbackfunctions/paytm/callback'),
			"ORDER_ID"         => $order_info['id'],
			"CHANNEL_ID"       => "WEB",
			"CUST_ID"          => $cust_id,
			"TXN_AMOUNT"       => $amount,
			"MOBILE_NO"        => $mobile_no,
			"EMAIL"            => $email,
		);

		$parameters["CHECKSUMHASH"] = getChecksumFromArray($parameters, $setting_data['merchant_key']);
		$action = $setting_data['transaction_url'];

		$view = APPPATH.'payments/views/paytm.php';
		require $view;
	}

	public function callback() {
		require_once(APPPATH . 'core/paytm/encdec_paytm.php');

		$setting_data    = $this->api->Product_model->getSettings('storepayment_paytm');
		$isValidChecksum = false;
		$txnstatus       = false;
		$authStatus      = false;

		if(isset($_POST['CHECKSUMHASH'])) {
			$checksum = htmlspecialchars_decode($_POST['CHECKSUMHASH']);
			$return = verifychecksum_e($_POST, $setting_data['merchant_key'], $checksum);
			if($return == "TRUE") $isValidChecksum = true;
		}

		$order_id = isset($_POST['ORDERID']) && !empty($_POST['ORDERID'])? $_POST['ORDERID'] : 0;
		$order_info   = $this->api->Order_model->getOrder((int)$order_id, 'store');
		if(isset($_POST['STATUS']) && $_POST['STATUS'] == "TXN_SUCCESS") {
			$txnstatus = true;
		}

		if ($order_info){
			if ($txnstatus && $isValidChecksum) {
				$reqParams = array(
					"MID"     => $setting_data['merchant_id'],
					"ORDERID" => $order_id
				);
				
				$reqParams['CHECKSUMHASH'] = getChecksumFromArray($reqParams, $setting_data['merchant_key']);		
				$resParams = callNewAPI($setting_data['transaction_status_url'], $reqParams);

				if($resParams['STATUS'] == 'TXN_SUCCESS' && $resParams['TXNAMOUNT'] == $_POST['TXNAMOUNT']) {
					$this->api->confirm_order_api($order_id,$setting_data['order_success_status_id'],$_POST['TXNID']);
					redirect($this->api->cart->getStoreUrl('thankyou/'. $order_id ));die;
				} else {
					$this->api->confirm_order_api($order_id,$setting_data['order_failed_status_id'],$_POST['TXNID']);
					redirect($this->api->cart->getStoreUrl('thankyou/'. $order_id ));die;
				}
				
			} else {
				$this->api->confirm_order_api($order_id,$setting_data['order_failed_status_id'],'');
				redirect($this->api->cart->getStoreUrl('thankyou/'. $order_id ));die;
			}
		}

		redirect($this->api->cart->getStoreUrl('checkout/'));die;
	}

	public function getMethod($data){
		return array(
			'html' => '<h3>Paytm</h3>',
		);
	}
}