<?php

class paypal {
	public $title = 'Paypal';
	public $icon = 'assets/images/payments/paypal.png';
	public $website = 'https://www.paypal.com/';


	function __construct($api){ $this->api = $api; }

	public function getConfirm($data) {
		$base_url = base_url();
		$view = APPPATH.'payments/views/paypal.php';
		require $view;
	}

	public function getMethod($data){
		return array(
			'html' => '<h3>Paypal</h3>',
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

	public function paypal_cancel($order_id){
		redirect('store/checkout');
	}

	public function notify($order_id){
		$this->api->load->model("Order_model");
		$this->api->load->model("Product_model");
		$order_info = $this->api->Order_model->getOrder($order_id, 'store');
		$setting = $this->api->Product_model->getSettings('storepayment_paypal');

		$config = array(
            'Sandbox'      => $setting['payment_mode'] == 'sandbox' ? TRUE : FALSE,
            'APIUsername'  => $setting['api_username'],
            'APIPassword'  => $setting['api_password'],
            'APISignature' => $setting['api_signature'],
            'APISubject'   => '',
            'APIVersion'   => '98.0' ,
        );

        $this->api->load->library('paypal/Paypal_pro', $config);
		$token    = $this->api->input->get('token');
		$payer_id = $this->api->input->get('PayerID');

		$PayPalResult = $this->api->paypal_pro->GetExpressCheckoutDetails($token);

        if ($PayPalResult['ACK'] == 'Success') {
            $transaction_amount = c_format($order_info['total'],false);

            $DECPFields = array(
				'token'   => $token,
				'payerid' => $payer_id,
            );

            $Payments = array();
            $Payment = array(
				'amt'            => $transaction_amount,
				'currencycode'   => $_SESSION['userCurrency'],
            );
            array_push($Payments, $Payment);
            $PayPalRequestData = array(
                'DECPFields' => $DECPFields,
                'Payments' => $Payments,
            );
            $PayPalResult = $this->api->paypal_pro->DoExpressCheckoutPayment($PayPalRequestData);
            
            if ($this->api->paypal_pro->APICallSuccessful($PayPalResult['ACK'])) {
            	$transaction_id = $PayPalResult['PAYMENTINFO_0_TRANSACTIONID'];
            	$payment_status = $PayPalResult['PAYMENTINFO_0_PAYMENTSTATUS'];

            	switch($payment_status) {
					case 'Canceled_Reversal' : $order_status_id = $setting['canceled_reversal_status_id']; break;
					case 'Completed'         : $order_status_id = $setting['completed_status_id']; break;
					case 'Denied'            : $order_status_id = $setting['denied_status_id']; break;
					case 'Expired'           : $order_status_id = $setting['expired_status_id']; break;
					case 'Failed'            : $order_status_id = $setting['failed_status_id']; break;
					case 'Pending'           : $order_status_id = $setting['pending_status_id']; break;
					case 'Processed'         : $order_status_id = $setting['processed_status_id']; break;
					case 'Refunded'          : $order_status_id = $setting['refunded_status_id']; break;
					case 'Reversed'          : $order_status_id = $setting['reversed_status_id']; break;
					case 'Voided'            : $order_status_id = $setting['voided_status_id']; break;
				}
		 		$this->api->confirm_order_api($order_id,$order_status_id,$transaction_id);
		 		redirect($this->api->cart->getStoreUrl('thankyou/'. $order_id ));die;
            } 
        } else {
        	$this->api->confirm_order_api($order_id,5);
        }

        redirect($this->api->cart->getStoreUrl('/'. $order_id ));die;
	}

	
}