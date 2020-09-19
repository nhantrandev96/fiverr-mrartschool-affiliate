<?php

class skrill {
	public $title = 'Skrill';
	public $icon = 'assets/images/payments/skrill.png';
	public $website = 'https://www.skrill.com';


	function __construct($api){ $this->api = $api; }

	public function getConfirm($data) {
		$action = 'https://www.moneybookers.com/app/payment.pl';

		$setting_data = $data['setting_data'];
		$order_info   = $data['order_info'];
		$products     = $data['products'];
		 
		$pay_to_email   = $setting_data['email'];
		//$platform       = '31974336';
		$description    = $order_info['firstname']." " .$order_info['lastname'];
		$transaction_id = time().'-'.$data['order_id'];
		$return_url     = site_url('store/thankyou/'. $data['order_id']);
		$cancel_url     = site_url('store/checkout');
		$status_url     = site_url('store/callbackfunctions/skrill/callback');
		$language       = 'en';
		$pay_from_email = $order_info['email'];
		$firstname      = $order_info['firstname'];
		$lastname       = $order_info['lastname'];
		$address        = $order_info['address'];
		$address2       = '';
		$phone_number   = $order_info['phone'];
		$postal_code    = $order_info['zip_code'];
		$city           = $order_info['city'];
		$state          = $order_info['state_name'];
		$country        = $order_info['country_code'];
		$amount         = $order_info['total'];
		$currency       = $order_info['currency_code'];

		$products = '';
		foreach ($products as $product) {
			$products .= $product['quantity'] . ' x ' . $product['product_name'] . ', ';
		}

		$detail1_text = $products;
		$order_id = $data['order_id'];

		$view = APPPATH.'payments/views/skrill.php';
		require $view;
	}

	public function callback(){
		if (isset($_POST['order_id'])) {
			$order_id = (int)$_POST['order_id'];
		} else {
			$order_id = 0;
		}

		$order_info   = $this->api->Order_model->getOrder($order_id, 'store');
		
		if ($order_info) {
			$setting_data = $this->api->Product_model->getSettings('storepayment_skrill');
			$verified = true;

			if ($setting_data['secret']) {
				$hash  = $_POST['merchant_id'];
				$hash .= $_POST['transaction_id'];
				$hash .= strtoupper(md5($setting_data['secret']));
				$hash .= $_POST['mb_amount'];
				$hash .= $_POST['mb_currency'];
				$hash .= $_POST['status'];

				$md5hash = strtoupper(md5($hash));
				$md5sig = $_POST['md5sig'];

				if (($md5hash != $md5sig) || (strtolower($_POST['pay_to_email']) != strtolower($setting_data['email'])) || ((float)$_POST['amount'] != (float)$order_info['total'])) {
					$verified = false;
				}
			}

			if ($verified) {
				switch($_POST['status']) {
					case '2'  : $status_id = $setting_data['order_status']; break;
					case '0'  : $status_id = $setting_data['pending_status']; break;
					case '-1' : $status_id = $setting_data['canceled_status']; break;
					case '-2' : $status_id = $setting_data['failed_status']; break;
					case '-3' : $status_id = $setting_data['chargeback_status']; break;
				}

				$this->api->confirm_order_api($order_id,$status_id,$_POST['transaction_id']);
			} else {
				
			}
		}
	}

	public function getMethod($data){
		return array(
			'html' => '<h3>Credit Card / Debit Card (Skrill)</h3>',
			'image' => '',
		);
	}
}