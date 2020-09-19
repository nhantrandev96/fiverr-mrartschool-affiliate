<?php

class opay {
	public $title = 'OPay';
	public $icon = 'assets/images/payments/opay.png';
	public $website = 'https://www.opay.tw/';

	function __construct($api){ $this->api = $api; }

	public function getConfirm($data) {

		include(APPPATH.'core/opay/Opay.Payment.Integration.php');
	    try {
	    	$obj = new OpayAllInOne();

	    	$setting_data = $data['setting_data'];
			$order_info = $data['order_info'];
			$products = $data['products'];

			$obj->ServiceURL                = "https://payment-stage.opay.tw/Cashier/AioCheckOut/V5";
			$obj->HashKey                   = $setting_data['HashKey'];
			$obj->HashIV                    = $setting_data['HashIV'];
			$obj->MerchantID                = $setting_data['MerchantID'];
			$obj->EncryptType               = OpayEncryptType::ENC_SHA256;
			$obj->Send['ReturnURL']         = site_url('store/callbackfunctions/opay/callback/' . $order_info['id']);
			$obj->Send['ClientBackURL']     = site_url('store/thankyou/'. $data['order_id']);
			
			$obj->Send['MerchantTradeNo']   = "DX".time();
			$obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');
			$obj->Send['TotalAmount']       = (float)$order_info['total'];
			$obj->Send['TradeDesc']         = "good to drink";
			$obj->Send['ChoosePayment']     = OpayPaymentMethod::ALL;

			$TradeDesc = [];
			foreach ($products as $key => $value) {
				$TradeDesc[] = $value['product_name'];

				array_push($obj->Send['Items'],
		        	array(
						'Name'     => $value['product_name'],
						'Price'    => (float)$value['price'],
						'Currency' => $_SESSION['userCurrency'],
						'Quantity' => (int)$value['quantity'],
						'URL'      => "dedwed"
		            )
		        );
			}

			$obj->Send['TradeDesc'] = implode(",", $TradeDesc);
	        $checkout = $obj->CheckOutString('Submit');
	    } catch (Exception $e) {
	    	$error= $e->getMessage();
	    }
		$view = APPPATH.'payments/views/opay.php';
		require $view;
	}

	public function callback($order_id) {
		include(APPPATH.'core/opay/Opay.Payment.Integration.php');
		$setting_data    = $this->api->Product_model->getSettings('storepayment_opay');

	    try {
            $obj = new OpayAllInOne();
			$obj->HashKey     = $setting_data['HashKey'];
			$obj->HashIV      = $setting_data['HashIV'];
			$obj->MerchantID  = $setting_data['MerchantID'];
			$obj->EncryptType = OpayEncryptType::ENC_SHA256;
            $arFeedback = $obj->CheckOutFeedback();

            //file_put_contents('./arFeedback.txt', json_encode($arFeedback) .PHP_EOL , FILE_APPEND | LOCK_EX); 
            
            if(is_array($arFeedback) && isset($arFeedback['RtnCode']) && (int)$arFeedback['RtnCode'] == 1){
            	$this->api->confirm_order_api($order_id,$setting_data['order_status'],$arFeedback['TradeNo']);
            } else{
            	$this->api->confirm_order_api($order_id,$setting_data['failed_status']);
            }
	    } catch (Exception $e) {
        	$this->api->confirm_order_api($order_id,$setting_data['failed_status']);
	    }
	}

	public function confirm($data) {
		$json['success'] = true;
		$json['redirect'] = $data['thankyou_url'];
		
		$this->api->confirm_order_api($data['order_info']['id'],7);
		return $json;
	}

	public function getMethod($data){
		return array(
			'html' => '<h3>Opay</h3>',
		);
	}
}