<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
ini_set('display_errors', 0);


class CronJob extends MY_Controller{
	
	function __construct(){
		parent::__construct();	
		___construct(1);	
	}

	public function transaction(){
		echo date("Y-m-d H:i:s")."<br>";
		$this->load->model('Wallet_model');
		$result = $this->Wallet_model->CronTransaction();

		$cur = date("Y-m-d H:i:s");
		$this->db->query("UPDATE wallet_recursion SET status=0 WHERE status=1 AND endtime <= '{$cur}' AND endtime IS NOT NULL");			
		
		if($result){
			echo "Success";
		}else{
			echo "No Records";
		}

	}
}