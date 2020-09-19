<?php
class Order_model extends MY_Model{
    public $status = array(
        '12'  =>  'Waiting For Payment',
        '0'  =>  'Waiting For Payment',
        '1'  =>  'Complete',
        '2'  =>  'Total not match',
        '3'  =>  'Denied',
        '4'  =>  'Expired',
        '5'  =>  'Failed',
        '6'  =>  'Pending',
        '7'  =>  'Processed',
        '8'  =>  'Refunded',
        '9'  =>  'Reversed',
        '10' =>  'Voided',
        '11' =>  'Canceled Reversal',
    );

    public function changeStatus($order_id, $status , $comment = ''){
        $this->load->model('Mail_model');

        $historyData = array(
            'order_id'        => $order_id,
            'paypal_status'   => $status,
            'comment'         => $comment,
            'payment_mode'    => '',
            'history_type'    => 'order',
            'created_at'      => date("Y-m-d H:i:s"),
            'order_status_id' => $status,
        );

        $this->db->insert('orders_history',$historyData);
        $this->db->set('status', $status);
        $this->db->where('id', $order_id);
        $this->db->update('order');

        if($status == 1){
            $check = $this->db->query("SELECT * FROM  `wallet` WHERE status = 0 AND type IN('sale_commission','refer_sale_commission','vendor_sale_commission') AND reference_id = {$order_id} ")->num_rows();
            if($check){
                $this->db->query("UPDATE `wallet` SET status =1 WHERE user_id != 1 AND type IN('sale_commission','refer_sale_commission','vendor_sale_commission') AND reference_id = {$order_id} ");

                $this->db->query("UPDATE `wallet` SET status = 3 WHERE user_id = 1 AND type IN('sale_commission','refer_sale_commission','vendor_sale_commission') AND reference_id = {$order_id} ");
                $this->Mail_model->send_commition_mail($order_id, true);
            }
        }

        $this->Mail_model->send_order_mail($order_id);
    }

