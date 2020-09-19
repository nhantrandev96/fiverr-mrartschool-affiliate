<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
ini_set('display_errors', 0);

class Installversion extends MY_Controller {
	function __construct() {
		parent::__construct();

		$this->loginID = $this->session->userdata('administrator');
		if(!isset($this->loginID['id'])){ redirect('admin', 'refresh'); }
		___construct(1);
	}

	public function uninstall_script($password, $licence){
		$password = base64_decode($password);
		$licence = base64_decode($licence);

		$user = $this->db->query("SELECT * FROM users WHERE id=". (int)$this->loginID['id'])->row();
		if(strtolower($licence) != strtolower(CODECANYON_LICENCE)){
			$json['errors']['licence'] = "Wrong licence number";
		}
		if(sha1($password) != $user->password){
			$json['errors']['password'] = "Wrong Admin Password..!";
		}

		if( !isset($json['errors']) ){
			list($c,$data) = api('codecanyon/uninstall_script',[
				'codecanyon'   => CODECANYON_LICENCE,
				"is_localhost" => isLocalHost(),
				"base_url"     => base_url()
			]);

			if(isset($data['success'])){
				$json['success'] = true;
				clear_session();
			} else {
				$json['warning'] = $data['error'];
			}
		}

		echo json_encode($json);
	}

	public function downloadDatabase(){
		header("Content-type: text/plain");
		header("Content-Disposition: attachment; filename=database.sql");

		$database_structure = $this->getOurDB();
		print_r($database_structure);
	}

	public function downloadDatabaseNewVersion(){
		header("Content-type: text/plain");
		header("Content-Disposition: attachment; filename=database.sql");

		$database_structure = $this->getOurDB(1);
		
		print_r($database_structure);
	}

	public function check_confirm_password(){
		$password = $this->input->post('password',true);
		$codecanyon = $this->input->post('codecanyon',true);

		$user = $this->db->query("SELECT * FROM users WHERE id=". (int)$this->loginID['id'])->row();
		if(sha1($password) == $user->password){

			list($c,$data) = api('codecanyon/checklicense-update',['codecanyon' => $codecanyon]);
			if(isset($data['item']['code'])){
				$json['success'] = true;
				$this->session->set_userdata('tmp_login' , $user->id);
			} else {
				$json['warning'] = $data['error'];
			}

		} else {
			$json['warning'] = "Wrong Password..!";
		}

		echo json_encode($json);
	}

	public function confirm_password(){
		$json = [];

		$lastLogin = $this->session->userdata('tmp_login');
		if(!$lastLogin){
			$data['for'] = $this->input->post('for', true);
			$json['html'] = $this->load->view("admincontrol/setting/steps/password",$data,true);
		} else {
			$json['callback'] = true;
		}

		echo json_encode($json);
	}

	public function getStep(){
		$number = (int)$this->input->post("number");
		$json = [];
		
		if($number == 0){
			$this->session->unset_userdata('tmp_login');
			$json['html'] = $this->load->view("admincontrol/setting/steps/database",$data,true);
		}

		if($number == 1){
			$this->session->unset_userdata('tmp_login');
			$json['html'] = $this->load->view("admincontrol/setting/steps/files",$data,true);
		}

		if($number == 2){
			$json['html'] = $this->load->view("admincontrol/setting/steps/finish",$data,true);
			$json['version']= SCRIPT_VERSION;
		}

		echo json_encode($json);
	}

	public function migrateFiles(){
		$files = $_FILES['update'];
		$json = [];

		//$json['errors']['update'] = 'This function is disable in demo version..!';echo json_encode($json);die;

		if(!isset($files['name']) || $files['name'] == ''){
			$json['errors']['update'] = 'Please select update.zip file';
		} else {
			$ext = pathinfo($files['name'], PATHINFO_EXTENSION);
			if($ext != 'zip'){
				$json['errors']['update'] = 'Please select .zip file';
			}
		}

		if(!isset($json['errors'])){
			$newVersion = str_replace(['update-','.zip'], ['',''], $files['name']);
			if(version_compare($newVersion,SCRIPT_VERSION) < 0){
				$json['errors']['update'] = 'Script version must be greater than '. SCRIPT_VERSION;
			}
		}

		if(!isset($json['errors'])){
			ini_set('max_execution_time', 600);
			ini_set('max_execution_time', 0);

			$destination = "updates.zip";
			unlink($destination);
			file_put_contents($destination, file_get_contents($files['tmp_name'])); 

			$zip = new ZipArchive;
			$res = $zip->open($destination);

			if ($res === TRUE) {
				if(!$zip->extractTo('.')) { 
					$json['errors']['update'] = "Error during extracting"; 
				} else {
				    $zip->close();
				    unlink($destination);

		            $version = "<?php \n";
			        $version .= "define('SCRIPT_VERSION', '". $newVersion ."'); \n";
			        $version .= "define('CODECANYON_LICENCE', '". CODECANYON_LICENCE ."'); \n";
		            file_put_contents(FCPATH."/install/version.php", $version); 

		            $data['btn'] = 'Finish';
		            $data['success_message'] = 'Files Migrated Successfully';
					$data['getStep'] = 2;
					$json['success'] = $this->load->view("admincontrol/setting/steps/success",$data,true);
				}
			    
			}
		}

		echo json_encode($json);
	}

