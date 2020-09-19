<?php	
class Income_model extends MY_Model{

	public function get_report($filter){
		$where = '';

		if (isset($filter['user_id']) && $filter['user_id'] > 0) {
			$where .= " AND u.id= ". (int)$filter['user_id'];
		}

		$sql = "
			SELECT
				u.id,
				CONCAT(firstname,' ',lastname) as name,
				c.sortname as country_code,
				u.username,
				(SELECT COUNT(id) FROM integration_clicks_action WHERE is_action=1 AND user_id = u.id) as external_action_click,
				(SELECT SUM(amount) FROM wallet WHERE type IN ('external_click_commission') AND is_action=1 AND user_id = u.id) as action_click_commission
			FROM users u
				LEFT JOIN countries c ON u.Country = c.id
			WHERE 
				u.type = 'user' {$where}
		";

		$users = $this->db->query($sql)->result_array();
			
		$data['data'] = array();
		foreach ($users as $key => $value) {
			$filter['user_id'] = $value['id'];

			$totals = $this->Wallet_model->getTotals($filter,true);
			/* if($value['id'] == 7){
						echo "<pre>"; print_r($totals); echo "</pre>";die; 
					}*/
				
			$data['data'][] = array(
				'id'                 => $value['id'],
				'name'               => $value['name'],
				'country_code'               => $value['country_code'],
				'username'           => $value['username'],
				'total_commission'   => c_format($totals['all_clicks_comm'] + $totals['all_sale_comm'] + + $totals['integration']['action_amount']),
				'total_click'        => (int)$totals['integration']['click_count'],
				'total_click_amount' => c_format($totals['integration']['click_amount']),
				'total_sale_count'   => (int)$totals['total_sale_count'],
				'total_sale_amount'  => c_format($totals['total_sale_amount']),
				'total_sale_comm'     => c_format($totals['all_sale_comm']),
				'wallet_accept_amount'     => c_format($totals['wallet_accept_amount']),
				'external_action_click'     => (int)($totals['integration']['action_count']),
				'action_click_commission'     => c_format($totals['integration']['action_amount']),
			);
		}


		return $data;
	}
}