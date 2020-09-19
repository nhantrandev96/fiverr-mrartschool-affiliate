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

    
    <link href="<?= base_url('assets/login/index3') ?>/css/bootstrap.min.css?v=<?= av() ?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/index3') ?>/css/flags.css?v=<?= av() ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/login/index3') ?>/css/style.css?v=<?= av() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/login/index3') ?>/css/toastr.min.css?v=<?= av() ?>">

    <script src="<?= base_url('assets/login/index2') ?>/js/jquery.min.js"></script>
    <script src="<?= base_url('assets/login/index1') ?>/js/popper.min.js"></script>
    <script src="<?= base_url('assets/login/index3') ?>/js/bootstrap.min.js"></script>
    <script src="<?= base_url('assets/login/index3') ?>/js/jquery.dd.min.js"></script>
    <script src="<?= base_url('assets/login/index3') ?>/js/toastr.min.js"></script>

    <?php if (is_rtl()) { ?>
      <!-- place here your RTL css code -->
    <?php } ?>
</head>

<body>
    <div class="login-box">
        <?php if($store['language_status']){ ?>
            <div class="language-changer"> <?= $LanguageHtml ?></div>
        <?php } ?>

        <div class="left-side">
            <div class="outer" id="outer">

                <div class="boxx" id="login" style="display: block;">
                    <form method="POST" action="" id="login-form">
                        <div class="logo">
                            <img src="<?= resize($logo,100,80) ?>" alt="#">
                        </div>
                        <div class="row">
                            <div class="col-12 text-center"><?= $title ?></div>
                        </div>
                        <br>
                        <div class="input-box">
                            <img class="icon" src="<?= base_url('assets/login/index3') ?>/image/user.png" alt="#">
                            <input type="text" class="box" name="username" placeholder="<?= __('user.username') ?>"><br>
                        </div>

                        <div class="input-box">
                            <img class="icon" src="<?= base_url('assets/login/index3') ?>/image/padlock.png" alt="#">
                            <input type="password" class="box" name="password" id="loginpassowrd" value="" placeholder="Password">
                        </div>  

                        <div>
                            <?php if (isset($googlerecaptcha['affiliate_login']) && $googlerecaptcha['affiliate_login']) { ?>
                                <div class="captch  mb-3">
                                    <script src='https://www.google.com/recaptcha/api.js'></script>
                                    <div class="g-recaptcha" data-sitekey="<?= $googlerecaptcha['sitekey'] ?>"></div>
                                </div>
                            <?php } ?>
                        </div>                      

                        <button class="button btn-submit" type="submit" ><?= __('user.login') ?></button>
                        <p class="text forget-text">
                            <a onclick="forgot();return false;" href="<?= base_url('forget-password') ?>"><?= __('user.forget_password') ?>?</a>
                        </p>
                        <p class="text"><?= __('user.dont_have_an_account') ?><a href="#" onclick="register()"><?= __('user.register') ?></a> </p>
                    </form>
                </div>
                <div class="boxx" id="fog-pass" style="display: none;">
                    <div class="logo">
                        <img src="<?= resize($logo,100,80) ?>" alt="#">
                    </div>
                    <form method="POST" action="" class="reset-password-form">
                        <input class="box " name="email" placeholder="<?= __('user.email') ?>" type="email">

                        <button class="button sign-in" type="button" onclick="login()"><?= __('user.login') ?></button>
                        <button class="button btn-submit submit" type="submit" ><?= __('user.submit') ?></button>
                    </form>
                </div>

                <div class="boxx" id="register" style="display: none;">
                    <div class="logo">
                        <img src="<?= resize($logo,100,80) ?>" alt="#">
                    </div>
                    <?= $register_fomm ?>
                    <br><br>
                </div>
                <div class="boxx" id="treams" style="display: none;">                    
                    <div class="logo">
                        <img src="<?= resize($logo,100,80) ?>" alt="#">
                    </div>
                    <?= $tnc['content'] ?>
                    <button class="button sign-in" type="button" onclick="register()"><?= __('user.register') ?></button>
                </div>
            </div>
        </div>
        <div class="right-side">
            <img src="<?= base_url('assets/login/index3') ?>/image/group.png" alt="#">
            <div class="affiliate-description">
            </div>
        </div>

    </div>
</body>

</html>

<!-- Theme Customise -->
<script type="text/javascript">

    function register() {
        document.getElementById('register').style = "height:100%;";
        document.getElementById('fog-pass').style = "display:none;";
        document.getElementById('login').style = "display:none";
        document.getElementById('treams').style = "display:none;";
    }

    function login() {
        document.getElementById('register').style = "display:none;";
        document.getElementById('fog-pass').style = "display:none;";
        document.getElementById('login').style = "display:block;";
        document.getElementById('treams').style = "display:none;";
    }

    function forgot() {
        document.getElementById('register').style = "display:none;";
        document.getElementById('login').style = "display:none;";
        document.getElementById('fog-pass').style = "display:block;";
        document.getElementById('treams').style = "display:none;";
    }

    function treams() {
        document.getElementById('register').style = "display:none;";
        document.getElementById('login').style = "display:none;";
        document.getElementById('fog-pass').style = "display:none;";
        document.getElementById('treams').style = "display:block;";
    }

    $(".register-form button.change-view").click(login)
    function show_forget(){
        $(".forget-forms").show();
        $(".login-reg-form,.tearms-page").hide();
        return false;
    }

    function show_login(){
        $(".forget-forms,.tearms-page").hide();
        $(".login-reg-form").show();
        $('[href="#login"]').click()
        return false;
    }

    $(".reg-agree-label a").click(function(e){
        treams();
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
                            $formGroup.find(".input-group").after("<span class='bg-white text-left d-block text-danger'>"+ j +"</span>");
                        } else {
                            $ele.after("<span class='bg-white text-left d-block text-danger'>"+ j +"</span>");
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
                            $formGroup.find(".input-group").after("<span class='bg-white d-block text-danger'>"+ j +"</span>");
                        } else {
                            $ele.after("<span class='bg-white d-block text-danger'>"+ j +"</span>");
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