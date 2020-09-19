<?php
class Backup {
    private $dir ='';
    public $lang = [];
    protected $conn_mod = 'mysqli';
    public $mysql = [];
    protected $conn = false;
    public $fetch = 'assoc';
    public $num_rows =0;
    public $error = false;
    function  __construct($lang ='en', $dir ='backup'){
        $this->setLang($lang);
        if($dir !='') $this->dir = APPPATH ."backup/mysql/";
    }
    private function setLang($lang){
        if(file_exists('lang_'. $lang .'.json')) $this->lang = json_decode(file_get_contents('lang_'. $lang .'.json'), true);
        else if(file_exists('lang_en.json')) $this->lang = json_decode(file_get_contents('lang_en.json'), true);
        if(!is_array($this->lang)){
            $this->lang = [];
            echo $this->langTxt('er_json');
        }
    }
    public function langTxt($key){
        if(isset($this->lang[$key])) return $this->lang[$key];
        else return $key;
    }
    public function getTables(){
        $re = [];
        $resql = $this->sqlExec('SHOW TABLES');
        $nr = $this->num_rows;
        for($i=0; $i<$nr; $i++) $re[] = $resql[$i]['Tables_in_'. $this->mysql['dbname']];
            return $re;
    }
    public function getListTables(){
        $tables = $this->getTables();
        $re = ['f'=>'', 'er'=>''];
        if(!$this->error){
            $nr = count($tables);
            $re['f'] ='<form action="'. $_SERVER['REQUEST_URI'] .'" method="post" id="frm_cht"><h3>'. $this->langTxt('msg_select_tbles') .'</h3><label id="ch_all"><input type="checkbox">'. sprintf($this->langTxt('msg_select_all'), $nr) .'</label><br><div class="ch_tables">';
            for($i=0; $i<$nr; $i++) {
                if($i >0 && ($i %12) ==0) $re['f'] .='</div><div class="ch_tables">';
                $re['f'] .= '<label><input type="checkbox" name="tables[]" value="'. $tables[$i] .'">'. $tables[$i] .'</label>';
            }
            $re['f'] .='</div><br>';
            $re['f'] = str_ireplace('<div class="ch_tables"></div>', '', $re['f']) .'<input type="submit" value="'. $this->langTxt('msg_backup') .'"></form>';
        }
        else $re['er'] = $this->error;
        return $re;
    }
    public function getSqlBackup($tables){
        $re = '-- # A Mysql Backup System
        -- # Export created: '. date('Y/m/d') .' on ' . date('h:i'). '
        -- # Database : '. $this->mysql['dbname']. PHP_EOL;
        $re .= 'SET AUTOCOMMIT = 0 ;'. PHP_EOL;
        $re .= 'SET FOREIGN_KEY_CHECKS=0 ;'. PHP_EOL;

        foreach($tables as $table){
            $re .= PHP_EOL .'-- # Tabel structure for table `' . $table . '`'. PHP_EOL;
            $re.= 'DROP TABLE  IF EXISTS `'.$table.'`;'. PHP_EOL;
            $shema = $this->sqlExec('SHOW CREATE TABLE `'.$table.'`');
            $re.= $shema[0]['Create Table'] .';'. PHP_EOL;
            $shema ='';
          
            $resql = $this->sqlExec('SELECT * FROM `'. $table.'`');
            
            if($this->num_rows >0){
                $nr = $this->num_rows;
                $re .= PHP_EOL .'INSERT INTO `'.$table .'` (`'. implode('`, `', array_keys($resql[0])) .'`) VALUES ';
                $data_rows = [];
                for($i=0; $i<$nr; $i++){
                    $data_cols = [];
                    foreach($resql[$i] AS $k => $v) {
                        $data_cols[] = is_numeric($v) ? $v :"'". addslashes($v) ."'";
                    }
                    $data_rows[] = '('. implode(', ', $data_cols) .')';
                    $data_cols ='';
                }
                $resql ='';
                $re .= implode(', '. PHP_EOL, $data_rows) .';'. PHP_EOL;
                $data_rows ='';
            }
            
        }
        $re .= PHP_EOL .'SET FOREIGN_KEY_CHECKS = 1;
        COMMIT;
        SET AUTOCOMMIT = 1; '. PHP_EOL;
        return $re;
    }
    public function saveBkZip($tables, $filename = ''){
         
        if(!is_dir($this->dir) || !is_writable($this->dir) ){
            mkdir($this->dir, 0777, true);
        }
        if($this->dir =='' || is_writable($this->dir)){
            //$file_sql ='backup-'. $this->mysql['dbname'] .'-'. date('d-m-Y'). '@'. date('H.i.s') .'.sql';
            $file_sql = $filename .'.sql';
            
            $file_zip = $this->dir . $file_sql .'.zip';
            $zip = new ZipArchive();
            $zip_open = $zip->open($file_zip, ZIPARCHIVE::CREATE);
            if($zip_open){
                $zip->addFromString($file_sql, $this->getSqlBackup($tables));
                $re = sprintf($this->langTxt('ok_saved'), $file_sql);
            }
            else $re = sprintf($this->langTxt('er_saved'), $file_sql);
            $zip->close();
        }
        else $re = sprintf($this->langTxt('er_write'), $this->dir);
        return $re;
    }
    public function getListZip(){
        $data = array();
        $files = glob($this->dir .'*.zip');
        array_multisort(
          array_map( 'filemtime', $files ),
          SORT_NUMERIC,
          SORT_DESC,
          $files
        );
        
        $nr = count($files);
        for($i=0; $i<$nr; $i++) {
            $file = str_ireplace($this->dir, '', $files[$i]);
            $size = filesize($files[$i]);
            $size = ($size/1024 <1) ? number_format($size, 2) .' Bytes' : (($size/1024 >=1 && $size/(1024*1024) <1) ? number_format($size/1024, 2) .' KB' : number_format($size/(1024*1024), 2) .'MB');
            $data[] = array(
                'file' => $file,
                'size' => $size,
                'date' => date("d M Y, h:i:s A", filectime($files[$i])),
            );
        }
        return $data;
    }
    public function restore($zp){
        $zip = new ZipArchive();
        if($zip->open($this->dir . $zp) === TRUE) {
            $sql = $zip->getFromIndex(0);
            $sql = preg_replace('/^-- # .*[\r\n]*/m', '', $sql);
            $zip->close();
            if(floor(strlen($sql)/(1024*1024)) <50){
                $resql = $this->multiQuery($sql);
                $sql ='';
                $re = ($resql) ? $this->langTxt('ok_res_backup') : $this->langTxt('er_res_backup');
            }
            else if(function_exists('exec')){
                if(file_put_contents($this->dir .'temp.sql', $sql)){
                    $zip ='';  $sql ='';
                    $resql = $this->sqlExec("SHOW VARIABLES LIKE 'basedir'");
                    $mysql_exe = $resql[0]['Value']. '/bin/mysql.exe';
                    $cmd = $mysql_exe ." -h {$this->mysql['host']} --user={$this->mysql['user']} --password={$this->mysql['pass']}  -D {$this->mysql['dbname']} < ". realpath($this->dir .'temp.sql');
                    exec($cmd, $out, $ere);
                    $re = (!$ere) ? $this->langTxt('ok_res_backup') : $this->langTxt('er_res_backup');
                    @unlink($this->dir .'temp.sql');
                }
                else $re = sprintf($this->langTxt('er_write_in'), $this->dir);
            }
            else $this->langTxt('er_exec');
        }
        else $re = sprintf($this->langTxt('er_open'), $zp);
        return $re;
    }
    public function getZipFile($zip){
        if(file_exists($this->dir . $zip)){
            header('Pragma: public');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Cache-Control: private',false);
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename='. $zip .';' );
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: '. filesize($this->dir . $zip));
            readfile($this->dir . $zip);
            exit;
        }
        else return sprintf($this->langTxt('er_file'), $zip);
    }
    public function delFile($file){
        if(@unlink($this->dir . $file)) return $this->langTxt('ok_delete');
        else return sprintf($this->langTxt('er_delete'), $file);
    }
    public function setMysql($conn_data){$this->mysql = $conn_data;}
    protected function setConn($mysql) {
        if($this->conn_mod == 'pdo') {
            if(extension_loaded('PDO') === true) $this->conn_mod = 'pdo';
            else if(extension_loaded('mysqli') === true) $this->conn_mod = 'mysqli';
        }
        else if($this->conn_mod == 'mysqli') {
            if(extension_loaded('mysqli') === true) $this->conn_mod = 'mysqli';
            else if(extension_loaded('PDO') === true) $this->conn_mod = 'pdo';
        }
        if($this->conn_mod == 'pdo') $this->connPDO($mysql);
        else if($this->conn_mod == 'mysqli') $this->connMySQLi($mysql);
        else $this->setSqlError($this-langTxt('er_conn'));
    }
    protected function connPDO($mysql) {
        try {
            $this->conn = new PDO("mysql:host=".$mysql['host']."; dbname=".$mysql['dbname'], $mysql['user'], $mysql['pass']);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec('SET character_set_client="utf8",character_set_connection="utf8",character_set_results="utf8";');
        }
        catch(PDOException $e) {
            $this->setSqlError($e->getMessage());
        }
    }
    protected function connMySQLi($mysql) {
        if($this->conn = new mysqli($mysql['host'], $mysql['user'], $mysql['pass'], $mysql['dbname'])) {
            $this->conn->query('SET character_set_client="utf8",character_set_connection="utf8",character_set_results="utf8";');
        }
        else if (mysqli_connect_errno()) $this->setSqlError('MySQL connection failed: '. mysqli_connect_error());
    }
    private function sqlExec($sql) {
        if($this->conn === false || $this->conn === NULL) $this->setConn($this->mysql);
        $this->affected_rows = 0;
        $re = true;
        if($this->conn !== false) {
            $ar_mode = explode(' ', trim($sql), 2);
            $mode = strtolower($ar_mode[0]);
            $this->error = false;
            if($this->conn_mod == 'pdo') {
                try {
                    if($mode == 'select' || $mode == 'show') {
                        $sqlre = $this->conn->query($sql);
                        $re = $this->getSelectPDO($sqlre);
                    }
                    else $this->conn->exec($sql);
                }
                catch(PDOException $e) { $this->setSqlError($e->getMessage()); }
            }
            else if($this->conn_mod == 'mysqli') {
                $sqlre = $this->conn->query($sql);
                if($sqlre){
                    if($mode == 'select' || $mode == 'show') $re = $this->getSelectMySQLi($sqlre);
                }
                else {
                    if(isset($this->conn->error_list[0]['error'])) $this->setSqlError($this->conn->error_list[0]['error']);
                    else $this->setSqlError('Unable to execute the SQL query');
                }
            }
        }
        if($this->error !== false) $re = false;
        return $re;
    }
    protected function getSelectPDO($sqlre) {
        $re = [];
        if($row = $sqlre->fetch()){
            do {
                foreach($row AS $k=>$v) {
                    if(($this->fetch == 'assoc' && is_int($k)) || ($this->fetch == 'num' && is_string($k))) { unset($row[$k]); continue; }
                    if(is_numeric($v)) $row[$k] = $v + 0;
                }
                $re[] = $row;
            }
            while($row = $sqlre->fetch());
        }
        $this->num_rows = count($re);
        return $re;
    }
    protected function getSelectMySQLi($sqlre) {
        $re = [];
        $fetch = ($this->fetch == 'assoc') ? MYSQLI_ASSOC :(($this->fetch == 'num') ? MYSQLI_NUM : MYSQLI_BOTH);
        while($row = $sqlre->fetch_array($fetch)) {
            $re[] = $row;
        }
        $this->num_rows = count($re);
        return $re;
    }
    public function multiQuery($sql){
        if($this->conn === false || $this->conn === NULL) $this->setConn($this->mysql);
        $this->affected_rows = 0;
        if($this->conn !== false){
            if($this->conn_mod == 'pdo'){
                $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
                $re = ($this->conn->exec($sql) !== false) ? true : false;
            }
            else if($this->conn_mod == 'mysqli'){
                if($this->conn->multi_query($sql)){
                    do {
                        $this->conn->next_result();
                    } while ($this->conn->more_results());
                    $re = true;
                }
                else $re = false;
            }
        }
        else $re = false;
        return $re;
    }
    protected function setSqlError($err) {
        $this->error = $err ;
    }
}