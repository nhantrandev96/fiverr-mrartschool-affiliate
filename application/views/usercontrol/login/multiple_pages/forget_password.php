<?php include(APPPATH.'/views/usercontrol/login/multiple_pages/header.php'); ?>

<div class="container-fluid login-container">
    <a href="<?= site_url('/p/login') ?>" class="back-btn text-primary"><i class="fas fa-arrow-circle-left mr-2"></i>Back To Login</a>
    <div class="row h-100">
        <div class="col-lg-6">
            <div class="container p-5 d-flex align-items-center justify-content-center h-100">
                <div>
                    <div class="login-card pb-3">
                        <h3 class="text-center pb-2">Forgot Password</h3>
    		             <form class="reset-password-form">
                            <div class="form-group">
                                <label class="form-control-label">Email Address</label>
                                <input class="form-control" name="email" placeholder="<?= __('user.email') ?>" type="email">
                                <small class="text-muted"><?= __('user.Please_enter_your_email_address_and_we_will_send_you_a_password_password_link') ?></small>
                            </div>
                            <button class="btn btn-submit btn-primary btn-block"><?= __('user.send_reset_link') ?></button>
                        </form>
		            </div>
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
    
    $('.reset-password-form').on('submit',function(){
        $this = $(this);
        $.ajax({
            url:'<?= base_url('auth/forget') ?>',
            type:'POST',
            dataType:'json',
            data: $this.serialize(),
            beforeSend:function(){ $this.find(".btn-submit").btn("loading"); },
            complete:function(){ $this.find(".btn-submit").btn("reset"); },
            success:function(json){
                $this.find(".is-invalid").removeClass("is-invalid");
                $this.find("span.invalid-feedback,.success-msg").remove();

                if(json['success']){
                    $this.find(".btn-submit").before("<div class='alert success-msg alert-success'> " + json['success'] + "</div>");
                }
                if(json['errors']){
                    $.each(json['errors'], function(i,j){
                        if(i == 'captch_response' && grecaptcha){ grecaptcha.reset(); }
                        $ele = $this.find('[name="'+ i +'"]');
                        if($ele){
                            $formGroup = $ele.parents(".form-group");
                            $ele.addClass("is-invalid");
                            if($formGroup.find(".input-group").length){
                                $formGroup.find(".input-group").after("<span class='bg-white d-block invalid-feedback'>"+ j +"</span>");
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
