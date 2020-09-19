<!doctype html>
<html>

<head>
	<?php 
        if($SiteSetting['google_analytics']){ echo $SiteSetting['google_analytics']; }
        if($SiteSetting['faceboook_pixel']){ echo $SiteSetting['faceboook_pixel']; }
        $logo = ($SiteSetting['logo'] ? 'assets/images/site/'.$SiteSetting['logo'] : 'assets/vertical/assets/images/users/avatar-1.jpg');

        if($SiteSetting['favicon']){
            echo '<link rel="icon" href="'. base_url('assets/images/site/'.$SiteSetting['favicon']) .'" type="image/*" sizes="16x16">';
        }

        $global_script_status = (array)json_decode($SiteSetting['global_script_status'],1);
        if(in_array('front', $global_script_status)){ echo $SiteSetting['global_script']; }

        $db =& get_instance(); 
        $products = $db->Product_model; 
        $googlerecaptcha =$db->Product_model->getSettings('googlerecaptcha');
        $tnc =$db->Product_model->getSettings('tnc');
    ?>
    <title><?= $title ?></title>
    <meta name="author" content="<?= $meta_author ?>">
    <meta name="keywords" content="<?= $meta_keywords ?>">
    <meta name="description" content="<?= $meta_description ?>">

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="<?= base_url('assets/login/index6') ?>/css/bootstrap.min.css?v=<?= av() ?>" rel="stylesheet">
    <link href="<?= base_url('assets/login/index6') ?>/css/common.css?v=<?= av() ?>" rel="stylesheet">
    <link href="<?= base_url('assets/login/index6') ?>/css/custom.css?v=<?= av() ?>" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/login/index6') ?>/css/theme-06.css?v=<?= av() ?>" rel="stylesheet">

    <script src="<?= base_url('assets/login/index6') ?>/js/jquery.min.js"></script>
    <script src="<?= base_url('assets/login/index1') ?>/js/popper.min.js"></script>
    <script src="<?= base_url('assets/login/index6') ?>/js/bootstrap.min.js"></script>
    <script src="<?= base_url('assets/login/index6') ?>/js/main.js"></script>
    <script src="<?= base_url('assets/login/index6') ?>/js/demo.js"></script>

    <?php if (is_rtl()) { ?>
      <!-- place here your RTL css code -->
    <?php } ?>
</head>


<body>
	<?php if($store['language_status']){ ?>
        <div class="language-changer"> <?= $LanguageHtml ?></div>
    <?php } ?>

    <div class="forny-container">
        <div class="forny-inner">
            <div class="forny-two-pane">
                <div class="forny-form-wrapper">
                    <div class="container py-3">
                        <div class="login-card-wrapper">
                            <div class="row">
                                <div class="affiliate-description col-lg-6">
                                    <div class="image-container">
                                        <div class="w-100 paragraph-container">
                                            <div class="forny-logo">
                                                <img src="<?= resize($logo,50,50) ?>">
                                            </div>
                                            <br>
                                            <h3><?= $setting['heading'] ?></h3>
                                            <br>
                                            <?= $setting['content'] ?>
                                            <br>
                                        </div>
                                        <img  src="assets/login/index6/img/cog.png" class="cog"/>
                                    </div>
                                </div>
                                <div class="forny-form col-lg-6">
                                	<div class="login-reg-form">
            	                        <div class="tab-content">
            	                            <div class="tab-pane fade show active mt-5" role="tabpanel" id="login">
            	                                <h1 class="card-title">Login</h1>
            	                                <p class="subtitle">Use your credentials to login into account</p>
            	                                <form id="login-form">
            	                                    <div class="form-group">
            	                                        <input required  class="form-control" name="username" placeholder="<?= __('user.username') ?>">
            	                                    </div>
            	                                    <div class="form-group">
            	                                        <input required  class="form-control" type="password" name="password" id="loginpassowrd" value="" placeholder="Password">
            	                                    </div>
            	                                     <div class="d-block text-right my-3">
                                                        <a onclick="show_forget();return false;" href="<?= base_url('forget-password') ?>"><?= __('user.forget_password') ?>?</a>
                                                    </div>
            	                                    <div>
            				                            <?php if (isset($googlerecaptcha['affiliate_login']) && $googlerecaptcha['affiliate_login']) { ?>
            				                                <div class="captch mb-3">
            				                                    <script src='https://www.google.com/recaptcha/api.js'></script>
            				                                    <div class="g-recaptcha" data-sitekey="<?= $googlerecaptcha['sitekey'] ?>"></div>
            				                                </div>
            				                            <?php } ?>
            				                        </div> 
            				                        <button class="btn btn-primary btn-submit btn-block"><?= __('user.login') ?></button>
            	                                </form>
            	                                <a href="#register" class="toggle-sign-up btn btn-light btn-block mt-2">Create New Account</a>
            	                            </div>
            
            	                            <div class="tab-pane fade" role="tabpanel" id="register">
            	                                <h1 class="card-title">Register</h1>
            	                                <p class="subtitle">Enter your information to setup a new account</p>
            	                                <?= $register_fomm ?>
            	                                <a href="#login" class="toggle-sign-in btn btn-light btn-block mt-2">Back to Login</a>
            	                               </div>
            	                        </div>
            	                    </div>
            
            	                    <div class="forget-forms">
            		                    <div class="reset-form d-block">
            		                        <form class="reset-password-form">
            		                            <h4 class="mb-5"><?= __('user.reset_password') ?></h4>
            		                            <p class="mb-10">
            		                                <?= __('user.Please_enter_your_email_address_and_we_will_send_you_a_password_password_link') ?>
            		                            </p>
            		                            <div class="form-group">
            		                                <div class="input-group">
            		                                    <input class="form-control" name="email" placeholder="<?= __('user.email') ?>" type="email">
            		                                </div>
            		                            </div> 
            		                            <div class="row">
            		                                <div class="col-md-12">
            		                                    <button class="btn btn-submit btn-primary btn-block"><?= __('user.send_reset_link') ?></button>
            		                                    <button type="button" class="btn btn-block" onclick="show_login()"><?= __('user.back_to_login') ?></button>
            		                                </div>
            		                            </div>
            		                        </form>
            		                    </div>
            		                </div>
            		                <div class="tearms-page">
            		                    <h3 class="mb-10"><?= $tnc['heading'] ?></h3>
            		                    <?= $tnc['content'] ?>
            		                    <div class="text-center"><a href="javascript:void()" onclick="show_login()"><?= __('user.back_to_register') ?></a></div>    
            		                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="background-image"></div>
    </div>

