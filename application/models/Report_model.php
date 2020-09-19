<?php
class Report_model extends MY_Model{
	public function view($file_name, $data = array(), $control = 'admincontrol'){

		$this->load->view($control . '/includes/header', $data);
		$this->load->view($control . '/includes/sidebar', $data);
		$this->load->view($control . '/includes/topnav', $data);
		$this->load->view($file_name, $data);
		$this->load->view($control . '/includes/footer', $data);
	}


	public function getAllTransaction($filter = array()){
		$where = array();

		$this->db->from('wallet');
		$this->db->join('users', 'users.id = wallet.user_id','LEFT');
		if (isset($filter['user_id'])) {
			$this->db->where('wallet.user_id', (int)$filter['user_id']);
		}
		if (isset($filter['status_gt'])) {
			$this->db->where('wallet.status >= '. (int)$filter['status_gt']);
		}
		if (isset($filter['status'])) {
			$this->db->where('wallet.status', (int)$filter['status']);
		}
		if (isset($filter['sortBy']) && $filter['sortBy'] && isset($filter['orderBy']) && $filter['orderBy']) {
			$this->db->order_by($filter['sortBy'], $filter['orderBy']);
		} else{
			$this->db->order_by('wallet.id','DESC');
		}

		$total_query = '';
		if (isset($filter['limit']) && isset($filter['page'])) {
			$limit = (int)$filter['limit'];
			$offset = (int)$filter['limit'] * ((int)$filter['page'] - 1);
			$this->db->limit($limit,$offset);
		}

		$this->db->select([
			'wallet.*',
			'users.username',
			'users.firstname',
			'users.lastname',
			"(SELECT payment_method FROM `order` WHERE wallet.reference_id = `order`.id AND wallet.type = 'sale_commission' ) as payment_method",
			"(SELECT total FROM `integration_orders` WHERE comm_from='ex' AND wallet.reference_id_2 = `integration_orders`.id AND wallet.type = 'sale_commission') as integration_orders_total "
		]);
		
		$results = $this->db->get()->result_array();
		$_status = $this->Wallet_model->status;
		$_status_icon = $this->Wallet_model->status_icon;

		$json = array();

		if (isset($filter['limit']) && isset($filter['page'])) {
			$total = explode("\n", $this->db->last_query());
			$total[0] = 'SELECT count(wallet.id) as total';
			array_pop($total);

			$total = $this->db->query(implode("\n", $total))->row();
			$json['total'] = $total->total;
		}

		$data = array();
		foreach ($results as $key => $value) {
			$data[] = array(
				'id'                       => $value['id'],
				'username'                     => $value['username'],
				'name'                     => $value['firstname'] ." " .$value['lastname'],
				'user_id'                  => $value['user_id'],
				'amount'                   => c_format($value['amount']),
				'comment'                  => parseMessage($value['comment'],$value, isset($filter['control']) ? $filter['control'] : 'usercontrol'),
				'created_at'               => $value['created_at'],
				'type'                     => $value['type'],
				'comm_from'                => $value['comm_from'] == 'ex' ? 'External' : 'Store',
				'dis_type'                 => wallet_type($value),
				'status'                   => $_status[$value['status']],
				'status_id'                => $value['status'],
				'integration_orders_total' => $value['integration_orders_total'],
				'status_icon'              => $_status_icon[$value['status']],
				'payment_method'           => payment_method($value['payment_method']),
			);
		}
		
		$json['data'] = $data;

		return $json;
	}


