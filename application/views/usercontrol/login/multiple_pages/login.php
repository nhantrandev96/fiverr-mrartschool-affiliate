<?php include(APPPATH.'/views/usercontrol/login/multiple_pages/header.php'); ?>

<div class="container-fluid login-container">
    <a href="<?= site_url('/') ?>" class="back-btn text-primary"><i class="fas fa-arrow-circle-left mr-2"></i>Back To Home</a>
    <div class="row h-100">
        <div class="col-lg-6">
            <div class="container p-5 d-flex align-items-center justify-content-center h-100">
                <div>
                    <div class="login-card pb-3">
                        <h3 class="text-center pb-2">Login</h3>
    		            <form id="login-form">
    		                <div class="form-group">
    		                	<label class="form-control-label">Username</label>
    		                    <input class="form-control" name="username" placeholder="<?= __('user.username') ?>">
    		                </div>
    		                <div class="form-group password-field">
    		                	<label class="form-control-label">Password</label>
    		                    <input required  class="form-control" name="password" type="password" name="password" placeholder="*************">
    		                    <a href="<?= base_url('/p/forget-password') ?>" class="text-right d-block mt-2"><?= __('user.forget_password') ?>?</a>
    		                </div>
    		                <div>
    		                    <?php if (isset($googlerecaptcha['affiliate_login']) && $googlerecaptcha['affiliate_login']) { ?>
    		                    <div class="captch mb-3">
    		                        <script src='https://www.google.com/recaptcha/api.js'></script>
    		                        <div class="g-recaptcha" data-sitekey="<?= $googlerecaptcha['sitekey'] ?>"></div>
    		                    </div>
    		                    <?php } ?>
    		                </div>
    		                <div class="row">
    		                    <div class="col-12">
    		                        <button class="btn btn-primary btn-submit d-block w-100"><?= __('user.login') ?></button>
    		                    </div>
    		                </div>
    		            </form>
		            </div>
        		   	<hr/>
        		   	<p class="text-center pt-3">Don't have account yet? <a href="<?= site_url('/p/register') ?>" class="ml-1">Sign Up</a></p>
                </div>
            </div>
        </div>
        <div class="col-lg-6 position-relative">
            <img src="<?= base_url('assets/login/multiple_pages') ?>/img/bg-photo.jpg" class="login-bg"/>
        </div>
    </div>
</div>

<script type="text/javascript">
	(function ($) {
        $.fn.btn = function (action) {
            var self = $(this);
            if (action == 'loading') { $(self).addClass("btn-loading"); }
            if (action == 'reset') { $(self).removeClass("btn-loading"); }
        }
    })(jQuery);
    var grecaptcha = undefined;
    $('#login-form').on('submit',function(){
        $this = $(this);
        $.ajax({
            url:'<?= base_url('auth/login') ?>',
            type:'POST',
            dataType:'json',
            data: $this.serialize() + '&type=user',
            beforeSend:function(){ $this.find(".btn-submit").btn("loading"); },
            complete:function(){ $this.find(".btn-submit").btn("reset"); },
            success:function(json){
                $this.find(".is-invalid").removeClass("is-invalid");
                $this.find(".has-error").removeClass("has-error");
                $this.find("span.invalid-feedback").remove();

                if(json['errors']){
                    $.each(json['errors'], function(i,j){
                        if(i == 'captch_response' && grecaptcha){ grecaptcha.reset(); }
                        $ele = $this.find('[name="'+ i +'"]');
                        if($ele){
                            $formGroup = $ele.parents(".form-group");
                            $ele.addClass("is-invalid");
                            if($formGroup.find(".input-group").length){
                                $formGroup.find(".input-group").after("<span class='invalid-feedback'>"+ j +"</span>");
                            } else {
                                $ele.after("<span class='invalid-feedback'>"+ j +"</span>");
                            }
                        }
                    })
                }
                if(json['redirect']){ window.location = json['redirect']; }
            },
        })
        return false;
    });
</script>
<?php include(APPPATH.'/views/usercontrol/login/multiple_pages/footer.php'); ?>
