<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
ini_set('display_errors', 0);

class Setting extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('Product_model');
		$this->load->library('user_agent');
		___construct(1);
	}

	public function getModal(){
		$data = array();
		$input = $this->input->post(null,true);
		$key = $input['key'];
		$type = $input['type'];

		$data['skey'] = $type;
		$data['setting_key'] = $key;
		
		if($key == 'live_dashboard'){
			$data['title'] = "Dashboard Setting";
			$data['settings'] = array(
				'sound_status'              => array( 'name' =>'Notification Sound', 'type' => 'switch'),
				'action_status'             => array( 'name' =>'Notification Banner For Action', 'type' => 'switch'),
				'integration_order_status'  => array( 'name' =>'Notification Banner For Integration Order', 'type' => 'switch'),
				'affiliate_register_status' => array( 'name' =>'Notification Banner For Affiliate Register', 'type' => 'switch'),
				'local_store_order_status'  => array( 'name' =>'Notification Banner For Local Store Order', 'type' => 'switch'),
				'data_load_interval'        => array( 'name' =>'Dashboard Data Load Time', 'type' => 'number','help' => 'Time Interval In Seconds'),
			);
		}
		else if($key == 'live_log'){
			$data['title'] = "Log Window Setting";

			$data['settings'] = array(
				'integration_logs'   => array( 'name' => 'Integration Logs', 'type' => 'switch'),
				'integration_orders' => array( 'name' => 'Integration Orders', 'type' => 'switch'),
				'newuser'            => array( 'name' => 'New User', 'type' => 'switch'),
				//'notifications'      => 'Notifications'
			);
		}

		$data['db_value'] = $this->Product_model->getSettings($key);
		$data['html'] = $this->load->view("common/setting_model",$data,true);

		echo json_encode($data);die;
	}

	public function saveSetting(){
		$input = $this->input->post(null,true);
		$this->Setting_model->save($input['setting_key'], $input['settings']);

		$data['success'] = 1;
		echo json_encode($data);die;
	}
}