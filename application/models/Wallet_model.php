<?php	
class Wallet_model extends MY_Model{
	public $request_status = array(
		'0' => 'Pending',
		'1' => 'Complete',
		'2' => 'Proccessing',
		'3' => 'Cancel',
		'4' => 'Decline',
	);
	public $status = array(
		'0' => 'ON HOLD',
		'1' => 'IN WALLET',
		'2' => 'REQUEST SENT',
		'3' => 'ACCEPT',
		'4' => 'DECLINE',
	);

	public $status_icon = array();

	function __construct(){
		parent::__construct();

		$this->status_icon = array(
			'0' => "<p class='m-0 status-text' style='color:red;'>ON HOLD</p>",
			'1' => "<p class='m-0 status-text' style='color:blue;'>IN WALLET</p>",
			'2' => "<p class='m-0 status-text' style='color:orange;'>REQUEST SENT</p>",
			'3' => "<p class='m-0 status-text' style='color:green;'>ACCEPT</p>",
			'4' => "<p class='m-0 status-text' style='color:red;'>DECLINE</p>",


		// $this->status_icon = array(
		// 	'0' => '<img src="'. base_url('assets/images/wallet-icon/on_hold.png') .'" class="wallet-icons">',
		// 	'1' => '<img src="'. base_url('assets/images/wallet-icon/in_wallet.png') .'" class="wallet-icons">',
		// 	'2' => '<img src="'. base_url('assets/images/wallet-icon/request_sent.png') .'" class="wallet-icons">',
		// 	'3' => '<img src="'. base_url('assets/images/wallet-icon/paid_done.png') .'" class="wallet-icons">',
		// 	'4' => '<img src="'. base_url('assets/images/wallet-icon/decline.png') .'" class="wallet-icons">',
		);
	}

