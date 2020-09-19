<?php
class Product_model extends MY_Model{
    function create_data($table, $details){
        $this->db->insert($table, $details);
        return $this->db->insert_id();
    }
    public function getHtml($file,$data = []){
        return $this->load->view($file,$data,true);
    }
    public function duplicateProduct($product_id,$status_review = false){
        $product = $this->db->query("SELECT * FROM product WHERE product_id=". (int)$product_id)->row_array();
        $product['product_name'] = $product['product_name'] ." - Duplicate";
        $product['product_created_date'] =  date("Y-m-d H:i:s");
        unset($product['product_id']);

        if($status_review){
            $product['product_status'] = 0;
        }
        
        $this->db->insert("product", $product);
        $new_product_id = $this->db->insert_id();


        // Duplicate product category
        $product_categories = $this->db->query("SELECT * FROM product_categories WHERE product_id=". (int)$product_id)->result_array();
        foreach ($product_categories as $key => $category) {
            unset($category['id']);
            $category['product_id'] = $new_product_id;
            $this->db->insert("product_categories", $category);
        }

        // Duplicate product product media data
        $product_media_upload = $this->db->query("SELECT * FROM product_media_upload WHERE product_id=". (int)$product_id)->result_array();
        foreach ($product_media_upload as $key => $media) {
            unset($media['product_media_upload_id']);
            $media['product_id'] = $new_product_id;
            $this->db->insert("product_media_upload", $media);
        }

        // Duplicate product seller data
        $product_affiliate = $this->db->query("SELECT * FROM product_affiliate WHERE product_id=". (int)$product_id)->result_array();
        foreach ($product_affiliate as $key => $seller) {
            unset($seller['id']);
            $seller['product_id'] = $new_product_id;
            $this->db->insert("product_affiliate", $seller);
        }
    }