	public function migrateDatabase(){
		$files = $_FILES['database'];
		$json= array();

		//$json['errors']['database'] = 'This function is disable in demo version..!';echo json_encode($json);die;

		if(!isset($files['name']) || $files['name'] == ''){
			$json['errors']['database'] = 'Please select a database file';
		} else {
			$ext = pathinfo($files['name'], PATHINFO_EXTENSION);
			if($ext != 'sql'){
				$json['errors']['database'] = 'Please select .sql file';
			}
		}

		if(!isset($json['errors'])){
			$database_sql = file_get_contents($files['tmp_name']);

			$updates_query = $this->getDiff($database_sql);
			if(is_array($updates_query)){
				foreach ($updates_query as $key => $value) {
					$this->db->query($value);
				}
			}

			$data['btn'] = 'Next Migrate Files';
			$data['success_message'] = 'Databse Migrated Successfully';
			$data['getStep'] = 1;
			$json['success'] = $this->load->view("admincontrol/setting/steps/success",$data,true);
		}

		echo json_encode($json);
	}

	
	public function getDiff($master_db){ 
		$user_db_tables = $this->getOurDB();
		$tables_drop = [];

		require_once APPPATH.'core/dbStruct.php';
		$updater = new dbStructUpdater();		
		$result = $updater->getUpdates($user_db_tables, (string)$master_db);
		return $result;
	}

	private function getOurDB($recordsExport=false){
		$dir = APPPATH . 'core/doctorin/vendor/autoload.php';
		require $dir;

		$config = new \Doctrine\DBAL\Configuration();
		$connectionParams = array(
			'dbname'   => $this->db->database,
			'user'     => $this->db->username,
			'password' => $this->db->password,
			'host'     => $this->db->hostname,
			'driver'   => 'mysqli',
		);
		$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
		$conn->query("SET SQL_MODE = ''");

	 	$tables= array();
		$db = $this->db->query("SHOW TABLES")->result_array();

		if (isset($_POST['records'])) {
			$records = json_decode($_POST['records'], true);
		}

		$database_structure = '';
		foreach ($db as $key => $value) {
			
			$tb_name = $value['Tables_in_'. $this->db->database];
			$schemaManager = $conn->getSchemaManager();
			$t = $schemaManager->listTableDetails( $tb_name);
			

			$platform = $schemaManager->getDatabasePlatform();
			$platform->registerDoctrineTypeMapping('enum', 'string');

			$s = $platform->getCreateTableSQL($t);
			
			if(isset($s[0])){ $database_structure .= $s[0] . PHP_EOL; }
		}

		if($recordsExport){
			$tables = ['countries','mail_templates','setting','cities','states'];
			$customs = ['currency','language','users'];

			foreach ($customs as $key => $table) {
				$data = [];
				if($table == 'users'){
					$data = $this->db->query("SELECT * FROM {$table} WHERE id =1")->result_array();
				}
				else if($table == 'language'){
					$data = $this->db->query("SELECT * FROM {$table} WHERE id =1")->result_array();
				}
				else if($table == 'currency'){
					$data = $this->db->query("SELECT * FROM {$table} WHERE currency_id =1")->result_array();
				}

				foreach ($data as $row) {
		            $database_structure .= "INSERT INTO {$table} SET ";
		            $datas = [];
		            foreach ($row as $column => $d) {
		            	$datas[] ="`{$column}`= ". $this->db->escape($d);
		            }
		            
		            $database_structure .=  implode(",", $datas) .";\n";
		        }
			}

			foreach ($tables as $key => $table) {
				$data = $this->db->query("SELECT * FROM {$table}")->result_array();
				foreach ($data as $row) {
		            $database_structure .= "INSERT INTO {$table} SET ";
		            $datas = [];
		            foreach ($row as $column => $d) {
		            	$datas[] ="`{$column}`= ". $this->db->escape($d);
		            }
		            
		            $database_structure .=  implode(",", $datas) .";\n";
		        }
			}


			/*$sql_querys = [];
			$lines = explode("\n", $database_structure);
            $sql_query = '';
            foreach($lines as $line) {
                if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
                    $sql_query .= $line;
                    if (preg_match('/;\s*$/', $line)) {
                        $sql_querys[] = trim(preg_replace('/\s\s+/', ' ',$sql_query));
                        $sql_query = '';
                    }
                }
            }*/
		}


		return $database_structure;
	}
}