<?php
class Message_model extends MY_Model{
	public $message_rules = array(
		    'message' => array (
					'field' => 'message',
					'label' => 'message',
					'rules' => 'trim|required|xss_clean'
			)
		);
	public function conversation($user, $chatbuddy, $limit = 5){
        $this->db->where('from', $user);
        $this->db->where('to', $chatbuddy);
        $this->db->or_where('from', $chatbuddy);
        $this->db->where('to', $user);
        $this->db->order_by('id', 'desc');
        $messages = $this->db->get('messages', $limit);
        $this->db->where('to', $user)->where('from',$chatbuddy)->update('messages', array('is_read'=>'1'));
        return $messages->result();
	}
	public function thread_len($user, $chatbuddy){
        $this->db->where('from', $user);
        $this->db->where('to', $chatbuddy);
        $this->db->or_where('from', $chatbuddy);
        $this->db->where('to', $user);
        $this->db->order_by('id', 'desc');
        $messages = $this->db->count_all_results('messages');
        return $messages;
	}
	public function latest_message($user, $last_seen){
		$message  =  $this->db->where('to', $user)
							  ->where('id  > ', $last_seen)
							  ->order_by('time', 'desc')
							  ->get('messages', 1);
		if($message->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	public function new_messages($user, $last_seen){
		$messages  =  $this->db->where('to', $user)
							  ->where('id  > ', $last_seen)
							  ->order_by('time', 'asc')
							  ->get('messages');
		return $messages->result();
	}
	public function unread($user){
		$messages  =  $this->db->where('to', $user)
							  ->where('is_read', '0')
							  ->order_by('time', 'asc')
							  ->get('messages');
		return $messages->result();
	}
	public function mark_read(){
		$id = $this->input->post('id',true);
		$this->db->where('id', $id)->update('messages', array('is_read'=>'1'));
	}
	public function unread_per_user($id, $from){
		$count  =  $this->db->where('to', $id)
							->where('from', $from)
							->where('is_read', '0')
							->count_all_results('messages');
		return $count;
	}
}