	public function getStatistics($filter = array()){
		$where_b = ' 1 ';
		$where_c = ' 1 ';

		if(isset($filter['user_id'])){
			$where_b = " user_id = ". (int)$filter['user_id'];
			$where_c = " refid = ". (int)$filter['user_id'];
		}

		$queryAffi = $this->db->query("SELECT m.country_code,c.name  FROM `affiliate_action` m LEFT JOIN `countries` c ON c.sortname = m.country_code WHERE ". $where_b)->result_array();
		$queryFrom = $this->db->query("SELECT m.country_code,c.name  FROM `form_action` m LEFT JOIN `countries` c ON c.sortname = m.country_code WHERE ". $where_b)->result_array();
		$queryProduct = $this->db->query("SELECT m.country_code,c.name  FROM `product_action` m LEFT JOIN `countries` c ON c.sortname = m.country_code WHERE ". $where_b)->result_array();
		$queryExternalClick = $this->db->query("SELECT m.country_code,c.name  FROM `integration_clicks_action` m LEFT JOIN `countries` c ON c.sortname = m.country_code WHERE m.is_action = 0 AND m.page_name = '' AND ". $where_b)->result_array();

		$data['clicks'] = $data['sale'] = array();
		foreach ($queryAffi as $value) { 
			@$data['clicks'][$value['name']]++; 
			@$data['clicks_count'] ++;
		}
		foreach ($queryFrom as $value) { 
			@$data['clicks'][$value['name']]++; 
			@$data['clicks_count'] ++;
		}
		foreach ($queryProduct as $value) { 
			@$data['clicks'][$value['name']]++; 
			@$data['clicks_count'] ++;
		}
		foreach ($queryExternalClick as $value) { 
			@$data['clicks'][$value['name']]++; 
			@$data['clicks_count'] ++;
		}

		$queryActionExternalClick = $this->db->query("SELECT m.country_code,c.name  FROM `integration_clicks_action` m LEFT JOIN `countries` c ON c.sortname = m.country_code WHERE m.is_action =1  AND ". $where_b)->result_array();
		foreach ($queryActionExternalClick as $value) { 
			@$data['action_clicks'][$value['name']]++; 
			@$data['action_clicks_count'] ++;
		}
		
		if(isset($filter['user_id'])){
			$queryOrder = $this->db->query("SELECT o.country_code,c.name FROM `order_products` op LEFT JOIN `order` o ON o.id= op.order_id LEFT JOIN countries c ON c.sortname = o.country_code WHERE o.status > 0 AND op.refer_id = ". (int)$filter['user_id'] ." GROUP BY op.order_id")->result_array();
			foreach ($queryOrder as $value) { 
				$label = $value['name'] ? $value['name'] : 'Unknow';
				@$data['sale'][$label]++; 
				@$data['sale_count']++; 
			}

			$queryOrder = $this->db->query("SELECT o.country_code,c.name  FROM `integration_orders` o LEFT JOIN `countries` c ON c.sortname = o.country_code WHERE o.status > 0 AND user_id= ". (int)$filter['user_id'])->result_array();
			foreach ($queryOrder as $value) { 
				$label = $value['name'] ? $value['name'] : 'Unknow';
				@$data['sale'][$label]++; 
				@$data['sale_count']++; 
			}
		}
		else{
			$queryOrder = $this->db->query("SELECT o.country_code,c.name  FROM `order` o LEFT JOIN `countries` c ON c.sortname = o.country_code WHERE o.status > 0 ")->result_array();
			
			foreach ($queryOrder as $value) { 
				$label = $value['name'] ? $value['name'] : 'Unknow';
				@$data['sale'][$label]++; 
				@$data['sale_count']++; 
			}
			$queryOrder = $this->db->query("SELECT o.country_code,c.name  FROM `integration_orders` o LEFT JOIN `countries` c ON c.sortname = o.country_code WHERE o.status > 0 ")->result_array();
			foreach ($queryOrder as $value) { 
				$label = $value['name'] ? $value['name'] : 'Unknow';
				@$data['sale'][$label]++; 
				@$data['sale_count']++; 
			}
		}


		$queryAffiUser = $this->db->query("SELECT c.name  FROM `users` u LEFT JOIN `countries` c ON c.id = u.Country WHERE u.type='user' AND ". $where_c)->result_array();
		foreach ($queryAffiUser as $value) { 
			$label = $value['name'] ? $value['name'] : 'Unknow';
			@$data['affiliate_user'][$label]++; 
			@$data['affiliate_user_count']++; 
		}

		$queryClient = $this->db->query("SELECT c.name  FROM `users` u LEFT JOIN `countries` c ON c.id = u.Country WHERE u.type='client' AND ". $where_c)->result_array();
		foreach ($queryClient as $value) { 
			$label = $value['name'] ? $value['name'] : 'Unknow';
			@$data['client_user'][$label]++; 
			@$data['client_user_count']++; 
		}

		return $data;
	}

