<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
 
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $store_setting['name'] ?>  <?php echo isset($meta_title) ? '- ' . $meta_title : '' ?></title>
    <link href="<?php echo base_url('assets/plugins/store/') ?>/vendor/bootstrap/css/bootstrap.min.css?v=<?= av() ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/plugins/store/') ?>/shop-homepage.css?v=<?= av() ?>" rel="stylesheet">
    <script src="<?php echo base_url('assets/plugins/store/') ?>/vendor/jquery/jquery.min.js"></script>

    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/builder_layout/font-awesome.min.css') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if(isset($meta_title)){ ?> <meta property="og:title" content="<?php echo $meta_title ?>"><?php } ?>
    <?php if(isset($meta_description)){ ?> <meta property="og:description" content="<?php echo $meta_description ?>"><?php } ?>
    <?php if(isset($meta_image)){ ?> <meta property="og:image" content="<?php echo $meta_image ?>"><?php } ?>
    <?php 
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    ?>
    <meta property="og:url" content="<?php echo $actual_link ?>">
    <meta name="twitter:card" content="summary_large_image">
    <?php if($store_setting['favicon']){ ?>
        <link rel="icon" href="<?php echo base_url('assets/images/site/'.$store_setting['favicon']) ?>" type="image/*" sizes="16x16">
    <?php } ?>
    <?php if($SiteSetting['google_analytics'] != ''){ ?><?= $SiteSetting['google_analytics'] ?><?php } ?>
    <?php 
      $global_script_status = (array)json_decode($SiteSetting['global_script_status'],1);
      if(in_array('store', $global_script_status)){
        echo $SiteSetting['global_script'];
      }
    ?>
    <script type="text/javascript">
        (function ($) {
            $.fn.btn = function (action) {
                var self = $(this);
                if (action == 'loading') {
                    if ($(self).attr("disabled") == "disabled") {
                        //e.preventDefault();
                    }
                    $(self).attr("disabled", "disabled");
                    $(self).attr('data-btn-text', $(self).html());
                    $(self).html('<i class="fa fa-spinner fa-spin"></i> ' + $(self).text());
                }
                if (action == 'reset') {
                    $(self).html($(self).attr('data-btn-text'));
                    $(self).removeAttr("disabled");
                }
            }
        })(jQuery);
        var formDataFilter = function(formData) {
            if (!(window.FormData && formData instanceof window.FormData)) return formData
            if (!formData.keys) return formData
            var newFormData = new window.FormData()
            Array.from(formData.entries()).forEach(function(entry) {
                var value = entry[1]
                if (value instanceof window.File && value.name === '' && value.size === 0) {
                    newFormData.append(entry[0], new window.Blob([]), '')
                } else {
                    newFormData.append(entry[0], value)
                }
            })
            
            return newFormData
        }
    </script>

    <?php if (is_rtl()) { ?>
      <!-- place here your RTL css code -->
    <?php } ?>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <?php 
                if($store_setting['logo']){
                    $logo = base_url('assets/images/site/'.$store_setting['logo']); 
                }else{
                    $logo = base_url('assets/images/no-logo-1.jpg');  
                }
            ?>
            <a class="navbar-brand" href="<?php echo $home_link ?>">
              <img src="<?php echo $logo ?>" class="logo" style='height: 40px;'>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active"><a class="nav-link" href="<?php echo $home_link ?>"><?= __('store.products') ?></a></li>
                    <li class="nav-item active"><a class="nav-link" href="<?php echo $base_url ?>category">Categories</a></li>
                    <li class="nav-item active"><a class="nav-link" href="<?php echo $base_url ?>about"><?= __('store.about') ?></a></li>
                    <li class="nav-item active"><a class="nav-link" href="<?php echo $base_url ?>contact"><?= __('store.contact') ?></a></li>
                    <li class="nav-item active"><a class="nav-link" href="<?php echo $base_url ?>policy"><?= __('store.policy') ?></a></li>
           
                    <?php if($is_logged){ ?>
                    <li class="nav-item active user-dropdown">
                        <div class="dropdown">
                            <a href="javascript::void(0)" class="nav-link text-white" data-toggle="dropdown">
                            <?php 

                                $avatar = $client['avatar'] != '' ? $client['avatar'] : 'no-user_image.jpg' ; 
                            ?>
                            <img src="<?php echo base_url('assets/images/users/'. $avatar);?>" class="thumbnail user-img" border="0">
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?php echo $base_url ?>profile"><i class="fa fa-user"></i> <?= __('store.profile') ?></a>
                                <a class="dropdown-item" href="<?php echo $base_url ?>order"><i class="fa fa-gift"></i> <?= __('store.order') ?></a>
                                <a class="dropdown-item" href="<?php echo $base_url ?>shipping"><i class="fa fa-truck"></i> <?= __('store.shipping') ?></a>
                                <a class="dropdown-item" href="<?php echo $base_url ?>logout"><i class="fa fa-power-off"></i> <?= __('store.logout') ?></a>
                            </div>
                        </div>
                    </li>
                    <?php } else { ?>
                    <li class="nav-item active"><a class="nav-link" href="<?php echo $base_url ?>login"><?= __('store.login') ?></a></li>
                    <?php } ?>
                    <li class="nav-item active lang-menu">
                        <div class="dropdown dropdown d-lg-inline-block d-md-block" ><?= $CurrencyHtml ?></div>
                        <div class="dropdown dropdown d-lg-inline-block d-md-block" ><?= $LanguageHtml ?></div>
                    </li>
                    <li class="nav-item active min-cart">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle arrow-none waves-effect text-white" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <span class="fa fa-shopping-cart"></span>
                                <span class="text"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-cart" ></ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <?php if($this->session->flashdata('success')){ ?><br>
                    <div class="alert alert-success">
                        <?= $this->session->flashdata('success') ?>
                    </div>
                <?php } ?>
                <?php if($this->session->flashdata('error')){ ?><br>
                    <div class="alert alert-danger">
                        <?= $this->session->flashdata('error') ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php echo $content ?>
    <footer class="bg-dark">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <p class="m-0 text-white"><?php echo $store_setting['footer'] ?></p>
                </div>
                <div class="col-sm-6 text-right">
                    <div class="payment-icons">
                        <?php 
                            $payments = get_payment_gateways();
                            foreach ($payments as $key => $payment) {
                                if($payment['status']){
                                    echo "<img src='". resize($payment['icon'],50,40) ."' title='". $payment['title'] ."' >";
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script src="<?php echo base_url('assets/plugins/store/') ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
        $("#login-form input, #register-form input").focus(function(){
            if($(document).width() <= 408){
                $(".navbar-expand-lg,footer").hide();
            }
        })
        $("#login-form input, #register-form input").blur(function(){
            $(".navbar-expand-lg,footer").show();
        })
        $(document).delegate(".number-input div span","click",function(){
            var val = $(this).parents(".number-input").find("input").val();
            if($(this).hasClass("plus")) { val ++ }
            else { val -- }
            if(val <= 0) val = 1;
            $(this).parents(".number-input").find("input").val(val).trigger("change");
        })
        function updateCart(){
            $.ajax({
                url:'<?= $base_url ?>/mini_cart',
                type:'POST',
                dataType:'json',
                beforeSend:function(){},
                complete:function(){},
                success:function(json){
                    $(".min-cart .dropdown-cart").html(json['cart']);
                    $(".min-cart .nav-link .text").html(json['total']);
                },
            })
        }
        updateCart();
        $(".dropdown-cart").delegate(".btn-remove-cart","click",function(){
            $this = $(this);
            $.ajax({
                url:$this.attr("data-href"),
                type:'POST',
                dataType:'json',
                beforeSend:function(){},
                complete:function(){},
                success:function(json){
                    updateCart();              
                },
            })
            return false;
        });
    </script>
    
    <div style="display:none;">
    <a href="<?= base_url() ?>">affiliate pro</a>
    </div>

</body>
</html>