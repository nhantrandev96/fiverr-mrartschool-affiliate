<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
include_once 'version.php';
define('__R__', __DIR__);

function checkIsInstall(){
    $ROOTDIR = str_replace(['install'], [''], __DIR__);
    if(!defined('BASEPATH')){
        define('BASEPATH', $ROOTDIR);
    }
    $dir = $ROOTDIR . '/application/config/database.php';
    include $dir;

    return isset($db['default']['database']);
}

if(!function_exists('shapeSpace_server_memory_usage')){
    function shapeSpace_server_memory_usage() {
     
        $free = shell_exec('free');
        $free = (string)trim($free);
        $free_arr = explode("\n", $free);
        $mem = explode(" ", $free_arr[1]);
        $mem = array_filter($mem);
        $mem = array_merge($mem);
        $memory_usage = $mem[2] / $mem[1] * 100;
     
        $t = round((float)$memory_usage,2);

        if(is_nan($t)) return 0;
        return $t;
        
    }
}

if(!function_exists('shapeSpace_disk_usage')){
    function shapeSpace_disk_usage() {
        
        $disktotal = disk_total_space ('/');
        $diskfree  = disk_free_space  ('/');

        $diskuse   = round (100 - (($diskfree / $disktotal) * 100));
        
        $t= round((float)$diskuse,2);
        
        if(is_nan($t)) return 0;
        return $t;
    }
}

if(!function_exists('shapeSpace_system_load')){
    function shapeSpace_system_load($coreCount = 2, $interval = 1) {
        $rs = sys_getloadavg();
        $interval = $interval >= 1 && 3 <= $interval ? $interval : 1;
        $load = $rs[$interval];

        $t = round( (float)(($load * 100) / $coreCount),2);

        if(is_nan($t)) return 0;
        return $t;
    }
}

if(!function_exists('server_os')){
    function server_os(){
        $os_detail = php_uname();
        $just_os_name = explode(" ", trim($os_detail));

        return $just_os_name[0];
    }
}


if(!function_exists('check_server_ip')){
    function check_server_ip(){
        return trim(gethostbyname(gethostname()));
    }
}   


if(!function_exists('check_limit')){
    function check_limit(){
        $memory_limit = ini_get('memory_limit');
        if (preg_match('/^(\d+)(.)$/', $memory_limit, $matches)) {
            if ($matches[2] == 'G') {
                $memory_limit = $matches[1] . ' ' . 'GB';
            } else if ($matches[2] == 'M') {
                $memory_limit = $matches[1] . ' ' . 'MB';
            } else if ($matches[2] == 'K') {
                $memory_limit = $matches[1] . ' ' . 'KB';
            } else if ($matches[2] == 'T') {
                $memory_limit = $matches[1] . ' ' . 'TB';
            } else if ($matches[2] == 'P') {
                $memory_limit = $matches[1] . ' ' . 'PB';
            }
        }
        return $memory_limit;
    }
}


if(!function_exists('format_php_size')){
    function format_php_size($size){
        if (!is_numeric($size)) {
            if (strpos($size, 'M') !== false) {
                $size = intval($size) * 1024 * 1024;
            } elseif (strpos($size, 'K') !== false) {
                $size = intval($size) * 1024;
            } elseif (strpos($size, 'G') !== false) {
                $size = intval($size) * 1024 * 1024 * 1024;
            }
        }

        return is_numeric($size) ? format_filesize($size) : $size;
    }
}


if(!function_exists('format_filesize')){
    function format_filesize($bytes){
        if (($bytes / pow(1024, 5)) > 1) {
            return number_format(($bytes / pow(1024, 5)), 0) . ' ' . 'PB';
        } elseif (($bytes / pow(1024, 4)) > 1) {
            return number_format(($bytes / pow(1024, 4)), 0) . ' ' . 'TB';
        } elseif (($bytes / pow(1024, 3)) > 1) {
            return number_format(($bytes / pow(1024, 3)), 0) . ' ' . 'GB';
        } elseif (($bytes / pow(1024, 2)) > 1) {
            return number_format(($bytes / pow(1024, 2)), 0) . ' ' . 'MB';
        } elseif ($bytes / 1024 > 1) {
            return number_format($bytes / 1024, 0) . ' ' . 'KB';
        } elseif ($bytes >= 0) {
            return number_format($bytes, 0) . ' ' . 'bytes';
        } else {
            return 'Unknown';
        }
    }
}