    public function getAllClickLogs($filter = array()){
        $store_setting = $this->Product_model->getSettings('store');
        $where1  = $where2 = $where3 = '';

        if(!$store_setting['status']){
            $where1 .= " AND 1=2 ";
        }

        if(isset($filter['user_id'])){
            $where1 .= " AND (ic.user_id = ". (int)$filter['user_id'] ." OR ic.vendor_id = ". (int)$filter['user_id'] .")";
            //$where1 .= " AND ic.user_id = ". (int)$filter['user_id'];
            $where2 .= " AND pa.user_id = ". (int)$filter['user_id'];
            $where3 .= " AND op.refer_id = ". (int)$filter['user_id'];
        }

        $union1 = array(
            '"ex" as type',
            'u.firstname',
            'u.lastname ',
            'ic.user_id',
            'ic.created_at',
            'ic.country_code',
            'ic.ip',

            'ic.id',
            'ic.base_url',
            'ic.link',
            'ic.agent',
            'ic.browserName',
            'ic.browserVersion',
            'ic.systemString',
            'ic.osPlatform',
            'ic.osVersion',
            'ic.osShortVersion',
            'ic.isMobile',
            'ic.mobileName',
            'ic.osArch',
            'ic.isIntel',
            'ic.isAMD',
            'ic.isPPC',
            'ic.click_id',
            'ic.click_type',

            '"action_id" as action_id',
            '"action_type" as action_type',
            '"product_id" as product_id',
            '"viewer_id" as viewer_id',
            '"counter" as counter',
            '"pay_commition" as pay_commition',  

            '"status" as status',
            '"txn_id" as txn_id',
            '"address" as address',
            '"country_id" as country_id',
            '"state_id" as state_id',
            '"city" as city',
            '"zip_code" as zip_code',
            '"phone" as phone',
            '"payment_method" as payment_method',
            '"shipping_cost" as shipping_cost',
            '"total" as total',
            '"coupon_discount" as coupon_discount',
            '"total_commition" as total_commition',
            '"shipping_charge" as shipping_charge',
            '"currency_code" as currency_code',
            '"allow_shipping" as allow_shipping',
            '"files" as files',
            '"comment" as comment',
        );

        $union2 = array(
            '"store" as type',
            'u.firstname',
            'u.lastname ',
            'pa.user_id',
            'pa.created_at',
            'pa.country_code',
            'pa.user_ip as ip',

            '"id" as id',
            '"base_url" as base_url',
            '"link" as link',
            '"agent" as agent',
            '"browserName" as browserName',
            '"browserVersion" as browserVersion',
            '"systemString" as systemString',
            '"osPlatform" as osPlatform',
            '"osVersion" as osVersion',
            '"osShortVersion" as osShortVersion',
            '"isMobile" as isMobile',
            '"mobileName" as mobileName',
            '"osArch" as osArch',
            '"isIntel" as isIntel',
            '"isAMD" as isAMD',
            '"isPPC" as isPPC',
            '"click_id" as click_id',
            '"click_type" as click_type',

            'pa.action_id',
            'pa.action_type',
            'pa.product_id',
            'pa.viewer_id',
            'pa.counter',
            'pa.pay_commition',

            '"status" as status',
            '"txn_id" as txn_id',
            '"address" as address',
            '"country_id" as country_id',
            '"state_id" as state_id',
            '"city" as city',
            '"zip_code" as zip_code',
            '"phone" as phone',
            '"payment_method" as payment_method',
            '"shipping_cost" as shipping_cost',
            '"total" as total',
            '"coupon_discount" as coupon_discount',
            '"total_commition" as total_commition',
            '"shipping_charge" as shipping_charge',
            '"currency_code" as currency_code',
            '"allow_shipping" as allow_shipping',
            '"files" as files',
            '"comment" as comment',
        );

        $union3 = array(
            '"order" as type',
            'u.firstname',
            'u.lastname ',
            'o.user_id',
            'o.created_at',
            'o.country_code',
            'o.ip',

            'o.id',

            '"base_url" as base_url',
            '"link" as link',
            '"agent" as agent',
            '"browserName" as browserName',
            '"browserVersion" as browserVersion',
            '"systemString" as systemString',
            '"osPlatform" as osPlatform',
            '"osVersion" as osVersion',
            '"osShortVersion" as osShortVersion',
            '"isMobile" as isMobile',
            '"mobileName" as mobileName',
            '"osArch" as osArch',
            '"isIntel" as isIntel',
            '"isAMD" as isAMD',
            '"isPPC" as isPPC',
            '"click_id" as click_id',
            '"click_type" as click_type',

            '"action_id" as action_id',
            '"action_type" as action_type',
            '"product_id" as product_id',
            '"viewer_id" as viewer_id',
            '"counter" as counter',
            '"pay_commition" as pay_commition', 

            'o.status',
            'o.txn_id',
            'o.address',
            'o.country_id',
            'o.state_id',
            'o.city',
            'o.zip_code',
            'o.phone',
            'o.payment_method',
            'o.shipping_cost',
            'o.total',
            'o.coupon_discount',
            'o.total_commition',
            'o.shipping_charge',
            'o.currency_code',
            'o.allow_shipping',
            'o.files',
            'o.comment',
        );


        $select1 = implode(",", $union1);         
        $union_query1 = "
            SELECT {$select1} FROM `integration_clicks_logs` ic 
                LEFT JOIN users u ON u.id = ic.user_id
            WHERE 1 {$where1}
        ";

        $select2 = implode(",", $union2);
        $union_query2 = "
            SELECT {$select2}
            FROM product_action pa
            LEFT JOIN users u ON u.id = pa.user_id  
            WHERE 1 {$where2}
        ";

        $union2[0] = '"store_other_aff" as type';
        $select5 = implode(",", $union2);
        $union_query5 = "
            SELECT {$select5}
            FROM product_action pa
            LEFT JOIN users u ON u.id = pa.user_id  
            LEFT JOIN product_affiliate paff ON (paff.product_id = pa.product_id) 
            WHERE paff.user_id= ". (int)$filter['user_id'] ."
        ";

        $union2[0] = '"store_admin" as type';
        $select4 = implode(",", $union2);
        $union_query4 = "
            SELECT {$select4}
            FROM product_action_admin pa
            LEFT JOIN users u ON u.id = pa.user_id  
            LEFT JOIN product_affiliate paff ON (paff.product_id = pa.product_id)
            WHERE  paff.user_id= ". (int)$filter['user_id'] ."
        ";

        $select3 = implode(",", $union3);
        $union_query3 = "
            SELECT {$select3}
            FROM `order` o 
                LEFT JOIN order_products op ON op.order_id = o.id
                LEFT JOIN users u ON u.id = o.user_id
            WHERE 1 AND vendor_id = 0 {$where3}
        ";

        
        $union = "SELECT SQL_CALC_FOUND_ROWS * FROM ( 
        ({$union_query1}) UNION ALL 
        ({$union_query2}) UNION ALL 
        ({$union_query3}) UNION ALL 
        ({$union_query5}) UNION ALL 
        ({$union_query4}) ) as tmp";
        $union.= " ORDER BY created_at DESC ";
        if (isset($filter['page'],$filter['limit'])) {
            $offset = (($filter['page']-1) * $filter['limit']);
            $union.= " LIMIT {$offset},". $filter['limit'];
        }
         
        $clicks = $this->db->query($union)->result_array();
        $total = $this->db->query("SELECT FOUND_ROWS() AS total")->row()->total;

