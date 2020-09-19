<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
	ini_set('display_errors', 1);
	class Share extends CI_Controller {
		function __construct() {
			parent::__construct();
			$this->load->model('Product_model');
			$this->load->helper('share');
			___construct(1);
		}
		function index($product_slug = null, $user_id = null) {
			$data = array();
			if($product_slug){
				$data['product'] = $this->Product_model->getProductBySlug($product_slug);
				if(!empty($user_id)){
					$data['user'] = $this->Product_model->getUserDetails($user_id);
					} else {
					$data['user'] = '';
				}
				$this->load->view('product/index', $data);
			}
		}
		function clicks($type = null, $affiliateads_id = null, $user_id = null) {
			$data = array();
			$this->load->library('user_agent');
			if($type && $affiliateads_id && $user_id){
				$data['getAffiliate'] 	= $this->Product_model->getAffiliateById($affiliateads_id);
				 
				$this->cart->setcookieAffiliate($user_id);
				
				$data['share'] = 1;
				$setting = $this->Product_model->getSettings('affiliateprogramsetting');
				$getUserData = $this->Product_model->getUserDetails($user_id);
				$getAdminUserData = $this->Product_model->getUserDetails(9);
				$click = 0;
				if($setting && $setting['affiliate_commission_type'] && $setting['affiliate_commission'] && $setting['affiliate_ppc']){
					$click = ($setting['affiliate_ppc']) / ($setting['affiliate_commission']);
				}
				if(!empty($data['getAffiliate'])){
					$getData = json_decode($data['getAffiliate']['affiliateads_metadata'],true);
				}
				if($getData['postdata']['affiliateads_status'] == 'active') {

					$_user = $this->Product_model->getUserDetails((int)$user_id);

					if($_user && $_user['type'] == 'user' && $this->session->userdata('user') == false && $this->session->userdata('administrator') == false){
						$this->Product_model->setAffiliateClick($affiliateads_id, $user_id, $data['getAffiliate']['affiliateads_type']);
						$this->Product_model->setAffiliateStoreClick($affiliateads_id, $user_id, $data['getAffiliate']['affiliateads_type']);
					}
					$details = array(
						'clicks_views_action_id'        =>  $affiliateads_id,
						'clicks_views_browser'          =>  $this->agent->browser(),
						'clicks_views_click'            =>  1,
						'clicks_views_click_commission' =>  $click,
						'clicks_views_created'          =>  date('Y-m-d H:i:s'),
						'clicks_views_created_by'       =>  $user_id,
						'clicks_views_data_commission'  =>  json_encode($setting),
						'clicks_views_ipaddress'        =>  $_SERVER['REMOTE_ADDR'],
						'clicks_views_isp'              =>  gethostbyaddr($_SERVER['REMOTE_ADDR']),
						'clicks_views_os'               =>  $this->agent->platform(),
						'clicks_views_referrer'         =>  $this->agent->referrer(),
						'clicks_views_refuser_id'       =>  $user_id,
						'clicks_views_status'           =>  1,
						'clicks_views_type'             =>  $type,
						'clicks_views_user_agent'       =>  $this->agent->agent_string(),
						'clicks_views_view'             =>  0,
						'clicks_views_view_commission'  =>  0,
					);
					$this->Product_model->create_data('clicks_views', $details);
					$data['affiliateads_type'] = $type;
					$data['affiliateads_id'] = $affiliateads_id;
					$data['user_id'] = $user_id;
					//$update['affiliateads_click'] = $data['getAffiliate']['affiliateads_click'] + 1;
					//$update['affiliateads_click_commission'] = $data['getAffiliate']['affiliateads_click_commission'] + $click;
					//$this->Product_model->update_data('affiliateads', $update,array('affiliateads_id' => $affiliateads_id));
					/*$userData['affiliate_commission'] = $getUserData['affiliate_commission'] + $click;
					$userData['affiliate_total_click'] = $getUserData['affiliate_total_click'] + 1;
					$this->Product_model->update_data('users', $userData,array('id' => $getUserData['id']));
					$adminData['affiliate_commission'] = $getAdminUserData['affiliate_commission'] + $click;
					$adminData['affiliate_total_click'] = $getAdminUserData['affiliate_total_click'] + 1;
					$this->Product_model->update_data('users', $adminData,array('id' => $getAdminUserData['id']));*/


					$redirectLocation = '';
					if($type == 'banner'){
						if($getData['postdata']['banner_enable_redirect'] == 'Yes') {
							if($getData['postdata']['banner_redirect_custom_url']){
								$redirectLocation = $getData['postdata']['banner_redirect_custom_url'];
							}
						}
					}
					if($type == 'html'){
						if($getData['postdata']['htmlad_enable_redirect'] == 'Yes') {
							if($getData['postdata']['htmlad_redirect_custom_url']){
								$redirectLocation = $getData['postdata']['htmlad_redirect_custom_url'];
							}
						}
					}
					if($type == 'invisilinks'){
						if($getData['postdata']['invisilinks_url']){
							$redirectLocation = $getData['postdata']['invisilinks_url'];
						}
					}
					if($type == 'viralvideo'){
						if($getData['postdata']['viralvideo_enable_redirect'] == 'Yes') {
							if($getData['postdata']['viralvideo_redirect_custom_url']){
								$redirectLocation = $getData['postdata']['viralvideo_redirect_custom_url'];
							}
						}
					}

					if($redirectLocation){
						$redirectLocation = $this->addParams($redirectLocation,"af_id",_encrypt_decrypt($user_id));
						redirect($redirectLocation);
					}

					$this->load->view('admincontrol/affiliateads/generatecode1', $data);
				} else {
					die('Sorry ! Link Expire');
				}
			}
		}
		function views($type = null, $affiliateads_id = null, $user_id = null) {
			$data = array();
			$this->load->library('user_agent');
			if($type && $affiliateads_id && $user_id){
				$data['getAffiliate'] 	= $this->Product_model->getAffiliateById($affiliateads_id);
				$data['share'] = 1;
				$setting = $this->Product_model->getSettings('affiliateprogramsetting');
				if(!empty($data['getAffiliate'])){
					$getData = json_decode($data['getAffiliate']['affiliateads_metadata'],true);
				}
				if($getData['postdata']['affiliateads_status'] == 'active') {
					$details = array(
					'clicks_views_refuser_id'	=>  $user_id,
					'clicks_views_action_id'	=>  $affiliateads_id,
					'clicks_views_status'		=>  1,
					'clicks_views_type'		=>  $type,
					'clicks_views_click'		=>  0,
					'clicks_views_view'		=>  1,
					'clicks_views_referrer'		=>  $this->agent->referrer(),
					'clicks_views_user_agent'		=>  $this->agent->agent_string(),
					'clicks_views_os'		=>  $this->agent->platform(),
					'clicks_views_browser'		=>  $this->agent->browser(),
					'clicks_views_isp'		=>  gethostbyaddr($_SERVER['REMOTE_ADDR']),
					'clicks_views_ipaddress'		=>  $_SERVER['REMOTE_ADDR'],
					'clicks_views_created_by'		=>  $user_id,
					'clicks_views_created'		=>  date('Y-m-d H:i:s'),
					'clicks_views_click_commission'		=>  0,
					);
					$this->Product_model->create_data('clicks_views', $details);
					$data['affiliateads_type'] = $type;
					$data['affiliateads_id'] = $affiliateads_id;
					$data['user_id'] = $user_id;
					$update['affiliateads_view'] = $data['getAffiliate']['affiliateads_view'] + 1;
					$this->Product_model->update_data('affiliateads', $update,array('affiliateads_id' => $affiliateads_id));
					$this->load->view('admincontrol/affiliateads/generatecode1', $data);
					} else {
					die('Sorry ! Link Expire');
				}
			}
		}

		private function addParams($url, $key, $value) {
	
			$url = preg_replace('/(.*)(?|&)'. $key .'=[^&]+?(&)(.*)/i', '$1$2$4', $url .'&');
			$url = substr($url, 0, -1);
			
			if (strpos($url, '?') === false) {
				return ($url .'?'. $key .'='. $value);
			} else {
				return ($url .'&'. $key .'='. $value);
			}
		}
	}																															