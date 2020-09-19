<?php
class Lastmsg_model extends MY_Model{
	public $_table = 'last_seen';
	public $belongs_to = array( 'user' => array('model'=>'user_model'));
	public function update_lastSeen($user=0)
	{
		$last_msg = $this->db->where('to', $user)->order_by('time', 'desc')->get('messages', 1)->row();
		$msg = !empty($last_msg) ? $last_msg->id : 0;
		$record = $this->get_by('user_id', $user);
		$details = array('user_id' => $user,'message_id' => $msg);
		if(empty($record))
		{
			$this->insert($details);
		}else{
			$this->update($record->id, $details);
		}
	}
}