        $data = array();
        foreach ($clicks as $key => $value) {
            if($value['type'] == 'store'|| $value['type'] == 'store_admin' || $value['type'] == 'store_other_aff'){ 
                $data[] = array(
                    'type'          => $value['type'],
                    'firstname'     => $value['firstname'],
                    'lastname'      => $value['lastname'],
                    'action_id'     => $value['action_id'],
                    'action_type'   => $value['action_type'],
                    'product_id'    => $value['product_id'],
                    'user_id'       => $value['user_id'],
                    'ip'            => $value['user_ip'],
                    'viewer_id'     => $value['viewer_id'],
                    'counter'       => $value['counter'],
                    'pay_commition' => $value['pay_commition'],
                    'created_at'    => $value['created_at'],
                    'country_code'  => $value['country_code'],
                    'flag'           => "<img class='small-flag' title='". $value['country_code'] ."' src='". base_url('assets/vertical/assets/images/flags/'. strtolower($value['country_code'])) .".png'>",
                );
            }
            else if($value['type'] == 'order'){ 
                $data[] = array(
                    'type'            => $value['type'],
                    'status'          => $value['status'],
                    'txn_id'          => $value['txn_id'],
                    'address'         => $value['address'],
                    'country_id'      => $value['country_id'],
                    'state_id'        => $value['state_id'],
                    'city'            => $value['city'],
                    'zip_code'        => $value['zip_code'],
                    'phone'           => $value['phone'],
                    'payment_method'  => $value['payment_method'],
                    'shipping_cost'   => $value['shipping_cost'],
                    'total'           => $value['total'],
                    'coupon_discount' => $value['coupon_discount'],
                    'total_commition' => $value['total_commition'],
                    'shipping_charge' => $value['shipping_charge'],
                    'currency_code'   => $value['currency_code'],
                    'allow_shipping'  => $value['allow_shipping'],
                    'files'           => $value['files'],
                    'comment'         => $value['comment'],
                    'firstname'       => $value['firstname'],
                    'lastname'        => $value['lastname'],
                    'user_id'         => $value['user_id'],
                    'created_at'      => $value['created_at'],
                    'country_code'    => $value['country_code'],
                    'ip'              => $value['ip'],
                    'id'              => $value['id'],
                    'flag'            => "<img class='small-flag' title='". $value['country_code'] ."' src='". base_url('assets/vertical/assets/images/flags/'. strtolower($value['country_code'])) .".png'>",
                );
            } else {
                $data[] = array(
                    'type'          => $value['type'],
                    'id'             => $value['id'],
                    'base_url'       => $value['base_url'],
                    'link'           => $value['link'],
                    'agent'          => $value['agent'],
                    'browserName'    => $value['browserName'],
                    'browserVersion' => $value['browserVersion'],
                    'systemString'   => $value['systemString'],
                    'osPlatform'     => $value['osPlatform'],
                    'osVersion'      => $value['osVersion'],
                    'osShortVersion' => $value['osShortVersion'],
                    'isMobile'       => $value['isMobile'],
                    'mobileName'     => $value['mobileName'],
                    'osArch'         => $value['osArch'],
                    'isIntel'        => $value['isIntel'],
                    'isAMD'          => $value['isAMD'],
                    'isPPC'          => $value['isPPC'],
                    'ip'             => $value['ip'],
                    'country_code'   => $value['country_code'],
                    'created_at'     => date("d-m-Y h:i A",strtotime($value['created_at'])),
                    'click_id'       => $value['click_id'],
                    'username'       => $value['username'],
                    'click_type'     => str_replace("_", " ", ucfirst($value['click_type'])),
                    'flag'           => "<img class='small-flag' title='". $value['country_code'] ."' src='". base_url('assets/vertical/assets/images/flags/'. strtolower($value['country_code'])) .".png'>",
                );
            }
        }
        