</body>
</html>

<!-- Theme Customise -->
<script type="text/javascript">

    function show_forget(){
        $(".forget-forms").show();
        $(".login-reg-form,.tearms-page").hide();
        return false;
    }

    function show_login(){
        $(".forget-forms,.tearms-page").hide();
        $(".login-reg-form").show();
        //$('[href="#login"]').click()
        return false;
    }

    function show_terms(){
        $(".forget-forms,.login-reg-form").hide();
        $(".tearms-page").show();
        return false;
    }
   
    $(".reg-agree-label a").click(function(e){
        show_terms();
        return false
    });
    
    $('.toggle-sign-up').on('click',function(){
        $('#register').addClass('show active'); 
        $('#login').removeClass('show active');
    });
    
    $('.toggle-sign-in').on('click',function(){
        $('#login').toggleClass('show active');
        $('#register').removeClass('show active'); 
    });
</script>

<!-- START JS API Scripts -->
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
                $this.find(".is-valid").removeClass("is-valid");
                $this.find(".has-error").removeClass("has-error");
                $this.find("span.text-danger").remove();
                
                if(json['errors']){
                    $.each(json['errors'], function(i,j){
                        if(i == 'captch_response' && grecaptcha){ grecaptcha.reset(); }

                        $ele = $this.find('[name="'+ i +'"]');
                        if($ele){
                            $formGroup = $ele.parents(".form-group");
                            $formGroup.addClass("has-error");

                            if($formGroup.find(".input-group").length){
                                $formGroup.find(".input-group").after("<span class='error-text'>"+ j +"</span>");
                            } else {
                                $ele.after("<span class='error-text'>"+ j +"</span>");
                            }
                        }
                    })
                }

                if(json['redirect']){ window.location = json['redirect']; }
            },
        })
        return false;
    });

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

                    $this.find(".has-error").removeClass("has-error");
                    $this.find("span.text-danger").remove();

                    if(json['errors']){
                        $.each(json['errors'], function(i,j){
                            if(i == 'captch_response' && grecaptcha){ grecaptcha.reset(); }
                            if(i == 'terms'){ $(".reg-agree-label").after("<span class='error-text'>"+ j +"</span>"); return true; }

                            $ele = $this.find('[name="'+ i +'"]');
                            if($ele){
                                $formGroup = $ele.parents(".form-group");
                                $formGroup.addClass("has-error");

                                if($formGroup.find(".input-group").length){
                                    $formGroup.find(".input-group").after("<span class='error-text'>"+ j +"</span>");
                                } else {
                                    $ele.after("<span class='error-text'>"+ j +"</span>");
                                }
                            }
                        })
                    }
                },
            })
        }
        return false;
    }) 

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
                $this.find(".has-error").removeClass("has-error");
                $this.find("span.text-danger,.success-msg").remove();
                
                if(json['success']){
                    $this.find('[name="email"]').after("<div class='alert success-msg alert-success'> " + json['success'] + "</div>");
                }

                if(json['errors']){
                    $.each(json['errors'], function(i,j){
                        if(i == 'captch_response' && grecaptcha){ grecaptcha.reset(); }

                        $ele = $this.find('[name="'+ i +'"]');
                        if($ele){
                            $formGroup = $ele.parents(".form-group");
                            $formGroup.addClass("has-error");

                            if($formGroup.find(".input-group").length){
                                $formGroup.find(".input-group").after("<span class='error-text'>"+ j +"</span>");
                            } else {
                                $ele.after("<span class='error-text'>"+ j +"</span>");
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
<!-- END JS API Scripts -->