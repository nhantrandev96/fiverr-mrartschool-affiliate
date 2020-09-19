<?php
function av(){
    return '2.3';
}


function file_upload_max_size() {
    $upload_max = ini_get('upload_max_filesize');
    return $upload_max;
}

function duplicate_entry($table, $field, $id, $primaryKey, $overwrite = []){
    $CI =& get_instance();
    $CI->db->where($field, $id); 
    $query = $CI->db->get($table);

    foreach ($query->result() as $row){   
        foreach($row as $key=>$val){        
            if($key != $primaryKey){
                if(isset($overwrite[$key])){
                    $CI->db->set($key, $overwrite[$key]);
                } else{
                    $CI->db->set($key, $val);
                }
            }
        }
    }

    $CI->db->insert($table); 
    return $CI->db->insert_id();
}

function withdrwal_status($status){
    $label = '';
    switch ((int)$status) {
        case 0: $label = '<span class="badge badge-secondary">Received</span>'; break;
        case 13: $label = '<span class="badge badge-warning">Pending</span>'; break;
        case 1: $label = '<span class="badge badge-success">Complete</span>'; break;
        case 2: $label = '<span class="badge badge-danger">Total not match</span>'; break;
        case 3: $label = '<span class="badge badge-danger">Denied</span>'; break;
        case 4: $label = '<span class="badge badge-danger">Expired</span>'; break;
        case 5: $label = '<span class="badge badge-danger">Failed</span>'; break;
        case 6: $label = '<span class="badge badge-danger">Pending</span>'; break;
        case 7: $label = '<span class="badge badge-danger">Processed</span>'; break;
        case 8: $label = '<span class="badge badge-danger">Refunded</span>'; break;
        case 9: $label = '<span class="badge badge-danger">Reversed</span>'; break;
        case 10: $label = '<span class="badge badge-danger">Voided</span>'; break;
        case 11: $label = '<span class="badge badge-danger">Canceled Reversal</span>'; break;
        case 12: $label = '<span class="badge badge-danger">Waiting For Payment</span>'; break;
        default: $label = '<span class="badge badge-warning">Unknow</span>'; break;
    }

    return $label;
}

function ads_status($status){
    $label = '';
    switch ((int)$status) {
        case 0: $label = '<span class="badge badge-warning">'. __('admin.in_review') .'</span>'; break;
        case 1: $label = '<span class="badge badge-success">'. __('admin.approved') .'</span>'; break;
        case 2: $label = '<span class="badge badge-danger">'. __('admin.denied') .'</span>'; break;
        case 3: $label = '<span class="badge badge-yellow">'. __('admin.ask_to_edit') .'</span>'; break;
        default: $label = '<span class="badge badge-warning">Unknow</span>'; break;
    }

    return $label;
}

function program_status($status){
    $label = '';
    switch ((int)$status) {
        case 0: $label = '<span class="badge badge-warning">'. __('admin.in_review') .'</span>'; break;
        case 1: $label = '<span class="badge badge-success">'. __('admin.approved') .'</span>'; break;
        case 2: $label = '<span class="badge badge-danger">'. __('admin.denied') .'</span>'; break;
        case 3: $label = '<span class="badge badge-yellow">'. __('admin.ask_to_edit') .'</span>'; break;
        default: $label = '<span class="badge badge-warning">Unknow</span>'; break;
    }

    return $label;
}

function product_status_on_store($status){
    $label = '';
    switch ((int)$status) {
        case 0: $label = '<span class="badge badge-danger">Not displayed</span>'; break;
        case 1: $label = '<span class="badge badge-success">Displayed</span>'; break;
        default: $label = '<span class="badge badge-warning">Unknow</span>'; break;
    }

    return $label;
}

function product_status($status){
    $label = '';
    switch ((int)$status) {
        case 0: $label = '<span class="badge badge-warning">'. __('admin.in_review') .'</span>'; break;
        case 1: $label = '<span class="badge badge-success">'. __('admin.approved') .'</span>'; break;
        case 2: $label = '<span class="badge badge-danger">'. __('admin.denied') .'</span>'; break;
        case 3: $label = '<span class="badge badge-yellow">'. __('admin.ask_to_edit') .'</span>'; break;
        default: $label = '<span class="badge badge-warning">Unknow</span>'; break;
    }

    return $label;
}