	public function getDeleteData($transaction_id, $type = false){
		$tran = $this->getbyId($transaction_id);
		
		$data = array(
			'amount' => 0,
			'removed' => array(),
		);

		if($tran){
			$ips = array();
			$unique_ids = array();
			foreach ($tran->ip_details as $ip) {
				$ips[] = "'". $ip['ip'] ."'";
				$unique_ids[] = (int)$ip['id'];
			}

			$data['amount'] = $tran->amount;
			$data['id'] = $tran->id;
 
			switch ($tran->type) {
				case 'form_click_commission':
					$query = $this->db->query("SELECT form_action.*,users.firstname,users.lastname FROM `form_action` LEFT JOIN users ON  users.id = form_action.user_id WHERE form_id = ". (int)$tran->reference_id ." AND user_id = ". (int)$tran->user_id ." AND user_ip IN (". implode(",", $ips) .") ")->result_array();
					

					if($query){
						foreach ($query as $value) {
							$data['removed'][] = array(
								'message' => "Form Click Done By ". $value['firstname'] ." " . $value['lastname'] ." <br><b>IP:</b> ". $value['user_ip'] ."  - <b>Date:</b> ". $value['created_at'],
								'query' => "DELETE FROM `form_action` WHERE action_id = ". $value['action_id'],
							);

						}
					}
					break;
				case 'click_commission':
					$query = $this->db->query("SELECT product_action.*,users.firstname,users.lastname FROM `product_action` LEFT JOIN users ON  users.id = product_action.user_id WHERE product_id = ". (int)$tran->reference_id ." AND user_id = ". (int)$tran->user_id ." AND user_ip IN (". implode(",", $ips) .") ")->result_array();

					if($query){
						foreach ($query as $value) {
							$data['removed'][] = array(
								'message' => "Product Click Done By ". $value['firstname'] ." " . $value['lastname'] ." <br><b>IP:</b> ". $value['user_ip'] ."  - <b>Date:</b> ". $value['created_at'],
								'query' => "DELETE FROM `product_action` WHERE action_id = ". $value['action_id'],
							);

						}
					}
					break;
				case 'external_click_commission':
					$query = $this->db->query("SELECT integration_clicks_action.*,users.firstname,users.lastname FROM `integration_clicks_action` LEFT JOIN users ON  users.id = integration_clicks_action.user_id WHERE 
						integration_clicks_action.id IN(". implode(",", $unique_ids) .")

					")->result_array();

					if($query){
						foreach ($query as $value) {
							$data['removed'][] = array(
								'message' => "Product Click Done By ". $value['firstname'] ." " . $value['lastname'] ." <br><b>IP:</b> ". $value['ip'] ."  - <b>Date:</b> ". $value['created_at'],
								'query' => "DELETE FROM `integration_clicks_action` WHERE id = ". $value['id'],
							);

						}
					}

					break;
				case 'external_click_comm_admin':
					$query = $this->db->query("SELECT integration_admin_clicks_action.*,users.firstname,users.lastname FROM `integration_admin_clicks_action` LEFT JOIN users ON  users.id = integration_admin_clicks_action.user_id WHERE 
						integration_admin_clicks_action.id IN(". implode(",", $unique_ids) .")

					")->result_array();
					
					if($query){
						foreach ($query as $value) {
							$data['removed'][] = array(
								'message' => "Admin Product Click Done By ". $value['firstname'] ." " . $value['lastname'] ." <br><b>IP:</b> ". $value['ip'] ."  - <b>Date:</b> ". $value['created_at'],
								'query' => "DELETE FROM `integration_admin_clicks_action` WHERE id = ". $value['id'],
							);

						}
					}

					break;
				case 'affiliate_click_commission':
					$query = $this->db->query("SELECT affiliate_action.*,users.firstname,users.lastname FROM `affiliate_action` LEFT JOIN users ON  users.id = affiliate_action.user_id WHERE affiliate_id = ". (int)$tran->reference_id ." AND user_id = ". (int)$tran->user_id ." AND user_ip IN (". implode(",", $ips) .") ")->result_array();

					if($query){
						foreach ($query as $value) {
							$data['removed'][] = array(
								'message' => "Affiliate Click Done By ". $value['firstname'] ." " . $value['lastname'] ." <br><b>IP:</b> ". $value['user_ip'] ."  - <b>Date:</b> ". $value['created_at'],
								'query' => "DELETE FROM `affiliate_action` WHERE id = ". $value['id'],
							);

						}
					}
					break;
				case 'sale_commission':
					$data['order_type'] = $tran->comm_from;
					if($tran->comm_from == 'ex'){
						$data['order_amount'] = $this->db->query("SELECT total FROM integration_orders WHERE id= ".(int)$tran->reference_id_2)->row()->total; 
						$data['removed'][] = array(
							'message' => "External Site Sale Done By ". $tran->name ." <br><b>IP:</b> ". str_replace("'", "", implode(",", $ips)) ."  - <b>Date:</b> ". $tran->created_at,
							'query' => "DELETE FROM  `integration_orders` WHERE id = ". (int)$tran->reference_id_2,
						);
					} else{
						$data['removed'][] = array(
							'message' => "Sale Done By ". $tran->name ."<br> <b>IP:</b> ". str_replace("'", "", implode(", ", $ips)) ." <br><br><b>Date:</b> ". $tran->created_at,
							'query' => "UPDATE `order_products` SET commission=0 WHERE order_id = ". (int)$tran->reference_id ." AND refer_id = ". (int)$tran->user_id,
						);
					}
					break;
				default: break;
			}
		}
	
		return $data;		
	}
	
	public function addTransaction($data){

		$this->load->model('Product_model');
		$ipInformatiom = $this->Product_model->ip_info();

		if(!isset($data['ip_details'])){
			$ips[] = array(
				'ip' => @$ipInformatiom['ip'],
				'country_code' => @$ipInformatiom['country_code'],
			);
			$data['ip_details'] = json_encode($ips);
		}

		$data['created_at']  = date("Y-m-d H:i:s");

        $this->db->insert('wallet',$data);
        return $this->db->insert_id();
	}
	public function addRequest($data){
		$data['created_at']  = date("Y-m-d H:i:s");
        $this->db->insert('wallet_request',$data);
        return $this->db->insert_id();
	}
	public function getRequest($filter){
		if (isset($filter['user_id'])) {
			$this->db->where('wallet_request.user_id', (int)$filter['user_id']);
		}
		if (isset($filter['id'])) {
			$this->db->where('wallet_request.id', (int)$filter['id']);
		}
		$this->db->select(array(
			'wallet_request.*',
			'users.firstname',
			'users.lastname',
		));
		$this->db->from('wallet_request');
		$this->db->join('users', 'users.id = wallet_request.user_id','left');
        $this->db->order_by('wallet_request.created_at','DESC');
        if (isset($filter['id'])) {
			return $this->db->get()->row_array();
        }
        else{
			return $this->db->get()->result_array();
        }
	}
	public function balance($filter = array()){
		$where = ' 1 ';
		if (isset($filter['user_id'])) {
			$where .= '  AND wallet.user_id = '. (int)$filter['user_id'];
		}
		return (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE '. $where)->row_array()['total'];
	}
	public function getbyId($id){
		$this->db->select(array(
			'wallet.*',
			'CONCAT(users.firstname," ",users.lastname) as name',
			'users.email as user_email'
		));
		$this->db->from('wallet');
		$this->db->join('users', 'users.id = wallet.user_id','left');
		$this->db->where('wallet.id', (int)$id);
		$tran =  $this->db->get()->row();

		$tran->ip_details = json_decode($tran->ip_details,1);

		return $tran;
	}
	public function getallUnpaid($id){
		$this->db->select(array(
			'wallet.*',
			'CONCAT(users.firstname," ",users.lastname) as name',
			'users.email as user_email'
		));
		$this->db->from('wallet');
		$this->db->join('users', 'users.id = wallet.user_id','left');
		$this->db->where('wallet.user_id', (int)$id);
		$this->db->where('wallet.status', 1);
		$tran =  $this->db->get()->result_array();

		return $tran;
	}
	public function changeStatus($id, $status){
		return $this->db->update('wallet', array('status' => $status), array('id' => (int)$id));
	}
	public function getTransaction($filter = array()){
		$select = array(
			'wallet.*',
			'users.username',
			'users.firstname',
			'users.lastname',
			'wallet_recursion.id as wallet_recursion_id',
			'wallet_recursion.status as wallet_recursion_status',
			'wallet_recursion.type as wallet_recursion_type',
			'wallet_recursion.custom_time as wallet_recursion_custom_time',
			'wallet_recursion.next_transaction as wallet_recursion_next_transaction',
			'wallet_recursion.endtime as wallet_recursion_endtime',
			"(SELECT count(id) as total FROM `wallet` w WHERE wallet.id = w.parent_id) as total_recurring ",
			"(SELECT payment_method FROM `order` WHERE wallet.reference_id = `order`.id AND wallet.type IN('sale_commission','vendor_sale_commission') AND wallet.dis_type is NULL ) as payment_method ",
			"(SELECT total FROM `integration_orders` WHERE comm_from='ex' AND wallet.reference_id_2 = `integration_orders`.id AND wallet.type IN ('sale_commission','admin_sale_commission')) as integration_orders_total ",
			"(SELECT SUM(amount) FROM `wallet` ww WHERE ww.parent_id=wallet.id) as total_recurring_amount",
			"(SELECT total FROM `order` WHERE comm_from != 'ex' AND id = wallet.reference_id AND wallet.type IN('sale_commission','vendor_sale_commission') ) as local_orders_total "
		);


		$where = '';
		if (isset($filter['user_id'])) {
			$where .= ' AND wallet.user_id = '. (int)$filter['user_id'];
		}
		if (isset($filter['old_with'])) {
			$where .= ' AND (wallet.wv != "V2" OR wallet.wv IS NULL)';
		}
		if (isset($filter['id_ne'])) {
			$where .= ' AND wallet.id != '. (int)$filter['id_ne'];
		}
		if (isset($filter['group_id'])) {
			$where .= ' AND wallet.group_id = '. $filter['group_id'];
		}
		if (isset($filter['paid_status'])) {
			if($filter['paid_status'] == 'unpaid'){
				$where .= ' AND wallet.status IN (0,1)';
			} else if($filter['paid_status'] == 'paid'){
				$where .= ' AND wallet.status = 3 ';
			}
		}
		if (isset($filter['id'])) {
			$where .= ' AND wallet.id = '. (int)$filter['id'];
		}
		if (isset($filter['id_in'])) {
			$where .= ' AND wallet.id IN ('. $filter['id_in'] .")";
		}
		if (isset($filter['parent_id'])) {
			$where .= ' AND wallet.parent_id = '. (int)$filter['parent_id'];
		}
		if (isset($filter['recurring']) && $filter['recurring'] == '1') {
			$where .= ' AND wallet_recursion.id > 0 ';
		} else if (isset($filter['recurring']) && $filter['recurring'] == '0') {
			$where .= ' AND wallet_recursion.id  = "" ';
		}

		if (isset($filter['status_gt'])) {
			$where .= ' AND wallet.status >= '. (int)$filter['status_gt'];
		}
		if (isset($filter['status'])) {
			$where .= ' AND wallet.status= ' . (int)$filter['status'];
		}
		if (isset($filter['type'])) {
			$where .= ' AND wallet.type = ' . "'".$filter['type']."'";
		}
		if (isset($filter['type_in'])) {
			$where .= ' AND wallet.type IN (' . $filter['type_in']. ')';
		}
		if (isset($filter['is_action'])) {
			$where .= ' AND wallet.is_action = '. $filter['is_action'];
		}

		if (isset($filter['click_log'])) {
			$where .= " AND  (wallet.type IN ('click_commission','form_click_commission','affiliate_click_commission') OR (wallet.type = 'external_click_commission' AND is_action=0 AND comm_from = 'ex')) ";
		}

		if (isset($filter['date'])) {
			if (strpos($filter['date'], ' - ') !== false) {
				list($start_date, $end_date) = explode(" - ", $filter['date']);

				$start_date = date("Y-m-d", strtotime($start_date));
				$end_date = date("Y-m-d", strtotime($end_date));

				$where .= " AND DATE(wallet.created_at) >= '{$start_date}'";
				$where .= " AND DATE(wallet.created_at) <= '{$end_date}'";
			}
		}

		if (isset($filter['types'])) {
			switch ($filter['types']) {
				case 'actions':
					$where .= ' AND wallet.is_action=1 AND wallet.type="external_click_commission" ';
					break;

				case 'sale':
					$where .= ' AND wallet.type IN("sale_commission","admin_sale_commission") ';
					break;
				case 'external_integration':
					//$where .= " AND  ( (wallet.type IN ('external_click_commission')) OR (wallet.type = 'external_click_commission' AND is_action=0 AND comm_from = 'ex')) ";
					$where .= " AND  wallet.comm_from = 'ex' ";
					break;
				case 'clicks':
					$where .= " AND (wallet.type IN ('click_commission','form_click_commission','affiliate_click_commission') OR (wallet.type = 'external_click_commission' AND is_action=0 AND comm_from = 'ex')) ";
					break;
				
				default:
					# code...
					break;
			}
		}
		
		$query = " SELECT ". implode(",", $select);
		$query .= " FROM wallet LEFT JOIN users ON users.id = wallet.user_id";
		$query .= " LEFT JOIN  wallet_recursion ON wallet_recursion.transaction_id = wallet.id";
		$query .= " WHERE 1 {$where} ";

		if (isset($filter['sortBy']) && $filter['sortBy'] && isset($filter['orderBy']) && $filter['orderBy']) {
			$query .= " ORDER BY ".$filter['sortBy'] ." ".$filter['orderBy'];
		} else{
			$query .= " ORDER BY wallet.id DESC";
		}

		if (isset($filter['per_page']) && isset($filter['page_num'])) {
			$query .= " LIMIT ".$filter['page_num'].",".$filter['per_page'];
		}

		$data = $this->db->query($query)->result_array();
		
		return $data;
	}

	public function getIntegrationTransaction($filter = array()){
		$select = array(
			'wallet.*',
			'users.username',
			'users.firstname',
			'users.lastname',
			'wallet_recursion.id as wallet_recursion_id',
			'wallet_recursion.status as wallet_recursion_status',
			'wallet_recursion.type as wallet_recursion_type',
			'wallet_recursion.custom_time as wallet_recursion_custom_time',
			'wallet_recursion.next_transaction as wallet_recursion_next_transaction',
			'wallet_recursion.endtime as wallet_recursion_endtime',
			"(SELECT count(id) as total FROM `wallet` w WHERE wallet.id = w.parent_id) as total_recurring ",
			"(SELECT total FROM `integration_orders` WHERE comm_from='ex' AND wallet.reference_id_2 = `integration_orders`.id AND wallet.type IN ('sale_commission','admin_sale_commission') ) as integration_orders_total ",
			"(SELECT SUM(amount) FROM `wallet` ww WHERE ww.parent_id=wallet.id) as total_recurring_amount"
		);

		$where = '';
		$user_id= (int)$filter['user_id'];
		if (isset($filter['user_id'])) {
			//$where .= ' AND integration_orders.vendor_id = '. (int)$filter['user_id'];
		}
		if (isset($filter['parent_id'])) {
			$where .= ' AND wallet.parent_id = '. (int)$filter['parent_id'];
		}
		if (isset($filter['recurring']) && $filter['recurring'] == '1') {
			$where .= ' AND wallet_recursion.id > 0 ';
		} else if (isset($filter['recurring']) && $filter['recurring'] == '0') {
			$where .= ' AND wallet_recursion.id  = "" ';
		}

		if (isset($filter['status_gt'])) {
			$where .= ' AND wallet.status >= '. (int)$filter['status_gt'];
		}
		if (isset($filter['status'])) {
			$where .= ' AND wallet.status= ' . (int)$filter['status'];
		}
		if (isset($filter['type'])) {
			$where .= ' AND wallet.type = ' . "'".$filter['type']."'";
		}
		if (isset($filter['type_in'])) {
			$where .= ' AND wallet.type IN (' . $filter['type_in']. ')';
		}
		if (isset($filter['is_action'])) {
			$where .= ' AND wallet.is_action = '. $filter['is_action'];
		}

		if (isset($filter['click_log'])) {
			$where .= " AND  (wallet.type IN ('click_commission','form_click_commission','affiliate_click_commission') OR (wallet.type = 'external_click_commission' AND is_action=0 AND comm_from = 'ex')) ";
		}

		if (isset($filter['date'])) {
			if (strpos($filter['date'], ' - ') !== false) {
				list($start_date, $end_date) = explode(" - ", $filter['date']);

				$start_date = date("Y-m-d", strtotime($start_date));
				$end_date = date("Y-m-d", strtotime($end_date));

				$where .= " AND DATE(wallet.created_at) >= '{$start_date}'";
				$where .= " AND DATE(wallet.created_at) <= '{$end_date}'";
			}
		}

		$query = " SELECT ". implode(",", $select);
		$query .= " FROM wallet LEFT JOIN users ON users.id = wallet.user_id";
		$query .= " LEFT JOIN  wallet_recursion ON wallet_recursion.transaction_id = wallet.id";
		$query .= " WHERE (wallet.id IN (SELECT affiliate_tran FROM integration_orders WHERE vendor_id = {$user_id}) OR wallet.id IN (SELECT admin_tran FROM integration_orders WHERE vendor_id = {$user_id}) ){$where} ";


		if (isset($filter['sortBy']) && $filter['sortBy'] && isset($filter['orderBy']) && $filter['orderBy']) {
			$query .= " ORDER BY ".$filter['sortBy'] ." ".$filter['orderBy'];
		} else{
			$query .= " ORDER BY wallet.id DESC";
		}

		if (isset($filter['per_page']) && isset($filter['page_num'])) {
			$query .= " LIMIT ".$filter['page_num'].",".$filter['per_page'];
		}

		$data = $this->db->query($query)->result_array();
		
		return $data;
	}
	public function getTotals($filter = array(),$extraTotals = false, $calling_for = 'admin'){
		$where = ' 1 ';
		$where1 = ' 1 ';		 
		$where_vendor = ' 1 ';		 
		
		if (isset($filter['date'])) {
			if (strpos($filter['date'], ' - ') !== false) {

				list($start_date, $end_date) = explode(" - ", $filter['date']);

				$start_date = date("Y-m-d", strtotime($start_date));
				$end_date = date("Y-m-d", strtotime($end_date));

				$where .= "  AND DATE(created_at) >= '{$start_date}' AND DATE(created_at) <= '{$end_date}' ";
				$where1 .= "  AND DATE(o.created_at) >= '{$start_date}' AND DATE(o.created_at) <= '{$end_date}' ";
				//$where1 .= '  AND op.refer_id = '. (int)$filter['user_id'];		
			}
		}

		if (isset($filter['total_commision_filter_month']) && (int)$filter['total_commision_filter_month'] > 0) {
			$where .= "  AND MONTH(created_at) = '". (int)$filter['total_commision_filter_month'] ."' ";
			$where1 .= "  AND MONTH(o.created_at) = '". (int)$filter['total_commision_filter_month'] ."' ";
		}

		if (isset($filter['total_commision_filter_year']) && (int)$filter['total_commision_filter_year'] > 0) {
			$where .= "  AND YEAR(created_at) = '". (int)$filter['total_commision_filter_year'] ."' ";
			$where1 .= "  AND YEAR(o.created_at) = '". (int)$filter['total_commision_filter_year'] ."' ";
		}

		if (isset($filter['integration_data_month']) && (int)$filter['integration_data_month'] > 0) {
			$where .= "  AND MONTH(created_at) = '". (int)$filter['integration_data_month'] ."' ";
			$where1 .= "  AND MONTH(o.created_at) = '". (int)$filter['integration_data_month'] ."' ";
		}

		if (isset($filter['integration_data_year']) && (int)$filter['integration_data_year'] > 0) {
			$where .= "  AND YEAR(created_at) = '". (int)$filter['integration_data_year'] ."' ";
			$where1 .= "  AND YEAR(o.created_at) = '". (int)$filter['integration_data_year'] ."' ";
		}

		if (isset($filter['user_id'])) {
			$where .= '  AND user_id = '. (int)$filter['user_id'];
			$where_vendor = $where1;
			$where1 .= '  AND op.refer_id = '. (int)$filter['user_id'];
			$where_vendor .= '  AND op.vendor_id = '. (int)$filter['user_id'];
		}

		$data['unpaid_commition'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE status IN (1,2) AND '. $where)->row_array()['total'];
		//$data['total_balance'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE status=1 AND '. $where)->row_array()['total'];
		$data['total_sale_commi'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE status > 0 AND  type IN("sale_commission","vendor_sale_commission") AND '. $where )->row_array()['total'];
		
		$data['total_in_request'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE status=2 AND '. $where )->row_array()['total'];
		$data['total_click_commi'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE type = "click_commission" AND status > 0 AND '. $where )->row_array()['total'];
		$data['total_form_click_commi'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE type = "form_click_commission" AND status > 0 AND '. $where )->row_array()['total'];
		$data['total_store_m_commission'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE type = "store_m_commission" AND '. $where )->row_array()['total'];
		$data['total_affiliate_click_commission'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE type = "affiliate_click_commission" AND status > 0 AND '. $where )->row_array()['total'];

		$data['total_no_click'] = (int)$this->db->query('SELECT COUNT(action_id) as total FROM product_action WHERE  '.$where)->row_array()['total'];
		$data['total_no_form_click'] = (int)$this->db->query('SELECT COUNT(action_id) as total FROM form_action WHERE '.$where)->row_array()['total'];
		$data['aff_total_no_click'] = (int)$this->db->query('SELECT COUNT(id) as total FROM affiliate_action WHERE '.$where)->row_array()['total'];
		
		$data['admin_click_earning'] = (float)$this->db->query('SELECT SUM(amount) as total FROM wallet WHERE reference_id_2 = "vendor_click_commission_for_admin" ')->row_array()['total'];
		
		
		 
		$data['all_clicks_comm'] = $data['total_click_commi'] + $data['total_form_click_commi'] + $data['total_affiliate_click_commission'] ;
		$data['all_sale_comm'] = $data['total_sale_commi'];

		if($extraTotals){
			$data['affiliates_program'] = (float)$this->db->query('SELECT COUNT(*) as total FROM affiliateads')->row_array()['total'];
			$data['total_sale_count'] = (float)$this->db->query('SELECT COUNT(op.order_id) as total FROM order_products op LEFT JOIN `order` o on (o.id = op.order_id) WHERE o.status > 0  AND '.$where1 ." GROUP BY o.id ")->row_array()['total'];

			$data['total_sale'] = (float)$this->db->query('SELECT SUM(op.total) as total FROM `order_products` op LEFT JOIN `order` as o ON o.id = op.order_id WHERE o.status = 1 AND '. $where1)->row_array()['total'];
			$data['total_vendor_sale'] = (float)$this->db->query('SELECT SUM(op.total) as total FROM `order_products` op LEFT JOIN `order` as o ON o.id = op.order_id WHERE o.status = 1 AND '. $where_vendor)->row_array()['total'];
			//$data['total_sale'] = (int)$this->db->query('SELECT SUM(total) as total FROM `order` WHERE '. $where .' AND status  = 1')->row_array()['total'];

			if($calling_for == 'admin'){
				$data['total_sale_balance'] = (float)$this->db->query('SELECT SUM(op.total) as total FROM `order_products` op LEFT JOIN `order` as o ON o.id = op.order_id WHERE o.status = 1 AND vendor_id=0 AND '. $where1)->row_array()['total'];
				$data['total_sale_balance'] += (float)$this->db->query('SELECT SUM(op.admin_commission) as total FROM `order_products` op LEFT JOIN `order` as o ON o.id = op.order_id WHERE o.status = 1 AND vendor_id > 0 AND '. $where1)->row_array()['total'];
			} else {
				$data['total_sale_balance'] = (float)$this->db->query('SELECT SUM(op.total) as total FROM `order_products` op LEFT JOIN `order` as o ON o.id = op.order_id WHERE o.status = 1 AND '. $where1)->row_array()['total'];
			}

			if($calling_for == 'admin'){
				$data['total_sale_week'] = (float)$this->db->query('SELECT SUM(op.total) as total FROM `order` o LEFT JOIN order_products op ON op.order_id = o.id WHERE '. $where1 .' AND vendor_id = 0 AND YEARWEEK(o.`created_at`, 1) = YEARWEEK(CURDATE(), 1) AND o.status  = 1')->row_array()['total'];
				$data['total_sale_week'] += (float)$this->db->query('SELECT SUM(op.admin_commission) as total FROM `order` o LEFT JOIN order_products op ON op.order_id = o.id WHERE '. $where1 .' AND vendor_id > 0 AND YEARWEEK(o.`created_at`, 1) = YEARWEEK(CURDATE(), 1) AND o.status  = 1')->row_array()['total'];
				$data['total_sale_month'] = (float)$this->db->query('SELECT SUM(op.total) as total FROM `order` o LEFT JOIN order_products op ON op.order_id = o.id WHERE '. $where1 .' AND vendor_id = 0 AND MONTH(o.`created_at`) = MONTH(NOW())  AND o.status  = 1')->row_array()['total'];
				$data['total_sale_month'] += (float)$this->db->query('SELECT SUM(op.admin_commission) as total FROM `order` o LEFT JOIN order_products op ON op.order_id = o.id WHERE '. $where1 .' AND vendor_id > 0 AND MONTH(o.`created_at`) = MONTH(NOW())  AND o.status  = 1')->row_array()['total'];
				$data['total_sale_year'] = (float)$this->db->query('SELECT SUM(op.total) as total FROM `order` o LEFT JOIN order_products op ON op.order_id = o.id WHERE '. $where1 .' AND vendor_id = 0 AND YEAR(o.`created_at`) = '.date("Y").' AND o.status  = 1')->row_array()['total'];
				$data['total_sale_year'] += (float)$this->db->query('SELECT SUM(op.admin_commission) as total FROM `order` o LEFT JOIN order_products op ON op.order_id = o.id WHERE '. $where1 .' AND vendor_id > 0 AND YEAR(o.`created_at`) = '.date("Y").' AND o.status  = 1')->row_array()['total'];


				$data['admin_click_earning_week'] = (float)$this->db->query('SELECT SUM(amount) as total FROM wallet WHERE reference_id_2 = "vendor_click_commission_for_admin" AND YEARWEEK(`created_at`, 1) = YEARWEEK(CURDATE(), 1) ')->row_array()['total'];
				$data['admin_click_earning_month'] = (float)$this->db->query('SELECT SUM(amount) as total FROM wallet WHERE reference_id_2 = "vendor_click_commission_for_admin" AND MONTH(`created_at`) = MONTH(NOW()) ')->row_array()['total'];
				$data['admin_click_earning_year'] = (float)$this->db->query('SELECT SUM(amount) as total FROM wallet WHERE reference_id_2 = "vendor_click_commission_for_admin" AND YEAR(`created_at`) = '.date("Y").' ')->row_array()['total'];


				$data['admin_total_no_click'] = (int)$this->db->query('SELECT COUNT(action_id) as total FROM product_action_admin ')->row_array()['total'];

			} else {
				$data['total_sale_week'] = (float)$this->db->query('SELECT SUM(op.total) as total FROM `order` o LEFT JOIN order_products op ON op.order_id = o.id WHERE vendor_id = 0 AND '. $where1 .' AND YEARWEEK(o.`created_at`, 1) = YEARWEEK(CURDATE(), 1) AND o.status  = 1')->row_array()['total'];
				$data['total_sale_week'] += (float)$this->db->query('SELECT SUM(op.commission) as total FROM `order` o LEFT JOIN order_products op ON op.order_id = o.id WHERE vendor_id > 0 AND '. $where1 .' AND YEARWEEK(o.`created_at`, 1) = YEARWEEK(CURDATE(), 1) AND o.status  = 1')->row_array()['total'];
				$data['total_sale_week'] += (float)$this->db->query('SELECT SUM(op.vendor_commission) as total FROM `order` o LEFT JOIN order_products op ON op.order_id = o.id WHERE '. $where_vendor .' AND YEARWEEK(o.`created_at`, 1) = YEARWEEK(CURDATE(), 1) AND o.status  = 1')->row_array()['total'];

				$data['total_sale_month'] = (float)$this->db->query('SELECT SUM(op.total) as total FROM `order` o LEFT JOIN order_products op ON op.order_id = o.id WHERE vendor_id = 0 AND '. $where1 .' AND MONTH(o.`created_at`) = MONTH(NOW())  AND o.status  = 1')->row_array()['total'];
				$data['total_sale_month'] += (float)$this->db->query('SELECT SUM(op.commission) as total FROM `order` o LEFT JOIN order_products op ON op.order_id = o.id WHERE vendor_id > 0 AND '. $where1 .' AND MONTH(o.`created_at`) = MONTH(NOW())  AND o.status  = 1')->row_array()['total'];
				$data['total_sale_month'] += (float)$this->db->query('SELECT SUM(op.vendor_commission) as total FROM `order` o LEFT JOIN order_products op ON op.order_id = o.id WHERE '. $where_vendor .' AND MONTH(o.`created_at`) = MONTH(NOW())  AND o.status  = 1')->row_array()['total'];

				$data['total_sale_year'] = (float)$this->db->query('SELECT SUM(op.total) as total FROM `order` o LEFT JOIN order_products op ON op.order_id = o.id WHERE vendor_id = 0 AND '. $where1 .' AND YEAR(o.`created_at`) = '.date("Y").' AND o.status  = 1')->row_array()['total'];
				$data['total_sale_year'] += (float)$this->db->query('SELECT SUM(op.commission) as total FROM `order` o LEFT JOIN order_products op ON op.order_id = o.id WHERE vendor_id > 0 AND '. $where1 .' AND YEAR(o.`created_at`) = '.date("Y").' AND o.status  = 1')->row_array()['total'];
				$data['total_sale_year'] += (float)$this->db->query('SELECT SUM(op.vendor_commission) as total FROM `order` o LEFT JOIN order_products op ON op.order_id = o.id WHERE '. $where_vendor .' AND YEAR(o.`created_at`) = '.date("Y").' AND o.status  = 1')->row_array()['total'];

				$data['admin_total_no_click'] = (int)$this->db->query('SELECT COUNT(action_id) as total FROM product_action_admin WHERE product_id IN (SELECT product_id FROM product_affiliate WHERE user_id = '. (int)$filter['user_id'] .' ) ')->row_array()['total'];

				$data['total_no_click'] += (int)$this->db->query('SELECT COUNT(pa.action_id) as total FROM product_action pa LEFT JOIN product_affiliate paff ON (paff.product_id = pa.product_id) WHERE paff.user_id=   '. (int)$filter['user_id'])->row_array()['total'];

			}

			$data['all_clicks'] = $data['total_no_click'] + $data['total_no_form_click'] + $data['aff_total_no_click'];// $data['admin_total_no_click'];
			$data['vendor_order_count'] += (float)$this->db->query('SELECT COUNT(op.id) as total FROM `order` o LEFT JOIN order_products op ON op.order_id = o.id WHERE '. $where_vendor .' AND op.vendor_id > 0 AND o.status > 0')->row_array()['total'];
			$data['vendor_order_count'] += (float)$this->db->query('SELECT COUNT(id) as total FROM `integration_orders`  WHERE '. $where .'  AND status > 0')->row_array()['total'];

			$data['total_paid'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE status=3 AND '. $where)->row_array()['total'];
			
			//$data['unpaid_commition'] = ($data['total_sale_commi']  +$data['total_click_commi']);
			$data['total_paid_commition'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE status=3 AND '. $where )->row_array()['total'];
			$data['paid_commition'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE status=3 AND type IN("click_commission","sale_commission") AND '. $where )->row_array()['total'];
			$data['requested_commition'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE status=2 AND type IN("click_commission","sale_commission") AND '. $where )->row_array()['total'];
			$data['aff_paid_commition'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE status=3 AND type IN("affiliate_click_commission") AND '. $where )->row_array()['total'];
			$data['aff_unpaid_commition'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE status=1 AND type IN("affiliate_click_commission") AND '. $where )->row_array()['total'];
			$data['aff_requested_commition'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE status=2 AND type IN("affiliate_click_commission") AND '. $where )->row_array()['total'];

			$data['form_paid_commition'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE status=3 AND type IN("form_click_commission") AND '. $where )->row_array()['total'];
			$data['form_unpaid_commition'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE status=1 AND type IN("form_click_commission") AND '. $where )->row_array()['total'];
			$data['form_requested_commition'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE status=2 AND type IN("form_click_commission") AND '. $where )->row_array()['total'];

			$data['total_transaction'] = (float)$this->db->query('SELECT count(amount) as total FROM wallet WHERE 1 AND '. $where )->row_array()['total'];

			$data['wallet_on_hold_amount'] = $data['wallet_on_hold_count'] = $data['wallet_request_sent_amount'] = $data['wallet_request_sent_count'] = $data['wallet_accept_amount'] = $data['wallet_accept_count'] = $data['wallet_cancel_amount'] = $data['wallet_cancel_count'] = 0;

			$query = $this->db->query('SELECT sum(amount) as amount,count(`status`) as counts,`status` FROM `wallet` WHERE '. $where .' GROUP BY `status`')->result_array();
			foreach ($query as $key => $value) {
				switch ($value['status']) {
					case '0':
						$data['wallet_on_hold_amount'] = (float)$value['amount'];
						$data['wallet_on_hold_count'] = (float)$value['counts'];
						break;
					case '1':
						$data['wallet_unpaid_amount'] = (float)$value['amount'];
						$data['wallet_unpaid_count'] = (float)$value['counts'];
						break;
					case '2':
						$data['wallet_request_sent_amount'] = (float)$value['amount'];
						$data['wallet_request_sent_count'] = (float)$value['counts'];
						break;
					case '3':
						$data['wallet_accept_amount'] = (float)$value['amount'];
						$data['wallet_accept_count'] = (float)$value['counts'];
						break;
					case '4':
						$data['wallet_cancel_amount'] = (float)$value['amount'];
						$data['wallet_cancel_count'] = (float)$value['counts'];
						break;
					default: break;
				}
			}

			$query = $this->db->query('SELECT sum(amount) as amount,count(`status`) as counts,`status` FROM `wallet` WHERE type IN ("vendor_sale_commission") AND  '. $where .' GROUP BY `status`')->result_array();
			foreach ($query as $key => $value) {
				switch ($value['status']) {
					case '0':
						$data['vendor_wallet_on_hold_amount'] = (float)$value['amount'];
						$data['vendor_wallet_on_hold_count'] = (float)$value['counts'];
						break;
					case '1':
						$data['vendor_wallet_unpaid_amount'] = (float)$value['amount'];
						$data['vendor_wallet_unpaid_count'] = (float)$value['counts'];
						break;
					case '2':
						$data['vendor_wallet_request_sent_amount'] = (float)$value['amount'];
						$data['vendor_wallet_request_sent_count'] = (float)$value['counts'];
						break;
					case '3':
						$data['vendor_wallet_accept_amount'] = (float)$value['amount'];
						$data['vendor_wallet_accept_count'] = (float)$value['counts'];
						break;
					case '4':
						$data['vendor_wallet_cancel_amount'] = (float)$value['amount'];
						$data['vendor_wallet_cancel_count'] = (float)$value['counts'];
						break;
					default: break;
				}
			}

			
			//status > 0 AND
			$integration_balance_week = $this->db->query("SELECT sum(total) as total FROM integration_orders WHERE ". $where ." AND (vendor_id=0 OR vendor_id is NULL) AND YEARWEEK(`created_at`, 1) = YEARWEEK(CURDATE(), 1)")->row_array()['total'];
			$integration_balance_week += $this->db->query("SELECT sum(amount) as total FROM wallet WHERE type='admin_sale_commission' AND comm_from='ex' AND user_id=1 AND YEARWEEK(`created_at`, 1) = YEARWEEK(CURDATE(), 1)")->row_array()['total'];

				
			$integration_balance_month = $this->db->query("SELECT sum(total) as total FROM integration_orders WHERE ". $where ." AND (vendor_id=0 OR vendor_id is NULL) AND  MONTH(`created_at`) = MONTH(NOW())")->row_array()['total'];
			$integration_balance_month += $this->db->query("SELECT sum(amount) as total FROM wallet WHERE type='admin_sale_commission' AND comm_from='ex' AND user_id=1 AND MONTH(`created_at`) = MONTH(NOW())")->row_array()['total'];

			//status > 0 AND
				
			$integration_balance_year = $this->db->query('SELECT sum(total) as total FROM integration_orders WHERE '. $where .' AND (vendor_id=0 OR vendor_id is NULL) AND YEAR(created_at) = '.date("Y"))->row_array()['total'];
			$integration_balance_year += $this->db->query("SELECT sum(amount) as total FROM wallet WHERE type='admin_sale_commission' AND comm_from='ex' AND user_id=1 AND YEAR(created_at) = " .date("Y"))->row_array()['total'];
			// status > 0 AND
			
			
			$data['integration']['hold_action_count'] = 0;
			$data['integration']['hold_orders'] = 0;
			
			/*$integration_balance = $this->db->query('SELECT base_url,sum(total) as total FROM integration_orders WHERE  '. $where ." AND (vendor_id=0 OR vendor_id is NULL)  GROUP BY base_url")->result();
			foreach ($integration_balance as $vv) {
				$data['integration']['balance'] += $vv->total;
				$data['integration']['all'][$vv->base_url]['balance'] += $vv->total;
			}*/

			$integration_balance = $this->db->query('SELECT vendor_id,base_url,total,(SELECT amount FROM wallet WHERE integration_orders.admin_tran = wallet.id) as admin_comm FROM integration_orders WHERE  '. $where ."  ")->result();

			foreach ($integration_balance as $vv) {
				if((int)$vv->vendor_id == 0){
					$data['integration']['balance'] += $vv->total;
					$data['integration']['all'][$vv->base_url]['balance'] += $vv->total;
				} else{
					$data['integration']['balance'] += $vv->admin_comm;
					$data['integration']['all'][$vv->base_url]['balance'] += $vv->admin_comm;
				}

			}
			
			$integration_balance = $this->db->query('SELECT domain_name,sum(amount) as total FROM wallet WHERE comm_from = "ex" AND status = 3 AND '. $where ." GROUP BY domain_name")->result();
			foreach ($integration_balance as $vv) {
				$data['integration']['user_balance'] += $vv->total;
			}

			$integration_balance = $this->db->query('SELECT domain_name,sum(amount) as total FROM wallet WHERE comm_from != "ex" AND status = 3 AND '. $where ." GROUP BY domain_name")->result();
			foreach ($integration_balance as $vv) {
				$data['store']['user_balance'] += $vv->total;
			}

			$data_integration_sale  = $this->db->query('SELECT domain_name,SUM(amount) as total,COUNT(*) as total_count FROM `wallet` WHERE type = "sale_commission" AND status > 0 AND comm_from = "ex" AND '. $where .' GROUP BY domain_name' )->result();
			 
			foreach ($data_integration_sale as $vv) {
				$data['integration']['sale'] += $vv->total;
				$data['integration']['total_count'] += $vv->total_count;
				$data['integration']['all'][$vv->domain_name]['sale'] += $vv->total;
				$data['integration']['all'][$vv->domain_name]['total_count'] += $vv->total_count;
			}

			$integration_click_count  = $this->db->query('SELECT base_url,count(*) as total FROM `integration_clicks_action` WHERE '. $where .'  AND is_action=0 GROUP BY base_url' )->result();
			foreach ($integration_click_count as $vv) {
				$data['integration']['click_count'] += $vv->total;
				$data['integration']['all'][$vv->base_url]['click_count'] += $vv->total;
			}

			$integration_click_amount = $this->db->query('SELECT domain_name,SUM(amount) as total FROM `wallet` WHERE type = "external_click_commission" AND is_action=0 AND comm_from = "ex" AND '. $where .' GROUP BY domain_name')->result();
			foreach ($integration_click_amount as $vv) {
				$data['integration']['click_amount'] += $vv->total;
				$data['integration']['all'][$vv->domain_name]['click_amount'] += $vv->total;
			}

			/*$integration_action_count  = $this->db->query('SELECT base_url,count(*) as total FROM `integration_clicks_action` WHERE '. $where .' AND is_action=1 GROUP BY base_url' )->result();

			foreach ($integration_action_count as $vv) {
				$data['integration']['action_count'] += $vv->total;
				$data['integration']['all'][$vv->base_url]['action_count'] += $vv->total;
			}*/

			$data['admin_transaction'] = $this->db->query('SELECT SUM(amount) as total  FROM `wallet` WHERE type="admin_transaction" AND  '. $where .'')->row()->total;
			
			$integration_click_amount = $this->db->query('SELECT domain_name,count(*) as total FROM `wallet` WHERE is_action=1 AND type="external_click_commission" AND status = 0 AND comm_from = "ex" AND '. $where .' GROUP BY domain_name')->result();
			foreach ($integration_click_amount as $vv) {
				$data['integration']['hold_action_count'] += $vv->total;
				$data['integration']['all'][$vv->domain_name]['hold_action_count'] += $vv->total;
			}

			$integration_click_amount = $this->db->query('SELECT domain_name,count(*) as total FROM `wallet` WHERE is_action=1 AND type="external_click_commission" AND status > 0 AND comm_from = "ex" AND '. $where .' GROUP BY domain_name')->result();
			foreach ($integration_click_amount as $vv) {
				$data['integration']['action_count'] += $vv->total;
				$data['integration']['all'][$vv->domain_name]['action_count'] += $vv->total;
			}

			$integration_click_amount = $this->db->query('SELECT domain_name,SUM(amount) as total FROM `wallet` WHERE is_action=1 AND type="external_click_commission" AND status > 0 AND comm_from = "ex" AND '. $where .' GROUP BY domain_name')->result();
			foreach ($integration_click_amount as $vv) {
				$data['integration']['action_amount'] += $vv->total;
				$data['integration']['all'][$vv->domain_name]['action_amount'] += $vv->total;
			}

			//$data['integration']['action_count'] = $this->db->query("SELECT count(*) as total FROM integration_refer_product_action  WHERE {$where} AND is_action = 1")->row()->total;

			$integration_total_orders = $this->db->query('SELECT base_url,count(*) as total,SUM(commission) as commission,SUM(total) as total_amount FROM `integration_orders` WHERE  '. $where .' GROUP BY base_url' )->result();
			//status > 0 AND

			foreach ($integration_total_orders as $vv) {
				$data['integration']['total_orders'] += $vv->total;
				$data['integration']['total_orders_amount'] += $vv->total_amount;
				$data['integration']['total_orders_commission'] += $vv->commission;
				$data['integration']['all'][$vv->base_url]['total_orders'] += $vv->total;
			}


			$data['integration']['total_commission'] = ($data['integration']['click_amount'] + $data['integration']['sale'] + $data['integration']['action_amount']);

			$data['store']['hold_orders'] = (int)$this->db->query('SELECT count(*) as total FROM `order` WHERE '. $where .' AND status  = 7')->row_array()['total'];
			//$data['integration']['hold_orders'] = (int)$this->db->query('SELECT count(*) as total FROM `integration_orders` WHERE '. $where .' AND status  = 0')->row_array()['total'];
			$data['integration']['hold_orders'] = (int)$this->db->query('SELECT count(*) as total FROM `wallet` WHERE type="sale_commission" AND '. $where .' AND status  = 0')->row_array()['total'];
			 
			$data['all_clicks_comm']                 += $data['integration']['click_amount'];
			$data['all_clicks']                      += $data['integration']['click_count'];
			
			$data['total_sale_count']                += $data['integration']['total_orders'] ;
			
			$data['store']['balance']                = $data['total_sale'];
			$data['store']['sale']                   = ($data['all_sale_comm'] - $data['integration']['sale']);
				
			$data['store']['click_count']            = ($data['all_clicks'] - $data['integration']['click_count']);
			$data['store']['click_amount']           = ($data['all_clicks_comm'] - $data['integration']['click_amount']);
			$data['store']['total_commission']       = ($data['store']['click_amount'] + $data['store']['sale']);

			$data['total_sale_amount'] = $data['total_sale'] + $data['integration']['total_orders_amount'];
			$data['total_sale_balance'] = $data['total_sale_balance'] + $data['integration']['total_orders_commission'];

			$data['total_balance'] = $data['total_sale'] + $data['integration']['balance'];
			$data['weekly_balance'] =  $data['admin_click_earning_week'] +  $data['total_sale_week'] + $integration_balance_week;
			$data['monthly_balance'] =  $data['admin_click_earning_month'] +  $data['total_sale_month'] + $integration_balance_month;
			$data['yearly_balance'] =  $data['admin_click_earning_year'] +  $data['total_sale_year'] + $integration_balance_year;
		}


		return $data;
	}

	/*public function getTotals($filter = array(),$extraTotals = false){
		$where = ' 1 ';
		$where1 = ' 1 ';		 
		if (isset($filter['user_id'])) {
			$where .= '  AND user_id = '. (int)$filter['user_id'];			
			$where1 .= '  AND op.refer_id = '. (int)$filter['user_id'];			
		}
		$data['total_all_in_request'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE status=2 AND '. $where)->row_array()['total'];
		$data['total_balance'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE status=1 AND '. $where)->row_array()['total'];
		$data['total_sale_commi'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE status=1 AND type = "sale_commission" AND '. $where )->row_array()['total'];
		$data['total_in_request'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE status=2 AND '. $where )->row_array()['total'];
		$data['total_click_commi'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE type = "click_commission" AND '. $where )->row_array()['total'];
		$data['total_no_click'] = (float)$this->db->query('SELECT COUNT(action_id) as total FROM product_action WHERE '.$where)->row_array()['total'];
		
		if($extraTotals){
			$data['total_sale_count'] = (int)$this->db->query('SELECT COUNT(op.order_id) as total FROM order_products op LEFT JOIN `order` o on (o.id = op.order_id) WHERE o.status > 0 AND '.$where1)->row_array()['total'];
			$data['aff_total_no_click'] = (float)$this->db->query('SELECT COUNT(id) as total FROM affiliate_action WHERE '.$where)->row_array()['total'];
			
			$data['unpaid_commition'] = ($data['total_sale_commi']  +$data['total_click_commi']);
			$data['paid_commition'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE status=3 AND type IN("click_commission","sale_commission") AND '. $where )->row_array()['total'];
			$data['requested_commition'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE status=2 AND type IN("click_commission","sale_commission") AND '. $where )->row_array()['total'];
			$data['aff_paid_commition'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE status=3 AND type IN("affiliate_click_commission") AND '. $where )->row_array()['total'];
			$data['aff_unpaid_commition'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE status=1 AND type IN("affiliate_click_commission") AND '. $where )->row_array()['total'];
			$data['aff_requested_commition'] = (float)$this->db->query('SELECT sum(amount) as total FROM wallet WHERE status=2 AND type IN("affiliate_click_commission") AND '. $where )->row_array()['total'];
		}

		return $data;
	}*/


	public function addTransactionRecursion($data){	
		$transaction_id = (int)$data['transaction_id'];
		$data['endtime'] = ($data['setCustomTime'] =='true' && $data['endtime']) ? date("Y-m-d H:i:00",strtotime($data['endtime'])) : null;

		if (isset($data['force_recursion_endtime'])) {
			$data['endtime'] = ($data['force_recursion_endtime']) ? date("Y-m-d H:i:s",strtotime($data['force_recursion_endtime'])) : null;
		}
		unset($data['force_recursion_endtime']);
		unset($data['setCustomTime']);

		$type = $data['type'];
		$json = [];

		if($type == ''){
			$this->db->query('UPDATE wallet_recursion SET type="", endtime=null, status=0 WHERE transaction_id='.$transaction_id);
			$json['status']= 'removed';
		} else {
			$data['next_transaction'] = $this->next_transaction_date($data['type'], $data['custom_time']);			
			$last_transaction = $this->db->query('SELECT next_transaction FROM wallet_recursion WHERE transaction_id='.$transaction_id.'  ORDER BY id DESC LIMIT 1' )->row();
			$data['last_transaction'] = $last_transaction->next_transaction;	
			$check_trans = $this->db->query('SELECT * FROM wallet_recursion WHERE transaction_id='.$transaction_id);
			if ( $check_trans->num_rows () > 0 ) {
				$results = $check_trans->result_array();
				$this->UpdateTransactionRecursion($data);
	        } else {
	        	$data['status'] = 1;
		        $this->db->insert('wallet_recursion',$data);
	        }

	        $next_transaction = $data['next_transaction'];
	        $json['status']=  'added';
	        
		}

		$next_transaction = $this->db->query('SELECT next_transaction FROM wallet_recursion WHERE transaction_id='.$transaction_id.'  ORDER BY id DESC LIMIT 1' )->row()->next_transaction;
        $total_recurring = $this->db->query("SELECT count(*) as total,SUM(amount) as total_amount FROM wallet WHERE parent_id = {$transaction_id} ")->row();
        $json['button'] = "<span class='badge badge-default p-2'>". cycle_details((int)$total_recurring->total, $next_transaction, $data['endtime'],(float)$total_recurring->total_amount) ."</span>";

		return $json;
	}

	public function GetTransactionRecursion($transaction_id){
		$row = $this->db->query("SELECT * FROM wallet_recursion WHERE transaction_id='$transaction_id'")->row_array();		

		return $row;
	}

	public function UpdateTransactionRecursion($data){
		$transaction_id = $data['transaction_id'];
		$custom_time = $data['custom_time'];
		$next_transaction = $data['next_transaction'];	
		$type = $data['type'];	

		$this->db->set("type", $type)
                  ->set("custom_time", $custom_time)                 
                  ->set("status", 1)                 
                  ->set("next_transaction", $next_transaction)                 
                  ->set("endtime", $data['endtime'])                 
                  ->where( 'transaction_id' , $transaction_id );

        if( $this->db->update('wallet_recursion') ){
            return true;
        }       
        
        return false;	
	}

	public function CronTransaction(){
	    $today = date('Y-m-d H:i:s'); 		     

		//$results = $this->db->query("SELECT * FROM wallet_recursion WHERE next_transaction < '$today' AND id IN (SELECT Max(id) FROM wallet_recursion GROUP BY transaction_id )");
		
		$results = $this->db->query("SELECT * FROM wallet_recursion WHERE status=1 AND (next_transaction <= endtime OR endtime IS NULL) AND  next_transaction <= '$today' LIMIT 5");
			
		if ( $results->num_rows () > 0 ) {
		  	$results = $results->result_array();

		  	foreach ($results as $recursion) {
		  		$transaction_id = $recursion['transaction_id'];				
		  		$wallet_payment = $this->db->query("SELECT * FROM wallet WHERE id = '$transaction_id'")->row_array();
		  		$wallet_payment['parent_id'] = $wallet_payment['id'];
		  		$wallet_payment['created_at'] = date("Y-m-d H:i:s");
		  		$wallet_payment['id'] = "";		  	
		  		
		  		if( $this->db->insert('wallet',$wallet_payment) ){
					$next_transaction = $this->next_transaction_date($recursion['type'],$recursion['custom_time']);
					$this->db->set("last_transaction", $recursion['next_transaction'])              
                  			 ->set("next_transaction", $next_transaction)                 
                  			 ->where ( 'transaction_id' , $transaction_id );

					$this->db->update('wallet_recursion');
		  		}
		  	}
		  	return true;
		}
		return false;		
	}


	public function next_transaction_date($type, $minutes = null){
		//date_default_timezone_set('Asia/Kolkata');		

		$today = strtotime( date('Y-m-d H:i:s') ); 
			
		if($type == 'every_day') {   
			$next_date = date('Y-m-d H:i:s', strtotime("+1 day", $today));
		}else if($type == 'every_week'){
			$next_date = date('Y-m-d H:i:s', strtotime("+7 day", $today));
		}else if($type == 'every_month'){
			$next_date = date('Y-m-d H:i:s', strtotime("+1 month", $today));
		}else if($type == 'every_year'){
			$next_date = date('Y-m-d H:i:s', strtotime("+1 year", $today));
		}else if($type == 'custom_time'){   
			$next_date = date('Y-m-d H:i:s', strtotime("+".$minutes." minutes", $today));
		}	

		return $next_date;
	}

}