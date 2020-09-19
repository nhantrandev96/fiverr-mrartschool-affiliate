<?php
class stripes {
	public $title = 'Stripe';
	public $icon = 'assets/images/payments/stripe_logo.png';
	public $website = 'https://stripe.com/';

	function __construct($api){ $this->api = $api; }

	public function getConfirm($data) {
		$setting_data = $data['setting_data'];
		$order_info   = $data['order_info'];
		$products     = $data['products'];
		 
		$store_url = base_url();
		if((int)$setting_data['environment'] == 1) {
			$stripe_public_key = $setting_data['live_public_key'];
			$test_mode = false;
		} else {
			$stripe_public_key = $setting_data['test_public_key'];
			$test_mode = true;
		}

		$billing_details = array(
			'billing_details' => array(
				'name'    => $order_info['firstname'] . ' ' . $order_info['lastname'],
				'email'   => $order_info['email'],
				'address' => array(
					'line1'       => $order_info['address'],
					'line2'       => '',
					'city'        => $order_info['city'],
					'state'       => $order_info['state_name'],
					'postal_code' => $order_info['zip_code'],
					'country'     => $order_info['country_code']
				)
			)
		);

		$action = site_url('store/callbackfunctions/stripes/callback/' . $data['order_id']);

		$view = APPPATH.'payments/views/stripes.php';
		require $view;
	}

	public function callback($order_id){
		
		$json = array('error' => 'Server did not get valid request to process');
		try{
			if((int)$order_id <= 0){
				throw new Exception("Your order seems lost in session. We did not charge your payment. Please contact administrator for more information.");
			}

			$json_str = file_get_contents('php://input');
			$json_obj = json_decode($json_str);

			$order_info   = $this->api->Order_model->getOrder($order_id, 'store');;
			$setting      = $this->api->Product_model->getSettings('storepayment_stripes');
			//$setting_site = $this->api->getSettings('site');
			
			$this->initStripe($setting);

			// get order info
			//$this->load->model('checkout/order');
			//$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

			if(empty($order_info)){
				throw new Exception("Your order seems lost before payment. We did not charge your payment. Please contact administrator for more information.");
			}

			// Create the PaymentIntent
			if (isset($json_obj->payment_method_id)) {
				$amount = $order_info['total'];
				$amount = $amount * 100;

				// Create the PaymentIntent
				$intent = \Stripe\PaymentIntent::create(array(
					'payment_method'      => $json_obj->payment_method_id,
					'amount'              => $amount,
					'currency'            => strtolower($order_info['currency_code']),
					'confirmation_method' => 'manual',
					'confirm'             => true,
					'description'         => "Charge for Order #".$order_id,
					'metadata'            => array(
						'order_id'	=> $order_id,
						'email'		=> $order_info['email']
					),
				));
			}

			if (isset($json_obj->payment_intent_id)) {
				$intent = \Stripe\PaymentIntent::retrieve(
					 $json_obj->payment_intent_id
				);
				$intent->confirm();
			}

			if(!empty($intent)) {
				if (($intent->status == 'requires_action' || $intent->status == 'requires_source_action') &&
				$intent->next_action->type == 'use_stripe_sdk') {
					// Tell the client to handle the action
					$json = array(
						'requires_action' => true,
						'payment_intent_client_secret' => $intent->client_secret
					);
				} else if ($intent->status == 'succeeded') {
					// The payment didn’t need any additional actions and completed!
					// Handle post-payment fulfillment

					// charge this customer and update order accordingly
					$charge_result = $this->chargeAndUpdateOrder($intent, $order_info, $setting);

					// set redirect to success or failure page as per payment charge status
					if($charge_result) {
						$json = array('success' => base_url('store/thankyou/'. $order_id ));
					} else {
						$json = array('error' => 'Payment could not be completed. Please try again.');
					}

				} else {
					// Invalid status
					$json = array('error' => 'Invalid PaymentIntent Status ('.$intent->status.')');
				}
			}

		} catch (\Stripe\Error\Base $e) {
			$json = array('error' => $e->getMessage());
		} catch (\Exception $e) {
			$json = array('error' => $e->getMessage());
		}

		echo json_encode($json);
		return;
	}

	private function chargeAndUpdateOrder($intent, $order_info, $setting_data){

		if(isset($intent->id)) {
			$message = 'Payment Intent ID: '.$intent->id. PHP_EOL .'Status: '. $intent->status;
			if($intent->status == "succeeded") {
				$this->api->confirm_order_api($order_info['id'],$setting_data['order_success_status'],$intent->id,$message);
			} else {
				$this->api->confirm_order_api($order_info['id'],$setting_data['order_failed_status'],$intent->id,$message);
			}
			
			return true;
		} else {			
			return false;
		}
	}

	private function initStripe($setting_data) {
		require_once(APPPATH . 'core/stripe/stripe.php');

		if((int)$setting_data['environment'] == 1) {
			$stripe_secret_key = $setting_data['live_secret_key'];
		} else {
			$stripe_secret_key = $setting_data['test_secret_key'];
		}

		if($stripe_secret_key != '' && $stripe_secret_key != null) {
			\Stripe\Stripe::setApiKey($stripe_secret_key);
			return true;
		}

		throw new Exception("Unable to load stripe libraries.");
	}

	public function getMethod($data){
		return array(
			'html' => '<h3>Credit/Debit Card (3D and SCA Secured)</h3>',
		);
	}
}