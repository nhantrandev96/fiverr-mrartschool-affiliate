<div class="row bs-wizard" style="border-bottom:0;">
    <div class="col-xs-6 bs-wizard-step active -complete">
        <div class="text-center bs-wizard-stepnum">Step 1</div>
        <div class="progress"><div class="progress-bar"></div></div>
        <a href="#" class="bs-wizard-dot"></a>
        <div class="bs-wizard-info text-center">Installation</div>
    </div>

    <div class="col-xs-6 bs-wizard-step disabled">
        <div class="text-center bs-wizard-stepnum">Step 2</div>
        <div class="progress"><div class="progress-bar"></div></div>
        <a href="#" class="bs-wizard-dot"></a>
        <div class="bs-wizard-info text-center">Installation complete</div>
    </div>
</div>
<?php 
    function getData($key,$default = ''){
        return isset($_POST[$key]) ? $_POST[$key]  : $default;
    }
    function getError($key,$error){
        return isset($error[$key]) ? '<div class="text-danger">'. $error[$key] .'</div>'  : '';
    }

    $allow_installed = true;

    $serverReq = checkReq();
    foreach ($serverReq as $key => $value) {
        echo "<div class='alert alert-danger'>". $value ."</div>";
    }
?>
  
<div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-6">
        <div class="main-body">
            <form id="register_form">
                <?php if($checkIsInstall){  ?>
                    <div class="alert alert-danger">
                        <ul>
                        <span style="Border:solid 2px black">YOUR UPGRADE IS SUCCESSFULLY DONE!YOUR SITE DATA IS SAFE!</span>
                        <li>Re-type your codecanyon mail account, Codecanyon license, Current running database details and press contiune.</li>
                        <li>If you don't have a codecanyon license for this domain, you will not be able to run the script.</li>
                        <li>Please <a class="btn btn-danger btn-sm" href="https://codecanyon.net/item/affiliate-management-system/25393355If" target="_blank">BUY</a> a new license or 
                        <a class="btn btn-danger btn-sm" target="_blank" href="https://codecanyon.net/item/affiliate-management-system/25393355/support">contact us</a> </li>
                    </ul>
                    </div>
                <?php } ?>

                <div class="form-group">
                    <label class="control-label">Email</label>
                    <input type="text" name="email" class="form-control" placeholder="Email">
                </div>

                <div class="form-group required">
                    <label class="control-label" for="input-db-hostname">Database Hostname</label>
                    <div class="">
                        <input type="text" name="db_hostname" value="<?= getData('db_hostname','localhost') ?>" id="input-db-hostname" class="form-control">
                        <?= getError('db_hostname',$error) ?>
                    </div>
                </div>
                <div class="form-group required">
                    <label class="control-label" for="input-db-username">Database Username</label>
                    <div class="">
                        <input type="text" name="db_username" value="<?= getData('db_username') ?>" id="input-db-username" class="form-control">
                        <?= getError('db_username',$error) ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label" for="input-db-password">Database Password</label>
                    <div class="">
                        <input readonly="" onfocus="this.removeAttribute('readonly');" onblur="this.setAttribute('readonly','readonly');" type="password" name="db_password" value="<?= getData('db_password') ?>" id="input-db-password" class="form-control">
                    </div>
                </div>
                <div class="form-group required">
                    <label class="control-label" for="input-db-database">Database Name</label>
                    <div class="">
                        <input type="text" name="db_database" value="<?= getData('db_database') ?>" id="input-db-database" class="form-control">
                        <?= getError('db_error',$error) ?>
                        <?= getError('db_database',$error) ?>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Continue</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-sm-4"> 
        <div class="main-body">
            
            <div class="right-details">
                <h4>Server Requirement</h4>
                <div>
                    <ul class="server-reqirement">
                        <li class="<?= array_key_exists('php', $serverReq) ? 'error' : 'success' ?>" >PHP Version</li>
                        <li class="<?= array_key_exists('curl', $serverReq) ? 'error' : 'success' ?>" >Curl</li>
                        <li class="<?= array_key_exists('openssl_encrypt', $serverReq) ? 'error' : 'success' ?>" >Openssl Encrypt</li>
                        <li class="<?= array_key_exists('mysqli', $serverReq) ? 'error' : 'success' ?>" >Mysqli</li>
                        <li class="<?= array_key_exists('ipapi', $serverReq) ? 'error' : 'success' ?>" >IP API</li>
                        <li class="<?= array_key_exists('ziparchive', $serverReq) ? 'error' : 'success' ?>" >ZipArchive</li>
                        <li class="<?= array_key_exists('gzip', $serverReq) ? 'error' : 'success' ?>" >Gzip compression</li>
                        <li class="<?= array_key_exists('allow_url_fopen', $serverReq) ? 'error' : 'success' ?>" >allow_url_fopen</li>
                        <li class="<?= is_ssl() ? 'success' : 'error' ?>" ><?= is_ssl() ? 'SSL' : 'Non SSL' ?></li>
                    </ul>
                </div>
            </div>
        </div>              
    </div>
</div>
    
<script type="text/javascript">
    $("#register_form").submit(function(){
        $this = $(this);
        $.ajax({
            url:'proccess.php',
            type:'POST',
            dataType:'json',
            data:$this.serialize()+'&page=step2',
            beforeSend:function(){$this.find("button[type=submit]").btn("loading");},
            complete:function(){$this.find("button[type=submit]").btn("reset");},
            success:function(json){
                if(json['html']){
                    $("#main").html(json['html']);
                }

                $this.find(".has-error").removeClass("has-error");
                $this.find("span.text-danger").remove();                
                if(json['errors']){
                    $.each(json['errors'], function(i,j){
                        $ele = $this.find('[name="'+ i +'"]');
                        if($ele){
                            $ele.parents(".form-group").addClass("has-error");
                            $ele.after("<span class='text-danger'>"+ j +"</span>");
                        }
                    })
                }
            },
        })

        return false;
    })
</script>