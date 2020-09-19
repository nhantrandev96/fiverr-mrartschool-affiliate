<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);

class Shorturl extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('Shorturl_model');
		___construct(1);
	}

	public function redirect_user($short_code){
		$url = $this->Shorturl_model->getByCode($short_code);
		$redirect = (isset($url['long_url']) && $url['long_url']) ? $url['long_url'] : base_url('store');
		header('location:'. $redirect);
	}

	public function configModal(){
		$post = $this->input->post(null);

		$url = $this->Shorturl_model->getByUrl($post['longUrl']);
		$data['url'] = $url;

		$data['o_url'] = $post['longUrl'];

		$json['html'] = $this->load->view('admincontrol/shorturl/configModal',$data, true);
		echo json_encode($json);
	}
}