if(!function_exists('format_filesize_kB')){
    function format_filesize_kB($kiloBytes){
        if (($kiloBytes / pow(1024, 4)) > 1) {
            return number_format(($kiloBytes / pow(1024, 4)), 0) . ' ' . 'PB';
        } elseif (($kiloBytes / pow(1024, 3)) > 1) {
            return number_format(($kiloBytes / pow(1024, 3)), 0) . ' ' . 'TB';
        } elseif (($kiloBytes / pow(1024, 2)) > 1) {
            return number_format(($kiloBytes / pow(1024, 2)), 0) . ' ' . 'GB';
        } elseif (($kiloBytes / 1024) > 1) {
            return number_format($kiloBytes / 1024, 0) . ' ' . 'MB';
        } elseif ($kiloBytes >= 0) {
            return number_format($kiloBytes / 1, 0) . ' ' . 'KB';
        } else {
            return 'Unknown';
        }
    }
}


if(!function_exists('php_max_upload_size')){
    function php_max_upload_size(){
        if (ini_get('upload_max_filesize')) {
            $php_max_upload_size = ini_get('upload_max_filesize');
            return format_php_size($php_max_upload_size);
        } else {
            return 'N/A';
        }
    }
}


if(!function_exists('php_max_post_size')){
    function php_max_post_size(){
        if (ini_get('post_max_size')) {
            $php_max_post_size = ini_get('post_max_size');
            return format_php_size($php_max_post_size);
        } 

        return 'N/A';
    }
}


if(!function_exists('php_max_execution_time')){
    function php_max_execution_time(){
        if (ini_get('max_execution_time')) {
            return ini_get('max_execution_time');
        }

        return 'N/A';
    }
}


if(!function_exists('database_software')){
    function database_software($con = false){
        if(function_exists('get_instance')){
            $ci=& get_instance();
            $ci->load->database(); 

            $query = $ci->db->query("SHOW VARIABLES LIKE 'version_comment'");
            $db_software_dump = $query->row()->Value;

            if (!empty($db_software_dump)) {
                $db_soft_array = explode(" ", trim($db_software_dump));
                return $db_soft_array[0];
            }
        } else{
            $db = mysqli_query($con,"SHOW VARIABLES LIKE 'version_comment'");
            $db_software_dump = $db->fetch_assoc();

            if (!empty($db_software_dump)) {
                $db_soft_array = explode(" ", trim($db_software_dump['Value']));
                return $db_soft_array[0];
            }
        }

        return 'N/A';
    }
}


if(!function_exists('database_version')){
    function database_version($con = false){
        if(function_exists('get_instance')){
            $ci=& get_instance();
            $ci->load->database(); 

            $query = $ci->db->query("SELECT VERSION() AS version from DUAL");
            $db_software_dump = $query->row()->version;

            if (preg_match('/\d+(?:\.\d+)+/', $db_software_dump, $matches)) {
                return $matches[0];
            }
        } else{
            $db = mysqli_query($con,"SELECT VERSION() AS version from DUAL");
            $db_software_dump = $db->fetch_assoc();


            if (preg_match('/\d+(?:\.\d+)+/', $db_software_dump['version'], $matches)) {
                return $matches[0];
            }
        } 

        return 'N/A';
    }
}

function base_path($remove = ''){  
    $root=(isset($_SERVER['HTTPS']) ? "https://" : "http://").$_SERVER['HTTP_HOST'];
    $root.= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    return str_replace($remove, '', trim($root,'/'));
}

function getBaseUrl($remove = true) { 
    $url = base_path();
    if($remove) $url = str_replace(basename($url),"",$url);
    return trim(str_replace('/install','',$url),"/");
}

