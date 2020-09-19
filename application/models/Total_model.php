<?php
class Total_model extends MY_Model{
	public function adminTotals(){
		$totals = [];

		$totals['admin_balance'] = $this->adminBalance();

		$sale_localstore_total = $this->db->query("SELECT 
			SUM(total) as total,
			SUM(admin_commission+commission) as total_commission,
			COUNT(*) as total_order 
		FROM order_products WHERE (vendor_id=0 OR vendor_id IS NULL) GROUP BY order_id ")->row();

		$totals['sale_localstore_total'] = $sale_localstore_total->total;
		$totals['sale_localstore_commission'] = $sale_localstore_total->total_commission;
		$totals['sale_localstore_count'] = $sale_localstore_total->total_order;


		$sale_localstore_vendor_total = $this->db->query("SELECT 
			SUM(total) as total,
			SUM(admin_commission+commission) as total_commission,
			COUNT(*) as total_order 
		FROM order_products WHERE vendor_id > 0 GROUP BY order_id ")->row();
		$totals['sale_localstore_vendor_total'] = $sale_localstore_vendor_total->total;
		$totals['sale_localstore_vendor_commission'] = $sale_localstore_vendor_total->total_commission;
		$totals['sale_localstore_vendor_count'] = $sale_localstore_vendor_total->total_order;


		$totals['click_localstore_total'] += (int)$this->db->query('SELECT COUNT(pa.action_id) as total FROM product_action pa LEFT JOIN product_affiliate paff ON (paff.product_id = pa.product_id) WHERE 1')->row()->total;
		$totals['click_localstore_commission'] = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE type='click_commission' AND amount > 0  ")->row()->total;

		$totals['click_integration_total'] += (int)$this->db->query('SELECT COUNT(id) as total FROM integration_clicks_action WHERE is_action=0')->row()->total;
		$totals['click_integration_commission'] = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE type IN('external_click_commission','external_click_comm_admin') AND status > 0 AND is_action=0 ")->row()->total;

		$totals['click_form_total'] = (int)$this->db->query('SELECT COUNT(action_id) as total FROM form_action WHERE 1')->row()->total;
		$totals['click_form_commission'] = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE type='form_click_commission'  ")->row()->total;

		$totals['click_action_total'] = (int)$this->db->query('SELECT COUNT(id) as total FROM integration_clicks_action WHERE is_action=1')->row()->total;
		$totals['click_action_commission'] = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE type IN('external_click_commission','external_click_comm_admin') AND is_action=1 ")->row()->total;


		$query = $this->db->query('SELECT sum(amount) as amount,count(`status`) as counts,`status` FROM `wallet` WHERE  amount > 0 GROUP BY `status`')->result_array();
		foreach ($query as $key => $value) {
			switch ($value['status']) {
				case '0':
					$totals['wallet_on_hold_amount'] = (float)$value['amount'];
					$totals['wallet_unpaid_amounton_hold_count'] = (float)$value['counts'];
					break;
				case '1':
					$totals['wallet_unpaid_amount'] = (float)$value['amount'];
					$totals['wallet_unpaid_count'] = (float)$value['counts'];
					break;
				case '2':
					$totals['wallet_request_sent_amount'] = (float)$value['amount'];
					$totals['wallet_request_sent_count'] = (float)$value['counts'];
					break;
				case '3':
					$totals['wallet_accept_amount'] = (float)$value['amount'];
					$totals['wallet_accept_count'] = (float)$value['counts'];
					break;
				case '4':
					$totals['wallet_cancel_amount'] = (float)$value['amount'];
					$totals['wallet_cancel_count'] = (float)$value['counts'];
					break;
				default: break;
			}
		}


		$query = $this->db->query('SELECT sum(amount) as amount,count(`status`) as counts,`status` FROM `wallet` WHERE type IN ("vendor_sale_commission") AND amount > 0 GROUP BY `status`')->result_array();
		foreach ($query as $key => $value) {
			switch ($value['status']) {
				case '0':
					$totals['vendor_wallet_on_hold_amount'] = (float)$value['amount'];
					$totals['vendor_wallet_on_hold_count'] = (float)$value['counts'];
					break;
				case '1':
					$totals['vendor_wallet_unpaid_amount'] = (float)$value['amount'];
					$totals['vendor_wallet_unpaid_count'] = (float)$value['counts'];
					break;
				case '2':
					$totals['vendor_wallet_request_sent_amount'] = (float)$value['amount'];
					$totals['vendor_wallet_request_sent_count'] = (float)$value['counts'];
					break;
				case '3':
					$totals['vendor_wallet_accept_amount'] = (float)$value['amount'];
					$totals['vendor_wallet_accept_count'] = (float)$value['counts'];
					break;
				case '4':
					$totals['vendor_wallet_cancel_amount'] = (float)$value['amount'];
					$totals['vendor_wallet_cancel_count'] = (float)$value['counts'];
					break;
				default: break;
			}
		}

		$totals['order_vendor_total'] += (float)$this->db->query('SELECT COUNT(op.id) as total FROM `order` o LEFT JOIN order_products op ON op.order_id = o.id WHERE 1 AND op.vendor_id > 0 AND o.status > 0')->row()->total;
		

		$order_external_count = $this->db->query('SELECT COUNT(id) as counts, SUM(total) as total,SUM(commission) as commission FROM integration_orders  WHERE 1')->row();
		$totals['order_external_total'] = $order_external_count->total;

		//$order_external_count = $this->db->query('SELECT SUM(total) as total,SUM(commission) as commission FROM integration_orders LEFT JOIN wallet as aw ON aw.id = integration_orders.affiliate_tran WHERE aw.status > 0 ')->row();
		
		$order_vendor_commission = $this->db->query('
			SELECT 
				COUNT(*) as total,
				SUM(aw.amount) as admin_tran,
				SUM(at.amount) as affiliate_tran 
			FROM 
				integration_orders o
				LEFT JOIN wallet as aw ON aw.id = o.admin_tran 
				LEFT JOIN wallet as at ON at.id = o.affiliate_tran 
			WHERE  o.status >= 0
		')->row();
		$totals['order_external_count'] = $order_vendor_commission->total;
		$totals['order_external_commission'] = $order_vendor_commission->admin_tran+$order_vendor_commission->affiliate_tran;

		return $totals;
	}

	public function chart($filter = []){
		$json = [];
		$orderBy = ' ORDER BY created_at DESC ';

        if($filter['group'] == 'month'){
            if(isset($filter['year'])){
                $current_year = " YEAR(created_at) = ". $filter['year'];
            }else{
                $current_year = " YEAR(created_at) = ". date("Y");
            }
        } else{
            $current_year .= ' 1=1 ';
        }

        if($filter['group'] == 'day'){ $groupby = 'CONCAT(DAY(created_at),"-",MONTH(created_at),"-",YEAR(created_at))'; }
        else if($filter['group'] == 'week'){ $groupby = 'WEEK(created_at)';}
        else if($filter['group'] == 'month'){ $groupby = 'MONTH(created_at)';}
        else if($filter['group'] == 'year'){ $groupby = 'YEAR(created_at)';}

        $this->db->select(array(
            'sum(commission) as total_commission',
            'sum(total) as total_sale',
            'count(id) as total_order',
            "{$groupby} as groupby"
        ));
      
        $this->db->where($current_year);
        $this->db->order_by('created_at','DESC');
        $this->db->group_by($groupby);
        $data = $this->db->get('integration_orders')->result_array();
         
        $chart = array();
        foreach ($data as $key => $value) {
            $chart[] = array(
				'key'              => $value['groupby'],
				'order_total'      => c_format($value['total_sale'], false),
				'order_count'      => (int)$value['total_order'],
				'order_commission' => c_format($value['total_commission'], false),
            );
        }

        $this->db->select(array(
            'sum(op.total) as total_sale',
            'count(op.id) as total_order',
            "{$groupby} as groupby",
            'sum(op.commission + op.vendor_commission) as total_commission'
        ));
        $this->db->join("order_products op",'op.order_id = order.id','left');
        $this->db->where($current_year);   
        $this->db->where('order.status = 1');
        $this->db->group_by('op.order_id');
        $data = $this->db->get('order')->result_array();
        
        foreach ($data as $key => $value) {
            $chart[] = array(
                'key' => $value['groupby'],
                'order_total' => c_format($value['total_sale'], false),
                'order_count' => (int)$value['total_order'],
                'order_commission' => c_format($value['total_commission'], false),
            );
        }

       
        $integration_click_amount = $this->db->query('SELECT '. $groupby . ' as groupby,SUM(amount) as total,COUNT(amount) as total_count FROM `wallet` WHERE is_action=1 AND type="external_click_commission" AND '. $_where . $current_year .' AND comm_from = "ex"  AND status > 0 GROUP BY '. $groupby .'   '. $orderBy )->result_array();
        foreach ($integration_click_amount as $value) {
            $chart[] = array(
                'key' => $value['groupby'],
                'action_commission' => c_format($value['total'], false),
                'action_count' => $value['total_count'],
            );
        }

        $week = [];
        $day = [];
        $year = [];
        for($i=1;$i<=53;$i++){ $week[] = "Week {$i}"; }
        for($i=1;$i<=31;$i++){ $day[date($i."-n-Y")] = date($i."-n-Y"); }
        for($i=2016;$i<=date("Y");$i++){ $year[$i] = $i; }

        $defaultKey = [
        	'month' => ['','January','February','March','April','May','June','July','August','September','October','November','December'],
        	'week' => $week,
        	'day' => $day,
        	'year' => $year,
        ];

        $allData = [];
        foreach ($chart as $key => $value) {
        	$DK = $defaultKey[$filter['group']][$value['key']];
        	$allData[$DK]['order_total'] += isset($value['order_total']) ? $value['order_total'] : 0;
        	$allData[$DK]['order_count'] += isset($value['order_count']) ? $value['order_count'] : 0;
        	$allData[$DK]['order_commission'] += isset($value['order_commission']) ? $value['order_commission'] : 0;
        	$allData[$DK]['action_commission'] += isset($value['action_commission']) ? $value['action_commission'] : 0;
        	$allData[$DK]['action_count'] += isset($value['action_count']) ? $value['action_count'] : 0;
        }

        foreach ($defaultKey[$filter['group']] as $key => $value) {
        	if($value){
	        	$json['order_total'][$value] = isset($allData[$value]['order_total']) ? $allData[$value]['order_total'] : 0; 
	        	$json['order_count'][$value] = isset($allData[$value]['order_count']) ? $allData[$value]['order_count'] : 0; 
	        	$json['order_commission'][$value] = isset($allData[$value]['order_commission']) ? $allData[$value]['order_commission'] : 0; 
	        	$json['action_commission'][$value] = isset($allData[$value]['action_commission']) ? $allData[$value]['action_commission'] : 0; 
	        	$json['action_count'][$value] = isset($allData[$value]['action_count']) ? $allData[$value]['action_count'] : 0; 
        	}
        }

        $json['keys'] = $defaultKey[$filter['group']];
        if ($filter['group'] == 'month') {
        	$json['keys'] = array_filter($defaultKey[$filter['group']]);
        }


        return $json;
	}

	public function adminBalance($filter = []){
		$wallet_where = ' 1 ';
		$order_product_where = ' 1 ';
		$aw_where = ' 1 ';

		if (isset($filter['week'])) {
			$wallet_where .= ' AND YEARWEEK(wallet.`created_at`, 1) = YEARWEEK(CURDATE(), 1)';
			$order_product_where .= ' AND YEARWEEK(o.`created_at`, 1) = YEARWEEK(CURDATE(), 1)';
			$aw_where .= ' AND YEARWEEK(aw.`created_at`, 1) = YEARWEEK(CURDATE(), 1)';
		}
		
		if (isset($filter['month'])) {
			$wallet_where .= ' AND MONTH(wallet.`created_at`) = MONTH(NOW())';
			$order_product_where .= ' AND MONTH(o.`created_at`) = MONTH(NOW())';
			$aw_where .= ' AND MONTH(aw.`created_at`) = MONTH(NOW())';
		}
		
		if (isset($filter['year'])) {
			$year = date("Y");
			$wallet_where .= ' AND YEAR(wallet.`created_at`) ='. $year;
			$order_product_where .= ' AND YEAR(o.`created_at`) ='. $year;
			$aw_where .= ' AND YEAR(aw.`created_at`) ='. $year;
		}

		$balance  = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE {$wallet_where} AND type='vendor_sale_commission' AND user_id=1 AND status > 0")->row()->total;

		$balance += $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE {$wallet_where} AND type IN('external_click_comm_admin') AND is_action=1 AND status>0")->row()->total;
 		 
		$balance += $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE {$wallet_where} AND type IN('click_commission','admin_sale_commission') AND user_id=1 AND status>0 ")->row()->total;

		$balance -= $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE {$wallet_where} AND type IN('click_commission') AND reference_id_2 = '' AND comm_from = 'store' ")->row()->total;

		$balance -= $t= $this->db->query("
			SELECT 
				SUM(wallet.amount) as total 
			FROM wallet 
				LEFT JOIN order_products op ON op.order_id = wallet.reference_id
			WHERE 
				wallet.type IN('sale_commission')
				AND (op.vendor_id IS NULL OR op.vendor_id = 0)  
				AND wallet.status > 0
				AND {$wallet_where}
				AND wallet.comm_from='store'
			")->row()->total;

		$balance += $this->db->query("
			SELECT 
				SUM(op.total) as total
			FROM 
				order_products op
				LEFT JOIN `order` o ON o.id = op.order_id 
			WHERE (op.vendor_id=0 OR op.vendor_id IS NULL) AND {$order_product_where} AND o.status NOT IN(0,7) GROUP BY op.order_id "
		)->row()->total;

		$balance -= $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE {$wallet_where} AND type IN('external_click_commission') AND status>0 AND is_action=1 AND is_vendor=0 ")->row()->total;

		$balance -= $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE {$wallet_where} AND type IN('external_click_commission','external_click_comm_admin') AND status > 0 AND is_action=0 AND is_vendor=0 ")->row()->total;

		$balance += $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE {$wallet_where} AND type IN('external_click_commission','external_click_comm_admin') AND user_id=1 AND status > 0 AND is_action=0 AND is_vendor=1 ")->row()->total;

		//$balance += $this->db->query("SELECT SUM(commission) as commission FROM integration_orders LEFT JOIN wallet as aw ON aw.id = integration_orders.affiliate_tran WHERE {$aw_where} AND aw.status > 0")->row()->commission;
		//$balance += $this->db->query("SELECT SUM(commission) as commission FROM integration_orders LEFT JOIN wallet as aw ON aw.id = integration_orders.affiliate_tran WHERE {$aw_where} AND aw.status > 0")->row()->commission;

		$balance -=  $this->db->query("SELECT SUM(commission) as commission FROM integration_orders LEFT JOIN wallet as aw ON aw.id = integration_orders.affiliate_tran WHERE {$aw_where} AND aw.status > 0 AND integration_orders.vendor_id=0")->row()->commission;

		$balance += $this->db->query("SELECT SUM(total) as total FROM integration_orders LEFT JOIN wallet as aw ON aw.id = integration_orders.affiliate_tran WHERE {$aw_where} AND aw.status > 0 AND integration_orders.vendor_id=0")->row()->total;
		//$balance -= $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE {$wallet_where} AND comm_from='ex' AND type='sale_commission' AND status > 0 AND is_vendor=1 ")->row()->total;

		return $balance;
	}

	public function getIntegrationsTotals(){
		$where = ' 1 ';
		$where1 = ' 1 ';		 
		$where_vendor = ' 1 ';	

		$data['integration']['hold_action_count'] = 0;
		$data['integration']['hold_orders'] = 0;
		
		$integration_balance = $this->db->query('SELECT 
			integration_orders.vendor_id,
			integration_orders.base_url,
			integration_orders.total,
			wallet.status as wallet_status,
			integration_orders.commission as admin_comm 
		FROM integration_orders
			LEFT JOIN wallet ON wallet.id = integration_orders.affiliate_tran
		WHERE   '. $where ."  ")->result();
		//(integration_orders.vendor_id IS NULL OR integration_orders.vendor_id =0) AND


		foreach ($integration_balance as $vv) {
			if($vv->wallet_status > 0){
				if((int)$vv->vendor_id == 0){
					$data['integration']['balance'] += ($vv->total - $vv->admin_comm);
					$data['integration']['all'][$vv->base_url]['balance'] += ($vv->total- $vv->admin_comm);
				} else{
					$data['integration']['balance'] += $vv->admin_comm;
					$data['integration']['all'][$vv->base_url]['balance'] += $vv->admin_comm;
				}
			}

			$data['integration']['total_count'] += 1;
			$data['integration']['all'][$vv->base_url]['total_count'] += 1;

			if((int)$vv->vendor_id == 0){
				if($vv->wallet_status > 0){
					$data['integration']['sale'] += ($vv->total - $vv->admin_comm);
					$data['integration']['all'][$vv->base_url]['sale'] +=($vv->total - $vv->admin_comm);
				}
			} else{
				$data['integration']['sale'] += $vv->admin_comm;
				$data['integration']['all'][$vv->base_url]['sale'] += $vv->admin_comm;
			}
		}


		/*$data_integration_sale  = $this->db->query('SELECT 
			is_vendor,
			domain_name,
			amount as total
		FROM `wallet` 
		WHERE 
			type = "sale_commission" AND 
			(is_vendor=0 OR is_vendor IS NULL) AND 
			status > 0 AND 
			comm_from = "ex" AND '. $where )->result();

		
		foreach ($data_integration_sale as $vv) {
			$data['integration']['total_count'] += 1;
			$data['integration']['all'][$vv->domain_name]['total_count'] += 1;

			if((int)$vv->is_vendor == 0){
				$data['integration']['sale'] += $vv->total;
				$data['integration']['all'][$vv->domain_name]['sale'] += $vv->total;
			}
		}*/




		$integration_click_count  = $this->db->query('SELECT base_url,count(*) as total FROM `integration_clicks_action` WHERE '. $where .'  AND is_action=0 GROUP BY base_url' )->result();
		foreach ($integration_click_count as $vv) {
			$data['integration']['click_count'] += $vv->total;
			$data['integration']['all'][$vv->base_url]['click_count'] += $vv->total;
		}

		$integration_click_amount = $this->db->query('
			SELECT domain_name,SUM(amount) as total 
			FROM `wallet` 
			WHERE 
				type = "external_click_commission" AND 
				is_vendor=0 AND 
				is_action=0 AND 
				comm_from = "ex" AND '. $where .' 
				GROUP BY domain_name
		')->result();

		foreach ($integration_click_amount as $vv) {
			$data['integration']['click_amount'] -= $vv->total;
			$data['integration']['all'][$vv->domain_name]['click_amount'] -= $vv->total;

			$data['integration']['balance'] -= $vv->total;
			$data['integration']['all'][$vv->domain_name]['balance'] -= $vv->total;
		}

		$vendor_integration_click_amount = $this->db->query('
			SELECT domain_name,SUM(amount) as total 
			FROM `wallet` 
			WHERE 
				type = "external_click_comm_admin" AND is_vendor=1 AND 
				is_action=0 AND 
				comm_from = "ex" AND '. $where .' 
				GROUP BY domain_name
		')->result();


		foreach ($vendor_integration_click_amount as $vv) {
			$data['integration']['click_amount'] += $vv->total;
			$data['integration']['all'][$vv->domain_name]['click_amount'] += $vv->total;
			
			$data['integration']['balance'] += $vv->total;
			$data['integration']['all'][$vv->domain_name]['balance'] += $vv->total;
		}

		
		$integration_click_amount = $this->db->query('SELECT domain_name,count(*) as total FROM `wallet` WHERE is_action=1 AND type="external_click_commission" AND status = 0 AND comm_from = "ex" AND '. $where .' GROUP BY domain_name')->result();
		foreach ($integration_click_amount as $vv) {
			$data['integration']['hold_action_count'] += $vv->total;
			$data['integration']['all'][$vv->domain_name]['hold_action_count'] += $vv->total;
		}


		
		$integration_action_amount = $this->db->query('SELECT 
			status,
			domain_name,
			amount as total
			FROM `wallet` WHERE is_action=1 AND type="external_click_commission" AND comm_from = "ex" AND is_vendor=0 AND '. $where .' ')->result();

		foreach ($integration_action_amount as $vv) {
			if($vv->status > 0){
				$data['integration']['action_amount'] -= $vv->total;
				$data['integration']['all'][$vv->domain_name]['action_amount'] -= $vv->total;
			}

			$data['integration']['action_count'] += 1;
			$data['integration']['all'][$vv->domain_name]['action_count'] += 1;
		}

		$integration_action_amount = $this->db->query('SELECT domain_name,SUM(amount) as total FROM `wallet` WHERE is_action=1 AND type="external_click_commission" AND status > 0 AND comm_from = "ex" AND is_vendor=0 AND '. $where .' GROUP BY domain_name')->result();

		foreach ($integration_action_amount as $vv) {
			$data['integration']['balance'] -= $vv->total;
			$data['integration']['all'][$vv->domain_name]['balance'] -= $vv->total;
		}




		$integration_action_amount_vendor = $this->db->query('SELECT 
			status,
			domain_name,
			amount as total
		FROM `wallet` 
		WHERE 
			is_action=1 AND 
			type="external_click_comm_admin" AND 
			comm_from = "ex" AND 
			is_vendor=1 AND '. $where .' ')->result();

		foreach ($integration_action_amount_vendor as $vv) {
			if($vv->status > 0){
				$data['integration']['action_amount'] += $vv->total;
				$data['integration']['all'][$vv->domain_name]['action_amount'] += $vv->total;
			}

			$data['integration']['action_count'] += 1;
			$data['integration']['all'][$vv->domain_name]['action_count'] += 1;
		}


		$integration_action_amount_vendor = $this->db->query('SELECT domain_name,SUM(amount) as total FROM `wallet` WHERE 
			is_action=1 AND 
			type="external_click_comm_admin" AND 
			status > 0 AND 
			comm_from = "ex" AND 
			is_vendor=1 AND '. $where .' GROUP BY domain_name')->result();

		foreach ($integration_action_amount_vendor as $vv) {
			$data['integration']['balance'] += $vv->total;
			$data['integration']['all'][$vv->domain_name]['balance'] += $vv->total;
		}
		 



		/*$integration_click_amount_vendor = $this->db->query('SELECT domain_name,SUM(amount) as total FROM `wallet` WHERE is_action=1 AND type="external_click_comm_admin" AND status > 0 AND comm_from = "ex" AND is_vendor=1 AND '. $where .' GROUP BY domain_name')->result();

		foreach ($integration_click_amount_vendor as $vv) {
			$data['integration']['balance'] += $vv->total;
			$data['integration']['all'][$vv->domain_name]['balance'] += $vv->total;
		}*/


		$integration_total_orders = $this->db->query('SELECT base_url,count(*) as total,SUM(commission) as commission,SUM(total) as total_amount FROM `integration_orders` WHERE  '. $where .' GROUP BY base_url' )->result();
		foreach ($integration_total_orders as $vv) {
			$data['integration']['total_orders'] += $vv->total;
			$data['integration']['total_orders_amount'] += $vv->total_amount;
			$data['integration']['total_orders_commission'] += $vv->commission;
			$data['integration']['all'][$vv->base_url]['total_orders'] += $vv->total;
		}

		$data['integration']['total_commission'] = ($data['integration']['click_amount'] + $data['integration']['sale'] + $data['integration']['action_amount']);
		
		return $data;
	}

	public function get_integartion_data($return  = false){
		$post = $this->input->post();
		$json = array();

		if($post['integration_data_year'] && $post['integration_data_month']){
			$integration_filters = array(
				'integration_data_year' => $post['integration_data_year'],
				'integration_data_month' => $post['integration_data_month'],
			);
		}else{
			$integration_filters = array();
		}
		$json['array'] = '';

		$totals = $this->getIntegrationsTotals($integration_filters);
		
		if($totals){
			$html = '';
			if ($totals['integration']['all'] ==null) {
			   
			} else {
			    $html .= '<tr>
		            <td>ALL WEBSITE</td>
		            <td class="no-wrap">'. c_format($totals['integration']['balance']) .'</td>
		            <td class="no-wrap">'. (int)$totals['integration']['total_count'] .' / '. c_format($totals['integration']['sale']) .'</td>
		            <td class="no-wrap">'. (int)$totals['integration']['click_count'] .' / '. c_format($totals['integration']['click_amount']) .'</td>
		            <td class="no-wrap">'. (int)$totals['integration']['action_count'] .' / '. c_format($totals['integration']['action_amount']) .'</td>
		            <td class="no-wrap">'. c_format($totals['integration']['total_commission']) .' </td>
		            <td class="no-wrap">'. (int)$totals['integration']['total_orders'] .' </td>
		        </tr>';
			    $index = 0; 
			    foreach ($totals['integration']['all'] as $website => $value) {
			        $html .= '<tr>
	                <td class="no-wrap">'. $website .'</td>
	                <td class="no-wrap">'. c_format($value['balance']) .'</td>
	                <td class="no-wrap">'. (int)$value['total_count'] .' / '. c_format($value['sale']) .'        </td>
	                <td class="no-wrap">'. (int)$value['click_count'] .' / '. c_format($value['click_amount']) .'</td>
	                <td class="no-wrap">'. (int)$value['action_count'] .' / '. c_format($value['action_amount']) .'</td>
	                <td class="no-wrap">'. c_format($value['click_amount'] + $value['sale'] + $value['action_amount']) .' </td>
	                <td class="no-wrap">'. (int)$value['total_orders'] .' </td>
	            </tr>';
			    }
			}

			$integration_data_selected = 'all';
			if(isset($post['integration_data_selected']) && $post['integration_data_selected'] != '') $integration_data_selected = $post['integration_data_selected'];

            $json['html'] = $html;
            //$type = isset($post['integration_data_website_selected']) && $post['integration_data_website_selected'] != '' ?  $post['integration_data_website_selected'] : 'all';

			/*if($type == 'all'){
				$data = array(
					'balance'				=>	(float)$totals['integration']['balance'],
					'total_orders_amount'	=>	(float)$totals['integration']['total_orders_amount'],
					'sale'					=>	(float)$totals['integration']['sale'],
					'click_count'			=>	(float)$totals['integration']['click_count'],
					'click_amount'			=>	(float)$totals['integration']['click_amount'],
					'action_count'			=>	(float)$totals['integration']['action_count'],
					'action_amount'			=>	(float)$totals['integration']['action_amount'],
					'total_commission'		=>	(float)$totals['integration']['total_commission'],
					'total_orders'			=>	(float)$totals['integration']['total_orders'],
				);
			}else{
				$integration = $totals['integration']['all'];
				if(isset($integration[$type])){
					$data = array(
						'balance'				=>	isset($integration[$type]['balance']) ? (float)$integration[$type]['balance'] : 0,
						'total_orders_amount'	=>	isset($integration[$type]['total_orders_amount']) ? (float)$integration[$type]['total_orders_amount'] : 0,
						'sale'					=>	isset($integration[$type]['sale']) ? (float)$integration[$type]['sale'] : 0,
						'click_count'			=>	isset($integration[$type]['click_count']) ? (float)$integration[$type]['click_count'] : 0,
						'click_amount'			=>	isset($integration[$type]['click_amount']) ? (float)$integration[$type]['click_amount'] : 0,
						'action_count'			=>	isset($integration[$type]['action_count']) ? (float)$integration[$type]['action_count'] : 0,
						'action_amount'			=>	isset($integration[$type]['action_amount']) ? (float)$integration[$type]['action_amount'] : 0,
						'total_commission'		=>	isset($integration[$type]['total_commission']) ? (float)$integration[$type]['total_commission'] : 0,
						'total_orders'			=>	isset($integration[$type]['total_orders']) ? (float)$integration[$type]['total_orders'] : 0,
					);
				}
			}*/

			//$json['chart'][$post['integration_data_year']] = $data;
			
		}else{
			$json['html'] = false;
		}

		if($return) return $json;
		echo json_encode($json);die;
	}


	public function getUserTotals($user_id){
		$totals = [];

		$totals['click_localstore_total'] += (int)$this->db->query("SELECT COUNT(pa.action_id) as total FROM product_action pa LEFT JOIN product_affiliate paff ON (paff.product_id = pa.product_id) WHERE (pa.user_id={$user_id})")->row()->total;		
		$totals['click_localstore_commission'] = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE type='click_commission' AND amount > 0 AND user_id={$user_id}  ")->row()->total;

		$totals['sale_localstore_total'] = 0;
		$totals['sale_localstore_commission'] = 0;
		$totals['sale_localstore_count'] = 0;
		
		$sale_localstore_total_query = $this->db->query("SELECT 
			SUM(total) as total,
			SUM(commission) as total_commission,
			COUNT(*) as total_order 
		FROM order_products WHERE refer_id={$user_id} GROUP BY order_id ")->result();

		foreach ($sale_localstore_total_query as $key => $value) {
			$totals['sale_localstore_total'] += $value->total;
			$totals['sale_localstore_commission'] += $value->total_commission;
			$totals['sale_localstore_count'] += $value->total_order;
		}


		$totals['click_external_total'] += (int)$this->db->query("SELECT COUNT(id) as total FROM integration_clicks_action WHERE user_id={$user_id} AND is_action=0")->row()->total;
		$totals['click_external_commission'] = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE type IN('external_click_commission') AND status > 0 AND is_action=0 AND user_id={$user_id} ")->row()->total;

		$order_external_count = $this->db->query("
			SELECT 
				COUNT(id) as counts, 
				SUM(total) as total,
				SUM(commission) as commission 
				FROM integration_orders  WHERE user_id={$user_id} ")->row();
		$totals['order_external_total'] = $order_external_count->total;
		$totals['order_external_count'] = $order_external_count->counts;
		$totals['order_external_commission'] = $order_external_count->commission;

		$totals['click_action_total'] = (int)$this->db->query("SELECT COUNT(id) as total FROM integration_clicks_action WHERE is_action=1 AND user_id={$user_id}")->row()->total;
		$totals['click_action_commission'] = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE type IN('external_click_commission','external_click_comm_admin') AND is_action=1 AND user_id={$user_id}")->row()->total;

		/* VENDOR COUNTS */

		$totals['vendor_click_localstore_total'] += (int)$this->db->query("SELECT COUNT(pa.action_id) as total FROM product_action pa LEFT JOIN product_affiliate paff ON (paff.product_id = pa.product_id) WHERE (paff.user_id={$user_id})")->row()->total;
		$totals['vendor_click_localstore_commission_pay'] = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE type='click_commission' AND amount < 0 AND user_id={$user_id}  ")->row()->total;

		$sale_localstore_total = $this->db->query("SELECT 
			SUM(total) as total,
			SUM(admin_commission+commission) as total_commission,
			COUNT(*) as total_order 
		FROM order_products WHERE vendor_id={$user_id} GROUP BY order_id ")->row();

		$totals['vendor_sale_localstore_total'] = $sale_localstore_total->total;
		$totals['vendor_sale_localstore_commission_pay'] = $sale_localstore_total->total_commission;
		$totals['vendor_sale_localstore_count'] = $sale_localstore_total->total_order;

		$totals['vendor_click_external_total'] += (int)$this->db->query("
			SELECT COUNT(ica.id) as total 
			FROM integration_clicks_action ica 
				LEFT JOIN integration_tools it ON it.id = ica.tools_id 
			WHERE it.vendor_id={$user_id} AND is_action=0")->row()->total;

		$totals['vendor_click_external_commission_pay'] = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE type IN('external_click_comm_pay') AND status > 0 AND is_action=0 AND user_id={$user_id} ")->row()->total;


		$totals['vendor_action_external_total'] = (int)$this->db->query("
			SELECT COUNT(ica.id) as total 
			FROM integration_clicks_action ica 
				LEFT JOIN integration_tools it ON it.id = ica.tools_id 
			WHERE it.vendor_id={$user_id} AND is_action=1")->row()->total;

		$totals['vendor_action_external_commission_pay'] = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE type IN('external_click_comm_pay') AND status > 0 AND is_action=1 AND user_id={$user_id} ")->row()->total;


		$totals['vendor_order_external_commission_pay'] = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE type IN ('sale_commission_vendor_pay','admin_sale_commission_v_pay') AND user_id={$user_id} ")->row()->total;

		$vendor_order_external = $this->db->query("SELECT COUNT(id) as counts, SUM(total) as total FROM integration_orders  WHERE vendor_id={$user_id} ")->row();

		$totals['vendor_order_external_count'] = $vendor_order_external->counts;
		$totals['vendor_order_external_total'] = $vendor_order_external->total;

		$totals['click_form_total'] = (int)$this->db->query("SELECT COUNT(action_id) as total FROM form_action WHERE user_id={$user_id}")->row()->total;
		$totals['click_form_commission'] = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE type='form_click_commission' AND user_id={$user_id}")->row()->total;

		$query = $this->db->query("SELECT sum(amount) as amount,count(`status`) as counts,`status` FROM `wallet` WHERE user_id={$user_id}  GROUP BY `status`")->result_array();
		foreach ($query as $key => $value) {
			switch ($value['status']) {
				case '0':
					$totals['wallet_on_hold_amount'] = (float)$value['amount'];
					$totals['wallet_unpaid_amounton_hold_count'] = (float)$value['counts'];
					break;
				case '1':
					$totals['wallet_unpaid_amount'] = (float)$value['amount'];
					$totals['wallet_unpaid_count'] = (float)$value['counts'];
					break;
				case '2':
					$totals['wallet_request_sent_amount'] = (float)$value['amount'];
					$totals['wallet_request_sent_count'] = (float)$value['counts'];
					break;
				case '3':
					$totals['wallet_accept_amount'] = (float)$value['amount'];
					$totals['wallet_accept_count'] = (float)$value['counts'];
					break;
				case '4':
					$totals['wallet_cancel_amount'] = (float)$value['amount'];
					$totals['wallet_cancel_count'] = (float)$value['counts'];
					break;
				default: break;
			}
		}

		
		$totals['user_balance'] = $this->getUserBalance($user_id);
		
		return $totals;
	}

	public function getUserBalance($user_id, $filter = []){
		$wallet_where = ' AND 1 ';
		$order_product_where = ' AND 1 ';
		$aw_where = ' AND 1 ';

		if (isset($filter['week'])) {
			$wallet_where .= ' AND YEARWEEK(wallet.`created_at`, 1) = YEARWEEK(CURDATE(), 1)';
			$order_product_where .= ' AND YEARWEEK(o.`created_at`, 1) = YEARWEEK(CURDATE(), 1)';
		}
		
		if (isset($filter['month'])) {
			$wallet_where .= ' AND MONTH(wallet.`created_at`) = MONTH(NOW())';
			$order_product_where .= ' AND MONTH(o.`created_at`) = MONTH(NOW())';
		}
		
		if (isset($filter['year'])) {
			$year = date("Y");
			$wallet_where .= ' AND YEAR(wallet.`created_at`) ='. $year;
			$order_product_where .= ' AND YEAR(o.`created_at`) ='. $year;
		}

		$click_localstore_commission = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE type='click_commission' AND amount > 0 AND user_id={$user_id} {$wallet_where} ")->row()->total;
		$click_external_commission = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE type IN('external_click_commission') AND status > 0 AND is_action=0 AND user_id={$user_id} {$wallet_where} ")->row()->total;
		$click_action_commission = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE type IN('external_click_commission','external_click_comm_admin') AND is_action=1 AND status>0 AND user_id={$user_id} {$wallet_where} ")->row()->total;


		$sale_localstore_commission_query = $this->db->query("SELECT 
			SUM(commission) as total_commission
		FROM order_products
		LEFT JOIN `order` o ON o.id= order_products.order_id 
		LEFT JOIN `wallet` ON (wallet.reference_id= o.id AND wallet.type='sale_commission') 
		WHERE refer_id={$user_id} AND wallet.status>0 {$wallet_where}  GROUP BY order_id ")->result();
		$sale_localstore_commission = 0;
		foreach ($sale_localstore_commission_query as $key => $value) {
			$sale_localstore_commission += $value->total_commission;
		}

		$order_external_commission = $this->db->query("
				SELECT SUM(io.commission) as commission 
				FROM integration_orders  io 
					LEFT JOIN wallet ON wallet.id=io.affiliate_tran 
				WHERE io.user_id={$user_id} AND wallet.status > 0 {$wallet_where}
				")->row()->commission;

		$click_form_commission = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE type='form_click_commission' AND user_id={$user_id} {$wallet_where}")->row()->total;



		$vendor_click_localstore_commission_pay = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE type='click_commission' AND amount < 0 AND user_id={$user_id} {$wallet_where} ")->row()->total;

		$sale_localstore_total = $this->db->query("SELECT 
				SUM(op.total) as total,
				SUM(op.admin_commission+ op.commission) as total_commission,
				COUNT(op.id) as total_order 
			FROM order_products op
				LEFT JOIN `order` o ON o.id= op.order_id 
				LEFT JOIN wallet w ON (w.reference_id = o.id AND w.type='sale_commission')
			WHERE op.vendor_id={$user_id} AND w.status > 0 {$order_product_where} GROUP BY op.order_id 
		")->result();

		foreach ($sale_localstore_total as $key => $value) {
			$vendor_sale_localstore_total += $value->total;
			$vendor_sale_localstore_commission_pay += $value->total_commission;
		}

		$vendor_click_external_commission_pay = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE type IN('external_click_comm_pay') AND status > 0 AND is_action=0 AND user_id={$user_id} {$wallet_where} ")->row()->total;
		$vendor_action_external_commission_pay = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE type IN('external_click_comm_pay') AND status > 0 AND is_action=1 AND user_id={$user_id} {$wallet_where} ")->row()->total;
		$vendor_order_external_commission_pay = $this->db->query("SELECT SUM(amount) as total FROM wallet WHERE type IN ('sale_commission_vendor_pay','admin_sale_commission_v_pay') AND user_id={$user_id} AND status > 0 {$wallet_where} ")->row()->total;
		$vendor_order_external_total = $this->db->query("SELECT 
			COUNT(integration_orders.id) as counts, 
			SUM(integration_orders.total) as total 
			FROM integration_orders 
				LEFT JOIN wallet ON wallet.id = integration_orders.affiliate_tran
			WHERE vendor_id={$user_id} AND wallet.status > 0 {$wallet_where}")->row()->total;

		$user_balance = 0;
		$user_balance += $click_localstore_commission;
		$user_balance += $sale_localstore_commission;
		$user_balance += $click_external_commission;
		$user_balance += $click_action_commission;
		$user_balance += $order_external_commission;
		$user_balance += $click_form_commission;

		$user_balance -= abs($vendor_click_localstore_commission_pay);
		$user_balance += $vendor_sale_localstore_total;
		$user_balance -= abs($vendor_sale_localstore_commission_pay);
		$user_balance -= abs($vendor_click_external_commission_pay);
		$user_balance -= abs($vendor_action_external_commission_pay);
		$user_balance -= abs($vendor_order_external_commission_pay);
		$user_balance += $vendor_order_external_total;

		return $user_balance;
	}


	public function chartUser($userid , $filter = []){
		$json = [];
		$orderBy = ' ORDER BY created_at DESC ';

        if($filter['group'] == 'month'){
            if(isset($filter['year'])){
                $current_year = " YEAR(created_at) = ". $filter['year'];
            }else{
                $current_year = " YEAR(created_at) = ". date("Y");
            }
        } else{
            $current_year .= ' 1=1 ';
        }

        if($filter['group'] == 'day'){ $groupby = 'CONCAT(DAY(created_at),"-",MONTH(created_at),"-",YEAR(created_at))'; }
        else if($filter['group'] == 'week'){ $groupby = 'WEEK(created_at)';}
        else if($filter['group'] == 'month'){ $groupby = 'MONTH(created_at)';}
        else if($filter['group'] == 'year'){ $groupby = 'YEAR(created_at)';}

        $this->db->select(array(
            'sum(commission) as total_commission',
            'sum(total) as total_sale',
            'count(id) as total_order',
            "{$groupby} as groupby"
        ));
      
        $this->db->where($current_year);
        $this->db->order_by('created_at','DESC');
        $this->db->where('user_id',$userid);
        $this->db->group_by($groupby);
        $data = $this->db->get('integration_orders')->result_array();
        
        $chart = array();
        foreach ($data as $key => $value) {
            $chart[] = array(
				'key'              => $value['groupby'],
				'order_total'      => c_format($value['total_sale'], false),
				'order_count'      => (int)$value['total_order'],
				'order_commission' => c_format($value['total_commission'], false),
            );
        }

        $this->db->select(array(
            'sum(op.total) as total_sale',
            'count(op.id) as total_order',
            "{$groupby} as groupby",
            'sum(op.vendor_commission) as total_commission'
        ));
        $this->db->join("order_products op",'op.order_id = order.id','left');
        $this->db->where('op.vendor_id',$userid);   
        $this->db->where($current_year);   
        $this->db->where('order.status = 1');
        $this->db->group_by('op.order_id');
        $data = $this->db->get('order')->result_array();
        
        foreach ($data as $key => $value) {
            $chart[] = array(
                'key' => $value['groupby'],
                'order_total' => c_format($value['total_sale'], false),
                'order_count' => (int)$value['total_order'],
                'order_commission' => c_format($value['total_commission'], false),
            );
        }

        $this->db->select(array(
            'sum(op.total) as total_sale',
            'count(op.id) as total_order',
            "{$groupby} as groupby",
            'sum(op.commission) as total_commission'
        ));
        $this->db->join("order_products op",'op.order_id = order.id','left');
        $this->db->where('op.refer_id',$userid);   
        $this->db->where($current_year);   
        $this->db->where('order.status = 1');
        $this->db->group_by('op.order_id');
        $data = $this->db->get('order')->result_array();
        
        foreach ($data as $key => $value) {
            $chart[] = array(
                'key' => $value['groupby'],
                'order_total' => c_format($value['total_sale'], false),
                'order_count' => (int)$value['total_order'],
                'order_commission' => c_format($value['total_commission'], false),
            );
        }

        
       
        $integration_click_amount = $this->db->query('SELECT '. $groupby . ' as groupby,SUM(amount) as total,COUNT(amount) as total_count FROM `wallet` WHERE is_action=1 AND type="external_click_commission" AND user_id='. $userid .' AND '. $_where . $current_year .' AND comm_from = "ex"  AND status > 0 GROUP BY '. $groupby .'   '. $orderBy )->result_array();
        foreach ($integration_click_amount as $value) {
            $chart[] = array(
                'key' => $value['groupby'],
                'action_commission' => c_format($value['total'], false),
                'action_count' => $value['total_count'],
            );
        }

        $week = [];
        $day = [];
        $year = [];
        for($i=1;$i<=53;$i++){ $week[] = "Week {$i}"; }
        for($i=1;$i<=31;$i++){ $day[date($i."-n-Y")] = date($i."-n-Y"); }
        for($i=2016;$i<=date("Y");$i++){ $year[$i] = $i; }

        $defaultKey = [
        	'month' => ['','January','February','March','April','May','June','July','August','September','October','November','December'],
        	'week' => $week,
        	'day' => $day,
        	'year' => $year,
        ];

        $allData = [];
        foreach ($chart as $key => $value) {
        	$DK = $defaultKey[$filter['group']][$value['key']];
        	$allData[$DK]['order_total'] += isset($value['order_total']) ? $value['order_total'] : 0;
        	$allData[$DK]['order_count'] += isset($value['order_count']) ? $value['order_count'] : 0;
        	$allData[$DK]['order_commission'] += isset($value['order_commission']) ? $value['order_commission'] : 0;
        	$allData[$DK]['action_commission'] += isset($value['action_commission']) ? $value['action_commission'] : 0;
        	$allData[$DK]['action_count'] += isset($value['action_count']) ? $value['action_count'] : 0;
        }

        foreach ($defaultKey[$filter['group']] as $key => $value) {
        	if($value){
	        	$json['order_total'][$value] = isset($allData[$value]['order_total']) ? $allData[$value]['order_total'] : 0; 
	        	$json['order_count'][$value] = isset($allData[$value]['order_count']) ? $allData[$value]['order_count'] : 0; 
	        	$json['order_commission'][$value] = isset($allData[$value]['order_commission']) ? $allData[$value]['order_commission'] : 0; 
	        	$json['action_commission'][$value] = isset($allData[$value]['action_commission']) ? $allData[$value]['action_commission'] : 0; 
	        	$json['action_count'][$value] = isset($allData[$value]['action_count']) ? $allData[$value]['action_count'] : 0; 
        	}
        }

        $json['keys'] = $defaultKey[$filter['group']];
        if ($filter['group'] == 'month') {
        	$json['keys'] = array_filter($defaultKey[$filter['group']]);
        }


        return $json;
	}
}