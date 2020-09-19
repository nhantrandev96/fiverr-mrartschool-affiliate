
<script type="text/javascript">
	var grecaptcha = undefined;
</script>
<?php 
	$db =& get_instance(); 
    $googlerecaptcha =$db->Product_model->getSettings('googlerecaptcha');
?>

<form class="signin-form" >
	<legend class="signin-form-title"><?= __('user.sign_in') ?></legend>
	
	<input type="hidden" name="type" value="user">
    <div class="form-group">
		<label for="Username"><?= __('user.username') ?></label>
		<input class=" input100 form-control" id="Username" type="text" name="username" placeholder="<?= __('user.username') ?>">
	</div>
	<div class="form-group">
		<label for="password"><?= __('user.password') ?></label>
		<input type="password" name="password" placeholder="*************" class="form-control input100">
	</div>

	<?php if (isset($googlerecaptcha['affiliate_login']) && $googlerecaptcha['affiliate_login']) { ?>
		<div class="captch">
			<script src='https://www.google.com/recaptcha/api.js'></script>
			<div class="g-recaptcha" data-sitekey="<?= $googlerecaptcha['sitekey'] ?>"></div>
		</div>
	<?php } ?>

	<div class="login-error"></div>
	<button type="submit" class="btn btn-primary btn-submit"><?= __('user.sign_in') ?></button>
	<a type="button" class="btn -btn-primary pull-right btn-goto-forget"><?= __('user.forget_password') ?></a>
</form>
<form class="forget-form" >
	<legend class="forget-form-title"><?= __('user.forget_password') ?></legend>
	
	<input type="hidden" name="type" value="user">
    <div class="form-group">
		<label for="email">Enter Your Email Address</label>
		<input class=" input100 form-control" id="email" type="text" name="email" placeholder="<?= __('user.email') ?>">
	</div>

	<div class="login-error"></div>
	<div class="success-msg"></div>
	<button type="submit" class="btn btn-primary btn-submit"><?= __('user.send_mail') ?></button>
	<a type="button" class="btn -btn-primary pull-right btn-goto-signin"><?= __('user.back_login') ?></a>
</form>

<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/login/style.css') ?>">
<script type="text/javascript">
	$(".btn-goto-signin").on('click',function(){
		$(".signin-form").show();
		$(".forget-form").hide();
	})
	$(".btn-goto-forget").on('click',function(){
		$(".signin-form").hide();
		$(".forget-form").show();
	})

	$('.forget-form').on('submit',function(){

		

        $.ajax({
            url:'<?= base_url('auth') ?>/forget',
            type:'POST',
            dataType:'json',
            data: $('.forget-form').serialize(),
            beforeSend:function(){ $('.forget-form button').prop("disabled",true); },
            complete:function(){ $('.forget-form button').prop("disabled",false); },
            success:function(json){
            	$(".success-msg").html('');
                $container = $('.forget-form');
                $container.find(".has-error").removeClass("has-error");
                $container.find("span.text-danger").remove();
                
                if(json['success']){
                	$(".success-msg").html("<div class='alert alert-success'> " + json['success'] + "</div>");
                }

                if(json['errors']){
                    $.each(json['errors'], function(i,j){
                    	
                    	if(i == 'email'){
                    		$(".forget-form .login-error").html(j).show();
                    		$(".forget-form .login-error").addClass('shake')
                    		setTimeout(function(){ $(".forget-form .login-error").removeClass('shake') }, 500);
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

	$('.signin-form').on('submit',function(){
      $.ajax({
          url:action_url+'/login',
          type:'POST',
          dataType:'json',
          data: $('.signin-form').serialize(),
          beforeSend:function(){ $('.signin-form .btn-submit').btn("loading"); },
          complete:function(){ $('.signin-form .btn-submit').btn("reset"); },
          success:function(json){
           
              $container = $(".signin-form");
              $container.find(".has-error").removeClass("has-error");
              $container.find("span.text-danger").remove();
              
              if(json['errors']){
                    $.each(json['errors'], function(i,j){
                    	if(i == 'captch_response' && grecaptcha){ grecaptcha.reset(); }
                    	if(i == 'username'){
                    		$(".signin-form .login-error").html(j).show();
                    		$(".signin-form .login-error").addClass('shake')
                    		setTimeout(function(){ $(".signin-form .login-error").removeClass('shake') }, 500);
                    	} else{
	                        $ele = $container.find('[name="'+ i +'"]');
	                        if($ele){
	                            $ele.parents(".form-group").addClass("has-error");
	                            $ele.after("<span class='text-danger'>"+ j +"</span>");
	                        }
                    	}
                    })
                }
              if (json['success']) {
                  showMessage($(".signin-form-title"),json['success'],'success');
              }
              if(json['redirect']){
                  window.location = json['redirect'];
              }
          },
      })
  

      return false;
  });
</script>