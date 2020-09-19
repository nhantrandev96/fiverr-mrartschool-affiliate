<?php
class ControllerExtensionModuleAffiliatepro extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/affiliatepro');

		$this->document->setTitle($this->language->get('heading_title'));
		if(version_compare(VERSION, '3', '>=')){
		$token = $this->session->data['user_token'];
		$pre_fix = 'user_token';
		}else{
		$token = $this->session->data['token'];			
		$pre_fix = 'token';
		}

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if(version_compare(VERSION, '3', '>=')){
				$this->model_setting_setting->editSetting('module_affiliatepro', $this->request->post);
			}else{
				$this->model_setting_setting->editSetting('affiliatepro', $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');
			if(version_compare(VERSION, '3', '>=')){
				$this->response->redirect($this->url->link('marketplace/extension', $pre_fix .'=' . $token . '&type=module', true));
			}else{
				$this->response->redirect($this->url->link('extension/extension', $pre_fix .'=' . $token . '&type=module', true));				
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $pre_fix .'=' . $token, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', $pre_fix .'=' . $token . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/affiliatepro', $pre_fix .'=' . $token, true)
		);

		$data['action'] = $this->url->link('extension/module/affiliatepro', $pre_fix .'=' . $token, true);
		if(version_compare(VERSION, '3', '>=')){
			$data['cancel'] = $this->url->link('marketplace/extension', $pre_fix .'=' . $token . '&type=module', true);
		}else{
			$data['cancel'] = $this->url->link('extension/extension', $pre_fix .'=' . $token . '&type=module', true);
		}
		if(version_compare(VERSION, '3', '>=')){
			if (isset($this->request->post['module_affiliatepro_status'])) {
				$data['module_affiliatepro_status'] = $this->request->post['module_affiliatepro_status'];
			} else {
				$data['module_affiliatepro_status'] = $this->config->get('module_affiliatepro_status');
			}
		}else{
			if (isset($this->request->post['affiliatepro_status'])) {
				$data['affiliatepro_status'] = $this->request->post['affiliatepro_status'];
			} else {
				$data['affiliatepro_status'] = $this->config->get('affiliatepro_status');
			}
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/affiliatepro', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/affiliatepro')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}