<?php
	session_start();
	$post = $_POST;
	$json = array();
	$force_step2 = false;

	include_once 'helper.php';

	if (isset($post['page']) && $post['page'] == 'step1') {
		$d['checkIsInstall'] = checkIsInstall();
		$json['html'] = view('steps/step1',$d);
	} else if (isset($post['page']) && $post['page'] == 'step2') {
		$post['access_type'] = 'direct_access';
		$post['root_url'] = root_url();
		$post['base_url'] = getBaseUrl();
			
		if(!isset($post['email']) || $post['email'] == '') { $json['errors']['email'] = 'Email is required!'; }
		if(!isset($post['db_hostname']) || $post['db_hostname'] == '') { $json['errors']['db_hostname'] = 'Database Hostname is required!'; }
		if(!isset($post['db_username']) || $post['db_username'] == '') { $json['errors']['db_username'] = 'Database Username is required!'; }
		if(!isset($post['db_database']) || $post['db_database'] == '') { $json['errors']['db_database'] = 'Database Database is required!'; }

		if(!isset($json['errors'])){
			try {
				try {
					mysqli_report(MYSQLI_REPORT_STRICT);
					$db = new \mysqli(
						$post['db_hostname'], 
						$post['db_username'], 
						html_entity_decode($post['db_password'], ENT_QUOTES, 'UTF-8'), 
						$post['db_database'], 
						3306
					);
					
					if (isset($db->affected_rows)) { $db->close(); }
					else{ $json['errors']['db_database'] = "Error: Could not connect to the database please make sure the database server, username and password is correct!"; }
				} catch (mysqli_sql_exception  $e) {
					$json['errors']['db_database'] = "Error: Could not connect to the database please make sure the database server, username and password is correct!";
				}
			} catch(Exception $e) {
				$error['db_database'] = "Error: Could not connect to the database please make sure the database server, username and password is correct!";
			}
		}

		if(!isset($json['errors'])){
			$json = installScript($post);
			if (isset($json['success'])) {
				$force_step2 = 'step3';
			}
		}
	}

	if ($force_step2 == 'step3') {
		$view_data['base_url'] = getBaseUrl();;
		$view_data['user']     = $_SESSION['api_login'];
		$view_data['license']  = false;
	
		$json['html'] = view('steps/step3',$view_data);
	}

	if ($force_step2 == 'step1') {
		$d['checkIsInstall'] = checkIsInstall();
		$json['html'] = view('steps/step1',$d);
	}

	if (isset($post['action']) && $post['action'] == 'send_message') {
		$json = api('api/send_message',$post, true);
	}

	echo json_encode($json);
?>