<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Common extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('user_model', 'user');
		$this->load->model('Product_model');
		___construct(1);
	}
	public function term_condition()
	{
		$data['page'] 	= $this->Product_model->getSettings('tnc');
		$this->load->view('term-condition', $data);
	}
}