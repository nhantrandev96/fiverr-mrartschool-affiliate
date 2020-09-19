<?php
class Form_model extends MY_Model{
	    
	public function getForm($form_id = 0){
        return $this->db->query("SELECT o.* FROM `form` o  WHERE o.form_id = ". (int)$form_id)->row_array();
    }
    public function getByCode($form_code){
        $now = date("Y-m-d");
        $sql =" SELECT o.* 
            FROM `form_coupon` o  
            WHERE ((o.date_start = '0000-00-00' OR o.date_start <= '{$now}') AND (o.date_end = '0000-00-00' OR o.date_end >= '{$now}')) 
            AND o.status = '1'
            AND o.code = ". $this->db->escape($form_code);
        return $this->db->query($sql)->row_array();
    }
    public function getFormCouponCount($form_id = 0,$id = 0){        
        $where = $clause_orders = '';
        if($id)
            $where = ' AND op.refer_id = "'.$id.'" ';
          
         return $this->db->query("SELECT 
                o.form_id FROM `form` o 
                LEFT JOIN  order_products op ON (o.form_id = op.form_id)
                LEFT JOIN form_coupon fc ON (fc.form_coupon_id = op.coupon_code)
                WHERE op.form_id = '".$form_id."' AND NOT op.coupon_code = '' ".$where."
                GROUP BY op.order_id")->num_rows();
    }
    public function getForms($id = 0, $filter = []){
        $where2 = $where = $clause_orders = '';
        if($id){
            $where = ' AND op.refer_id = "'.$id.'" ';
           // $where2 = ' AND (o.status != 0 OR o.vendor_id = "'.$id.'") ';
        }
        else $where = ' AND o.status != 0 ';

        if($user_type == 'admin') $clause = ' ';
        else if($id){
            $clause = " user_id = $id AND ";
           $clause_orders = " op.refer_id = {$id} AND ";
        }

        /*if (isset($filter['vendor_id'])) {
            $where2 .= ' AND o.vendor_id = '. (int)$filter['vendor_id'];
        }*/

        $start = 0; $limit = 20;
        if(isset($filter['start']) && (int)$filter['start']){
            $start = $filter['start'];
        }
        if(isset($filter['limit']) && (int)$filter['limit']){
            $limit = $filter['limit'];
        }


        $sql = "SELECT o.*,
            (SELECT SUM(op.commission)
                FROM `order` AS oo
                LEFT JOIN order_products op ON (oo.id = op.order_id)
                WHERE (
                        (oo.payment_method = 'bank_transfer' AND oo.status = 1) OR
                        (oo.payment_method != 'bank_transfer' AND oo.status > 0)
                    ) AND
                    op.form_id = o.form_id
                    {$where}
            ) as total_commission,
            (SELECT count(op.commission) FROM order_products AS op WHERE op.form_id = o.form_id {$where}) AS count_commission,
            (SELECT count(form_id) FROM form_action WHERE {$clause} form_id = o.form_id ) AS commition_click_count,
            (SELECT SUM(amount) FROM wallet WHERE {$clause} type = 'form_click_commission' AND reference_id = o.form_id GROUP BY reference_id) AS commition_click

            FROM `form` o
            
            WHERE 1 {$where2}
            LIMIT {$start}, {$limit}
        ";
        return $this->db->query($sql)->result_array();
    }
    public function getUses($user_id,$form_code){
        return  $this->db->query("SELECT o.status FROM order_products  op LEFT JOIN `order` o ON o.id = op.order_id WHERE o.status =1 AND o.user_id = {$user_id} AND form_code = '{$form_code}'  ")->num_rows(); 
	}
    public function getFormCouponname($form_coupon_id = 0){
        $name = $this->db->query("SELECT o.name FROM `form_coupon` o  WHERE o.form_coupon_id = {$form_coupon_id} ")->row_array();
        if($name)
            return $name['name'];
        return '';
    }  
    public function getFormCoupon($form_coupon_id){
        return $this->db->query("SELECT o.* FROM `form_coupon` o  WHERE o.form_coupon_id = {$form_coupon_id} ")->row_array();
    }  
    public function getFormCouponCode($form_coupon_id){
        $code =  $this->db->query("SELECT o.code FROM `form_coupon` o  WHERE o.form_coupon_id = ". (int)$form_coupon_id)->row_array();
        if($code)
            return $code['code'];
        return '';
    }  
    public function getCouponByCode($coupon_code){
        return $this->db->query("SELECT o.* FROM `form_coupon` o  WHERE o.code = ". $this->db->escape($coupon_code))->row_array();
    }  
    public function getFormCoupons($user_id = 0){
        return $this->db->query("SELECT o.* FROM `form_coupon` o WHERE o.vendor_id = ". (int)$user_id)->result_array();
    }
    public function formcount(){
        return $this->db->count_all_results('form');
    }
    public function deleteforms($id = 0){
         if (!empty($id)) {
            $this->db->where('form_id', $id);
            return $this->db->delete('form');
        }
        return false;
    }
}