    public function calcVendorCommission($data){
        $json = [];
        $product_price = (float)$data['product_price'];
        $vendor_setting = $this->getSettings('vendor');
        $admin_sale_com = $affiliate_sale_com = 0;


        if($data['admin_sale_commission_type'] == 'default'){
            $admin_sale_com = 0;
            if($vendor_setting['admin_sale_commission_type'] == 'percentage'){
                $admin_sale_com = ($product_price * (float)$vendor_setting['admin_commission_value']) / 100;
            } else {
                $admin_sale_com = (float)$vendor_setting['admin_commission_value'];
            }
        } else if($data['admin_sale_commission_type'] == 'percentage'){
            $admin_sale_com = ($product_price * (float)$data['admin_commission_value']) / 100;
        } else if($data['admin_sale_commission_type'] == 'fixed'){
            $admin_sale_com = (float)$data['admin_commission_value'];
        }

        if($data['affiliate_sale_commission_type'] == 'default'){
            if(isset($data['user_id'])){
                $seller_setting = $this->db->query("SELECT vs.* FROM vendor_setting vs WHERE vs.user_id=". (int)$data['user_id'] ." ")->row();
            } else {
                $seller_setting = $this->db->query("SELECT vs.* FROM vendor_setting vs LEFT JOIN product_affiliate pa ON (pa.user_id = vs.user_id) WHERE pa.product_id=". (int)$data['product_id'] ." ")->row();
            }

            $affiliate_sale_com = 0;
            if($seller_setting->affiliate_sale_commission_type == 'percentage'){
                $affiliate_sale_com = ($product_price * (float)$seller_setting->affiliate_commission_value) / 100;
            } else {
                $affiliate_sale_com = (float)$seller_setting->affiliate_commission_value;
            }
        } else if($data['affiliate_sale_commission_type'] == 'percentage'){
            $affiliate_sale_com = ($product_price * (float)$data['affiliate_commission_value']) / 100;
        } else if($data['affiliate_sale_commission_type'] == 'fixed'){
            $affiliate_sale_com = (float)$data['affiliate_commission_value'];
        }

        $vendor_commission = ($product_price - $admin_sale_com - $affiliate_sale_com);

        return [
            'vendor_commission'  => round($vendor_commission,2),
            'admin_sale_com'     => round($admin_sale_com,2),
            'affiliate_sale_com' => round($affiliate_sale_com,2),
        ];
    }
    public function assignToSeller($product_id, $product, $user_id, $admin_comment, $comment_from = 'affiliate', $comm = array()){
        $data = [
            'product_id' => (int)$product_id,
            'user_id' => (int)$user_id,
            'comment' => '[]',
        ];

        $check = $this->db->query("SELECT * FROM product_affiliate WHERE product_id=". (int)$product_id ." ")->row();
        if($comment_from == 'admin'){
            unset($data['user_id']);
        } else {
            if($check && $check->user_id != $user_id){
                return false;
            }
            //$check = $this->db->query("SELECT * FROM product_affiliate WHERE user_id=". (int)$user_id ." AND product_id=". (int)$product_id ." ")->row();
        }

        if($comment_from == 'admin'){
            $data['admin_sale_commission_type']  = $comm['admin_sale_commission_type'];
            $data['admin_commission_value']      = $comm['admin_commission_value'];
            $data['admin_click_commission_type'] = $comm['admin_click_commission_type'];
            $data['admin_click_amount']          = $comm['admin_click_amount'];
            $data['admin_click_count']           = $comm['admin_click_count'];
        } else {
            $data['affiliate_click_commission_type'] = $comm['affiliate_click_commission_type'];
            $data['affiliate_click_count']           = $comm['affiliate_click_count'];
            $data['affiliate_click_amount']          = $comm['affiliate_click_amount'];
            $data['affiliate_sale_commission_type']  = $comm['affiliate_sale_commission_type'];
            $data['affiliate_commission_value']      = $comm['affiliate_commission_value'];
        }

        if($check){
            if($admin_comment){
                $c = json_decode($check->comment,1);
                $c[] = [
                    'from'    => $comment_from,
                    'comment' => $admin_comment,
                ];

                $check->comment = json_encode($c);
            }
            $data['comment'] = $check->comment;
            $this->db->update('product_affiliate', $data, ['id' => $check->id]);
        } else {
            if($comment_from = 'affiliate'){
                $this->create_data('product_affiliate', $data);
            }
        }
    }
    public function getShippingCountry(){
        $shipping_setting = $this->Product_model->getSettings('shipping_setting');
        $cost = (array)(isset($shipping_setting['cost']) ? json_decode($shipping_setting['cost'],1) : []);

        if((int)$shipping_setting['shipping_in_limited'] == 1){
            $_cost = [];
            foreach ($cost as $key => $value) {
                $_cost[$value['country']] = $value['cost'];
            }

            return $_cost;
        }

        return 'all';
    }
    public function upload_photo($fieldname,$path) {
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'png|gif|jpeg|jpg|PNG|GIF|JPEG|JPG|ICO|ico';
        $config['max_size']      = 2048;
        $this->load->helper('string');
        $config['file_name']  = random_string('alnum', 32);
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($fieldname)) {
            $data = array('success' => false, 'msg' => $this->upload->display_errors());
        }
        else
        {
            $upload_details = $this->upload->data();
            $config1 = array(
                'source_image' => $upload_details['full_path'],
                'new_image' => $path.'/thumb',
                'maintain_ratio' => true,
                'width' => 300,
                'height' => 300
            );
            $this->load->library('image_lib', $config1);
            $this->image_lib->resize();
            $data = array('success' => true, 'upload_data' => $upload_details, 'msg' => "Upload success!");
        }
        return $data;
    }
    public function getShippingRate($countryId){
        $shipping_setting = $this->Product_model->getSettings('shipping_setting');
        $cost = (array)(isset($shipping_setting['cost']) ? json_decode($shipping_setting['cost'],1) : []);

        $_cost = [];
        foreach ($cost as $key => $value) {
            $_cost[$value['country']] = $value['cost'];
        }

        if(isset($_cost[(int)$countryId])){
            return $_cost[(int)$countryId];
        }

        return 0;
    }
    public function insertOrDelete($data,$where){
        $this->db->delete('order_proof', $where);
        $this->db->insert('order_proof', $data);
    }
    public function getProductCategory($product_id){
        return $this->db->query("SELECT pc.product_id,c.* FROM product_categories pc LEFT JOIN categories c ON c.id = pc.category_id WHERE pc.product_id = {$product_id}")->result_array();
    }
    private function buildTreeForCategory(array $elements, $parentId = 0) {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTreeForCategory($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
                unset($elements[$key]);
            }
        }

        return $branch;
    }
    public function getCategoryTree($filter = array()){
        $categories = $this->db->query("SELECT * FROM categories")->result_array();
        return $this->buildTreeForCategory($categories);
    }
    public function getCategory($filter = array()){
        $sql = "SELECT SQL_CALC_FOUND_ROWS c.*,pc.name as parent_name,(SELECT count(pc.category_id) FROM product_categories pc WHERE pc.category_id = c.id ) as total_product FROM categories c LEFT JOIN categories pc ON pc.id = c.parent_id WHERE 1";

        $sql.= " ORDER BY c.id DESC ";

        if (isset($filter['page'],$filter['limit'])) {
            $offset = (($filter['page']-1) * $filter['limit']);
            $sql.= " LIMIT {$offset},". $filter['limit'];
        }

        $categories = $this->db->query($sql)->result_array();
        $total = $this->db->query("SELECT FOUND_ROWS() AS total")->row()->total;

        $data = array();
        foreach ($categories as $key => $value) {
            $data[] = array(
                'id'          => $value['id'],
                'name'        => $value['name'],
                'description' => $value['description'],
                'parent_name' => $value['parent_name'],
                'image'       => $value['image'],
                'total_product'       => (int)$value['total_product'],
                'image_url'   => base_url($value['image'] != '' ? 'assets/images/product/upload/thumb/' . $value['image'] : 'assets/images/no_image_available.png'),
                'parent_id'   => $value['parent_id'],
                'created_at'  => date("d-m-Y h:i A",strtotime($value['created_at'])),
            );
        }

        return array($data,$total);
    }

    public function getIntegrationCategory($filter = array()){
        $sql = "SELECT SQL_CALC_FOUND_ROWS c.* FROM integration_category c WHERE 1";

        $sql.= " ORDER BY c.id DESC ";

        if (isset($filter['page'],$filter['limit'])) {
            $offset = (($filter['page']-1) * $filter['limit']);
            $sql.= " LIMIT {$offset},". $filter['limit'];
        }

        $categories = $this->db->query($sql)->result_array();
        $total = $this->db->query("SELECT FOUND_ROWS() AS total")->row()->total;

        $data = array();
        foreach ($categories as $key => $value) {
            $data[] = array(
                'id'          => $value['id'],
                'name'        => $value['name'],
                'created_at'  => date("d-m-Y h:i A",strtotime($value['created_at'])),
            );
        }

        return array($data,$total);
    }
    public function my_refer_status($user_id){
        $referlevelSettings = $this->Product_model->getSettings('referlevel');
        $disabled_for = json_decode( (isset($referlevelSettings['disabled_for']) ? $referlevelSettings['disabled_for'] : '[]'),1);
        $refer_status = true;
        if((int)$referlevelSettings['status'] == 0){ $refer_status = false; }
        else if((int)$referlevelSettings['status'] == 2 && in_array($user_id, $disabled_for)){ $refer_status = false; }

        return $refer_status;
    }
    public function ping($user_id){
        $this->db->query("UPDATE users SET last_ping = '". date("Y-m-d H:i:s") ."' WHERE id = ". (int)$user_id);
    }
    public function onlineCount(){
        $data = array();

        $result = $this->db->query("
            SELECT SUM(IF(TIMESTAMPDIFF(SECOND, last_ping, '". date("Y-m-d H:i:s") ."') < 60, 1, 0)) as online, count(*) as total, type
            FROM `users`
            GROUP BY type
        ")->result_array();

        $data['admin'] = array('total' => 0, 'online' => 0);
        $data['user'] = array('total' => 0, 'online' => 0);

        foreach ($result as $key => $value) {
            $data[$value['type']] = array(
                'online' => $value['online'],
                'total' => $value['total'],
            );
        }

        return $data;
    }
    public function page_id()  {
        return $this->router->fetch_class().'_'.$this->router->fetch_method();
    }
    public function getUserWorldMap(){

        $data = $this->db->query("
            SELECT c.name,c.sortname,count(u.id) as total
            FROM users as u
                LEFT JOIN countries c ON c.id = u.Country
            WHERE type='user' AND Country > 0 GROUP BY Country
        ")->result_array();

        require APPPATH.'/core/latlong.php';

        $markers = array();
        foreach ($data as $key => $value) {
            $l = $_lat_lng[$value['sortname']];

            $code = $_countryCode[strtoupper($value['sortname'])];
            if($code){
                $markers[] = array(
                    'latLng' => array((float)$l[0], (float)$l[1]),
                    'code'      => $value['sortname'],
                    'total'      => (int)$value['total'],
                    'name'      => $value['name'] ." - ".$value['total'],
                );
            }
        }

        return $markers;
    }
    public function getSettingStatus(){
        $this->load->model('PagebuilderModel');

        $json = array();

        $site = $this->Product_model->getSettings('site');
        $login = $this->Product_model->getSettings('login');
        $email = $this->Product_model->getSettings('email');

        $check = $this->db->query("SELECT currency_id FROM  currency WHERE is_default = 1 ")->num_rows();
        if($check == 0){
            $json['currency'] = true;
        }

        $check = $this->db->query("SELECT id FROM language WHERE is_default = 1 ")->num_rows();
        if($check == 0){
            $json['language'] = true;
        }

        if($site['time_zone'] == ''){
            $json['site_time_zone'] = true;
        }
        if($site['notify_email'] == ''){
            $json['notify_email'] = true;
        }

        if($email['from_email'] == ''){
            $json['from_email'] = true;
        }
        if($email['from_name'] == ''){
            $json['from_name'] = true;
        }

        return $json;
    }
    public function hold_noti($filter = array()){
        $where = ' 1 ';
        if (isset($filter['user_id'])) {
            $where .= '  AND user_id = '. (int)$filter['user_id'];
        }

        $data['store_hold_orders'] = (int)$this->db->query('SELECT count(*) as total FROM `order` WHERE '. $where .' AND status  = 7')->row_array()['total'];
        $data['integration_hold_orders'] = (int)$this->db->query('SELECT count(*) as total FROM `integration_orders` WHERE '. $where .' AND status  = 0')->row_array()['total'];

        return $data;
    }
    public function getPaymentWarning(){
        $userdetails = $this->userdetails('user');
        $data['paymentlist'] = $this->Product_model->getAllPayment($userdetails['id']);
        if (isset($data['paymentlist'][0])) {
            $data['paymentlist'] = array(
                'payment_id'             => $data['paymentlist'][0]['payment_id'],
                'payment_bank_name'      => $data['paymentlist'][0]['payment_bank_name'],
                'payment_account_number' => $data['paymentlist'][0]['payment_account_number'],
                'payment_account_name'   => $data['paymentlist'][0]['payment_account_name'],
                'payment_ifsc_code'      => $data['paymentlist'][0]['payment_ifsc_code'],
            );
        } else {
            $data['paymentlist'] = array(
                'payment_id'             => 0,
                'payment_bank_name'      => '',
                'payment_account_number' => '',
                'payment_account_name'   => '',
                'payment_ifsc_code'      => '',
            );
        }
        $data['paypalaccounts'] = $this->Product_model->getPaypalAccounts($userdetails['id']);
        if (isset($data['paypalaccounts'][0])) {
            $data['paypalaccounts'] = array(
                'paypal_email' => $data['paypalaccounts'][0]['paypal_email'],
                'id'           => $data['paypalaccounts'][0]['id'],
            );
        } else {
            $data['paypalaccounts'] = array(
                'paypal_email' => '',
                'id'           => 0,
            );
        }
        $data['paymentlist']['paypalaccounts'] = $data['paypalaccounts'];
        return $data['paymentlist'];
    }

    public $loginUser = [];
    public function userdetails($guard = 'administrator',$force = 0){ 

        if($force){
            $this->loginUser[$guard] = $this->db->query("SELECT * FROM users WHERE id=". (int)$u['id'])->row_array();
        }

        if(!isset($this->loginUser[$guard])){
            $u = $this->session->userdata($guard);  
            if($u){
                $this->loginUser[$guard] = $this->db->query("SELECT * FROM users WHERE id=". (int)$u['id'])->row_array();
            }
        }

        return $this->loginUser[$guard];
        return $this->session->userdata($guard); 
    }
    public function getSiteSetting(){ return $this->getSettings('site'); }
    public function getLicese(){ return $this->session->userdata('license'); }
    public function getMultipleProductById($product_id){ return $this->db->get_where('product_media_upload', array('product_media_upload_id' => $product_id))->row_object(); }
    public function getProductByIdArray($product_id){ return $this->db->get_where('product', array('product_id' => $product_id))->row_array(); }
    public function getAffiliateById($affiliateads_id = null){ return $this->db->get_where('affiliateads', array('affiliateads_id' => $affiliateads_id))->row_array(); }
    public function getProductById($product_id){ return $this->db->get_where('product', array('product_id' => $product_id))->row_object(); }
    public function getSettingById($product_id){ return $this->db->get_where('setting', array('setting_id' => $setting_id))->row_object(); }
    public function getProductBySlug($product_slug){ return $this->db->get_where('product', array('product_slug' => $product_slug))->row_array(); }
    public function getUserDetails($user_id){ return $this->db->get_where('users', array('id' => $user_id))->row_array(); }
    public function getUserDetailsObject($user_id){ return $this->db->get_where('users', array('id' => $user_id))->row_object(); }
    public function getAllImages($id){ return $this->db->get_where('product_media_upload', array('product_media_upload_status' => 1, 'product_media_upload_type' => 'image', 'product_id' => $id))->result_array(); }
    public function getAllVideoImages($id){ return $this->db->get_where('product_media_upload', array('product_media_upload_status' => 1, 'product_media_upload_type' => 'video', 'product_id' => $id))->result_array(); }
    public function getAllVideos($id){ return $this->db->get_where('product_media_upload', array('product_media_upload_status' => 1, 'product_media_upload_type' => 'video', 'product_id' => $id))->result_array(); }
    public function getAllSettings(){ return $this->db->get_where('setting', array('setting_status' => 1))->result_array(); }
    public function getAllProductrecord(){ return $this->db->get_where('product', array('product_status' => 1))->result_array(); }
    public function getPaymentById($payment_id){ return $this->db->get_where('payment_detail', array('payment_id' => $payment_id))->row_object(); }
    public function getRequestPaymentById($user_payment_request_id){ return $this->db->get_where('user_payment_request', array('user_payment_request_id' => $user_payment_request_id))->row_object(); }
    public function getAllPaymentRequest(){ return $this->db->get_where('user_payment_request')->result_array(); }
    public function getUserPaymentRequest($id){ return $this->db->get_where('user_payment_request', array('user_payment_request_amount_status' => 'pending', 'user_payment_request_user_id' => $id))->result_array(); }
    public function getPaymentRequestById($id){ return $this->db->get_where('user_payment_request', array('user_payment_request_id' => $id))->row_array(); }

    function update_data($product, $details, $where_data_array = NULL){
        if ($where_data_array){
            foreach ($where_data_array as $key => $data) $this->db->where($key, $data);
        }
        return $this->db->update($product, $details);
    }
    function getProductByIds($product_ids){
        $this->db->select('*');
        $this->db->from('product');
        $this->db->where_in('product_id', $product_ids);
        return $this->db->get()->result();
    }
    function getAllProducts($filter = []){
        $sql = "SELECT DISTINCT p.*,seller.id seller_id FROM product p
            LEFT JOIN product_affiliate pa ON pa.product_id = p.product_id
            LEFT JOIN users as seller ON pa.user_id = seller.id WHERE 1";

        if (isset($filter['vendor_id'])) {
            if($filter['vendor_id'] == 'admin'){
                $sql .= " AND seller.id IS NULL";
            } else {
                $sql .= " AND seller.id=". (int)$filter['vendor_id'];
            }
        }

        return $this->db->query($sql)->result();
    }
    function getDeleteById($affiliateads_id){
        $this->db->where('affiliateads_id', $affiliateads_id);
        return $this->db->delete('affiliateads');
    }
    function getLanguageHtml($control = 'admincontrol'){
        $lang = $_SESSION['userLang'];
        if(!$lang) $lang = 1;
        $extra_class = 'd-none d-sm-inline-block';
        if($control == 'store'){ $extra_class = ''; }

        $selected = $this->db->query("SELECT * FROM language WHERE status=1 AND id=". (int)$lang)->row_array();

        $all = $this->db->query("SELECT * FROM language WHERE status=1 AND id != ". (int)$lang)->result_array();

        $_html = '';
        foreach ($all as $key => $value) {
            if(!$selected) {
                $selected = $value;
                $this->db->query("UPDATE language SET is_default = 1 WHERE id=". (int)$selected['id']);
            }
            $_html .='<a class="dropdown-item" href="'. base_url($control."/change_language/".$value['id']) .'"><img src="'. base_url('application/language/'. $value['id']."/flag.png") .'" alt="" height="16"/><span> '. $value['name'] .' </span></a>';
        }

        $html = '<a class="nav-link dropdown-toggle arrow-none waves-effect text-white" data-toggle="dropdown" href="#" role="button"
                aria-haspopup="false" aria-expanded="false">
                    <span class="'. $extra_class .'">'. $selected['name'] .'</span> <img src="'. base_url('application/language/'. $selected['id']."/flag.png?v=".time()) .'" class="ml-2" height="16" alt=""/>
                </a><div class="dropdown-menu dropdown-menu-right language-switch">' . $_html;

        return $html.'</div>';
    }
    function getCurrencyHtml($control = 'admincontrol'){
        $lang = $_SESSION['userCurrency'];
        $selected = $this->db->query("SELECT * FROM currency WHERE code = '{$lang}' ")->row_array();
        $extra_class = 'd-none d-sm-inline-block';
        if($control == 'store'){ $extra_class = ''; }
        if(!$selected){
            $selected = $this->db->query("SELECT * FROM currency WHERE is_default=1")->row_array();
            $_SESSION['userCurrency'] = $selected['code'];
        }

        $all = $this->db->query("SELECT * FROM currency WHERE status=1 AND code != '{$lang}' ")->result_array();
        $html = '<a class="nav-link dropdown-toggle arrow-none waves-effect text-white" data-toggle="dropdown" href="#" role="button"
                aria-haspopup="false" aria-expanded="false">'. $selected['symbol_left'] ." <span class='". $extra_class ."'>". $selected['title'] ."</span> ". $selected['symbol_right']  .'</a><div class="dropdown-menu dropdown-menu-right currency-switch">';
        foreach ($all as $key => $value) {
            $html .='<a class="dropdown-item" href="'. base_url($control."/change_currency/".$value['code']) .'"><span> '. $value['symbol_left'] ." ". $value['title'] ." ". $value['symbol_right'] .' </span></a>';
        }
        return $html.'</div>';
    }
    function getAffiliateByType($affiliateads_type, $user_id = 0){
        $where = '';
        if($user_id > 0){
            $where = " AND user_id =  {$user_id} ";
        }
        $this->db->select(array(
            'affiliateads.*',
            "( SELECT count(*) FROM affiliate_action WHERE  affiliate_action.affiliate_id = affiliateads.affiliateads_id {$where} ) total_click",
            "( SELECT SUM(amount) FROM wallet WHERE wallet.reference_id = affiliateads.affiliateads_id AND type='affiliate_click_commission' AND wallet.status = 1 {$where}) total_unpaid",
            "( SELECT SUM(amount) FROM wallet WHERE wallet.reference_id = affiliateads.affiliateads_id AND type='affiliate_click_commission' AND wallet.status = 2 {$where}) total_inrequest",
            "( SELECT SUM(amount) FROM wallet WHERE wallet.reference_id = affiliateads.affiliateads_id AND type='affiliate_click_commission' AND wallet.status = 3 {$where}) total_paid",
        ));
        if ($affiliateads_type) {
            $data = $this->db->get_where('affiliateads', array('affiliateads_type' => trim($affiliateads_type)))->result_array();
        } else {
            $data = $this->db->get_where('affiliateads')->result_array();
        }
        return $data;
    }
    function getAffiliateUserByByType($affiliateads_type = null, $user_id = null){
        $this->db->select('affiliateads.affiliateads_id,SUM( clicks_views.clicks_views_click ) as total_click, SUM( clicks_views.clicks_views_view ) as total_view,SUM( clicks_views.clicks_views_click_commission ) as total_commission  ');
        $this->db->join('affiliateads', 'affiliateads.affiliateads_id = clicks_views.clicks_views_action_id');
        if ($affiliateads_type) {
            $this->db->where('clicks_views.clicks_views_refuser_id', $user_id);
            $this->db->group_by('clicks_views.clicks_views_action_id');
            $getData = $this->db->get_where('clicks_views', array('clicks_views.clicks_views_type' => trim($affiliateads_type)))->result_array();
        } else {
            $this->db->where('clicks_views.clicks_views_type IN ("banner","html","viralvideo","invisilinks")');
            $this->db->where('clicks_views.clicks_views_refuser_id', $user_id);
            $this->db->group_by('clicks_views.clicks_views_action_id');
            $getData = $this->db->get_where('clicks_views')->result_array();
        }
        foreach ($getData as $setArray) {
            $getArray[$setArray['affiliateads_id']] = $setArray;
        }
        return $getArray;
    }
    function getAllProduct($user_id, $user_type, $filter = array()){
        if($user_type == 'admin') $clause = ' ';
        else{
            $clause = " user_id = $user_id AND ";
            $clause_orders = " op.refer_id = {$user_id} AND ";
        }

        $left_join = $where = '';
        $vendor = $this->getSettings('vendor');
        if((int)$vendor['storestatus'] == 0){
            $where .= " AND( seller.id=0 OR seller.id IS NULL)";
        }


        if(isset($filter['seller_id'])){
            $where .= " AND pa.user_id=". (int)$filter['seller_id'];
        }

        if(isset($filter['on_store'])){
            $where .= " AND on_store=". (int)$filter['on_store'];
        }

        if(isset($filter['seller_allow_only_status']) && $filter['seller_allow_only_status']){
            $where .= " AND (vs.user_id = ". $filter['seller_allow_only_status'] ." OR  vs.vendor_status = 1 OR vs.user_id IS NULL) ";
            $left_join .= " LEFT JOIN vendor_setting AS vs ON (seller.id = vs.user_id)";
        }

        if(isset($filter['only_admin_product'])){
           $where .= " AND( seller.id=0 OR  seller.id IS NULL) AND on_store=1 ";
        }


        if(isset($filter['not_show_my'])){
           $where .= " AND( seller.id != ". (int)$filter['not_show_my'] ." OR  seller.id IS NULL )";
        }

        if(isset($filter['product_status'])){
            $where .= " AND product.product_status=". (int)$filter['product_status'];
        }

        if(isset($filter['product_status_in'])){
            $where .= " AND product.product_status IN (". $filter['product_status_in'].")";
        }

        if(isset($filter['category_id']) && $filter['category_id']){
            $where .= " AND product.product_id IN ( SELECT product_id FROM product_categories WHERE category_id = ". $filter['category_id'] ." GROUP BY product_id)";
        }

        $limit = '';
        if(isset($filter['limit']) && (int)$filter['limit'] > 0){
            $limit = " LIMIT ". (int)$filter['limit'];
        }
        if(isset($filter['start']) && (int)$filter['start'] && $limit){
            $limit = " LIMIT {$filter['start']} , {$filter['limit']} ";
        }
        if(isset($filter['page']) && $limit){
            $offset = (int)$filter['limit'] * ((int)$filter['page'] - 1);
            $limit = " LIMIT ". $offset ." ,". (int)$filter['limit'];
        }

        $query = "SELECT SQL_CALC_FOUND_ROWS
                product.*,
                seller.firstname as seller_firstname,
                seller.lastname as seller_lastname,
                seller.username as seller_username,
                seller.id as seller_id,
                (
                    SELECT sum(op.commission)
                    FROM order_products op
                        LEFT JOIN `order` o ON (o.id = op.order_id)
                    WHERE
                         {$clause_orders}
                          o.status = 1 AND
                         op.product_id = product.product_id AND o.status > 0 AND op.refer_id > 0) as commission,
                (SELECT count(op.commission) FROM order_products op LEFT JOIN `order` o ON (o.id = op.order_id) WHERE {$clause_orders} op.product_id = product.product_id AND o.status > 0 ) as order_count,
                (SELECT count(action_id) FROM product_action WHERE {$clause} product_id = product.product_id) as commition_click_count,
                (SELECT count(action_id) FROM product_action_admin WHERE product_id = product.product_id) as commition_click_count_admin,
                (SELECT SUM(amount) FROM wallet WHERE {$clause} type = 'click_commission' AND reference_id = product.product_id) as commition_click
            FROM product
                LEFT JOIN product_affiliate pa ON pa.product_id = product.product_id
                LEFT JOIN users as seller ON pa.user_id = seller.id
                {$left_join}
            WHERE 1 {$where} ORDER BY product_created_date DESC {$limit}

            ";

            //((o.payment_method = 'bank_transfer' AND o.status = 1) OR (o.payment_method != 'bank_transfer' AND o.status > 0))
        $data =  $this->db->query($query)->result_array();

        if(isset($filter['page'])){
            $total = $this->db->query("SELECT FOUND_ROWS() AS total")->row()->total;

            return [
                'data'  => $data,
                'total'  => $total,
            ];
        }

        return $data;
    }

    public function getAllProductForVendor($user_id, $user_type, $filter = array()){
        if($user_type == 'admin') $clause = ' ';
        else{
            $clause = " ";
            $clause_orders = " op.vendor_id = {$user_id} AND ";
        }
        $where = '';

        if(isset($filter['seller_id'])){
            $where .= " AND pa.user_id=". (int)$filter['seller_id'];
        }

        if(isset($filter['only_admin_product'])){
           $where .= " AND( seller.id=0 OR  seller.id IS NULL)";
        }

        if(isset($filter['product_status'])){
            $where .= " AND product.product_status=". (int)$filter['product_status'];
        }

        if(isset($filter['product_status_in'])){
            $where .= " AND product.product_status IN (". $filter['product_status_in'].")";
        }

        if(isset($filter['category_id'])){
            $where .= " AND product_id IN ( SELECT product_id FROM product_categories WHERE category_id = ". $filter['category_id'] ." GROUP BY product_id)";
        }

        $query = "SELECT
                product.*,
                seller.firstname as seller_firstname,
                seller.lastname as seller_lastname,
                seller.username as seller_username,
                seller.id as seller_id,
                (
                    SELECT sum(op.commission)
                    FROM order_products op
                        LEFT JOIN `order` o ON (o.id = op.order_id)
                    WHERE
                         {$clause_orders}
                          o.status = 1 AND
                         op.product_id = product.product_id AND o.status > 0 AND op.refer_id > 0) as commission,
                (SELECT count(op.commission) FROM order_products op LEFT JOIN `order` o ON (o.id = op.order_id) WHERE {$clause_orders} op.product_id = product.product_id AND o.status > 0 ) as order_count,
                (SELECT count(action_id) FROM product_action WHERE {$clause} product_id = product.product_id) as commition_click_count,
                (SELECT count(action_id) FROM product_action_admin WHERE product_id = product.product_id) as commition_click_count_admin,
                (SELECT SUM(amount) FROM wallet WHERE {$clause} type = 'click_commission' AND reference_id = product.product_id) as commition_click
            FROM product
                LEFT JOIN product_affiliate pa ON pa.product_id = product.product_id
                LEFT JOIN users as seller ON pa.user_id = seller.id
            WHERE 1 {$where}
                ORDER BY product_created_date DESC
            ";

            //((o.payment_method = 'bank_transfer' AND o.status = 1) OR (o.payment_method != 'bank_transfer' AND o.status > 0))
        $data =  $this->db->query($query)->result_array();

        return $data;
    }
    public function getSellerFromProduct($product_id)   {
        return $this->db->query("SELECT * FROM product_affiliate WHERE product_id=". (int)$product_id ." ")->row();
    }
    public function getSellerSetting($user_id)   {
        return $this->db->query("SELECT * FROM vendor_setting WHERE user_id=". (int)$user_id ." ")->row();
    }
    public function getAllUsersExport($filter = array()){
        $query = 'SELECT
            countries.sortname,
            users.*,
            pd.*,
            pa.paypal_email,
            up.username as under_affiliate

            FROM users
            LEFT JOIN countries ON countries.id = users.Country
            LEFT JOIN users up ON up.id = users.refid
            LEFT JOIN payment_detail pd ON pd.payment_created_by = users.id
            LEFT JOIN paypal_accounts pa ON pa.user_id = users.id
            WHERE
                users.TYPE = "user"
            ORDER BY users.id DESC
        ';

        return $this->db->query($query)->result_array();
    }
    public function getAllUsersNormal($filter = array()){
        $where = '';
        if(isset($filter['country_id']) && (int)$filter['country_id'] > 0){
            $where .= " AND countries.id = ". (int)$filter['country_id'];
        }
        if(isset($filter['name'])){
            $where .= " AND (users.firstname like '%". $filter['name'] ."%' OR users.lastname like '%". $filter['name'] ."%') ";
        }
        if(isset($filter['email'])){
            $where .= " AND users.email like '%". $filter['email'] ."%' ";
        }
        if(isset($filter['id_gt']) && (int)$filter['id_gt'] > 0){
            $where .= " AND users.id > ". (int)$filter['id_gt'];
        }

        $limit = '';
        if(isset($filter['limit']) && (int)$filter['limit'] > 0){
            $limit = " LIMIT ". (int)$filter['limit'];
        }

        if(isset($filter['page'])){
            $offset = (int)$filter['limit'] * ((int)$filter['page'] - 1);
            $limit = " LIMIT ". $offset ." ,". (int)$filter['limit'];
        }

        $query = 'SELECT
            countries.*,
            users.*,
            up.username as under_affiliate

            FROM users
            LEFT JOIN countries ON countries.id = users.Country
            LEFT JOIN users up ON up.id = users.refid
            WHERE
                users.TYPE = "user" '. $where .'
            ORDER BY users.id DESC
        '. $limit;

        $json['data'] =  $this->db->query($query)->result_array();
        $query = 'SELECT count(users.id) as total
            FROM users
            LEFT JOIN countries ON countries.id = users.Country
            LEFT JOIN users up ON up.id = users.refid
            WHERE users.TYPE = "user" '. $where ;

        $json['total'] = $this->db->query($query)->row()->total;
        return $json;

    }

    function getPopulerUsers($filter = array()){
        //(SELECT COUNT(id) FROM integration_clicks_action WHERE is_action=1 AND user_id = users.id) as external_action_click,

        $where = '';
        if(isset($filter['country_id']) && (int)$filter['country_id'] > 0){
            $where .= " AND countries.id = ". (int)$filter['country_id'];
        }
        if(isset($filter['name'])){
            $where .= " AND (users.firstname like '%". $filter['name'] ."%' OR users.lastname like '%". $filter['name'] ."%') ";
        }
        if(isset($filter['email'])){
            $where .= " AND users.email like '%". $filter['email'] ."%' ";
        }
        if(isset($filter['id_gt']) && (int)$filter['id_gt'] > 0){
            $where .= " AND users.id > ". (int)$filter['id_gt'];
        }

        $limit = '';
        if(isset($filter['limit']) && (int)$filter['limit'] > 0){
            $limit = " LIMIT ". (int)$filter['limit'];
        }

        if(isset($filter['page'])){
            $offset = (int)$filter['limit'] * ((int)$filter['page'] - 1);
            $limit = " LIMIT ". $offset ." ,". (int)$filter['limit'];
        }

        $query = 'SELECT
            countries.sortname,
            users.*,
            (SELECT sum(amount) FROM wallet WHERE status > 0 AND wallet.user_id = users.id AND type NOT IN("vendor_sale_commission") ) as all_commition

            FROM users
            LEFT JOIN countries ON countries.id = users.Country

            WHERE
                users.TYPE = "user" '. $where .'
            ORDER BY all_commition DESC
        '. $limit;
        // AND ((o.payment_method = "bank_transfer" AND o.status = 1) )
        //OR (o.payment_method != "bank_transfer" AND o.status > 0)

        $dataUsers = [];

        if(!isset($filter['page'])){
            $dataUsers= $this->db->query($query)->result_array();
        } else{
            $json['data'] =  $this->db->query($query)->result_array();
            $query = 'SELECT count(users.id) as total
                FROM users
                LEFT JOIN countries ON countries.id = users.Country
                LEFT JOIN users up ON up.id = users.refid
                WHERE users.TYPE = "user" '. $where ;

            $json['total'] = $this->db->query($query)->row()->total;
            return $json;
        }

        $this->load->model('Total_model');
        $filterData = [];
        foreach ($dataUsers as $key => $value) {
            $filterData[$key] = $value;
            $filterData[$key]['amount'] = $this->Total_model->getUserBalance($value['id']);
        }

        return $filterData;

    }
    function getAllUsers($filter = array()){
        //(SELECT COUNT(id) FROM integration_clicks_action WHERE is_action=1 AND user_id = users.id) as external_action_click,

        $where = '';
        if(isset($filter['country_id']) && (int)$filter['country_id'] > 0){
            $where .= " AND countries.id = ". (int)$filter['country_id'];
        }
        if(isset($filter['name'])){
            $where .= " AND (users.firstname like '%". $filter['name'] ."%' OR users.lastname like '%". $filter['name'] ."%') ";
        }
        if(isset($filter['email'])){
            $where .= " AND users.email like '%". $filter['email'] ."%' ";
        }
        if(isset($filter['id_gt']) && (int)$filter['id_gt'] > 0){
            $where .= " AND users.id > ". (int)$filter['id_gt'];
        }

        $limit = '';
        if(isset($filter['limit']) && (int)$filter['limit'] > 0){
            $limit = " LIMIT ". (int)$filter['limit'];
        }

        if(isset($filter['page'])){
            $offset = (int)$filter['limit'] * ((int)$filter['page'] - 1);
            $limit = " LIMIT ". $offset ." ,". (int)$filter['limit'];
        }

        $query = 'SELECT
            countries.*,
            users.*,
            up.username as under_affiliate,
            (SELECT sum(amount) FROM wallet WHERE status > 0 AND wallet.user_id = users.id) as all_commition,
            (SELECT sum(amount) FROM wallet WHERE status = 3 AND wallet.user_id = users.id) as paid_commition,
            (SELECT sum(amount) FROM wallet WHERE status = 2 AND wallet.user_id = users.id) as in_request_commiton,
            (SELECT sum(amount) FROM wallet WHERE status IN(1,2) AND wallet.user_id = users.id) as unpaid_commition,

            (SELECT SUM(amount) FROM wallet WHERE type IN ("click_commission","external_click_commission","form_click_commission","affiliate_click_commission") AND is_action=0 AND user_id = users.id) as click_commission,

            (SELECT SUM(amount) FROM wallet WHERE type IN ("external_click_commission") AND is_action=1 AND status > 0 AND user_id = users.id) as action_click_commission,
            (SELECT COUNT(amount) FROM wallet WHERE type IN ("external_click_commission") AND is_action=1 AND status > 0 AND user_id = users.id) as external_action_click,

            (SELECT COUNT(action_id) FROM product_action WHERE user_id = users.id) as click,
            (SELECT COUNT(id) FROM integration_clicks_action WHERE is_action=0 AND user_id = users.id) as external_click,
            (SELECT COUNT(action_id) FROM form_action WHERE user_id = users.id) as form_click,
            (SELECT COUNT(id) FROM affiliate_action WHERE user_id = users.id) as aff_click,

            (SELECT SUM(amount) FROM wallet WHERE type IN ("sale_commission") AND status > 0 AND user_id = users.id) as sale_commission,
            (SELECT SUM(o.total) FROM `order` o LEFT JOIN order_products op ON (o.id = op.order_id) WHERE  op.refer_id = users.id AND ((o.payment_method = "bank_transfer" AND o.status = 1) OR (o.payment_method != "bank_transfer" AND o.status > 0)) ) as amount,
            (SELECT SUM(io.total) FROM `integration_orders` io WHERE io.status > 0 AND io.user_id = users.id) as external_sale_amount

            FROM users
            LEFT JOIN countries ON countries.id = users.Country
            LEFT JOIN users up ON up.id = users.refid
            WHERE
                users.TYPE = "user" '. $where .'
            ORDER BY users.id DESC
        '. $limit;

        if(!isset($filter['page'])){
            return $this->db->query($query)->result_array();
        } else{
            $json['data'] =  $this->db->query($query)->result_array();
            $query = 'SELECT count(users.id) as total
                FROM users
                LEFT JOIN countries ON countries.id = users.Country
                LEFT JOIN users up ON up.id = users.refid
                WHERE users.TYPE = "user" '. $where ;

            $json['total'] = $this->db->query($query)->row()->total;
            return $json;
        }
    }

    public function getAvatar($image){
        if ($image != '')
            return base_url('assets/images/users/'. $image);
        else
            return base_url('assets/vertical/assets/images/users/avatar-1.jpg');
    }
    public $level_count = 0;
    public function getAllUsersTree($filter = array()){
        $children = array();
        $this->level_count = 0;

        $where = '';
        $admin_result= $this->db->query("SELECT id,CONCAT(firstname,' ',lastname) as name,avatar,refid FROM users WHERE type='admin' AND refid = 0")->row();
        if(isset($filter['user_id'])){
            $where .= " AND id=". (int)$filter['user_id'];
        } else {
            $where .= " AND (refid = 0 or refid = ". (int)$admin_result->id ." )";
        }


        $result = $this->db->query("SELECT id,CONCAT(firstname,' ',lastname) as name,avatar,refid FROM users WHERE type='user'  {$where}")->result_array();

        foreach ($result as $key => $value) {
            $c = $this->getAllUsersTreeChildren($value['id'], $filter);
            $children[] = array(
                'text' => array(
                    'name' => $value['name']
                ),
                'image' => $this->getAvatar($value['avatar']),
                'collapsed' => count($c) ? true : false,
                'children' => $c,
            );
        }


        $tree = array(
            'text' => array(
                'name' => $admin_result->name
            ),
            'image' => $this->getAvatar($admin_result->avatar),
            'collapsed' => true,
            'children' => $children
        );

        return $tree;
    }
    public function getAllUsersTreeV2($filter = array()){
        $children = array();
        $this->level_count = 0;

        $where = '';
        $admin_result= $this->db->query("SELECT id,username as name,avatar,refid FROM users WHERE type='admin'")->row();
        if(isset($filter['user_id'])){
            $where .= " AND id=". (int)$filter['user_id'];
        } else {
            //$where .= " AND (refid = 0 or refid = ". (int)$admin_result->id ." )";
        }


        $result = $this->db->query("SELECT id,username as name,avatar,refid FROM users WHERE type='user'  {$where}")->result_array();

        $children[] = array(
            array(
                'v' => $admin_result->id,
                'f' => $admin_result->name ."<img class='user-avtar-tree' src='". $this->getAvatar($admin_result->avatar) ."'>",
            ),
            $admin_result->id,
            $admin_result->name,
        );

        foreach ($result as $key => $value) {

            $children[] = array(
                array(
                    'v' => $value['id'],
                    'f' => $value['name'] ."<img class='user-avtar-tree' src='". $this->getAvatar($value['avatar']) ."'>"
                ),
                ((int)$value['refid'] != 0 ? $value['refid'] : $admin_result->id),
                $value['name']
            );
        }


        return $children;
    }
    private function buildTree(array $elements, $parentId = 0) {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }
    public function getAllinOneQuery($filter = array()){
        $_children = [];
        $admin_result= $this->db->query("SELECT id,username as name,avatar,refid FROM users WHERE type='admin'")->row_array();
        $_children[] = array(
            'id'        => $admin_result['id'],
            'parent_id' => 0,
            'name'      => $admin_result['name'] ."<img class='user-avtar-tree' src='". $this->getAvatar($admin_result['avatar']) ."'>",
        );

        $users= $this->db->query("SELECT id,username as name,avatar,refid FROM users WHERE type='user'")->result_array();
        foreach ($users as $key => $value) {
            $_children[] = array(
                'id'        => $value['id'],
                'parent_id' => $value['refid'] ? $value['refid'] : $admin_result['id'],
                'name'      => $value['name'] ."<img class='user-avtar-tree' src='". $this->getAvatar($value['avatar']) ."'>",
            );
        }

        return $this->buildTree($_children);
    }
    public function getAllUsersTreeV3($filter = array(), $user_id = 0, $first_time = true, $is_admin = false){
        $children = array();

        if(!$is_admin){
            if($first_time){
                $this->level_count = 0;
            }
            $this->level_count++;
            $setting = $this->Product_model->getSettings('referlevel');
            $max_level = isset($setting['levels']) ? $setting['levels'] : 3;

            if($this->level_count >= ($max_level)+2) return array();
        }

        $where = '';
        if(isset($filter['user_id'])){
            $where .= " AND id=". (int)$filter['user_id'];
        }

        $users= $this->db->query("SELECT id,username as name,avatar,refid
         FROM users WHERE type='user' {$where} AND  refid = ". $user_id)->result_array();

        $children = [];
        foreach ($users as $key => $value) {
            $value['children'] = $this->getAllUsersTreeV3($filter, $value['id'], false, $is_admin);
            $children[] = array(
                'name'  => $value['name'] ."<img class='user-avtar-tree' src='". $this->getAvatar($value['avatar']) ."'>",
                'children' => $value['children'],
            );
        }

        if($first_time){
           // $admin_result= $this->db->query("SELECT id,username as name,avatar,refid FROM users WHERE type='admin'")->row_array();
            $user_result= $this->db->query("SELECT id,username as name,avatar,refid FROM users WHERE id={$user_id} AND type='user'")->row_array();
            // echo "<pre>"; print_r($admin_result); echo "</pre>";die;
            $user_children[] = array(
                'name'  => $user_result['name'] ."<img class='user-avtar-tree' src='". $this->getAvatar($user_result['avatar']) ."'>",
                'children' => $children,
            );

            return $user_children;

            /*$_children[] = array(
                'name'  => $admin_result['name'] ."<img class='user-avtar-tree' src='". $this->getAvatar($admin_result['avatar']) ."'>",
                'children' => $user_children,
            );
            return $_children;*/
        }

        return $children;
    }

    public function getAllUsersTreeV2ForUser($user_id, $first_time = true){
        $children = array();
        $result = $this->db->query("SELECT id,username as name,avatar,refid FROM users WHERE type='user'  AND refid=". (int)$user_id)->result_array();

        if($first_time){
            $admin_result= $this->db->query("SELECT id,username as name,avatar,refid FROM users WHERE type='admin'")->row();
            $children[] = array(
                array(
                    'v' => $admin_result->id,
                    'f' => $admin_result->name ."<img class='user-avtar-tree' src='". $this->getAvatar($admin_result->avatar) ."'>"
                ),
                $admin_result->id,
                $admin_result->name,
            );

            $result = $this->db->query("SELECT id,username as name,avatar,refid FROM users WHERE type='user'  AND id=". (int)$user_id)->result_array();

            $children[] = array(
                array(
                    'v' => $result->id,
                    'f' => $result->name ."<img class='user-avtar-tree' src='". $this->getAvatar($result->avatar) ."'>"
                ),
                $admin_result->id,
                $result->name
            );

            $user_id = $admin_result->id;
            $this->level_count = 0;
        }

        $this->level_count++;

        $setting = $this->Product_model->getSettings('referlevel');
        $max_level = isset($setting['levels']) ? $setting['levels'] : 3;

        if($this->level_count >= ($max_level)+2) return array();

        foreach ($result as $key => $value) {
            $_children = $this->getAllUsersTreeV2ForUser($value['id'], false);

            $children[] = array(
                array(
                    'v' => $value['id'],
                    'f' => $value['name'] ."<img class='user-avtar-tree' src='". $this->getAvatar($value['avatar']) ."'>"
                ),
                $user_id,
                $value['name']
            );

            $children = array_merge($_children,$children);
        }

        return $children;
    }
    public function getAllUsersTreeChildren($parent, $filter){
        $children = array();
        $this->level_count ++;

        if($this->level_count <= 3 || !isset($filter['user_id'])){
            $result = $this->db->query("SELECT id,CONCAT(firstname,' ',lastname) as name,avatar,refid FROM users WHERE type='user' AND refid = {$parent}")->result_array();

            foreach ($result as $key => $value) {
                $c = $this->getAllUsersTreeChildren($value['id'], $filter);

                $children[] = array(
                    'text' => array(
                        'name' => $value['name']
                    ),
                    'image' => $this->getAvatar($value['avatar']),
                    'children' => $c,
                    'collapsed' => count($c) ? true : false,
                );
            }

            return $children;
        }

        return array();
    }

    function getAllClients(){
        $query = '
            SELECT
                users.*,

                (SELECT CONCAT(firstname, " " ,lastname) FROM users u WHERE u.id = users.refid) as ref_user,
                (SELECT COUNT(action_id) FROM product_action WHERE user_id = users.id) as click,
                (SELECT SUM(o.total) FROM `order` o WHERE  o.user_id = users.id AND o.status > 0) as amount ,
                (SELECT COUNT(o.id) FROM `order` o WHERE  o.user_id = users.id AND o.status > 0) as total_sale ,
                (SELECT SUM(amount) FROM product_action WHERE type IN ("click_commission","sale_commission") AND user_id = users.id) as commission
            FROM  users
            WHERE TYPE = "client"
            ORDER BY id DESC';
        return $this->db->query($query)->result_array();
    }
    function checkmail($email, $user_id = null){
        if ($user_id) {
            $this->db->where('id !=', $user_id);
        }
        return $this->db->get_where('users', array('email' => $email))->result_array();
    }
    function checkuser($username, $user_id = null){
        if ($user_id) {
            $this->db->where('id !=', $user_id);
        }
        return $this->db->get_where('users', array('username' => $username))->result_array();
    }
    function getAllUserrecord(){
        $this->db->select('countries.*, users.*');
        $this->db->from('users');
        $this->db->where('users.type', 'user');
        $this->db->join('countries', 'countries.id = users.Country', 'left');
        $query = $this->db->get();
        return $query->result_array();
    }
    function getAllUserrecordCount(){
        $this->db->select('count(*) as total');
        $this->db->from('users');
        $this->db->where('users.type', 'user');
        $query = $this->db->get();

        return $query->row()->total;
    }
    function getAllClientrecord(){
        $this->db->order_by('created_at', 'desc');
        return $this->db->get_where('users', array('type' => 'client'))->result_array();
    }
    function getAllUserNew(){
        $this->db->select(array(
            'countries.*',
            'users.*',
            '(SELECT sum(amount) FROM wallet WHERE status = 3 AND wallet.user_id = users.id AND type IN("click_commission","sale_commission")) as paid_commition',
            '(SELECT sum(amount) FROM wallet WHERE status = 1 AND wallet.user_id = users.id AND type IN("click_commission","sale_commission")) as unpaid_commition',
            '(SELECT SUM(o.total) FROM `order` o LEFT JOIN order_products op ON (o.id = op.order_id) WHERE  op.refer_id = users.id AND o.status > 0) as amount',
            '(SELECT COUNT(action_id) FROM product_action WHERE user_id = users.id) as click',
            '(SELECT SUM(amount) FROM wallet WHERE type IN ("click_commission") AND user_id = users.id) as click_commission',
            '(SELECT SUM(amount) FROM wallet WHERE type IN ("sale_commission") AND user_id = users.id) as sale_commission',
            '(SELECT SUM(amount) FROM wallet WHERE type IN ("affiliate_click_commission") AND user_id = users.id) as aff_click_commission',
            '(SELECT COUNT(id) FROM affiliate_action WHERE user_id = users.id) as aff_click',
        ));

        $this->db->from('users');
        $this->db->where('users.type', 'user');
        $this->db->join('countries', 'countries.id = users.Country', 'left');
        $this->db->order_by('users.created_at', 'DESC');
        $this->db->limit(10);
        $query = $this->db->get();
        return $query->result_array();
    }
    function getAllClientNew(){
        $this->db->select('
            countries.*,
            users.*,
            (SELECT SUM(total) FROM `order` WHERE order.user_id = users.id AND status > 0 ) as buy_product_amount,
            (SELECT count(total) FROM `order` WHERE order.user_id = users.id AND status > 0 ) as buy_product
        ');
        $this->db->from('users');
        $this->db->where('users.type', 'client');
        $this->db->join('countries', 'countries.id = users.Country', 'left');
        $this->db->order_by('users.created_at', 'DESC');
        $this->db->limit(10);
        $query = $this->db->get();
        return $query->result_array();
    }
    function getReview($product_id){
        $this->db->select('product.product_name, rating.*');
        $this->db->order_by('rating_created', 'desc');
        $this->db->from('rating');
        $this->db->where('products_id', $product_id);
        $this->db->join('product', 'product.product_id = rating.products_id');
        $query = $this->db->get();
        return $query->result_array();
    }
    function getAllUserOnline(){
        $this->db->order_by('created_at', 'desc');
        $this->db->limit(7);
        return $this->db->get_where('users', array('type' => 'user', 'online' => 1))->result_array();
    }
    function getSettings($type=''){
        $settingdata = array();
        $this->db->where('setting_type', $type);
        $getSetting = $this->db->get_where('setting', array('setting_status' => 1))->result_array();
        foreach ($getSetting as $setting) {
            $settingdata[$setting['setting_key']] = $setting['setting_value'];
        }
        return $settingdata;
    }
    function deletesetting($key, $value, $type){
        $this->db->where('setting_key', $key);
        //$this->db->where('setting_value', $value);
        $this->db->where('setting_type', $type);
        return $this->db->delete('setting');
    }
    function getrefUsers($user_id = null){
        if ($user_id) {
            return $this->db->get_where('users', array('refid' => $user_id,'type' => 'user'))->result_array();
        }
        return false;
    }
    function getAllPayment($id){
        if ($id) {
            return $this->db->get_where('payment_detail', array('payment_status' => 1, 'payment_created_by' => $id))->result_array();
        }
        return false;
    }
    function getPaypalAccounts($user_id){
        $this->db->from("paypal_accounts");
        $this->db->where("user_id", (int)$user_id);
        return $this->db->get()->result_array();
    }
    function getAllBuyProduct($payment_user_id = null){
        if ($payment_user_id) {
            $this->db->join('users', 'users.id=order.user_id');
            $this->db->join('order_products', 'order_products.order_id=order.id');
            $this->db->join('product', 'product.product_id=order_products.product_id');
            $this->db->where('user_id', $payment_user_id);
            $this->db->where('status > 0');
            return $this->db->get('order')->result_array();
        }
    }
    function getAllRefBuyProduct($payment_user_id = null){
        if ($payment_user_id) {
            $this->db->join('users', 'users.id=payment.payment_ref_user_id');
            $this->db->join('product', 'product.product_id=payment.payment_item_id');
            $this->db->where('payment_ref_user_id', $payment_user_id);
            return $this->db->get('payment')->result_array();
        }
    }
    function getorderById($order_id){
        if ($order_id) {
            $this->db->where('payment_id', $order_id);
            $this->db->join('users', 'users.id=payment.payment_user_id');
            $this->db->join('product', 'product.product_id=payment.payment_item_id');
            return $this->db->get_where('payment')->row_array();
        }
    }
    function getcommentById($order_id){
        if ($order_id) {
            $this->db->where('payment_comments_action_id', $order_id);
            return $this->db->get_where('payment_comments')->result_array();
        }
    }
    function orderdelete($order_id = null){
        if ($order_id) {
            $this->db->where('payment_id', $order_id);
            $this->db->delete('payment');
        }
        if ($order_id) {
            $this->db->where('payment_comments_action_id', $order_id);
            $this->db->delete('payment_comments');
        }
    }
    function userdelete($user_id = null, $type = null){
        if ($user_id) {
            $this->db->where('id', $user_id);
            $this->db->where('type', $type);
            $this->db->delete('users');
            /*$this->db->where('payment_ref_user_id', $user_id);
            $this->db->delete('payment');
            $this->db->where('payment_user_id', $user_id);
            $this->db->delete('payment');*/
            /*$this->db->where('affiliateadslog_user_id', $user_id);
            $this->db->delete('affiliateadslog');*/
            $this->db->where('productslog_user_id', $user_id);
            $this->db->delete('productslog');
        }
    }
    function getallorders($user_id = null){

        return array();
    }
    function getallsales($user_id = null){
        if(!empty($user_id)){
            $this->db->where('payment_user_id', $user_id);
        }
        $this->db->join('users', 'users.id=payment.payment_user_id');
        $this->db->join('product', 'product.product_id=payment.payment_item_id');
        return $this->db->get_where('payment', array('payment_item_status' => 'Completed'))->result_array();
    }
    function getallPercentageByallorders($user_id = null){
        return 0;
    }
    function getallPercentageByallsales($user_id = null){
        return 0;
    }
    function getcountry($select = '*'){
        $this->db->select($select);
        $query = $this->db->get('countries');
        return $query->result();
    }
    function getAllstate($country_id = ''){
        $this->db->select('states.*');
        $this->db->where('country_id', $country_id);
        $query = $this->db->get('states');
        return $query->result_array();
    }
    function getnotification($viewfor = null, $user_id){
        $this->db->select('notification.*');
        $this->db->where('notification_view_user_id', $user_id);
        $this->db->where('notification_viewfor', $viewfor);
        $this->db->where('notification_is_read', 0);
        $this->db->order_by('notification_id', 'desc');
        $this->db->limit(10);
        $query = $this->db->get('notification');
        return $query->result_array();
    }
    function getnotificationnew($viewfor = null, $user_id,$limit = 0, $filter = array()){
        $this->db->select('notification.*');
        if($user_id > 0){
            $this->db->where(" (notification_view_user_id = {$user_id} OR notification_view_user_id = 'all')  ",NULL,false);
        }

        if (isset($filter['id_gt'])) {
            $this->db->where('notification_id > '. (int)$filter['id_gt']);
        }
        //$this->db->where('notification_is_read', 0);
        $this->db->where('notification_viewfor', $viewfor);
        $this->db->order_by('notification_id', 'desc');
        if($limit > 0)  $this->db->limit($limit);
        $query = $this->db->get('notification');
        return $query->result_array();
    }
    function getnotificationnew_count($viewfor = null, $user_id){
        $this->db->select('notification.notification_id');
        if($user_id > 0){
            $this->db->where(" (notification_view_user_id = {$user_id} OR notification_view_user_id = 'all')  ",NULL,false);
        }
        //$this->db->where('notification_is_read', 0);
        $this->db->where('notification_viewfor', $viewfor);
        $query = $this->db->get('notification');
        return $query->num_rows();
    }
    function getnotificationall($viewfor = null, $user_id){
        $this->db->select('notification.*');
        $this->db->where('notification_view_user_id', $user_id);
        $this->db->where('notification_viewfor', $viewfor);
        $this->db->order_by('notification_id', 'desc');
        $query = $this->db->get('notification');
        return $query->result_array();
    }
    function deleteusers($id = null){
        if (!empty($id)) {
            $this->db->where('id', $id);
            return $this->db->delete('users');
        }

        return false;
    }
    function deleteproducts($id = null){
        if (!empty($id)) {
            $this->db->query("DELETE FROM product_categories WHERE product_id = {$id} ");
            $this->db->query("DELETE FROM product_affiliate WHERE product_id = {$id} ");
            $this->db->query("DELETE FROM product WHERE product_id = {$id} ");

            return true;
        }
        return false;
    }
    function deleteImage($id = null){
        if (!empty($id)) {
            $this->db->where('product_media_upload_id', $id);
            return $this->db->delete('product_media_upload');
        }
    }
    function getProductAction($product_id, $user_id, $viewer_id = 0){
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $this->db->from('product_action');
        $this->db->where('product_id', $product_id);

        if($viewer_id) $this->db->where('viewer_id', $viewer_id);

        $this->db->where('user_ip', $ip_address);
        $this->db->where('user_id', $user_id);
        $result = $this->db->get()->num_rows();
        return $result;
    }
    function getFormAction($product_id, $user_id, $viewer_id = 0){
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $this->db->from('form_action');
        $this->db->where('form_id', $product_id);
        if($viewer_id) $this->db->where('viewer_id', $viewer_id);
        $this->db->where('user_ip', $ip_address);
        $this->db->where('user_id', $user_id);
        $result = $this->db->get()->num_rows();
        return $result;
    }
    public function calcCommitions($product, $type= 'sale', $shareUser = []){
        $product = (array)$product;
        $seller = $this->db->query("SELECT * FROM product_affiliate WHERE product_id=". (int)$product['product_id'] ." ")->row();
        $product_price = ((int)$product['quantity'] * (float)$product['product_price']);
        $vendor_setting = $this->Product_model->getSettings('vendor');

        if($seller && (int)$vendor_setting['storestatus'] == 1){
            $seller_setting = $this->Product_model->getSellerSetting($seller->user_id);

            $data = [
                'type' => '',
                'commission' => 0,

                'admin_commission_type' => '',
                'admin_commission' => 0,
            ];

            if($shareUser && $shareUser['type'] == 'user' && $product['refer_id'] != $product['vendor_id']){
                if($seller->affiliate_sale_commission_type == 'default'){
                    if($seller_setting->affiliate_sale_commission_type == 'percentage'){
                        $data['type'] = 'Percentage ('. (float)$seller_setting->affiliate_commission_value .'%) ';
                        $data['commission'] = max(($product_price * (float)$seller_setting->affiliate_commission_value),1) / 100;
                    }
                    else if($seller_setting->affiliate_sale_commission_type == 'fixed'){
                        $data['type'] = 'Fixed';
                        $data['commission'] = $seller_setting->affiliate_commission_value;
                    }
                } else if($seller->affiliate_sale_commission_type == 'percentage'){
                    $data['type'] = 'Percentage ('. (float)$seller->affiliate_commission_value  .'%) ';
                    $data['commission'] = max(($product_price * (float)$seller->affiliate_commission_value),1) / 100;

                } else if($seller->affiliate_sale_commission_type == 'fixed'){
                    $data['type'] = 'Fixed';
                    $data['commission'] = (float)$seller->affiliate_commission_value;
                }
            }

            $commnent_line = '';
            if($seller->admin_sale_commission_type == 'default'){
               if($vendor_setting['admin_sale_commission_type'] == 'percentage'){
                    $data['admin_commission_type'] = 'Percentage ('. (float)$vendor_setting['admin_commission_value'] .'%) ';
                    $data['admin_commission'] = max(($product_price * (float)$vendor_setting['admin_commission_value']),1) / 100;
                }
                else if($vendor_setting['admin_sale_commission_type'] == 'fixed'){
                    $data['admin_commission_type'] = 'Fixed';
                    $data['admin_commission'] = $vendor_setting['admin_commission_value'];
                }

            } else if($seller->admin_sale_commission_type == 'percentage'){
                $data['admin_commission_type'] = 'Percentage ('. (float)$seller->admin_commission_value .'%) ';
                $data['admin_commission'] = max(($product_price * (float)$seller->admin_commission_value),1) / 100;

            } else if($seller->admin_sale_commission_type == 'fixed'){
                $data['admin_commission_type'] = 'Fixed';
                $data['admin_commission'] = (float)$seller->admin_commission_value;
            }

            $data['vendor_commission_type'] = 'her_sale';
            $data['vendor_commission'] = max( ($product_price - $data['admin_commission'] - $data['commission']), 0 );

            return $data;
        } else {

            if($type == 'sale'){
                $commission = 0;
                $commissionType = $product['product_commision_type'];
                if($product['product_commision_type'] == 'default'){
                    $commissionSetting = $this->Product_model->getSettings('productsetting');
                    $commissionType = $commissionSetting['product_commission_type'];
                    if($commissionSetting['product_commission_type'] == 'percentage'){
                        $commissionType = 'percentage ('. $commissionSetting['product_commission'] .'%)';
                        $commission = max(($product_price * $commissionSetting['product_commission']),1) / 100;
                    }
                    else if($commissionSetting['product_commission_type'] == 'Fixed'){
                        $commission = $commissionSetting['product_commission'];
                    }
                }
                else if($product['product_commision_type'] == 'percentage'){
                    $commissionType = 'percentage ('. $product['product_commision_value'] .'%)';
                    $commission = max(($product_price * $product['product_commision_value']),1) / 100;
                }
                else if($product['product_commision_type'] == 'fixed'){
                    $commission = $product['product_commision_value'];
                }
            }
            else if($type == 'click'){
                $commission = 0;
                $commissionType = $product['product_commision_type'];
                if($product['product_click_commision_type'] == 'default'){
                    $commissionSetting = $this->Product_model->getSettings('productsetting');
                    $commissionType = $commissionSetting['product_commission_type'];
                    if($commissionSetting['product_commission_type'] == 'percentage'){
                        $commissionType = 'percentage ('. $commissionSetting['product_ppc'] .'%)';
                        $commission = max(($product_price * $commissionSetting['product_ppc']),1) / 100;
                    }
                    else if($commissionSetting['product_commission_type'] == 'Fixed'){
                        $commission = $commissionSetting['product_ppc'];
                    }
                }
                else if($product['product_click_commision_type'] == 'percentage'){
                    $commissionType = 'percentage ('. $product['product_click_commision_value'] .'%)';
                    $commission = max(($product_price * $product['product_click_commision_value']),1) / 100;
                }
                else if($product['product_click_commision_type'] == 'fixed'){
                    $commission = $product['product_click_commision_value'];
                }
            }

            return array(
                'type' => strtolower($commissionType),
                'commission' => (float)$commission,
            );
        }
    }
    public function formcalcCommitions($product, $type= 'sale', $shareUser = []){
        $product = (array)$product;
        $product_price = ((int)$product['quantity'] * (float)$product['product_price']);
       
        if($type == 'sale'){
            $commission = 0;
            $commissionType = $product['product_commision_type'];
            if($product['product_commision_type'] == 'default'){
                $commissionSetting = $this->Product_model->getSettings('formsetting');
                $commissionType = $commissionSetting['product_commission_type'];
                if($commissionSetting['product_commission_type'] == 'percentage'){
                    $commissionType = 'percentage ('. $commissionSetting['product_commission'] .'%)';
                    $commission = max(($product_price * $commissionSetting['product_commission']),1) / 100;
                }
                else if($commissionSetting['product_commission_type'] == 'Fixed'){
                    $commission = $commissionSetting['product_commission'];
                }
            }
            else if($product['product_commision_type'] == 'percentage'){
                $commissionType = 'percentage ('. $product['product_commision_value'] .'%)';
                $commission = max(($product_price * $product['product_commision_value']),1) / 100;
            }
            else if($product['product_commision_type'] == 'fixed'){
                $commission = $product['product_commision_value'];
            }
        } else if($type == 'click'){
            $commission = 0;
            $commissionType = $product['product_commision_type'];
            if($product['product_click_commision_type'] == 'default'){
                $commissionSetting = $this->Product_model->getSettings('formsetting');
                $commissionType = $commissionSetting['product_commission_type'];
                if($commissionSetting['product_commission_type'] == 'percentage'){
                    $commissionType = 'percentage ('. $commissionSetting['product_ppc'] .'%)';
                    $commission = max(($product_price * $commissionSetting['product_ppc']),1) / 100;
                }
                else if($commissionSetting['product_commission_type'] == 'Fixed'){
                    $commission = $commissionSetting['product_ppc'];
                }
            }
            else if($product['product_click_commision_type'] == 'percentage'){
                $commissionType = 'percentage ('. $product['product_click_commision_value'] .'%)';
                $commission = max(($product_price * $product['product_click_commision_value']),1) / 100;
            }
            else if($product['product_click_commision_type'] == 'fixed'){
                $commission = $product['product_click_commision_value'];
            }
        }

        return array(
            'type' => strtolower($commissionType),
            'commission' => (float)$commission,
        );
        
    }

    function getProductActionIncrese($product_id, $user_id, $viewer_id =0){
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $this->db->from('product_action');
        $this->db->where('action_type', 'click');
        $this->db->where('product_id', $product_id);
        $this->db->where('user_id', $user_id);
        if($viewer_id) $this->db->where('viewer_id', $viewer_id);
        $this->db->where('user_ip', $ip_address);
        $result_array = $this->db->get()->row_array();
        if($result_array){
            $this->db->update(
                'product_action',
                array('counter'=> ($result_array['counter']+1)) ,
                array('action_id' => $result_array['action_id'])
            );
        }
    }
    function getFormActionIncrese($form_id, $user_id, $viewer_id =0){
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $this->db->from('form_action');
        $this->db->where('action_type', 'click');
        $this->db->where('form_id', $form_id);
        $this->db->where('user_id', $user_id);
        if($viewer_id) $this->db->where('viewer_id', $viewer_id);
        $this->db->where('user_ip', $ip_address);
        $result_array = $this->db->get()->row_array();
        if($result_array){
            $this->db->update(
                'product_action',
                array('counter'=> ($result_array['counter']+1)) ,
                array('action_id' => $result_array['action_id'])
            );
        }
    }

    public function giveAdminClickCommition($product){
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $this->db->from('product_action_admin');
        $this->db->where('product_id', (int)$product['product_id']);
        $this->db->where('user_ip', $ip_address);
        $this->db->where('user_id', 1);
        $match = $this->db->get()->num_rows();

        if ($match == 0){
            $new_record = array(
                'action_type'  => 'click',
                'product_id'   => (int)$product['product_id'],
                'user_id'      => 1,
                'user_ip'      => $ip_address,
                'created_at'   => date('Y-m-d h:i:s'),
                'counter'      => 1,
                'country_code' => @$this->ip_info()['country_code'],
            );
            $this->db->insert('product_action_admin', $new_record);
        } else {
            $this->db->set('counter','`counter` + 1', false);
            $this->db->where('action_type', 'click');
            $this->db->where('product_id', (int)$product['product_id']);
            $this->db->where('user_id', 1);
            $this->db->where('user_ip', $ip_address);
            $this->db->update('product_action_admin');
        }

        $product_id = $product['product_id'];
        $totalClick = $this->db->query("SELECT * FROM  product_action_admin WHERE pay_commition = 0 AND user_id = 1 AND  product_id = '{$product_id}' ");

        $wallet_group_id = time();

        if(isset($product['seller']['id'])){
            $needClick = 0;
            $payPerClick = 0;

            if($product['seller']['admin_click_commission_type'] == 'default'){
                $vendor_setting = $this->getSettings('vendor');
                $needClick = (int)$vendor_setting['admin_click_count'];
                $payPerClick = (float)$vendor_setting['admin_click_amount'];
            } else{
                $needClick = (int)$product['seller']['admin_click_count'];
                $payPerClick = $product['seller']['admin_click_amount'];
            }

            if($needClick && $payPerClick){
                $tC = $totalClick->num_rows();
                if($tC >= $needClick){
                    $ips = [];

                    foreach ($totalClick->result() as $vv) {
                        $ips[] = array('ip' => $vv->user_ip,'country_code' => $vv->country_code);
                    }

                    $this->Wallet_model->addTransaction(array(
                        'status'         => 3,
                        'user_id'        => 1,
                        'amount'         => $payPerClick,
                        'comment'        => "Commission for {$tC} click on product_id={$product_id} <br> Clicked done from ip_message",
                        'type'           => 'click_commission',
                        'reference_id'   => $product_id,
                        'reference_id_2' => 'vendor_click_commission_for_admin',
                        'ip_details'     => json_encode($ips),
                        'group_id'       => $wallet_group_id,
                        'is_vendor'      => 1,
                    ));

                    $this->Wallet_model->addTransaction(array(
                        'status'         => 1,
                        'user_id'        => $product['seller']['user_id'],
                        'amount'         => -$payPerClick,
                        'comment'        => "Pay Commission for {$tC} click on product_id={$product_id} <br> Clicked done from ip_message",
                        'type'           => 'click_commission',
                        'reference_id'   => $product_id,
                        'reference_id_2' => 'vendor_pay_click_commission_for_admin',
                        'ip_details'     => json_encode($ips),
                        'group_id'       => $wallet_group_id,
                        'is_vendor'      => 1,
                    ));

                    $this->db->query("UPDATE product_action_admin SET pay_commition = 1 WHERE user_id = 1 AND  product_id = '{$product_id}' ");
                }
            }
        }


    }
    public function giveClickCommition($product, $user_id, $viewer_id= 0){
        $product_id = $product['product_id'];
        $totalClick = $this->db->query("SELECT * FROM  product_action WHERE pay_commition = 0 AND user_id = '{$user_id}' AND  product_id = '{$product_id}' ");
        $wallet_group_id = time();
        $commission = 0;
        $needClick = 0;
        $payPerClick = 0;

        $minus_amount = 0;
        $reference_id_2 = '';
        if(isset($product['seller']['id'])){
            if($product['seller']['affiliate_click_commission_type'] == 'default'){
                $seller_setting = $this->Product_model->getSellerSetting($product['seller']['user_id']);
                $needClick = (int)$seller_setting->affiliate_click_count;
                $payPerClick = $seller_setting->affiliate_click_amount;
            } else{
                $needClick = (int)$product['seller']['affiliate_click_count'];
                $payPerClick = $product['seller']['affiliate_click_amount'];
            }

            $reference_id_2 = 'vendor_click_commission';
            $minus_amount = $product['seller']['user_id'];
        } else {
            if($product['product_click_commision_type'] == 'custom'){
                $payPerClick = (float)$product['product_click_commision_ppc'];
                $needClick = (int)$product['product_click_commision_per'];
            }else{
                $commissionSetting = $this->getSettings('productsetting');
                $needClick = (int)$commissionSetting['product_noofpercommission'];
                $payPerClick = (float)$commissionSetting['product_ppc'];
            }
        }

        if($needClick && $payPerClick){
            $tC = $totalClick->num_rows();
            if($tC >= $needClick){
                $ips = [];

                foreach ($totalClick->result() as $vv) {
                    $ips[] = array(
                        'ip' => $vv->user_ip,
                        'country_code' => $vv->country_code,
                    );
                }

                $this->Wallet_model->addTransaction(array(
                    'status'         => 1,
                    'user_id'        => $user_id,
                    'amount'         => $payPerClick,
                    'comment'        => "Commission for {$tC} click on product_id={$product_id} <br> Clicked done from ip_message",
                    'type'           => 'click_commission',
                    'reference_id'   => $product_id,
                    'reference_id_2' => $reference_id_2,
                    'ip_details'     => json_encode($ips),
                    'group_id'       => $wallet_group_id,
                    'is_vendor'      => $minus_amount ? 1 : 0,
                ));
                $this->db->query("UPDATE  product_action SET pay_commition = 1 WHERE user_id = '{$user_id}' AND  product_id = '{$product_id}' ");

                if($minus_amount > 0){
                    $this->Wallet_model->addTransaction(array(
                        'status'         => 1,
                        'user_id'        => $minus_amount,
                        'amount'         => -$payPerClick,
                        'comment'        => "Pay Commission for {$tC} click on product_id={$product_id} <br> Clicked done from ip_message",
                        'type'           => 'click_commission',
                        'reference_id'   => $product_id,
                        'reference_id_2' => 'vendor_pay_click_commission',
                        'ip_details'     => json_encode($ips),
                        'group_id'       => $wallet_group_id,
                        'is_vendor'      => $minus_amount ? 1 : 0,
                    ));
                }
            }
        }

    }
    public function giveFormClickCommition($form, $user_id, $viewer_id= 0){
        $form_id = $form['form_id'];
        $totalClick = $this->db->query("SELECT * FROM  form_action WHERE pay_commition = 0 AND user_id = '{$user_id}' AND  form_id = '{$form_id}' ");
        $commission = 0;
        $needClick = 0;
        $payPerClick = 0;

        $wallet_group_id = time();

        if((int)$form['vendor_id'] == 0){
            if($form['click_commision_type'] == 'default'){
                $commissionSetting = $this->getSettings('formsetting');
                $needClick = (int)$commissionSetting['product_noofpercommission'];
                $payPerClick = (float)$commissionSetting['product_ppc'];
            }else{
                $needClick = (int)$form['click_commision_ppc'];
                $payPerClick = (float)$form['click_commision_ppc'];
            }
        } else {
            $vendor_setting = $this->db->query("SELECT * FROM vendor_setting WHERE user_id=". (int)$form['vendor_id'] ." ")->row();
            
            if($form['click_commision_type'] == 'default'){
                $needClick = (int)$vendor_setting->form_affiliate_click_count;
                $payPerClick = (float)$vendor_setting->form_affiliate_click_amount;
            }else{
                $needClick = (int)$form['click_commision_ppc'];
                $payPerClick = (float)$form['click_commision_ppc'];
            }
        }

        if($needClick && $payPerClick){
            $tC = $totalClick->num_rows();

            if($tC >= $needClick){
                $ips = [];
                foreach ($totalClick->result() as $vv) {
                    $ips[] = array(
                        'ip' => $vv->user_ip,
                        'country_code' => $vv->country_code,
                    );
                }

                $this->Wallet_model->addTransaction(array(
                    'user_id'      => $user_id,
                    'amount'       => $payPerClick,
                    'comment'      => "Commission for {$tC} click on form_id={$form_id} <br> Clicked done from ip_message",
                    'type'         => 'form_click_commission',
                    'reference_id' => $form_id,
                    'reference_id_2' =>'vendor_form_click_commission',
                    'ip_details'   => json_encode($ips),
                    'group_id' => $wallet_group_id,
                ));
                $this->db->query("UPDATE  form_action SET pay_commition = 1 WHERE user_id = '{$user_id}' AND  form_id = '{$form_id}' ");
            }
        }
    }
    function setFormClicks($form_id, $user_id, $viewer_id = 0){
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $this->db->from('form_action');
        $this->db->where('action_type', 'click');
        $this->db->where('form_id', $form_id);
        $this->db->where('user_id', $user_id);
        $this->db->where('user_ip', $ip_address);
        $result = $this->db->get()->num_rows;
        if($result == 0){
            $cdate = date('Y-m-d h:i:s');
            $new_record = array(
                'action_type'  => 'click',
                'form_id'      => $form_id,
                'user_id'      => $user_id,
                'user_ip'      => $ip_address,
                'created_at'   => $cdate,
                'counter'      => 1,
                'country_code' => @$this->ip_info()['country_code'],
            );
            if($viewer_id) $new_record['viewer_id'] = $viewer_id;
            $this->db->insert('form_action', $new_record);
        }
    }
    function setClicks($product_id, $user_id, $viewer_id = 0){
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $this->db->from('product_action');
        $this->db->where('action_type', 'click');
        $this->db->where('product_id', $product_id);
        $this->db->where('user_id', $user_id);
        $this->db->where('user_ip', $ip_address);
        $result = $this->db->get()->num_rows();
        if($result == 0){
            $cdate = date('Y-m-d h:i:s');
            $new_record = array(
                'action_type'  => 'click',
                'product_id'   => $product_id,
                'user_id'      => $user_id,
                'user_ip'      => $ip_address,
                'created_at'   => $cdate,
                'counter'      => 1,
                'country_code' => @$this->ip_info()['country_code'],
            );
            if($viewer_id) $new_record['viewer_id'] = $viewer_id;
            $this->db->insert('product_action', $new_record);
        }

    }
    public function referClick($product_id, $user_id, $viewer_id = 0) {

        $store_commition_setting = $this->Product_model->getSettings('referlevel');
        $disabled_for = json_decode( (isset($store_commition_setting['disabled_for']) ? $store_commition_setting['disabled_for'] : '[]'),1);
        if((int)$store_commition_setting['status'] == 0){ return false; }
        else if((int)$store_commition_setting['status'] == 2 && in_array($user_id, $disabled_for)){ return false; }

        $ip_address = $_SERVER['REMOTE_ADDR'];
        $level = $this->getMyLevel($user_id);

        $count_for = '';
        $setting = $this->Product_model->getSettings('referlevel');
        $max_level = isset($setting['levels']) ? (int)$setting['levels'] : 3;
        for ($l=1; $l <= $max_level ; $l++) {
            $count_for .= (int)$level['level'. $l] > 0 ? $level['level'. $l] ."," : "";
        }

        if ($count_for) {
            $this->db->from('refer_product_action');
            $this->db->where('action_type', 'click');
            $this->db->where('product_id', $product_id);
            $this->db->where('user_id', $user_id);
            $this->db->where('user_ip', $ip_address);
            $result = $this->db->get()->num_rows();
            if($result == 0){
                $new_record = array(
                    'action_type' => 'click',
                    'product_id'  => $product_id,
                    'count_for'   => trim($count_for,","),
                    'user_id'     => $user_id,
                    'user_ip'     => $ip_address,
                    'created_at'  => date('Y-m-d h:i:s'),
                    'counter'     => 1,
                );
                if($viewer_id) $new_record['viewer_id'] = $viewer_id;

                $this->db->insert('refer_product_action', $new_record);
            }
        }

        /* Give Ferer Commition */
        $totalClick = $this->db->query("SELECT count(*) as total FROM  refer_product_action WHERE pay_commition = 0 AND user_id = '{$user_id}' AND  product_id = '{$product_id}' ")->row()->total;

        $_needClick = (int)$store_commition_setting['click'];

        if($totalClick >= $_needClick){
            $this->load->model('Mail_model');
            $max_level = isset($setting['levels']) ? (int)$setting['levels'] : 3;

            for ($l=1; $l <= $max_level ; $l++) {
                $s = $this->Product_model->getSettings('referlevel_'. $l);
                $levelUser = (int)$level['level'. $l];
                if($s && $levelUser > 0){
                    $_giveAmount = (float)$s['commition'];
                    if($_giveAmount > 0){
                        $this->Wallet_model->addTransaction(array(
                            'status'       => 1,
                            'user_id'      => $levelUser,
                            'amount'       => $_giveAmount,
                            'dis_type'     => '',
                            'comment'      => "Level {$l} Commition  For {$totalClick} click on product ",
                            'type'         => 'refer_click_commission',
                            'reference_id' => $product_id,
                        ));
                    }
                }
            }
            $this->db->query("UPDATE  refer_product_action SET pay_commition = 1 WHERE user_id = '{$user_id}' AND  product_id = '{$product_id}' ");
        }
    }
    public function getMyUnder($user_id, $first_time = true){

        /*return $this->db->query("SELECT
            id,
            firstname,
            lastname,
            email,
            (SELECT sum(amount) FROM wallet WHERE type = 'refer_click_commission' AND user_id = users.id ) as product_click_commition,
            (SELECT sum(amount) FROM wallet WHERE type = 'refer_sale_commission'  AND user_id = users.id ) as product_sale_commition,
            (SELECT count(*)    FROM wallet WHERE type = 'refer_sale_commission'  AND user_id = users.id ) as product_sale_count,
            (SELECT sum(amount) FROM wallet WHERE type = 'store_m_commission' AND user_id = users.id ) as market_commition,
            (SELECT count(*) FROM refer_product_action WHERE find_in_set(users.id,count_for) ) as product_sale_click,
            (SELECT count(*) FROM refer_market_action WHERE find_in_set(users.id,count_for) ) as market_click,
            username
         FROM users WHERE type='user' AND  refid = ". $user_id)->result_array();*/

        if($first_time){
            $this->level_count = 0;
        }


        $this->level_count++;
        $setting = $this->Product_model->getSettings('referlevel');
        $max_level = isset($setting['levels']) ? $setting['levels'] : 3;

        if($this->level_count >= ($max_level)+2) return array();

        $users= $this->db->query("SELECT
            id,
            CONCAT(firstname,' ',lastname) AS title,
            email,

            (SELECT sum(amount) FROM wallet WHERE status > 0 AND wallet.user_id = users.id) as all_commition,
            (SELECT sum(amount) FROM wallet WHERE status = 3 AND wallet.user_id = users.id) as paid_commition,
            (SELECT sum(amount) FROM wallet WHERE status = 2 AND wallet.user_id = users.id) as in_request_commiton,
            (SELECT sum(amount) FROM wallet WHERE status IN(1,2) AND wallet.user_id = users.id) as unpaid_commition,

            (SELECT SUM(amount) FROM wallet WHERE type IN ('click_commission','external_click_commission','form_click_commission','affiliate_click_commission') AND is_action=0 AND user_id = users.id) as click_commission,
            (SELECT COUNT(action_id) FROM product_action WHERE user_id = users.id) as click,

            (SELECT SUM(amount) FROM wallet WHERE type IN ('external_click_commission') AND is_action=1 AND user_id = users.id) as action_click_commission,
            (SELECT COUNT(id) FROM integration_clicks_action WHERE is_action=1 AND user_id = users.id) as external_action_click,

            (SELECT COUNT(id) FROM integration_clicks_action WHERE page_name = '' AND is_action=0 AND user_id = users.id) as external_click,

            (SELECT COUNT(action_id) FROM form_action WHERE user_id = users.id) as form_click,
            (SELECT COUNT(id) FROM affiliate_action WHERE user_id = users.id) as aff_click,

            (SELECT SUM(amount) FROM wallet WHERE type IN ('sale_commission') AND status > 0 AND user_id = users.id) as sale_commission,
            (SELECT SUM(o.total) FROM `order` o LEFT JOIN order_products op ON (o.id = op.order_id) WHERE  op.refer_id = users.id AND ((o.payment_method = 'bank_transfer' AND o.status = 1) OR (o.payment_method != 'bank_transfer' AND o.status > 0)) ) as amount,
            (SELECT SUM(io.total) FROM `integration_orders` io WHERE io.status > 0 AND io.user_id = users.id) as external_sale_amount,

            username
         FROM users WHERE type='user' AND  refid = ". $user_id)->result_array();

        $children = [];
        foreach ($users as $key => $value) {
            $value['children'] = $this->getMyUnder($value['id'], false);
            $children[] = array(
                'title'                       => $value['title'],
                'email'                       => $value['email'],
                'click'                       => (int)$value['click'],
                'external_click'              => (int)$value['external_click'],
                'form_click'                  => (int)$value['form_click'],
                'aff_click'                   => (int)$value['aff_click'],
                'click_commission'            => c_format($value['click_commission']),
                'external_action_click'       => (int)$value['external_action_click'],
                'action_click_commission'     => c_format($value['action_click_commission']),
                'amount_external_sale_amount' => c_format($value['amount'] + $value['external_sale_amount']),
                //'external_sale_amount'      => $value['external_sale_amount'],
                'sale_commission'             => c_format($value['sale_commission']),
                'paid_commition'              => c_format($value['paid_commition']),
                'unpaid_commition'            => c_format($value['unpaid_commition']),
                'in_request_commiton'         => c_format($value['in_request_commiton']),
                'all_commition'               => c_format($value['all_commition']),
                'children'               => $value['children'],
            );
        }

        return $children;

    }
    public function getReferalTotals($user_id = 0){
        $where = '';
        if($user_id > 0){
            $where .= " AND user_id=". $user_id;
            $find_in_set .= " AND find_in_set({$user_id},count_for)";
        }

        /* Products Click */
        $data['total_product_click']            = $this->db->query("SELECT sum(amount) as amounts FROM wallet WHERE type IN ('refer_click_commission')  AND is_action = 0 AND (page_name = '' OR page_name is NULL) ". $where)->row_array();

        $data['total_product_click']['clicks']  = $this->db->query("SELECT count(*) as counts FROM refer_product_action WHERE 1  $find_in_set ")->row()->counts;

        $data['total_product_click']['clicks']  += $this->db->query("SELECT count(*) as counts FROM integration_refer_product_action WHERE is_action = 0 AND page_name = '' $find_in_set ")->row()->counts;

        $clicks = $this->db->query("SELECT sum(amount) as amounts,status FROM wallet WHERE type = 'refer_click_commission' AND is_action = 0 AND page_name = '' ". $where ." GROUP BY status")->result_array();
        foreach ($clicks as $key => $value) {
            if($value['status'] == 3){
                $data['total_product_click']['paid'] = $value['amounts'];
            }
            else if($value['status'] == 2){
                $data['total_product_click']['request'] = $value['amounts'];
            }
            else if($value['status'] == 1){
                $data['total_product_click']['unpaid'] = $value['amounts'];
            }
        }

        /*  Ganeral Click */
        $G_clicks = $this->db->query("SELECT sum(amount) as amounts,status FROM `wallet` WHERE 1 {$where} AND wallet.type = 'refer_click_commission' AND wallet.comm_from = 'ex'  AND reference_id_2 IN ('__general_click__') GROUP BY status ")->result_array();
        foreach ($G_clicks as $key => $value) {
            $data['total_ganeral_click']['total_amount'] = $value['amounts'];
            if($value['status'] == 3){
                $data['total_ganeral_click']['paid'] = $value['amounts'];
            }
            else if($value['status'] == 2){
                $data['total_ganeral_click']['request'] = $value['amounts'];
            }
            else if($value['status'] == 1){
                $data['total_ganeral_click']['unpaid'] = $value['amounts'];
            }
        }

        $data['total_ganeral_click']['total_clicks'] = $this->db->query("SELECT count(*) as amounts FROM `integration_refer_product_action` WHERE 1 {$find_in_set} AND is_action  = 0 AND page_name != '' ")->row()->amounts;

        /* Action Counts */

        $data['total_action']['click_count'] = $this->db->query("SELECT count(*) as total FROM `integration_refer_product_action` WHERE 1 {$find_in_set} AND is_action  = 1")->row()->total;
        $a_clicks = $this->db->query("SELECT sum(amount) as amounts , status FROM `wallet` WHERE 1  {$where} AND  wallet.type = 'refer_click_commission' AND wallet.comm_from = 'ex' AND wallet.is_action = 1 GROUP BY status ")->result_array();

        foreach ($a_clicks as $key => $value) {
            $data['total_action']['total_amount'] = $value['amounts'];
            if($value['status'] == 3){
                $data['total_action']['paid'] = $value['amounts'];
            }
            else if($value['status'] == 2){
                $data['total_action']['request'] = $value['amounts'];
            }
            else if($value['status'] == 1){
                $data['total_action']['unpaid'] = $value['amounts'];
            }
        }

        /* Sale Counts */
        $data['total_product_sale']             = $this->db->query("SELECT sum(amount) as amounts,count(*) as counts FROM wallet WHERE status > 0 AND type = 'refer_sale_commission' ". $where)->row_array();
        $data['total_product_sale']['paid']     = $this->db->query("SELECT sum(amount) as amounts FROM wallet WHERE status=3 AND type = 'refer_sale_commission' ". $where)->row()->amounts;
        $data['total_product_sale']['request']  = $this->db->query("SELECT sum(amount) as amounts FROM wallet WHERE status=2 AND type = 'refer_sale_commission' ". $where)->row()->amounts;
        $data['total_product_sale']['unpaid']   = $this->db->query("SELECT sum(amount) as amounts FROM wallet WHERE status=1 AND type = 'refer_sale_commission' ". $where)->row()->amounts;


        return $data;
    }
    public function setAffiliateStoreClick($affiliate_id, $user_id, $affiliateads_type){

        $store_commition_setting = $this->Product_model->getSettings('referlevel');
        $disabled_for = json_decode( (isset($store_commition_setting['disabled_for']) ? $store_commition_setting['disabled_for'] : '[]'),1);
        if((int)$store_commition_setting['status'] == 0){ return false; }
        else if((int)$store_commition_setting['status'] == 2 && in_array($user_id, $disabled_for)){ return false; }


        $ip_address = $_SERVER['REMOTE_ADDR'];
        $level = $this->getMyLevel($user_id);
        $cdate = date('Y-m-d h:i:s');
        $count_for = '';
        $count_for .= (int)$level['level1'] > 0 ? $level['level1'] ."," : "";
        $count_for .= (int)$level['level2'] > 0 ? $level['level2'] ."," : "";
        $count_for .= (int)$level['level3'] > 0 ? $level['level3'] ."," : "";
        if($count_for){
            $this->db->from('refer_market_action');
            $this->db->where('affiliate_id', $affiliate_id);
            $this->db->where('user_id', $user_id);
            $this->db->where('user_ip', $ip_address);
            $result = $this->db->get()->num_rows()  ;

            if($result == 0){
                $new_record = array(
                    'affiliate_id' => $affiliate_id,
                    'user_id'      => $user_id,
                    'count_for' => trim($count_for,","),
                    'user_ip'      => $ip_address,
                    'created_at'   => $cdate,
                    'commission'   => 0
                );
                $this->db->insert('refer_market_action', $new_record);
            }
            $this->db->select('COUNT(*) AS total');
            $this->db->from('refer_market_action');
            $this->db->where('user_id',$user_id);
            $this->db->where('commission',0);
            $totalClick = $this->db->get()->row()->total;
            //$store_commition_setting = $this->Product_model->getSettings('referlevel');
            $_needClick = (int)$store_commition_setting['m_click'];
            if($totalClick >= $_needClick){
                $setting = $this->Product_model->getSettings('referlevel');
                $max_level = isset($setting['levels']) ? (int)$setting['levels'] : 3;

                $this->load->model('Mail_model');
                for ($l=1; $l <= $max_level ; $l++) {
                //foreach (array(1,2,3) as $l) {
                    $s = $this->Product_model->getSettings('referlevel_'. $l);
                    $levelUser = (int)$level['level'. $l];
                    if($s && $levelUser > 0){
                        $_giveAmount = (float)$s['m_commition'];
                        //$this->Mail_model->market_click_notification($user_id,$affiliate_id,$affiliateads_type,$setting['affiliate_commission']);
                        $this->Wallet_model->addTransaction(array(
                            'user_id' => $levelUser,
                            'amount' => $_giveAmount,
                            'dis_type' => $affiliateads_type,
                            'comment' => "Level {$l} Commition  For {$totalClick} click on Affiliate link",
                            'type' => 'store_m_commission',
                            'reference_id' => $affiliate_id,
                        ));
                    }
                }
                $this->db->query("UPDATE  refer_market_action SET commission = 1 WHERE user_id = '{$user_id}' AND  affiliate_id = '{$affiliate_id}' ");
            }
        }
    }
    public function getMyLevel($user_id){
        $setting = $this->getSettings('referlevel');
        $max_level = isset($setting['levels']) ? (int)$setting['levels'] : 3;

        $select = [];
        $join=  [];
        for ($i=1; $i <= $max_level ; $i++) {
            $select[] = "l{$i}.id as level{$i},l{$i}.firstname as name_level{$i}";
            $join[] = " LEFT JOIN users as l{$i} ON (l{$i}.id=l". ($i-1) .".refid AND  l{$i}.type = 'user') ";
        }

        $q = $this->db->query("SELECT ". implode(",", $select) .", l0.id as myid
            FROM `users` as l0
            ". implode(" ", $join) ."
            WHERE l0.id=" . (int)$user_id
        )->row_array();

        // echo "<pre>"; print_r($q); echo "</pre>";die;
        return $q;

        $q = $this->db->query("SELECT
                l3.id as level3,
                l2.id as level2,
                l1.id as level1,
                l.id as myid
            FROM `users` as l
            LEFT JOIN users as l1 ON (l1.id=l.refid AND  l1.type = 'user')
            LEFT JOIN users as l2 ON (l2.id=l1.refid AND l2.type = 'user')
            LEFT JOIN users as l3 ON (l3.id=l2.refid AND l3.type = 'user')
            WHERE l.id=" . (int)$user_id
        )->row_array();
    }
    public function ip_info($ip = false) {
        $output = array('country_code' => '');

        if(!$ip){
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if (filter_var($ip, FILTER_VALIDATE_IP)) {

            $curl = curl_init("http://www.geoplugin.net/json.gp?ip=" . $ip);
            $request = '';
            curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $ipdat = json_decode(curl_exec($curl));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                $output = array(
                    "ip"             => $ip,
                    "city"           => @$ipdat->geoplugin_city,
                    "state"          => @$ipdat->geoplugin_regionName,
                    "country"        => @$ipdat->geoplugin_countryName,
                    "country_code"   => @$ipdat->geoplugin_countryCode,
                    "continent_code" => @$ipdat->geoplugin_continentCode,
                    "regionCode"     => @$ipdat->geoplugin_regionCode,
                    "regionName"     => @$ipdat->geoplugin_regionName,
                    "countryCode"    => @$ipdat->geoplugin_countryCode,
                    "countryName"    => @$ipdat->geoplugin_countryName,
                    "continentName"  => @$ipdat->geoplugin_continentName,
                    "timezone"       => @$ipdat->geoplugin_timezone,
                    "currencyCode"   => @$ipdat->geoplugin_currencyCode,
                    "currencySymbol" => @$ipdat->geoplugin_currencySymbol,
                );
            }
        }

        return $output;
    }

    function setAffiliateClick($affiliate_id, $user_id, $affiliateads_type){

        $ip_address = $_SERVER['REMOTE_ADDR'];
        $this->db->from('affiliate_action');
        $this->db->where('affiliate_id', $affiliate_id);
        $this->db->where('user_id', $user_id);
        $this->db->where('user_ip', $ip_address);
        $result = $this->db->get()->num_rows();

        if($result == 0){
            $cdate = date('Y-m-d h:i:s');
            $new_record = array(
                'affiliate_id' => $affiliate_id,
                'user_id'      => $user_id,
                'user_ip'      => $ip_address,
                'created_at'   => $cdate,
                'commission'   => 0,
                'country_code' => @$this->ip_info()['country_code'],
            );
            $this->db->insert('affiliate_action', $new_record);


            $this->db->select('*');
            $this->db->from('affiliate_action');
            $this->db->where('user_id',$user_id);
            $this->db->where('affiliate_id',$affiliate_id);
            $this->db->where('commission',0);
            $totalClick = $this->db->get();

            $setting = $this->Product_model->getSettings('affiliateprogramsetting');

            if($setting['affiliate_ppc'] <= $totalClick->num_rows()){
                $tC = $totalClick->num_rows();
                $ips = [];

                foreach ($totalClick->result() as $vv) {
                    $ips[] = array(
                        'ip' => $vv->user_ip,
                        'country_code' => $vv->country_code,
                    );
                }

                $this->load->model('Mail_model');
                $this->Mail_model->market_click_notification($user_id,$affiliate_id,$affiliateads_type,$setting['affiliate_commission']);
                $this->Wallet_model->addTransaction(array(
                    'user_id'      => $user_id,
                    'amount'       => $setting['affiliate_commission'],
                    'dis_type'     => $affiliateads_type,
                    'comment'      => "Commission for {$tC} click On {$affiliateads_type} <br> Clicked done from ip_message",
                    'type'         => 'affiliate_click_commission',
                    'reference_id' => $affiliate_id,
                    'ip_details'   => json_encode($ips),
                ));
                $this->db->query("UPDATE  affiliate_action SET commission = 1 WHERE user_id = '{$user_id}' AND  affiliate_id = '{$affiliate_id}' ");
            }

        }
    }
    function user_info($user_id){
        $this->db->from('users');
        $this->db->where('id', $user_id);
        $query = $this->db->get()->row();
        return $query;
    }
    function update_payment($cdate){
        $this->db->from('payment');
        $this->db->where('payment_created_date', $cdate);
        $this->db->update('payment', array(
            'payment_item_status' => 'Completed'
        ));
    }
    function getProductCommission($user_id = null){
        if(empty($user_id)){
            $sub_query = '1 = 1';
        }else{
            $sub_query = 'user_id = '. $user_id;
        }
        $query = 'SELECT SUM(commission) AS total_commission FROM product_action WHERE '. $sub_query;
        $value['total_commission'] = $this->db->query($query)->row()->total_commission;
        $query = 'SELECT COUNT(action_type) AS click FROM product_action WHERE ' . $sub_query . ' AND action_type = "click"';
        $value['click'] = $this->db->query($query)->row()->click;
        return $value;
    }
    function getAffiliateCommission($user_id = null){
        if(empty($user_id)){
            $sub_query = '1 = 1';
        }else{
            $sub_query = 'user_id = '. $user_id;
        }
        $query = 'SELECT COUNT(id) AS click, SUM(commission) AS total_commission FROM affiliate_action WHERE '. $sub_query;
        return $this->db->query($query)->row_array();
    }
    function getCommissionType(){
        $this->db->from('setting');
        $this->db->where('setting_key', 'product_commission_type');
        return $this->db->get()->row()->setting_value;
    }
    function add_product_media($user_id, $cdate){
        $this->db->from('product');
        $this->db->where('product_created_by', $user_id);
        $this->db->where('product_created_date', $cdate);
        $product = $this->db->get()->row();
        if(!empty($product->product_featured_image)){
            $image_data = array(
                'product_id' => $product->product_id,
                'product_media_upload_type' => 'image',
                'product_media_upload_path' => $product->product_featured_image,
                'product_media_upload_status' => 1,
                'product_media_upload_ipaddress' => $product->product_ipaddress,
                'product_media_upload_created_date'=>$cdate
            );
            $this->db->insert('product_media_upload', $image_data);
        }
        if(!empty($product->product_video)){
            $image_data = array(
                'product_id' => $product->product_id,
                'product_media_upload_type' => 'video',
                'product_media_upload_path' => $product->product_video,
                'product_media_upload_status' => 1,
                'product_media_upload_ipaddress' => $product->product_ipaddress,
                'product_media_upload_created_date'=>$cdate
            );
            $this->db->insert('product_media_upload', $image_data);
        }
    }

    public function parseDownloads($downloadable_files){
        $_data = json_decode($downloadable_files, 1);
        $data = [];
        foreach ($_data as $key => $value) {
            $data[$value['name']] = array(
                'mask' => $value['mask'],
                'name' => $value['name'],
                'type' => $this->get_types($value['type']),
            );
        }
        return $data;
    }
    private function get_types($filetype){
        switch (true) {
            case preg_match('/image/', $filetype): return 'image';
            case preg_match('/video/', $filetype): return 'video';
            case preg_match('/audio/', $filetype): return 'audio';
            case preg_match('/pdf/', $filetype): return 'pdf';
            case preg_match('/(csv|excel)/', $filetype): return 'spreadsheet';
            case preg_match('/powerpoint/', $filetype): return 'powerpoint';
            case preg_match('/(msword|text)/', $filetype): return 'document';
            case preg_match('/zip/', $filetype): return 'zip';
            case preg_match('/rar/', $filetype): return 'rar';
            default: return 'default-filetype';
        }
    }
}