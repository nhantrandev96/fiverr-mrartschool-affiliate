<div class="row">
	<div class="col-sm-5">
		<p class="help-text"><?= __('admin.system_status_help_line') ?></p>
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

	<div class="col-sm-7">
		<p class="help-text"><?= __('admin.system_information_help_line') ?></p>
		<div class="card m-b-30">
			<div class="card-body p-0" style="overflow: auto;">
				<div class="system-status">
					<div>
						<div>PHP Version</div>
						<div><?= phpversion() ?></div>
					</div>
                    <div>
                    	<div>Database Version</div>
                    	<div><?= database_version() ?></div>
                	</div>
                	<div>
                    	<div>Database Software</div>
                    	<div><?= database_software() ?></div>
                	</div>

                	<div>
                    	<div>System OS</div>
                    	<div><?= server_os() ?></div>
                	</div>
                	<div>
                    	<div>Memory Limit</div>
                    	<div><?= check_limit() ?></div>
                	</div>
                	<div>
                    	<div>Server IP</div>
                    	<div><?= check_server_ip() ?></div>
                	</div>
                	<div>
                    	<div>Max File Upload Size </div>
                    	<div><?= php_max_upload_size() ?></div>
                	</div>
                	<div>
                    	<div>Post Variable Size</div>
                    	<div><?= php_max_post_size() ?></div>
                	</div>
                	<div>
                    	<div>Max Execution Time</div>
                    	<div><?= php_max_execution_time() ?></div>
                	</div>
				</div>
			</div>
		</div> 
	</div> 
</div>

