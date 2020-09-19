<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

class Firstsetting extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('user_model', 'user');
		$this->load->model('Product_model');
		$this->load->model('Income_model');
		$this->load->model('Wallet_model');
		$this->load->model('Report_model');
		___construct(1);
	}

	public function userdetails(){ return $this->session->userdata('administrator'); }

	public function index(){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }

		$data['administrator'] = $this->session->userdata('administrator');
		$data['missing'] = $this->Product_model->getSettingStatus();

		$this->Report_model->view('firstsetting/index', $data);
	}

	public function get_step(){
		$number = (int)$this->input->post("number",true);
		$save = (int)$this->input->post("save",true);
		$post_data = $this->input->post(null,true);

		switch ($save) {
			case 1:
				if ((int)$post_data['store']['affiliate_cookie'] == 0) {
					$json['errors']['store[affiliate_cookie]'] = "Enter valid value" ;
				}

				if (!isset($json['errors'])) {
					$this->Setting_model->save('store', $post_data['store']);
					$this->Setting_model->save('site', $post_data['site']);
					$this->Setting_model->save('login', $post_data['login']);
					$this->Setting_model->save('referlevel', $post_data['referlevel']);
				}

				break;
			case 2:
				if (!filter_var($post_data['profile_email'], FILTER_VALIDATE_EMAIL)) {
					$json['errors']['profile_email'] = "Enter a valid email.." ;
				}

				if (!isset($json['errors'])) {
					$d = $this->session->userdata('administrator');
					$d['email'] = $post_data['profile_email'];

					$this->session->set_userdata(array('administrator'=>$d));
				 	$this->db->query("UPDATE users SET email = '". $post_data['profile_email'] ."' WHERE id=". (int)$d['id']);
				}
				break;
			case 3:
				if (!filter_var($post_data['site']['notify_email'], FILTER_VALIDATE_EMAIL)) {
					$json['errors']['site[notify_email]'] = "Enter a valid email.." ;
				}

				if (!filter_var($post_data['email']['from_email'], FILTER_VALIDATE_EMAIL)) {
					$json['errors']['email[from_email]'] = "Enter a valid email.." ;
				}

				if ($post_data['email']['from_name'] == '') {
					$json['errors']['email[from_name]'] = "Enter From Name" ;
				}

				if (!isset($json['errors'])) {
					$this->Setting_model->save('email', $post_data['email']);
					$this->Setting_model->save('site', $post_data['site']);
				}
				break;
			case 4:
				if ((int)$post_data['currency'] == 0 ) {
					$json['errors']['currency'] = "Select currency" ;
				}
				if ((int)$post_data['language'] == 0 ) {
					$json['errors']['language'] = "Select language" ;
				}

				if (!isset($json['errors'])) {
				 	$this->db->query("UPDATE currency SET  is_default = 0 ");
				 	$this->db->query("UPDATE currency SET  is_default = 1 WHERE currency_id=". (int)$post_data['currency']);
				
				 	$this->db->query("UPDATE language SET  is_default = 0 ");
			 		$this->db->query("UPDATE language SET  is_default = 1 WHERE id=". (int)$post_data['language']);
			 	}
				break;

			case 5:
				if ($post_data['password'] != '' ) {
					
					if($post_data['password'] != $post_data['c_password']){
						$json['errors']['c_password'] = "Confirm password not match..!";
					}

					if (!isset($json['errors'])) {
						$userdetails = $this->userdetails();

						
						$this->db->where('id', $userdetails['id']);
						$this->db->update('users',  array('password'=>sha1($this->input->post('password'))) );
				 	}
				}

				break;
		}

		switch ($number) {
			case 1:
				$data['store'] = $this->Product_model->getSettings('store');
				$data['site'] = $this->Product_model->getSettings('site');
				$data['login'] = $this->Product_model->getSettings('login');
				$data['referlevel'] = $this->Product_model->getSettings('referlevel');
				$this->load->model('PagebuilderModel');

				$themes = $this->PagebuilderModel->getAlltheme();
				$this->config->load('theme');
				$data['themes'] = $this->config->item('themes');
				foreach ($themes as $key => $theme) {
					$data['themes'][] = array(
						'id' => $theme['theme_id'],
						'type' => 'builder',
						'name' => $theme['name'],
						'image' => 'builder.png',
					);
				}
				break;
			case 2:
				$d = $this->session->userdata('administrator');

				$data['profile_email'] = $d['email'];
				break;
			case 3:
				$data['setting'] = $this->Product_model->getSettings('email');
				$data['site'] = $this->Product_model->getSettings('site');
				break;
			case 4:
				$data['currency'] = $this->db->query("SELECT * FROM currency")->result_array();
				$data['language'] = $this->db->query("SELECT * FROM language")->result_array();
				break;
		}

		$data['total_step'] = 6;
		$data['number'] = $number;

		


		if (!isset($json['errors'])) {
			$json['html'] = $this->load->view("firstsetting/step", $data, true);
		}

		echo json_encode($json);
	}
	
}