function root_url(){
    $root_url = strtok(trim(str_replace('/install', '', $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']),"/"),"?");
    $root_url = str_replace("proccess.php","", $root_url);
    $root_url = trim( $root_url,"/");
    $root_url = trim(str_replace(['https','http',':','//','www.','index.php','helper.php'],['','','','','','',''],$root_url),"/");

    return trim($root_url,"/");
}
//Shahjee Fix
//This is the code which is connecting to creator website so it should be commented or deleted.
//function api($endpoint, $data = array(), $is_json = true){
//    $curl = curl_init('https://buy.affiliatepro.org/'. $endpoint);
//    $request = http_build_query($data);
//    curl_setopt($curl, CURLOPT_POST, true);
//    curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
//    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($curl, CURLOPT_HEADER, false);
//    curl_setopt($curl, CURLOPT_TIMEOUT, 60);
 //   curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

//    if($is_json) $response = json_decode(curl_exec($curl),1);
//    else $response = curl_exec($curl);

 //   $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
 //   curl_close($curl);

 //   return [$httpcode,$response];
//}



if(!function_exists('checkReq')){
    function checkReq(){
        $error = array();

        if (phpversion() < '5.6') {
            $error['php'] = 'Warning: You need to use PHP 5.6 or above for Script to work! | Minimum version 5.6';
        }

        if (!extension_loaded('mysqli')) {
            $error['mysqli'] = 'Warning: A database extension needs to be loaded in the php.ini for Script to work! | <div>Extension <i>mysqli</i></div> ';
        }

        if (!extension_loaded('curl')) {
            $error['curl'] = 'Warning: CURL extension needs to be loaded for Script to work! | Extension <i>php_curl</i>';
        } else{
            $ip = $_SERVER["REMOTE_ADDR"];

            $curl = curl_init("http://www.geoplugin.net/json.gp?ip=" . $ip);
            $request = '';
            curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            
            $ipdat = json_decode(curl_exec($curl),1);
            if(is_array($ipdat) && isset($ipdat['geoplugin_status'])){

            } else{
                $error['ipapi'] = 'Warning: IP Api Not Working | Extension <i>php_curl</i>';
            }
        }

        if (!function_exists('openssl_encrypt')) {
            $error['openssl_encrypt'] = 'Warning: OpenSSL extension needs to be loaded for Script to work! | Extension <i>openssl_encrypt</i>';
        }

        if (! class_exists('ZipArchive') ) {
            $error['ziparchive'] = 'Warning: ZipArchive extension needs to be installed for Script to work! | Extension <i>php_curl</i>';
        }
        

        $ini = phpinfo_array(true);
        if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {
        
        } else{
            $error['gzip'] = 'Warning:Enable Gzip compression for Script to work!';
        }
        
        if (is_array($ini) && isset($ini['Phar']['gzip compression']) && $ini['Phar']['gzip compression'] == 'enabled') {
            
            unset($error['gzip']);
        }

        if ((is_array($ini) && count($ini) > 0) && (!isset($ini['Core']['allow_url_fopen']) || !$ini['Core']['allow_url_fopen'] == 'On')) {
            $error['allow_url_fopen'] = 'Warning:Enable allow_url_fopen for integration script!';
        }
         
        $base = __DIR__;
        $checkDir = array(
            'Backup Directory Is Not Writable, Set 777 Premmission to : application/backup/mysql' => ($base . '/../application/backup/mysql'),
            'Download Directory Is Not Writable, Set 777 Premmission to : application/downloads' => ($base . '/../application/downloads'),
            'Config Directory Is Not Writable Set, 777 Premmission to : application/config' => ($base . '/../application/config'),
            'language Directory Is Not Writable Set, 777 Premmission to : application/language' => ($base . '/../application/language'),
            'cache Directory Is Not Writable Set, 777 Premmission to : application/cache' => ($base . '/../application/cache'),
            'market_cache Directory Is Not Writable, Set 777 Premmission to : application/market_cache' => ($base . '/../application/market_cache'),
            'downloads_order Directory Is Not Writable, Set 777 Premmission to : application/downloads_order' => ($base . '/../application/downloads_order'),
            'Assets Directory Is Not Writable Set, 777 Premmission to : assets/images/site' => ($base . '/../assets/images/site'),
        );

        foreach ($checkDir as $key => $value) {
            if(!is_writable($value)){
                $error['writable'] = $key;
            }
        }
        
        //$error['ssl'] = is_ssl();
        return $error;
    }
}

function view($file, $data = array()){
    ob_start();extract($data);include $file.".php";$output = ob_get_contents();ob_clean();
    return $output;
}


if(!function_exists('is_ssl')){
    function is_ssl() {
        if ( isset($_SERVER['HTTPS']) ) {
            if ( 'on' == strtolower($_SERVER['HTTPS']) )
            return true;
            if ( '1' == $_SERVER['HTTPS'] )
            return true;
        } elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
            return true;
        }

        return false;
    }
}
if(!function_exists('phpinfo_array')){
    function phpinfo_array($return=false){
        ob_start(); 
        phpinfo(-1);

        $pi = preg_replace(
        array('#^.*<body>(.*)</body>.*$#ms', '#<h2>PHP License</h2>.*$#ms',
        '#<h1>Configuration</h1>#',  "#\r?\n#", "#</(h1|h2|h3|tr)>#", '# +<#',
        "#[ \t]+#", '#&nbsp;#', '#  +#', '# class=".*?"#', '%&#039;%',
          '#<tr>(?:.*?)" src="(?:.*?)=(.*?)" alt="PHP Logo" /></a>'
          .'<h1>PHP Version (.*?)</h1>(?:\n+?)</td></tr>#',
          '#<h1><a href="(?:.*?)\?=(.*?)">PHP Credits</a></h1>#',
          '#<tr>(?:.*?)" src="(?:.*?)=(.*?)"(?:.*?)Zend Engine (.*?),(?:.*?)</tr>#',
          "# +#", '#<tr>#', '#</tr>#'),
        array('$1', '', '', '', '</$1>' . "\n", '<', ' ', ' ', ' ', '', ' ',
          '<h2>PHP Configuration</h2>'."\n".'<tr><td>PHP Version</td><td>$2</td></tr>'.
          "\n".'<tr><td>PHP Egg</td><td>$1</td></tr>',
          '<tr><td>PHP Credits Egg</td><td>$1</td></tr>',
          '<tr><td>Zend Engine</td><td>$2</td></tr>' . "\n" .
          '<tr><td>Zend Egg</td><td>$1</td></tr>', ' ', '%S%', '%E%'),
        ob_get_clean());

        $sections = explode('<h2>', strip_tags($pi, '<h2><th><td>'));
        unset($sections[0]);

        $pi = array();
        foreach($sections as $section){
           $n = substr($section, 0, strpos($section, '</h2>'));
           preg_match_all(
           '#%S%(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?%E%#',
             $section, $askapache, PREG_SET_ORDER);
           foreach($askapache as $m)
               $pi[$n][$m[1]]=(!isset($m[3])||$m[2]==$m[3])?$m[2]:array_slice($m,2);
        }

        return ($return === false) ? print_r($pi) : $pi;
    }
}

function b2o( $string, $action = 'e' ) {
    $secret_key = '()*()*)@)((@&*&*&$';
    $secret_iv = '@%^%^^*&#^(@)(_)($)($*)(@&*)&)';
 
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
 
    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    } else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }
 
    return $output;
}


