<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
 
  <?php if($favicon){ ?>
      <link rel="icon" href="<?php echo base_url('assets/images/site/'.$favicon) ?>" type="image/*" sizes="16x16">
  <?php } ?>
  
  <title><?= $title ?></title>
  <meta name="author" content="<?= $meta_author ?>">
  <meta name="keywords" content="<?= $meta_keywords ?>">
  <meta name="description" content="<?= $meta_description ?>">

  <script src="<?= base_url('assets/plugins/builder_layout') ?>/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

  <script src="<?= base_url('assets/plugins/builder_layout') ?>/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  
  <link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/builder_layout/font-awesome.min.css') ?>">
  <link id="main-sheet" rel="stylesheet" href="<?= base_url('assets/plugins/pBuilder/public/css/bootstrap.min.css?v='. av()) ?>">
  <script src="<?= base_url('assets/plugins/builder_layout') ?>/bootstrap.min.js"></script>
  
  <link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/builder_layout') ?>//bootstrap-datepicker.min.css">
  <script src="<?= base_url('assets/plugins/builder_layout') ?>/bootstrap-datepicker.min.js"></script>
  
  <link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/builder_layout/style.css') ?>">
  <?php 
    $logo = base_url($site['logo'] ? 'assets/images/site/'.$site['logo'] : 'assets/vertical/assets/images/users/avatar-1.jpg');
  ?>
  <script type="text/javascript">
      var action_url = '<?= base_url('auth') ?>';        
  </script>

  <?php if($site['google_analytics']){ echo $site['google_analytics']; } ?>
  <?php if($site['faceboook_pixel']){ echo $site['faceboook_pixel']; } ?>

  <?php 
    $global_script_status = (array)json_decode($site['global_script_status'],1);
    if(in_array('front', $global_script_status)){
      echo $site['global_script'];
    }
  ?>
</head>

<nav class="navbar navbar-default" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?= base_url() ?>">
        <img src="<?= $logo ?>" id="logo" alt="">
      </a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
      <ul class="nav navbar-nav navbar-right">
        <?php foreach ($pages as $key => $value) {    ?>
            <li >
              <?php if(filter_var($value['slug'], FILTER_VALIDATE_URL)){ ?>
                <a <?= $value['new_tab'] ? 'target="_blank"' : '' ?> href="<?= $value['slug'] ?>"><?= $value['name'] ?></a>
              <?php } else { ?>
                <a <?= $value['new_tab'] ? 'target="_blank"' : '' ?> href="<?= base_url("page/".$value['slug']) ?>"><?= $value['name'] ?></a>
              <?php } ?>
            </li>
          <?php } ?>

          <?php if($store['language_status']){ ?>
            <li class="dropdown">
              <?= $LanguageHtml ?>
            </li>
          <?php } ?>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div>
</nav>
<body><?= $body ?></body>
<footer class="blog-footer">  <?= $footer ?> </footer> 


<div style="display:none;"><a href="https://affiliatepro.org">affiliate pro</a></div>
<script>

  $("img[data-src]").each(function(){
    var html = '<iframe width="100%" height="315" src="'+ $(this).attr("data-src") +'" style="'+ $(this).attr("style") +'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
    $(this).after(html);
    $(this).remove()
  });
  
  (function ($) {
    $.fn.btn = function (action) {
        var self = $(this);
        if (action == 'loading') {
            $(self).addClass("btn-loading");
        }
        if (action == 'reset') {
            $(self).removeClass("btn-loading");
        }
    }
  })(jQuery); 

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
          var check_captch = true;
          if (grecaptcha === undefined) {
              check_captch = false;
          }

          
          $("#captch_response").val('')
          if(check_captch){
              captch_response = grecaptcha.getResponse();
              $("#captch_response").val(captch_response)
          }

          $this = $(this);
          $.ajax({
            url:'<?= base_url("pagebuilder/register") ?>',
            type:'POST',
            dataType:'json',
            data:$this.serialize(),
            beforeSend:function(){ $this.find('.btn-submit').btn("loading"); },
            complete:function(){ $this.find('.btn-submit').btn("reset"); },
            success:function(json){
              if(json['redirect']){
                window.location.replace(json['redirect']);
              }
              if(json['warning']){
                showMessage($(".reg_form > legend"),json['warning'],'danger');
              }
              if(json['errors']){
                $container = $(".reg_form");
                $container.find(".has-error").removeClass("has-error");
                $container.find("span.text-danger").remove();
                
                $.each(json['errors'], function(i,j){
                    $ele = $container.find('[name="'+ i +'"]');
                    if(i == 'captch_response' && grecaptcha){
                        grecaptcha.reset();
                       }
                    if($ele){
                        $ele.parents(".form-group").addClass("has-error");
                        $ele.after("<span class='text-danger'>"+ j +"</span>");
                    }
                })
                
              }
            },
          })
        }

        return false;
  }) 

  

  function validate (input) {
      if($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
          if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
              return false;
          }
      }
      else {
          if($(input).val().trim() == ''){
              return false;
          }
      }
  }
  function showValidate(input) {
      var thisAlert = $(input).parent();

      $(thisAlert).addClass('alert-validate');
  }
  function showMessage(ele,message,type) {
    $(".alert").remove();

    var msg = $('<div class="alert alert-'+ type +' col-sm-12">\
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>\
        '+ message +' \
    </div>');

    ele.after(msg);
  }

</script>
</body>
</html>
