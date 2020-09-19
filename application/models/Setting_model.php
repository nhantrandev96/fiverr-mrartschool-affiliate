<?php	
class Setting_model extends MY_Model{
	public function clear($setting_type){
		$this->db->query('DELETE FROM setting WHERE setting_type= "'. $setting_type .'" ');
	}

	public function save($setting_type, $data){
		foreach ($data as $key => $value) {
			$this->db->where('setting_type',$setting_type);
			$this->db->where('setting_key',$key);
			$q = $this->db->get('setting');
			if (is_array($value)) {
				$value = json_encode($value);
			}
			/*if ($setting_type == 'paymentsetting' && $key == 'bank_transfer_instruction') {
				 echo "<pre>"; print_r($q->row()); echo "</pre>";die; 
			}*/
			if ( $q->num_rows() > 0 ){
				$this->db->where('setting_id',$q->row()->setting_id );
				$this->db->update('setting', array(
					'setting_value' => $value,
					'setting_ipaddress' => $_SERVER['REMOTE_ADDR'],
				));
			} else {
				$this->db->insert('setting',array(
					'setting_value' => $value,
					'setting_key' => $key,
					'setting_status' => 1,
					'setting_type' => $setting_type,
					'setting_ipaddress' => $_SERVER['REMOTE_ADDR'],
				));
			}
		}
	}
	
}