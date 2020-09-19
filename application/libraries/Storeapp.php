<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Storeapp {
    private $data;
    private $CI;
    public function __construct(){
        $this->CI =& get_instance();
    }
    public function view($page, $data = array(), $skip_layout = false, $return = false){
        $this->CI->load->model("Product_model");
        $this->CI->load->model("User_model");

        $data['LanguageHtml'] = $this->CI->Product_model->getLanguageHtml('store');
        $data['CurrencyHtml'] = $this->CI->Product_model->getCurrencyHtml('store');
    	$settingdata = array();

        $this->CI->db->where('setting_type', 'store');
        $getSetting = $this->CI->db->get_where('setting', array('setting_status' => 1))->result_array();


        $this->CI->db->where('setting_type', 'site');
        $SiteSetting = $this->CI->db->get_where('setting', array('setting_status' => 1))->result_array();

        foreach ($SiteSetting as $key => $value) {
            $data['SiteSetting'][$value['setting_key']] = $value['setting_value'];
        }
        foreach ($getSetting as $setting) {
            $settingdata[$setting['setting_key']] = $setting['setting_value'];
        }


        $data['store_setting'] = $settingdata;
        $data['is_logged'] = $this->CI->cart->is_logged();
        $data['home_link'] = base_url("store/");
        $data['base_url'] = base_url("store/");
    	$data['referid'] = (int)$this->CI->cart->getReferId();
        $data['client'] = $this->CI->User_model->get_user_by_id($this->CI->cart->getUserId());

        $theme = $settingdata['theme'];
        $theme = $theme ? $theme : '0';

        $path = "store/{$theme}/{$page}.php";
        if($theme != '0'){
            $path = "store/{$theme}/{$page}.php";
            if(!file_exists(APPPATH."views/".$path)){
                $path = "store/default/{$page}.php";
            }
        } else {
            $path = "store/default/{$page}.php";
        }

        $this->data['content'] = $this->CI->load->view($path,  $data, true);

        if($skip_layout){
            if($return)  return $this->data['content'];
            echo $this->data['content'];
        } else {
            $path = '';
            if($theme != '0'){
                $path = "store/{$theme}/layout.php";
                if(!file_exists(APPPATH."views/".$path)){
                    $path = "store/default/layout.php";
                }
            } else {
                $path = "store/default/layout.php";
            }
            $this->CI->load->view($path, $this->data);   
        }
    }
} 