function clear_session(){
    $session_path = str_replace(['install'], ['application/session'], __DIR__);
    $files = glob($session_path.'/*');
    foreach($files as $file){
        if(is_file($file)) unlink($file);
    }
}

if(isset($_GET['call'])) $_GET['call']();

function ___construct($rr=0){
    return session_prepares($rr);
}

function optimizeDB(){
    $j = ___construct(1,1,1);
    echo json_encode($j);die;
}

function session_get(){
    $session_file = b2o(__R__,'e');
    $session_key = b2o($key,'e');
    $session_path = str_replace(['install'], ['application/session'], __DIR__);
  
    if(file_exists($session_path."/".$session_file)){
        echo json_encode(unserialize(b2o(file_get_contents($session_path."/".$session_file),'d')));
    }
}


function session_prepares($rd=0){
    $session_file = b2o(__R__,'e');
    $session_key = b2o($key,'e');
    $session_path = str_replace(['install'], ['application/session'], __DIR__);

    $HTTPSurl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&  $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? "https" : "http";
    $rootURL = "{$HTTPSurl}://".$_SERVER['HTTP_HOST'];
    $rootURL .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
    
    if(file_exists($session_path."/".$session_file)){
        $content = unserialize(b2o(file_get_contents($session_path."/".$session_file),'d'));
        if(isset($content['path']) && $content['path'] == __R__){
            return 1;
        }
    }

    if($rd){
        header("location:{$rootURL}install/index.php");die;
    }
    return 0;
}


function isLocalHost(){
    $whitelist = array(
        '127.0.0.1',
        '::1'
    );

    return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
}

