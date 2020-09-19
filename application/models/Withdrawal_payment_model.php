<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Withdrawal_payment_model extends CI_Model {

	public $status_list = array(
        '0'  =>  'Received',
        '1'  =>  'Complete',
        '2'  =>  'Total not match',
        '3'  =>  'Denied',
        '4'  =>  'Expired',
        '5'  =>  'Failed',
        '7'  =>  'Processed',
        '8'  =>  'Refunded',
        '9'  =>  'Reversed',
        '10' =>  'Voided',
        '11' =>  'Canceled Reversal',
        '12'  =>  'Waiting For Payment',
        '13'  =>  'Pending',
    );

	private function getAdminSettings($code,$data = array()){
		$data['status_list'] = $this->status_list;
		$file = APPPATH."/withdrawal_payment/admin_settings/{$code}.php";
		
		if(is_file($file)){
			ob_start();
			extract($data);
			include $file;
			$output = ob_get_contents();
			ob_clean();
	    	return $output;
		}
	}

	public function getConfirm($code,$data = array()){
		$file = APPPATH."/withdrawal_payment/confirm_view/{$code}.php";
		 
		if(is_file($file)){
			ob_start();
			extract($data);
			include $file;
			$output = ob_get_contents();
			ob_clean();
	    	return $output;
		}
	}

	private function getUserSettings($code,$data = array()){
		$file = APPPATH."/withdrawal_payment/user_settings/{$code}.php";
		if(is_file($file)){
			ob_start();
			extract($data);
			include $file;
			$output = ob_get_contents();
			ob_clean();
	    	return $output;
		}
	}

	public function getEditPage($code){
		$filename = APPPATH."/withdrawal_payment/controllers/{$code}.php";
		if(is_file($filename)){
			$data['setting_data'] = $this->Product_model->getSettings('withdrawalpayment_'.$code);

			return [$this->getAdminSettings($code,$data),$data];
		}

		return false;
	}

	function getSettings($type){
        $settingdata = array();
        $this->db->where('setting_type', $type);
        $getSetting = $this->db->get_where('setting', array('setting_status' => 1))->result_array();
        foreach ($getSetting as $setting) {
            $settingdata[$setting['setting_key']] = $setting['setting_value'];
        }
        return $settingdata;
    }

	public function getDetails($code, $extra = array()){
		$filename = APPPATH."/withdrawal_payment/controllers/{$code}.php";
		if(is_file($filename)){
			require $filename;
			$obj = new $code($this);

			$pdata = array();
			$pdata['title'] = $obj->title;
			$pdata['icon'] = "application/withdrawal_payment/logo/{$code}.png";
			$pdata['website'] = $obj->website;
			$pdata['code']  = $code;

			$setting_data = $this->Product_model->getSettings('withdrawalpayment_'.$code);
			$pdata['status']  = 0;
			$pdata['is_install']  = 0;

			if (isset($extra['get_user_setting']) && $extra['get_user_setting']) {
				$pdata['user_setting'] = $this->getUserSettings($code);
			}

			if (isset($setting_data['status']) && $setting_data['status']) {
				$pdata['status']  = 1;
			}

			if (isset($setting_data['is_install']) && $setting_data['is_install']) {
				$pdata['is_install']  = 1;
			}

			return $pdata;
		}

		return false;
	}

	public function changeInstallUninstall($code){
		$path = APPPATH."/withdrawal_payment/controllers/{$code}.php";
		if(is_file($path)){
			$setting_data = $this->Product_model->getSettings('withdrawalpayment_'.$code);
			$setting_data['is_install'] = (int)$setting_data['is_install'] == 1 ? 0 : 1;

			if($setting_data['is_install'] == 0){
				$setting_data = [
					'is_install' => 0,
				];
				$this->Setting_model->clear('withdrawalpayment_'.$code);
			}

			$this->Setting_model->save('withdrawalpayment_'.$code, $setting_data);

			require $path;
			$class = new $code($this);

			$this->session->set_flashdata('success', 'Payment Method '. ((int)$setting_data['is_install'] == 1 ? 'Installed' : 'Un-Installed') .' Successfully');

			if((int)$setting_data['is_install'] == 1 && method_exists($code,'onInstall')){
				$class->onInstall();
			}
			if((int)$setting_data['is_install'] == 0 && method_exists($code,'onUnInstall')){
				$class->onUnInstall();
			}
		}
	}

	public function getPaymentMethods($filter = array()){
		$files = array();
		foreach (glob(APPPATH."/withdrawal_payment/controllers/*.php") as $file) {
		  	$files[] = $file;
		}
		$methods = array_unique($files);
		
		$payment_methods = array();
		foreach ($methods as $key => $filename) {
			$code = basename($filename, ".php");
			$pdata = $this->getDetails($code, $filter);
			if($pdata){
				$payment_methods[$code] = $pdata;
			}
		}

		return $payment_methods;
	}

	public function getRequestDetails($id){
		return $this->db->query("SELECT * FROM wallet_requests WHERE id={$id}")->row_array();
	}

	public function apiAddWithdrwalRequestHistory($req_id, $data = array()){
		$date = date("Y-m-d H:i:s");

		$this->load->model("Mail_model");
		$this->Mail_model->withdrwal_status_change($req_id,$data);

		$request_history = [
			'created_at'     => $date,
			'req_id'         => (int)$req_id,
			'status'         => (int)$data['status_id'],
			'comment'        => isset($data['comment']) ? $data['comment'] : '',
			'transaction_id' => isset($data['transaction_id']) ? $data['transaction_id'] : '',
		];

		$this->db->insert("wallet_requests_history", $request_history);
		$insert_id = $this->db->insert_id();

		$this->db->query("UPDATE wallet_requests SET status = ". (int)$data['status_id'] ." WHERE id=". (int)$req_id);


		if((int)$data['status_id'] == 1){
			$req = $this->db->query("SELECT * FROM wallet_requests WHERE id=". (int)$req_id)->row();
			if($req && $req->tran_ids){
				$this->db->query("UPDATE wallet SET status=3 WHERE id IN (". $req->tran_ids .") ");
			}
		}

		return $insert_id;
	}

	public function apiAddWithdrwalRequest($code,$ids,$setting = array())
	{
		$userdetails = $this->session->userdata('user');
		
		$json = array();

		if($ids == 'all'){
			$ids = array_column($this->db->query("SELECT id FROM wallet WHERE status=1 AND user_id=".(int)$userdetails['id']  )->result_array(), 'id');
		} else {
			$ids = (array)explode(",", $ids);
		}
		
		$transactions = $this->db->query("SELECT * FROM wallet WHERE id IN (". implode(",", $ids) .") ")->result();
		$date = date("Y-m-d H:i:s");

		$request = [
			'tran_ids'      => implode(",", $ids),
			'status'        => 0,
			'user_id'       => (int)$userdetails['id'],
			'total'         => 0,
			'prefer_method' => $code,
			'settings'      => json_encode($setting),
			'created_at'    => $date,
		];

		foreach ($transactions as $key => $value) {
			$request['total'] += (float)$value->amount;
		}

		$json['status'] = 0;
		if($request['total'] > 0){

			$this->db->query("UPDATE wallet SET  wv='V2',status=2 WHERE id IN (". implode(",", $ids) .") ");
			$this->db->insert("wallet_requests", $request);

			$insert_id = $this->db->insert_id();
			$this->db->query("INSERT INTO wallet_requests_history SET created_at='{$date}', req_id={$insert_id}, status=0, comment='Your request is received'");

			$this->load->model('Mail_model');
			$this->Mail_model->send_wallet_withdrawal_req($request['total'], $userdetails);
			$json['status'] = 1;
		} else{
			$json['error_message'] = 'Withdrwal total must be greater than zero..!';
		}

		return $json;
	}

	public function getRequests($filter = array())
	{
		$query = "SELECT wr.*,u.firstname,u.lastname,u.username FROM wallet_requests wr LEFT JOIN users u ON u.id=wr.user_id WHERE 1";

		if (isset($filter['date'])) {
			if (strpos($filter['date'], ' - ') !== false) {
				list($start_date, $end_date) = explode(" - ", $filter['date']);

				$start_date = date("Y-m-d", strtotime($start_date));
				$end_date = date("Y-m-d", strtotime($end_date));

				$query .= " AND DATE(wr.created_at) >= '{$start_date}'";
				$query .= " AND DATE(wr.created_at) <= '{$end_date}'";
			}
		}

		if (isset($filter['user_id'])) {
			$query .= ' AND wr.user_id = '. (int)$filter['user_id'];
		}

		return $this->db->query($query)->result_array();
	}
}
