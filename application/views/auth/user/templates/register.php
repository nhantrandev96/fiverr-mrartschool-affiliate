<div class="container">
    <div class="row">
    	<div class="col">
    		<h1 class="mt-5 text-center">Affiliate Register</h1>
    	</div>
    </div>

    <div class="row">
    	<div class="col-sm-6 mt-5 offset-sm-3">
    		<form class="register-form_" style="display: none;">

    			<input type="hidden" name="refid" value="<?= isset($refid) ? $refid : '' ?>">
                <input type="hidden" name="user_type" value="user">

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <span class="control-label"><?= __('user.first_name') ?></span>
                            <input class="form-control" type="text" name="firstname">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <span class="control-label"><?= __('user.last_name') ?></span>
                            <input class="form-control" type="text" name="lastname">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <span class="control-label"><?= __('user.username') ?></span>
                    <input class="form-control" type="text" name="username">
                </div>
                <div class="form-group">
                    <span class="control-label"><?= __('user.email') ?></span>
                    <input class="form-control" type="text" name="email">
                </div>
                <div class="form-group">
                    <span class="control-label"><?= __('user.password') ?></span>
                    <input class="form-control" type="password" name="password">
                </div>
                <div class="form-group">
                    <span class="control-label"><?= __('user.repeat_password') ?></span>
                    <input class="form-control" type="password" name="cpassword">
                </div>
                
                <div class="wrap-input100 validate-input">
                    <span class="control-label"><?= __('template_simple.address') ?></span>
                    <textarea name="address" class="form-control" placeholder="<?= __('template_simple.address') ?>..."></textarea>
                    <span class="focus-input100"></span>
                </div>
                
                <div class="wrap-input100 validate-input">
                    <span class="control-label"><?= __('template_simple.country') ?></span>
                    <select name="country" size="1" id="countries" class="form-control">
                        <?php if($countries) { ?>
                        <?php foreach ($countries as $country) { ?>
                        <option value="<?php echo $country['id'] ?>"><?php echo $country['name'] ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                    <span class="focus-input100"></span>
                </div>

                <div class="wrap-input100 validate-input">
                    <span class="control-label"><?= __('template_simple.state') ?></span>
                    <select name="state" id="states" size="1" class="form-control">
                        <option value="">Select State</option>
                    </select>
                    <span class="focus-input100"></span>
                </div>

                <div class="wrap-input100 validate-input">
                    <span class="control-label"><?= __('template_simple.paypal_email') ?></span>
                    <input class="form-control" type="text" name="paypal_email" placeholder="<?= __('template_simple.paypal_email') ?>...">
                    <span class="focus-input100"></span>
                </div>

                <div class="wrap-input100 validate-input">
                    <span class="control-label"><?= __('template_simple.phone_number') ?></span>
                    <input class="form-control" type="text" name="phone_number" placeholder="<?= __('template_simple.phone_number') ?>...">
                    <span class="focus-input100"></span>
                </div>

                <div class="wrap-input100 validate-input">
                    <span class="control-label"><?= __('template_simple.alternate_phone_number') ?></span>
                    <input class="form-control" type="text" name="alternate_phone_number" placeholder="<?= __('template_simple.alternate_phone_number') ?>...">
                    <span class="focus-input100"></span>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="1" name="terms">
                                    <?= __('user.i_accept_the_terms_of_the') ?>  <a target="_blank" href="<?= base_url('privacy-policy') ?>"><?= __('user.affiliate_policy') ?></a> 
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-4 text-right">   
                            <a href="<?= base_url('login') ?>" class="" ><?= __('user.sign_in') ?></a>
                        </div>
                    </div>

                    <div class="warning"></div>
                </div>

                <div class="text-left pb-5">
                	<div class="row">
                        <div class="col text-right">    
                    	   <button class="btn btn-primary"><?= __('user.sign_up') ?></button>
                        </div>
                    </div>
                </div>
    		</form>
            <?= $register_fomm ?>
    	</div>
    </div>
</div>
<div style="display:none;"><a href="https://affiliatepro.org">affiliate pro</a></div>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/login/style.css') ?>">
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
	$('.register-form').on('submit',function(){

        $.ajax({
            url:'<?= base_url('auth') ?>/register',
            type:'POST',
            dataType:'json',
            data: $('.register-form').serialize(),
            beforeSend:function(){ $('.register-form button').prop("disabled",true); },
            complete:function(){ $('.register-form button').prop("disabled",false); },
            success:function(json){
                $('.warning').html('');
                $container = $('.register-form');
                $container.find(".has-error").removeClass("has-error");
                $container.find("span.text-danger").remove();
                
                if(json['errors']){
                    $.each(json['errors'], function(i,j){
                        $ele = $container.find('[name="'+ i +'"]');
                        if($ele){
                            if($ele.attr("type") == 'checkbox'){
                                $ele.parents(".checkbox").addClass("has-error");
                                $ele.parents(".checkbox").after("<span class='text-danger'>"+ j +"</span>");
                            } else{
                                $ele.parents(".form-group").addClass("has-error");
                                $ele.after("<span class='text-danger'>"+ j +"</span>");
                            }
                        }
                    })
                }

                if(json['redirect']){
                    window.location = json['redirect'];
                }
                if(json['warning']){
                    $('.warning').html("<br><br><div class='alert alert-danger'>"+ json['warning'] +"</div>");
                }
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


            $.ajax({
              url:'<?= base_url("pagebuilder/register") ?>',
              type:'POST',
              dataType:'json',
              data:$this.serialize(),
              beforeSend:function(){
                $('.reg_form button[type="submit"]').btn("loading");
              },
              complete:function(){
               $('.reg_form button[type="submit"]').btn("reset");
              },
              success:function(json){
                if(json['redirect']){
                  window.location.replace(json['redirect']);
                }
                if(json['warning']){
                  showMessage($(".reg_form > legend"),json['warning'],'danger');
                }
                //
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
            scrollTop: $(".reg_form ").offset().top-100
        }, 100);
  }
</script>
<script type="text/javascript">
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