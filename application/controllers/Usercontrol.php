<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Usercontrol extends MY_Controller {
	function __construct() {
		parent::__construct();
		___construct(1);

		$this->load->model('user_model', 'user');
		$this->load->model('Product_model');
		$this->load->helper('share');
		$this->load->helper('url');
		$this->load->library('user_agent');
		$this->load->model('IntegrationModel');

		$session='';
		$path_info = (isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : (!empty($_SERVER['ORIG_PATH_INFO']) ? $_SERVER['ORIG_PATH_INFO'] : '');			
		
		if($this->router->class!='usercontrol' &&  $this->router->method !='register'){
			if (!$session && $this->router->class !='usercontrol' && $this->router->method != 'index' ) {
				redirect('usercontrol');
			} else if ($session && ($path_info == '/usercontrol' || $path_info == '/usercontrol/')) {
				redirect('usercontrol/dashboard');
			}
		}
	}

	public function duplicateProduct($product_id){
		$userdetails = $this->userdetails();
		if(empty($userdetails)) redirect('usercontrol/dashboard');

		$this->Product_model->duplicateProduct($product_id,true);

		$this->session->set_flashdata('success', 'Product Duplicate Successfully');
		redirect(base_url('usercontrol/store_products'));
	}

	public function integration(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)) redirect('usercontrol/dashboard');

		$market_vendor = $this->Product_model->getSettings('market_vendor');
		if((!isset($userdetails['is_vendor']) || !$userdetails['is_vendor']) || (int)$market_vendor['marketvendorstatus'] == 0) show_404();

		$data['integration_modules'] = $this->modules_list();

		$this->view($data, 'integration/index','usercontrol');
	}

	private function modules_list(){
	    
	    $integration_modules['general_integration'] = array(
			'name' => "General Integration",
			'image' => base_url('assets/integration/general_integration-logo.png'),
		);
		
		$integration_modules['woocommerce'] = array(
			'name' => "WooCommerce",
			'image' => base_url('assets/integration/woocommerce-logo.png'),
		);

		$integration_modules['prestashop'] = array(
			'name' => "PrestaShop",
			'image' => base_url('assets/integration/prestashop-logo.png'),
		);

		$integration_modules['opencart'] = array(
			'name' => "Opencart",
			'image' => base_url('assets/integration/opencart-logo.png'),
		);

		$integration_modules['magento'] = array(
			'name' => "Magento",
			'image' => base_url('assets/integration/magento-logo.png'),
		);

		$integration_modules['shopify'] = array(
			'name' => "Shopify",
			'image' => base_url('assets/integration/shopify-logo.png'),
		);

		$integration_modules['bigcommerce'] = array(
			'name' => "Big Commerce",
			'image' => base_url('assets/integration/big-commerce.png'),
		);

		$integration_modules['paypal'] = array(
			'name' => "Paypal",
			'image' => base_url('assets/integration/paypal.jpg'),
		);

		$integration_modules['oscommerce'] = array(
			'name' => "osCommerce",
			'image' => base_url('assets/integration/oscommerce.jpg'),
		);

		$integration_modules['zencart'] = array(
			'name' => "Zen Cart",
			'image' => base_url('assets/integration/zencart.png'),
		);

		$integration_modules['xcart'] = array(
			'name' => "XCART",
			'image' => base_url('assets/integration/xcart.jpg'),
		);

		$integration_modules['laravel'] = array(
			'name' => "Laravel",
			'image' => base_url('assets/integration/laravel.png'),
		);

		$integration_modules['cakephp'] = array(
			'name' => "Cake PHP",
			'image' => base_url('assets/integration/cakephp.png'),
		);

		$integration_modules['codeigniter'] = array(
			'name' => "CodeIgniter",
			'image' => base_url('assets/integration/codeIgniter.png'),
		);

		$integration_modules['wp_user_register'] = array(
			'name' => "Wordpress/Woocommerce registration bridge",
			'image' => base_url('assets/integration/WordpressWoocommerceRegistrationBridge.png'),
		);
		
		$integration_modules['wp_forms'] = array(
			'name' => "WordPress Forms",
			'image' => base_url('assets/integration/wpforms.png'),
		);
		$integration_modules['postback'] = array(
			'name' => "Postback URL",
			'image' => base_url('assets/integration/postback.png'),
		);

		$integration_modules['show_affiliate_id'] = array(
			'name' => "Show Affiliate ID",
			'image' => base_url('assets/integration/show-affiliate-id.png'),
		);

		$integration_modules['wp_show_affiliate_id'] = array(
			'name' => "Wordpress Show Affiliate ID",
			'image' => base_url('assets/integration/wp-show-affiliate-id.jpg'),
		);

		return $integration_modules;
	}

	public function instructions($module_key){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }

		$data['integration_modules'] = $this->modules_list();
		$data['module_key'] = $module_key;

		
		$data['action_codes'] = $this->db->select('integration_tools.action_code')
		->from('integration_tools')
		->where("tool_type",'action')
		->where("status",1)
		->get()
		->result_array();

		$data['general_codes'] = $this->db->select('integration_tools.general_code')
		->from('integration_tools')
		->where("tool_type",'general_click')
		->where("status",1)
		->get()
		->result_array();
		$data['module'] = $data['integration_modules'][$module_key];

		$data['views'] = '';
		if(in_array($module_key, array('laravel','cakephp','codeigniter'))){
			switch ($module_key) {
				case 'laravel':
					$data['views'] = $this->load->view('admincontrol/integration/ins_laravel', $data, true);
					break;
				case 'cakephp':
					$data['views'] = $this->load->view('admincontrol/integration/ins_cakephp', $data, true);
					break;
				case 'codeigniter':
					$data['views'] = $this->load->view('admincontrol/integration/ins_codeigniter', $data, true);
					break;
				default: break;
			}
		}
		

		$this->view($data, 'integration/instructions','usercontrol');
	}

	public function change_language($language_id){
		$language = $this->db->query("SELECT * FROM language WHERE id=".$language_id)->row_array();
		if($language){
			$_SESSION['userLang'] = $language_id;
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
		else { show_404(); }
	}
	public function change_currency($currency_code){
		$language = $this->db->query("SELECT * FROM currency WHERE code = '{$currency_code}' ")->row_array();
		if($language){
			$_SESSION['userCurrency'] = $currency_code;
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
		else { show_404(); }
	}
	public function getSiteSetting(){return $this->Product_model->getSettings('site');}

	public $loginUser = [];
	public function userdetails(){
		if(!$this->loginUser){
			$u = $this->session->userdata('user');	
			if($u){
				$this->loginUser = $this->db->query("SELECT * FROM users WHERE id=". $u['id'])->row_array();
			}
		}

		return $this->loginUser;
		return $this->session->userdata('user');
	}	
	public function myreferal_ajax(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		$data = $this->Product_model->getMyUnder($userdetails['id']);

		echo json_encode($data);die;
	}


	public function resetpassword($token){
		$tok  =  $this->db->query("SELECT * FROM password_resets WHERE token like '{$token}' ")->row();
		$post = $this->input->post(null,true);

		if($tok){
			if (isset($post['conf_password'])) {
				if($post['password'] == $post['conf_password']){
					$password = $this->input->post('password',true);
					$res = array('password'=>sha1($password));
					$this->db->where('email',$tok->email);
					$this->db->update('users',$res);
					$this->db->query("DELETE  FROM password_resets WHERE email like '{$tok->email}' ");
					$this->session->set_flashdata('success' , __('user.password_reset_successfully_successfully'));
					$user = $this->db->query("SELECT * FROM `users` WHERE email like '{$tok->email}' ")->row();
					if($user->type == 'client'){
						redirect(base_url('store/login'));
					} else {
						redirect(base_url());
					}
				}
				else{
					$this->session->set_flashdata('error',__('user.confirm_password_not_match'));
					redirect(base_url('resetpassword/' . $token));
				}
			}
			$this->load->view('resetpassword');
		}
		else
		{
			die("Token Expire..!");
		}
	}
	public function index(){ redirect('/', 'refresh'); }
	public function notification(){
		if(!$this->userdetails()){ redirect('/login', 'refresh'); }
		$this->load->library('pagination');
    	$this->load->helper('url');
    	$config['base_url'] = base_url('usercontrol/notification');
		$config['per_page'] = 10;
		$post = $this->input->post(null,true);

		if (isset($post['delete_ids'])) {
			$delete_ids = implode(",", $post['delete_ids']);
			$this->db->query("DELETE FROM notification WHERE notification_id IN ({$delete_ids})");
			echo json_encode(array());
			die;
		}
		
		$data['title'] = 'Notification';
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$notification = $this->user->getAllNotificationPaging('user',$this->userdetails()['id'],$config['per_page'],$page);
		$config['total_rows'] = $notification['total'];
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['notifications'] = $notification['notifications'];
		$data['user_id'] = $this->userdetails()['id'];
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);
		$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/dashboard/notification', $data);
		$this->load->view('usercontrol/includes/footer', $data);
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
    public function getState(){
        $this->load->model('User_model');
        $country_id = $this->input->post('country_id',true);
        $states = $this->User_model->getState($country_id);
        echo json_encode($states);
        die;
    }
	public function auth($action){
		$json = array();
		$post = $this->input->post(null,true);
		if ($action == 'login') {
			$username = $this->input->post('username',true);
			$password = $this->input->post('password',true);

			$checking_key = (isset($post['type']) && $post['type'] == 'admin') ? 'admin_login' : 'affiliate_login';
			 
			$googlerecaptcha = $this->Product_model->getSettings('googlerecaptcha');
			if (isset($googlerecaptcha[$checking_key]) && $googlerecaptcha[$checking_key]) {
				if($post['g-recaptcha-response'] == ''){
					if($checking_key != 'admin_login'){
						$json['errors']['username'] = 'Invalid Recaptcha';
					}
					//$json['errors']['g-recaptcha-response'] = 'Invalid Recaptcha';
				}
			}

			if( count($json['errors']) == 0 ){
				if (isset($googlerecaptcha[$checking_key]) && $googlerecaptcha[$checking_key]) {
					$post = http_build_query(
					 	array (
					 		'response' => $post['g-recaptcha-response'],
					 		'secret' => $googlerecaptcha['secretkey'],
					 		'remoteip' => $_SERVER['REMOTE_ADDR']
					 	)
					);
					$opts = array('http' => 
						array (
							'method' => 'POST',
							'header' => 'application/x-www-form-urlencoded',
							'content' => $post
						)
					);
					$context = stream_context_create($opts);
					$serverResponse = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
					if (!$serverResponse) {
						$json['errors']['username'] = 'Failed to validate Recaptcha';
						$json['errors']['captch_response'] = 'Failed to validate Recaptcha';
					}
					$result = json_decode($serverResponse);

					if (!$result->success) {
						$json['errors']['username'] = 'Invalid Recaptcha';
						$json['errors']['captch_response'] = 'Invalid Recaptcha';
					}
				}
			}

			$post = $this->input->post(null,true);
			if( count($json['errors']) == 0 ){
				if($this->authentication->login($username, $password)){
					$user_details_array=$this->user->login($username);

					if(!empty($user_details_array['username']) && sha1($password)==$user_details_array['password']){

						if($user_details_array['status']){
							
							if($user_details_array['type'] == 'user' && isset($post['type']) && $post['type'] == 'user' ){
								$this->user->update_user_login($user_details_array['id']);
								$this->session->set_userdata(array('user'=>$user_details_array));
								$json['redirect'] = base_url('usercontrol/dashboard');

							}else if($user_details_array['type'] == 'admin' && isset($post['type']) && $post['type'] == 'admin' ){
		                        $this->user->update_user_login($user_details_array['id']);
		                        $this->session->set_userdata(array('administrator'=>$user_details_array));
		                        $json['redirect'] = base_url('admincontrol/dashboard');
		                    }else if($user_details_array['type'] == 'client' && !isset($post['type'])){
		                        $this->user->update_user_login($user_details_array['id']);
		                        $this->session->set_userdata(array('client'=>$user_details_array));
		                        $l = $this->session->userdata('login_data');
		                        if($l['refid'] && $l['product_slug'] && $l['user_id']){
									$json['redirect'] = base_url('product/payment/'. $l['product_slug'].'/'.$l['user_id']);
								}else if($this->session->userdata('refer_id')){
									$json['redirect'] = base_url('store/'. base64_encode($this->session->userdata('refer_id')));
								}else{
									$json['redirect'] = base_url('store/profile/');
								}
		                    }else {
								$json['errors']['username'] = __('user.invalid_valid_user');
							}
						} else{
							$json['errors']['username'] = __('user.account_block_message');		
						}
					}
				} else {
					$json['errors']['username'] = __('user.invalid_credentials');
				}
			}
		}
		else if ($action == 'register') {
			$refid = isset($post['refid']) ? $post['refid'] : '';
			$post['affiliate_id'] = !empty($refid) ? base64_decode($refid) : 0;
			if($this->userdetails()){
				$json['redirect'] = base_url('usercontrol/dashboard');
			} else {

				$this->load->library('form_validation');
				$this->form_validation->set_rules('firstname', 'First Name', 'required|trim');
				$this->form_validation->set_rules('lastname', 'Last Name', 'required|trim');
				$this->form_validation->set_rules('username', 'Username', 'required|trim');
				$this->form_validation->set_rules('email', 'Email', 'required|valid_email|xss_clean');
				$this->form_validation->set_rules('terms', 'Terms and Condition', 'required');
				$this->form_validation->set_rules('password', 'Password', 'required|trim', array('required' => '%s is required'));
				$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim', array('required' => '%s is required'));
                $this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim|matches[password]', array('required' => '%s is required'));
                $this->form_validation->set_rules('address', 'Address', 'required|trim|xss_clean', array('required' => '%s is required'));
                $this->form_validation->set_rules('state', 'State', 'required', array('required' => '%s is required'));
                $this->form_validation->set_rules('paypal_email', 'Payal Email', 'required|valid_email|xss_clean', array('required' => '%s is required'));
                $this->form_validation->set_rules('phone_number', 'Phone Number', 'required|regex_match[/^[0-9]{10}$/]', array('required' => '%s is required'));
				$this->form_validation->set_rules('alternate_phone_number', 'Alternate Phone Number', 'required|regex_match[/^[0-9]{10}$/]', array('required' => '%s is required'));
				if ($this->form_validation->run() == FALSE) {
					$json['errors'] = $this->form_validation->error_array();
				} else {
					$checkEmail = $this->db->query("SELECT id FROM users WHERE email like ". $this->db->escape($this->input->post('email',true)) ." ")->num_rows();
					if($checkEmail > 0){ $json['errors']['email'] = "Email Already Exist"; }
					$checkUsername = $this->db->query("SELECT id FROM users WHERE username like ". $this->db->escape($this->input->post('username',true)) ." ")->num_rows();
					if($checkUsername > 0){ $json['errors']['username'] = "Username Already Exist"; }

					if(!isset($post['terms'])){
						$json['warning'] = __('user.accept_our_affiliate_policy');
					}

					if(!isset($json['errors'])){	
						$user_type = 'user';
						$geo = $this->ip_info();
						
						
						$refid = !empty($refid) ? base64_decode($refid) : 0;
						$commition_setting = $this->Product_model->getSettings('referlevel');

						$disabled_for = json_decode( (isset($commition_setting['disabled_for']) ? $commition_setting['disabled_for'] : '[]'),1); 
						if((int)$commition_setting['status'] == 0){ $refid  = 0; }
						else if((int)$commition_setting['status'] == 2 && in_array($refid, $disabled_for)){ $refid = 0; }

						$data = $this->user->insert(array(
							'firstname'                 => $this->input->post('firstname',true),
							'lastname'                  => $this->input->post('lastname',true),
							'email'                     => $this->input->post('email',true),
							'username'                  => $this->input->post('username',true),
							'password'                  => sha1($this->input->post('password',true)),
							'refid'                     => $refid,
							'type'                      => $user_type,
                            //'Country'                   => (int)$geo['id'],
							'Country'                   => $this->input->post('country',true),
							'City'                      => (string)$geo['city'],
							'phone'                     => $this->input->post('phone_number',true),
							'twaddress'                 => $this->input->post('address',true),
							'address1'                  => '',
							'address2'                  => '',
							'ucity'                     => '',
							'ucountry'                  => '',
							'state'                     => $this->input->post('state',true),
							'uzip'                      => '',
							'avatar'                    => '',
							'online'                    => '0',
							'unique_url'                => '',
							'bitly_unique_url'          => '',
							'created_at'                => date("Y-m-d H:i:s"),
							'updated_at'                => date("Y-m-d H:i:s"),
							'google_id'                 => '',
							'facebook_id'               => '',
							'twitter_id'                => '',
							'umode'                     => '',
							'PhoneNumber'               => $this->input->post('alternate_phone_number',true),
							'Addressone'                => '',
							'Addresstwo'                => '',
							'StateProvince'             => '',
							'Zip'                       => '',
							'f_link'                    => '',
							't_link'                    => '',
							'l_link'                    => '',
							'product_commission'        => '0',
							'affiliate_commission'      => '0',
							'product_commission_paid'   => '0',
							'affiliate_commission_paid' => '0',
							'product_total_click'       => '0',
							'product_total_sale'        => '0',
							'affiliate_total_click'     => '0',
							'sale_commission'           => '0',
							'sale_commission_paid'      => '0',
							'status'                    => '1'
						));

						$this->db->insert("paypal_accounts", array(
                            'paypal_email' => $this->input->post('paypal_email',true),
                            'user_id' => $data,
                        ));
						$post['refid'] = !empty($refid) ? base64_decode($refid) : 0;
						if(!empty($data) && $user_type == 'user'){
							$notificationData = array(
								'notification_url'          => '/userslist/'.$data,
								'notification_type'         =>  'user',
								'notification_title'        =>  __('user.new_user_registration'),
								'notification_viewfor'      =>  'admin',
								'notification_actionID'     =>  $data,
								'notification_description'  =>  $this->input->post('firstname',true).' '.$this->input->post('lastname',true).' register as a '. $this->input->post("affliate_type",true) . ' on affiliate Program on '.date('Y-m-d H:i:s'),
								'notification_is_read'      =>  '0',
								'notification_created_date' =>  date('Y-m-d H:i:s'),
								'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
							);
							$this->insertnotification($notificationData);
							if ($post['affiliate_id'] > 0) {
								$notificationData = array(
									'notification_url'          => '/managereferenceusers',
									'notification_type'         =>  'user',
									'notification_title'        =>  __('user.new_user_registration_under_your'),
									'notification_viewfor'      =>  'user',
									'notification_view_user_id' =>  $post['affiliate_id'],
									'notification_actionID'     =>  $data,
									'notification_description'  =>  $this->input->post('firstname',true).' '.$this->input->post('lastname',true).' has been register under you on '.date('Y-m-d H:i:s'),
									'notification_is_read'      =>  '0',
									'notification_created_date' =>  date('Y-m-d H:i:s'),
									'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
								);
								$this->insertnotification($notificationData);
							}
							$json['success']  =  "You've Successfully registered";
		                    $user_details_array=$this->user->login($this->input->post('username',true));
		                    $this->load->model('Mail_model');
		                    
		                    $this->user->update_user_login($user_details_array['id']);
							$this->Mail_model->send_register_mail($post,__('user.welcome_to_new_user_registration'));
		                    if ($user_type == 'user') {
		                    	$this->session->set_userdata(array('user'=>$user_details_array));
		                    	$json['redirect'] = base_url('usercontrol/dashboard');
		                    } else {
		                    	$this->session->set_userdata(array('client'=>$user_details_array));
		                    	$json['redirect'] = base_url('clientcontrol/dashboard');
		                    }
						}
					}
				}
			}
		}
		else if ($action == 'forget') {
			$email = $this->input->post('email',true);
			$data = $this->db->query("SELECT * FROM users WHERE email like '{$email}' ")->row();
			if ($data) {
				$token = md5(uniqid(rand(), true));
				$resetlink = base_url('resetpassword/'. $token);
				
				$this->db->query("DELETE  FROM password_resets WHERE email like '{$email}' ");
				$this->db->query("INSERT INTO password_resets SET 
					email = '{$email}',
					token = '{$token}'
				");
				$this->load->model('Mail_model');
				$this->Mail_model->send_forget_mail($data, $resetlink);
				$json['success'] = __('user.password_reset_instructions_will_be_sent_to_your_registered_email_address');
			}
			else
			{
				$json['errors']['email'] = __('user.email_address_not_found');
			}
		}
		echo json_encode($json);
	}
	public function insertnotification($postData = null){
		if(!empty($postData)){
			$data['custom'] = $this->Product_model->create_data('notification', $postData);
		}
	}
	public function changePassword(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		$post = $this->input->post(null,true);

		if(isset($post) && !empty($post)){
			$this->form_validation->set_rules('old_pass', __('user.old_password'), 'required|trim', array('required' => '%s is required'));
			$this->form_validation->set_rules('password', __('user.new_password'), 'required|trim', array('required' => '%s is required'));
			$this->form_validation->set_rules('conf_password', 'Confirm Password', 'required|trim|matches[password]', array('required' => '%s is required'));
			if ($this->form_validation->run() == FALSE) {
				$data['validate_err'] = validation_errors();
			} else {
				$admin = $this->db->from('users')->where('id',$userdetails['id'])->get()->row_array();
				if($admin['password'] == sha1($post['old_pass'])){
					$res = array('password'=>sha1($post['password']));
					$this->db->where('id',$admin['id']);
					$this->db->update('users',$res);
					$this->session->set_flashdata(array('flash' => array('success' => __('user.user_profile_updated_successfully'))));
					redirect('usercontrol/changePassword', 'refresh');
				}else{
					$this->session->set_flashdata(array('flash' => array('error' => __('user.old_password_not_matched'))));
					redirect('usercontrol/changePassword');
				}
			}
		}
		
		$data['title'] = __('user.change_password');
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/dashboard/change-password', $data);
		$this->load->view('usercontrol/includes/footer', $data);
		
	}
	public function dashboardlist(){
		$data['title'] = __('user.user_dashboard');
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/dashboard/dashboardlist', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}
	public function dashboard(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		$post = $this->input->post(null,true);
		$get = $this->input->get(null,true);

		$this->load->model('Total_model');
		$this->load->model("Form_model");
		$this->load->model('IntegrationModel');

		if (isset($post['dvl'])) {
			$remain=20;
			$filter = [
				'user_id'          => $userdetails['id'],
				'restrict'         => $userdetails['id'],
				'redirectLocation' => 1,
				'status'           => 1,
				'limit'            => ceil($remain / 3),
				'start'            => 0,
			];

			if($post['dvl'] == 'false'){
				$filter['not_show_my'] = $userdetails['id'];
			}

	        $data['tools'] = $this->IntegrationModel->getProgramTools($filter);
			$remain -= count($data['tools']);

	        $data['form_default_commission'] = $this->Product_model->getSettings('formsetting');
	        $data['default_commition'] = $this->Product_model->getSettings('productsetting');

	       	$filter = array(
	       		'limit' => ceil($remain / 2),
				'start' => 0,
	       	);
			$forms = $this->Form_model->getForms($userdetails['id'], $filter);
			$remain -= count($forms);

			$filter = array(
				'product_status' => 1,
				'limit' => $remain,
				'start' => 0,
	       	);
	       	if($post['dvl'] == 'false'){
				$filter['not_show_my'] = $userdetails['id'];
			}
	       	$products = $this->Product_model->getAllProduct($userdetails['id'], $userdetails['type'], $filter);
	 		foreach ($products as $key => $value) {
	 			$products[$key]['is_product'] = 1;
	 		}

	 		foreach ($forms as $key => $value) {
				$forms[$key]['coupon_name']  = $this->Form_model->getFormCouponname(($value['coupon']) ? $value['coupon'] : 0);
				$forms[$key]['public_page']  = base_url('form/'.$value['seo'].'/'.base64_encode($this->userdetails()['id']));
				$forms[$key]['count_coupon'] = $this->Form_model->getFormCouponCount($value['form_id'],$this->userdetails()['id']);
	 			if($value['coupon']){
	 				$forms[$key]['coupon_code'] = $this->Form_model->getFormCouponCode($value['coupon']);
	 			}
	 			$forms[$key]['seo'] = str_replace('_', ' ', $value['seo']);
	 			$forms[$key]['is_form'] = 1;
	 			$forms[$key]['product_created_date'] = $value['created_at'];
	 		}

	 		$data_list = array_merge($products,$forms,$data['tools']);
	 		usort($data_list,function($a, $b){
	 			$ad = strtotime($a['product_created_date']);
			    $bd = strtotime($b['product_created_date']);
			    return ($ad-$bd);
	 		});
	 		$data['data_list'] = array_reverse($data_list);
	 		$data['pagination'] = 20;
			$remain = $data['pagination'];
			
	 		$json['html'] = $this->load->view("usercontrol/dashboard/market_tools", $data, true);
	 		echo json_encode($json);die;
		}

		if (isset($get['getChartData'])) {
			$data['chart'] = $this->Total_model->chartUser(
				(int)$userdetails['id'],
				[
					'year' => $post['year'],
					'group' => $post['group']
				]
			);
			echo json_encode($data);die;
		}

		
		
		$data['total'] = $this->Product_model->getallPercentageByallorders($userdetails['id']);
		//$data['totalsales'] = $this->Product_model->getallPercentageByallsales($userdetails['id']);
		$data['ordercount'] = $this->Product_model->getallorders($userdetails['id']);
		$data['salescount']    = $this->Order_model->getCount(array( 'affiliate_id' => $userdetails['id'] ));
		$data['product_count'] = $this->Product_model->getAllProductrecord();
		$data['user_count'] = $this->Product_model->getrefUsers($userdetails['id']);
		$data['formcount'] = $this->Form_model->formcount();
		$data['title'] = __('user.user_dashboard');
        $this->load->model('Wallet_model');
		$data['totals'] = $this->Wallet_model->getTotals(array('user_id' => $userdetails['id']), true,'usercontrol');

		$data['refer_total'] = $this->Product_model->getReferalTotals($userdetails['id']);
        $data['notifications'] = $this->user->getAllNotification($userdetails['id']);
		$data['populer_users'] = $this->Product_model->getPopulerUsers(array("limit" => 10));

		$data['totals']['full_hold_orders'] = $data['totals']['integration']['hold_orders'];

		

       
        

        $data['integration_orders'] = $this->IntegrationModel->getOrders(array(
        	"limit" => 5,
        	'user_id' => $userdetails['id'],
        ));
        
    	$data['buyproductlist'] = $this->Order_model->getOrders();
	    foreach ($data['buyproductlist'] as $key => $value) {
			$p = $this->Order_model->getProducts($value['id'],['refer_id' => $userdetails['id']]);
			$t = $this->Order_model->getTotals($p,array());
			$data['buyproductlist'][$key]['total'] = $t['total']['value'];
		}

        $referlevelSettings = $this->Product_model->getSettings('referlevel');
        $disabled_for = json_decode( (isset($referlevelSettings['disabled_for']) ? $referlevelSettings['disabled_for'] : '[]'),1);
        $refer_status = true;
        if((int)$referlevelSettings['status'] == 0){ $refer_status = false; }
        else if((int)$referlevelSettings['status'] == 2 && in_array($userdetails['id'], $disabled_for)){ $refer_status = false; }

        $data['refer_status'] = $refer_status;
        $data['store'] = $this->Product_model->getSettings('store');
         

        $data['integration_logs'] = $this->IntegrationModel->getLogs(array(
			'page'  => 1,
			'limit' => 5,
			'user_id' => $userdetails['id'],
		))['records'];
        
		
        $this->load->model('Report_model');
		$data['live_window'] = $this->Report_model->combine_window($data);


		$data['months'] = array('All','01','02','03','04','05','06','07','08','09','10','11','12');
		$data['years'] = array('All',date("Y",strtotime("-3 year")),date("Y",strtotime("-2 year")),date("Y",strtotime("-1 year")),date("Y",strtotime("0 year")));


		$data['totals']['full_digital_orders'] =$this->db->query('SELECT COUNT(op.id) as total FROM `order_products` op LEFT JOIN `order` as o ON o.id = op.order_id WHERE o.status > 0 AND op.refer_id ='. (int)$userdetails['id'])->row()->total;
		$data['totals']['external_inte_order'] =$this->db->query('SELECT COUNT(id) as total FROM `integration_orders` WHERE user_id='.  (int)$userdetails['id'])->row()->total;

		if(isset($_GET['a'])){
			$this->view($data,'dashboard/index', 'usercontrol');
			return false;
		}
			
		


		$data['user_totals'] = $this->Total_model->getUserTotals((int)$userdetails['id']);
		$data['user_totals_week'] = c_format($this->Total_model->getUserBalance((int)$userdetails['id'], ['week' => 1]));
		$data['user_totals_month'] = c_format($this->Total_model->getUserBalance((int)$userdetails['id'], ['month' => 1]));
		$data['user_totals_year'] = c_format($this->Total_model->getUserBalance((int)$userdetails['id'], ['year' => 1]));
		
		$this->view($data,'dashboard/dashboard', 'usercontrol');
	}
	public function get_integartion_data($return  = false){
		$post = $this->input->post();
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		$json = array();

		if($post['integration_data_year'] && $post['integration_data_month']){
			$integration_filters = array(
				'integration_data_year' => $post['integration_data_year'],
				'integration_data_month' => $post['integration_data_month'],
			);
		}else{
			$integration_filters = array();
		}

		$integration_filters['user_id'] = $userdetails['id'];

		$totals = $this->Wallet_model->getTotals($integration_filters, true);
		if($totals){
			$html = '';
			if ($totals['integration']['all'] ==null) {
			    $html .= '<div class="text-center">
			        <img class="img-responsive" src="'. base_url('assets/vertical/assets/images/no-data-2.png') .'" style="margin-top:100px;">
			        <h3 class="m-t-40 text-center text-muted">'. __('admin.no_integration_done_yet') .'</h3>
			    </div>';
			} else {
			    $html .= '<div role="tabpanel" class="tab-pane" id="site-all" style="display: block">
			        <ul class="list-group p-t-10" style="min-height:360px">
			            <li class="list-group-item">
			                '. __( 'admin.total_balance' ) .'
			                <span class="badge badge-light font-14 pull-right">
			                    '. c_format($totals['integration']['balance']) .'        
			                </span>
			            </li>
			            <li class="list-group-item">
			                '. __( 'admin.total_sales' ) .'
			                <span class="badge badge-light font-14 pull-right">
			                    '. c_format($totals['integration']['balance']) .' / '. c_format($totals['integration']['sale']) .'        
			                </span>
			            </li>
			            <li class="list-group-item">
			                '. __( 'admin.total_clicks' ) .'
			                <span class="badge badge-light font-14 pull-right">
			                    '. (int)$totals['integration']['click_count'] .' / '. c_format($totals['integration']['click_amount']) .'
			                </span>
			            </li>
			            <li class="list-group-item">
			                '. __('admin.total_actions') .'
			                <span class="badge badge-light font-14 pull-right">
			                    '. (int)$totals['integration']['action_count'] .' / '. c_format($totals['integration']['action_amount']) .'
			                </span>
			            </li>
			            <li class="list-group-item">
			                '. __( 'admin.total_commission' ) .'
			                <span class="badge badge-light font-14 pull-right">
			                    '. c_format($totals['integration']['total_commission']) .' 
			                </span>
			            </li>
			            <li class="list-group-item">
			                '. __( 'admin.total_orders' ) .'
			                <span class="badge badge-light font-14 pull-right">
			                    '. (int)$totals['integration']['total_orders'] .' 
			                </span>
			            </li>
			        </ul>
			    </div>';
			    $index = 0; 
			    foreach ($totals['integration']['all'] as $website => $value) {
			        $html .= '<div role="tabpanel" class="tab-pane" id="site-'. ++$index .'" style="height:360px;display: none;">
			            <ul class="list-group p-t-10" >
			                <li class="list-group-item">
			                    '. __( 'admin.total_balance' ) .'
			                    <span class="badge badge-light font-14 pull-right">
			                        '. c_format($value['balance']) .'
			                    </span>
			                </li>
			                <li class="list-group-item">
			                    '. __( 'admin.total_sales' ) .'
			                    <span class="badge badge-light font-14 pull-right">
			                        '. c_format($value['balance']) .' / '. c_format($value['sale']) .'        
			                    </span>
			                </li>
			                <li class="list-group-item">
			                    '. __( 'admin.total_clicks' ) .'
			                    <span class="badge badge-light font-14 pull-right">
			                        '. (int)$value['click_count'] .' / '. c_format($value['click_amount']) .'
			                    </span>
			                </li>
			                <li class="list-group-item">
			                    '. __('admin.total_actions') .'
			                    <span class="badge badge-light font-14 pull-right">
			                        '. (int)$value['action_count'] .' / '. c_format($value['action_amount']) .'
			                    </span>
			                </li>
			                <li class="list-group-item">
			                    '. __( 'admin.total_commission' ) .'
			                    <span class="badge badge-light font-14 pull-right">
			                        '. c_format($value['click_amount'] + $value['sale'] + $value['action_amount']) .' 
			                    </span>
			                </li>
			                <li class="list-group-item">
			                    '. __( 'admin.total_orders' ) .'
			                    <span class="badge badge-light font-14 pull-right">
			                        '. (int)$value['total_orders'] .' 
			                    </span>
			                </li>
			                <li class="list-group-item">
			                    <a class="btn btn-lg btn-default btn-success" href="http://'. $website .'" target="_blank">'. __( 'admin.priview_store' ) .'</a>
			                </li>
			            </ul>
			        </div>';
			    }
			}

			$integration_data_selected = 'all';
			if(isset($post['integration_data_selected']) && $post['integration_data_selected'] != '') $integration_data_selected = $post['integration_data_selected'];

			$newHTML = "<div class='p-3'>
                    <select name='integration-chart-type' id='integration-chart-type' class='form-control show-tabs select2-input'>
                        <option value='all' data-id='all' data-website='all'>All</option>";
                        $index = 0;
                        foreach ($totals['integration']['all'] as $website => $value) {
                        	$k = base64_encode($website); 
                            $newHTML .= "<option ". ( $integration_data_selected == $k ? 'selected' : '' ) ." value='". $k ."' data-id='". ++$index ."' data-website='". $website ."' >". $website ."</option>";
                       	}
                    $newHTML .= "</select>
                </div>
                <div class='tab-content'>
                    {$html}
                </div>";


            $json['html'] = $newHTML;


            $type = isset($post['integration_data_website_selected']) && $post['integration_data_website_selected'] != '' ?  $post['integration_data_website_selected'] : 'all';

			if($type == 'all'){
				$data = array(
					'balance'				=>	(float)$totals['integration']['balance'],
					'total_orders_amount'	=>	(float)$totals['integration']['total_orders_amount'],
					'sale'					=>	(float)$totals['integration']['sale'],
					'click_count'			=>	(float)$totals['integration']['click_count'],
					'click_amount'			=>	(float)$totals['integration']['click_amount'],
					'action_count'			=>	(float)$totals['integration']['action_count'],
					'action_amount'			=>	(float)$totals['integration']['action_amount'],
					'total_commission'		=>	(float)$totals['integration']['total_commission'],
					'total_orders'			=>	(float)$totals['integration']['total_orders'],
				);
			}else{
				$integration = $totals['integration']['all'];
				if(isset($integration[$type])){
					$data = array(
						'balance'				=>	isset($integration[$type]['balance']) ? (float)$integration[$type]['balance'] : 0,
						'total_orders_amount'	=>	isset($integration[$type]['total_orders_amount']) ? (float)$integration[$type]['total_orders_amount'] : 0,
						'sale'					=>	isset($integration[$type]['sale']) ? (float)$integration[$type]['sale'] : 0,
						'click_count'			=>	isset($integration[$type]['click_count']) ? (float)$integration[$type]['click_count'] : 0,
						'click_amount'			=>	isset($integration[$type]['click_amount']) ? (float)$integration[$type]['click_amount'] : 0,
						'action_count'			=>	isset($integration[$type]['action_count']) ? (float)$integration[$type]['action_count'] : 0,
						'action_amount'			=>	isset($integration[$type]['action_amount']) ? (float)$integration[$type]['action_amount'] : 0,
						'total_commission'		=>	isset($integration[$type]['total_commission']) ? (float)$integration[$type]['total_commission'] : 0,
						'total_orders'			=>	isset($integration[$type]['total_orders']) ? (float)$integration[$type]['total_orders'] : 0,
					);
				}
			}

			$json['chart'][$post['integration_data_year']] = $data;
		}else{
			$json['html'] = false;
		}



		if($return) return $json;
		echo json_encode($json);die;
	}
	public function logs(){
		$data = array();
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		$input = $this->input->post(null,true);
		
		$filter = array(
			'user_id' => $userdetails['id'],
		);

		$data['status'] = $this->Wallet_model->status;
		$data['status_icon'] = $this->Wallet_model->status_icon;
		if($input['type'] == 'sale'){
			$data['title'] = "Sales Logs";

			$filter['type_in'] = "'sale_commission','vendor_sale_commission'";
			$data['data'] = $this->Wallet_model->getTransaction($filter);
		}
		else if($input['type'] == 'hold_orders'){
			$data['title'] = "Hold Orders Logs";

			$filter['type'] = "sale_commission";
			$filter['status'] = 0;
			 
			$data['data'] = $this->Wallet_model->getTransaction($filter);
		}
		else if($input['type'] == 'click'){
			$data['title'] = "Clicks Logs";
			$filter['click_log'] = 1;
			$data['data'] = $this->Wallet_model->getTransaction($filter);

			$data['title2'] = "Clicks Logs";
			$record = array();

			$where = ' AND user_id = '. $userdetails['id'];

			$record[] = $this->db->query('SELECT country_code,created_at,ip  as user_ip,commission as pay_commition,"Integration Click" as type FROM integration_clicks_action WHERE is_action=0'.$where)->result_array();
			$record[] = $this->db->query('SELECT country_code,created_at,user_ip,pay_commition,"Product Click" as type  FROM product_action WHERE  1'.$where)->result_array();
			$record[] = $this->db->query('SELECT country_code,created_at,user_ip,pay_commition,"Form Click" as type  FROM form_action WHERE 1'.$where)->result_array();
			$record[] = $this->db->query('SELECT country_code,created_at,user_ip,commission as pay_commition,"Affiliate Click" as type FROM affiliate_action WHERE 1'.$where)->result_array();

			$record[] = $this->db->query('SELECT pa.country_code,pa.created_at,pa.user_ip,pa.pay_commition,"Store Product Click (Other Affiliate)" as type  FROM product_action pa LEFT JOIN product_affiliate paff ON (paff.product_id = pa.product_id)  WHERE paff.user_id=  '. (int)$userdetails['id'] .' ')->result_array();

            //$record[] = $this->db->query('SELECT pa.country_code,pa.created_at,pa.user_ip,pa.pay_commition,"Store Product Click (Admin)" as type  FROM product_action_admin pa LEFT JOIN product_affiliate paff ON (paff.product_id = pa.product_id)  WHERE paff.user_id=  '. (int)$userdetails['id'] .' ')->result_array();


			$_record = array();
			foreach ($record as $key => $re) {
				foreach ($re as $_key => $value) {
					$_record[] = array(
						'created_at' => $value['created_at'],
						'comment' => 'Click from ip_message ',
						'status' => $value['type'],
						'country_code' => $value['country_code'],
						'user_ip' => $value['user_ip'],
					);
				}
			}

			usort($_record, array('Admincontrol', 'date_compare') ); 
			$data['data2'] = $_record;
		}
		else if($input['type'] == 'orders'){
			$order_status = $this->Order_model->status;
			$data['title'] = "Digital Orders";
			$record = $this->db->query('SELECT o.* FROM `order_products` op LEFT JOIN `order` as o ON o.id = op.order_id WHERE o.status > 0 AND op.refer_id='. (int)$userdetails['id'])->result_array();

			$_record = array();
			foreach ($record as $_key => $value) {
				$_record[] = array(
					'created_at'   => $value['created_at'],
					'comment'      => 'Order from ip_message ',
					'status'       => $order_status[$value['status']],
					'country_code' => $value['country_code'],
					'user_ip'      => $value['ip'],
					'amount'       => $value['total'],
				);
			}

			$data['data'] = $_record;

		}
		else if($input['type'] == 'ex_orders'){
			$data['title'] = "External Orders";
			$record = $this->db->query('SELECT * FROM `integration_orders` WHERE user_id='. (int)$userdetails['id'])->result_array();
 
			$_record = array();
			foreach ($record as $_key => $value) {
				$_record[] = array(
					'created_at'   => $value['created_at'],
					'comment'      => 'Order from ip_message ',
					'status'       => 'Complete',
					'country_code' => $value['country_code'],
					'user_ip'      => $value['ip'],
					'amount'       => $value['total'],
				);
			}

			$data['data'] = $_record;

		}
		else if($input['type'] == 'action'){
			$data['title'] = "Actions Logs";
			
			$filter['type'] = "external_click_commission";
			$filter['is_action'] = 1;
			$data['data'] = $this->Wallet_model->getTransaction($filter);
		}
		else if($input['type'] == 'hold_actions'){
			$data['title'] = "Hold Action Logs";
			
			$filter['type'] = "external_click_commission";
			$filter['is_action'] = 1;
			$filter['status'] = 0;
			$data['data'] = $this->Wallet_model->getTransaction($filter);
		}
		
		$data['html'] = $this->load->view("common/log_model",$data,true);

		echo json_encode($data);die;
	}
	public function logout(){
		$this->session->unset_userdata('user');
		redirect('/login');
	}
	public function deleteUser($id){
		$data['users'] = $this->admin_model->deleteUser($id);
		$this->session->set_flashdata('success', __('user.user_deleted_successfullly'));
		redirect('usercontrol/manageUsers');
	}
	public function addComission(){
		$post = $this->input->post(null,true);
		if(isset($post) && !empty($post)){
			$this->form_validation->set_rules('buyid', 'BuyId', 'required|trim', array('required' => '%s is required'));
			$this->form_validation->set_rules('amount', 'Amount', 'required|trim', array('required' => '%s is required.')
			);
			$this->form_validation->set_rules('qty', 'Qty', 'required|trim', array('required' => '%s is required.')
			);
			
			if ($this->form_validation->run() == FALSE) {
				$data['validate_err'] = validation_errors();
				} else {
				$db = new MY_Controller();
				$userdetails=$db->userdetails();
				$kirim = array('RefiD'=>$userdetails['refid'],'buyiD'=>$post['buyid'],'userID'=>$userdetails['id'],'worlbit_qty'=>$post['qty'],'Amount'=>$post['amount']);
				
				$res = $this->commisioninfo->set_commission($kirim);
				$this->session->set_flashdata(array('flash' => array('success' => __('user.comission_added_successfully!'))));
				redirect('usercontrol/addComission', 'refresh');
			}
		}
		$data['title'] = 'Add Comission';
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/dashboard/addComission', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}
	public function addUser(){
		$post = $this->input->post(null,true);
		if(isset($post) && !empty($post)){
			$this->form_validation->set_rules('firstname', __('user.first_name'), 'required|trim', array('required' => '%s is required'));
			$this->form_validation->set_rules('lastname', __('user.last_name'), 'required|trim', array('required' => '%s is required.'));
			$this->form_validation->set_rules('username', __('user.username'), 'required|trim|is_unique[users.username]', array('required' => '%s is required'));
			$this->form_validation->set_rules('email', __('user.email'), 'required|trim', array('required' => '%s is required'));
			$this->form_validation->set_rules('password', __('user.password'), 'required|trim', array('required' => '%s is required'));
			$this->form_validation->set_rules('conf_password', __('user.confirm_password'), 'required|trim|matches[password]', array('required' => '%s is required'));
			
			if ($this->form_validation->run() == FALSE) {
				$data['validate_err'] = validation_errors();
				} else {
				
				$res = array('firstname'=>$post['firstname'],'lastname'=>$post['lastname'],'email'=>$post['email'],'username'=>$post['username'],'password'=>sha1($post['password']),'updated_at'=>date('Y-m-d H:i:s'));
				
				$this->db->insert('users',$res);
				$this->session->set_flashdata(array('flash' => array('success' => __('user.user_added_successfully'))));
				redirect('usercontrol/manageUsers', 'refresh');
			}
		}
		$data['title'] = 'Add User';
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/dashboard/addUser', $data);
		$this->load->view('usercontrol/includes/footer', $data);	
	}
	public function editUser($id){
		$post = $this->input->post(null,true);
		if(isset($post['id']) && !empty($post['id'])){
			$res = array('firstname'=>$post['firstname'],'lastname'=>$post['lastname'],'updated_at'=>date('Y-m-d H:i:s'));
			$this->db->where('id',$post['id']);
			$this->db->update('users',$res);
			$this->session->set_flashdata(array('flash' => array('success' => __('user.user_profile_updated_successfully'))));
			redirect('usercontrol/manageUsers', 'refresh');
		}
		
		
		$data['users'] = $this->admin_model->getUsers($id);
		$data['title'] = 'Edit User';
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/dashboard/edit-user', $data);
		$this->load->view('usercontrol/includes/footer', $data);	
	}
	public function messages(){
		$data['title'] = 'Message';
		$data['users'] = $this->admin_model->getUsers($id=null);
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/dashboard/message', $data);
		$this->load->view('usercontrol/includes/footer', $data);	
	}
	public function chatmessage(){
		$this->load->helper('smiley');
		$data['title'] = 'Message';
		$data['users'] = $this->admin_model->getUsers($id=null);
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('chat', $data);
		$this->load->view('usercontrol/includes/footer', $data); 		
	}
	public function google_login(){
		$get = $this->input->get(null,true);
		$clientId = '163214076002-9o582d2urnpc10nebsd032sgadhcgvmf.apps.googleusercontent.com'; //Google client ID
		$clientSecret = 'Ent8s37alsTYf6Ai8Z7y0Z6l'; //Google client secret
		$redirectURL = base_url() . 'admin/google_login/';
		
		//Call Google API
		$gClient = new Google_Client();
		$gClient->setApplicationName('Login');
		$gClient->setClientId($clientId);
		$gClient->setClientSecret($clientSecret);
		$gClient->setRedirectUri($redirectURL);
		$google_oauthV2 = new Google_Oauth2Service($gClient);
		
		if(isset($get['code']))
		{
			$gClient->authenticate($get['code']);
			$_SESSION['token'] = $gClient->getAccessToken();
			header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
		}
		
		if (isset($_SESSION['token']))
		{
			$gClient->setAccessToken($_SESSION['token']);
		}
		
		if ($gClient->getAccessToken()) {
			$userProfile = $google_oauthV2->userinfo->get();
			echo "<pre>";
			print_r($userProfile);
			die;
		}
		else
		{
			$url = $gClient->createAuthUrl();
			header("Location: $url");
			exit;
		}
	}
	public function store_orders($page = 1){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		$data['status'] = $this->Order_model->status;

		unset($data['status']['0']);

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$page = max((int)$page,1);
			$post = $this->input->post(null,true);
			$filter = array(
				'limit' => 100,
				'page' => $page,
				'user_id' => $userdetails['id'],
				'o_status_gt' => 1,
			);

			if(isset($post['filter_status']) && $post['filter_status'] != ''){
				$filter['o_status'] = $this->input->post('filter_status',true);
			}

			list($data['orders'],$total) = $this->Order_model->getAllOrders($filter);

			$data['start_from'] = (($page-1) * $filter['limit'])+1;
			$data['wallet_status'] = $this->Wallet_model->request_status;
			$json['html'] = $this->load->view("usercontrol/store/order_list.php",$data,true);

			$this->load->library('pagination');
			$config['base_url'] = base_url('usercontrol/store_orders/');
			$config['per_page'] = $filter['limit'];
			$config['total_rows'] = $total;
			$config['use_page_numbers'] = TRUE;
			$config['enable_query_strings'] = TRUE;
			$this->pagination->initialize($config);

			$json['pagination'] = $this->pagination->create_links();

			echo json_encode($json);die;
		}
		
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);
		$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/store/orders', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}
    public function store_logs($page = 0){

    	$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$page = max((int)$page,1);
			
			$filter = array(
				'limit'   => 100,
				'page'    => $page,
				'user_id' => $userdetails['id'],
			);
			$data['userdetails'] = $userdetails;

			list($data['clicks'],$total) = $this->Order_model->getAllClickLogs($filter);
			$data['start_from'] = (($page-1) * $filter['limit'])+1;

			$json['html'] = $this->load->view("usercontrol/store/log_list.php",$data,true);

			$this->load->library('pagination');
			$config['base_url'] = base_url('usercontrol/store_logs/');
			$config['per_page'] = $filter['limit'];
			$config['total_rows'] = $total;
			$config['use_page_numbers'] = TRUE;
			$config['enable_query_strings'] = TRUE;
			$this->pagination->initialize($config);


			$json['pagination'] = $this->pagination->create_links();
			echo json_encode($json);die;
		}
    
        $this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);
		$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/store/logs', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}

    public function store_markettools(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)) redirect('login');

		$this->load->model('Form_model');
        $this->load->model('Report_model');
        $this->load->model('Wallet_model');
        $this->load->model('IntegrationModel');

        if ($this->input->server('REQUEST_METHOD') == 'POST'){
        	$post = $this->input->post(null,true);
			$get = $this->input->get(null,true);
			$json['form_default_commission'] = $this->Product_model->getSettings('formsetting');
			$json['default_commition']       = $this->Product_model->getSettings('productsetting');

			$filter = [
				'user_id'          => $userdetails['id'],
				'status'           => 1,
				'redirectLocation' => 1,
				'restrict'         => $userdetails['id'],
			];

			if($post['dvl'] == 'false'){
				$filter['not_show_my'] = $userdetails['id'];
			}

			if (isset($post['category_id'])) {
				$filter['category_id'] = $post['category_id'];
			}
			if (isset($post['ads_name'])) {
				$filter['ads_name'] = $post['ads_name'];
			}


	        $json['tools'] = $this->IntegrationModel->getProgramTools($filter);

	        $filter = ['product_status' => 1,'on_store'=>1];
	        if (isset($post['market_category_id'])) {
				$filter['category_id'] = $post['market_category_id'];
			}
			if($post['dvl'] == 'false'){
				$filter['not_show_my'] = $userdetails['id'];
			}

			$products = $this->Product_model->getAllProduct($userdetails['id'], $userdetails['type'], $filter);
			$forms    = $this->Form_model->getForms($userdetails['id']);
	 		foreach ($products as $key => $value) { $products[$key]['is_product'] = 1; }
	 		foreach ($forms as $key => $value) {
				$forms[$key]['coupon_name']          = $this->Form_model->getFormCouponname(($value['coupon']) ? $value['coupon'] : 0);
				$forms[$key]['public_page']          = base_url('form/'.$value['seo'].'/'.base64_encode($this->userdetails()['id']));
				$forms[$key]['count_coupon']         = $this->Form_model->getFormCouponCount($value['form_id'],$this->userdetails()['id']);
				$forms[$key]['seo']                  = str_replace('_', ' ', $value['seo']);
				$forms[$key]['is_form']              = 1;
				$forms[$key]['product_created_date'] = $value['created_at'];
				$forms[$key]['fevi_icon'] = $value['fevi_icon'] ? 'assets/images/form/favi/'.$value['fevi_icon'] : 'assets/images/no_image_available.png';

				if($value['coupon']){
	 				$forms[$key]['coupon_code'] = $this->Form_model->getFormCouponCode($value['coupon']);
	 			}
	 		}

	 		$data_list = array_merge($products,$forms,$json['tools']);
	 		usort($data_list,function($a,$b){
	 			$ad = strtotime($a['product_created_date']);
			    $bd = strtotime($b['product_created_date']);
			    return ($ad-$bd);
	 		});
	 		$json['data_list'] = array_reverse($data_list);
	 		$json['view'] = $this->load->view("usercontrol/store/markettools_list",$json,true);

	 		echo json_encode($json);die;
	 	}

		$data['categories'] = $this->db->query("SELECT id as value,name as label FROM integration_category ")->result_array();
		$data['store_categories'] = $this->db->query("SELECT id as value,name as label FROM categories WHERE 1")->result_array();

		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);
		$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/store/markettools', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}
	public function listproduct_ajax($page = 1){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/'); }
		$store_setting =$this->Product_model->getSettings('store');
		if(!$store_setting['status']){redirect('/usercontrol/dashboard');}

		$get = $this->input->get(null,true);
		$post = $this->input->post(null,true);
		$filter = array(
			'page' => isset($get['page']) ? $get['page'] : $page,
			'limit' => 20,
			'only_admin_product' => 1,
		);


	
		$record = $this->Product_model->getAllProduct($userdetails['id'], $userdetails['type'], $filter);
		$data['productlist'] = $record['data'];
		$data['store_setting'] = $store_setting;
		$data['default_commition'] =$this->Product_model->getSettings('productsetting');
		
		$json['view'] = $this->load->view("usercontrol/product/product_list", $data, true);

		$this->load->library('pagination');
		$this->pagination->cur_page = $filter['page'];

		$config['base_url'] = base_url('usercontrol/listproduct_ajax');
		$config['per_page'] = $filter['limit'];
		$config['total_rows'] = $record['total'];
		$config['use_page_numbers'] = TRUE;
		$config['page_query_string'] = TRUE;
		$config['enable_query_strings'] = TRUE;
		$_GET['page'] = $filter['page'];
		$config['query_string_segment'] = 'page';
		$this->pagination->initialize($config);
		$json['pagination'] = $this->pagination->create_links();

		echo json_encode($json);

	}
	public function listproduct(){
		$userdetails = $this->userdetails();
		
		if(empty($userdetails)){ redirect('/login'); }

		$store_setting =$this->Product_model->getSettings('store');
		if(!$store_setting['status']){
			redirect('/usercontrol/dashboard');			
		}
		$this->load->model('Form_model');

		$data['totals'] = $this->Wallet_model->getTotals(array('user_id' => $userdetails['id']), true);
		//$data['productlist'] = $this->Product_model->getAllProduct($userdetails['id'], $userdetails['type'],['only_admin_product' => 1]);
       	
       	//$data['client_count'] =$this->db->query('SELECT count(*) as total FROM users WHERE  type="client"')->row()->total;
       	$data['ordercount'] =$this->db->query('SELECT COUNT(op.id) as total FROM `order_products` op LEFT JOIN `order` as o ON o.id = op.order_id WHERE o.status > 0 AND op.`refer_id` = '. (int)$userdetails['id'] )->row()->total;


       		
		$data['user'] = $userdetails;
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);
        $this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/product/index', $data);	
		$this->load->view('usercontrol/includes/footer', $data);
	}
	public function managereferenceusers(){redirect('usercontrol/my_network');}
	public function my_network(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		$referlevelSettings = $this->Product_model->getSettings('referlevel');
        $disabled_for = json_decode( (isset($referlevelSettings['disabled_for']) ? $referlevelSettings['disabled_for'] : '[]'),1);
        $refer_status = true;
        if((int)$referlevelSettings['status'] == 0){ $refer_status = false; }
        else if((int)$referlevelSettings['status'] == 2 && in_array($userdetails['id'], $disabled_for)){ $refer_status = false; }
        //if(!$refer_status){ show_404(); }

		//$data['refUsers'] = $this->Product_model->getrefUsers($userdetails['id']);
		$userlist = $this->Product_model->getAllUsersTreeV3(array(),$userdetails['id']);
		$site_setting = $this->Product_model->getSettings('site');
		 
		if($site_setting['show_sponser'] == 'none'){
			$data['userslist'] = $userlist;
		}
		else if($site_setting['show_sponser'] == 'real_sponser'){
			if($userdetails['refid'] > 0){
				$admin_result= $this->db->query("SELECT id,username as name,avatar,refid FROM users WHERE type='user' AND id= " . (int)$userdetails['refid'])->row_array();
				if($admin_result){
					$_children = [];
					$_children[] = array(
		                'name'  => $admin_result['name'] ."<img class='user-avtar-tree' src='". $this->Product_model->getAvatar($admin_result['avatar']) ."'>",
		                'children' => $userlist,
		            );

	            	$data['userslist'] = $_children;
				}
			}

			if(!isset($data['userslist'])){
            	$data['userslist'] = $userlist;
			}
            
		} else{
			$admin_result= $this->db->query("SELECT id,username as name,avatar,refid FROM users WHERE type='admin'")->row_array();
			$_children = [];
			$_children[] = array(
                'name'  => ($site_setting['sponser_name'] ? $site_setting['sponser_name'] : $admin_result['name']) ."<img class='user-avtar-tree' src='". $this->Product_model->getAvatar($admin_result['avatar']) ."'>",
                'children' => $userlist,
            );
            
            $data['userslist'] = $_children;
		}
		$data['refer_total'] = $this->Product_model->getReferalTotals($userdetails['id']);
		
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/users/my_network', $data);	
		$this->load->view('usercontrol/includes/footer', $data);
	}
	public function addpayment($id = null){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		$post = $this->input->post(null,true);

		if (isset($post['add_paypal'])) {
			$email = $this->input->post('paypal_email',true);
			if ((int)$post['id'] > 0) {
				$this->db->update("paypal_accounts", array(
					'paypal_email' => $email,
					'user_id' => $userdetails['id'],
				),
				array(
					'id' => $post['id']
				));
			}
			else
			{
				$this->db->insert("paypal_accounts", array(
					'paypal_email' => $email,
					'user_id' => $userdetails['id'],
				));
			}
			$this->session->set_flashdata('success', __('user.paypal_account_saved_successfully'));
			redirect('usercontrol/mywallet/#tab-paymentdetails');
		} else if(!empty($post)){
			$this->load->helper(array('form', 'url'));
			
            $this->load->library('form_validation');
			
            $this->form_validation->set_rules('payment_account_number', __('user.account_number'), 'required');
            $this->form_validation->set_rules('payment_account_name', __('user.account_name'), 'required' );
            $this->form_validation->set_rules('payment_ifsc_code', __('user.ifsc_code'), 'required');
			if($this->form_validation->run())
			{
				$errors= array();
				
				$details = array(
					'payment_bank_name'      =>  $this->input->post('payment_bank_name',true),
					'payment_account_number' =>  $this->input->post('payment_account_number',true),
					'payment_account_name'   =>  $this->input->post('payment_account_name',true),
					'payment_ifsc_code'      =>  $this->input->post('payment_ifsc_code',true),
					'payment_status'         =>  1,
					'payment_ipaddress'      =>  $_SERVER['REMOTE_ADDR'],
				);
				if(empty($errors)){
					 
					if( (int)$post['payment_id'] > 0 ){
						$this->session->set_flashdata('success', __('user.payment_updated_successfully'));
						$details['payment_updated_by'] = $userdetails['id'];
						$details['payment_updated_date'] = date('Y-m-d H:i:s');
						$this->Product_model->update_data('payment_detail', $details,array('payment_id' => (int)$post['payment_id']));	
						redirect('usercontrol/mywallet/#tab-paymentdetails');
					}
					else {
						$this->session->set_flashdata('success', __('user.payment_added_successfully'));
						$details['payment_created_by'] = $userdetails['id'];
						$details['payment_created_date'] = date('Y-m-d H:i:s');
						$this->Product_model->create_data('payment_detail', $details);	
						redirect('usercontrol/mywallet/#tab-paymentdetails');
					}
					
				} else {
					if(!empty($id)){
						$this->session->set_flashdata('error', $errors['avatar_error'] );
						redirect('usercontrol/mywallet/#tab-paymentdetails');
					} else {
						$this->session->set_flashdata('error', $errors['avatar_error'] );
						redirect('usercontrol/mywallet/#tab-paymentdetails');
					}
				}
			} else {
				$this->session->set_flashdata('error', __('user.form_validation_error'));
				redirect('usercontrol/addpayment');
			}
		
		} else {
			redirect('usercontrol/mywallet/#tab-paymentdetails');
		}				
	}
	public function generateproductcode($affiliateads_id = null){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		else {
			if($affiliateads_id){
				$data['product_id'] = $affiliateads_id;		
				$data['user_id'] = $userdetails['id'];		
				$data['getProduct'] 	= $this->Product_model->getProductByIdArray($affiliateads_id);
				$this->load->view('usercontrol/product/generatecode', $data);	
			}
		}	
	}
	public function listbuyproduct(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		$data['buyproductlist'] = $this->Product_model->getAllBuyProduct($userdetails['id']);
		
		$data['user'] = $userdetails;
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/product/listofallbuyproduct', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}


	public function listbuyaffiproduct(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		$store_setting = $this->Product_model->getSettings('store');
		if(!$store_setting['status']){ show_404(); }

		$filter = array( 'affiliate_id' => $userdetails['id'] );

		$data['buyproductlist'] = $this->Order_model->getOrders($filter);
		foreach ($data['buyproductlist'] as $key => $value) {
			$p = $this->Order_model->getProducts($value['id'],['refer_id' => $userdetails['id']]);
			$t = $this->Order_model->getTotals($p,array());
			$data['buyproductlist'][$key]['total'] = $t['total']['value'];
		}

		$data['status'] = $this->Order_model->status;
		$data['user'] = $userdetails;

		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/product/listbuyaffiproduct', $data);	
		$this->load->view('usercontrol/includes/footer', $data);	
	}
	public function editProfile(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		else { $id =  $userdetails['id']; }

		$this->load->model('PagebuilderModel');
		$this->load->model('User_model');
		if ($this->input->post()) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('firstname', 'First Name', 'required|trim');
			$this->form_validation->set_rules('lastname', 'Last Name', 'required|trim');
			$this->form_validation->set_rules('username', 'Username', 'required|trim');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|xss_clean');
			$this->form_validation->set_rules('country_id', 'Country', 'required');
			$post = $this->input->post(null,true);

			if($post['password'] != ''){
				$this->form_validation->set_rules('password', 'Password', 'required|trim', array('required' => '%s is required'));
				$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim', array('required' => '%s is required'));
	            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim|matches[password]', array('required' => '%s is required'));
			}
			
			$json['errors'] = array();

			$register_form = $this->PagebuilderModel->getSettings('registration_builder');
			if($register_form){
				$customField = json_decode($register_form['registration_builder'],1);
				
				foreach ($customField as $_key => $_value) {
					$field_name = 'custom_'. $_value['name'];

					if($_value['required'] == 'true'){
						if(!isset($post[$field_name]) || $post[$field_name] == ''){
							$json['errors'][$field_name] = $_value['label'] ." is required.!";
						}
					}

					if(!isset($json['errors'][$field_name]) && (int)$_value['maxlength'] > 0){
						if(strlen( $post[$field_name] ) > (int)$_value['maxlength']){
							$json['errors'][$field_name] = $_value['label'] ." Maximum length is ". (int)$_value['maxlength'];
						}
					}

					if(!isset($json['errors'][$field_name]) && (int)$_value['minlength'] > 0){
						if(strlen( $post[$field_name] ) > (int)$_value['minlength']){
							$json['errors'][$field_name] = $_value['label'] ." Minimum length is ". (int)$_value['minlength'];
						}
					}

					if(!isset($json['errors'][$field_name]) && $_value['mobile_validation']  == 'true'){
						
						/*if(!preg_match('/^[0-9]{10}+$/', $post[$field_name])){
							$json['errors'][$field_name] = $_value['label'] ." Invalid mobile number ";
						}*/
					}
				}
			}

			if ($this->form_validation->run() == FALSE) {
				$json['errors'] = array_merge($this->form_validation->error_array(), $json['errors']);
			}
			if( count($json['errors']) == 0){
				$checkmail = $this->Product_model->checkmail($this->input->post('email',true),$id);
				$checkuser = $this->Product_model->checkuser($this->input->post('username',true),$id);
				
				if(!empty($checkmail)){ $json['errors']['email'] = "Email Already Exist"; }
				if(!empty($checkuser)){ $json['errors']['username'] = "Username Already Exist"; }


				if(count($json['errors']) == 0){

					$custom_fields = array();
                    foreach ($this->input->post() as $key => $value) {
                    	if(!in_array($key, array('affiliate_id','terms','cpassword','firstname','lastname','email','username','password'))){
                    		$custom_fields[$key] = $value;
                    	}
                    }

                    $userArray = array(
						'firstname'                 => $this->input->post('firstname',true),
						'lastname'                  => $this->input->post('lastname',true),
						'email'                     => $this->input->post('email',true),
						'username'                  => $this->input->post('username',true),
						//'password'                  => sha1($this->input->post('password',true)),
						'twaddress'                 => '',
						'address1'                  => '',
						'address2'                  => '',
						'uzip'                      => '',
						'avatar'                    => '',
						'online'                    => '0',
						'unique_url'                => '',
						'bitly_unique_url'          => '',
						'google_id'                 => '',
						'facebook_id'               => '',
						'twitter_id'                => '',
						'umode'                     => '',
						'PhoneNumber'               => '',
						'Addressone'                => '',
						'Addresstwo'                => '',
						'StateProvince'             => '',
						'Zip'                       => '',
						'f_link'                    => '',
						't_link'                    => '',
						'l_link'                    => '',
						'product_commission'        => '0',
						'affiliate_commission'      => '0',
						'product_commission_paid'   => '0',
						'affiliate_commission_paid' => '0',
						'product_total_click'       => '0',
						'product_total_sale'        => '0',
						'affiliate_total_click'     => '0',
						'sale_commission'           => '0',
						'sale_commission_paid'      => '0',
						'status'                    => '1',
						'ucountry'                    => $this->input->post('country_id',true),
						'Country'                    => $this->input->post('country_id',true),
						'value'                    => json_encode($custom_fields),
					);

					if($post['password'] != ''){
                    	$userArray['password'] = sha1( $post['password'] );
					}

					if(!empty($_FILES['avatar']['name'])){
						$upload_response = $this->upload_photo('avatar','assets/images/users');

						if($upload_response['success']){
							$userArray['avatar'] = $upload_response['upload_data']['file_name'];
						}
					}

					$this->user->update_user($id, $userArray);
					$userArray = $this->db->query("SELECT * FROM users WHERE id = ". (int)$id)->row_array();
					$this->session->set_userdata(array('user'=>$userArray));


					$this->session->set_flashdata('success', 'Profile Updated Successfully');
					$json['location'] = base_url('usercontrol/editProfile/');
				}
			}


			echo json_encode($json);die;
		} else {
			$data['user']  = (array)$this->user->get($id);
			//$data['country'] = $this->Product_model->getcountry();
			$data['countries'] = $this->User_model->getCountries();
			
			$register_form = $this->PagebuilderModel->getSettings('registration_builder');	
			$data['data'] = json_decode($register_form['registration_builder'],1);
			$data['edit_view'] = true;

			
			$data['html_form'] = $this->load->view('auth/user/templates/register_form',$data, true);

			$this->load->view('usercontrol/includes/header', $data);
			$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
			$this->load->view('usercontrol/users/edit_profile', $data);	
			$this->load->view('usercontrol/includes/footer', $data);
		}
		
		function getstate($country_id = null) {
			$userdetails = $this->userdetails();
			if(empty($userdetails)){
				redirect('usercontrol');
			}
			else {
				$states = $this->Product_model->getAllstate($country_id);
				echo '<option selected="selected">Select State</option>';
				if(!empty($states)){
					foreach($states as $state){
						echo '<option value="'.$state['name'].'">'.$state['name'].'</option>';
					}
				}
				die;
				
			}
		}
	}
	public function friendly_seo_string($vp_string){
		$vp_string = trim($vp_string);
		$vp_string = html_entity_decode($vp_string);		
		$vp_string = strip_tags($vp_string);
		$vp_string = strtolower($vp_string);		
		$vp_string = preg_replace('~[^ a-z0-9_.]~', ' ', $vp_string);
		$vp_string = preg_replace('~ ~', '-', $vp_string);	
		$vp_string = preg_replace('~-+~', '-', $vp_string);
		return $vp_string;
	}
	public function upload_photo($fieldname,$path) {
		
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'png|gif|jpeg|jpg';
		
		$this->load->helper('string');
		$config['file_name']  = random_string('alnum', 32);
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		
		if (!$this->upload->do_upload($fieldname)) {
			echo $this->upload->display_errors();
			die;
			$data = array('success' => false, 'msg' => $this->upload->display_errors());
		} else {
			$upload_details = $this->upload->data();
			
			$config1 = array(
				'source_image' => $upload_details['full_path'],
				'new_image' => $path.'/thumb',
				'maintain_ratio' => true,
				'width' => 300,
				'height' => 300
			);
			$this->load->library('image_lib', $config1);
			$this->image_lib->resize();
			$data = array('success' => true, 'upload_data' => $upload_details, 'msg' => "Upload success!");
		}
		return $data;
	}
	public function updatenotify($country_id = null) {
		$userdetails = $this->userdetails();
		$post = $this->input->post(null,true);

		if(empty($userdetails)){ redirect('/login'); }
		else {
			if(!empty($post['id'])){
				$noti = $this->db->query("SELECT * FROM notification WHERE notification_id= ". $post['id'])->row();
				
				if($noti->notification_type == 'integration_click'){
					$json['location'] = base_url('integration/click_logs');
				}
				else if($noti->notification_type == 'integration_orders'){
					$json['location'] = base_url('integration/user_orders');
				} else{
					$json['location'] = base_url('usercontrol/'.$noti->notification_url);
				}
				
				$this->Product_model->update_data('notification', array('notification_is_read' => 1),array('notification_id' => $post['id']));
			}
		}

		echo json_encode($json);
	}
	public function getnotificationnew() {
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		else {
			$notifications = $this->Product_model->getnotificationnew('user', $userdetails['id']);
			echo trim(count($notifications));
		}
	}
	public function getnotificationall() {
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		else {
			$notifications = $this->Product_model->getnotificationall('user', $userdetails['id']);
			echo trim(count($notifications));
		}
	}
    public function delete_image($image_id = null){
        $userdetails = $this->userdetails();
        $post = $this->input->post(null,true);

        if(empty($userdetails)){ redirect('/login'); }
        else {
            if(!empty($post['image_id'])){
                $this->Product_model->deleteImage($post['image_id']);
            }
        }
    }
	public function getnotification() {
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		else {
			$notifications = $this->Product_model->getnotification('user', $userdetails['id']);
			if(!empty($notifications)){
				foreach($notifications as $notification){
					if($notification['notification_type'] == 'order'){
						if($notification['notification_view_user_id'] == $userdetails['id']){
							echo '<a href="javascript:void(0)" onclick=shownofication('.$notification['notification_id'].',"'.base_url().'usercontrol'.$notification['notification_url'].'") class="dropdown-item notify-item">
							<div class="notify-icon bg-primary"><i class="mdi mdi-cart-outline"></i></div>
							<p class="notify-details"><b>'.$notification['notification_title'].'</b><small class="text-muted">'.$notification['notification_description'].'</small></p>
							</a>';
						}
					}
					
					if($notification['notification_type'] == 'client'){
						echo '<a href="javascript:void(0)" onclick=shownofication('.$notification['notification_id'].',"'.base_url().'usercontrol'.$notification['notification_url'].'") class="dropdown-item notify-item">
						<div class="notify-icon bg-primary"><i class="mdi mdi-account-circle"></i></div>
						<p class="notify-details"><b>'.$notification['notification_title'].'</b><small class="text-muted">'.$notification['notification_description'].'</small></p>
						</a>';
					}
					
					if($notification['notification_type'] == 'paymentrequest'){
						echo '<a href="javascript:void(0)" onclick=shownofication('.$notification['notification_id'].',"'.base_url().'usercontrol'.$notification['notification_url'].'") class="dropdown-item notify-item">
						<div class="notify-icon bg-primary"><i class="mdi mdi-account-circle"></i></div>
						<p class="notify-details"><b>'.$notification['notification_title'].'</b><small class="text-muted">'.$notification['notification_description'].'</small></p>
						</a>';
					}
					
					if($notification['notification_type'] == 'user'){
						echo '<a href="javascript:void(0)" onclick=shownofication('.$notification['notification_id'].',"'.base_url().'usercontrol'.$notification['notification_url'].'") class="dropdown-item notify-item">
						<div class="notify-icon bg-primary"><i class="mdi mdi-account"></i></div>
						<p class="notify-details"><b>'.$notification['notification_title'].'</b><small class="text-muted">'.$notification['notification_description'].'</small></p>
						</a>';
					}
					
					if($notification['notification_type'] == 'product'){
						echo '<a href="javascript:void(0)" onclick=shownofication('.$notification['notification_id'].',"'.base_url().'usercontrol'.$notification['notification_url'].'") class="dropdown-item notify-item">
						<div class="notify-icon bg-primary"><i class="mdi mdi-basket"></i></div>
						<p class="notify-details"><b>'.$notification['notification_title'].'</b><small class="text-muted">'.$notification['notification_description'].'</small></p>
						</a>';
					}
					
					if($notification['notification_type'] == 'commission'){
						echo '<a href="javascript:void(0)" onclick=shownofication('.$notification['notification_id'].',"'.base_url().'usercontrol'.$notification['notification_url'].'") class="dropdown-item notify-item">
						<div class="notify-icon bg-primary"><i class="mdi mdi-basket"></i></div>
						<p class="notify-details"><b>'.$notification['notification_title'].'</b><small class="text-muted">'.$notification['notification_description'].'</small></p>
						</a>';
					}
					
					if($notification['notification_type'] == 'commissionrequest'){
						echo '<a href="javascript:void(0)" onclick=shownofication('.$notification['notification_id'].',"'.base_url().'usercontrol'.$notification['notification_url'].'") class="dropdown-item notify-item">
						<div class="notify-icon bg-primary"><i class="mdi mdi-cash-usd"></i></div>
						<p class="notify-details"><b>'.$notification['notification_title'].'</b><small class="text-muted">'.$notification['notification_description'].'</small></p>
						</a>';
					}
					
				}
			}
			die;
			
		}
	}
	public function vieworder($order_id){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		$this->load->model('Form_model');
		$data['order'] = $this->Order_model->getOrder($order_id);
		$data['products'] = $this->Order_model->getProducts($order_id,['vendor_or_refer_id' => $userdetails['id']]);
		if($data['products']){
			
			$data['affiliateuser'] = $this->Order_model->getAffiliateUser($order_id);
			$data['payment_history'] = $this->Order_model->getHistory($order_id);
			$data['status'] = $this->Order_model->status;
			$data['order_history'] = $this->Order_model->getHistory($order_id, 'order');
			$data['totals'] = $this->Order_model->getTotals($data['products'],$data['order']);
			
			$this->load->view('usercontrol/includes/header', $data);
			$this->load->view('usercontrol/includes/sidebar', $data);
			$this->load->view('usercontrol/includes/topnav', $data);
			$this->load->view('usercontrol/product/vieworder', $data);
			$this->load->view('usercontrol/includes/footer', $data);
		}
		else{
			die("You are not allow to see.. !");
		}
	}
	
	
	public function wallet_requests_details($id){
		$userdetails = $this->userdetails();
		$get = $this->input->get(null,true);
		$id=(int)$id;

		if(empty($userdetails)){ redirect('/login'); }

		$data['request'] = $this->db->query("SELECT * FROM wallet_requests WHERE id={$id} AND user_id=". $userdetails['id'])->row_array();
		if(!$data['request']){
			show_404();
		}

		$filter = array(
			'user_id' => $userdetails['id'],
			'id_in' => $data['request']['tran_ids'],
		);

		$data['transaction'] = $this->Wallet_model->getTransaction($filter);
		$data['status'] = $this->Wallet_model->status;
		$data['status_icon'] = $this->Wallet_model->status_icon;

		$this->view($data,'users/wallet_requests_details','usercontrol');
	}

	public function wallet_requests_list(){
		$userdetails = $this->userdetails();
		$get = $this->input->get(null,true);

		if(empty($userdetails)){ redirect('/login'); }

		$data['lists'] = $this->db->query("SELECT * FROM wallet_requests WHERE user_id=". $userdetails['id']. " ORDER BY id DESC")->result_array();

		$filter = array(
			'user_id' => $userdetails['id'],
			'status_gt' => 2,
			'old_with' => 1,
		);

		$data['status'] = $this->Wallet_model->status;
		$data['status_icon'] = $this->Wallet_model->status_icon;
		$data['payout_transaction'] = $this->Wallet_model->getTransaction($filter);

		$this->view($data,'users/wallet_requests_list','usercontrol');
	}
	public function mywallet(){
		$userdetails = $this->userdetails();
		$get = $this->input->get(null,true);

		if(empty($userdetails)){ redirect('/login'); }
		$filter = array(
			'user_id' => $userdetails['id'],
			'status_gt' => 0,
			'parent_id' => 0,
		);

		if ( isset($get['type']) && $get['type'] ) {
			$filter['types'] = $get['type'];
		}

		if (isset($get['paid_status']) && $get['paid_status']) {
			$filter['paid_status'] = $get['paid_status'];
		}

		$data['site_setting'] = $this->Product_model->getSettings('site');

		$this->load->model('Total_model');
		$data['user_totals'] = $this->Total_model->getUserTotals((int)$userdetails['id']);

		$post = $this->input->post(null,true);
		$get = $this->input->get(null,true);

		if (isset($post['request_payment_all'])) {
			$json = array();

			$ids = (array)$post['ids'];
			$transactions = $this->db->query("SELECT * FROM wallet WHERE id IN (". implode(",", $ids) .") ")->result();

			$request = [
				'tran_ids' => implode(",", $ids),
				'status' => 0,
				'user_id' => (int)$userdetails['id'],
				'total' => 0,
				'created_at' => date("Y-m-d H:i:s"),
			];

			foreach ($transactions as $key => $value) {
				$request['total'] += (float)$value->amount;
			}

			if($request['total'] > 0){
				$this->db->query("UPDATE wallet SET status=2 WHERE id IN (". implode(",", $ids) .") ");
				$this->db->insert("wallet_requests", $request);
				$this->load->model('Mail_model');
				$this->Mail_model->send_wallet_withdrawal_req($request['total'], $userdetails);
				$json['success'] = 1;
			} else{
				$json['error'] = 'Withdrwal total must be greater than zero..!';
			}


			// echo "<pre>"; print_r($_POST); echo "</pre>";die; 

			/*
			$wallet = $this->Wallet_model->getallUnpaid($userdetails['id']);

			if($wallet){
				$this->load->model('Mail_model');
				$this->Mail_model->send_wallet_withdrawal_req($data['totals']['wallet_unpaid_amount'], $userdetails);
				foreach ($wallet as $key => $value) {
					$this->Wallet_model->changeStatus($value['id'],2);
				}

				$json['success'] = __('user.request_send_successfully');
			}
			
			*/
			echo json_encode($json);die;
		}

		$data['wallet_unpaid_amount'] = (float)$this->db->query("SELECT SUM(amount) as total FROM wallet WHERE status=1 AND user_id=". (int)$userdetails['id'])->row()->total;

		
		$filter['sortBy'] = isset($get['sortby']) ? $get['sortby'] : '';
		$filter['orderBy'] = isset($get['order']) ? $get['order'] : '';

		$data['request_status'] = $this->Wallet_model->request_status;
		$data['status'] = $this->Wallet_model->status;
		$data['status_icon'] = $this->Wallet_model->status_icon;
		$data['transaction'] = $this->Wallet_model->getTransaction($filter);
		$data['Wallet_model'] = $this->Wallet_model;
		
		$data['refer_total'] = $this->Product_model->getReferalTotals($userdetails['id']);
		$data['site_setting'] = $this->Product_model->getSettings('site');


		/*My Payout*/
		/*$filter = array(
			'user_id' => $userdetails['id'],
			'status_gt' => 2,
		);
		$data['payout_transaction'] = $this->Wallet_model->getTransaction($filter);*/


		/* Add Payout*/
		/*$data['paymentlist'] = $this->Product_model->getAllPayment($userdetails['id']);
		if (isset($data['paymentlist'][0])) {
			$data['paymentlist'] = array(
				'payment_id'             => $data['paymentlist'][0]['payment_id'],
				'payment_bank_name'      => $data['paymentlist'][0]['payment_bank_name'],
				'payment_account_number' => $data['paymentlist'][0]['payment_account_number'],
				'payment_account_name'   => $data['paymentlist'][0]['payment_account_name'],
				'payment_ifsc_code'      => $data['paymentlist'][0]['payment_ifsc_code'],
			);
		} else {
			$data['paymentlist'] = array(
				'payment_id'             => 0,
				'payment_bank_name'      => '',
				'payment_account_number' => '',
				'payment_account_name'   => '',
				'payment_ifsc_code'      => '',
			);
		}

		$data['paypalaccounts'] = $this->Product_model->getPaypalAccounts($userdetails['id']);
		if (isset($data['paypalaccounts'][0])) {
			$data['paypalaccounts'] = array(
				'paypal_email' => $data['paypalaccounts'][0]['paypal_email'],
				'id'           => $data['paypalaccounts'][0]['id'],
			);
		} else {
			$data['paypalaccounts'] = array(
				'paypal_email' => '',
				'id'           => 0,
			);
		}*/

		//$data['table'] = $this->load->view("usercontrol/users/parts/wallet_tr", $data, true);
	
		/*$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);
		$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/users/mywallet', $data);
		$this->load->view('usercontrol/includes/footer', $data);*/

		$this->view($data,'users/newmywallet','usercontrol');
	}
	public function getRecurringTransaction(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		$id = (int)$this->input->post('id');
		$filter = array(
			'user_id' => $userdetails['id'],
			'status_gt' => 1,
			'parent_id' => $id,
		);

		$data['recurring'] = $id;
		$data['request_status'] = $this->Wallet_model->request_status;
		$data['status'] = $this->Wallet_model->status;
		$data['status_icon'] = $this->Wallet_model->status_icon;
		$data['transaction'] = $this->Wallet_model->getTransaction($filter);
		//$json['table'] = $this->load->view("usercontrol/users/parts/wallet_tr", $data, true);

		$json['table'] = '';
		foreach ($data['transaction'] as $key => $value) {
			$data['class'] = 'child-recurring';
			$data['force_class'] = $_POST['ischild'] == 'true' ? 'child-arrow' : '';
			$data['recurring'] = $id;
			$data['value'] = $value;
 			$data['wallet_status'] = $data['status'];
			$json['table'] .= $this->load->view("usercontrol/users/parts/new_wallet_tr", $data, true);
		}
	
		echo json_encode($json);
	}
	public function form(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		$store_setting = $this->Product_model->getSettings('store');
		if(!$store_setting['status']){ show_404(); }
 
		$this->load->model("Form_model");
		$data['forms'] = $this->Form_model->getForms($userdetails['id']);		
 		foreach ($data['forms'] as $key => $value) { 			 
 			$data['forms'][$key]['coupon_name'] = $this->Form_model->getFormCouponname(($value['coupon']) ? $value['coupon'] : 0);
 			$data['forms'][$key]['public_page'] = base_url('form/'.$value['seo'].'/'.base64_encode($this->userdetails()['id']));
 			$data['forms'][$key]['count_coupon'] = $this->Form_model->getFormCouponCount($value['form_id'],$this->userdetails()['id']);
 			$data['forms'][$key]['coupon_code'] = $this->Form_model->getFormCouponCode($value['coupon']);
 			$data['forms'][$key]['seo'] = str_replace('_', ' ', $value['seo']) ;
 		}
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);
		$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/form/index', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}
	public function generateformcode($form = 0){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		else {
			if($form){
				$data['form_id'] = $form;
				$data['user_id'] = $userdetails['id'];
				$this->load->model("Form_model");
				$data['getForm'] 	= $this->Form_model->getForm($form);
				$this->load->view('usercontrol/form/generatecode', $data);
			}
		}
	}
	public function category_auto(){
		$userdetails = $this->userdetails();
		if(!$this->userdetails()){ redirect('/', 'refresh'); }
		$keyword = $this->input->get('query');
		
		$data = $this->db->query("SELECT id as value,name as label FROM categories WHERE name  like ". $this->db->escape("%".$keyword."%") ." ")->result_array();
		
		echo json_encode($data);die;
	}
	public function store_products(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		$vendor_setting = $this->Product_model->getSettings('vendor');
		if((!isset($userdetails['is_vendor']) || !$userdetails['is_vendor']) || (int)$vendor_setting['storestatus'] == 0) show_404();

		$store_setting = $this->Product_model->getSettings('store');
		if(!$store_setting['status']){ show_404(); }
		
		$filter = array(
			'seller_id' => $userdetails['id'],
		);
		$get = $this->input->get(null,true);

		if(isset($get['category_id']) && $get['category_id']){
			$filter['category_id'] = (int)$this->input->get('category_id');
		}

		$data['default_commition'] =$this->Product_model->getSettings('productsetting');
		$data['productlist'] = $this->Product_model->getAllProductForVendor($userdetails['id'], $userdetails['type'],$filter);

		
		$this->view($data,'store/store_products','usercontrol');
	}
	public function store_setting(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		$vendor_setting = $this->Product_model->getSettings('vendor');
		if((!isset($userdetails['is_vendor']) || !$userdetails['is_vendor']) || (int)$vendor_setting['storestatus'] == 0) show_404();


		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$json = [];
			$data = $this->input->post(null,true);

			$update = [
				'vendor_status'                       => $data['vendor_status'],
				'affiliate_click_count'               => $data['affiliate_click_count'],
				'affiliate_click_amount'              => $data['affiliate_click_amount'],
				'affiliate_sale_commission_type'      => $data['affiliate_sale_commission_type'],
				'affiliate_commission_value'          => $data['affiliate_commission_value'],
				'form_affiliate_click_count'          => $data['form_affiliate_click_count'],
				'form_affiliate_click_amount'         => $data['form_affiliate_click_amount'],
				'form_affiliate_sale_commission_type' => $data['form_affiliate_sale_commission_type'],
				'form_affiliate_commission_value'     => $data['form_affiliate_commission_value'],
				'user_id'                             => (int)$userdetails['id'],
			];

			$id = $this->db->query("SELECT * FROM vendor_setting WHERE user_id=". (int)$userdetails['id'] ." ")->row();
			if($id){
				$this->db->update("vendor_setting", $update, ['user_id'=> (int)$userdetails['id'] ]);
			} else{
				$this->db->insert("vendor_setting", $update);
			}
			
			$json['success'] = 'Setting updated Successfully';
			echo json_encode($json);die;
		}

		$data['setting'] = $this->db->query("SELECT * FROM vendor_setting WHERE user_id=". (int)$userdetails['id'] ." ")->row_array();

		$this->view($data,'store/store_setting','usercontrol');
	}
	public function contact_us(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		//$vendor_setting = $this->Product_model->getSettings('vendor');
		//if((int)$vendor_setting['storestatus'] == 0) show_404();

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('subject', 'Subject', 'required');
			$this->form_validation->set_rules('body', 'Mail Body', 'required' );

			if($this->form_validation->run()){
				$data = $this->input->post(null);
				$this->load->model('Mail_model');
				$this->Mail_model->send_store_contact_vendor($data);
				$json['success'] = 'Mail sent Successfully';
			}else{
				$json['errors'] = $this->form_validation->error_array();
			}

			echo json_encode($json);die;
		}

		$data['userdetails'] = $this->Product_model->userdetails('user');
		$data['domain'] = base_url('/');
		$data['user_mobile'] = '';
		
		$this->load->model('PagebuilderModel');
		$register_form = $this->PagebuilderModel->getSettings('registration_builder');
		if($register_form){
			$customField = json_decode($register_form['registration_builder'],1);
			
			foreach ($customField as $_key => $_value) {
				$field_name = 'custom_'. $_value['name'];
				if(!isset($json['errors'][$field_name]) && $_value['mobile_validation']  == 'true'){
					$custom_val = json_decode($data['userdetails']['value'],1);
					$data['user_mobile'] = isset($custom_val[$field_name]) ? $custom_val[$field_name] : '';
				}
			}
		}

		$this->view($data,'store/store_contact','usercontrol');
	}

	public function store_edit_product($product_id = 0){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		$vendor_setting = $this->Product_model->getSettings('vendor');
		if((int)$vendor_setting['storestatus'] == 0) show_404();

		$data['vendor_setting'] = $vendor_setting;
		$data['setting'] 	= $this->Product_model->getSettings('productsetting');
		$data['product'] = $this->Product_model->getProductById($product_id);
		if($data['product']){
			$data['seller'] = $this->db->query("SELECT * FROM product_affiliate WHERE product_id=". (int)$data['product']->product_id ." ")->row();
			if(!$data['seller'] || $data['seller']->user_id != $userdetails['id']){
				show_404();
			}

			$data['categories'] =$this->Product_model->getProductCategory($data['product']->product_id);
			$data['downloads'] = $this->Product_model->parseDownloads($data['product']->downloadable_files);
			$data['product_state'] = $this->db->query("SELECT * FROM states WHERE id=". (int)$data['product']->state_id )->row();
			$data['states'] = $this->db->query("SELECT * FROM states WHERE country_id=". (int)$data['product_state']->country_id )->result();
		}

		$data['country_list'] = $this->db->query("SELECT name,id FROM countries")->result();
		$data['seller_setting'] = $this->db->query("SELECT * FROM vendor_setting WHERE user_id=". (int)$userdetails['id'] ." ")->row();

		$this->view($data,'store/product_form','usercontrol');
	}
	public function store_save_product(){
		$userdetails = $this->userdetails();
		$post = $this->input->post(null,true);	

		$vendor_setting = $this->Product_model->getSettings('vendor');
		if((int)$vendor_setting['storestatus'] == 0) show_404();	

		if(!empty($post)){
			$product_id = (int)$this->input->post('product_id',true);
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');

			$this->form_validation->set_rules('product_name', __('admin.product_name_'), 'required');
			$this->form_validation->set_rules('product_description', __('admin.product_description'), 'required' );

			if($post['allow_country'] == "1"){
				$this->form_validation->set_rules('state_id', 'State', 'required' );
			}
			$this->form_validation->set_rules('product_short_description', __('admin.short_description'),'required|min_length[5]|max_length[150]',
		        array(
	                'required'      => 'Enter %s',
	                'is_unique'     => 'This %s already exists.',
	                'min_length' 	=> '%s: the minimum of characters is %s',
	                'max_length' 	=> '%s: the maximum of characters is %s',
		        )
			);
			$this->form_validation->set_rules('category[]',"Category", "required");
			$this->form_validation->set_rules('product_price', 'Product Price', 'required');
			$this->form_validation->set_rules('product_sku', 'Product SKU', 'required');
			$this->form_validation->set_rules('product_video', 'Product Video', 'trim');

			if( $post['product_recursion_type'] == 'custom' ){
				$this->form_validation->set_rules('product_recursion', 'Product Recursion', 'required');
				if( $post['product_recursion'] == 'custom_time' ){
					$this->form_validation->set_rules('recursion_custom_time', 'Custom Time', 'required|greater_than[0]');
				}
			}
			
			$product_recursion = ($post['product_recursion_type'] && $post['product_recursion_type'] != 'default') ? $post['product_recursion'] : "";
			$recursion_custom_time = ($product_recursion == 'custom_time' ) ? $post['recursion_custom_time'] : 0;

			if($this->form_validation->run()){
				$post = $this->input->post(null,true);				

				$errors = array();
				$downloadable_files = array();

				if($product_id){
					$product_details = $this->Product_model->getProductById($product_id);
					$_downloads = $this->Product_model->parseDownloads($product_details->downloadable_files);

					foreach ($post['keep_files'] as $_value) {
						if(isset($_downloads[$_value])){
							$downloadable_files[] = $_downloads[$_value];
						} else{
							@unlink(APPPATH.'/downloads/'.$_value);
						}
					}
				}

				$details = array(
					'product_name'                 =>  $post['product_name'],
					'product_description'          =>  $post['product_description'],
					'product_short_description'    =>  $post['product_short_description'],
					'product_price'                =>  $post['product_price'],
					'product_sku'                  =>  $post['product_sku'],
					'product_video'                =>  $post['product_video'],
					'product_price'                =>  $post['product_price'],
					'product_type'                 =>  $post['product_type'],
					'state_id'                     =>  $post['allow_country'] == "1" ? (int)$post['state_id'] : 0,
					'product_commision_type'       =>  'default',
					'product_commision_value'      =>  0,
					'product_click_commision_type' =>  'default',
					'product_click_commision_ppc'  =>  0,
					'product_click_commision_per'  =>  0,
					'on_store'                     =>  (int)$post['on_store'],
					'allow_shipping'               =>  (int)$post['allow_shipping'],
					'allow_upload_file'            =>  (int)$post['allow_upload_file'],
					'allow_comment'                =>  (int)$post['allow_comment'],
					//'product_status'             =>  1,
					'product_ipaddress'            =>  $_SERVER['REMOTE_ADDR'],
					'product_recursion_type'       =>  $post['product_recursion_type'],
					'recursion_endtime'            =>  (isset($post['recursion_endtime_status']) && $post['recursion_endtime']) ? date("Y-m-d H:i:s",strtotime($post['recursion_endtime'])) : null,
					'product_recursion'            =>  $product_recursion,
					'recursion_custom_time'        =>  (int)$recursion_custom_time,
				);					
				
				if($_FILES['product_featured_image']['error'] != 0 && $product_id == 0 ){
					$errors['product_featured_image'] = 'Select Featured Image File!';
				}else if(!empty($_FILES['product_featured_image']['name'])){
					$upload_response = $this->upload_photo('product_featured_image','assets/images/product/upload/thumb');
					if($upload_response['success']){
						$details['product_featured_image'] = $upload_response['upload_data']['file_name'];
					}else{
						$errors['product_featured_image'] = $upload_response['msg'];
					}
				}

				if(!empty($_FILES['downloadable_file'])){
					$files = $_FILES['downloadable_file'];
					$count_file = count($_FILES['downloadable_file']['name']);
					
					$this->load->helper('string');		
					for($i=0; $i<$count_file; $i++){
						$extension = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
				        if($extension=='zip'){
					        $FILES['downloadable_files']['name'] = md5(random_string('alnum', 10));
					        $FILES['downloadable_files']['type'] = $files['type'][$i];
					        $FILES['downloadable_files']['tmp_name'] = $files['tmp_name'][$i];
					        $FILES['downloadable_files']['error'] = $files['error'][$i];
					        $FILES['downloadable_files']['size'] = $files['size'][$i];    
					     	
					     	if(empty($FILES['downloadable_files']['error'])){
					     		move_uploaded_file($FILES['downloadable_files']['tmp_name'], APPPATH.'/downloads/'. $FILES['downloadable_files']['name']);
								
								$downloadable_files[] = array(
									'type' => $FILES['downloadable_files']['type'],
									'name' => $FILES['downloadable_files']['name'],
									'mask' => $files['name'][$i],
								);
					     	}else{
					     		$errors['downloadable_files'] = $FILES['downloadable_files']['error'];
					     	}
					    } else {
				     		$errors['downloadable_files'] = 'Only zip file are allow..';
					    }
		    		}
				}

				$new_product_created = false;
				$old_product_data = [];
				if(empty($errors)){
					$details['downloadable_files'] = json_encode($downloadable_files);
					$this->session->set_flashdata('success', __('admin.product_added_successfully'));

					$details['product_created_by'] = $userdetails['id'];
					$details['product_created_date'] = date('Y-m-d H:i:s');					

					if($product_id){
						$old_product_data = $this->db->query("SELECT * FROM product WHERE product_id = ". (int)$product_id)->row_array();

						$this->Product_model->update_data('product', $details, array('product_id' => $product_id));
					}else{
						$details['product_status'] = 0;
						$product_id = $this->Product_model->create_data('product', $details);

						$new_product_created = true;

						$notificationData = array(
							'notification_url'          => 'updateproduct/'.$product_id,
							'notification_type'         =>  'vendor_product',
							'notification_title'        =>  __('admin.new_product_added_by_vendor'),
							'notification_viewfor'      =>  'admin',
							'notification_actionID'     =>  $product_id,
							'notification_description'  =>  $post['product_name'].' product is addded by '. $userdetails['username'] .' in store on '.date('Y-m-d H:i:s'),
							'notification_is_read'      =>  '0',
							'notification_created_date' =>  date('Y-m-d H:i:s'),
							'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
						);	
						$this->insertnotification($notificationData);
						

						$seofilename = $this->friendly_seo_string($post['product_name'].'-'.$post['product_sku']);
						$seofilename = strtolower($seofilename);
						$product_slug = $seofilename.'-'.$product_id;
						$this->db->query("UPDATE product SET product_slug = ". $this->db->escape($product_slug) ." WHERE product_id =". $product_id);
					}

					if($product_id){
						$this->db->query("DELETE FROM product_categories WHERE product_id = {$product_id}");
						if(isset($post['category']) && is_array($post['category'])){
							foreach ($post['category'] as $category_id) {
								$category = array(
									'product_id' => $product_id,
									'category_id' => $category_id,
								);

								$this->Product_model->create_data('product_categories', $category);
							}
						}

						$admin_comment = '';
						if(isset($post['admin_comment']) && $post['admin_comment']){
							$admin_comment = $post['admin_comment'];
						}

						$seller_comm = [
							'affiliate_click_commission_type' => $post['affiliate_click_commission_type'],
							'affiliate_click_count'           => $post['affiliate_click_count'],
							'affiliate_click_amount'          => $post['affiliate_click_amount'],
							'affiliate_sale_commission_type'  => $post['affiliate_sale_commission_type'],
							'affiliate_commission_value'      => $post['affiliate_commission_value'],
						];

						
						$old_setting = $this->db->query("SELECT * FROM product_affiliate WHERE product_id=". (int)$product_id ." ")->row();

						if($old_setting){
							if(
								$seller_comm['affiliate_click_commission_type'] != $old_setting->affiliate_click_commission_type ||
								$seller_comm['affiliate_click_count']           != $old_setting->affiliate_click_count ||
								$seller_comm['affiliate_click_amount']          != $old_setting->affiliate_click_amount ||
								$seller_comm['affiliate_sale_commission_type']  != $old_setting->affiliate_sale_commission_type ||
								$seller_comm['affiliate_commission_value']      != $old_setting->affiliate_commission_value
							){
								$this->db->query("UPDATE product SET product_status=0 WHERE product_id=". (int)$product_id);
							}
						}

						if($post['action'] == 'ask_to_review'){
							$this->db->query("UPDATE product SET product_status=0 WHERE product_id=". (int)$product_id);
						}

						$this->Product_model->assignToSeller($product_id, $details, $userdetails['id'], $admin_comment,'affiliate', $seller_comm);

						$this->load->model('Mail_model');
						if($new_product_created){
							$this->Mail_model->vendor_create_product($product_id);
						} else {
							$product_data = $this->db->query("SELECT * FROM product WHERE product_id = ". (int)$product_id)->row_array();

							if($old_product_data['product_status'] != $product_data['product_status'] && $product_data['product_status'] == 0){
								$this->Mail_model->vendor_product_status_change($product_id, 'admin',true);
							}
						}
					}					

					$json['location'] = base_url('usercontrol/store_products');
				} else {
					$json['errors'] = $errors;
				}
			} else {
				$json['errors'] = $this->form_validation->error_array();

				if(isset($json['errors']['category[]'])){
					$json['errors']['category_auto'] = $json['errors']['category[]'];
				}
			}

			echo json_encode($json);die;
		}
	}
	public function productupload($id = null){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('login'); }
		if(empty($id)){
			$this->session->set_flashdata('error', __('admin.photo_can_not_be_uploaded'));
			redirect('usercontrol/store_products');
		}

		$vendor_setting = $this->Product_model->getSettings('vendor');
		if((int)$vendor_setting['storestatus'] == 0) show_404();

		if(!empty($_FILES)){
			$errors= array();

			$details = array(
				'product_id'                        =>  $id,
				'product_media_upload_type'         =>  'image',
				'product_media_upload_status'       =>  1,
				'product_media_upload_os'           =>  $this->agent->platform(),
				'product_media_upload_browser'      =>  $this->agent->browser(),
				'product_media_upload_isp'          =>  gethostbyaddr($_SERVER['REMOTE_ADDR']),
				'product_media_upload_ipaddress'    =>  $_SERVER['REMOTE_ADDR'],
				'product_media_upload_created_by'   =>  $userdetails['id'],
				'product_media_upload_created_date' =>  date('Y-m-d H:i:s'),
			);

			$details['product_media_upload_created_by'] = $userdetails['id'];
			if(!empty($_FILES['product_multiple_image'])){
				$files = $_FILES;
				$cpt = count($_FILES['product_multiple_image']['name']);
				
				$this->load->helper('string');
				$config = array(
					'upload_path'   => 'assets/images/product/upload/',
					'allowed_types' => 'png|gif|jpeg|jpg|PNG|GIF|JPEG|JPG',
					'max_size'      => 2048,
					'file_name'     => random_string('alnum', 32),
				);

				$this->load->library('upload', $config);
				$this->load->library('image_lib');
			    $this->upload->initialize($config);

				for($i=0; $i<$cpt; $i++){
					if($files['product_multiple_image']['error'][$i] == 0){
				        $_FILES['product_multiple_images']['name'] = $files['product_multiple_image']['name'][$i];
				        $_FILES['product_multiple_images']['type'] = $files['product_multiple_image']['type'][$i];
				        $_FILES['product_multiple_images']['tmp_name'] = $files['product_multiple_image']['tmp_name'][$i];
				        $_FILES['product_multiple_images']['error'] = $files['product_multiple_image']['error'][$i];
				        $_FILES['product_multiple_images']['size'] = $files['product_multiple_image']['size'][$i];    
				        
				        $this->upload->do_upload('product_multiple_images');
				        $upload_details = $this->upload->data();
						
						$config1 = array(
							'source_image'   => $upload_details['full_path'],
							'new_image'      => 'assets/images/product/upload/thumb',
							'maintain_ratio' => true,
							'width'          => 300,
							'dynamic_output' => 1,
							'height'         => 300
						);

						$this->image_lib->initialize($config1);
						$this->image_lib->resize();
						$this->image_lib->clear();
						
						if($upload_details){
							$details['product_media_upload_path'] = $upload_details['file_name'];
						} else {
							$errors['avatar_error'] = $upload_details['msg'];
						}

						$details['product_media_upload_created_date'] = date('Y-m-d H:i:s');
						$this->Product_model->create_data('product_media_upload', $details);					
					}
	    		}
			}

			if(!empty($errors)){
				$this->session->set_flashdata('error', $errors['avatar_error']);
				redirect('usercontrol/productupload/'.$id);exit();
			}

			$this->session->set_flashdata('success', __('admin.product_images_added_successfully'));
			redirect('usercontrol/productupload/'.$id);
		}

		$data['imageslist'] = $this->Product_model->getAllImages($id);
		$this->view($data,'store/productupload','usercontrol');
	}
	public function videoupload($id = null){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('admin'); }
		if(empty($id)){ redirect('usercontrol/store_products'); }

		$vendor_setting = $this->Product_model->getSettings('vendor');
		if((int)$vendor_setting['storestatus'] == 0) show_404();

		$post = $this->input->post(null,true);

		if(!empty($post)){
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			$this->form_validation->set_rules('product_media_upload_video', __('admin.product_video'), 'trim');
			if($this->form_validation->run())
			{
				$errors= array();
				$details = array(
					'product_id'                        => $id,
					'product_media_upload_path'         =>  $this->input->post('product_media_upload_path',true),
					'product_media_upload_type'         =>  'video',
					'product_media_upload_status'       =>  1,
					'product_media_upload_os'           =>  $this->agent->platform(),
					'product_media_upload_browser'      =>  $this->agent->browser(),
					'product_media_upload_isp'          =>  gethostbyaddr($_SERVER['REMOTE_ADDR']),
					'product_media_upload_ipaddress'    =>  $_SERVER['REMOTE_ADDR'],
					'product_media_upload_created_by'   =>  $userdetails['id'],
					'product_media_upload_created_date' =>  date('Y-m-d H:i:s'),
				);
				if(!empty($_FILES['video_thumbnail_image']['name'])){
					$upload_response = $this->upload_photo('video_thumbnail_image','assets/images/product/upload/thumb');
					if($upload_response['success']){
						$details['product_media_upload_video_image'] = $upload_response['upload_data']['file_name'];
					}
					else{
						$errors['avatar_error'] = $upload_response['msg'];
					}
				}
				if(!empty($errors)){
					$this->session->set_flashdata('error', $errors['avatar_error']);
					redirect('usercontrol/videoupload/'.$id);
					exit();
				}

				$this->session->set_flashdata('success', __('admin.product_video_and_images_added_successfully'));
				$details['product_media_upload_created_by'] = $userdetails['id'];
				$details['product_media_upload_created_date'] = date('Y-m-d H:i:s');
				$this->Product_model->create_data('product_media_upload', $details);
				$data['productinfo'] = $this->Product_model->getProductByIdArray($id);

				/*$notificationData = array(
					'notification_url'          => '/videoupload/'.$id,
					'notification_type'         =>  'product',
					'notification_title'        =>  __('admin.new_product_added_in_affiliate_program'),
					'notification_view_user_id' =>  '',
					'notification_viewfor'      =>  'user',
					'notification_actionID'     =>  $id,
					'notification_description'  =>  'New Video uploaded on product '.$data['productinfo']['product_name'].' by admin in affiliate Program on '.date('Y-m-d H:i:s'),
					'notification_is_read'      =>  '0',
					'notification_created_date' =>  date('Y-m-d H:i:s'),
					'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
				);
				$this->insertnotification($notificationData);*/
				redirect('usercontrol/videoupload/'.$id);
			} else {
				$this->session->set_flashdata('error', __('admin.form_validation_error'));
				redirect('usercontrol/videoupload/'.$id);
			}

		} else {
			$data['videoimageslist'] = $this->Product_model->getAllVideoImages($id);
			$data['videoslist'] = $this->Product_model->getAllVideos($id);
			$data['user'] = $userdetails;
			
			$this->view($data,'store/videoupload','usercontrol');
		}
	}
	public function deleteAllproducts(){
		$post = $this->input->post(null,true);

		$vendor_setting = $this->Product_model->getSettings('vendor');
		if((int)$vendor_setting['storestatus'] == 0) show_404();

		if(!empty($post['product']) || !empty($post['form'])){
			if(isset($post['product'])){
				foreach($post['product'] as $id){
					$this->Product_model->deleteproducts((int)$id);
				}
			}

			$this->session->set_flashdata('success', __('user.product_is_deleted_successfully'));
			redirect(base_url() . 'usercontrol/store_products');
		}
		else{
			$id = (int)$this->input->get('delete_id');
			$res = $this->Product_model->deleteproducts($id);
			$this->session->set_flashdata('success', __('user.product_is_deleted_successfully'));
			redirect(base_url() . 'usercontrol/store_products');
		}

		$this->session->set_flashdata('error', __('user.product_delete_failed'));
		redirect(base_url() . 'usercontrol/store_products');
	}
	public function calc_commission(){
		$data = $this->input->post(null,true);
		$userdetails = $this->userdetails();

		$vendor_setting = $this->Product_model->getSettings('vendor');
		if((int)$vendor_setting['storestatus'] == 0) show_404();

		if (isset($data['product_id']) && (int)$data['product_id'] > 0) {
			$product = $this->db->query("SELECT * FROM product_affiliate WHERE product_id=". (int)$data['product_id'])->row();
			
			if($product){
				$data['admin_sale_commission_type']      = $product->admin_sale_commission_type;
				$data['admin_commission_value']          = $product->admin_commission_value;
				$data['admin_click_commission_type']     = $product->admin_click_commission_type;
				$data['admin_click_amount']              = $product->admin_click_amount;
				$data['admin_click_count']               = $product->admin_click_count;
				
				/*$data['affiliate_sale_commission_type']  = $product->affiliate_sale_commission_type;
				$data['affiliate_commission_value']      = $product->affiliate_commission_value;
				$data['affiliate_click_commission_type'] = $product->affiliate_click_commission_type;
				$data['affiliate_click_count']           = $product->affiliate_click_count;
				$data['affiliate_click_amount']          = $product->affiliate_click_amount;*/
			}
		} else {
			$data['admin_sale_commission_type']      = 'default';
			$data['admin_click_commission_type']     = 'default';
		}


		$setting = array(
			'product_id' => $data['product_id'],
			'product_price' => $data['product_price'],

			'admin_click_commission_type' => ($data['admin_click_commission_type'] != '' ? $data['admin_click_commission_type'] : 'default'),
			'admin_click_count'           => $data['admin_click_count'],
			'admin_click_amount'          => $data['admin_click_amount'],
			'admin_sale_commission_type'  => $data['admin_sale_commission_type'] != '' ? $data['admin_sale_commission_type'] : 'default',
			'admin_commission_value'      => $data['admin_commission_value'],

		    'affiliate_click_commission_type' => $data['affiliate_click_commission_type'],
		    'affiliate_click_count' => $data['affiliate_click_count'],
		    'affiliate_click_amount' => $data['affiliate_click_amount'],
		    'affiliate_sale_commission_type' => $data['affiliate_sale_commission_type'],
		    'affiliate_commission_value' => $data['affiliate_commission_value'],
		    'user_id' => (int)$userdetails['id'],
		);


		
		$json['commission'] = $this->Product_model->calcVendorCommission($setting);
		$json['success'] = true;

		echo json_encode($json);
	}


	public function store_coupon_manage($coupon_id = 0){
		if(!$this->userdetails()){ redirect('/', 'refresh'); }
		$this->load->model("Coupon_model");
		$data['coupon'] = $this->Coupon_model->getCoupon($coupon_id);
		$data['product'] = $this->db->query("SELECT product_id,product_name FROM product")->result_array();

		if(isset($data['coupon']['vendor_id']) && $data['coupon']['vendor_id'] != $this->userdetails()['id']){
			show_404();
		}
		
		$this->view($data,'store/coupon_form','usercontrol');
	}
	public function store_coupon_delete($coupon_id){
		if(!$this->userdetails()){ redirect('/', 'refresh'); }

		$this->load->model("Coupon_model");
		$this->Coupon_model->deleteCoupon($coupon_id);

		$this->session->set_flashdata('success', __('admin.coupon_deleted_successfully'));
		
		redirect(base_url("usercontrol/store_coupon"));
	}
	public function store_coupon(){
		$userdetails = $this->userdetails();
		if(!$userdetails){ redirect('/', 'refresh'); }
		if(!isset($userdetails['is_vendor']) || !$userdetails['is_vendor']) show_404();

		$this->load->model("Coupon_model");
		$data['coupons'] = $this->Coupon_model->getCoupons((int)$userdetails['id']);
		$ptotal = $this->db->query('SELECT product_id FROM product')->num_rows();
		 
		foreach ($data['coupons'] as $key => $value) {
			if(strtolower($value['allow_for']) == 's'){
				$data['coupons'][$key]['product_count'] = count(explode(',', $value['products']));
			}else{
				$data['coupons'][$key]['product_count'] = $ptotal;
			}
			$data['coupons'][$key]['count_coupon'] = $this->Coupon_model->getCouponCount($value['coupon_id']);
		}
		
		$this->view($data,'store/coupon_index','usercontrol');
	}

	public function save_coupon(){
		if(!$this->userdetails()){ redirect('/', 'refresh'); }
		$this->load->library('form_validation');
		$json = array();

		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('code', 'Coupon Code', 'required|trim');
		$this->form_validation->set_rules('type', 'Type', 'required|trim');
		$this->form_validation->set_rules('allow_for', 'Allow For', 'required|trim');
		$this->form_validation->set_rules('discount', 'Discount', 'required|trim');
		$this->form_validation->set_rules('date_start', 'Start Date', 'required|trim');
		$this->form_validation->set_rules('date_end', 'End Date', 'required|trim');
		$this->form_validation->set_rules('status', 'Status', 'required|trim');

		if ($this->form_validation->run() == FALSE) {
			$json['errors'] = $this->form_validation->error_array();
		} else {
			$data = $this->input->post(null,true);

			$coupon = array(
				'vendor_id'  => $this->userdetails()['id'],
				'name'       => $data['name'],
				'code'       => $data['code'],
				'type'       => $data['type'],
				'allow_for'  => $data['allow_for'],
				'discount'   => $data['discount'],
				'date_start' => date("Y-m-d", strtotime($data['date_start'])),
				'date_end'   => date("Y-m-d", strtotime($data['date_end'])),
				'uses_total' => $data['uses_total'],
				'status'     => $data['status'],
				'products'   => implode(",", $data['products']),
				'date_added' => date("Y-m-d H:i:s"),
			);

			if($data['id'] > 0){
				unset($coupon['date_added']);
				$this->db->update("coupon",$coupon,['coupon_id' => $data['id']]);
			} else {
				$this->db->insert("coupon",$coupon);
				$coupon_id = $this->db->insert_id();
			}
			$json['location'] = base_url("usercontrol/store_coupon");
		}

		echo json_encode($json);
	}







	public function programs(){
		$userdetails = $this->userdetails();
		$market_vendor = $this->Product_model->getSettings('market_vendor');
		if((!isset($userdetails['is_vendor']) || !$userdetails['is_vendor']) || (int)$market_vendor['marketvendorstatus'] == 0) show_404();

		if(!$userdetails){ redirect('usercontrol/dashboard', 'refresh'); }
		$filter = [
			'vendor_id' => $userdetails['id'],
		];

		$data['programs'] = $this->IntegrationModel->getPrograms($filter);

		$this->view($data,'integration/programs','usercontrol', $data);
	}

	public function programs_form($program_id = 0){
		$userdetails = $this->userdetails();
		if(!$this->userdetails()){ redirect('usercontrol/dashboard', 'refresh'); }

		$market_vendor = $this->Product_model->getSettings('market_vendor');
		if((!isset($userdetails['is_vendor']) || !$userdetails['is_vendor']) || (int)$market_vendor['marketvendorstatus'] == 0) show_404();

		$data = array();
		if($program_id){
			$data['programs'] = $this->IntegrationModel->getProgramByID($program_id);
		}
		

		$data['market_vendor'] = $this->Product_model->getSettings('market_vendor');
		
		$this->view($data,'integration/programs_form','usercontrol', $data);
	}

	public function delete_programs_form(){
		$userdetails = $this->userdetails();
		if(!$this->userdetails()){ redirect('usercontrol/dashboard', 'refresh'); }
		$market_vendor = $this->Product_model->getSettings('market_vendor');
		if((!isset($userdetails['is_vendor']) || !$userdetails['is_vendor']) || (int)$market_vendor['marketvendorstatus'] == 0) show_404();

		$program_id = (int)$this->input->post("id",true);
		$ads = $this->db->select("*")->from("integration_tools")->where("program_id",$program_id)->get()->num_rows();
		
		if($ads == 0){
			$this->db->query("DELETE FROM integration_programs WHERE id=". $program_id);
			$json['success'] = true;
		} else{
			$json['message'] = "There are {$ads} Integration Tools Assgin to This Program";
		}
		
		echo json_encode($json);
	}


	public function editProgram(){
		$userdetails = $this->userdetails();
		if(!$userdetails){ redirect('usercontrol/dashboard', 'refresh'); }
		$market_vendor = $this->Product_model->getSettings('market_vendor');
		if((!isset($userdetails['is_vendor']) || !$userdetails['is_vendor']) || (int)$market_vendor['marketvendorstatus'] == 0) show_404();

		$data = $this->input->post(null,true);

		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		if($data['sale_status']){
			$this->form_validation->set_rules('commission_type', 'Commission Type', 'required|trim');
			$this->form_validation->set_rules('commission_sale', 'Sale Comission', 'required|trim|numeric|greater_than[0]');
		}
		if($data['click_status']){
			$this->form_validation->set_rules('commission_number_of_click', 'Number of click', 'required|trim|numeric|greater_than[0]');
			$this->form_validation->set_rules('commission_click_commission', 'Click Commission', 'required|trim|numeric|greater_than[0]');
		}
		
		if ($this->form_validation->run() == FALSE) {
			$json['errors'] = $this->form_validation->error_array();
		} else {
			
			$program_id = (int)$data['program_id'];

			$program_id = $this->IntegrationModel->editProgram($data,$program_id,'user',$userdetails['id']);
			if($program_id){
				$json['location'] = base_url("usercontrol/programs");
			} else{
				$json['errors']['name'] = "Something Wrong";
			}
		}

		echo json_encode($json);
	}

	public function integration_tools($page= 1){
		$userdetails = $this->userdetails();
		if(!$userdetails){ redirect('usercontrol/dashboard', 'refresh'); }

		$market_vendor = $this->Product_model->getSettings('market_vendor');
		if((!isset($userdetails['is_vendor']) || !$userdetails['is_vendor']) || (int)$market_vendor['marketvendorstatus'] == 0) show_404();

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$post = $this->input->post(null,true);
			$get = $this->input->get(null,true);
			$filter = array(
				'page' => isset($get['page']) ? $get['page'] : $page,
				'limitdata' => 10,
				'vendor_id' => $userdetails['id'],
			);

			if (isset($post['category_id'])) {
				$filter['category_id'] = $post['category_id'];
			}
			if (isset($post['ads_name'])) {
				$filter['ads_name'] = $post['ads_name'];
			}

			$json = array();
			list($data['tools'],$total) = $this->IntegrationModel->getProgramTools($filter);
			$json['view'] = $this->load->view("usercontrol/integration_tools/integration_tools_list", $data, true);

			$this->load->library('pagination');
			$this->pagination->cur_page = $filter['page'];

			$config['base_url'] = base_url('usercontrol/integration_tools');
			$config['per_page'] = $filter['limitdata'];
			$config['total_rows'] = $total;
			$config['use_page_numbers'] = TRUE;
			$config['page_query_string'] = TRUE;
			$config['enable_query_strings'] = TRUE;
			$_GET['page'] = $filter['page'];
			$config['query_string_segment'] = 'page';
			$this->pagination->initialize($config);
			$json['pagination'] = $this->pagination->create_links();

			echo json_encode($json);die;
		}
		$data['categories'] = $this->db->query("SELECT id as value,name as label FROM integration_category ")->result_array();

		$this->view($data,'integration_tools/integration_tools','usercontrol', $data);
	}

	public function integration_code_modal(){
		if(!$this->userdetails()){ redirect('usercontrol/dashboard', 'refresh'); }

		$market_vendor = $this->Product_model->getSettings('market_vendor');
		if((!isset($userdetails['is_vendor']) || !$userdetails['is_vendor']) || (int)$market_vendor['marketvendorstatus'] == 0) show_404();

		$data['action_code'] = 'action_code';
		$data['general_code'] = 'general_code';

		$tools = $this->IntegrationModel->getProgramToolsByID((int)$this->input->post('id',true));
		if($tools){
			
			$data['name'] = $tools['name'];
			$data['target_link'] = $tools['target_link'];
			$data['tool_type'] = $tools['tool_type'];
			if($tools['tool_type'] == 'action'){
				$data['action_code'] = $tools['action_code'];
			}
			if($tools['tool_type'] == 'general_click'){
				$data['general_code'] = $tools['general_code'];
			}
		}
		$json['html'] = $this->load->view('usercontrol/integration/integration_code_modal', $data, true);

		echo json_encode($json);die;
	}

	public function integration_tools_form($type="banner", $tools_id = 0){
		$userdetails = $this->userdetails();
		if(!$userdetails){ redirect('usercontrol/dashboard', 'refresh'); }

		$market_vendor = $this->Product_model->getSettings('market_vendor');
		if((!isset($userdetails['is_vendor']) || !$userdetails['is_vendor']) || (int)$market_vendor['marketvendorstatus'] == 0) show_404();
		
		$setting = $this->Product_model->getSettings('referlevel');
		$data['max_level'] = isset($setting['levels']) ? (int)$setting['levels'] : 3;

		if($tools_id){
			$data['tool'] = $this->IntegrationModel->getProgramToolsByID($tools_id);
			$category_ids = explode(",", $data['tool']['category']);
			if(count(array_filter($category_ids)) > 0){
				$data['categories'] = $this->db->query("SELECT id as value,name as label FROM integration_category WHERE id IN (". implode(",", $category_ids) .") ")->result_array();
			}

			$data['referlevel'] = $data['tool']['commission']['referlevel'];
			for ($i=1; $i <= $data['max_level']; $i++) { 
				$data['referlevel_'. $i] = $data['tool']['commission']['referlevel_'. $i];
			}
		}
		
		$data['default_marketpostback'] = $this->Product_model->getSettings('marketpostback');
		$data['programs'] = $this->IntegrationModel->getPrograms(['vendor_id' => $userdetails['id'],'status' => 1]);
		$data['type'] = $type;
		$data['CurrencySymbol'] = $this->currency->getSymbol();
		$data['users'] = $this->db->query("SELECT CONCAT(firstname,' ',lastname) as name,id FROM users WHERE type='user'")->result_array();
		
		$this->view($data,'integration_tools/integration_tools_form','usercontrol', $data);
	}

	function valid_url_custom($url) {
        if(filter_var($url, FILTER_VALIDATE_URL)){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }

	public function integration_tools_form_post(){
		$userdetails = $this->userdetails();
		if(!$userdetails){ redirect('usercontrol/dashboard', 'refresh'); }

		$market_vendor = $this->Product_model->getSettings('market_vendor');
		if((!isset($userdetails['is_vendor']) || !$userdetails['is_vendor']) || (int)$market_vendor['marketvendorstatus'] == 0) show_404();
		
		$data = $this->input->post(null,true);
		$program_tool_id = isset($data['program_tool_id']) ? (int)$data['program_tool_id'] : 0;
		
		$this->form_validation->set_rules('target_link', 'Target Link', 'callback_valid_url_custom');
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		//$this->form_validation->set_rules('status', 'Status', 'required|trim');
		$this->form_validation->set_rules('type', 'Type', 'required|trim');
		$this->form_validation->set_rules('tool_type', 'Tool Type', 'required|trim');

		if($data['tool_type'] == 'action'){
			$this->form_validation->set_rules('action_click', 'Action Click', 'required|trim');
			$this->form_validation->set_rules('action_amount', 'Action Amount', 'required|trim');
			$this->form_validation->set_rules('action_code', 'Action Code', 'required|trim');
			$data['program_id'] = 0;
		}
		else if($data['tool_type'] == 'general_click'){
			$this->form_validation->set_rules('general_click', 'General Click', 'required|trim');
			$this->form_validation->set_rules('general_amount', 'General Amount', 'required|trim');
			$this->form_validation->set_rules('general_code', 'General Code', 'required|trim');
			$data['program_id'] = 0;
		}
		else if($data['tool_type'] == 'program'){
			$this->form_validation->set_rules('program_id', 'Program', 'required|trim');
		}

		if($data['type'] == 'text_ads'){
			$this->form_validation->set_rules('text_ads_content', 'Ads Content', 'required|trim');
			$this->form_validation->set_rules('text_color', 'Color', 'required|trim');
			$this->form_validation->set_rules('text_bg_color', 'Background color', 'required|trim');
			$this->form_validation->set_rules('text_border_color', 'Border color', 'required|trim');
			$this->form_validation->set_rules('text_size', 'Border color', 'required|trim');
		}
		else if($data['type'] == 'link_ads'){
			$this->form_validation->set_rules('link_title', 'Link Title', 'required|trim');
		}
		else if($data['type'] == 'video_ads'){
			$this->form_validation->set_rules('video_link', 'Video Link', 'required|trim');
			$this->form_validation->set_rules('button_text', 'Video Button Text', 'required|trim');
			$this->form_validation->set_rules('video_height', 'Video Height', 'required|trim');
			$this->form_validation->set_rules('video_width', 'Video Width', 'required|trim');
		}
		$this->form_validation->set_message('valid_url_custom','Enter a valid URL.');

		if( $data['recursion'] == 'custom_time' ){
			$this->form_validation->set_rules('recursion_custom_time', 'Custom Time', 'required|greater_than[0]');
		}

		if ($this->form_validation->run() == FALSE) {
			$json['errors'] = $this->form_validation->error_array();
		} else {
			$checkActionCode = 0;

			if($data['tool_type'] == 'action'){
				$checkActionCode = $this->db->query("SELECT * FROM integration_tools WHERE action_code like ". $this->db->escape($data['action_code']) ." AND id != ". $program_tool_id)->num_rows();
				if($checkActionCode > 0)  $json['errors']['action_code'] = "Action code to be unique";
			}
			else if($data['tool_type'] == 'general_click'){
				$checkActionCode = $this->db->query("SELECT * FROM integration_tools WHERE general_code like ". $this->db->escape($data['general_code']) ." AND id != ". $program_tool_id)->num_rows();
				if($checkActionCode > 0) $json['errors']['general_code'] = "General code to be unique";
			}

			if($_FILES['featured_image']['error'] != 0 && $program_tool_id == 0 ){
				$json['errors']['featured_image'] = 'Select Featured Image File!';
			}

			if (isset($data['marketpostback']['status']) && $data['marketpostback']['status'] == 'custom') {
				if (filter_var($data['marketpostback']['url'], FILTER_VALIDATE_URL) === FALSE) {
				    $json['errors']['marketpostback-url'] = 'Enter a valid postback URL';
				}
			}

			if(count($json['errors']) == 0){
				$data['featured_image'] = $data['old_featured_image'];
				if(!empty($_FILES['featured_image']['name'])){
					$upload_response = $this->Product_model->upload_photo('featured_image','assets/images/product/upload/thumb');
					if($upload_response['success']){
						$data['featured_image'] = $upload_response['upload_data']['file_name'];
					}
				}

				$program_tool_id = $this->IntegrationModel->editProgramTools($data,$_FILES['custom_banner'],'vendor', $userdetails['id']);

				if($program_tool_id){
					if(isset($data['save_close'])){
						$json['location'] = base_url("usercontrol/integration_tools_form/". $data['type'] ."/". $program_tool_id);
					} else{
						$json['location'] = base_url("usercontrol/integration_tools");
					}
				} else{
					$json['errors']['name'] = "Something Wrong";
				}
			}
		}

		echo json_encode($json);
	}

	public function integration_tools_delete($tools_id){
		$userdetails = $this->userdetails();
		if(!$this->userdetails()){ redirect('usercontrol/dashboard', 'refresh'); }
		$market_vendor = $this->Product_model->getSettings('market_vendor');
		if((!isset($userdetails['is_vendor']) || !$userdetails['is_vendor']) || (int)$market_vendor['marketvendorstatus'] == 0) show_404();

		$this->IntegrationModel->deleteTools($tools_id);

		redirect(base_url("usercontrol/integration_tools"));
	}

	public function tool_get_code($control = 'usercontrol'){
		$tools_id = (int)$this->input->post("id",true);
		if($control == 'usercontrol'){
			if(!$this->userdetails()){ redirect('usercontrol/dashboard', 'refresh'); }
			$data['user_id'] = $this->userdetails()['id'];
		}
		else if($control == 'usercontrol'){
			if(!$this->userlogins()){ redirect('usercontrol/dashboard', 'refresh'); }
			$data['user_id'] = $this->userlogins()['id'];
		}
		
		$data['tool'] = $this->IntegrationModel->getProgramToolsByID($tools_id);
		if($data['tool']){
			$json['html'] = $this->load->view("integration/code", $data, true);
		}
		
		echo json_encode($json);die;
	}

	public function integration_category_auto(){
		$userdetails = $this->userdetails();
		if(!$this->userdetails()){ redirect('/', 'refresh'); }
		$keyword = $this->input->get('query');
		
		$data = $this->db->query("SELECT id as value,name as label FROM integration_category WHERE name  like ". $this->db->escape("%".$keyword."%") ." ")->result_array();
		
		echo json_encode($data);die;
	}

	public function integration_tools_duplicate($tools_id){
		$this->IntegrationModel->duplicate_tools($tools_id);

		$this->session->set_flashdata('success', 'Ads Duplicate Successfully');
		redirect(base_url('usercontrol/integration_tools'));
	}

	public function integration_code_modal_new(){
		$userdetails = $this->userdetails();
		if(!$userdetails){ redirect('/', 'refresh'); }

		$data['action_code'] = 'action_code';
		$data['general_code'] = 'general_code';

		$tools = $this->IntegrationModel->getProgramToolsByID((int)$this->input->post('id',true));
		if($tools){
			
			$data['name'] = $tools['name'];
			$data['target_link'] = $tools['target_link'];
			$data['tool_type'] = $tools['tool_type'];
			if($tools['tool_type'] == 'action'){
				$data['action_code'] = $tools['action_code'];
			}
			if($tools['tool_type'] == 'general_click'){
				$data['general_code'] = $tools['general_code'];
			}
		}

		$json['html'] = $this->load->view('admincontrol/integration/integration_code_modal', $data, true);

		echo json_encode($json);die;
	}

	public function get_withdrawal_modal(){
		$userdetails = $this->userdetails();
		if(!$userdetails){ redirect('/', 'refresh'); }

		$site_setting = $this->Product_model->getSettings('site');
		$data['ids'] = $this->input->post("ids",true);

		if($data['ids'] == 'all'){
			$transaction_total = $this->db->query("SELECT SUM(amount) total FROM wallet WHERE status=1 AND user_id=".(int)$userdetails['id']  )->row()->total;
		} else{
			$transaction_total = $this->db->query("SELECT SUM(amount) total FROM wallet WHERE id IN (". $data['ids'] .") ")->row()->total;
		}
		
		if( (float)$transaction_total >= (float)$site_setting['wallet_min_amount']){
			$this->load->model('Withdrawal_payment_model');
			$data['payment_methods'] = $this->Withdrawal_payment_model->getPaymentMethods([
				'get_user_setting' => true,
			]);
		} else{
			$data['warning'] = $site_setting['wallet_min_message'] ;
		}

		$json['html'] = $this->load->view('usercontrol/users/parts/withdrawal_modal', $data, true);
		echo json_encode($json);die;
	}

	/*public function integration_orders(){
		$userdetails = $this->userdetails();
		if(!$userdetails){ redirect('usercontrol/dashboard', 'refresh'); }
		$market_vendor = $this->Product_model->getSettings('market_vendor');
		if((!isset($userdetails['is_vendor']) || !$userdetails['is_vendor']) || (int)$market_vendor['marketvendorstatus'] == 0) show_404();

		$filter = [
			'vendor_id' => $userdetails['id'],
		];

		$data['orders'] = $this->IntegrationModel->getOrders();
		$this->view($data,'integration_tools/orders','usercontrol', $data);
	}*/

	/*public function integration_mywallet(){
		$userdetails = $this->userdetails();
		$get = $this->input->get(null,true);
		if(empty($userdetails)){ redirect('/login'); }

		$filter = array(
			'user_id' => $userdetails['id'],
			'status_gt' => 0,
			'parent_id' => 0,
		);

		if ( isset($get['type']) && $get['type'] ) {
			$filter['types'] = $get['type'];
		}

		$post = $this->input->post(null,true);
		
		$data['request_status'] = $this->Wallet_model->request_status;
		$data['status'] = $this->Wallet_model->status;
		$data['status_icon'] = $this->Wallet_model->status_icon;
		$data['transaction'] = $this->Wallet_model->getIntegrationTransaction($filter);

		$data['site_setting'] = $this->Product_model->getSettings('site');
		$data['table'] = $this->load->view("usercontrol/integration_tools/wallet_tr", $data, true);
	
		$this->view($data,'integration_tools/wallet','usercontrol');
	}*/

	public function get_withdrwal_history($id)
	{
		$status_history = $this->db->query("SELECT * FROM wallet_requests_history WHERE req_id={$id} ORDER BY id DESC ")->result_array();
		$json['html'] = '';

		foreach ($status_history as $key => $value) {
			$badge = $value['transaction_id'] ?  ' <span class="badge badge-blue-grey d-inline-block">Tran ID: '. $value['transaction_id'] .'</span>' : '';
			$json['html'].= '<tr>
				<td>'. withdrwal_status($value['status'])  .'</td>
				<td>'. $value['comment'] . $badge.'</td>
			</tr>';
		}

		echo json_encode($json);die;
	}
}