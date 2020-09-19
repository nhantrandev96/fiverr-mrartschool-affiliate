<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
ini_set('display_errors', 0);

class ReportController extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('user_model', 'user');
		$this->load->model('Product_model');
		$this->load->model('Report_model');
		___construct(1);
	}
	
 
	public function userdetails(){ return $this->session->userdata('administrator'); }
	public function userlogins(){ return $this->session->userdata('user'); }

	public function admin_transaction($page = 1){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }
		$filter['sortBy'] = isset($_GET['sortby']) ? $_GET['sortby'] : '';
		$filter['orderBy'] = isset($_GET['order']) ? $_GET['order'] : '';

		$filter['page'] = $page;
		$filter['limit'] = 10;
		$filter['control'] = 'admincontrol';

		$data['totals'] = $this->Wallet_model->getTotals([],true);
		$transaction = $this->Report_model->getAllTransaction($filter);
		$data['transaction'] = $transaction['data'];

		$this->load->library('pagination');
		$config['base_url'] = base_url('ReportController/admin_transaction');
		$config['per_page'] = $filter['limit'];
		$config['total_rows'] = $transaction['total'];
		$config['use_page_numbers'] = TRUE;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$this->Report_model->view('reports/admin/admin_transaction', $data);
	}

	public function user_transaction($page = 1){
		$userdetails = $this->userlogins();
		if(!$userdetails){ redirect('usercontrol/dashboard', 'refresh'); }

		$filter['sortBy'] = isset($_GET['sortby']) ? $_GET['sortby'] : '';
		$filter['orderBy'] = isset($_GET['order']) ? $_GET['order'] : '';
		$filter['user_id'] = $userdetails['id'];
		$filter['status_gt'] = 1;
		$data['totals'] = $this->Wallet_model->getTotals(['user_id' => $userdetails['id']],true);


		$filter['page'] = $page;
		$filter['limit'] = 10;

		$transaction = $this->Report_model->getAllTransaction($filter);
		$data['transaction'] = $transaction['data'];

		$this->load->library('pagination');
		$config['base_url'] = base_url('ReportController/user_transaction');
		$config['per_page'] = $filter['limit'];
		$config['total_rows'] = $transaction['total'];
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$this->Report_model->view('reports/user/user_transaction', $data, 'usercontrol');
	}

	public function admin_statistics(){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }
		$data['statistics'] = $this->Report_model->getStatistics();
		$this->Report_model->view('reports/admin/admin_statistics', $data);
	}

	public function user_reports(){
		$userdetails = $this->userlogins();
		if(!$userdetails){ redirect('usercontrol/dashboard', 'refresh'); }

		$referlevelSettings = $this->Product_model->getSettings('referlevel');
        $disabled_for = json_decode( (isset($referlevelSettings['disabled_for']) ? $referlevelSettings['disabled_for'] : '[]'),1);
        $refer_status = true;
        if((int)$referlevelSettings['status'] == 0){ $refer_status = false; }
        else if((int)$referlevelSettings['status'] == 2 && in_array($userdetails['id'], $disabled_for)){ $refer_status = false; }

        $data['refer_status'] = $refer_status;

		$data['statistics'] = $this->Report_model->getStatistics(['user_id' => $userdetails['id']]);



		/*Transaction*/
		$page = max(isset($_GET['per_page']) ? $_GET['per_page'] : 1,1);
		$filter = array();
		$filter['sortBy'] = isset($_GET['sortby']) ? $_GET['sortby'] : '';
		$filter['orderBy'] = isset($_GET['order']) ? $_GET['order'] : '';
		$filter['user_id'] = $userdetails['id'];
		$filter['status_gt'] = 1;
		$data['totals'] = $this->Wallet_model->getTotals(['user_id' => $userdetails['id']],true);

		$filter['page'] = $page;
		$filter['limit'] = 10;

		$transaction = $this->Report_model->getAllTransaction($filter);
		$data['transaction'] = $transaction['data'];

		$this->load->library('pagination');
		$config['base_url'] = base_url('ReportController/user_reports/');
		$config['per_page'] = $filter['limit'];
		$config['total_rows'] = $transaction['total'];
		$config['use_page_numbers'] = TRUE;
		$config['page_query_string'] = TRUE;
		$config['enable_query_strings'] = TRUE;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();


		$this->Report_model->view('reports/user/user_reports', $data,'usercontrol');

	}

	public function user_statistics(){
		$userdetails = $this->userlogins();
		if(!$userdetails){ redirect('usercontrol/dashboard', 'refresh'); }

		$referlevelSettings = $this->Product_model->getSettings('referlevel');
        $disabled_for = json_decode( (isset($referlevelSettings['disabled_for']) ? $referlevelSettings['disabled_for'] : '[]'),1);
        $refer_status = true;
        if((int)$referlevelSettings['status'] == 0){ $refer_status = false; }
        else if((int)$referlevelSettings['status'] == 2 && in_array($userdetails['id'], $disabled_for)){ $refer_status = false; }

        $data['refer_status'] = $refer_status;

		$data['statistics'] = $this->Report_model->getStatistics(['user_id' => $userdetails['id']]);
		$this->Report_model->view('reports/user/user_statistics', $data,'usercontrol');
	}
}