	public function date_compare($element1, $element2) { 
	    $datetime1 = strtotime($element1['date']); 
	    $datetime2 = strtotime($element2['date']); 
	    return ($datetime1 == $datetime2) ? 0 : ($datetime1 < $datetime2) ? 1 : -1;
	}

	public function combine_window($data, $notify = false){
		$json = array();
		$live_log 	= $this->Product_model->getSettings('live_log');
		
		if(isset($live_log['admin_integration_logs']) && $live_log['admin_integration_logs'] == "1"){
			foreach ($data['integration_logs'] as $log) {
				$json[] = array(
					'date'  => date('Y-m-d H:i:s', strtotime($log['created_at'])),
					'type'  => 'integration_logs',
					'id'    => $log['id'],
					'title' => '<tr>
	                    <td>'. date('d-m-Y', strtotime($log['created_at'])) .'</td>
	                    <td>'. date('H:i A', strtotime($log['created_at'])) .'</td>
	                    <td><div class="log-div"><b>NEW '. $log['click_type'] .'</b> done From '. $log['flag'] .' '. $log['ip'] .' - <small>'. $log['country_code'] .'</small></div></td>
	                </tr>' ,
				);
			}
		}

		if(isset($live_log['admin_integration_orders']) && $live_log['admin_integration_orders'] == "1"){
			foreach ($data['integration_orders'] as $order) {
				$flag = '';
				if($order['country_code']){
					$flag = '<img title="'. $order['country_code'] .'" alt="'. $order['country_code'] .'" src="'. base_url('assets/vertical/assets/images/flags/'. strtolower($order['country_code'])) .'.png" width="25" height="15">';
				}
				$json[] = array(
					'type'  => 'integration_orders',
					'id'    => $order['id'],
					'date'  => date('Y-m-d H:i:s', strtotime($order['created_at'])),
					'title' => '<tr>
	                    <td>'. date('d-m-Y', strtotime($order['created_at'])) .'</td>
	                    <td>'. date('H:i A', strtotime($order['created_at'])) .'</td>
	                    <td><div class="log-div"><b>New Order '. $order['order_id'] .' ['. $order['total'] . $order['currency'] .'] </b> done From '. $order['country_code'] .' '. $flag .'</div></td>
	                </tr>' ,
				);
			}
		}

		if(isset($live_log['admin_newuser']) && $live_log['admin_newuser'] == "1"){
			foreach ($data['newuser'] as $users) {
				$flag = '';
				if($users['sortname']){
					$flag = '<img title="'. $users['sortname'] .'" alt="'. $users['sortname'] .'" src="'. base_url('assets/vertical/assets/images/flags/'. strtolower($users['sortname'])) .'.png" width="25" height="15">';
				}
				$json[] = array(
					'type'  => 'newuser',
					'id'    => $users['id'],
					'date'  => date('Y-m-d H:i:s', strtotime($users['created_at'])),
					'title' => '<tr>
	                    <td>'. date('d-m-Y', strtotime($users['created_at'])) .'</td>
	                    <td>'. date('H:i A', strtotime($users['created_at'])) .'</td>
	                    <td><div class="log-div"><b>New Affiliate</b> "'. $users['username'] .'" From '.  $users['sortname'] .' '. $flag . '</div></td>
	                </tr>' ,
				);
			}
		}
		
		usort($json, array('Report_model', 'date_compare') ); 
		return $json;
	}
}
