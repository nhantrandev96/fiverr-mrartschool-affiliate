<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);

class Integration extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('user_model', 'user');
		$this->load->model('Product_model');
		$this->load->model('Report_model');
		$this->load->model('IntegrationModel');
		___construct(1);
	}

	public function userdetails(){ return $this->session->userdata('administrator'); }
	public function userlogins(){ return $this->session->userdata('user'); }

	public function script(){
		header('Content-Type: application/javascript');
		$this->load->view('integration/script');
	}

	public function show_affiliate_id(){
		$data = $this->input->get(null);
		$data['script'] = "general_integration";
		header('Content-Type: application/javascript');
		$this->load->view('integration/show_id', $data);
	}

	public function general_integration(){
		$data['script'] = "general_integration";
		header('Content-Type: application/javascript');
		$this->load->view('integration/general_integration', $data);
	}
	public function shopify(){
		$data['script'] = "shopify";
		header('Content-Type: application/javascript');
		$this->load->view('integration/general_integration', $data);
	}
	public function xcart(){
		$data['script'] = "xcart";
		header('Content-Type: application/javascript');
		$this->load->view('integration/general_integration', $data);
	}
	public function zencart(){
		$data['script'] = "zencart";
		header('Content-Type: application/javascript');
		$this->load->view('integration/general_integration', $data);
	}
	public function paypal(){
		$data['script'] = "paypal";
		header('Content-Type: application/javascript');
		$this->load->view('integration/general_integration', $data);
	}
	public function bigcommerce(){
		$data['script'] = "bigcommerce";
		header('Content-Type: application/javascript');
		$this->load->view('integration/general_integration', $data);
	}
	public function oscommerce(){
		$data['script'] = "oscommerce";
		header('Content-Type: application/javascript');
		$this->load->view('integration/general_integration', $data);
	}
	
	public function addClick(){
		$content = file_get_contents("php://input");

		if($content){
			parse_str($content, $data);
		}else{
			$data = $this->input->get(null);
		}
	 	//file_put_contents('./logs.txt', json_encode($data) .PHP_EOL , FILE_APPEND | LOCK_EX); die;

		$this->IntegrationModel->addClick($data);
	}

	public function addOrder(){
		$content = file_get_contents("php://input");
		if($content){
			parse_str($content, $data);
		}else{
			$data = $this->input->get(null);
		}
		//file_put_contents('./logs.txt', json_encode($data) .PHP_EOL , FILE_APPEND | LOCK_EX); die;
		$this->IntegrationModel->addOrder($data);
	}

	public function stopRecurring(){
		$content = file_get_contents("php://input");
		if($content){
			parse_str($content, $data);
		}else{
			$data = $this->input->get(null);
		}
		$this->IntegrationModel->stopRecurring($data);
	}

	public function addUser(){
		$content = file_get_contents("php://input");
		if($content){
			parse_str($content, $data);
		}else{
			$data = $this->input->get(null);
		}

		list($firstname, $lastname) = explode(" ", $data['display_name']);
		//$username = (preg_replace('/([^@]*).*/', '$1', $data['user_email'])) . $data['ID'];
		$username = $data['user_login'];
		$password = rand(11111111,99999999);

		$geo = $this->ip_info();
		
		$_data = array(
			'firstname'                 => $firstname,
			'lastname'                  => $lastname ? $lastname : $firstname,
			'email'                     => $data['user_email'],
			'username'                  => $username,
			'password'                  => sha1($password),
			'refid'                     => 0,
			'type'                      => 'user',
			'Country'                   => $geo['id'],
			'City'                      => (string)$geo['city'],
			'phone'                     => $geo['city'],
			'twaddress'                 => '',
			'address1'                  => '',
			'address2'                  => '',
			'ucity'                     => $geo['city'],
			'ucountry'                  => $geo['id'],
			'state'                     => $geo['state'],
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
			'value'                     => json_encode(array()),
		);

		$json = array();

		$checkEmail = $this->db->query("SELECT id FROM users WHERE email like ". $this->db->escape($_data['email']))->num_rows();
		if($checkEmail > 0){ $json['error'][] = "Email Already Exist"; }

		$checkUsername = $this->db->query("SELECT id FROM users WHERE username like ". $this->db->escape($_data['username']))->num_rows();
		if($checkUsername > 0){ $json['error'][] = "Username Already Exist"; }

		if(!isset($json['error'])){
			$this->user->insert($_data);

			$_data['password'] = $password;
            $this->load->model('Product_model');
            $this->load->model('Mail_model');
            
			$this->Mail_model->send_register_integration_mail($_data,__('user.welcome_to_new_user_registration'));

			$notificationData = array(
				'notification_url'          => '/userslist/',
				'notification_type'         =>  'user',
				'notification_title'        =>  __('user.new_user_registration'),
				'notification_viewfor'      =>  'admin',
				'notification_actionID'     =>  0,
				'notification_description'  =>  $_data['firstname'].' '.$_data['lastname'].' register as a  on affiliate Program on '.date('Y-m-d H:i:s'),
				'notification_is_read'      =>  '0',
				'notification_created_date' =>  date('Y-m-d H:i:s'),
				'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
			);

			$this->Product_model->create_data('notification', $notificationData);
		} else {
			 echo "<pre>"; print_r($json); echo "</pre>";die; 
		}
	
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

	public function addOrderPaypal(){
		$post = $this->input->post(null,true);
		$paypalData = json_decode($post['post'] , 1);

		if($paypalData && isset($paypalData['payment_status'])){
			switch ($paypalData['payment_status']) {
				case 'Completed':
				case 'Pending':
					$this->IntegrationModel->addOrder(array(
						'script_name'    => 'paypal',
						'order_currency' => $paypalData['mc_currency'],
						'order_total'    => $paypalData['auth_amount'],
						'af_id'          => $post['af_id'],
						'order_id'       => $post['order_id'],
						'base_url'       => $post['base_url'],
						'product_ids'    => $post['product_ids'],
					));

					break;
				default:
					echo $paypalData['payment_status'];
					break;
			}
		}
		
	}

	

	public function index(){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }
		
		$data['integration_modules'] = $this->modules_list();
		$this->Report_model->view('admincontrol/integration/index', $data);
	}

	public function programs(){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }
		
		$data['programs'] = $this->IntegrationModel->getPrograms();
		$this->Report_model->view('admincontrol/integration/programs', $data);
	}

	public function programs_form($program_id = 0){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }
		$data = array();
		if($program_id){
			$data['programs'] = $this->IntegrationModel->getProgramByID($program_id);
		}
		
		$this->Report_model->view('admincontrol/integration/programs_form', $data);
	}

	public function delete_programs_form(){

		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }
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
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }
		$data = $this->input->post(null,true);

		$program_id = (int)$data['program_id'];
		$programs = $this->IntegrationModel->getProgramByID($program_id);

		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		if($data['sale_status']){
			$this->form_validation->set_rules('commission_type', 'Name', 'required|trim');
			$this->form_validation->set_rules('commission_sale', 'Name', 'required|trim');
		}
		if($data['click_status']){
			$this->form_validation->set_rules('commission_number_of_click', 'Name', 'required|trim');
			$this->form_validation->set_rules('commission_click_commission', 'Name', 'required|trim');
		}

		
		if($programs['vendor_id']){
			if((float)$data['admin_commission_sale'] > 0 && $data['admin_commission_sale'] > $programs['commission_sale']){
				$custom_errors['admin_commission_sale'] = 'Must be less than vendor commission ('. c_format($programs['commission_sale']) .')';
			}

			if((float)$data['admin_commission_click_commission'] > 0 && $data['admin_commission_click_commission'] > $programs['commission_click_commission']){
				$custom_errors['admin_commission_click_commission'] = 'Must be less than vendor commission ('. c_format($programs['commission_click_commission']) .')';
			}

			if((float)$data['admin_commission_number_of_click'] > 0 && $data['admin_commission_number_of_click'] > $programs['commission_number_of_click']){
				$custom_errors['admin_commission_number_of_click'] = 'Must be less than vendor commission ('. (int)$programs['commission_number_of_click'] .')';
			}
		}
		
		if ($this->form_validation->run() == FALSE) {
			$json['errors'] = $this->form_validation->error_array();
			foreach ($custom_errors as $key => $value) { $json['errors'][$key] = $value; }
		} else {
			foreach ($custom_errors as $key => $value) { $json['errors'][$key] = $value; }

			if(count($json['errors']) == 0){
				$program_id = $this->IntegrationModel->editProgram($data,$program_id, 'admin');
				if($program_id){
					$json['location'] = base_url("integration/programs");
				} else{
					$json['errors']['name'] = "Something Wrong";
				}
			}

		}

		echo json_encode($json);
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
		
		$this->load->model('PagebuilderModel');
		$register_form = $this->PagebuilderModel->getSettings('registration_builder');
		$data['customField'] = json_decode($register_form['registration_builder'],1);

		$this->Report_model->view('admincontrol/integration/instructions', $data);
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

		$integration_modules['affiliate_register_api'] = array(
			'name' => "Affiliate Register API",
			'image' => base_url('assets/integration/affiliate_register_api.jpg'),
		);

		return $integration_modules;
	}

	public function integration_tools($page= 1){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$post = $this->input->post(null,true);
			$get = $this->input->get(null,true);
			 
			$filter = array(
				'page' => isset($get['page']) ? $get['page'] : $page,
				'limitdata' => 10,
			);

			if (isset($post['category_id'])) {
				$filter['category_id'] = $post['category_id'];
			}
			if (isset($post['ads_name'])) {
				$filter['ads_name'] = $post['ads_name'];
			}
			if (isset($post['vendor_id']) && $post['vendor_id']) {
				$filter['vendor_id'] = $post['vendor_id'];
			}

			if (isset($post['show_only']) && $post['show_only'] == 'true') {
				$filter['show_only'] = 'admin';
			}

			$json = array();
			list($data['tools'],$total) = $this->IntegrationModel->getProgramTools($filter);
			$json['view'] = $this->load->view("admincontrol/integration/integration_tools_list", $data, true);

			$this->load->library('pagination');
			$this->pagination->cur_page = $filter['page'];

			$config['base_url'] = base_url('integration/integration_tools');
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

		$data['vendors'] = $this->db->query("SELECT users.id,CONCAT(users.firstname,' ',users.lastname) as name FROM `integration_tools` LEFT JOIN users ON users.id= vendor_id WHERE vendor_id > 0 GROUP by vendor_id")->result_array();

		$data['categories'] = $this->db->query("SELECT id as value,name as label FROM integration_category ")->result_array();
		$this->Report_model->view('admincontrol/integration/integration_tools', $data);
	}

	public function integration_code_modal(){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }

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

	public function integration_tools_duplicate($tools_id){
		$this->IntegrationModel->duplicate_tools($tools_id);

		$this->session->set_flashdata('success', 'Ads Duplicate Successfully');
		redirect(base_url('integration/integration_tools'));
	}

	public function integration_tools_form($type="banner", $tools_id = 0){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }
		
		$setting = $this->Product_model->getSettings('referlevel');
		$data['max_level'] = isset($setting['levels']) ? (int)$setting['levels'] : 3;

		$program_filter = [];
		if($tools_id){
			$data['tool'] = $this->IntegrationModel->getProgramToolsByID($tools_id);
			$program_filter['vendor_id'] = (int)$data['tool']['vendor_id'];

			$category_ids = explode(",", $data['tool']['category']);
			if(count(array_filter($category_ids)) > 0){
				$data['categories'] = $this->db->query("SELECT id as value,name as label FROM integration_category WHERE id IN (". implode(",", $category_ids) .") ")->result_array();
			}

			$data['referlevel'] = $data['tool']['commission']['referlevel'];
			for ($i=1; $i <= $data['max_level']; $i++) { 
				$data['referlevel_'. $i] = $data['tool']['commission']['referlevel_'. $i];
			}
		} else{
			$program_filter['vendor_id'] = 0;
		}

		$data['default_marketpostback'] = $this->Product_model->getSettings('marketpostback');
		$data['programs'] = $this->IntegrationModel->getPrograms($program_filter);
		$data['type'] = $type;
		$data['CurrencySymbol'] = $this->currency->getSymbol();
		$data['users'] = $this->db->query("SELECT CONCAT(firstname,' ',lastname) as name,id FROM users WHERE type='user'")->result_array();
		
		$this->Report_model->view('admincontrol/integration/integration_tools_form', $data);
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
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }
		
		$custom_errors = [];
		$data = $this->input->post(null,true);
		$program_tool_id = isset($data['program_tool_id']) ? (int)$data['program_tool_id'] : 0;
		
		$this->form_validation->set_rules('target_link', 'Target Link', 'callback_valid_url_custom');
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('status', 'Status', 'required|trim');
		$this->form_validation->set_rules('type', 'Type', 'required|trim');
		$this->form_validation->set_rules('tool_type', 'Tool Type', 'required|trim');

    	$old = $this->db->query("SELECT * FROM integration_tools WHERE id=". (int)$program_tool_id)->row();

		if($data['tool_type'] == 'action'){
			if($old->vendor_id){
				$this->form_validation->set_rules('admin_action_click', 'Action Click', 'required|trim');
				$this->form_validation->set_rules('admin_action_amount', 'Action Amount', 'required|trim');

				if((float)$data['admin_action_amount'] > 0 && $old->action_amount < (float)$data['admin_action_amount']){
					$custom_errors['admin_action_amount'] = 'Must be less than vendor commission ('. c_format($old->action_amount) .')';
				}

			} else{
				$this->form_validation->set_rules('action_click', 'Action Click', 'required|trim');
				$this->form_validation->set_rules('action_amount', 'Action Amount', 'required|trim');
			}
			$this->form_validation->set_rules('action_code', 'Action Code', 'required|trim');
			$data['program_id'] = 0;
		}
		else if($data['tool_type'] == 'general_click'){
			if($old->vendor_id){
				$this->form_validation->set_rules('admin_general_click', 'General Click', 'required|trim');
				$this->form_validation->set_rules('admin_general_amount', 'General Amount', 'required|trim');

				if((float)$data['admin_general_amount'] > 0 && $old->general_amount < (float)$data['admin_general_amount']){
					$custom_errors['admin_general_amount'] = 'Must be less than vendor commission ('. c_format($old->general_amount) .')';
				}

			} else{
				$this->form_validation->set_rules('general_click', 'General Click', 'required|trim');
				$this->form_validation->set_rules('general_amount', 'General Amount', 'required|trim');
			}

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

		//$this->form_validation->set_rules('recursion', 'Recursion', 'required');
		if( $data['recursion'] == 'custom_time' ){
			$this->form_validation->set_rules('recursion_custom_time', 'Custom Time', 'required|greater_than[0]');
		}
		
		if ($this->form_validation->run() == FALSE) {
			$json['errors'] = $this->form_validation->error_array();

			foreach ($custom_errors as $key => $value) { $json['errors'][$key] = $value; }
		} else {
			$checkActionCode = 0;
			foreach ($custom_errors as $key => $value) { $json['errors'][$key] = $value; }

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

				$program_tool_id = $this->IntegrationModel->editProgramTools($data,$_FILES['custom_banner']);

				if($program_tool_id){
					if(isset($data['save_close'])){
						$json['location'] = base_url("integration/integration_tools_form/". $data['type'] ."/". $program_tool_id);
					} else{
						$json['location'] = base_url("integration/integration_tools");
					}
				} else{
					$json['errors']['name'] = "Something Wrong";
				}
			}
		}

		echo json_encode($json);
	}

	public function integration_tools_delete($tools_id){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }

		$this->IntegrationModel->deleteTools($tools_id);

		redirect(base_url("integration/integration_tools"));
	}

	public function tool_get_code($control = 'admincontrol'){
		$tools_id = (int)$this->input->post("id",true);
		if($control == 'admincontrol'){
			if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }
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


	public function user_integration_tools(){
		$user = $this->userlogins();
		if(!$user){ redirect('usercontrol/dashboard', 'refresh'); }
		
		$data['tools'] = $this->IntegrationModel->getProgramTools([
			'user_id' => $user['id'],
			'status' => 1,
			'redirectLocation'=> 1,
			'restrict'=> $user['id'],
		]);

		$this->Report_model->view('usercontrol/integration/integration_tools', $data,'usercontrol');
	}

	public function orders(){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$json = array();
			$orders = $this->IntegrationModel->getDeleteOrders($this->input->post('ids',true));
			$html = '<table class="table table-sm table-bordered"><tr><td>Id</td><td>Commission</td><td>Refer Commission</td></tr>';
			foreach ($orders as $key => $value) {
				$html .= '<tr>';
				$html .= '	<td>'. $key ."</td>";
				$html .= '	<td>'. c_format($value['commission']) ."</td>";
				$html .= '	<td>'. c_format($value['refer_commission']) ."</td>";
				$html .= '</tr>';
			}
			$html .= '</table>';

			$json['html'] = $html;
			echo json_encode($json);die;
		}

		$data['orders'] = $this->IntegrationModel->getOrders();
		$this->Report_model->view('admincontrol/integration/orders', $data);
	}
	
	public function deleteOrdersConfirm(){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$json = array();
			$orders = $this->IntegrationModel->getDeleteOrders($this->input->post('ids',true));
			
			foreach ($orders as $key => $value) {
				foreach ($value['sql'] as $key => $sql) {
					$this->db->query($sql);
				}
			}
		}		

		echo json_encode($json);die;
	}
	

	public function user_orders(){
		$user = $this->userlogins();
		if(!$user){ redirect('usercontrol/dashboard', 'refresh'); }

		$data['orders'] = $this->IntegrationModel->getOrders(['user_id' => $user['id']]);
		$this->Report_model->view('usercontrol/integration/orders', $data,'usercontrol');
	}

	public function logs(){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }
		$this->load->library('pagination');
    	$this->load->helper('url');

		$filter['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
		if(isset($_GET['type']) && $_GET['type']){
			$filter['type'] = $_GET['type'];
		}

		$_data = $this->IntegrationModel->getLogs($filter);
		

		$config['base_url'] = base_url('integration/logs');
		$config['per_page'] = 50;
		$config['total_rows'] = $_data['total'];
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		$data['logs'] = $_data['records'];

		$this->Report_model->view('admincontrol/integration/logs', $data);
	}

	public function click_logs(){
		$user = $this->userlogins();
		if(!$user){ redirect('usercontrol/dashboard', 'refresh'); }
		$this->load->library('pagination');
    	$this->load->helper('url');

		$filter['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
		$filter['user_id'] = $user['id'];

		if(isset($_GET['type']) && $_GET['type']){
			$filter['type'] = $_GET['type'];
		}

		$_data = $this->IntegrationModel->getLogs($filter);


		$config['base_url'] = base_url('integration/click_logs');
		$config['per_page'] = 50;
		$config['total_rows'] = $_data['total'];
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		$data['logs'] = $_data['records'];

		$this->Report_model->view('usercontrol/integration/logs', $data ,'usercontrol');
	}

	public function delete_log(){
		$ids = (array)$this->input->post('ids',true);
		if($ids){
			$ids = implode(",", $ids);

			$this->db->query("DELETE FROM integration_clicks_logs WHERE id IN ({$ids})");
		}

		echo json_encode(array());		 
	}

	public function _zip($archive_folder,  $archive_name){
		$zip = new ZipArchive; 
		$archive_path = APPPATH . "cache/". $archive_name;
		if ($zip->open($archive_path, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) 
		{ 
		    $dir = preg_replace('/[\/]{2,}/', '/', $archive_folder."/"); 
		    $dirs = array($dir); 

		    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($archive_folder), RecursiveIteratorIterator::SELF_FIRST);
			foreach ($files as $file){
				$file = str_replace('\\', '/', $file);
				if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) ) continue;

				$file = realpath($file);

				$n = str_replace(APPPATH. (str_replace('application/', '', $archive_folder)) , '', $file);
				if (is_dir($file) === true){
					$zip->addEmptyDir(str_replace($archive_folder, '', $n . '/'));
				}else if (is_file($file) === true){
					$content = str_replace('__baseurl__', base_url(),file_get_contents($file));
					$zip->addFromString($n, $content);
				}
			}

			if ($zip->status == ZIPARCHIVE::ER_OK){
				$zip->close();
			}
		    
		    $zip->close(); 
		    
		    /*header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"".$archive_name."\"");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".filesize($archive_path));
			ob_end_flush();
			@readfile($archive_path);*/

			header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
		    header("Content-Type: application/zip");
		    header("Content-Transfer-Encoding: Binary");
		    header("Content-Length: ".filesize($archive_path));
		    header("Content-Disposition: attachment; filename=\"".$archive_name."\"");
		    header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		    header("Cache-control: private");
		    header('Pragma: private');

		    ob_end_clean();
		    readfile($archive_path);

		    @unlink($archive_path);
		} 
		else 
		{ 
		    echo 'Error, can\'t create a zip file!'; 
		}
	}

	public function download_plugin($script, $version = 0){
		if($script == 'woocommerce'){
			$path = "application/plugins/tracking-affiliate-pro/";
			$this->_zip($path,'AffiliatePro_WooCommerce.zip');
		}
		else if($script == 'wp_user_register'){
			$path = "application/plugins/wp_user_register/";
			$this->_zip($path,'WordpressWoocommerceRegistrationBridge.zip');
		}
		else if($script == 'show_affiliate_id'){
			$path = "application/plugins/show-affiliate-id/";
			$this->_zip($path,'WordpressShowAffiliateID.zip');
		}
		else if($script == 'prestashop'){
			$path = "application/plugins/ps_aff/";
			$this->_zip($path,'ps_aff.zip');
		}
		else if($script == 'magento'){
			if($version == 1){
				$path = "application/plugins/magento1/";
				$this->_zip($path,'AffiliatePro_Magento.zip');
			} else{
				$path = "application/plugins/magento/";
				$this->_zip($path,'AffiliatePro_Magento.zip');
			}
		}
		else if($script == 'opencart'){
			if($version  == 1){
				$path = "application/plugins/opencart-1564-2200/";
				$this->_zip($path,'AffiliatePro_Opencart-1564-2200.ocmod.zip');
			}
			else if($version  == 2){
				$path = "application/plugins/opencart-2300-3011/";
				$this->_zip($path,'AffiliatePro_Opencart-2300-3011.ocmod.zip');
			}
		}
	}


	public function integration_category_delete($category_id = 0){
		$userdetails = $this->userdetails();
		if(!$this->userdetails()){ redirect('admin', 'refresh'); }

		if($category_id > 0){
			$data['category'] = $this->db->query("DELETE FROM integration_category WHERE id = ". (int)$category_id);
		}

		$this->session->set_flashdata('success', 'Category Deleted Successfully');
		redirect(base_url('integration/integration_category'));
	}

	public function category_auto(){
		$userdetails = $this->userdetails();
		if(!$this->userdetails()){ redirect('admin', 'refresh'); }
		$keyword = $this->input->get('query');
		
		$data = $this->db->query("SELECT id as value,name as label FROM integration_category WHERE name  like ". $this->db->escape("%".$keyword."%") ." ")->result_array();
		
		echo json_encode($data);die;
	}

	public function integration_category_add($category_id = 0){
		$userdetails = $this->userdetails();
		if(!$this->userdetails()){ redirect('admin', 'refresh'); }

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', 'Category Name', 'required');
			
			if($this->form_validation->run()){
				$details = array(
					'name' =>  $this->input->post('name',true),
				);

				if($category_id){
					$this->Product_model->update_data('integration_category', $details, array('id' => $category_id));
				}else{
					$details['created_at'] = date('Y-m-d H:i:s');
					$category_id = $this->Product_model->create_data('integration_category', $details);
				}

				$this->session->set_flashdata('success', 'Category Saved Successfully');
				$json['location'] = base_url('integration/integration_category');	
			} else {
				$json['errors'] = $this->form_validation->error_array();
			}

			echo json_encode($json);die;
		}

		$data['category'] = array();
		if($category_id > 0){
			$data['category'] = $this->db->query("SELECT * FROM integration_category WHERE id = ". (int)$category_id)->row_array();
		}

		$data['categories'] = $this->db->query("SELECT id,name FROM integration_category")->result_array();

		$this->load->view('admincontrol/includes/header', $data);
		$this->load->view('admincontrol/includes/sidebar', $data);
		$this->load->view('admincontrol/includes/topnav', $data);
		$this->load->view('admincontrol/integration/category_add', $data);
		$this->load->view('admincontrol/includes/footer', $data);
	}

	public function integration_category($page = 1){
		$userdetails = $this->userdetails();
		if(!$this->userdetails()){ redirect('admin', 'refresh'); }

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$page = max((int)$page,1);
			
			$filter = array(
				'limit' => 100,
				'page' => $page,
			);

			list($data['categories'],$total) = $this->Product_model->getIntegrationCategory($filter);
			$data['start_from'] = (($page-1) * $filter['limit'])+1;
			$json['html'] = $this->load->view("admincontrol/integration/category_list.php",$data,true);

			$this->load->library('pagination');
			$config['base_url'] = base_url('admincontrol/integration_category/');
			$config['per_page'] = $filter['limit'];
			$config['total_rows'] = $total;
			$config['use_page_numbers'] = TRUE;
			$config['enable_query_strings'] = TRUE;
			$this->pagination->initialize($config);

			$json['pagination'] = $this->pagination->create_links();

			echo json_encode($json);die;
		}
		
		$this->load->view('admincontrol/includes/header', $data);
		$this->load->view('admincontrol/includes/sidebar', $data);
		$this->load->view('admincontrol/includes/topnav', $data);
		$this->load->view('admincontrol/integration/integration_category', $data);
		$this->load->view('admincontrol/includes/footer', $data);
	}
}