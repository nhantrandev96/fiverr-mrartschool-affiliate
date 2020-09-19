<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filemanager extends CI_Controller {

	function __construct() {
		parent::__construct();
		define('BASE_PATH', str_replace('application/', '', APPPATH));
		define('DIR_IMAGE', BASE_PATH.'assets/user_upload/');
		___construct(1);
	}

	public function index() {
		$get_ = $this->input->get(null,true);
		$filter_name = null;
		$directory = DIR_IMAGE;
		$page = 1;
		if (isset($get_['filter_name'])) $filter_name = rtrim(str_replace(array('../', '..\\', '..', '*'), '', $get_['filter_name']), '/');
		if (isset($get_['directory'])) $directory = rtrim(DIR_IMAGE. str_replace(array('../', '..\\', '..'), '', $get_['directory']), '/'); // Make sure we have the correct directory
		if (isset($get_['page'])) $page = $get_['page'];

		$data['images'] = array();
		// Get directories
		$directories = glob($directory . '/' . $filter_name . '*', GLOB_ONLYDIR);
		if (!$directories) $directories = array();

		// Get files
		$files = glob($directory . '/' . $filter_name . '*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE);
		if (!$files) $files = array();		

		// Merge directories and files
		$images = array_merge($directories, $files);
		// Get total number of files and directories
		$image_total = count($images);
		// Split the array based on current page number and max number of items per page of 10
		$images = array_splice($images, ($page - 1) * 16, 16);
		foreach ($images as $image){
			$name = str_split(basename($image), 14);
			if (is_dir($image)){
				$url = '';
				if (isset($get_['target'])) $url .= '&target=' . $get_['target'];
				if (isset($get_['thumb'])) $url .= '&thumb=' . $get_['thumb'];
				$data['images'][] = array(
					'thumb' => '',
					'name'  => implode(' ', $name),
					'type'  => 'directory',
					'path'  => substr($image, strlen(DIR_IMAGE)),
					'href'  => base_url("filemanager").'?directory=' . urlencode(substr($image, strlen(DIR_IMAGE))) . $url
				);
			} elseif (is_file($image)) {
				// Find which protocol to use to pass the full image link back
				$server = base_url('assets/user_upload');
				$data['images'][] = array(
					'thumb' => base_url(str_replace(BASE_PATH, '', $image)),
					'name'  => implode(' ', $name),
					'type'  => 'image',
					'path'  => substr($image, strlen(DIR_IMAGE)),
					'href'  => $server.substr($image, strlen(DIR_IMAGE))
				);
			}
		}

		$data['directory'] = $data['filter_name'] = $data['target'] = $data['thumb'] = '';
		if (isset($get_['directory'])) $data['directory'] = urlencode($get_['directory']);
		if (isset($get_['filter_name'])) $data['filter_name'] = $get_['filter_name'];
		if (isset($get_['target'])) $data['target'] = $get_['target']; // Return the target ID for the file manager to set the value
		if (isset($get_['thumb'])) $data['thumb'] = $get_['thumb']; // Return the thumbnail for the file manager to show a thumbnail
		
		$url = '?_'; // Parent
		if (isset($get_['directory'])) {
			$pos = strrpos($get_['directory'], '/');
			if ($pos) $url .= '&directory=' . urlencode(substr($get_['directory'], 0, $pos));
		}
		if (isset($get_['target'])) $url .= '&target=' . $get_['target'];
		if (isset($get_['thumb'])) $url .= '&thumb=' . $get_['thumb'];
		$data['parent'] = base_url("filemanager"). $url;
		
		$url = '?_'; // Refresh
		if (isset($get_['directory'])) $url .= '&directory=' . urlencode($get_['directory']);		
		if (isset($get_['target'])) $url .= '&target=' . $get_['target'];
		if (isset($get_['thumb'])) $url .= '&thumb=' . $get_['thumb'];		
		$data['refresh'] = base_url("filemanager"). $url;

		$url = '?_';
		if (isset($get_['directory'])) $url .= '&directory=' . urlencode(html_entity_decode($get_['directory'], ENT_QUOTES, 'UTF-8'));
		if (isset($get_['filter_name'])) $url .= '&filter_name=' . urlencode(html_entity_decode($get_['filter_name'], ENT_QUOTES, 'UTF-8'));
		if (isset($get_['target'])) $url .= '&target=' . $get_['target'];
		if (isset($get_['thumb'])) $url .= '&thumb=' . $get_['thumb'];
		$data['pagination'] = '';
		$this->load->view('filemanager', $data);
	}
	public function upload() {
		$json = array();
		$get_ = $this->input->get(null,true);
		$directory = DIR_IMAGE;
		if (isset($get_['directory']) && $get_['directory']) $directory = rtrim(DIR_IMAGE.str_replace(array('../', '..\\', '..'), '', $get_['directory']), '/'); // Make sure we have the correct directory


		if (!is_dir($directory)) $json['error'] = 'Invalid directory.'; // Check its a directory
		
		if (!$json){
			
			if (!empty($_FILES['file']['name']) && is_file($_FILES['file']['tmp_name'])) {
				// Sanitize the filename
				$filename = basename(html_entity_decode($_FILES['file']['name'], ENT_QUOTES, 'UTF-8'));

				if ((strlen($filename) < 3) || (strlen($filename) > 255)) $json['error'] = 'Invalid file name.'; // Validate the filename length
				$allowed = array('jpg','jpeg','gif','png'); // Allowed file extension types
				if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) $json['error'] = 'Invalid file extension.';

				$allowed = array('image/jpeg','image/pjpeg','image/png','image/x-png','image/gif'); // Allowed file mime types

				if (!in_array($_FILES['file']['type'], $allowed)) $json['error'] = 'Invalid file extension type.';
				if ($_FILES['file']['error'] != UPLOAD_ERR_OK) $json['error'] = 'Invalid file.'; // Return any upload error
			} else $json['error'] = 'Invalid file.';
		}

		if (!$json) {
			move_uploaded_file($_FILES['file']['tmp_name'], $directory . '/' . $filename);
			$json['success'] = 'File successfully upload.';
		}
		echo json_encode($json);die;
	}

	public function folder() {
		$json = array();
		$get_ = $this->input->get(null,true);
		$post_ = $this->input->post(null,true);
		$directory = DIR_IMAGE;
		if (isset($get_['directory']) && $get_['directory']) $directory = rtrim(DIR_IMAGE.str_replace(array('../', '..\\', '..'), '', $get_['directory']), '/'); // Make sure we have the correct directory
		if (!is_dir($directory)) $json['error'] = 'Invalid directory.'; // Check its a directory

		if (!$json) {
			// Sanitize the folder name
			$folder = str_replace(array('../', '..\\', '..'), '', basename(html_entity_decode($post_['folder'], ENT_QUOTES, 'UTF-8')));
			
			if ((strlen($folder) < 3) || (strlen($folder) > 128)) $json['error'] = 'Invalid Folder Name'; // Validate the filename length
			if (is_dir($directory . '/' . $folder)) $json['error'] = 'Directory already exists.'; // Check if directory already exists or not
		}

		if (!$json) {
			mkdir($directory . '/' . $folder, 0777);
			chmod($directory . '/' . $folder, 0777);
			$json['success'] = "Directory added successfully.";
		}
		echo json_encode($json);die;
	}

	public function delete() {
		$json = array();
		$post_ = $this->input->post(null,true);
		$paths = array();
		if (isset($post_['path'])) $paths = $post_['path'];

		// Loop through each path to run validations
		foreach ($paths as $path){
			$path = rtrim(DIR_IMAGE . str_replace(array('../', '..\\', '..'), '', $path), '/');
			if ($path == DIR_IMAGE){ // Check path exsists
				$json['error'] = 'Path not exsists.';
				break;
			}
		}
		if (!$json) {
			foreach ($paths as $path){ // Loop through each path
				$path = rtrim(DIR_IMAGE . str_replace(array('../', '..\\', '..'), '', $path), '/');
				if (is_file($path)) unlink($path); // If path is just a file delete it
				elseif (is_dir($path)){ // If path is a directory beging deleting each file and sub folder
					$files = array();

					$path = array($path . '*'); // Make path into an array

					while (count($path) != 0){ // While the path array is still populated keep looping through
						$next = array_shift($path);
						foreach (glob($next) as $file) {
							if (is_dir($file)) $path[] = $file . '/*'; // If directory add to path array
							$files[] = $file; // Add the file to the files to be deleted array
						}
					}
					rsort($files); // Reverse sort the file array
					foreach ($files as $file){
						if (is_file($file)) unlink($file); // If file just delete
						elseif (is_dir($file)) rmdir($file); // If directory use the remove directory function						
					}
				}
			}
			$json['success'] = 'Directory successfully Delete.';
		}

		echo json_encode($json);die;
	}
}