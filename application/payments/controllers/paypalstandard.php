<?php

class paypalstandard {
	public $title = 'Paypal Standard';
	public $icon = 'assets/images/payments/paypal.png';
	public $website = 'https://www.paypal.com/';
	function __construct($api){ $this->api = $api; }

	public function getConfirm($data) {
		extract($data);

		if ($setting_data['sandbox_mode']) {
			$action = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		} else {
			$action = 'https://www.paypal.com/cgi-bin/webscr';
		}
		$custom = $this->api->session->session_id;

		$return =  $this->api->cart->getStoreUrl('thankyou/'. $order_id );
		$notify_url =  site_url('store/callbackfunctions/paypalstandard/cb/notify/' . $order_id);
		$cancel_return =  site_url('store/callbackfunctions/paypalstandard/cb/cancel_return/' . $order_id);
		
		if ((int)$setting_data['transaction'] == 0) {
			$paymentaction = 'authorization';
		} else {
			$paymentaction = 'sale';
		}


		$view = APPPATH.'payments/views/paypalstandard.php';
		require $view;
	}

	public function cb($action, $order_id){
		
		if($action == 'cancel_return'){
			
		} else if($action == 'notify'){
			$this->api->load->model("Order_model");
			$this->api->load->model("Product_model");
			$order_info = $this->api->Order_model->getOrder((int)$order_id, 'store');
			$setting_data = $this->api->Product_model->getSettings('storepayment_paypalstandard');

			if ($order_info) {
				$request = 'cmd=_notify-validate';
				$post = $this->api->input->post(null);

				foreach ($_POST as $key => $value) {
					$request .= '&' . $key . '=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
				}
				$response_log = '';
				if ($setting_data['sandbox_mode']) {
					$curl = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
					$response_log .= 'https://www.sandbox.paypal.com/cgi-bin/webscr';
				} else {
					$curl = curl_init('https://www.paypal.com/cgi-bin/webscr');
					$response_log .= 'https://www.paypal.com/cgi-bin/webscr';
				}

				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_HEADER, false);
				curl_setopt($curl, CURLOPT_TIMEOUT, 30);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

				$response = curl_exec($curl);

				
				if (!$response) {
					$response_log .= ' | PP_STANDARD :: CURL failed ' . curl_error($curl) . '(' . curl_errno($curl) . ')';
				}
				
				$response_log .= ' | PP_STANDARD :: IPN REQUEST: ' . $request;
				$response_log .= ' | PP_STANDARD :: IPN RESPONSE: ' . $response;

				//file_put_contents('./pp_logs.txt', json_encode($_POST) .$action  .$response_log.PHP_EOL , FILE_APPEND | LOCK_EX); 
				
				$order_status_id = 0;
				if ((strcmp($response, 'VERIFIED') == 0 || strcmp($response, 'UNVERIFIED') == 0) && isset($post['payment_status'])) {

					switch($post['payment_status']) {
						case 'Canceled_Reversal' : $order_status_id = $setting_data['canceled_reversal_status_id']; break;
						case 'Completed'         : $order_status_id = $setting_data['completed_status_id']; break;
						case 'Denied'            : $order_status_id = $setting_data['denied_status_id']; break;
						case 'Expired'           : $order_status_id = $setting_data['expired_status_id']; break;
						case 'Failed'            : $order_status_id = $setting_data['failed_status_id']; break;
						case 'Pending'           : $order_status_id = $setting_data['pending_status_id']; break;
						case 'Processed'         : $order_status_id = $setting_data['processed_status_id']; break;
						case 'Refunded'          : $order_status_id = $setting_data['refunded_status_id']; break;
						case 'Reversed'          : $order_status_id = $setting_data['reversed_status_id']; break;
						case 'Voided'            : $order_status_id = $setting_data['voided_status_id']; break;
					}

				} 

				$this->api->confirm_order_api($order_id,$order_status_id,$post['txn_id']);
				
				curl_close($curl);
			}
		}
	}

	public function getMethod($data){
		return array(
			'html' => '<h3>Paypal Standard</h3>',
			'image' => '',
		);
	}

	public function confirm($data) {
		$order        = $data['order_info'];
		$cart_product = $data['products'];
		$order_id     = $order['id'];
		$setting      = $this->api->Product_model->getSettings('storepayment_paypal');
		$setting_site = $this->api->getSettings('site');
		 
		$config = array(
            'Sandbox'      => $setting['payment_mode'] == 'sandbox' ? TRUE : FALSE,
            'APIUsername'  => $setting['api_username'],
            'APIPassword'  => $setting['api_password'],
            'APISignature' => $setting['api_signature'],
            'APISubject'   => '',
            'APIVersion'   => '98.0' ,
        );

        $this->api->load->library('paypal/Paypal_pro', $config);

        $SECFields = array(
			'returnurl' => site_url('store/callbackfunctions/paypal/notify/' . $order_id), 
			'cancelurl' => site_url('store/callbackfunctions/paypal/paypal_cancel/' . $order_id), 
			'brandname' => $setting_site['name'],
			'hdrimg'    => ''
        );

        $Payments = array();
        $PaymentOrderItems = array();
        foreach ($cart_product as $key => $product) {
            $Item = array(
				'name'    => $product['product_name'],
				'desc'    => $product['product_name'],
				'amt'     => c_format($product['total'],false),
				'number'  => $product['product_id'],
				'qty'     => 1,
				'taxamt'  => 0,
				'itemurl' => '', 
            );
            array_push($PaymentOrderItems, $Item);
       	}
       
        $Payment = array(
			'order_items'  => $PaymentOrderItems,
			'amt'          => c_format($order['total'],false),
			'itemamt'      => c_format($order['total'],false),
			'currencycode' => $_SESSION['userCurrency'],
        );

        array_push($Payments, $Payment);
        $PayPalRequestData = array(
			'SECFields' => $SECFields,
			'Payments'  => $Payments,
        );

        $PayPalResult = $this->api->paypal_pro->SetExpressCheckout($PayPalRequestData);
        
        if (isset($PayPalResult['ACK']) && $this->api->paypal_pro->APICallSuccessful($PayPalResult['ACK'])) {
            $json['redirect'] = $PayPalResult['REDIRECTURL'];
            $this->api->cart->clearCart();
        } else {
            $json['warning'] = isset($PayPalResult['ERRORS'][0]['L_LONGMESSAGE']) ? $PayPalResult['ERRORS'][0]['L_LONGMESSAGE'] : '';
        }

		return $json;
	}
}