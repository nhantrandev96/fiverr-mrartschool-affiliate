
<?php
	class Commisioninfo_model extends CI_Model 
	{
	    public function __construct()
	    {
	    	parent::__construct();
	    }
		public function set_commission($postData)
	    {
	        $this->db->insert('commsion', $postData); 
	        return ($this->db->affected_rows() != 1) ? 0 : $this->db->insert_id();
	    }
	    function getComission($param)
		{
		    
		    $this->db->select("*")->from("commsion")->where(array('userID'=>$param));
		    $query = $this->db->get();
			$fetchRows = $query->result();
			return $fetchRows;
		}
		function getTeam($param)
		{
		    
		    $this->db->select("*")->from("users")->where(array('refid'=>$param));
		    $query = $this->db->get();
			$fetchRows = $query->result();
			return $fetchRows;
		}
		function getRefComission($param)
		{
		    
		    $this->db->select("*")->from("commsion")->where(array('RefiD'=>$param));
		    $query = $this->db->get();
			$fetchRows = $query->result();
			return $fetchRows;
		}
	}
?>
      