        return array($data,$total);
    }

    public function getAllOrders($filter = array(), $addShipping = true){
        $store_setting = $this->Product_model->getSettings('store');
        $where1  = $where2 = '';

        if(!$store_setting['status']){
            $where1 .= " AND 1=2 ";
        }
       
        if(isset($filter['user_id'])){
            $where1 .= " AND (op.vendor_id = ". (int)$filter['user_id'] ." OR op.refer_id = ". (int)$filter['user_id'] .")";
            $where2 .= " AND (io.user_id = ". (int)$filter['user_id'] ." OR io.vendor_id = ". (int)$filter['user_id'] .") ";
        }
        if(isset($filter['o_status'])){
            $where1 .= " AND o.status = ". (int)$filter['o_status'];
            if((int)$filter['o_status'] != 1){
                $where2 .= " AND 1=2 ";
            }
        }

        if(isset($filter['o_status_gt'])){
            $where1 .= " AND o.status >= ". (int)$filter['o_status'];
        }

        $union1 = array(
            '"store" as type',
            '(SELECT status FROM wallet WHERE wallet.reference_id = op.id AND comm_from="store" AND type="sale_commission" AND op.refer_id = wallet.user_id LIMIT 1) as wallet_status',
            'o.id',
            'o.status',
            'o.user_id',
            'o.total',
            'o.ip',
            'o.country_code',
            'u.firstname',
            'u.lastname ',
            'o.created_at',    

            '"order_id" as order_id',
            '"product_ids" as product_ids',
            '"currency" as currency',
            '"commission_type" as commission_type',
            '"commission" as commission',
            '"base_url" as base_url',
            '"ads_id" as ads_id',
            '"script_name" as script_name',       

            'o.txn_id',
            'o.address',
            'o.country_id',
            'o.state_id',
            'o.city',
            'o.zip_code',
            'o.phone',
            'o.payment_method',
            'o.shipping_cost',
            'o.coupon_discount',
            'o.total_commition',
            'o.shipping_charge',
            'o.currency_code',
            'o.allow_shipping',
            'o.files',
            'o.comment',
            'sum(op.total) AS total_sum',
            '(SELECT paypal_status FROM orders_history WHERE orders_history.order_id = o.id ORDER BY id DESC LIMIT 1) as last_status',
        );

        $union2 = array(
            '"ex" as type',
            '(SELECT status FROM wallet WHERE wallet.id = io.affiliate_tran) as wallet_status',
            'io.id',
            'io.status',
            'io.user_id',
            'io.total',
            'io.ip',
            'io.country_code',
            'u.firstname',
            'u.lastname ',
            'io.created_at',

            'io.order_id',
            'io.product_ids',
            'io.currency',
            'io.commission_type',
            'io.commission',
            'io.base_url',
            'io.ads_id',
            'io.script_name',

            '"txn_id" as txn_id',
            '"address" as address',
            '"country_id" as country_id',
            '"state_id" as state_id',
            '"city" as city',
            '"zip_code" as zip_code',
            '"phone" as phone',
            '"payment_method" as payment_method',
            '"shipping_cost" as shipping_cost',
            '"coupon_discount" as coupon_discount',
            '"total_commition" as total_commition',
            '"shipping_charge" as shipping_charge',
            '"currency_code" as currency_code',
            '"allow_shipping" as allow_shipping',
            '"files" as files',
            '"comment" as comment',
            '"total_sum" as total_sum',
            '"last_status" as last_status',
        );

        $select1 = implode(",", $union1);
        $union_query1 = "
            SELECT {$select1} FROM `order` o 
                LEFT JOIN users u ON u.id = o.user_id   
                LEFT JOIN order_products op ON op.order_id = o.id
            WHERE o.status > 0 {$where1} 
            GROUP BY o.id
            ORDER BY o.id DESC
        ";
        
        $select2 = implode(",", $union2);
        $union_query2 = "
            SELECT {$select2} 
            FROM integration_orders io
            LEFT JOIN users u ON u.id = io.user_id  
            WHERE 1 {$where2}
        ";
        
        $union = "
            SELECT 
                SQL_CALC_FOUND_ROWS * 
            FROM (({$union_query1}) 
            UNION ALL ({$union_query2})) as tmp";

        $union.= " ORDER BY created_at DESC ";

        if (isset($filter['page'],$filter['limit'])) {
            $offset = (($filter['page']-1) * $filter['limit']);
            $union.= " LIMIT {$offset},". $filter['limit'];
        }
        $orders = $this->db->query($union)->result_array();
        $total = $this->db->query("SELECT FOUND_ROWS() AS total")->row()->total;

        $data = array();
        foreach ($orders as $key => $value) {
            if($value['type'] == 'store'){
                $products        = $this->Order_model->getProducts($value['id'],['vendor_or_refer_id' => $filter['user_id']]);
                $payment_history = $this->Order_model->getHistory($value['id']);
                $order_history   = $this->Order_model->getHistory($value['id'], 'order');
                $totals = $this->Order_model->getTotals($products,$value);

                $commission_amount = 0;
                foreach ($products as $key => $p) {
                    $commission_amount += ((float)$p['commission'] + (float)$p['admin_commission']);
                }

                $total_sum = $addShipping ? ($value['total_sum'] + $value['shipping_cost']) : $value['total_sum'];

                $data[] = array(
                    'id'                 => $value['id'],
                    'status'             => $value['status'],
                    'wallet_status'             => $value['wallet_status'],
                    'commission_amount'  => (float)$commission_amount,
                    'type'               => $value['type'],
                    'txn_id'             => $value['txn_id'],
                    'user_id'            => $value['user_id'],
                    'address'            => $value['address'],
                    'country_id'         => $value['country_id'],
                    'state_id'           => $value['state_id'],
                    'city'               => $value['city'],
                    'zip_code'           => $value['zip_code'],
                    'phone'              => $value['phone'],
                    'payment_method'     => $value['payment_method'],
                    'shipping_cost'      => $value['shipping_cost'],
                    'total'              => $value['total'],
                    'coupon_discount'    => $value['coupon_discount'],
                    'total_commition'    => $value['total_commition'],
                    'shipping_charge'    => $value['shipping_charge'],
                    'currency_code'      => $value['currency_code'],
                    'created_at'         => $value['created_at'],
                    'allow_shipping'     => $value['allow_shipping'],
                    'ip'                 => $value['ip'],
                    'country_code'       => $value['country_code'],
                    'files'              => $value['files'],
                    'comment'            => $value['comment'],
                    'firstname'          => $value['firstname'],
                    'lastname'           => $value['lastname'],
                    'total_sum'          => $total_sum,
                    'last_status'        => $value['last_status'],
                    'products'           => $products,
                    'order_history'      => $order_history,
                    'payment_history'    => $payment_history,
                    'totals'             => $totals,
                    'order_country_flag' => '<img style="width: 20px;margin: 0 10px;" src="'. base_url('assets/vertical/assets/images/flags/'. strtolower($value['country_code'])  ) .'.png"> IP: '. $value['ip'],
                );
            } else{
                $data[] = array(
                    'id'              => $value['id'],
                    'wallet_status'            => $value['wallet_status'],
                    'type'            => $value['type'],
                    'order_id'        => $value['order_id'],
                    'product_ids'     => $value['product_ids'],
                    'total'           => $value['total'],
                    'currency'        => $value['currency'],
                    'user_id'         => $value['user_id'],
                    'commission_type' => $value['commission_type'],
                    'commission'      => $value['commission'],
                    'ip'              => $value['ip'],
                    'country_code'    => $value['country_code'],
                    'base_url'        => $value['base_url'],
                    'ads_id'          => $value['ads_id'],
                    'script_name'     => $value['script_name'],
                    'created_at'      => date("d-m-Y h:i A", strtotime($value['created_at'])),
                    'user_name'       => $value['firstname'] ." " .$value['lastname'],
                    'order_country_flag' =>  '<img style="width: 20px;margin: 0 10px;" src="'. base_url('assets/vertical/assets/images/flags/'. strtolower($value['country_code'])  ) .'.png"> IP: '. $value['ip'],
                );
            }
        }
        
        return array($data,$total);
    }

    public function getCount($filter = array()){
        $this->db->where('o.status > 0');
        if(isset($filter['affiliate_id'])){
            $this->db->join("order_products op",'o.id = op.order_id','left');
            $this->db->where('op.refer_id', (int)$filter['affiliate_id']) ;
        }
        if(isset($filter['user_id'])){
            $this->db->where('o.user_id', (int)$filter['user_id']) ;
        }
        return $this->db->count_all_results('order o');
    }
    public function getSale($filter = array()){
        $this->db->where('o.status > 0');
            $this->db->join("order_products op",'o.id = op.order_id','left');
        if(isset($filter['affiliate_id'])){
            $this->db->where('op.refer_id', (int)$filter['affiliate_id']) ;
        }
            $this->db->select_sum('op.total');
        return (float)$this->db->get('order o')->row_array()['total'];
    }
    public function getSaleChart($filter = array(), $group = 'day'){
        $zero = '';
        $orderBy = ' ORDER BY created_at DESC ';

        if($group == 'month'){
            if(isset($filter['selectedyear'])){
                $current_year = " YEAR(created_at) = ". $filter['selectedyear'];
            }else{
                $current_year = " YEAR(created_at) = ". date("Y");
            }
        } else{
            $current_year .= ' 1=1 ';
        }
        if($group == 'day'){ $groupby = 'CONCAT(DAY(created_at),"-",MONTH(created_at),"-",YEAR(created_at))'; $zero = '2016-1-1'; }
        else if($group == 'week'){ $groupby = 'DATE_FORMAT(created_at, "%b %e")'; $zero = 'Jan 1'; }
        //else if($group == 'month'){ $groupby = 'CONCAT(YEAR(created_at),"-",MONTH(created_at))'; $zero = '2016-1'; }
        else if($group == 'month'){ $groupby = 'MONTH(created_at)'; $zero = '1'; }
        else if($group == 'year'){ $groupby = 'YEAR(created_at)'; $zero = '2016'; }


        $this->db->select(array(
            'sum(commission) as total_commission',
            'sum(total) as total_sale',
            'count(id) as total_order',
            "{$groupby} as groupby"
        ));
      
        if(isset($filter['affiliate_id'])) { $this->db->where('user_id', $filter['affiliate_id']); }
        //$this->db->where('status > 0');
        $this->db->where($current_year);
        $this->db->order_by('created_at','DESC');
        $this->db->group_by($groupby);
        $data = $this->db->get('integration_orders')->result_array();

        $chart = array();
        foreach ($data as $key => $value) {
            $chart[] = array(
                'y' => $value['groupby'],
                'a' => c_format($value['total_sale'], false),
                'b' => (int)$value['total_order'],
                'c' => c_format($value['total_commission'], false),
                'd' => 0,
                'e' => 0,
            );
        }

        $select = array(
            'sum(op.total) as total_sale',
            'count(op.id) as total_order',
            "{$groupby} as groupby"
        );
        if(isset($filter['affiliate_id'])) {
            $select [] = 'sum( IF(op.vendor_id = '. (int)$filter['affiliate_id'].',op.vendor_commission,op.commission) ) as total_commission';
        } else{
            $select [] = 'sum(op.commission + op.vendor_commission) as total_commission';
        }
        $this->db->select($select);


        $this->db->join("order_products op",'op.order_id = order.id','left');
        $this->db->where($current_year);

        if(isset($filter['affiliate_id'])) {
            $this->db->where('( op.vendor_id = '. $filter['affiliate_id'] .' OR  op.refer_id = '. $filter['affiliate_id'] .')');
            $this->db->where('order.status = 1');
        } else{
            $this->db->where('order.status = 1');
        }
        $this->db->order_by('created_at','DESC');
        $this->db->group_by('op.order_id');
        $data = $this->db->get('order')->result_array();
        foreach ($data as $key => $value) {
            $chart[] = array(
                'y' => $value['groupby'],
                'a' => c_format($value['total_sale'], false),
                'b' => (int)$value['total_order'],
                'c' => c_format($value['total_commission'], false),
                'd' => 0,
                'e' => 0,
            );
        }

 
        /*  FOR ACTION */
        $_where = '';
        if(isset($filter['affiliate_id'])) {
            $_where .=  ' user_id = '. (int)$filter['affiliate_id'] ." AND ";
        }

        $integration_click_amount = $this->db->query('SELECT '. $groupby . ' as groupby,count(amount) as total,SUM(amount) as total_count FROM `wallet` WHERE is_action=1 AND type="external_click_commission" AND '. $_where . $current_year .' AND comm_from = "ex"  AND status > 0 GROUP BY '. $groupby .'   '. $orderBy )->result_array();

        foreach ($integration_click_amount as $value) {
            $chart[] = array(
                'y' => $value['groupby'],
                'a' => 0,
                'b' => 0,
                'c' => 0,
                'd' => c_format($value['total'], false),
                'e' => $value['total_count'],
            );
        }


        $data = array();
        $sale = array();
        $order = array();
        $commissions = array();
        $action = array();
        $actioncommissions = array();
        $keys = array();
        if($group == 'month'){
            for ($i=1; $i <= 12; $i++) { 
                $keys[] = $i;
                $sale[$i] = $order[$i] = $commissions[$i] = $action[$i] = $actioncommissions[$i] = 0;
            }
        }
         
        foreach ($chart as $key => $value) {
            $tmp                            = $data[$value['y']];
            $sale[$value['y']]              += c_format((float)$value['a'] + (float)$tmp['a'], false);
            $order[$value['y']]             += (int)((float)$value['b'] + (float)$tmp['b']);
            $commissions[$value['y']]       += c_format((float)$value['c'] + (float)$tmp['c'], false);
            $action[$value['y']]            += c_format((float)$value['d'] + (float)$tmp['d'], false);
            $actioncommissions[$value['y']] += c_format((float)$value['e'] + (float)$tmp['e'], false);
            $data['y'][$value['y']]         = $value['y'];

            if($group != 'month' && !in_array($keys, $value['y'])){
                $keys[] = (string)$value['y'];
            }
        }
        function setup_tooltip($arr,$meta)
        {   
            return array("meta"=> $meta, "value" => $arr);
        } 

        //$salenew = array_map("setup_tooltip",$sale,array_fill(0, count($sale),'Sale'));

        if($group == 'day' || $group == 'week'){
            function date_sort($a, $b) {
                return strtotime($a) - strtotime($b);
            }
             
            usort($keys, "date_sort");
        }
        else if($group == 'year'){
            sort($keys);
        }

        $data['series_new'] = array(
            'keys'              => array_unique($keys),
            'sale'              => array('sale') + $sale,
            'order'             => array('order') + $order,
            'commissions'       => array('commissions') + $commissions,
            'action'            => array('action') + $action,
            'actioncommissions' => array('actioncommissions') + $actioncommissions,
        ); 

          
        return $data;
    }

    public function ___getSaleChart($filter = array(), $group = 'day'){
        $zero = '';
        $orderBy = ' ORDER BY created_at DESC ';

        if($group == 'day'){ $groupby = 'CONCAT(YEAR(created_at),"-",MONTH(created_at),"-",DAY(created_at))'; $zero = '2016-1-1'; }
        else if($group == 'week'){ $groupby = 'DATE_FORMAT(created_at, "%b %e")'; $zero = 'Jan 1'; }
        else if($group == 'month'){ $groupby = 'CONCAT(YEAR(created_at),"-",MONTH(created_at))'; $zero = '2016-1'; }
        else if($group == 'year'){ $groupby = 'YEAR(created_at)'; $zero = '2016'; }

        $this->db->select(array(
            'sum(commission) as total_commission',
            'sum(total) as total_sale',
            'count(id) as total_order',
            "{$groupby} as groupby"
        ));
      
        if(isset($filter['affiliate_id'])) {
            $this->db->where('user_id', $filter['affiliate_id']);
        }
        $this->db->where('status > 0');
        $this->db->order_by('created_at','DESC');
        $data = $this->db->get('integration_orders')->result_array();
        $chart = array();
        $chart[] = array(
            'y' => $zero,
            'a' => c_format(0, false),
            'b' => 0,
            'c' => c_format(0, false),
            'd' => 0,
            'e' => 0,
        );
        foreach ($data as $key => $value) {
            $chart[] = array(
                'y' => $value['groupby'],
                'a' => c_format($value['total_sale'], false),
                'b' => (int)$value['total_order'],
                'c' => c_format($value['total_commission'], false),
                'd' => 0,
                'e' => 0,
            );
        }

        $this->db->select(array(
            'sum(op.commission) as total_commission',
            'sum(order.total) as total_sale',
            'count(op.id) as total_order',
            "{$groupby} as groupby"
        ));
        $this->db->join("order_products op",'op.order_id = order.id','left');
        $this->db->where('order.status > 0');
        if(isset($filter['affiliate_id'])) {
            $this->db->where('op.refer_id', $filter['affiliate_id']);
        }
        $this->db->order_by('created_at','DESC');
        $this->db->group_by('op.order_id');
        $data = $this->db->get('order')->result_array();

        


        foreach ($data as $key => $value) {
            $chart[] = array(
                'y' => $value['groupby'],
                'a' => c_format($value['total_sale'], false),
                'b' => (int)$value['total_order'],
                'c' => c_format($value['total_commission'], false),
                'd' => 0,
                'e' => 0,
            );
        }


        $_where = '';
        if(isset($filter['affiliate_id'])) {
            $_where .= ' user_id = '. (int)$filter['affiliate_id'] ." AND ";
        }


        /*$integration_action_count  = $this->db->query('SELECT '. $groupby . ' as groupby,count(*) as total FROM `integration_clicks_action` WHERE '. $_where .' is_action=1 GROUP BY '. $groupby )->result_array();*/
        $integration_click_amount = $this->db->query('SELECT '. $groupby . ' as groupby,count(amount) as total FROM `wallet` WHERE is_action=1 AND type="external_click_commission" AND '. $_where .' comm_from = "ex"   GROUP BY domain_name '. $orderBy .'')->result_array();

        foreach ($integration_click_amount as $value) {
            $chart[] = array(
                'y' => $value['groupby'],
                'a' => 0,
                'b' => 0,
                'c' => 0,
                'd' => c_format($value['total'], false),
                'e' => 0,
            );
        }

        $integration_click_amount = $this->db->query('SELECT '. $groupby . ' as groupby,SUM(amount) as total FROM `wallet` WHERE is_action=1 AND type="external_click_commission" AND '. $_where .' comm_from = "ex"   GROUP BY domain_name '. $orderBy .'')->result_array();
        foreach ($integration_click_amount as $value) {
            $chart[] = array(
                'y' => $value['groupby'],
                'a' => 0,
                'b' => 0,
                'c' => 0,
                'd' => 0,
                'e' => c_format($value['total'], false),
            );
        }



        $data = array();
        foreach ($chart as $key => $value) {
            $tmp = $data[$value['y']];

            $data[$value['y']] = array(
                'y' => $value['y'],
                'a' => c_format($value['a'] + $tmp['a'], false),
                'b' => (int)($value['b'] + $tmp['b']),
                'c' => c_format($value['c'] + $tmp['c'], false),
                'd' => c_format($value['d'] + $tmp['d'], false),
                'e' => c_format($value['e'] + $tmp['e'], false),
            );
        }

        return array_values($data);
        return array_reverse(array_values($data));
        return $chart;
    }
    public function getOrder($order_id, $control = 'admincontrol'){
        $where = '';
        if(isset($filter['affiliate_id'])){
            $where .= " AND op.refer_id = ". (int)$filter['affiliate_id'];
        }
        $order = $this->db->query("
            SELECT o.*,u.firstname,u.lastname,u.email,c.name as country_name,s.name as state_name,cc.name as order_country,
                (SELECT paypal_status FROM orders_history WHERE orders_history.order_id = o.id ORDER BY id DESC LIMIT 1) as last_status,
                (SELECT form_id FROM order_products WHERE order_products.order_id = o.id ORDER BY form_id DESC LIMIT 1) as form_id
            FROM `order` o 
                LEFT JOIN users u ON u.id = o.user_id   
                LEFT JOIN order_products op ON op.order_id = o.id
                LEFT JOIN countries c ON c.id = o.country_id
                LEFT JOIN states s ON s.id = o.state_id
                LEFT JOIN countries cc ON cc.sortname = o.country_code
            WHERE o.id= ". (int)$order_id ." {$where} 
        ")->row_array();

        $order['files'] = json_decode($order['files'], true);
        if($order['files']){
            $html = '';
            foreach ($order['files'] as $v) {
                $html .= '<a target="_blank" href="'. base_url($control."/order_attechment/". $v['name'] ."/". $v['mask'] ) .'">'. $v['mask'] .'</a><br>';
            }

            $order['files'] = $html;
        }

        $order['order_country_flag'] = '<img style="width: 20px;margin: 0 10px;" src="'. base_url('assets/vertical/assets/images/flags/'. strtolower($order['country_code'])  ) .'.png"> IP: '. $order['ip'] ;

        return $order;
    }
    public function getTotals($products, $order){
        $totals = array();
        $total = 0;
        $discount = 0;
        foreach ($products as $key => $value) {
            $total += ($value['total']);
            $discount += ($value['coupon_discount']);
        }
        $totals['total'] = array("text" => 'Sub Total', 'value' => $total);
        if($discount > 0){
            $totals['discount_total'] = array("text" => 'Discount', 'value' => $discount);
        }
        if($order['coupon_discount'] > 0){
            $totals['discount_total'] = array("text" => 'Coupon Discount', 'value' => $order['coupon_discount']);
            $total -= $order['coupon_discount'];
        }
        if($order['shipping_cost'] > 0){
            $totals['shipping_cost'] = array("text" => 'Shipping Cost', 'value' => $order['shipping_cost']);
            $total += $order['shipping_cost'];
        }

        $totals['grand_total'] = array("text" => 'Grand Total', 'value' => $total);


        return $totals;
    }
    public function getProducts($order_id,$filter = array()){
        $where = '';
        if(isset($filter['refer_id'])) { 
            $where .= " AND op.refer_id =  ". $filter['refer_id']; 
        }
        if(isset($filter['vendor_or_refer_id'])) { 
            $where .= " AND (op.vendor_id = ". $filter['vendor_or_refer_id'] ." OR op.refer_id =  ". $filter['vendor_or_refer_id'] .")"; 
        }
        $cart_products = $this->db->query("SELECT 
            op.*,p.product_name,p.product_featured_image,p.product_type,p.downloadable_files,CONCAT(u.firstname,' ',u.lastname) as refer_name,u.email as refer_email
            FROM order_products op 
                LEFT JOIN product as p ON p.product_id = op.product_id 
                LEFT JOIN users as u ON u.id = op.refer_id 
            WHERE op.order_id = {$order_id}  {$where} ")->result_array();
        $products  = array();
        $this->load->model('Product_model');
        foreach ($cart_products as $key => $product) {
            $product_featured_image = resize('assets/images/product/upload/thumb/'. $product['product_featured_image'] , 100,100);
            $products[] = array(
                'id'                     => $product['id'],
                'order_id'               => $product['order_id'],
                'product_id'             => $product['product_id'],
                'refer_id'               => $product['refer_id'],
                'form_id'                => $product['form_id'],
                'product_type'           => $product['product_type'],
                'downloadable_files'     => $this->Product_model->parseDownloads($product['downloadable_files']),
                'price'                  => $product['price'],
                'quantity'               => $product['quantity'],
                'commission'             => $product['commission'],
                'commission_type'        => $product['commission_type'],
                'coupon_code'            => $product['coupon_code'],
                'coupon_name'            => $product['coupon_name'],
                'total'                  => $product['total'],
                'coupon_discount'        => $product['coupon_discount'],
                'product_name'           => $product['product_name'],
                'refer_email'            => $product['refer_email'],
                'refer_name'             => $product['refer_name'],
                'admin_commission'       => $product['admin_commission'],
                'admin_commission_type'  => $product['admin_commission_type'],
                'vendor_commission'      => $product['vendor_commission'],
                'vendor_commission_type' => $product['vendor_commission_type'],
                'vendor_id' => (int)$product['vendor_id'],
                'image'                  => $product_featured_image,
            );
        }
        return $products;
    }
    public function getAffiliateUser($order_id){
        $this->db->select('users.*,order_products.commission,order_products.commission_type,product.product_name,product.product_short_description');
        $this->db->where('order_products.order_id', $order_id);
        $this->db->join('users', 'users.id = order_products.refer_id');
        $this->db->join('product', 'product.product_id = order_products.product_id');
        return $this->db->get_where('order_products')->result_array();
    }
    public function getVender($order_info = array(), $product_info = array()){
        $p_ids = array_column($product_info, 'product_id');

        $this->db->select('order_products.product_id, order_products.vendor_commission, users.*');
        $this->db->join('users', 'users.id = order_products.refer_id');
        $this->db->where('order_products.order_id', $order_info['id']);
        $this->db->where_in('order_products.product_id', $p_ids);
        $vendrs = $this->db->get_where('order_products')->result_array();

        $return_vendrs = array();
        foreach ($vendrs as $vendr){
            $return_vendrs[$vendr['product_id']] = array(
                'id'                => $vendr['id'],
                'product_id'        => $vendr['product_id'],
                'firstname'         => $vendr['firstname'],
                'lastname'          => $vendr['lastname'],
                'email'             => $vendr['email'],
                'username'          => $vendr['username'],
                'vendor_commission' => $vendr['vendor_commission'],
            );
        }
        return $return_vendrs;
    }
    public function getHistory($order_id, $type = 'payment'){
        $this->db->where('order_id', $order_id);
        $this->db->where('history_type', $type);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('orders_history')->result_array();
    }

    public function getPaymentProof($order_id){
        $this->db->where('order_id', $order_id);
        $orderProof =  $this->db->get('order_proof')->row();
        if($orderProof){
            $orderProof->downloadLink = base_url('assets/user_upload/'. $orderProof->proof);
        }

        return $orderProof;
    }
    
    public function getOrders($filter = array(), $addShipping = true){
        $where = '';
        if(isset($filter['user_id'])){
            $where .= " AND o.user_id = ". (int)$filter['user_id'];
        }
        if(isset($filter['affiliate_id'])){
            $where .= " AND op.refer_id = ". (int)$filter['affiliate_id'];
        }
        $orders =  $this->db->query("
            SELECT o.*,u.firstname,u.lastname ,sum(op.total) AS total_sum,
                (SELECT paypal_status FROM orders_history WHERE orders_history.order_id = o.id ORDER BY id DESC LIMIT 1) as last_status
            FROM `order` o 
                LEFT JOIN users u ON u.id = o.user_id   
                LEFT JOIN order_products op ON op.order_id = o.id
            WHERE o.status > 0 {$where} 
            GROUP BY o.id
            ORDER BY o.id DESC
        ")->result_array();

        $data = array();
        foreach ($orders as $key => $value) {
            $total_sum = $addShipping ? ($value['total_sum'] + $value['shipping_cost']) : $value['total_sum'];

            $data[] = array(
                'id'                 => $value['id'],
                'status'             => $value['status'],
                'txn_id'             => $value['txn_id'],
                'user_id'            => $value['user_id'],
                'address'            => $value['address'],
                'country_id'         => $value['country_id'],
                'state_id'           => $value['state_id'],
                'city'               => $value['city'],
                'zip_code'           => $value['zip_code'],
                'phone'              => $value['phone'],
                'payment_method'     => $value['payment_method'],
                'shipping_cost'      => $value['shipping_cost'],
                'total'              => $total,
                'coupon_discount'    => $value['coupon_discount'],
                'total_commition'    => $value['total_commition'],
                'shipping_charge'    => $value['shipping_charge'],
                'currency_code'      => $value['currency_code'],
                'created_at'         => $value['created_at'],
                'allow_shipping'     => $value['allow_shipping'],
                'ip'                 => $value['ip'],
                'country_code'       => $value['country_code'],
                'files'              => $value['files'],
                'comment'            => $value['comment'],
                'firstname'          => $value['firstname'],
                'lastname'           => $value['lastname'],
                'total_sum'          => $total_sum,
                'last_status'        => $value['last_status'],
                'order_country_flag' =>  '<img style="width: 20px;margin: 0 10px;" src="'. base_url('assets/vertical/assets/images/flags/'. strtolower($value['country_code'])  ) .'.png"> IP: '. $value['ip'],
            );
        }

        return $data;
    }
    public function orderdelete($order_id, $transaction){
        if($transaction > 0){
            $this->db->query("DELETE FROM wallet WHERE reference_id = {$order_id} AND type IN ('sale_commission','vendor_sale_commission') ");
        }
        /*if ($walletCheck) {
            $this->Wallet_model->addTransaction(array(
                'user_id' => $walletCheck->user_id,
                'amount' => -$walletCheck->amount,
                'comment' => 'Commition rollback for order Id # '. $order_id,
                'type' => 'sale_commission_rollback',
                'reference_id' => $order_id,
            ));
        }*/
        $this->db->query("DELETE FROM `order` WHERE id = {$order_id} ");
        $this->db->query("DELETE FROM orders_history WHERE order_id = {$order_id} ");
        $this->db->query("DELETE FROM order_products WHERE order_id = {$order_id} ");
    }
    public function send_order_mail($order_id){
        $data['order'] = $this->Order_model->getOrder($order_id);
        $data['status'] = $this->Order_model->status;
        $data['orderLink'] = base_url('clientcontrol/vieworder/'.$order_id);
        $data['mob'] = '97895515452';
        $data['email'] = 'affiliate@affiliate.agposter.biz';
        $template = $this->load->view('mails/order', $data, true);
        $to = 'jaydeepakbari@gmail.com';// $data['order']['email'];
        $subject  = "Order Status Has Been Change ";
        $from     = 'affiliate@affiliate.agposter.biz';
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers  .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers  .= 'From: '.$from."\r\n". 'Reply-To: '.$from."\r\n" . 'X-Mailer: PHP/' . phpversion();
        if (mail($to, $subject, $template, $headers)) {
        } else {
        }
    }
}