<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
ini_set('display_errors', 0);

class Payment extends MY_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function call_payment_function($code, $function){
		$arg_list = func_get_args();
		$json = array();

		$filename = APPPATH."/withdrawal_payment/controllers/{$code}.php";
		if(is_file($filename)){
			require $filename;
			$obj = new $code($this);
			unset($arg_list[0],$arg_list[1]);
			$json = call_user_func_array(array($obj, $function), $arg_list);
		}

		echo json_encode($json);
	}

	private function load_response($json){
		echo json_encode($json);die;
	}

	public function installPayementGateway(){
		$tmp_path = APPPATH.'cache/tmp/';
		$foldername = 'payout';
		$upload_path = APPPATH.'cache/tmp/'.$foldername."/";

		if(!is_dir($tmp_path)){
			if (!mkdir($tmp_path, 0777)) {
				$json['warning'] = "Can't create folder";
				$this->load_response($json);
			}
		}

		if(!$_FILES['plugin'] || !isset($_FILES['plugin']['name']) || !isset($_FILES['plugin']['tmp_name'])){
			$json['warning'] = "Choose zip file to install";
			$this->load_response($json);
		}
		 
		if (strtolower(substr(strrchr($_FILES['plugin']['name'], '.'), 1)) != 'zip') {
			$json['warning'] = "Allow only zip file..";
			$this->load_response($json);
		}

		$zip = new ZipArchive();
		if ($zip->open($_FILES["plugin"]["tmp_name"])) {
			$zip->extractTo($upload_path);
			$zip->close();
		} else {
			$json['warning'] = "Can't extract zip file";
			$this->load_response($json);
		}

		if (is_dir($upload_path . 'upload/')) {
			$files = array();
			$path = $upload_path . 'upload/*';
			foreach ((array)glob($path) as $file) {
				if (is_dir($file)) {
					$files[] = $file;
				}
			}

			$allowed = array(
				'admin_settings',
				'confirm_view',
				'controllers',
				'logo',
				'user_settings',
			);

			$safe = true;
			$destination = '';
			foreach ($files as $file) {
				$destination = str_replace('\\', '/', substr($file, strlen($upload_path . 'upload/')));
				if(!in_array($destination, $allowed)){
					$safe = false;
					break;
				}
			}

			if (!$safe) {
				$json['warning'] = 'This folder is not allowed '. $destination;
				$this->load_response($json);
			}


			$files = array();
			$path = array($upload_path . 'upload/*');
			while (count($path) != 0) {
				$next = array_shift($path);
				foreach ((array)glob($next) as $file) {
					if (is_dir($file)) {
						$path[] = $file . '/*';
					}
					$files[] = $file;
				}
			}

			foreach ($files as $key => $file) {
				$destination = str_replace('\\', '/', substr($file, strlen($upload_path . 'upload/')));
				$path = APPPATH . 'withdrawal_payment/'. $destination;

				if (is_dir($file) && !is_dir($path)) {
					mkdir($path, 0777);
				}

				if (is_file($file)) {
					rename($file, $path);
				}
			}

			deleteDir($upload_path);
			$json['location'] = 1;
			$this->session->set_flashdata('success', 'Module installed successfully');
		}
		
		$this->load_response($json);
	}

	public function delete_plugin($code){
		$filename = APPPATH."/withdrawal_payment/controllers/{$code}.php";
		if(is_file($filename)){
			$files= [
				APPPATH."withdrawal_payment/admin_settings/{$code}.php",
				APPPATH."withdrawal_payment/confirm_view/{$code}.php",
				APPPATH."withdrawal_payment/controllers/{$code}.php",
				APPPATH."withdrawal_payment/user_settings/{$code}.php",
				APPPATH."withdrawal_payment/logo/{$code}.png",
			];

			foreach ($files as $key => $file) {
				if (is_file($file)) {
					unlink($file);
				}
			}
		}

		$this->session->set_flashdata('success', 'Module deleted successfully');
		$this->load->model('Setting_model');
		$this->Setting_model->clear('withdrawalpayment_'.$code);
		redirect('admincontrol/withdrawal_payment_gateways');
	}
}