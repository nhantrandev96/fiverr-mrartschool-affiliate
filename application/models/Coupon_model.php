<?php
class Coupon_model extends MY_Model{
	    
	public function getCoupon($coupon_id){
        return $this->db->query("SELECT o.* FROM `coupon` o  WHERE o.coupon_id = {$coupon_id} ")->row_array();
    }

    public function deleteCoupon($coupon_id){
        $this->db->query("DELETE FROM `coupon` WHERE coupon_id = {$coupon_id} ");
    }
    
    public function getByCode($coupon_code){
        $now = date("Y-m-d");
        $sql ="
            SELECT o.* 
            FROM `coupon` o  
            WHERE 
            ((o.date_start = '0000-00-00' OR o.date_start <= '{$now}') AND (o.date_end = '0000-00-00' OR o.date_end >= '{$now}')) 
            AND o.status = '1'
            AND o.code = ". $this->db->escape($coupon_code);
        return $this->db->query($sql)->row_array();
    }
    public function getCoupons(int $user_id = 0){
        return $this->db->query("SELECT o.* FROM `coupon` o WHERE o.vendor_id = '{$user_id}' ")->result_array();
    }
    public function getUses($user_id,$coupon_code){
        return  $this->db->query("SELECT o.status FROM order_products  op LEFT JOIN `order` o ON o.id = op.order_id WHERE o.status =1 AND o.user_id = {$user_id} AND coupon_code = '{$coupon_code}'  ")->num_rows(); 
	}
    public function getCouponCount($coupon_id = 0,$id = 0){        
        $where = $clause_orders = '';
        if($id)
            $where = ' AND op.refer_id = "'.$id.'" ';
          
         return $this->db->query("SELECT 
                c.coupon_id FROM `coupon` c 
                LEFT JOIN  order_products op ON (c.code = op.coupon_code)
                WHERE NOT op.coupon_code = '' ".$where." GROUP BY op.order_id")->num_rows();
    }
}