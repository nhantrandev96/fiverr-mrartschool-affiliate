<html >
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>Admin Login</title>
        <meta content="Admin Dashboard" name="description">
        <meta content="Mannatthemes" name="author">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <?php if($setting['favicon']){ ?>
            <link rel="icon" href="<?php echo base_url('assets/images/site/'.$setting['favicon']) ?>" type="image/*" sizes="16x16">
        <?php } ?>

        <link href="<?php echo base_url(); ?>assets/vertical/assets/css/bootstrap.min.css?v=<?= av() ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>assets/vertical/assets/css/icons.css?v=<?= av() ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>assets/vertical/assets/css/style.css?v=<?= av() ?>" rel="stylesheet" type="text/css">
        <style>
            .login-card{
                width: 440px;
            }
            .login-card{
                background-color: rgba(255,255,255,0.5);
            }
            .btn-login{
                background: #00858a;
                color: #fff;
            }
            .btn-login:hover{
                background: #005e61;
            }
            @media(max-width: 500px){
                .login-card{
                    width: 100%;
                }
            }
        </style>

    </head>

    <body class="fixed-left" style="overflow: visible;">
        <div class="accountbg"></div>
        <div class="wrapper-page">
            <div class="card login-card border-0 pt-5 shadow-lg rounded-0">
                <div class="card-body">
                    <h3 class="text-center mt-0 m-b-15">
                        <?php 
                            $logo = $setting['logo'] ? base_url('assets/images/site/'. $setting['logo'] ) : base_url('assets/images/no_image_available.png');
                        ?>
                        <a href="#" class="logo logo-admin"><img src="<?= $logo ?>" style='width: 50%;' alt="logo"></a>
                    </h3>

                    <div class="p-3">
                        <form class="form-horizontal m-t-20 login-form" action="" >
                            <div class="form-group row">
                                <input type="hidden" name="type" value="admin">
                                <div class="col-12">
                                    <input class="form-control" type="text" required="" placeholder="Username" name="username">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12">
                                    <input class="form-control" type="password" required="" placeholder="Password" name="password">
                                </div>
                            </div>

                            <script type="text/javascript">
                                var grecaptcha = undefined;
                            </script>
                            <?php 
                                $db =& get_instance(); 
                                $googlerecaptcha =$db->Product_model->getSettings('googlerecaptcha');
                            ?>
                            <?php if (isset($googlerecaptcha['admin_login']) && $googlerecaptcha['admin_login']) { ?>
                                <div class="captch">
                                    <script src='https://www.google.com/recaptcha/api.js'></script>
                                    <div class="g-recaptcha" data-sitekey="<?= $googlerecaptcha['sitekey'] ?>"></div>
                                    <input type="hidden" name="captch_response" id="captch_response"> <br><br>
                                </div>
                            <?php } ?>

                            <div class="form-group text-center row m-t-20">
                                <div class="col-12">
                                    <button class="btn btn-login btn-block waves-effect waves-light py-2" type="submit">Log In</button>
                                </div>
                            </div>

                            <div class="form-group m-t-10 mb-0 row">
                                <div class="col-sm-12 m-t-20 text-center">
                                    <a href="javascript:void(0)" onclick="_open('forget')" class="text-white"><i class="mdi mdi-lock"></i> <small>Forgot your password ?</small></a>
                                </div>
                            </div>
                        </form>

                        <form class="form-horizontal m-t-20 forget-form" action="" >
                            <div class="form-group row">
                                <div class="col-12">
                                    <input class="form-control" type="text" required="" placeholder="Email" name="email">
                                </div>
                            </div>
                           
                            <div class="form-group text-center row m-t-20">
                                <div class="col-12">
                                    <button class="btn btn-login btn-block waves-effect waves-light py-2" type="submit">Forget Password</button>
                                </div>
                            </div>

                            <div class="form-group m-t-10 mb-0 row">
                                <div class="col-sm-12 m-t-20 text-center">
                                    <a href="javascript:void(0)" onclick="_open('login')" class="text-white"><i class="mdi mdi-lock"></i> <small>Back to Login </small></a>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/popper.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/modernizr.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/app.js"></script>    

        <script type="text/javascript">
            function _open(form) {
                $(".login-form, .forget-form").hide();
                $("." + form + "-form").show();
            }

            $('.login-form').on('submit',function(){
                var check = true;
                var inputLogin = $('.login-form');
                
                if (check) {
                    var check_captch = true;
                    if (grecaptcha === undefined) {
                        check_captch = false;
                    }

                    $("#captch_response").val('')

                    if(check_captch){
                      captch_response = grecaptcha.getResponse();
                      $("#captch_response").val(captch_response)
                    }

                    $.ajax({
                        url:'<?= base_url('auth') ?>/login',
                        type:'POST',
                        dataType:'json',
                        data: $('.login-form').serialize(),
                        beforeSend:function(){ $('.login-form button').prop("disabled",true); },
                        complete:function(){ $('.login-form button').prop("disabled",false); },
                        success:function(json){
                            $container = inputLogin;
                            $container.find(".has-error").removeClass("has-error");
                            $container.find("span.text-danger").remove();
                            
                            if(json['errors']){
                                $.each(json['errors'], function(i,j){
                                    if(i == 'captch_response' && grecaptcha){ grecaptcha.reset(); }
                                    $ele = $container.find('[name="'+ i +'"]');
                                    if($ele){
                                        $ele.parents(".form-group").addClass("has-error");
                                        $ele.after("<span class='text-danger'>"+ j +"</span>");
                                    }
                                })
                            }

                            if(json['redirect']){
                                window.location = json['redirect'];
                            }
                        },
                    })
                }

                return false;
            });

            $('.forget-form').on('submit',function(){
              
                $.ajax({
                    url:'<?= base_url('auth') ?>/forget',
                    type:'POST',
                    dataType:'json',
                    data: $('.forget-form').serialize(),
                    beforeSend:function(){ $('.forget-form button').prop("disabled",true); },
                    complete:function(){ $('.forget-form button').prop("disabled",false); },
                    success:function(json){
                        
                        $container = $('.forget-form');
                        $container.find(".has-error").removeClass("has-error");
                        $container.find("span.text-danger,.alert").remove();
                        
                        if(json['errors']){
                            $.each(json['errors'], function(i,j){
                                $ele = $container.find('[name="'+ i +'"]');
                                if($ele){
                                    $ele.parents(".form-group").addClass("has-error");
                                    $ele.after("<span class='text-danger'>"+ j +"</span>");
                                }
                            })
                        }

                        if(json['success']){
                            $('.forget-form button').after("<br><br><div class='alert alert-success'>"+ json['success'] +"</div>");
                        }

                        if(json['redirect']){
                            window.location = json['redirect'];
                        }
                    },
                })
                
                return false;
            });
        </script>
    </body>
</html>