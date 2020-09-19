<!DOCTYPE html>
<html lang="en">
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

    <title><?= $setting['heading'] ?></title>
    <link href="<?= base_url('assets/login/multiple_pages') ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/login/multiple_pages') ?>/style.css" rel="stylesheet">
    <link href="<?= base_url('assets/login/multiple_pages/css') ?>/swiper.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/login/multiple_pages/css') ?>/fontawesome.css" rel="stylesheet">
    
    <script src="<?= base_url('assets/login/multiple_pages/js') ?>/swiper.min.js"></script>
    <script src="<?= base_url('assets/login/multiple_pages') ?>/jquery/jquery-1.12.0.min.js"></script>
    <script src="<?= base_url('assets/login/multiple_pages') ?>/bootstrap/js/bootstrap.bundle.min.js"></script>

    <?php if (is_rtl()) { ?>
      <!-- place here your RTL css code -->
    <?php } ?>
  </head>
<body>
  <?php if( current_url() != site_url('/p/login') && current_url() != site_url('/p/register') && current_url() != site_url('/p/forget-password') ){ ?>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light main-navbar">
    <div class="container">
      <a class="navbar-brand" title="<?= $setting['heading'] ?>" href="<?= base_url('/') ?>">
          <img id="logo" src="<?= resize($logo,100,80) ?>">
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item <?php if(base_url(uri_string()) == base_url('/')){ echo 'active'; } ?>">
            <a class="nav-link" href="<?= base_url('/') ?>">Home</a>
          </li>
          <li class="nav-item  <?php if(base_url(uri_string()) == base_url('/p/about')){ echo 'active'; } ?>">
              <a class="nav-link" href="<?= base_url('/p/about') ?>">About</a>
          </li>
          <li class="nav-item <?php if(base_url(uri_string()) == base_url('/p/contact')){ echo 'active'; } ?>">
              <a class="nav-link" href="<?= base_url('/p/contact') ?>">Contact</a>
         </li>
          <li class="nav-item <?php if(base_url(uri_string()) == base_url('/p/login')){ echo 'active'; } ?>">
              <a class="nav-link" href="<?= base_url('/p/login') ?>">Login</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <?php } ?>