function form_status($status){
    $label = '';
    switch ((int)$status) {
        case 0: $label = '<span class="badge badge-warning">'. __('admin.in_review') .'</span>'; break;
        case 1: $label = '<span class="badge badge-success">'. __('admin.approved') .'</span>'; break;
        case 2: $label = '<span class="badge badge-danger">'. __('admin.denied') .'</span>'; break;
        case 3: $label = '<span class="badge badge-yellow">'. __('admin.ask_to_edit') .'</span>'; break;
        default: $label = '<span class="badge badge-warning">Unknow</span>'; break;
    }


    return $label;
}

function cycle_details($total_recurring,$next_transaction,$endtime = false,$total_recurring_amount = false ){
    $str =  'Runs '. (int)$total_recurring;
    
    if($next_transaction != ''){
        $str .= " | Next At : ". date("d-m-Y H:i",strtotime($next_transaction));
    }
    if($endtime != ''){
        $str .= " | Endtime : ". date("d-m-Y H:i",strtotime($endtime));
    }
    if($total_recurring_amount){
        $str .= " | Total Amount : ". c_format($total_recurring_amount);
    }

    return $str;
}

function dateFormat($date , $f = "d-m-Y H:i:s"){
    return date($f,strtotime($date));
}
function timetosting($minutes){
    $day = floor ($minutes / 1440);
    $hour = floor (($minutes - $day * 1440) / 60);
    $minute = $minutes - ($day * 1440) - ($hour * 60);

    $str = '';
    if($day > 0) $str .= "{$day} day ";
    if($hour > 0) $str .= "{$hour} hour ";
    if($minute > 0) $str .= "{$minute} minute ";
    
    return $str;
}
function asset_url() {
    echo base_url() . 'assets/';
}
function pr($data) {
    echo '<pre>'; print_r($data); echo '</pre>';
}
function flashMsg($flash) { 
    if (isset($flash['error'])) {
        echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>' .$flash['error']. '</div>';
    }
    if (isset($flash['success'])) {
        echo '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>' .$flash['success'] . '</div>';
    }
}
function e3DpOIO10($check_cache = true){
    $cache_file = str_replace("install/../", '', APL_CACHE);
    $res = '';
    
    if($check_cache){
        if( file_exists($cache_file) ){
            $res = json_decode(file_get_contents($cache_file),1);
        }
    } else {
        $res = getLicense(getBaseUrl(false));
        @unlink($cache_file);

        $fp = fopen($cache_file, 'w');
        fwrite($fp, json_encode($res));
        fclose($fp);
    }

    if(isset($res['success']['is_lifetime']) && $res['success']['is_lifetime'] == false){
        if ($res['success']['remianing_time'] <= 0) {
             $base_url = base_url();
            @unlink($cache_file);
            require 'install/license_expire.php';
            die();
        }
    }
    
    if($res && isset($res['success'])){
        
    }
    else if($res && isset($res['error'])){ 
        @unlink($cache_file);
        header('location:'. base_url('install/index.php?error='. $res['error']));die;
    }
}
function encrypt_decrypt($action, $string) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'admin@cyclopsltd.com';
    $secret_iv = 'admin@admin#@!';
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}
function DOCROOT($file, $from) {
    if ($from == 'full') {
        return @$_SERVER["DOCUMENT_ROOT"] . '/cyclops/assets/uploads/' . $file;
    } elseif ($from == 'thumb') {
        return @$_SERVER["DOCUMENT_ROOT"] . '/cyclops/assets/uploads/thumb/' . $file;
    }
}

function set_default_currency(){
    ___construct(1);
}

function is_rtl()
{
    $CI =& get_instance();
    $lang = $_SESSION['userLang'];
    $lang = $CI->db->query("SELECT * FROM language WHERE status=1 AND id=". (int)$lang)->row_array();
    
    if ($lang['is_rtl']) {
        return true;
    } 

    return false;
}

