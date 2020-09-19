<?php

define("BASEPATH", __DIR__);
require_once 'install/helper.php';
require_once 'application/config/database.php';
$serverReq = checkReq();

$db = $db['default'];

$con = mysqli_connect($db['hostname'],$db['username'],$db['password'],$db['database']);
if (mysqli_connect_errno()){
  	$con = false;
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Server Analyzer...</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="assets/vertical/assets/plugins/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="assets/css/jquery-confirm.min.css">
    <link href="assets/vertical/assets/plugins/morris/morris.css" rel="stylesheet">
    
    <link href="assets/vertical/assets/plugins/chartist/css/chartist.min.css" rel="stylesheet" type="text/css">
    <link href="assets/vertical/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/vertical/assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="assets/vertical/assets/css/style.css?v=1" rel="stylesheet" type="text/css">
    
    <link href="assets/vertical/assets/plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen">
    <link rel="icon" href="assets/images/site/Z3wGaIpoYTg2sftybHNeS0mlWVKMq4hz.png" type="image/*" sizes="16x16">
    <link href="assets/css/jquery.uploadPreviewer.css" rel="stylesheet" type="text/css" media="screen">
    <link rel="stylesheet" href="assets/css/check.css">
</head>
<body>
	<div class="container">
		<br><br>
		<h2 class="website-title">Server Analyzer...</h2>
		<br/><br/>
		<div class="row">
			<div class="col-xl-6">
				<p class="help-text">Here You can check your system status..</p>
				<div class="card m-b-30">
					<div class="card-body p-0" style="overflow: auto;">
						<div class="system-status">
							<div class="<?= array_key_exists('php', $serverReq) ? 'error' : 'success' ?>" >
								<div>PHP Version</div>
								<div>Minimum version 5.6</div>
								<div><i class="fa fa-icon"></i></div>
							</div>
		                    <div class="<?= array_key_exists('curl', $serverReq) ? 'error' : 'success' ?>" >
		                    	<div>Curl</div>
		                    	<div>Extension <i>php_curl</i></div>
		                    	<div><i class="fa fa-icon"></i></div>
		                	</div>
		                    <div class="<?= array_key_exists('openssl_encrypt', $serverReq) ? 'error' : 'success' ?>" >
		                    	<div>Openssl Encrypt</div>
		                    	<div>Extension <i>openssl_encrypt</i></div>
		                    	<div><i class="fa fa-icon"></i></div>
		                	</div>
		                    <div class="<?= array_key_exists('mysqli', $serverReq) ? 'error' : 'success' ?>" >
		                    	<div>Mysqli</div>
		                    	<div>Extension <i>mysqli</i></div>
		                    	<div><i class="fa fa-icon"></i></div>
		                	</div>
		                    <div class="<?= array_key_exists('sourceguardian', $serverReq) ? 'error' : 'success' ?>" >
		                    	<div>Source Gurdian</div>
		                    	<div>Extension <i>sourceguardian</i></div>
		                    	<div><i class="fa fa-icon"></i></div>
		                	</div>
		                    <div class="<?= array_key_exists('ipapi', $serverReq) ? 'error' : 'success' ?>" >
		                    	<div>IP API</div>
		                    	<div>Extension <i>php_curl</i></div>
		                    	<div><i class="fa fa-icon"></i></div>
		                	</div>
		                    <div class="<?= array_key_exists('ziparchive', $serverReq) ? 'error' : 'success' ?>" >
		                    	<div>ZipArchive</div>
		                    	<div>Extension <i>zip</i></div>
		                    	<div><i class="fa fa-icon"></i></div>
		                	</div>

		                	<div class="<?= isset($serverReq['allow_url_fopen']) ? 'error' : 'success' ?>" >
		                    	<div>allow_url_fopen</div>
		                    	<div>PHP INI <i> allow_url_fopen</i></div>
		                    	<div><i class="fa fa-icon"></i></div>
		                	</div>
		                    <div class="<?= is_ssl() ? 'success' : 'error' ?>" >
		                    	<div><?= is_ssl() ? 'SSL' : 'Non SSL' ?></div>
		                    	<div>Install <i>SSL</i> Certificate</div>
		                    	<div><i class="fa fa-icon"></i></div>
		                    </div>
						</div>
					</div>
				</div> 
			</div> 

			<div class="col-xl-6">
				<p class="help-text">Your Server Details</p>
				<div class="card m-b-30">
					<div class="card-body p-0" style="overflow: auto;">
						<div class="system-status">
							<div>
								<div>PHP Version</div>
								<div></div>
								<div><?= phpversion() ?></div>
							</div>
		                    <div>
		                    	<div>Database Version</div>
		                    	<div></div>
		                    	<div><?= $con ? database_version($con) : 'Error'  ?></div>
		                	</div>
		                	<div>
		                    	<div>Database Software</div>
		                    	<div></div>
		                    	<div><?= $con ? database_software($con) : 'Error' ?></div>
		                	</div>

		                	<div>
		                    	<div>System OS</div>
		                    	<div></div>
		                    	<div><?= server_os() ?></div>
		                	</div>
		                	<div>
		                    	<div>Memory Limit</div>
		                    	<div></div>
		                    	<div><?= check_limit() ?></div>
		                	</div>
		                	<div>
		                    	<div>Server IP</div>
		                    	<div></div>
		                    	<div><?= check_server_ip() ?></div>
		                	</div>
		                	<div>
		                    	<div>Max File Upload Size </div>
		                    	<div></div>
		                    	<div><?= php_max_upload_size() ?></div>
		                	</div>
		                	<div>
		                    	<div>Post Variable Size</div>
		                    	<div></div>
		                    	<div><?= php_max_post_size() ?></div>
		                	</div>
		                	<div>
		                    	<div>Max Execution Time</div>
		                    	<div></div>
		                    	<div><?= php_max_execution_time() ?></div>
		                	</div>
		                    
						</div>
					</div>
				</div> 
			</div> 
		</div>
	</div>
</body>
</html>