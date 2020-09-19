<?php
class User_model extends MY_Model{
	protected $_table = 'users';
	public $register_rules = array(
		'firstname' => array (
			'field' => 'firstname',
			'label' => 'first name',
			'rules' => 'trim|required|xss_clean'
		),
		'lastname' => array (
			'field' => 'lastname',
			'label' => 'last name',
			'rules' => 'trim|required|xss_clean'
		),
		'email' => array (
			'field' => 'email',
			'label' => 'email',
			'rules' => 'trim|required|valid_email|callback_unique_email|xss_clean'
		),
		'username' => array (
			'field' => 'username',
			'label' => 'username',
			'rules' => 'trim|required|callback_unique_username|xss_clean'
		),
		'password' => array (
			'field' => 'password',
			'label' => 'password',
			'rules' => 'trim|required|matches[cpassword]|xss_clean'
		),
        'cpassword' => array (
            'field' => 'cpassword',
            'label' => 'confirm password',
            'rules' => 'trim|required|matches[password]|xss_clean'
        ),
        'address' => array (
            'field' => 'address',
            'label' => 'address',
            'rules' => 'trim|required|xss_clean'
        ),
        'state' => array (
            'field' => 'state',
            'label' => 'state',
            'rules' => 'trim|required'
        ),
        'country' => array (
            'field' => 'country',
            'label' => 'country',
            'rules' => 'trim|required'
        ),
        'paypal_email' => array (
            'field' => 'paypal_email',
            'label' => 'paypal email',
            'rules' => 'trim|required|valid_email|callback_unique_email|xss_clean'
        ),
        'phone_number' => array (
            'field' => 'phone_number',
            'label' => 'phone number',
            'rules' => 'trim|required|regex_match[/^[0-9]{10}$/]'
        ),
		'alternate_phone_number' => array (
			'field' => 'alternate_phone_number',
			'label' => 'alternate phone number',
			'rules' => 'trim|required|regex_match[/^[0-9]{10}$/]'
		)
	);
	public $login_rules = array(
		'username' => array (
			'field' => 'username',
			'label' => 'username',
			'rules' => 'trim|required'
		),
		'password' => array (
			'field' => 'password',
			'label' => 'password',
			'rules' => 'trim|required'
		)
	);	
	public $profile_rules = array(
		'firstname' => array (
			'field' => 'firstname',
			'label' => 'firstname',
			'rules' => 'trim|required'
		),
		'lastname' => array (
			'field' => 'lastname',
			'label' => 'lastname',
			'rules' => 'trim|required'
		),
		'email' => array (
			'field' => 'email',
			'label' => 'email',
			'rules' => 'trim|required|valid_email'
		)
	);	
	public $password_rules = array(
		'current_password' => array (
			'field' => 'current_password',
			'label' => 'current password',
			'rules' => 'trim|required|callback_password_check'
		),
		'new_password' => array (
			'field' => 'new_password',
			'label' => 'new password',
			'rules' => 'trim|required'
		),
		'confirm_newpassword' => array (
			'field' => 'confirm_newpassword',
			'label' => 'confirm password',
			'rules' => 'trim|required|matches[new_password]'
		)
	);	

	function login($username) {
		return $this->db->where('username',$username)
		->get('users')->row_array();
		/*->where('status',1)*/
	}
	
    function getCountries(){
        return $this->db->select('id,name')->from('countries')->get()->result_array();
    }
    function getState($country_id){
		return $this->db->select('id,name')->from('states')->where('country_id', $country_id)->get()->result_array();
    }
    function update_user_login($user_id) {
        return $this->db->where('id', $user_id)->update('users', array('online' => '1'));
	}
	function update_user($user_id,$url) {
		return $this->db->where('id', $user_id)->update('users',$url);
	}

	function get_user_by_id($usr_id)
	{
		return $this->db->get_where('users',array('id'=>$usr_id))->row_array();
	}
	function get_user_by_type($type)
	{
		return $this->db->get_where('users',array('type'=>$type))->row_array();
	}

	function checkmail($mail)
	{
		return $this->db->get_where('users',array('email'=>$mail))->row_array();
	}
	function checkuser($username)
	{
		return $this->db->get_where('users',array('username'=>$username))->row_array();
	}

	function getUserCountry(){
		$this->db->select('count(*) as num, countries.name');
		$this->db->from('users');
		$this->db->group_by('users.country');
		$this->db->join('countries','users.country=countries.id');
		$query = $this->db->get();
		return $query->result();
	}
	function custom_query()
	{
//return $this->db->query("ALTER TABLE users ADD google_id VARCHAR(255) NOT NULL, ADD facebook_id VARCHAR(255) NOT NULL, ADD twitter_id VARCHAR(255) NOT NULL");
	}
	function getAllNotification($user_id = null){
		$this->db->from('notification');
		if(!empty($user_id)){
			$this->db->where('notification_view_user_id', $user_id);
		}
		$this->db->where('notification_is_read', 0);
		$this->db->order_by("notification_created_date", "desc");
		$query = $this->db->get();
		return $query->result_array();
	}
	function getAllNotificationPaging($notification_viewfor = null,$user_id = null,$limit, $start){
		$this->db->from('notification');
		$this->db->limit($limit, $start);
		if(!empty($notification_viewfor)){
			$this->db->where('notification_viewfor', $notification_viewfor);
		}
		if(!empty($user_id)){
			$this->db->where(" (notification_view_user_id = {$user_id} OR notification_view_user_id = 'all')  ",NULL,false);
		}

		$this->db->order_by("notification_created_date", "desc");
		$data['notifications'] = $this->db->get()->result_array();

		$this->db->from('notification');
		if(!empty($user_id)){
			$this->db->where(" (notification_view_user_id = {$user_id} OR notification_view_user_id = 'all')  ",NULL,false);
		}
		if(!empty($notification_viewfor)){
			$this->db->where('notification_viewfor', $notification_viewfor);
		}
		$data['total'] = $this->db->count_all_results();
		return $data;
	}
}