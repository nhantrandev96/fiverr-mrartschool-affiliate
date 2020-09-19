<div class="container">
	<div class="row">
		<div class="col">
			<h1 class="mt-5 text-center">Affiliate login</h1>
		</div>
	</div>
	<script type="text/javascript">
		var grecaptcha = undefined;
	</script>
	<?php 
		$db =& get_instance(); 
		$products = $db->Product_model; 
	    $googlerecaptcha =$db->Product_model->getSettings('googlerecaptcha');
	    
	?>
	<div class="row">
		<div class="col-sm-6 mt-5 offset-sm-3">
			<form class="login-form">
			    
			    <input type="hidden" name="type" value="user">

				<div class="form-group">
	                <span class="control-label"><?= __('user.username') ?></span>
	                <input class="form-control" type="text" name="username" placeholder="<?= __('user.username') ?>">
	                <span class="focus-input100"></span>
	            </div>

	            <div class="form-group">
	                <span class="control-label"><?= __('user.password') ?></span>
	                <input class="form-control" type="password" name="password" placeholder="*************">
	                <span class="focus-input100"></span>
	            </div>

	            <?php if (isset($googlerecaptcha['affiliate_login']) && $googlerecaptcha['affiliate_login']) { ?>
					<div class="captch mb-3">
						<script src='https://www.google.com/recaptcha/api.js'></script>
						<div class="g-recaptcha" data-sitekey="<?= $googlerecaptcha['sitekey'] ?>"></div>
						<!-- <input type="hidden" name="captch_response" id="captch_response"> -->
					</div>
				<?php } ?>


	            <div class="login-error"></div>

	            <div class="text-left pb-5">
	            	<div class="row">
	            		<div class="col">	
	                		<a href="<?= base_url('forget-password') ?>" ><?= __('user.forget_password') ?> ?</a>
	            		</div>
	            		<div class="col text-right">	
	                		<a href="<?= base_url('register') ?>" class="dis-block txt3 hov1 p-r-30 p-t-10 p-b-10 p-l-30 change-view" data-type='register'><?= __('user.sign_up') ?></a>
	                	</div>
	            	</div>
	            </div>


	            <div class="row">
	            	<div class="col text-right">
	                	<button class="btn btn-primary"><?= __('user.sign_in') ?></button>
	                </div>
	            </div>

			</form>
		</div>
	</div>
</div>

<div style="display:none;"><a href="https://affiliatepro.org">affiliate pro</a></div>

<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/login/style.css') ?>">

<script type="text/javascript">
	$('.login-form').on('submit',function(){
        $.ajax({
            url:'<?= base_url('auth') ?>/login',
            type:'POST',
            dataType:'json',
            data: $('.login-form').serialize(),
            beforeSend:function(){ $('.login-form button').prop("disabled",true); },
            complete:function(){ $('.login-form button').prop("disabled",false); },
            success:function(json){
                $container = $('.login-form');
                $container.find(".has-error").removeClass("has-error");
                $container.find("span.text-danger").remove();
                
                if(json['errors']){
                    $.each(json['errors'], function(i,j){
                    	if(i == 'captch_response' && grecaptcha){ grecaptcha.reset(); }
                    	if(i == 'username'){
                    		$(".login-error").html(j).show();
                    		$(".login-error").addClass('shake')
                    		setTimeout(function(){ $(".login-error").removeClass('shake') }, 500);
                    	} else{
	                        $ele = $container.find('[name="'+ i +'"]');
	                        if($ele){
	                            $ele.parents(".form-group").addClass("has-error");
	                            $ele.after("<span class='text-danger'>"+ j +"</span>");
	                        }
                    	}
                    })
                }

                if(json['redirect']){
                    window.location = json['redirect'];
                }
            },
        })
        return false;
    });
</script>