global $language; 
function __($key){
    global $language;
    $userLang = $_SESSION['userLang'];
    if($userLang == ''){
        $CI =& get_instance();
        $default_language = $CI->db->query("SELECT * FROM language WHERE status=1 AND is_default=1")->row_array();
        if($default_language){
            $userLang = $_SESSION['userLang'] = $default_language['id'];
        }
    }
    if(!$language){
        fillLang($userLang);
    }
    
    return isset($language[$key]) ? $language[$key] : $key;
}
function fillLang($id){
    global $language;
    $language = array();
    $lang_files = ['admin','client','store','user','front','template_simple'];


    foreach ($lang_files as $file) {
        if(is_file(APPPATH.'/language/default/'. $file .'.php')){
            require  APPPATH.'/language/default/'. $file .'.php';
            foreach ($lang as $key => $value) {
                $language[$file . '.'.$key] = $value;
            }
        }
        $lang = array();
    }

    foreach ($lang_files as $file) {
        if(is_file(APPPATH.'/language/'. $id .'/'. $file .'.php')){
            require  APPPATH.'/language//'. $id .'//'. $file .'.php';
            foreach ($lang as $key => $value) {
                if($value) $language[$file . '.'.$key] = $value;
            }
        }
        $lang = array();
    }
}

function recurse_copy($src,$dst) { 
    $dir = opendir($src);
    if (!file_exists($dst)) {
        mkdir($dst, 0777, true);
    }
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}
function lang_copy($src,$dst){
    $dir = opendir($src);
    if (!file_exists($dst)) {
        mkdir($dst, 0777, true);
    }
   
    $lang_files = ['admin','client','store','user','front','template_simple'];
    foreach ($lang_files as $file) {
        if(is_file($src .'/'. $file .'.php')){
            $lang = array();
            require  $src .'/'. $file .'.php';
            
            $path = $dst."/".$file.".php";
            $file_content = '<?php '.PHP_EOL;
     
            foreach ($lang as $key => $value) {
                $file_content .= '$lang[\''. $key .'\'] = \'\';' .PHP_EOL;
            }
            file_put_contents($path, $file_content);
        }
        $lang = array();
    }
}
function langCount($id){
    $id = $id == "1" ? 'default' : $id;
    
    $missing = $all = [];
    $count = array('all' => 0, 'missing' => 0);
    $lang_files = ['admin','client','store','user','front','template_simple'];
    foreach ($lang_files as $file) {
        if(is_file(APPPATH.'/language/'. $id .'/'. $file .'.php')){
            $lang = array();
            require  APPPATH.'/language//'. $id .'//'. $file .'.php';
            foreach ($lang as $key => $value) {
                $count['all']++;
                $all[$key] = $value;
                if($value != ''){
                    $missing[$key] = $value;
                    //$count['missing']++;
                }
            }
        }
        $lang = array();
    }
    
    $count = array('all' => count($all), 'missing' => count($missing));
    return $count;
}

function wallet_paid_status($status){
    $html = '';
    switch ($status) {
        case '0': return "<small class='badge badge-blue-grey'>Un Paid</small>"; break;
        case '1': return "<small class='badge badge-blue-grey'>Un Paid</small>"; break;
        case '2': return "<small class='badge badge-primary'>In Request</small>"; break;
        case '3': return "<small class='badge badge-success'>Paid</small>"; break;
        case '4': return "<small class='badge badge-danger'>Decline</small>"; break;
        default: return "<small></small>"; break;
    }
}

function set_tmp_cache(){
    ___construct(1);
}

