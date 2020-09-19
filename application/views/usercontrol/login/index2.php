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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $title ?></title>
    <meta name="author" content="<?= $meta_author ?>">
    <meta name="keywords" content="<?= $meta_keywords ?>">
    <meta name="description" content="<?= $meta_description ?>">
    <link href="<?= base_url('assets/login/index2') ?>/css/bootstrap.min.css?v=<?= av() ?>" rel="stylesheet">
    <link href="<?= base_url('assets/login/index2') ?>/css/common.css?v=<?= av() ?>" rel="stylesheet">
    <link href="<?= base_url('assets/login/index2') ?>/css/theme-07.css?v=<?= av() ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700&display=swap" rel="stylesheet">
    <script src="<?= base_url('assets/login/index2') ?>/js/jquery.min.js"></script>
    <script src="<?= base_url('assets/login/index1') ?>/js/popper.min.js"></script>
    <script src="<?= base_url('assets/login/index2') ?>/js/bootstrap.min.js"></script>

    <?php if (is_rtl()) { ?>
      <!-- place here your RTL css code -->
    <?php } ?>
</head>
<body>
    <div class="forny-container">
        <div class="forny-inner">
            <div class="forny-two-pane">
                <div>
                    <div class="forny-form">
                        <?php if($store['language_status']){ ?>
                        <div class="language-changer"> <?= $LanguageHtml ?></div>
                        <?php } ?>
                        <div class="mb-8 forny-logo">
                            <a href="<?= base_url() ?>">
                                <img src="<?= resize($logo,100,80) ?>">
                            </a>
                        </div>
                        <div class="row">
                            <div class="col-12 text-center"><?= $title ?></div>
                        </div>
                        <br>
                        <div class="login-reg-form">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active bg-transparent" href="#login" data-toggle="tab" role="tab">
                                        <span><?= __('user.login') ?></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link bg-transparent" href="#register" data-toggle="tab" role="tab">
                                        <span><?= __('user.register') ?></span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" role="tabpanel" id="login">
                                    <p class="mt-6 mb-6">
                                        <?= __('user.Use_your_credentials_to_login_into_account') ?>
                                    </p>
                                    <form id="login-form">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="16" viewBox="0 0 24 16">
                                                            <g transform="translate(0)">
                                                                <path d="M23.983,101.792a1.3,1.3,0,0,0-1.229-1.347h0l-21.525.032a1.169,1.169,0,0,0-.869.4,1.41,1.41,0,0,0-.359.954L.017,115.1a1.408,1.408,0,0,0,.361.953,1.169,1.169,0,0,0,.868.394h0l21.525-.032A1.3,1.3,0,0,0,24,115.062Zm-2.58,0L12,108.967,2.58,101.824Zm-5.427,8.525,5.577,4.745-19.124.029,5.611-4.774a.719.719,0,0,0,.109-.946.579.579,0,0,0-.862-.12L1.245,114.4,1.23,102.44l10.422,7.9a.57.57,0,0,0,.7,0l10.4-7.934.016,11.986-6.04-5.139a.579.579,0,0,0-.862.12A.719.719,0,0,0,15.977,110.321Z" transform="translate(0 -100.445)"/>
                                                            </g>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <input class="form-control" name="username" placeholder="<?= __('user.username') ?>">
                                            </div>
                                        </div>
                                        <div class="form-group password-field">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="24" viewBox="0 0 16 24">
                                                            <g transform="translate(0)">
                                                                <g transform="translate(5.457 12.224)">
                                                                    <path d="M207.734,276.673a2.543,2.543,0,0,0-1.749,4.389v2.3a1.749,1.749,0,1,0,3.5,0v-2.3a2.543,2.543,0,0,0-1.749-4.389Zm.9,3.5a1.212,1.212,0,0,0-.382.877v2.31a.518.518,0,1,1-1.035,0v-2.31a1.212,1.212,0,0,0-.382-.877,1.3,1.3,0,0,1-.412-.955,1.311,1.311,0,1,1,2.622,0A1.3,1.3,0,0,1,208.633,280.17Z" transform="translate(-205.191 -276.673)"/>
                                                                </g>
                                                                <path d="M84.521,9.838H82.933V5.253a4.841,4.841,0,1,0-9.646,0V9.838H71.7a1.666,1.666,0,0,0-1.589,1.73v10.7A1.666,1.666,0,0,0,71.7,24H84.521a1.666,1.666,0,0,0,1.589-1.73v-10.7A1.666,1.666,0,0,0,84.521,9.838ZM74.346,5.253a3.778,3.778,0,1,1,7.528,0V9.838H74.346ZM85.051,22.27h0a.555.555,0,0,1-.53.577H71.7a.555.555,0,0,1-.53-.577v-10.7a.555.555,0,0,1,.53-.577H84.521a.555.555,0,0,1,.53.577Z" transform="translate(-70.11)"/>
                                                            </g>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <input required  class="form-control" name="password" type="password" name="password" placeholder="*************">
                                                <div class="input-group-append cursor-pointer">
                                                    <span class="input-group-text">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="16" viewBox="0 0 24 16">
                                                            <g transform="translate(0 0)">
                                                                <g transform="translate(0 0)">
                                                                    <path d="M23.609,117.568a15.656,15.656,0,0,0-5.045-4.859,12.823,12.823,0,0,0-6.38-1.811c-.062,0-.309,0-.371,0a12.823,12.823,0,0,0-6.38,1.811,15.656,15.656,0,0,0-5.045,4.859,2.464,2.464,0,0,0,0,2.658,15.656,15.656,0,0,0,5.045,4.859,12.822,12.822,0,0,0,6.38,1.811c.062,0,.309,0,.371,0a12.823,12.823,0,0,0,6.38-1.811,15.656,15.656,0,0,0,5.045-4.859A2.464,2.464,0,0,0,23.609,117.568Zm-17.74,6.489a14.622,14.622,0,0,1-4.712-4.538,1.155,1.155,0,0,1,0-1.245,14.621,14.621,0,0,1,4.712-4.538,12.747,12.747,0,0,1,1.586-.79,8.964,8.964,0,0,0,0,11.9A12.748,12.748,0,0,1,5.869,124.057ZM12,125.75c-3.213,0-5.827-3.074-5.827-6.853s2.614-6.853,5.827-6.853,5.827,3.074,5.827,6.853S15.211,125.75,12,125.75Zm10.841-6.23a14.621,14.621,0,0,1-4.712,4.538,12.737,12.737,0,0,1-1.585.788,8.964,8.964,0,0,0,0-11.9,12.74,12.74,0,0,1,1.587.791,14.622,14.622,0,0,1,4.712,4.538A1.155,1.155,0,0,1,22.839,119.52Z" transform="translate(0.002 -110.897)"/>
                                                                </g>
                                                                <g transform="translate(9.565 5.565)">
                                                                    <path d="M205.24,202.8a2.435,2.435,0,1,0,2.435,2.435A2.438,2.438,0,0,0,205.24,202.8Zm0,3.917a1.482,1.482,0,1,1,1.482-1.482A1.483,1.483,0,0,1,205.24,206.721Z" transform="translate(-202.805 -202.804)"/>
                                                                </g>
                                                            </g>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <?php if (isset($googlerecaptcha['affiliate_login']) && $googlerecaptcha['affiliate_login']) { ?>
                                            <div class="captch mb-3">
                                                <script src='https://www.google.com/recaptcha/api.js'></script>
                                                <div class="g-recaptcha" data-sitekey="<?= $googlerecaptcha['sitekey'] ?>"></div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="row mt-10">
                                            <div class="col-6">
                                                <button class="btn btn-primary btn-submit btn-block"><?= __('user.login') ?></button>
                                            </div>
                                            <div class="col-6 d-flex align-items-center justify-content-end">
                                                <a onclick="show_forget();return false;" href="<?= base_url('forget-password') ?>"><?= __('user.forget_password') ?>?</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" role="tabpanel" id="register">
                                    <p class="mt-6 mb-6">
                                        <?= __('user.enter_your_information_to_setup_a_new_account') ?>
                                    </p>
                                    <?= $register_fomm ?>
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
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="16" viewBox="0 0 24 16">
                                                        <g transform="translate(0)">
                                                        <path d="M23.983,101.792a1.3,1.3,0,0,0-1.229-1.347h0l-21.525.032a1.169,1.169,0,0,0-.869.4,1.41,1.41,0,0,0-.359.954L.017,115.1a1.408,1.408,0,0,0,.361.953,1.169,1.169,0,0,0,.868.394h0l21.525-.032A1.3,1.3,0,0,0,24,115.062Zm-2.58,0L12,108.967,2.58,101.824Zm-5.427,8.525,5.577,4.745-19.124.029,5.611-4.774a.719.719,0,0,0,.109-.946.579.579,0,0,0-.862-.12L1.245,114.4,1.23,102.44l10.422,7.9a.57.57,0,0,0,.7,0l10.4-7.934.016,11.986-6.04-5.139a.579.579,0,0,0-.862.12A.719.719,0,0,0,15.977,110.321Z" transform="translate(0 -100.445)"></path>
                                                    </g>
                                                </svg>
                                            </span>
                                        </div>
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
            <div></div>
        </div>
    </div>
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
                                $formGroup.find(".input-group").after("<span class='bg-white d-block text-danger'>"+ j +"</span>");
                            } else {
                                $ele.after("<span class='text-danger'>"+ j +"</span>");
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
                            if(i == 'terms'){ $(".reg-agree-label").after("<span class='text-danger'>"+ j +"</span>"); return true; }
                            $ele = $this.find('[name="'+ i +'"]');
                            if($ele){
                                $formGroup = $ele.parents(".form-group");
                                $formGroup.addClass("has-error");
                                if($formGroup.find(".input-group").length){
                                    $formGroup.find(".input-group").after("<span class='bg-white d-block text-danger'>"+ j +"</span>");
                                } else {
                                    $ele.after("<span class='text-danger'>"+ j +"</span>");
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
                    $this.find(".btn-submit").before("<div class='alert success-msg alert-success'> " + json['success'] + "</div>");
                }
                if(json['errors']){
                    $.each(json['errors'], function(i,j){
                        if(i == 'captch_response' && grecaptcha){ grecaptcha.reset(); }
                        $ele = $this.find('[name="'+ i +'"]');
                        if($ele){
                            $formGroup = $ele.parents(".form-group");
                            $formGroup.addClass("has-error");
                            if($formGroup.find(".input-group").length){
                                $formGroup.find(".input-group").after("<span class='bg-white d-block text-danger'>"+ j +"</span>");
                            } else {
                                $ele.after("<span class='text-danger'>"+ j +"</span>");
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