<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
error_reporting(0);
class PagebuilderModel extends MY_Model{
	
    public function view($file_name, $data = array(), $control = 'admincontrol'){

		$this->load->view($control . '/includes/header', $data);
		$this->load->view($control . '/includes/sidebar', $data);
		$this->load->view($control . '/includes/topnav', $data);
		$this->load->view($file_name, $data);
		$this->load->view($control . '/includes/footer', $data);
	}
	public function getAlltheme(){
    	return $this->db->select('*')
		->from('pagebuilder_theme')
		->get()
		->result_array();
    }
	public function getThemedetail($theme_id){
		return $this->db->select('*')
		->from('pagebuilder_theme')
		->where('theme_id',$theme_id)
		->get()
		->row_array();	
	}
	public function getThemePage($theme_id,$home = 0,$not_home=0){
		 $data = $this->db->select('*')
		->from('pagebuilder_theme_page')
		->where('theme_id',$theme_id)
		->order_by('sort_order');
		if($home){
			$data->where('home_page',1);
			return $data->get()->row_array();
		}
		if($not_home){
			$data->where('home_page',0);
		}
		return $data->get()->result_array();
		 
	}
	public function getPage($pageId){
		return $this->db->select('*')
		->from('pagebuilder_theme_page')
		->where('page_id',$pageId)
		->get()
		->row_array();	
	}
	public function checkname($name,$theme_id){
		return $this->db->select('*')
		->from('pagebuilder_theme')
		->where('name',$name)
		->where('theme_id !=',$theme_id)
		->get()
		->row_array();	
	}
	public function checkslug($slug,$page_id,$theme_id){
		return $this->db->select('*')
		->from('pagebuilder_theme_page')
		->where('slug',$slug)
		->where('page_id !=',$page_id)
		->where('theme_id',$theme_id)
		->get()
		->row_array();	
	}
	public function getDesign($page_id){
		return $this->db->select('design')
		->from('pagebuilder_theme_page')		
		->where('page_id',$page_id)
		->get()
		->row_array();	
	}
	public function addTheme($data) { 
        if ($this->db->insert("pagebuilder_theme", $data)) { 
            return true; 
        } 
    } 
    public function addPage($data) { 

    	if ((int)$data['home_page'] == 1) {
    		$this->db->where('theme_id',$data['theme_id']); 
			$this->db->set('home_page',0);
			$this->db->update('pagebuilder_theme_page');
    	}else{
	    	if (isset($data['theme_id'])) {
		    	 $data1 = $this->db->select('*')
				->from('pagebuilder_theme_page')
				->where('theme_id',$data['theme_id'])
				->where('home_page',1)
				->order_by('sort_order');

			 	if (!$data1->get()->num_rows()) {
		 			$data['home_page'] = 1;
			 	}
	    	}
    	}

        if ($this->db->insert("pagebuilder_theme_page", $data)) { 
            return true; 
        } 
    } 
   
	public function deleteTheme($theme_id) { 
		$this->db->delete("pagebuilder_theme", "theme_id = ".$theme_id);
		$this->db->delete("pagebuilder_theme_page", "theme_id = ".$theme_id);
	} 
	public function deletePage($page_id) { 
		$this->db->delete("pagebuilder_theme_page", "page_id = ".$page_id);
	} 

	public function updateTheme($theme_id,$data) { 
		$this->db->set($data); 
		$this->db->where("theme_id", $theme_id); 
		$this->db->update("pagebuilder_theme", $data); 
	} 
	public function updatePage($page_id,$data) { 

		if ((int)$data['home_page'] == 1) {
    		$this->db->where('theme_id',$data['theme_id']); 
			$this->db->set('home_page',0);
			$this->db->update('pagebuilder_theme_page');
    	}else{
	    	if (isset($data['theme_id'])) {
		    	 $data1 = $this->db->select('*')
				->from('pagebuilder_theme_page')
				->where('theme_id',$data['theme_id'])
				->where('page_id',$page_id)
				->where('home_page',1)
				->order_by('sort_order');

			 	if ($data1->get()->num_rows()) {
		 			$data['home_page'] = 1;
			 	}
	    	}
    	}

		$this->db->set($data); 
		$this->db->where("page_id", $page_id); 
		$this->db->update("pagebuilder_theme_page", $data); 
	}
	public function getThemePageBySlug($theme_id,$slug){
		return $this->db->select('*')
		->from('pagebuilder_theme_page')
		->where('theme_id',$theme_id)
		->where('slug',$slug)
		->get()
		->row_array();
	}
	public function getSettings($type=''){
        $settingdata = array();
        $this->db->where('setting_type', $type);
        $getSetting = $this->db->get_where('setting', array('setting_status' => 1))->result_array();
        foreach ($getSetting as $setting) {
            $settingdata[$setting['setting_key']] = $setting['setting_value'];
        }
        return $settingdata;
    }

    public function parseTemplate($page){
    	$this->load->model("Product_model");
    	$data['body'] = $page['design'];

    	$data['site'] = $this->Product_model->getSettings('site');
    	$data['store'] = $this->Product_model->getSettings('store');
    	
    	if( strpos($data['body'], "[sortcode-registerform-build]") !== false){
			if($data['store']['registration_status']){
		 		$register_form = $this->PagebuilderModel->getSettings('registration_builder');
		 		if(isset($register_form['registration_builder'])){
		 			 $registration_builder['data'] = json_decode($register_form['registration_builder'],1);
		 			 if($registration_builder){
		 			 	$html_replace = $this->load->view('auth/user/templates/register_form',$registration_builder, true);
		 			 	$data['body'] = str_replace('[sortcode-registerform-build]', $html_replace, $data['body']);
		 			 }
		 		}
			} else{		
 			 	$data['body'] = str_replace('[sortcode-registerform-build]', '', $data['body']);
			}
 		}
 		
 		if(strpos($data['body'], "[sortcode-loginform-build]")  !== false){
		 	$registration_builder = array();
		 	$html_replace = $this->load->view('auth/user/templates/login_form',$registration_builder, true);
		 	$data['body'] = str_replace('[sortcode-loginform-build]', $html_replace, $data['body']);		 			 
 		}



    	$login_settings = $this->Product_model->getSettings('login');
    	$data['setting'] = $this->Product_model->getSettings('templates');
    	
		$data['assets_url'] = base_url('application/views/auth/user/assets/');
    	
    	$data['templates_url'] =  $data['assets_url'] ."img/";
    	$data['title'] =  $page["title"];
    	$data['login'] =  $page["login"];
    	$data['footer'] =  $page["footer"];
    	
    	$data['favicon'] = $page['favicon'];
    	 
    	
    	$data['pages'] =  $this->db->select('*')
			->from('pagebuilder_theme_page')
			->where('theme_id',$login_settings['front_template'])
			->where('status',1)
			->order_by('sort_order','ASC')
			->get()
			->result_array();


    	$data['LanguageHtml'] = $this->Product_model->getLanguageHtml('AuthController');
    	return $this->load->view('auth/user/templates/builder_layout', $data, true);
    }
}