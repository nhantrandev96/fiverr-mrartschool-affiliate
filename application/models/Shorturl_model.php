<?php
class Shorturl_model extends MY_Model{   
	public function getByCode($code){
        return $this->db->query("SELECT * FROM `short_urls` WHERE short_code = '{$code}'")->row_array();
    }

    public function getByUrl($url){
        return $this->db->query("SELECT * FROM `short_urls` WHERE long_url = ". $this->db->escape($url))->row_array();
    }
}