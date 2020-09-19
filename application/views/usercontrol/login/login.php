<?php if($design) { echo str_replace('contenteditable', '', $design); }else{
?>
<?php
$db =& get_instance();
$products = $db->Product_model;

$LanguageHtml = $products->getLanguageHtml('usercontrol');
?>
<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?= $title ?></title>
    <meta name="author" content="<?= $meta_author ?>">
    <meta name="keywords" content="<?= $meta_keywords ?>">
    <meta name="description" content="<?= $meta_description ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if($SiteSetting['favicon']){ ?>
    <link rel="icon" href="<?php echo base_url('assets/images/site/'.$SiteSetting['favicon']) ?>" type="image/*" sizes="16x16">
    <?php } ?>
    <?php
    $csss = array(
    'bg_left'                  => array('type' => 'background', 'selectotr' => '.login100-more::before'),
    'bg_right'                 => array('type' => 'background', 'selectotr' => '.wrap-login100'),
    'footer_bf'                => array('type' => 'background', 'selectotr' => '.footer'),
    'footer_color'             => array('type' => 'color', 'selectotr' => '.footer'),
    'btn_sendmail_bg'          => array('type' => 'background', 'selectotr' => '.btn_sendmail_bg'),
    'btn_sendmail_color'       => array('type' => 'color', 'selectotr' => ".btn_sendmail_bg"),
    'btn_backlogin_bg'         => array('type' => 'background', 'selectotr' => "[data-type='login']"),
    'btn_backlogin_color'      => array('type' => 'color', 'selectotr' => "[data-type='login']"),
    'btn_forgotlink_bg'        => array('type' => 'background', 'selectotr' => '[data-type="forget"]'),
    'btn_forgotlink_color'     => array('type' => 'color', 'selectotr' => '[data-type="forget"]'),
    'btn_signin_bg'            => array('type' => 'background', 'selectotr' => '.btn_signin_bg'),
    'btn_signin_color'         => array('type' => 'color', 'selectotr' => '.btn_signin_bg'),
    'btn_signup_bg'            => array('type' => 'background', 'selectotr' => '.btn_signup'),
    'btn_signup_color'         => array('type' => 'color', 'selectotr' => '.btn_signup'),
    'btn_registersubmit_bg'    => array('type' => 'background', 'selectotr' => '.reg_form button[type=submit]'),
    'btn_registersubmit_color' => array('type' => 'color', 'selectotr' => '.reg_form button[type=submit]'),
    'heading_color' => array('type' => 'color', 'selectotr' => '.affiliate-description h1'),
    'input_text_color' => array('type' => 'color', 'selectotr' => '.input100'),
    'input_bg_color' => array('type' => 'background', 'selectotr' => '.input100'),
    'input_label_color' => array('type' => 'color', 'selectotr' => '.label-input100'),
    );
    $db =& get_instance();
    $googlerecaptcha =$db->Product_model->getSettings('googlerecaptcha');
    ?>
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/login/css/') ?>/bootstrap.min.css?v=<?= av() ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/login/css/') ?>/font-awesome.min.css?v=<?= av() ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/login/css/') ?>/icon-font.min.css?v=<?= av() ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/login/css/') ?>/material-design-iconic-font.min.css?v=<?= av() ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/login/css/') ?>/animate.css?v=<?= av() ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/login/css/') ?>/hamburgers.min.css?v=<?= av() ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/login/css/') ?>/animsition.min.css?v=<?= av() ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/login/css/') ?>/select2.min.css?v=<?= av() ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/login/css/') ?>/daterangepicker.css?v=<?= av() ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/login/css/') ?>/util.css?v=<?= av() ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/login/css/') ?>/main.css?v=<?= av() ?>">
    <script src="<?= base_url('assets/login/login/js/jquery-3.2.1.min.js') ?>"></script>
    <script type="text/javascript">
    var action_url = '<?= base_url('auth') ?>';
    var login_screen = '<?= isset($screen) ? $screen : "login" ?>';
    var grecaptcha = undefined;
    
    $(document).on('ready',function(){
    changeScreen(login_screen);
    })
    </script>
    <?php if($SiteSetting['google_analytics']){ echo $SiteSetting['google_analytics']; } ?>
    <?php if($SiteSetting['faceboook_pixel']){ echo $SiteSetting['faceboook_pixel']; } ?>
    <?php
    $global_script_status = (array)json_decode($SiteSetting['global_script_status'],1);
    if(in_array('front', $global_script_status)){
    echo $SiteSetting['global_script'];
    }
    ?>
    <style type="text/css">
    .login100-form-bgbtn,.login100-more::before{background:#1b6ea8;}
    <?php
    foreach ($csss as $key => $d) {
    if(isset($setting[$key]) && $setting[$key] != ''){
    echo "\n{$d['selectotr']}{";
    echo "\t {$d['type']} : ".$setting[$key]. ";" ;
    echo "}";
    }
    }
    ?>
    </style>

    <?php if (is_rtl()) { ?>
      <!-- place here your RTL css code -->
    <?php } ?>
</head>

<body style="background-color: #999999;">
    <div class="limiter">
        <div class="container-login100">
            <div class="login100-more">
                <div class="row justify-content-center align-self-center">
                    <div class="col-10 col-sm-6">
                        <div class="row justify-content-center">
                            <div class="col-10">
                                <?php
                                $logo = base_url($SiteSetting['logo'] ? 'assets/images/site/'.$SiteSetting['logo'] : 'assets/vertical/assets/images/users/avatar-1.jpg');
                                ?>
                                <center><img style="max-width: 200px;" src="<?= $logo ?>" id="logo" class="img-fluid"></center>
                            </div>
                        </div>
                        <div class="affiliate-description">
                            <br>
                            <h3><?= $setting['heading'] ?></h3>
                            <br>
                            <?= $setting['content'] ?>
                            <br>
                        </div>
                        <div class="footer">
                            <?= $footer ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="wrap-login100 p-l-50 p-r-50 p-t-72 p-b-50">
                <?php  if($store['language_status']){  ?>
                <div class="lang-div">
                    <?= $LanguageHtml ?>
                </div>
                <?php } ?>
                
                <form class="login100-form_ validate-form" style="display: none;">
                    <span class="login100-form-title p-b-59 login100-form-title">
                        <?= __('template_simple.sign_up') ?>
                    </span>
                    <input type="hidden" name="refid" value="<?= isset($refid) ? $refid : '' ?>">
                    <input type="hidden" name="user_type" value="<?= isset($user_type) ? $user_type : 'user' ?>">
                    <div class="wrap-input100 validate-input" data-validate="<?= __('template_simple.first_name_is_required') ?>">
                        <span class="label-input100"><?= __('template_simple.first_name') ?></span>
                        <input class="input100" type="text" name="firstname" placeholder="<?= __('template_simple.first_name') ?>...">
                        <span class="focus-input100"></span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="<?= __('template_simple.last_name_is_required') ?>">
                        <span class="label-input100"><?= __('template_simple.last_name') ?></span>
                        <input class="input100" type="text" name="lastname" placeholder="<?= __('template_simple.last_name') ?>...">
                        <span class="focus-input100"></span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="<?= __('template_simple.username_is_required') ?>">
                        <span class="label-input100"><?= __('template_simple.username') ?></span>
                        <input class="input100" type="text" name="username" placeholder="<?= __('template_simple.username') ?>...">
                        <span class="focus-input100"></span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="<?= __('template_simple.valid_email_is_required') ?>">
                        <span class="label-input100"><?= __('template_simple.email') ?></span>
                        <input class="input100" type="text" name="email" placeholder="<?= __('template_simple.email') ?>...">
                        <span class="focus-input100"></span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="<?= __('template_simple.password_is_required') ?>">
                        <span class="label-input100"><?= __('template_simple.password') ?></span>
                        <input class="input100" type="password" name="password" placeholder="*************">
                        <span class="focus-input100"></span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="<?= __('template_simple.repeat_password_is_required') ?>">
                        <span class="label-input100"><?= __('template_simple.repeat_password') ?></span>
                        <input class="input100" type="password" name="cpassword" placeholder="*************">
                        <span class="focus-input100"></span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="<?= __('template_simple.address_is_required') ?>">
                        <span class="label-input100"><?= __('template_simple.address') ?></span>
                        <textarea name="address" class="input100" placeholder="<?= __('template_simple.address') ?>..."></textarea>
                        <span class="focus-input100"></span>
                    </div>
                    
                    <div class="wrap-input100 validate-input" data-validate="<?= __('template_simple.country_is_required') ?>">
                        <span class="label-input100"><?= __('template_simple.country') ?></span>
                        <select name="country" id="countries" class="input100">
                            <?php if($countries) { ?>
                            <?php foreach ($countries as $country) { ?>
                            <option value="<?php echo $country['id'] ?>"><?php echo $country['name'] ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                        <span class="focus-input100"></span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="<?= __('template_simple.state_is_required') ?>">
                        <span class="label-input100"><?= __('template_simple.state') ?></span>
                        <select name="state" id="states" class="input100">
                            <option value="">Select State</option>
                        </select>
                        <span class="focus-input100"></span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="<?= __('template_simple.paypal_email_is_required') ?>">
                        <span class="label-input100"><?= __('template_simple.paypal_email') ?></span>
                        <input class="input100" type="text" name="paypal_email" placeholder="<?= __('template_simple.paypal_email') ?>...">
                        <span class="focus-input100"></span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="<?= __('template_simple.phone_number_is_required') ?>">
                        <span class="label-input100"><?= __('template_simple.phone_number') ?></span>
                        <input class="input100" type="text" name="phone_number" placeholder="<?= __('template_simple.phone_number') ?>...">
                        <span class="focus-input100"></span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="<?= __('template_simple.alternate_phone_number_is_required') ?>">
                        <span class="label-input100"><?= __('template_simple.alternate_phone_number') ?></span>
                        <input class="input100" type="text" name="alternate_phone_number" placeholder="<?= __('template_simple.alternate_phone_number') ?>...">
                        <span class="focus-input100"></span>
                    </div>
                    <div class="flex-m w-full p-b-33">
                        <div class="contact100-form-checkbox">
                            <input class="input-checkbox100" id="ckb1"  type="checkbox" name="terms">
                            <label class="label-checkbox100" for="ckb1">
                                <span class="txt1"> <?= __('template_simple.i_accept_the_terms_of_the') ?>
                                    <?php if(!isset($setting_store)) { ?>
                                    <a href="<?php echo base_url('term-condition') ?>" target='_blank'><?= __('template_simple.affiliate_policy') ?></a>
                                    <?php } else { ?>
                                    <a data-toggle="modal" href='#modal-privacy'><?= __('template_simple.affiliate_policy') ?></a>
                                    <?php } ?>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button class="login100-form-btn">
                            <?= __('template_simple.sign_up') ?>
                            </button>
                        </div>
                        <?php if($store['registration_status']){ ?>
                        <a href="javascript:void(0)" class="dis-block txt3 hov1 p-r-30 p-t-10 p-b-10 p-l-30 change-view" data-type='login'>
                            <i class="fa fa-long-arrow-left m-l-5"></i>
                            <?= __('template_simple.sign_in') ?>
                        </a>
                        <?php } ?>
                    </div>
                </form>
                <div class="login100-form dy_form" style="display: none;">
                    <?= $register_fomm ?>
                </div>
                
                <form class="signin-form" >
                    <span class="login100-form-title p-b-59 signin-form-title">
                        <?= __('template_simple.sign_in') ?>
                    </span>
                    <input type="hidden" name="type" value="user">
                    <div class="wrap-input100" data-validate="Username is required">
                        <span class="label-input100"><?= __('template_simple.username') ?></span>
                        <input class="input100" type="text" name="username" placeholder="Username...">
                        <span class="focus-input100"></span>
                    </div>
                    <div class="wrap-input100" data-validate="Password is required">
                        <span class="label-input100"><?= __('template_simple.password') ?></span>
                        <input class="input100" type="password" name="password" placeholder="*************">
                        <span class="focus-input100"></span>
                    </div>
                    <?php if (isset($googlerecaptcha['affiliate_login']) && $googlerecaptcha['affiliate_login']) { ?>
                    <div class="captch mb-3">
                        <script src='https://www.google.com/recaptcha/api.js'></script>
                        <div class="g-recaptcha" data-sitekey="<?= $googlerecaptcha['sitekey'] ?>"></div>
                        <input type="hidden" name="captch_response" id="captch_response">
                    </div>
                    <?php } ?>
                    <div class="login-error"></div>
                    <div class="text-center p-b-10">
                        <a href="javascript:void(0)" class="change-view dis-block txt3 hov1" data-type='forget'><?= __('template_simple.forget_password') ?> ?</a>
                        <br>
                    </div>
                    <div class="container-login100-form-btn">
                        <div class="login-buttons">
                            <button class="btn_signin_bg">
                            <?= __('template_simple.sign_in') ?>
                            </button>
                            <?php if($store['registration_status']){ ?>
                            <button type="button" class="change-view btn_signup" data-type='register'>
                            <?= __('template_simple.sign_up') ?>
                            <i class="fa fa-long-arrow-right m-l-5"></i>
                            </button>
                            <?php } ?>
                        </div>
                    </div>
                </form>
                <form class="forget-form" style="display: none;">
                    <span class="login100-form-title p-b-59 forget-form-title">
                        <?= __('template_simple.forget_password') ?>
                        
                    </span>
                    <div class="wrap-input100" >
                        <span class="label-input100"><?= __('template_simple.registered_email') ?></span>
                        <input class="input100" type="text" name="email" placeholder="Email addess...">
                        <span class="focus-input100"></span>
                    </div>
                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button class="login100-form-btn btn_sendmail_bg">
                            <?= __('template_simple.send_mail') ?>
                            </button>
                        </div>
                        <a href="javascript:void(0)" class="dis-block txt3 hov1 p-r-30 p-t-10 p-b-10 p-l-30 change-view" data-type='login'>
                            <i class="fa fa-long-arrow-left m-l-5"></i>
                            <?= __('template_simple.back_login') ?>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <?php if(isset($setting_store)) { ?>
    <div class="modal fade" id="modal-privacy">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?= __('template_simple.privacy_policy') ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body"><?= $setting_store['policy_content'] ?></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('template_simple.close') ?></button>
                </div>
            </div>
        </div>
    </div>
    <?php }  ?>
    
    <script type="text/javascript">
    (function ($) {
    $.fn.btn = function (action) {
    var self = $(this);
    if (action == 'loading') {
    $(self).attr("disabled", "disabled");
    $(self).attr('data-btn-text', $(self).html());
    $(self).html('Loading..');
    }
    if (action == 'reset') {
    $(self).html($(self).attr('data-btn-text'));
    $(self).removeAttr("disabled");
    }
    }
    })(jQuery);
    $(".login100-form.dy_form label").addClass("label-input100");
    $('.login100-form.dy_form input[type="text"],.login100-form.dy_form input[type="number"],.login100-form.dy_form input[type="email"],.login100-form.dy_form input[type="password"],.login100-form.dy_form textarea,.login100-form.dy_form select').addClass("input100");
    $(".login100-form.dy_form input").removeClass("form-control");
    $(".login100-form.dy_form form").addClass("login100-form validate-form");
    $(".login100-form.dy_form .form-group").addClass("wrap-input100 validate-input");
    $.each($('.login100-form.dy_form input'),function(i,j){
    $(this).parents(".form-group ").attr("data-validate",$(this).parents(".form-group ").find("label").html()+' required');
    $(this).after('<span class="focus-input100"></span>');
    })
    $(".reg_form").submit(function(e){
    e.preventDefault();
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
    } else {
    errorInnerHTML = 'The Mobile Number field is required.'
    }
    $("#phone").parents(".form-group").removeClass("has-error");
    $(".reg_form .text-danger").remove();
    if(!is_valid){
    $("#phone").parents(".form-group").addClass("has-error");
    $("#phone").parents(".form-group").find('> div').after("<span class='text-danger'>"+ errorInnerHTML +"</span>");
    }
    }
    if(is_valid){
    /*var check_captch = true;
    if (grecaptcha === undefined) {
    check_captch = false;
    }
    
    $("#reg_captch_response").val('')
    if(check_captch){
    reg_captch_response = grecaptcha.getResponse();
    $("#reg_captch_response").val(reg_captch_response)
    }*/
    $this = $(this);
    $.ajax({
    url:'<?= base_url("pagebuilder/register") ?>',
    type:'POST',
    dataType:'json',
    data:$this.serialize(),
    beforeSend:function(){
    $this.find('button[type="submit"]').btn("loading");
    },
    complete:function(){
    $this.find('button[type="submit"]').btn("reset");
    },
    success:function(json){
    if(json['redirect']){
    window.location.replace(json['redirect']);
    }
    if(json['warning']){
    showMessage($(".reg_form > legend"),json['warning'],'danger');
    }
    /* if(json['errors']){
    $.each(json['errors'],function(i,j){
    showMessage($(".reg_form > legend"),j,'danger');
    })
    }*/
    $container = $this;
    $container.find(".has-error").removeClass("has-error");
    $container.find("span.text-danger").remove();
    if(json['errors']){
    $.each(json['errors'], function(i,j){
    $ele = $container.find('[name="'+ i +'"]');
    if(i == 'captch_response' && grecaptcha){ grecaptcha.reset(); }
    if($ele){
    $ele.parents(".form-group").addClass("has-error");
    if($ele.attr("type") == 'checkbox'){
    $ele.parents("label").after("<span class='text-danger'>"+ j +"</span>");
    }else{
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
    function showMessage(ele,message,type) {
    $(".alert").remove();
    var msg = $('<div class="alert alert-'+ type +' col-sm-12">\
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>\
        '+ message +' \
    </div>');
    ele.after(msg);
    $('html, body').animate({
    scrollTop: $(".reg_form ").offset().top
    }, 100);
    }
    $("#countries").on('change',function(){
    var country = $(this).val();
    $.ajax({
    url: '<?php echo base_url('get_state') ?>',
    type: 'post',
    dataType: 'json',
    data: {
    country_id : country
    },
    success: function (json) {
    if(json){
    var html = '';
    $.each(json, function(k,v){
    html += '<option value="'+v.id+'">'+v.name+'</option>';
    });
    $('#states').html(html);
    }
    }
    });
    });
    </script>
    <script src="<?= base_url('assets/login/login/js/animsition.min.js') ?>"></script>
    <script src="<?= base_url('assets/login/login/js/popper.js') ?>"></script>
    <script src="<?= base_url('assets/login/login/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('assets/login/login/js/select2.min.js') ?>"></script>
    <script src="<?= base_url('assets/login/login/js/moment.min.js') ?>"></script>
    <script src="<?= base_url('assets/login/login/js/daterangepicker.js') ?>"></script>
    <script src="<?= base_url('assets/login/login/js/countdowntime.js') ?>"></script>
    <script src="<?= base_url('assets/login/login/js/main.js?v=1') ?>"></script>
    <div style="display:none;"><a href="https://affiliatepro.org">affiliate pro</a></div>
</body>
</html>
<?php }  ?>