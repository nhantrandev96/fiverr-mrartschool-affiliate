<?php 
class Admin_model extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	
	function adminLogin(){
		$arrayPost = $this->input->post();
		
		$name = isset($arrayPost['username'])?$arrayPost['username']:'';
		$password = isset($arrayPost['password'])?md5($arrayPost['password']):'';
		$this->db->select('id,name,password');
		$this->db->from('admin_user');
		$this->db->where(array('name'=>$name, 'password'=>$password, 'admin_status'=>'1'));
		$query = $this->db->get();
		$fetchRow = $query->row();
		if($query->num_rows()>0){
			$this->session->set_userdata(array('admin'=>array(
			'name'=>$fetchRow->name, 
			'id'=>$fetchRow->id, 
			)));
			return 1;
			} else {
			return 0;
		}
	}
	
	function getUsers($id){
		if($id && $id > 0){
			$this->db->select("*")->from("users")->where(array('id'=>$id));
			
			}else{
			$this->db->select("*")->from("users")->order_by('id','desc');
		}
		
		$query = $this->db->get();
		$fetchRows = $query->result();
		return $fetchRows;
	}
	function getAdminUser($param){			
        $this->db->select("*")->from("admin_user")->where(array('email'=>$param));
		$query = $this->db->get();
		$fetchRows = $query->result();
		return $fetchRows;
	}
	
	
	function deleteUser($id){
		$this ->db->where('id', $id);
		$this ->db->delete('users');
		return $this->db->affected_rows();
	}
}		