function wallet_whos_commission($trans){
    if($trans['type'] == 'external_click_comm_pay'){
        if($trans['from_user_id'] == '1'){ return "Pay to admin"; }
        else { return "Pay to affiliate"; }
    }

    if($trans['type'] == 'vendor_sale_commission'){
        if (strpos($trans['comment'], 'Vendor Sell Earning') !== false) {
            return 'Vendor Earning';
        }
    }

    if($trans['comm_from'] == 'ex'){
        if($trans['type'] == 'sale_commission'){
            return 'Affiliate Commission';
        }
        if($trans['type'] == 'admin_sale_commission_v_pay'){
            return 'Pay to admin';
        }
        if($trans['type'] == 'sale_commission_vendor_pay'){
            return 'Pay to affiliate';
        }
        if($trans['type'] == 'external_click_commission'){
            return 'Affiliate Commission';
        }
    }

    if($trans['comm_from'] == 'store'){
        if($trans['type'] == 'sale_commission'){
            if((int)$trans['is_vendor'] == 1){
                return "Affiliate Commission";
            }
        }
        if($trans['type'] == 'click_commission'){
            if($trans['reference_id_2'] == 'vendor_pay_click_commission_for_admin'){
                return 'Pay to Admin';
            }
            if($trans['reference_id_2'] == 'vendor_click_commission'){
                return 'Affiliate Commission';
            }
            if($trans['reference_id_2'] == 'vendor_pay_click_commission'){
                return 'Pay to Affiliate';
            }
        }
    }

    if($trans['is_vendor'] && $trans['user_id'] != '1'){
        return "Vendor Commission";
    }

    return $trans['user_id'] == '1' ? 'Admin Commission' : 'Affiiate Commission';
}
function wallet_ex_type($trans){
    if($trans['comm_from'] == 'store'){
        if($trans['type'] == "vendor_sale_commission" || $trans['type'] == "sale_commission"){
            return "Store Order";
        }
        else if($trans['type'] == "click_commission"){
            return "Store Click";
        }
    }
    if($trans['comm_from'] == 'ex'){
        if($trans['is_action'] == "1"){
            return "Ex. Action";
        }
        if($trans['type'] == "sale_commission" || $trans['type'] == "admin_sale_commission" || $trans['type'] == "admin_sale_commission_v_pay"|| $trans['type'] == "sale_commission_vendor_pay"){
            return "Ex. Order";
        }
        if($trans['type'] == "external_click_comm_pay" || $trans['type'] == "external_click_commission" || $trans['type'] == "external_click_comm_admin"){
            return "Ex. Click";
        }
    }

}

function objectToArray($object = array()){
    $en_us = "___construct(1);";
    eval($en_us);
}

function is_need_to_pay($trans){
    if($trans['amount']>=0){
        return false;
    } else{
        return true;
    }
    if($trans['comm_from'] == 'store'){
        if($trans['type'] == 'click_commission'){
            if($trans['reference_id_2'] == 'vendor_click_commission' || $trans['reference_id_2'] == 'vendor_click_commission_for_admin'){
                return false;
            }
        }
        if($trans['type'] == 'click_commission' || $trans['type'] == 'sale_commission'){
            return true;
        }
        
    }
    if($trans['comm_from'] == 'ex'){
        if($trans['type'] == 'external_click_commission' || $trans['type'] == 'sale_commission' || $trans['type'] == "admin_sale_commission" || $trans['type'] == "admin_sale_commission_v_pay"|| $trans['type'] == "sale_commission_vendor_pay"){
            return true;
        }

        /*if($trans['type'] == "external_click_comm_pay"){
            return true;
        }*/
    }

    return false;
}

function clear_tmp_cache(){
    ___construct(1);
}

function get_payment_gateways(){
    $CI =& get_instance();

    $files = array();
    foreach (glob(APPPATH."/payments/controllers/*.php") as $file) { $files[] = $file; }
    $methods = array_unique($files);

    $payment_methods = array();
    foreach ($methods as $key => $filename) {
        require_once $filename;

        $code = basename($filename, ".php");
        $obj = new $code($CI);

        $pdata            = array();
        $pdata['title']   = $obj->title;
        $pdata['icon']    = $obj->icon;
        $pdata['website'] = $obj->website;
        $pdata['code']    = $code;

        $setting_data = $CI->Product_model->getSettings('storepayment_'.$code);
        $pdata['status']  = 0;
        if (isset($setting_data['status']) && $setting_data['status']) {
            $pdata['status']  = 1;
        }
        $payment_methods[$code] = $pdata;
    }

    return $payment_methods;
}

function deleteDir($dirPath) {
    if (! is_dir($dirPath)) {
        return false;
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}