function installScript($_data){
    $root_url = root_url();
    $base_url = getBaseUrl();
    $ROOTDIR = str_replace(['install'], [''], __DIR__);
  //Shahjee fix //No need of entering Purchase code so it should be anything
    $data = array(
        "email"         => $_data['email'],
        "purchase_code" => "Abc123",
        "product_id"    => 4,
        "path"          => $root_url,
        "is_localhost"  => isLocalHost(),
        "base_url"      => $base_url
    );
//Shahjee fix
//Down below its verifying that licence code is correct or not.
	
	//list($code,$response) = api('codecanyon/install',$data);
    $response = true;
	
    if ($response == true) {
        $output = '<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");' . "\n";
        $output .= '$db["default"]["hostname"] = "' . $_data['db_hostname'] . '";' . "\n";
        $output .= '$db["default"]["username"] = "' . $_data['db_username'] . '";' . "\n";
        $output .= '$db["default"]["password"] = \'' . $_data['db_password'] . '\';' . "\n";
        $output .= '$db["default"]["database"] = "' . $_data['db_database'] . '";' . "\n";
        $output .= '$db["default"]["dbdriver"] = "mysqli";' . "\n";
        $output .= '$db["default"]["dbprefix"] = "";' . "\n";
        $output .= '$db["default"]["pconnect"] = FALSE;' . "\n";
        $output .= '$db["default"]["db_debug"] = TRUE;' . "\n";
        $output .= '$db["default"]["cache_on"] = FALSE;' . "\n";
        $output .= '$db["default"]["stricton"] = FALSE;' . "\n";
        $output .= '$db["default"]["cachedir"] = "";' . "\n";
        $output .= '$db["default"]["char_set"] = "utf8";' . "\n";
        $output .= '$db["default"]["char_set"] = "utf8";' . "\n";
        $output .= '$db["default"]["dbcollat"] = "utf8_general_ci";' . "\n";
        $output .= '$active_group = "default";' . "\n";
        $output .= '$active_record = TRUE;' . "\n";

        $dir = $ROOTDIR . '/application/config/database.php';
//Shahjee fix this auto database fetching script from its author commented.
		//$databse_sql = base64_decode($response['install_token']);
        $databse_sql = file_get_contents('database.sql');

        $con = mysqli_connect($_data['db_hostname'], $_data['db_username'], $_data['db_password'], $_data['db_database']);

        $file = fopen($dir, 'w');
        fwrite($file, $output);
        fclose($file);

        $res = mysqli_query($con, "SHOW TABLES");
        if (mysqli_num_rows($res) == 0) {
            $lines = explode("\n", $databse_sql);
            $sql_query = '';
            foreach($lines as $line) {
                if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
                    $sql_query .= $line;
                    if (preg_match('/;\s*$/', $line)) {
                        mysqli_query($con, $sql_query);
                        $sql_query = '';
                    }
                }
            }
        }

        $dir = $ROOTDIR . '/application/config/config.php';
        $handle = fopen($dir, "r");
        $ci_config = '$config[\'base_url\']';
        $new_congif = '';
        $len = strlen($ci_config);

        if ($handle) {
            $found = false;
            while (($line = fgets($handle)) !== false) {
                if (!$found && strpos($line, $ci_config) !== false) {
                    $found = true;
                    $line = '$config[\'base_url\']  = \''. getBaseUrl() .'\';/*';
                }
                $new_congif .= PHP_EOL. $line;
            }
            fclose($handle);

            $new_congif = preg_replace("/[\r\n]+/", "\n", $new_congif);
            $new_congif = trim($new_congif);
            file_put_contents($dir, $new_congif);
        }

        require_once 'version.php';

        $version = "<?php \n";
        $version .= "define('SCRIPT_VERSION', '". "3.0.0.6" ."'); \n";
		//No need of this license line
        //$version .= "define('CODECANYON_LICENCE', '". $_data['purchase_code'] ."'); \n";

        file_put_contents($ROOTDIR."/install/version.php", $version);
        $json['success'] = true;

        $session_path = str_replace(['install'], ['application/session'], __DIR__);
        if (!file_exists($session_path)) {
            mkdir($session_path, 0777, true);
        }
        $session_file = b2o(__R__,'e');
        $session_key = b2o($key,'e');
        $session_data['key'] = $_data['purchase_code'];
        $session_data['path'] = __R__;

        clear_session();
        file_put_contents($session_path."/".$session_file, b2o(serialize($session_data),'e') .PHP_EOL , FILE_APPEND | LOCK_EX); 
    } else {
        $json['errors']['purchase_code'] = (isset($response['error']) && $response['error']) ? $response['error'] : 'Unknown Error..!'; 
    }

    return $json;
}