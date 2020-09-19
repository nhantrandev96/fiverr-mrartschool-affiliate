<?php include(APPPATH.'/views/usercontrol/login/multiple_pages/header.php'); ?>

<div class="container-fluid">
    <a href="<?= site_url('/') ?>" class="back-btn"><i class="fas fa-arrow-circle-left mr-2"></i>Back To Home</a>
    <div class="row h-100  register-container">
        <div class="col-lg-7 position-relative p-5  d-flex align-items-center order-lg-first order-last">
            <div class="swiper-container login-swiper">
                <div class="swiper-wrapper pb-5 pb-lg-0">
                    
                    <!-- Slide -->
                    <div class="swiper-slide pb-0 pb-lg-5">
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <img src="<?= base_url('assets/login/multiple_pages') ?>/img/1.png"/>
                            </div>
                            <div class="col-lg-12 content">
                                <div class="pl-lg-5">
                                    <h5>Lorem Ipsum</h5>
                                    <p class="text-secondary">
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                                    when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Slide -->
                    
                    <!-- Slide -->
                    <div class="swiper-slide pb-0 pb-lg-5">
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <img src="<?= base_url('assets/login/multiple_pages') ?>/img/2.png"/>
                            </div>
                            <div class="col-lg-12 content">
                                <div class="pl-lg-5">
                                    <h5>Register Lorem Ipsum</h5>
                                    <p class="text-secondary">
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                                    when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                                    </p>
                                    <p class="text-secondary">
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                                    when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Slide -->
                    
                </div>
                <!-- Add Pagination -->
                <div class="login-swiper-pagination swiper-pagination"></div>
                <!-- Add Pagination -->
                <div class="login-swiper-button-next swiper-button-next"></div>
                <div class="login-swiper-button-prev swiper-button-prev"></div>
             </div>
        </div>
        <div class="col-lg-5 bg-light">
            <div class="container p-5 d-flex align-items-center justify-content-center h-100">
                <div class="registration-form pt-5">
                    <div class="login-card pb-3">
                        <h3 class="text-center pb-2">Create New Account</h3>
    		            <?= $register_fomm ?>
		            </div>
        		   	<hr/>
        		   	<p class="text-center pt-3">Already have account? <a href="<?= site_url('/p/login') ?>" class="ml-1">Sign In</a></p>
                </div>
            </div>
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

	$(".reg_form").submit(function(e){
        e.preventDefault();
        $this = $(this);
        var is_valid = true;
        if(tel_input){
            var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
            is_valid = false;
            var errorInnerHTML = '';
            if ($("#phone").val().trim()) {
                if (tel_input.isValidNumber()) {
                    is_valid = true;
                    $("#phonenumber-input").val("+"+tel_input.selectedCountryData.dialCode + $("#phone").val().trim())
                } else {
                    var errorCode = tel_input.getValidationError();
                    errorInnerHTML = errorMap[errorCode];
                }
            } else { errorInnerHTML = 'The Mobile Number field is required.'; }

            $("#phone").parents(".form-group").removeClass("has-error");
            $(".reg_form .text-danger").remove();
            if(!is_valid){
                $("#phone").parents(".form-group").addClass("has-error");
                $("#phone").parents(".form-group").find('> div').after("<span class='text-danger'>"+ errorInnerHTML +"</span>");
            }
        }
        if(is_valid){
            var check_captch = true;
            if (grecaptcha === undefined) { check_captch = false; }
            $("#captch_response").val('')
            if(check_captch){
                captch_response = grecaptcha.getResponse();
                $("#captch_response").val(captch_response)
            }
            $.ajax({
                url:'<?= base_url("pagebuilder/register") ?>',
                type:'POST',
                dataType:'json',
                data:$this.serialize(),
                beforeSend:function(){ $this.find(".btn-submit").btn("loading"); },
                complete:function(){ $this.find(".btn-submit").btn("reset"); },
                success:function(json){
                    if(json['redirect']){ window.location.replace(json['redirect']); }
                    if(json['warning']){ alert(json['warning']) }

                    $this.find(".is-invalid").removeClass("is-invalid");
                    $this.find("span.invalid-feedback").remove();

                    if(json['errors']){
                        $.each(json['errors'], function(i,j){
                            if(i == 'captch_response' && grecaptcha){ grecaptcha.reset(); }
                            if(i == 'terms'){ $(".reg-agree-label").after("<span class='invalid-feedback'>"+ j +"</span>"); return true; }

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
                },
            })
        }
        return false;
    })
</script>
<?php include(APPPATH.'/views/usercontrol/login/multiple_pages/footer.php'); ?>
