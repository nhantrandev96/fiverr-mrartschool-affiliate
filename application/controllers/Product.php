<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');
ini_set('display_errors', 0);
class Product extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('Product_model');
		$this->load->helper('share');
		___construct(1);
		$this->load->library('user_agent');
	}
	function index($product_slug = null, $user_id = null){
		redirect(base_url("store/product/". $product_slug));die;
		$data = array();
		$setting = array();
		$data['session'] = $this->session->userdata('client') ? $this->session->userdata('client') : '';
		if($product_slug){
			$data['product'] = $this->Product_model->getProductBySlug($product_slug);
			if(!$data['product']) die("Product Not Found..!");
			$data['product_slug'] = $product_slug;
			$data['user_id'] = $user_id;
			$this->db->set('view', 'view+1', FALSE);
			$this->db->where('product_id', $data['product']['product_id']);
			$this->db->update('product');
			if(!empty($user_id)){
				$data['user'] = $this->Product_model->getUserDetails($user_id);
			} else {
				$data['user'] = '';
			}
			$data['setting'] 	= $this->Product_model->getSettings('paymentsetting');
			$data['ratings'] = $this->Product_model->getReview($data['product']['product_id']);
			//if ($this->session->userdata('client') != false && $user_id) {
			$client_id = 0;
			if($this->session->userdata('client') != false) $client_id = $this->session->userdata('client')['id'];
			if (
				$this->session->userdata('administrator') == false && 
				$this->session->userdata('user') == false && 
				$user_id && 
				$client_id != $user_id
			) {
				
				$match = $this->Product_model->getProductAction(
					$data['product']['product_id'],
					$user_id
				);
				$this->Product_model->referClick($data['product']['product_id'],$user_id);
				if ($match == 0){
					$this->Product_model->setClicks($data['product']['product_id'],$user_id);
					$this->Product_model->giveClickCommition($data['product'], $user_id);
					$details = array(
						'clicks_views_refuser_id'       =>  $user_id,
						'clicks_views_action_id'        =>  $data['product']['product_id'],
						'clicks_views_status'           =>  1,
						'clicks_views_type'             =>  'productclick',
						'clicks_views_click'            =>  1,
						'clicks_views_view'             =>  0,
						'clicks_views_referrer'         =>  $this->agent->referrer(),
						'clicks_views_user_agent'       =>  $this->agent->agent_string(),
						'clicks_views_os'               =>  $this->agent->platform(),
						'clicks_views_browser'          =>  $this->agent->browser(),
						'clicks_views_isp'              =>  gethostbyaddr($_SERVER['REMOTE_ADDR']),
						'clicks_views_ipaddress'        =>  $_SERVER['REMOTE_ADDR'],
						'clicks_views_created_by'       =>  $user_id,
						'clicks_views_created'          =>  date('Y-m-d H:i:s'),
						'clicks_views_click_commission' =>  $click,
						'clicks_views_data_commission'  =>  json_encode($setting),
					);
					$this->Product_model->create_data('clicks_views', $details);
					$userData['product_commission'] = $getUserData['product_commission'] + $click;
					$userData['product_total_click'] = $getUserData['product_total_click'] + 1;
					$this->Product_model->update_data('users', $userData,array('id' => $getUserData['id']));
					$adminData['product_commission'] = $getAdminUserData['product_commission'] + $click;
					$adminData['product_total_click'] = $getAdminUserData['product_total_click'] + 1;
					$this->Product_model->update_data('users', $adminData,array('id' => $getAdminUserData['id']));
					$notificationData = array(
						'notification_url'          => '/dashboard',
						'notification_type'         =>  'commission',
						'notification_title'        =>  'New Commission added for click your reffered product in to your wallet '.$click,
						'notification_view_user_id' =>  $getUserData['id'],
						'notification_viewfor'      =>  'user',
						'notification_actionID'     =>  '',
						'notification_description'  =>  'New Commission added to your wallet '.$sales.' on '.date('Y-m-d H:i:s'),
						'notification_is_read'      =>  '0',
						'notification_created_date' =>  date('Y-m-d H:i:s'),
						'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
					);
					$this->insertproductlogs($orderlogData);
					/*$update['product_click_count'] = $data['product']['product_click_count'] + 1;
					$update['product_click_commission'] = $data['product']['product_click_commission'] + $click;
					$update['product_total_commission'] = $data['product']['product_total_commission'] + $click;
					$this->Product_model->update_data('product', $update,array('product_id' => $data['product']['product_id']));*/
				} else {
					$this->Product_model->getProductActionIncrese($data['product']['product_id'], $user_id);
				}
			}
			$this->load->view('product/index', $data);
		}
	}
	function clicks($product_slug = null, $user_id = null) {
		$data = array();
		$setting = array();
		$setting = $this->Product_model->getSettings('productsetting');
		$getUserData = $this->Product_model->getUserDetails($user_id);
		$getAdminUserData = $this->Product_model->getUserDetails(9);
		$click = 0;
		if($product_slug){
			$data['product'] = $this->Product_model->getProductBySlug($product_slug);
			if($setting && $setting['product_commission_type'] && $setting['product_noofpercommission'] && $setting['product_ppc']){
				if($setting['product_commission_type'] == 'percentage'){
					$click = ($setting['product_ppc']) * ($setting['product_noofpercommission'])  / (100);
				} else {
					$click = ($setting['product_ppc']) / ($setting['product_noofpercommission']);
				}
			}
			$data['product_slug'] = $product_slug;
			$data['user_id'] = $user_id;
			if(!empty($user_id)){
				$data['user'] = $this->Product_model->getUserDetails($user_id);
			} else {
				$data['user'] = '';
			}
			if ($this->session->userdata('user') == false && $this->session->userdata('administrator') == false) {
				$match = $this->Product_model->getProductAction($data['product']['product_id'], $_SERVER['REMOTE_ADDR']);
				if ($match == 0){
					$this->Product_model->setClicks($data['product']['product_id'],$user_id);
					$details = array(
						'clicks_views_action_id'        =>  $data['product']['product_id'],
						'clicks_views_browser'          =>  $this->agent->browser(),
						'clicks_views_click'            =>  1,
						'clicks_views_click_commission' =>  $click,
						'clicks_views_created'          =>  date('Y-m-d H:i:s'),
						'clicks_views_created_by'       =>  $user_id,
						'clicks_views_data_commission'  =>  json_encode($setting),
						'clicks_views_ipaddress'        =>  $_SERVER['REMOTE_ADDR'],
						'clicks_views_isp'              =>  gethostbyaddr($_SERVER['REMOTE_ADDR']),
						'clicks_views_os'               =>  $this->agent->platform(),
						'clicks_views_referrer'         =>  $this->agent->referrer(),
						'clicks_views_refuser_id'       =>  $user_id,
						'clicks_views_status'           =>  1,
						'clicks_views_type'             =>  'productclick',
						'clicks_views_user_agent'       =>  $this->agent->agent_string(),
						'clicks_views_view'             =>  0,
					);
					$this->Product_model->create_data('clicks_views', $details);
					$userData['product_commission'] = $getUserData['product_commission'] + $click;
					$userData['product_total_click'] = $getUserData['product_total_click'] + 1;
					$this->Product_model->update_data('users', $userData,array('id' => $getUserData['id']));
					$adminData['product_commission'] = $getAdminUserData['product_commission'] + $click;
					$adminData['product_total_click'] = $getAdminUserData['product_total_click'] + 1;
					$this->Product_model->update_data('users', $adminData,array('id' => $getAdminUserData['id']));
					$notificationData = array(
						'notification_url'	=> '/dashboard',
						'notification_type'	=>  'commission',
						'notification_title'	=>  'New Commission added for click your reffered product in to your wallet '.$click,
						'notification_view_user_id'	=>  $getUserData['id'],
						'notification_viewfor'	=>  'user',
						'notification_actionID'	=>  '',
						'notification_description'		=>  'New Commission added to your wallet '.$sales.' on '.date('Y-m-d H:i:s'),
						'notification_is_read'		=>  '0',
						'notification_created_date'		=>  date('Y-m-d H:i:s'),
						'notification_ipaddress'		=>  $_SERVER['REMOTE_ADDR']
					);
					$this->insertproductlogs($orderlogData);
					/* Update Prodoct Commission */
					/*$update['product_click_count'] = $data['product']['product_click_count'] + 1;
					$update['product_click_commission'] = $data['product']['product_click_commission'] + $click;
					$update['product_total_commission'] = $data['product']['product_total_commission'] + $click;
					$this->Product_model->update_data('product', $update,array('product_id' => $data['product']['product_id']));*/
					/* Update Prodoct Commission End */
				}
			}
			redirect('product/'.$product_slug.'/'.$user_id);
		}
	}
	function views($product_slug = null, $user_id = null) {
		$data = array();
		$setting = array();
		$setting = $this->Product_model->getSettings('productsetting');
		$getUserData = $this->Product_model->getUserDetails($user_id);
		$getAdminUserData = $this->Product_model->getUserDetails(9);
		$views = 0;
		if($product_slug){
			$data['product'] = $this->Product_model->getProductBySlug($product_slug);
			$data['product_slug'] = $product_slug;
			$data['user_id'] = $user_id;
			if(!empty($user_id)){
				$data['user'] = $this->Product_model->getUserDetails($user_id);
			} else {
				$data['user'] = '';
			}
			$details = array(
				'clicks_views_refuser_id'	=>  $user_id,
				'clicks_views_action_id'	=>  $data['product']['product_id'],
				'clicks_views_status'		=>  1,
				'clicks_views_type'		=>  'productview',
				'clicks_views_click'		=>  0,
				'clicks_views_view'		=>  1,
				'clicks_views_referrer'		=>  $this->agent->referrer(),
				'clicks_views_user_agent'		=>  $this->agent->agent_string(),
				'clicks_views_os'		=>  $this->agent->platform(),
				'clicks_views_browser'		=>  $this->agent->browser(),
				'clicks_views_isp'		=>  gethostbyaddr($_SERVER['REMOTE_ADDR']),
				'clicks_views_ipaddress'		=>  $_SERVER['REMOTE_ADDR'],
				'clicks_views_created_by'		=>  $user_id,
				'clicks_views_created'		=>  date('Y-m-d H:i:s'),
				'clicks_views_click_commission'		=>  0,
			);
			$this->Product_model->create_data('clicks_views', $details);
			$update['product_view_count'] = $data['product']['product_view_count'] + 1;
			$this->Product_model->update_data('product', $update,array('product_id' => $data['product']['product_id']));
			$data['ratings'] = $this->Product_model->getReview($data['product']['product_id']);
		}
	}
	/*function add_to_cart($product_slug = null, $user_id = null) {
		$product = $this->Product_model->getProductBySlug($product_slug);
		if($product){
			$this->cart->add($product['product_id'],1,$user_id);
		}
		$this->session->set_flashdata('error', 'store.product_add_successfully');
		redirect($this->cart->getStoreUrl('cart'));
	}*/
	function payment($product_slug = null, $user_id = null) {
		$data = array();
		$data['session'] 	= $this->session->userdata('client') ? $this->session->userdata('client') : '';
		if(!isset($data['session']['id'])){
			die("your are not login");
		}
		$data['setting'] 	= $this->Product_model->getSettings('paymentsetting');
		$data['setting_site'] 	= $this->Product_model->getSettings('site');
		if($product_slug){
			$product = $this->Product_model->getProductBySlug($product_slug);
			if(!empty($user_id)){
				$data['user'] = $this->Product_model->getUserDetails($user_id);
			} else {
				$data['user'] = '';
			}
			$commission = $this->Product_model->calcCommitions($product, 'sale');
			$orderData = array(
				'user_id'         => $data['session']['id'],
				'affiliate_id'    => (int)$user_id,
				'product_id'      => $product['product_id'],
				'quantity'        => 1,
				'currency_code'   => $data['setting']['payment_currency'],
				'total'   		  => $product['product_price'],
				'commission'      => $commission['commission'],
				'commission_type' => $commission['type'],
				'ip'              => $_SERVER['REMOTE_ADDR'],
				'created_at'      => date("Y-m-d H:i:s"),
				'user_agent'      => $this->agent->agent_string(),
				'os'              => $this->agent->platform(),
				'browser'         => $this->agent->browser(),
				'isp'             => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				'status'          => 0,
				'payment_status'  => 0,
			);
			$data['order'] = $orderData;
			$this->db->insert('orders',$orderData);
			$data['order_id'] = $this->db->insert_id();
			$data['product_name'] = $product['product_name'];
			$data['paypal_url'] = 'https://www.paypal.com/cgi-bin/webscr';

			/*if($data['setting']['payment_mode'] == 'sandbox'){
				$data['paypal_url'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
			}
			$this->load->view('product/payment', $data);*/
            $config = array(
                'Sandbox'      => $data['setting']['payment_mode'] == 'sandbox' ? TRUE : FALSE,
                'APIUsername'  => $data['setting']['api_username'],
                'APIPassword'  => $data['setting']['api_password'],
                'APISignature' => $data['setting']['api_signature'],
                'APISubject'   => '',
                'APIVersion'   => '98.0' ,
            );
            $this->load->library('paypal/Paypal_pro', $config);
            $SECFields = array(
                'returnurl' => site_url('product/notify/' . $data['order_id']), 
                'cancelurl' => site_url('paypal/paypal_cancel/' . $data['order_id']), 
                'brandname' => $data['setting_site']['name'],
                'hdrimg' => ''
            );
            $Payments = array();
            $PaymentOrderItems = array();
            $Item = array(
                'name' => $data['product_name'],
                'desc' => $data['product_name'],
                'amt' => $product['product_price'],
                'number' => $product['product_id'],
                'qty' => 1,
                'taxamt' => 0,
                'itemurl' => '', 
            );
            array_push($PaymentOrderItems, $Item);
           
            $Payment = array(
                'order_items' => $PaymentOrderItems,
                'amt' => $product['product_price'],
                'itemamt' => $product['product_price'],
                'currencycode' => $data['setting']['payment_currency'],
            );
            array_push($Payments, $Payment);
            $PayPalRequestData = array(
                'SECFields' => $SECFields,
                'Payments' => $Payments,
            );
            $PayPalResult = $this->paypal_pro->SetExpressCheckout($PayPalRequestData);
            if (isset($PayPalResult['ACK']) && $this->paypal_pro->APICallSuccessful($PayPalResult['ACK'])) {
                redirect($PayPalResult['REDIRECTURL']);
            } else {
                $message = isset($PayPalResult['ERRORS'][0]['L_LONGMESSAGE']) ? $PayPalResult['ERRORS'][0]['L_LONGMESSAGE'] : '';
                show_error($message, 500, 'error occured');
            }
		}
	}
	
	function insertnotification($postData = null){
		if(!empty($postData)){
			$data['custom'] = $this->Product_model->create_data('notification', $postData);
		}
	}
	function rating() {
		$data = array();
		$post = $this->input->post(null,true);
		if($post){
			$details = array(
				'rating_user_id'    =>  !empty($post['user_id']) ? $post['user_id'] : 0,
				'products_id '      =>  $post['product_id'],
				'rating_status'     =>  1,
				'rating_number'     =>  $post['number'],
				'rating_name'       =>  !empty($post['name']) ? $post['name'] : '',
				'rating_email'      =>  !empty($post['email']) ? $post['email'] : '',
				'rating_comments'   =>  !empty($post['comment']) ? $post['comment'] : '',
				'rating_referrer'   =>  $this->agent->referrer(),
				'rating_user_agent' =>  $this->agent->agent_string(),
				'rating_os'         =>  $this->agent->platform(),
				'rating_browser'    =>  $this->agent->browser(),
				'rating_isp'        =>  gethostbyaddr($_SERVER['REMOTE_ADDR']),
				'rating_ipaddress'  =>  $_SERVER['REMOTE_ADDR'],
				'rating_created_by' =>  !empty($post['user_id']) ? $post['user_id'] : 0,
				'rating_created'    =>  date('Y-m-d H:i:s'),
			);

			$this->Product_model->create_data('rating', $details);
			//$data['ratings'] = $this->Product_model->getReview($post['product_id']);
			/*if(!empty($data['ratings'])) {
				foreach($data['ratings'] as $rating) {
					echo '<div class="col-md-2 col-sm-2 text-center">
					<img class="rounded-circle reviewer" src="http://dev.mycodeg.com/assets/vertical/assets/images/users/avatar-1.jpg" alt="user" width="40">
					</div>
					<div class="col-md-10 col-sm-10">
					<div class="block-text rel zmin">
					<a title="" href="#">' . $rating['product_name'] . ' ' . $rating['rating_created'] . '</a>
					<div class="mark">My rating: <span class="rating-input">';
					for($i = 0; $i < 5 ; $i ++){
						if($i < $rating['rating_number']) {
							$star = 'glyphicon-star';
						}else {
							$star = 'glyphicon-star-empty';
						}
						echo '<span data-value="' . $i . '" class="glyphicon ' . $star . '"></span>';
					}
					echo '</span></div>
					<p>' . $rating['rating_comments'] . '</p>
					</div>
					</div>';
				}
			}*/
		}
	}
	function insertproductlogs($postData = null){
		if(!empty($postData)){
			$data['custom'] = $this->Product_model->create_data('payment_log', $postData);
		}
	}
	public function sendOrderNoti($order_info){
		$userDetail = $this->Product_model->getUserDetails($order_info['order_user_id']);
		$cdate = date('Y-m-d H:i:s');
		$notificationData = array(
			'notification_url'          => '/vieworder/'.$order_info['order_id'],
			'notification_type'         =>  'order',
			'notification_title'        =>  'New Order Generated by '.$userDetail['username'],
			'notification_viewfor'      =>  'admin',
			'notification_actionID'     =>  $order_info['order_id'],
			'notification_description'  =>  $userDetail['firstname'].' '.$userDetail['lastname'].' created a new order at affiliate Program on '.date('Y-m-d H:i:s'),
			'notification_is_read'      =>  '0',
			'notification_created_date' =>  $cdate,
			'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
		);
		$this->insertnotification($notificationData);
		$notificationData = array(
			'notification_url'          => '/vieworder/'.$order_info['order_id'],
			'notification_type'         =>  'order',
			'notification_title'        =>  'Your Order has been place',
			'notification_viewfor'      =>  'client',
			'notification_view_user_id' =>  $userDetail['id'],
			'notification_actionID'     =>  $order_info['order_id'],
			'notification_description'  =>  'Your Order has been place',
			'notification_is_read'      =>  '0',
			'notification_created_date' =>  $cdate,
			'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
		);
		$this->insertnotification($notificationData);
		$notificationData = array(
			'notification_url'          => '/vieworder/'.$order_info['order_id'],
			'notification_type'         =>  'order',
			'notification_title'        =>  'New Order Generated by '.$userDetail['username'],
			'notification_viewfor'      =>  'user',
			'notification_view_user_id' =>  $order_info['affiliate_id'],
			'notification_actionID'     =>  $order_info['order_id'],
			'notification_description'  =>  $userDetail['firstname'].' '.$userDetail['lastname'].' created a new order which you refered to him at affiliate Program on '.date('Y-m-d H:i:s'),
			'notification_is_read'      =>  '0',
			'notification_created_date' =>  $cdate,
			'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
		);
		$this->insertnotification($notificationData);
	}
	public function notify($order_id){
		
		$order_info = $this->Order_model->getOrder($order_id);
		$data['setting'] 	= $this->Product_model->getSettings('paymentsetting');
		$config = array(
            'Sandbox'      => $data['setting']['payment_mode'] == 'sandbox' ? TRUE : FALSE,
            'APIUsername'  => $data['setting']['api_username'],
            'APIPassword'  => $data['setting']['api_password'],
            'APISignature' => $data['setting']['api_signature'],
            'APISubject'   => '',
            'APIVersion'   => '98.0' ,
        );
        $this->load->library('paypal/Paypal_pro', $config);
        $token = $this->input->get('token');
        $payer_id = $this->input->get('PayerID');
        $PayPalResult = $this->paypal_pro->GetExpressCheckoutDetails($token);
        if ($PayPalResult['ACK'] == 'Success') {
            $transaction_amount = $order_info['product_price'];
            $DECPFields = array(
                'token' => $token,
                'payerid' => $payer_id,
            );
            $Payments = array();
            $Payment = array(
                'amt' => $transaction_amount,
                'currencycode' => $data['setting']['payment_currency'],
            );
            array_push($Payments, $Payment);
            $PayPalRequestData = array(
                'DECPFields' => $DECPFields,
                'Payments' => $Payments,
            );
            $PayPalResult = $this->paypal_pro->DoExpressCheckoutPayment($PayPalRequestData);
            
            if ($this->paypal_pro->APICallSuccessful($PayPalResult['ACK'])) {
               
                $transaction_id = $PayPalResult['PAYMENTINFO_0_TRANSACTIONID'];
                //$this->further_process($PayPalResult['ACK'], $transaction_id,$PayPalResult,$order_info);
                $update['payment_status'] = 1;
				$update['status'] = 1;
				$update['txn_id'] = $transaction_id;
				$this->Product_model->update_data( 'orders', $update,array('id' => $order_id) );
				$historyData = array(
					'order_id'        => $order_id,
					'paypal_status'   => $PayPalResult['ACK'],
					'comment'         => json_encode($PayPalResult),
					'created_at'      => date("Y-m-d H:i:s"),
					'order_status_id' => 1,
				);
				if($order_info['affiliate_id'] > 0){
					$this->Wallet_model->addTransaction(array(
						'user_id'      => $order_info['affiliate_id'],
						'amount'       => $order_info['commission'],
						'comment'      => 'Commition for order Id # '. $order_id .' | User : '. $order_info['firstname'] ." " .$order_info['lastname'],
						'type'         => 'sale_commission',
						'reference_id' => $order_id,
					));
					$level = $this->Product_model->getMyLevel($order_info['affiliate_id']);
					
			       	$setting = $this->Product_model->getSettings('referlevel');
					$max_level = isset($setting['levels']) ? (int)$setting['levels'] : 3;

					//foreach (array(1,2,3) as $l) {
					for ($l=1; $l <= $max_level ; $l++) { 
		                $s = $this->Product_model->getSettings('referlevel_'. $l);
		                $levelUser = (int)$level['level'. $l];
		                if($s && $levelUser > 0){
		                    $_giveAmount = (float)$s['sale_commition'];
		                    $this->Wallet_model->addTransaction(array(
								'user_id'      => $levelUser,
								'amount'       => $_giveAmount,
								'dis_type'     => '',
								'comment'      => "Level {$l} : ".'Commition for order Id # '. $order_id .' | User : '. $order_info['firstname'] ." " .$order_info['lastname'],
								'type'         => 'refer_sale_commission',
								'reference_id' => $order_id,
		                    ));
		                }
		            }
				}
				$this->sendOrderNoti($order_info);
				$this->load->model('Mail_model');
				$this->Mail_model->send_new_order_mail($order_id);
				$this->db->insert('orders_history',$historyData);
				redirect(base_url('product/thankyou/'. $order_id . "/". $order_info['product_id'] ));die;
            } else {
            }
        } else { }
        redirect(base_url('/'));
		
		/*if ($order_info) {
			$request = 'cmd=_notify-validate';
			foreach ($_POST as $key => $value) {
				$request .= '&' . $key . '=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
			}
			$setting = $this->Product_model->getSettings('paymentsetting');;
			$url = 'https://www.paypal.com/cgi-bin/webscr';
			if($setting['payment_mode'] == 'sandbox'){
				$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
			}
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$response = curl_exec($curl);
			$order_status_id = 0;
			
			if ((strcmp($response, 'VERIFIED') == 0 || strcmp($response, 'UNVERIFIED') == 0) && isset($_POST['payment_status'])) {
				switch($_POST['payment_status']) {
					case 'Canceled_Reversal':
						$order_status_id = 11;
						break;
					case 'Completed':
						$order_status_id = 1;
						break;
					case 'Denied':
						$order_status_id = 3;
						break;
					case 'Expired':
						$order_status_id = 4;
						break;
					case 'Failed':
						$order_status_id = 5;
						break;
					case 'Pending':
						$order_status_id = 6;
						break;
					case 'Processed':
						$order_status_id = 7;
						break;
					case 'Refunded':
						$order_status_id = 8;
						break;
					case 'Reversed':
						$order_status_id = 9;
						break;
					case 'Voided':
						$order_status_id = 10;
						break;
				}
			}
			
			$update['payment_status'] = $order_status_id;
			$update['status'] = $order_status_id;
			$update['txn_id'] = $_POST['txn_id'];
			$this->Product_model->update_data( 'orders', $update,array('id' => $order_id) );
			$historyData = array(
				'order_id'        => $order_id,
				'paypal_status'   => $_POST['payment_status'],
				'comment'         => json_encode($_POST),
				'created_at'      => date("Y-m-d H:i:s"),
				'order_status_id' => $order_status_id,
			);
			if($order_info['affiliate_id'] > 0){
				$this->Wallet_model->addTransaction(array(
					'user_id'      => $order_info['affiliate_id'],
					'amount'       => $order_info['commission'],
					'comment'      => 'Commition for order Id # '. $order_id .' | User : '. $order_info['firstname'] ." " .$order_info['lastname'],
					'type'         => 'sale_commission',
					'reference_id' => $order_id,
				));
			}
			$this->sendOrderNoti($order_info);

			$this->load->model('Mail_model');
			$this->Mail_model->send_new_order_mail($order_id);
			$this->db->insert('orders_history',$historyData);
			curl_close($curl);
		}*/
	}
	
	public function thankyou($order_id){
		$this->load->model('Order_model');
		$user = $this->session->userdata('client') ? $this->session->userdata('client') : '';
		$data['client_loged'] = $this->session->userdata('client') ? true : false;
		$data['order'] = $this->Order_model->getOrder($order_id);
		$this->load->model('User_model');
		$admin_info = $this->User_model->get_user_by_type('admin');
		$data['store_name'] =  $admin_info['firstname'].' '.$admin_info['lastname'];
		$data['store_email'] =  $admin_info['email'];
		if($data['order']['order_user_id'] == $user['id']){
			$data['affiliateuser'] = $this->Order_model->getAffiliateUser($order_id);
			$data['payment_history'] = $this->Order_model->getHistory($order_id);
			$data['status'] = $this->Order_model->status;
			$data['order_history'] = $this->Order_model->getHistory($order_id, 'order');
			$this->load->view('product/thanks', $data);
		}
		else{
			die("You are not allow to see.. !");
		}
	}
}