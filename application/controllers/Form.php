<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

ini_set('display_errors', 0);

class Form extends MY_Controller {
	protected $smiley_url = 'assets/images/smileys';
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model', 'user');
		$this->load->model('message_model', 'message');
		$this->load->model('lastmsg_model', 'last');
		$this->load->helper('smiley');
		___construct(1);
	}
	public function index($form_seo, $refer_id){
		$this->session->unset_userdata('last_order_id');
		$refer_id = base64_decode($refer_id);
		$user = $this->cart->is_logged();
		$where = "";
		if($user){
			$where .= " AND (status != 0 OR vendor_id = '{$user['id']}' )";
		}else{
			$where .= " AND status != 0 ";
		}
        $formDetails = $this->db->query("SELECT * FROM `form` WHERE seo = '". $form_seo . "' {$where} ")->row();
        if(!$formDetails) { show_404(); }

        $form_id = $formDetails->form_id;
		if($form_id != $this->session->userdata('form_id')){
			$this->cart->clearCart();
			$this->session->unset_userdata('form_coupon_discount');
		}

		$this->session->set_userdata('form_id', $form_id);
		$this->session->set_userdata('form_refer_id', $refer_id);
		$this->load->model('Product_model');
		$data['LanguageHtml'] = $this->Product_model->getLanguageHtml('store');
		$data['CurrencyHtml'] = $this->Product_model->getCurrencyHtml('store');
		

		if($user){
			$data['shipping'] = $this->db->query("SELECT * FROM shipping_address WHERE user_id = ". $user['id'])->row();
		}
		$data['countries'] = $this->db->query('SELECT * FROM countries')->result();
		$data['is_logged'] = $this->cart->is_logged();
		$data['cart_update_url']= base_url('form/cart');
		
		$getSetting = $this->db->get_where('setting', array('setting_status' => 1))->result_array();
		foreach ($getSetting as $setting) {
            $settingdata[$setting['setting_key']] = $setting['setting_value'];
        }
        $data['store_setting'] = $settingdata;


		if($formDetails){
			$this->cart->setcookieAffiliate($refer_id);
			$this->checkRefer((array)$formDetails,$refer_id);
        	if($formDetails->allow_for == "S"){
        		$product_ids = @explode(",", $formDetails->product);
        		$products = $this->Product_model->getProductByIds($product_ids);
        	}else{
    			$filter['vendor_id'] = 'admin';
        		$products = $this->Product_model->getAllProducts($filter);
        	}
        	if(empty($this->cart->getProducts())){
        		foreach ($products as $product) {
					$quantity = 1;
					$this->cart->add($product->product_id, $quantity, $refer_id);
        		}
        	}
        	
        	$data['page'] = $formDetails->title;
        	$data['analytics'] = $formDetails->google_analitics;
        	$data['fevi_icon'] = $formDetails->fevi_icon ? 'assets/images/form/favi/'.$formDetails->fevi_icon : 'assets/images/site/'.$settingdata['favicon'];
        	$data['description'] = $formDetails->description;
			$data['products'] = $this->cart->getProducts();
			$data['sub_total'] = $this->cart->subTotal();
			$data['final_total'] = $this->cart->finalTotal();
			if($this->session->userdata('form_coupon_discount')) {
				$data['form_coupon_discount'] = $this->session->userdata('form_coupon_discount');
			}
			//$data['payment'] = json_decode($formDetails->payment);
			$data['paymentsetting'] = $this->Product_model->getSettings('paymentsetting');
        	$data['footer'] = $formDetails->footer_title;
        }
        //echo "<pre>";print_r($data['product_type']);echo "</pre>";
        //echo "<pre>";print_r($data['downloadable_files']);echo "</pre>";die;

        $data['allow_shipping'] = $this->cart->allow_shipping;
		$this->load->view('form/checkout', $data);
	}
	public function checkRefer($formDetails,$user_id){
		$client_id = 0;
		$this->load->model('Product_model');
		$this->load->helper('share');
		$this->load->library('user_agent');
		$form_id = $formDetails['form_id'];

		$_user = $this->Product_model->getUserDetails((int)$user_id);
		
		if($this->session->userdata('client') != false) $client_id = $this->session->userdata('client')['id'];

		if (
			$_user && $_user['type'] == 'user' &&
			$this->session->userdata('administrator') == false && 
			$this->session->userdata('user') == false && 
			$user_id && 
			$client_id != $user_id
		) {
			$match = $this->Product_model->getFormAction(
				$form_id,
				$user_id
			);
			
			if ($match == 0){
				$this->Product_model->setFormClicks($form_id,$user_id);
				$details = array(
					'clicks_views_refuser_id'       =>  $user_id,
					'clicks_views_action_id'        =>  $form_id,
					'clicks_views_status'           =>  1,
					'clicks_views_type'             =>  'formclick',
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
					'clicks_views_data_commission'  =>  json_encode(array()),
				);
				$this->Product_model->create_data('clicks_views', $details);
				$notificationData = array(
					'notification_url'          => '/dashboard',
					'notification_type'         =>  'commission',
					'notification_title'        =>  'New Commission added for click your reffered form in to your wallet '.$click,
					'notification_view_user_id' =>  $getUserData['id'],
					'notification_viewfor'      =>  'user',
					'notification_actionID'     =>  '',
					'notification_description'  =>  'New Commission added to your wallet '.$sales.' on '.date('Y-m-d H:i:s'),
					'notification_is_read'      =>  '0',
					'notification_created_date' =>  date('Y-m-d H:i:s'),
					'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
				);
				$this->insertproductlogs($orderlogData);
			} else {
				$this->Product_model->getFormActionIncrese($form_id, $user_id);
			}

			$this->Product_model->giveFormClickCommition($formDetails, $user_id);
		}
	}
	function insertproductlogs($postData = null){
		if(!empty($postData)){
			$data['custom'] = $this->Product_model->create_data('payment_log', $postData);
		}
	}
	public function checkoutCart(){
		$data['products'] = $this->cart->getProducts();
		$data['is_logged'] = $this->cart->is_logged();
		if($data['products']){
			$data['base_url'] = $this->cart->getStoreUrl();
			$data['sub_total'] = $data['total'] = $this->cart->subTotal();
		}
		$data['sub_total'] = $this->cart->subTotal();
		$data['final_total'] = $this->cart->finalTotal();
		if($this->session->userdata('form_coupon_discount')) {
			$data['form_coupon_discount'] = $this->session->userdata('form_coupon_discount');
		}
		$this->load->view("form/checkout_cart",$data);
	}
	public function checkoutShipping(){
		$is_logged = $this->cart->is_logged();
		$this->cart->reloadCart();
		$data['allow_shipping'] = $this->cart->allow_shipping;

		if($data['allow_shipping']){
			if($is_logged){
				$data['shipping'] = $this->db->query("SELECT * FROM shipping_address WHERE user_id =  ". $is_logged['id'])->row();
			}
			$data['countries'] = $this->db->query('SELECT * FROM countries')->result();
		}

		$this->load->view("form/checkout_shipping",$data);
	}
	public function ajax_login(){
		$this->load->model('user_model', 'user');
		$username = $this->input->post('username',true);
		$password = $this->input->post('password',true);	
		$this->session->unset_userdata('user','administrator','client');
		$user_details_array = $this->user->login($username);
		if(!empty($user_details_array['username']) && sha1($password)==$user_details_array['password']){
			if($user_details_array['type'] == 'client'){
				$this->user->update_user_login($user_details_array['id']);
				$this->session->set_userdata(array('client'=>$user_details_array));
				$this->cart->syncCart();
				
				$json['success'] = true;
			}
		}
		if(!isset($json['success'])){
			$json['errors']['password'] = __('user.invalid_credentials');
		}
		echo json_encode($json);
	}
	public function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
	    $output = NULL;
	    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
	        $ip = $_SERVER["REMOTE_ADDR"];
	        if ($deep_detect) {
	            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
	                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
	                $ip = $_SERVER['HTTP_CLIENT_IP'];
	        }
	    }
	    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
	    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
	    $continents = array(
	        "AF" => "Africa",
	        "AN" => "Antarctica",
	        "AS" => "Asia",
	        "EU" => "Europe",
	        "OC" => "Australia (Oceania)",
	        "NA" => "North America",
	        "SA" => "South America"
	    );
	    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
	        
	        $curl = curl_init("http://www.geoplugin.net/json.gp?ip=" . $ip);
	        $request = '';
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($curl, CURLOPT_HEADER, false);
	        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	        
	        $ipdat = json_decode(curl_exec($curl));
	        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
	            switch ($purpose) {
	                case "location":
		                $id = 0;
	                    $code = @$ipdat->geoplugin_countryCode;
	                    $data = $this->db->query("SELECT id FROM countries WHERE sortname LIKE '{$code}' ")->row();
	                    if($data){
	                    	$id = $data->id;
	                    }
	                    $output = array(
							"city"           => @$ipdat->geoplugin_city,
							"state"          => @$ipdat->geoplugin_regionName,
							"country"        => @$ipdat->geoplugin_countryName,
							"country_code"   => @$ipdat->geoplugin_countryCode,
							"continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
							"continent_code" => @$ipdat->geoplugin_continentCode,
							"id"             => $id
	                    );
	                    break;
	                case "address":
	                    $address = array($ipdat->geoplugin_countryName);
	                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
	                        $address[] = $ipdat->geoplugin_regionName;
	                    if (@strlen($ipdat->geoplugin_city) >= 1)
	                        $address[] = $ipdat->geoplugin_city;
	                    $output = implode(", ", array_reverse($address));
	                    break;
	                case "city":
	                    $output = @$ipdat->geoplugin_city;
	                    break;
	                case "state":
	                    $output = @$ipdat->geoplugin_regionName;
	                    break;
	                case "region":
	                    $output = @$ipdat->geoplugin_regionName;
	                    break;
	                case "country":
	                    //$output = @$ipdat->geoplugin_countryName;
	                    $output = 0;
	                    $code = @$ipdat->geoplugin_countryCode;
	                    $data = $this->db->query("SELECT id FROM countries WHERE sortname LIKE '{$code}' ")->row();
	                    if($data){
	                    	$output = $data->id;
	                    }
	                    break;
	                case "countrycode":
	                    $output = @$ipdat->geoplugin_countryCode;
	                    break;
	            }
	        }
	    }
	   
	    return $output;
	}
	public function ajax_register(){
		$this->load->model('user_model', 'user');
		$this->load->model('Product_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('f_name', 'First Name', 'required|trim');
		$this->form_validation->set_rules('l_name', 'Last Name', 'required|trim');
		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required|trim', array('required' => '%s is required'));
		$this->form_validation->set_rules('c_password', 'Confirm Password', 'required|trim', array('required' => '%s is required'));
		$this->form_validation->set_rules('c_password', 'Confirm Password', 'required|trim|matches[password]', array('required' => '%s is required'));
		if ($this->form_validation->run() == FALSE) {
			$json['errors'] = $this->form_validation->error_array();
		} else {
			$checkEmail = $this->db->query("SELECT * FROM users WHERE email like ". $this->db->escape($this->input->post('email',true)) ." ")->num_rows();
			if($checkEmail > 0){ $json['errors']['email'] = "Email Already Exist"; }
			$checkUsername = $this->db->query("SELECT * FROM users WHERE username like ". $this->db->escape($this->input->post('username',true)) ." ")->num_rows();
			if($checkUsername > 0){ $json['errors']['username'] = "Username Already Exist"; }
			if(!isset($json['errors'])){
				$geo = $this->ip_info();			
				
				$data = $this->user->insert(array(
					'firstname' => $this->input->post('f_name',true),
					'lastname'  => $this->input->post('l_name',true),
					'email'     => $this->input->post('email',true),
					'username'  => $this->input->post('username',true),
					'password'  => sha1($this->input->post('password',true)),
					'refid'     => $this->cart->getReferId(),
					'type'      => 'client',
					'Country'   => (int)$geo['id'],
					'City'      => (string)$geo['city'],
				));
				$json['success'] =  __('user.youve_successfully_registered');
				$user_details_array = $this->user->login( $this->input->post('username',true) );
				$this->session->set_userdata(array('client'=>$user_details_array));
				
				$notificationData = array(
					'notification_url'          => '/listclients/'.$data,
					'notification_type'         =>  'client',
					'notification_title'        =>  __('user.new_client_registration'),
					'notification_viewfor'      =>  'admin',
					'notification_actionID'     =>  $data,
					'notification_description'  =>  $this->input->post('firstname',true).' '.$this->input->post('lastname',true).' register as a client on affiliate Program on '.date('Y-m-d H:i:s'),
					'notification_is_read'      =>  '0',
					'notification_created_date' =>  date('Y-m-d H:i:s'),
					'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
				);
				$this->Product_model->create_data('notification', $notificationData);
				$this->load->model('Mail_model');
				$this->Mail_model->send_register_mail($this->input->post(null,true),__('user.welcome_to_new_client_registration'));
			}
		}
		echo json_encode($json);
	}
	/*public function downloadable_files(){
		$json = array();
		if($this->input->post('form_seo',true)){
			$formDetails = $this->db->query("SELECT downloadable_files FROM `form` WHERE seo = '". $this->input->post('form_seo',true) . "'")->row();
			$files = array();
			if($formDetails){
				$files = json_decode($formDetails->downloadable_files, true);
			}

			$dir_path = APPPATH.'/downloads/';
			$zip = new ZipArchive();
			$filename = "application/downloads/files.zip";

			if ($zip->open($filename, ZipArchive::CREATE) !== TRUE) {
			    exit("cannot open <$filename>\n");
			}
			foreach($files as $file){
				$zip->addFile("application\downloads\\" . $file['name']);
			}
			$zip->close();
			echo base_url('application/downloads/files.zip');
		}
	}*/
	public function getState(){
		$data['states'] = array();
		if($this->input->post('id',true)){
			$data['states'] = $this->db->query('SELECT name,id FROM states WHERE country_id = '. $this->input->post('id',true))->result();
		}
		echo json_encode($data);
	}
	public function cart(){
		$get = $this->input->get(null,true);
		$post = $this->input->post(null,true);
		if (isset($get['remove'])){
			$this->cart->remove($get['remove']);
			$this->session->set_flashdata('error', 'store.product_remove_successfully');
			if(isset($get['checkout_page'])){ echo json_encode(array("success"=>true));die; }
			redirect($this->cart->getStoreUrl('cart'));
		}
		else if ($this->input->server('REQUEST_METHOD') == 'POST'){
			foreach ($this->input->post('quantity',true) as $cart_id => $quantity) {
				$this->cart->update($cart_id,$quantity);
			}
			if(isset($post['checkout_page'])) { 
				echo json_encode(array("success"=>true));die; 
			}
			$this->session->set_flashdata('error', 'store.cart_update_successfully');
			redirect($this->cart->getStoreUrl('cart'));
		}
		$data['base_url'] = $this->cart->getStoreUrl();
		$data['cart_url'] = $this->cart->getStoreUrl('cart');
		$data['products'] = $this->cart->getProducts();
		$data['sub_total'] = $data['total'] = $this->cart->subTotal();
		$this->storeapp->view("cart",$data);
	}
	public function checkout_confirm(){
		$is_logged = $this->cart->is_logged();
		$this->cart->reloadCart();

		$data['allow_comment'] = $this->cart->allow_comment;
		$data['allow_upload_file'] = $this->cart->allow_upload_file;
		
		$this->load->view("form/checkout_confirm",$data);
	}
	public function add_coupon() {
		$coupon_code = $this->input->post("coupon_code",true);
		$form_id = $this->session->userdata('form_id');
		$this->load->model("Form_model");
		$this->load->model("Coupon_model");
		$coupon = $this->Form_model->getByCode($coupon_code);
		
		$json = array();
		
		if($coupon){
			/*$formDetails = $this->db->query("SELECT * FROM `form` WHERE form_id = ".$form_id)->row();
			if($coupon['form_coupon_id'] != $formDetails->form_id){	
				$json['error'] = "Invalid Coupon Code";
			}*/
			$logged_user = $this->cart->is_logged();
			if ($logged_user) {
				$total_use = $this->Coupon_model->getUses($logged_user['id'],$coupon_code);
				 
				if($total_use >= $coupon['uses_total']){
					$json['error'] = "Coupon is expired or reached its usage limit!";
				}
			}
			if (!isset($json['error'])) {
				$json['success'] = 'Coupon Code Apply Successfully.!';
				$this->cart->addCoupon($form_id,$coupon,'form');
			}
		} else {
			$json['error'] = "Invalid Coupon Code";
		}
		echo json_encode($json);
	}
	public function confirm_order(){
		$this->load->library('form_validation');
		$user = $this->cart->is_logged();
		if($user){
			$this->cart->reloadCart();
			$allow_shipping = $this->cart->allow_shipping;

			if($allow_shipping){
				$this->form_validation->set_rules('address', 'Address', 'required|trim');
				$this->form_validation->set_rules('country', 'Country', 'required|trim');
				$this->form_validation->set_rules('state', 'State', 'required|trim');
				$this->form_validation->set_rules('city', 'City', 'required|trim');
				$this->form_validation->set_rules('zip_code', 'Postal Code', 'required|trim');
				$this->form_validation->set_rules('phone', 'Phone Number', 'required|trim');
			}

			$this->form_validation->set_rules('payment_method', 'Payment Method', 'required|trim');
			$this->form_validation->set_rules('agree', 'Agree', 'required|trim');
			$recaptcha = 123;
			//$recaptcha = $this->input->post('g-recaptcha-response',true);
			if ($this->form_validation->run() == FALSE || empty($recaptcha)) {
				$json['errors'] = $this->form_validation->error_array();
				if(isset($json['errors']['agree'])){
					$json['error'] = $json['errors']['agree'];
					unset($json['errors']['agree']);
				}
				if(empty($recaptcha)){
					$json['error'] = 'reCaptcha does not match!';
				}
			} else {
				if(!empty($_FILES['downloadable_file'])){
					$files = $_FILES['downloadable_file'];
					$count_file = count($_FILES['downloadable_file']['name']);
					$this->load->helper('string');
					
					for($i=0; $i<$count_file; $i++){

				        $FILES['downloadable_files']['name'] = md5(random_string('alnum', 10));
				        $FILES['downloadable_files']['type'] = $files['type'][$i];
				        $FILES['downloadable_files']['tmp_name'] = $files['tmp_name'][$i];
				        $FILES['downloadable_files']['error'] = $files['error'][$i];
				        $FILES['downloadable_files']['size'] = $files['size'][$i];    
				     	
				     	$extension = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
				     	if(in_array($extension, array('jpeg','jpg','pdf','gif','doc','docx','png','zip','tar'))){
				     		move_uploaded_file($FILES['downloadable_files']['tmp_name'], APPPATH.'/downloads_order/'. $FILES['downloadable_files']['name']);
							
							$downloadable_files[] = array(
								'type' => $FILES['downloadable_files']['type'],
								'name' => $FILES['downloadable_files']['name'],
								'mask' => $files['name'][$i],
							);
				     	}else{
				     		$json['error'] = "File type {$extension} not allow";
				     	}
		    		}
				}

				if(!isset($json['errors']) && !isset($json['error'])){
					$data = $this->input->post(null,true);
					$this->load->model('Product_model');

					if($allow_shipping){					
						$check = $this->db->query("SELECT id FROM shipping_address WHERE user_id =  ". $user['id'])->row();
						$shipping = array(
							'user_id'    => $user['id'],
							'address'    => $data['address'],
							'country_id' => (int)$data['country'],
							'state_id'   => (int)$data['state'],
							'city'       => $data['city'],
							'zip_code'   => $data['zip_code'],
							'phone'      => $data['phone'],
						);
						if($check){
							$this->db->update("shipping_address",$shipping,['id' => $check->id]);
						}else{
							$this->db->insert("shipping_address",$shipping);
						}
					}

	 				$discount = 0;
	 				if($this->session->userdata('form_coupon_discount')){
	 					$discount = $this->session->userdata('form_coupon_discount');
	 				}

	 				$ipInformatiom = $this->Product_model->ip_info();
					$order = array(
						'user_id'         => isset($user['id']) ? $user['id'] : '',
						'address'         => isset($data['address']) ? $data['address'] : '',
						'country_id'      => isset($data['country']) ? (int)$data['country'] : 0,
						'state_id'        => isset($data['state']) ? (int)$data['state'] : 0,
						'city'            => isset($data['city']) ? $data['city'] : '',
						'zip_code'        => isset($data['zip_code']) ? $data['zip_code'] : '',
						'phone'           => isset($data['phone']) ? $data['phone'] : '',
						'shipping_cost'   => 0,
						'total'           => $this->cart->finalTotal(),
						'payment_method'  => $data['payment_method'],
						'allow_shipping'  => $allow_shipping,
						'coupon_discount' => $discount,
						'total_commition' => 0,
						'shipping_charge' => 0,
						'currency_code'   => 'USD',
						'created_at'      => date("Y-m-d H:i:s"),
						'ip'              => @$ipInformatiom['ip'],
						'country_code'    => @$ipInformatiom['country_code'],
						'files'           => isset($downloadable_files) ? json_encode($downloadable_files) : '[]',
						'comment'         => isset($data['comment']) ? $data['comment'] : '',
					);

					$this->db->insert("order",$order);
					$order_id = $this->db->insert_id();
					$this->load->model('Product_model');
					$cart_product = $this->cart->getProducts();
					foreach ($cart_product as $key => $product) {
						$_user = $this->Product_model->getUserDetails((int)$product['refer_id']);
						$commission = false;
						if($_user && $_user['type'] == 'user'){
							$commission = $this->Product_model->formcalcCommitions($product, 'sale');
						}

						$_product = array(
							'order_id'        => $order_id,
							'product_id'      => $product['product_id'],
							'refer_id'        => ($_user && $_user['type'] == 'user') ? $_user['id'] : 0,
							'form_id'         => $this->session->userdata('form_id'),
							'price'           => (float)$product['product_price'],
							'total'           => (float)$product['total'],
							'quantity'        => (int)$product['quantity'],
							'commission'      => ($commission) ? $commission['commission'] : 0,
							'commission_type' => ($commission) ? $commission['type'] : '',
							'coupon_code'     => $product['coupon_code'],
							'coupon_name'     => $product['coupon_name'],
							'coupon_discount' => $product['coupon_discount'],
							'allow_shipping'  => $product['allow_shipping'],
						);
						$this->db->insert("order_products",$_product);
					}
					$payment_methods = $this->session->userdata('payment_methods');

					if($payment_methods && isset($payment_methods[$data['payment_method']])){
						require APPPATH."/payments/controllers/". $data['payment_method'] .".php";

						$pdata = $payment_methods[$data['payment_method']];

						$code = $data['payment_method'];
						$obj = new $code($this);

						$update1['payment_method'] = $obj->title;
						$this->Product_model->update_data( 'order', $update1,array('id' => $order_id) );

						$data['allow_comment'] = $this->cart->allow_comment;
						$data['allow_upload_file'] = $this->cart->allow_upload_file;

						$data['base_url'] = base_url();
						$this->session->set_userdata('payment_method',$code);
						
						$this->load->library("storeapp");
						//$json['confirm'] = $this->load->view("store/checkout_confirm",$data, true);
						$json['confirm'] = $this->storeapp->view("checkout_confirm",$data, true, true);
						$data['thankyou_url'] = base_url('store/thankyou/'. $order_id );
						$pdata['order_id'] = $order_id;
						$pdata['order_info'] = $this->Order_model->getOrder($order_id, 'store');
						$pdata['products']   = $this->Order_model->getProducts($order_id);

						ob_start();
						$obj->getConfirm($pdata);
						$json['confirm'] .= ob_get_clean();
					}

					/*if($data['payment_method'] == 'paypal'){
						
						$setting = $this->Product_model->getSettings('paymentsetting');
						$setting_site 	= $this->Product_model->getSettings('site');
						$config = array(
			                'Sandbox'      => $setting['payment_mode'] == 'sandbox' ? TRUE : FALSE,
			                'APIUsername'  => $setting['api_username'],
			                'APIPassword'  => $setting['api_password'],
			                'APISignature' => $setting['api_signature'],
			                'APISubject'   => '',
			                'APIVersion'   => '98.0' ,
			            );
			            $this->load->library('paypal/Paypal_pro', $config);
			            $SECFields = array(
							'returnurl' => site_url('form/paypal_notify/' . $order_id), 
							'cancelurl' => site_url('form/paypal_cancel/' . $order_id), 
							'brandname' => $setting_site['name'],
							'hdrimg'    => ''
			            );
			            $Payments = array();
			            $PaymentOrderItems = array();
			            foreach ($cart_product as $key => $product) {
				            $Item = array(
								'name'    => $product['product_name'],
								'desc'    => $product['product_name'],
								'amt'     => $product['total'],
								'number'  => $product['product_id'],
								'qty'     => 1,
								'taxamt'  => 0,
								'itemurl' => '', 
				            );
				            array_push($PaymentOrderItems, $Item);
			           	}
			           	if($this->session->userdata('form_coupon_discount')){
			           		$Item = array(
								'name'    => 'Coupon Discount',
								'desc'    => 'Coupon Discount',
								'amt'     => -$this->session->userdata('form_coupon_discount'),
								'number'  => 0,
								'qty'     => 1,
								'taxamt'  => 0,
								'itemurl' => '', 
				            );
				            array_push($PaymentOrderItems, $Item);
			           	}
			            $Payment = array(
							'order_items'  => $PaymentOrderItems,
							'amt'          => $order['total'],
							'itemamt'      => $order['total'],
							'currencycode' => $setting['payment_currency'],
			            );
			            array_push($Payments, $Payment);
			            $PayPalRequestData = array(
							'SECFields' => $SECFields,
							'Payments'  => $Payments,
			            );
			            $PayPalResult = $this->paypal_pro->SetExpressCheckout($PayPalRequestData);
			            if (isset($PayPalResult['ACK']) && $this->paypal_pro->APICallSuccessful($PayPalResult['ACK'])) {
			                $json['location'] = $PayPalResult['REDIRECTURL'];
			                $this->cart->clearCart();
			            } else {
			                $json['error'] = isset($PayPalResult['ERRORS'][0]['L_LONGMESSAGE']) ? $PayPalResult['ERRORS'][0]['L_LONGMESSAGE'] : '';
			            }
			        }else if($data['payment_method'] == 'bank_transfer'){
		                $this->cart->clearCart();
			            $json['location'] = site_url('form/bank_transfer_notify/' . $order_id);
			        }*/
			    }
			}
		}else{
			$json['error'] = "User not login";
		}
		echo json_encode($json);
	}
	public function paypal_notify($order_id){
		$this->load->model("Order_model");
		$this->load->model("Product_model");
		$order_info = $this->Order_model->getOrder($order_id);
		$setting 	= $this->Product_model->getSettings('paymentsetting');
		$config = array(
            'Sandbox'      => $setting['payment_mode'] == 'sandbox' ? TRUE : FALSE,
            'APIUsername'  => $setting['api_username'],
            'APIPassword'  => $setting['api_password'],
            'APISignature' => $setting['api_signature'],
            'APISubject'   => '',
            'APIVersion'   => '98.0' ,
        );
        $this->load->library('paypal/Paypal_pro', $config);
		$token        = $this->input->get('token');
		$payer_id     = $this->input->get('PayerID');
		$PayPalResult = $this->paypal_pro->GetExpressCheckoutDetails($token);
        if ($PayPalResult['ACK'] == 'Success') {
            $transaction_amount = $order_info['total'];
            $DECPFields = array(
				'token'   => $token,
				'payerid' => $payer_id,
            );
            $Payments = array();
            $Payment = array(
                'amt' => $transaction_amount,
                'currencycode' => $setting['payment_currency'],
            );
            array_push($Payments, $Payment);
            $PayPalRequestData = array(
                'DECPFields' => $DECPFields,
                'Payments' => $Payments,
            );
            $PayPalResult = $this->paypal_pro->DoExpressCheckoutPayment($PayPalRequestData);
            
            if ($this->paypal_pro->APICallSuccessful($PayPalResult['ACK'])) {
                $transaction_id = $PayPalResult['PAYMENTINFO_0_TRANSACTIONID'];
				$update['status'] = 1;
				$update['txn_id'] = $transaction_id;
				$this->Product_model->update_data( 'order', $update,array('id' => $order_id) );
				$historyData = array(
					'order_id'        => $order_id,
					'paypal_status'   => $PayPalResult['ACK'],
					'comment'         => json_encode($PayPalResult),
					'created_at'      => date("Y-m-d H:i:s"),
					'payment_mode'    => 'paypal',
					'history_type'    => 'payment',
					'order_status_id' => 1,
				);

		 		$products = $this->Order_model->getProducts($order_id);
		 		foreach ($products as $key => $product) {
		 			if($product['refer_id'] > 0){
						$this->Wallet_model->addTransaction(array(
							'user_id'      => $product['refer_id'],
							'amount'       => $product['commission'],
							'comment'      => 'Commission for order Id order_id='. $order_id .' | Order By : '. $order_info['firstname'] ." " .$order_info['lastname'] ."<br> Sale done from ip_message" ,
							'type'         => 'sale_commission',
							'reference_id' => $order_id,
						));
						$level = $this->Product_model->getMyLevel($product['refer_id']);
				       	
				       	$setting = $this->Product_model->getSettings('referlevel');
    					$max_level = isset($setting['levels']) ? (int)$setting['levels'] : 3;

						//foreach (array(1,2,3) as $l) {
						for ($l=1; $l <= $max_level ; $l++) { 
			                $s = $this->Product_model->getSettings('referlevel_'. $l);
			                $levelUser = (int)$level['level'. $l];
			                if($s && $levelUser > 0){
			                    $_giveAmount = (float)$s['sale_commition'];
			                    $this->Wallet_model->addTransaction(array(
			                        'user_id' => $levelUser,
			                        'amount' => $_giveAmount,
			                        'dis_type' => '',
			                        'comment' => "Level {$l} : ".'Commission for order Id order_id='. $order_id .' | User : '. $order_info['firstname'] ." " .$order_info['lastname'],
			                        'type' => 'refer_sale_commission',
			                        'reference_id' => $order_id,
			                    ));
			                }
			            }
					}
		 		}
				$this->sendOrderNoti($order_info,$products);
				$this->load->model('Mail_model');
				$this->Mail_model->send_new_order_mail($order_id);
				$this->db->insert('orders_history',$historyData);
				$this->cart->clearCart();
				$this->session->unset_userdata('form_coupon_discount');
				$this->session->unset_userdata('form_id');
				$this->session->unset_userdata('form_refer_id');
				redirect(base_url('form/thankyou/'. $order_id ));die;
            } 
        }
	}
	public function bank_transfer_notify($order_id){
		$this->load->model("Order_model");
		$this->load->model("Product_model");
		$order_info = $this->Order_model->getOrder($order_id);
		$setting 	= $this->Product_model->getSettings('paymentsetting');
		$products = $this->Order_model->getProducts($order_id);
 		foreach ($products as $key => $product) {
 			if($product['refer_id'] > 0){
				$this->Wallet_model->addTransaction(array(
					'user_id'      => $product['refer_id'],
					'amount'       => $product['commission'],
					'comment'      => 'Commission for order Id order_id='. $order_id .' | Order By : '. $order_info['firstname'] ." " .$order_info['lastname'] ."<br> Sale done from ip_message" ,
					'type'         => 'sale_commission',
					'reference_id' => $order_id,
					'status'       => 0,
				));

				$level = $this->Product_model->getMyLevel($product['refer_id']);
		       	$setting = $this->Product_model->getSettings('referlevel');
				$max_level = isset($setting['levels']) ? (int)$setting['levels'] : 3;
				//foreach (array(1,2,3) as $l) {
				for ($l=1; $l <= $max_level ; $l++) { 
	                $s = $this->Product_model->getSettings('referlevel_'. $l);
	                $levelUser = (int)$level['level'. $l];
	                if($s && $levelUser > 0){
	                    $_giveAmount = (float)$s['sale_commition'];
	                    $this->Wallet_model->addTransaction(array(
							'status'       => 0,
							'user_id'      => $levelUser,
							'amount'       => $_giveAmount,
							'dis_type'     => '',
							'comment'      => "Level {$l} : ".'Commission for order Id order_id='. $order_id .' | User : '. $order_info['firstname'] ." " .$order_info['lastname'],
							'type'         => 'refer_sale_commission',
							'reference_id' => $order_id,
	                    ));
	                }
	            }
			}
 		}
 		$historyData = array(
			'order_id'        => $order_id,
			'payment_mode'    => 'bank_transfer',
			'paypal_status'   => 'Processed',
			'comment'         => json_encode(array()),
			'created_at'      => date("Y-m-d H:i:s"),
			'order_status_id' => 7,
			'history_type'    => 'payment',
		);
		$this->sendOrderNoti($order_info,$products);
		$this->load->model('Mail_model');
		$this->Mail_model->send_new_order_mail($order_id);
		$this->db->insert('orders_history',$historyData);
		$this->cart->clearCart();
		$this->session->unset_userdata('form_coupon_discount');
		$this->session->unset_userdata('form_id');
		$this->session->unset_userdata('form_refer_id');
		
		$update1['status'] = 7;
		$this->Product_model->update_data( 'order', $update1,array('id' => $order_id) );
		
		redirect(base_url('form/thankyou/'. $order_id ));die;
	}
	public function sendOrderNoti($order_info,$products){
		$userDetail = $this->Product_model->getUserDetails($order_info['user_id']);
		$cdate = date('Y-m-d H:i:s');
		$notificationData = array(
			'notification_url'          => '/vieworder/'.$order_info['id'],
			'notification_type'         =>  'order',
			'notification_title'        =>  'New Order Generated by '.$userDetail['username'],
			'notification_viewfor'      =>  'admin',
			'notification_actionID'     =>  $order_info['id'],
			'notification_description'  =>  $userDetail['firstname'].' '.$userDetail['lastname'].' created a new order at affiliate Program on '.date('Y-m-d H:i:s'),
			'notification_is_read'      =>  '0',
			'notification_created_date' =>  $cdate,
			'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
		);
		$this->insertnotification($notificationData);
		$notificationData = array(
			'notification_url'          => '/vieworder/'.$order_info['id'],
			'notification_type'         =>  'order',
			'notification_title'        =>  'Your Order has been place',
			'notification_viewfor'      =>  'client',
			'notification_view_user_id' =>  $userDetail['id'],
			'notification_actionID'     =>  $order_info['id'],
			'notification_description'  =>  'Your Order has been place',
			'notification_is_read'      =>  '0',
			'notification_created_date' =>  $cdate,
			'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
		);
		$this->insertnotification($notificationData);
		foreach ($products as $key => $product) {
			if($product['refer_id'] > 0){
				$notificationData = array(
					'notification_url'          => '/vieworder/'.$order_info['id'],
					'notification_type'         =>  'order',
					'notification_title'        =>  'New Order Generated by '.$userDetail['username'],
					'notification_viewfor'      =>  'user',
					'notification_view_user_id' =>  $product['refer_id'],
					'notification_actionID'     =>  $order_info['id'],
					'notification_description'  =>  $userDetail['firstname'].' '.$userDetail['lastname'].' created a new order which you refered to him at affiliate Program on '.date('Y-m-d H:i:s'),
					'notification_is_read'      =>  '0',
					'notification_created_date' =>  $cdate,
					'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
				);
				$this->insertnotification($notificationData);
			}
		}
	}
	private function insertnotification($postData = null){
		if(!empty($postData)){
			$this->Product_model->create_data('notification', $postData);
		}
	}
	public function paypal_cancel(){
		$formsetting = $this->session->userdata();
		redirect(base_url('form/'. $formsetting['form_id'] ."/". $formsetting['form_refer_id']));
	}
	public function thankyou($order_id){
		$this->load->model('Order_model');
		$this->load->model('Product_model');
		$this->load->model('Form_model');
		$user = $this->session->userdata('client') ? $this->session->userdata('client') : '';
		$data['client_loged'] = $this->session->userdata('client') ? true : false;
		$data['order'] = $this->Order_model->getOrder($order_id, 'store');
		$data['products'] = $this->Order_model->getProducts($order_id);
		$data['totals'] = $this->Order_model->getTotals($data['products'],$data['order']);
		
		$this->load->model('User_model');
		$admin_info = $this->User_model->get_user_by_type('admin');
		$data['store_name'] =  $admin_info['firstname'].' '.$admin_info['lastname'];
		$data['store_email'] =  $admin_info['email'];

		if($data['order']['user_id'] == $user['id']){
			$data['affiliateuser'] = $this->Order_model->getAffiliateUser($order_id);
			$data['payment_history'] = $this->Order_model->getHistory($order_id);
			$data['status'] = $this->Order_model->status;
			$data['order_history'] = $this->Order_model->getHistory($order_id, 'order');
			$data['paymentsetting'] = $this->Product_model->getSettings('paymentsetting');
			$this->load->view('store/thanks', $data);
		}
		else{
			die("You are not allow to see.. !");
		}
	}
}