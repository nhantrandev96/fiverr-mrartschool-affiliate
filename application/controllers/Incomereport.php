<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Incomereport extends MY_Controller {
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
	public function userlogins(){ return $this->session->userdata('user'); }

	public function index(){
		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }
		$data = array();

		$this->Report_model->view('incomereports/admin/admin_transaction', $data);
	}

	public function statistics(){
		if(!$this->userlogins()){ redirect('admincontrol/dashboard', 'refresh'); }
		$data = array();

		$this->Report_model->view('incomereports/user/transaction', $data , 'usercontrol');
	}

	public function get_data(){
		//parse_str($_POST['filter'], $filter);
		$filter = $this->input->post(null,true);

		if (!isset($filter['is_admin'])) { 
			$filter['user_id'] = (int)$this->userlogins()['id'];
		}
		 
		$data = $this->Income_model->get_report($filter);
		$json['data'] = array();
		foreach ($data['data'] as $key => $value) {
			$json['data'][] = array(
				$key+1,
				$value['name'] .' - ('. $value['username'] .') <img src="'. base_url('assets/vertical/assets/images/flags/'. strtolower($value['country_code']).'.png') .'" class="pull-right country-flag">',
				
				$value['total_click'],
				$value['total_click_amount'],
				$value['total_sale_count'] ,
				$value['total_sale_amount'],
				$value['total_sale_comm'],
				
				$value['external_action_click'] ."/" . $value['action_click_commission'],

				$value['wallet_accept_amount'],
				$value['total_commission'],

			);
		}

		if (isset($_GET['export'])) {
			include APPPATH . '/core/excel/Classes/PHPExcel.php';

			$objPHPExcel = PHPExcel_IOFactory::load(APPPATH . '/core/excel/statics.xlt');
			$index = 3;
			foreach ($json['data'] as $key => $d) {
			 	$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'. $index , strip_tags($d[1]))
                    ->setCellValue('B'. $index , $d[2])
                    ->setCellValue('C'. $index , $d[3])
                    ->setCellValue('D'. $index , $d[4])
                    ->setCellValue('E'. $index , $d[5])
                    ->setCellValue('F'. $index , $d[6])
                    ->setCellValue('G'. $index , $d[7])
                    ->setCellValue('H'. $index , $d[8])
                    ->setCellValue('I'. $index , $d[9]);
				$index ++;
			}

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save(APPPATH.'/core/excel/output/statistics.xlsx');

			$json['download'] = base_url('application/core/excel/output/statistics.xlsx');
		}

		echo json_encode($json);
	}

	public function user_search(){
		$data = $this->db->query("SELECT id,CONCAT(firstname,' ',lastname, ' - (', username ,')') as name  FROM users WHERE type='user' AND firstname like  '%". $this->input->get('q') ."%' ")->result_array();

		echo json_encode($data);
	}
}
