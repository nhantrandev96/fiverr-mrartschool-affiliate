<?php

class paypal {
	public $title = 'Paypal';
	public $icon = 'assets/images/withdrawal_payment/paypal.png';
	public $website = '';

	function __construct($api){ $this->api = $api; }

	public function onInstall() {

	}

	public function onUnInstall() {
	}

	public function doPayment($request_id)
	{	
		$this->api->load->model('Withdrawal_payment_model');
		$w_request = $this->api->Withdrawal_payment_model->getRequestDetails((int)$request_id);
		$setting_data = $this->api->Withdrawal_payment_model->getSettings('withdrawalpayment_paypal');

		if(!$w_request){
			$this->api->session->set_flashdata('error',"Unknown error..");
			redirect('admincontrol/wallet_requests_details/'. (int)$request_id);die;
		}

		$with_submit_setting= json_decode($w_request['settings'],1);
		 
		if(!isset($with_submit_setting['paypal_email'])){
			$this->api->session->set_flashdata('error',"User email is not valid");
			redirect('admincontrol/wallet_requests_details/'. (int)$request_id);die;
		}
	
		require APPPATH . 'libraries/PayPal-PHP-SDK/autoload.php';
		$payouts = new \PayPal\Api\Payout();

		$apiContext = new \PayPal\Rest\ApiContext(
	        new \PayPal\Auth\OAuthTokenCredential(
	            $setting_data['ClientID'],$setting_data['ClientSecret']
	        )
		);

		$amount = number_format($w_request['total'], 2, '.', '');
		$senderBatchHeader = new \PayPal\Api\PayoutSenderBatchHeader();

		$senderBatchHeader->setSenderBatchId(uniqid())->setEmailSubject("You have a Payout!");
		$senderItem = new \PayPal\Api\PayoutItem();
		$senderItem->setRecipientType('Email')
		    ->setNote('Payment of withdrawal request #'. (int)$request_id)
		    ->setReceiver($with_submit_setting['paypal_email'])
		    ->setSenderItemId("2014031400023")
		    ->setAmount(new \PayPal\Api\Currency('{
                "value":"'. $amount .'",
                "currency":"USD"
            }'));

		$payouts->setSenderBatchHeader($senderBatchHeader)->addItem($senderItem);
		$request = clone $payouts;

		try {
		    $output = $payouts->createSynchronous($apiContext);
		} catch (Exception $ex) {
			$data = json_decode($ex->getData(),1);
			
			if(isset($data['message'])){
				$this->api->session->set_flashdata('error',$data['message']);
			}
			else if(isset($data['error_description'])){
				$this->api->session->set_flashdata('error',$data['error_description']);
			}

			redirect('admincontrol/wallet_requests_details/'. (int)$request_id);die;
		}

		$status_id = 0;
		switch ($output->getBatchHeader()->batch_status) {
			case 'DENIED': $status_id = (int)$setting_data['denied_status_id']; break;
			case 'PENDING': $status_id = (int)$setting_data['pending_status_id']; break;
			case 'PROCESSING': $status_id = (int)$setting_data['processing_status_id']; break;
			case 'SUCCESS': $status_id = (int)$setting_data['success_status_id']; break;
			case 'CANCELED': $status_id = (int)$setting_data['canceled_status_id']; break;
		}

		$this->api->Withdrawal_payment_model->apiAddWithdrwalRequestHistory((int)$request_id,[
			'status_id' => $status_id,
			'comment' => 'Payment done via paypal',
			'transaction_id' => $output->getBatchHeader()->payout_batch_id,
		]);
		
		$messages = [
			'DENIED' => 'Your payout requests were denied, so they were not processed.',
			'PENDING' => 'Your payout requests were received and will be processed soon.',
			'PROCESSING' => 'Your payout requests were received and are now being processed.',
			'SUCCESS' => 'Your payout batch was processed and completed. Check the status of each item for any holds or unclaimed transactions.',
			'CANCELED' => 'The payouts file that was uploaded through the PayPal portal was cancelled by the sender.',
		];

		$message = $messages[$output->getBatchHeader()->batch_status];
		$message .= ' | Paypal Status : '. $output->getBatchHeader()->batch_status;

		$this->api->session->set_flashdata('success',$message);
		redirect('admincontrol/wallet_requests_details/'. (int)$request_id);die;
	}

	public function saveUserSubmit(){
		$data = $this->api->input->post(null,true);
		$json = [];

		if (!isset($data['paypal_email']) || trim($data['paypal_email']) == '') {
			$json['errors']['paypal_email'] = "Email address is required";
		} else if (!filter_var($data['paypal_email'], FILTER_VALIDATE_EMAIL)) {
		  	$json['errors']['paypal_email'] = "Invalid email format";
		}

		if (!isset($json['errors'])) {
			$this->api->load->model('Withdrawal_payment_model');
			$saveSetting = [
				'paypal_email' => $data['paypal_email'],
			];

			$status = $this->api->Withdrawal_payment_model->apiAddWithdrwalRequest($data['code'],$data['ids'],$saveSetting);
			
			if((int)$status['status'] == 1){
				$json['success'] = 1;
			} else{
				$json['errors']['paypal_email'] = $status['error_message'];
			}
		}

		return $json;
	}
}