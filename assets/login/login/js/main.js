function changeScreen(type) {
    $(".login100-form,.forget-form,.signin-form,.back-to-login").hide();
    if (type == 'login') {
        $(".signin-form").show();
    }
    else if (type == 'register'){
        $(".login100-form,.back-to-login").show();
    }
    else if (type == 'forget'){
        $(".forget-form").show();
    }
}
(function ($) {
    "use strict";

    /*==================================================================
    [ Focus Contact2 ]*/
    $('.input100').each(function(){
        $(this).on('blur', function(){
            if($(this).val().trim() != "") {
                $(this).addClass('has-val');
            }
            else {
                $(this).removeClass('has-val');
            }
        })    
    })


    /*==================================================================
    [ Validate after type ]*/
    $('.validate-input .input100').each(function(){
        $(this).on('blur', function(){
            if(validate(this) == false){
                showValidate(this);
            }
            else {
                $(this).parent().addClass('true-validate');
            }
        })    
    })

    /*==================================================================
    [ Validate ]*/
    var input = $('.validate-input .input100');

    $('.validate-form').on('submit',function(){
        var check = true;

        for(var i=0; i<input.length; i++) {
            if(validate(input[i]) == false){
                showValidate(input[i]);
                check=false;
            }
        }

        if (check) {
            $.ajax({
                url:action_url+'/register',
                type:'POST',
                dataType:'json',
                data: $('.validate-form').serialize(),
                beforeSend:function(){ $('.login100-form-btn').prop("disabled",true); },
                complete:function(){ $('.login100-form-btn').prop("disabled",false); },
                success:function(json){

                    if (json['errors']) {
                        showMessage($(".login100-form-title"),Object.values(json['errors']).join("<br>"),'danger');
                    }
                    if (json['error']) {
                        showMessage($(".login100-form-title"),json['error'],'danger');
                    }
                    if (json['success']) {
                        showMessage($(".login100-form-title"),json['success'],'success');
                    }
                    if(json['redirect']){
                        window.location = json['redirect'];
                    }
                },
            })
        }

        return false;
    });

    function showMessage(ele,message,type) {
        $(".alert").remove();

        var msg = $('<div class="alert alert-'+ type +' col-sm-12">\
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>\
            '+ message +' \
        </div>');

        ele.after(msg);
    }

    $('.signin-form').on('submit',function(){
        var check = true;

        var inputLogin = $('.signin-form .input100');
        for(var i=0; i<inputLogin.length; i++) {
            if(validate(inputLogin[i]) == false){
                showValidate(inputLogin[i]);
                check=false;
            }
        }

        if (check) {

            var check_captch = true;
            if (grecaptcha === undefined) {
                check_captch = false;
            }

            $("#captch_response").val('')

            if(check_captch){
              var captch_response = grecaptcha.getResponse();
              $("#captch_response").val(captch_response)
            }

            $.ajax({
                url:action_url+'/login',
                type:'POST',
                dataType:'json',
                data: $('.signin-form').serialize(),
                beforeSend:function(){ $('.login100-form-btn').prop("disabled",true); },
                complete:function(){ $('.login100-form-btn').prop("disabled",false); },
                success:function(json){
                     if(json['errors']){
                        $.each(json['errors'], function(i,j){
                           
                            if(i == 'username'){
                                if(check_captch) grecaptcha.reset();
                                $(".login-error").html(j).show();
                                $(".login-error").addClass('shake')
                                setTimeout(function(){ $(".login-error").removeClass('shake') }, 500);
                            }
                        })
                    }
                    if (json['error']) {
                        showMessage($(".signin-form-title"),json['error'],'danger');
                    }
                    if (json['success']) {
                        showMessage($(".signin-form-title"),json['success'],'success');
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
        var check = true;

        var inputLogin = $('.forget-form .input100');
        for(var i=0; i<inputLogin.length; i++) {
            if(validate(inputLogin[i]) == false){
                showValidate(inputLogin[i]);
                check=false;
            }
        }

        if (check) {
            $.ajax({
                url:action_url+'/forget',
                type:'POST',
                dataType:'json',
                data: $('.forget-form').serialize(),
                beforeSend:function(){ $('.login100-form-btn').prop("disabled",true); },
                complete:function(){ $('.login100-form-btn').prop("disabled",false); },
                success:function(json){
                    if (json['error']) {
                        showMessage($(".forget-form-title"),json['error'],'danger');
                    }
                    if (json['success']) {
                        showMessage($(".forget-form-title"),json['success'],'success');
                    }
                    if(json['redirect']){
                        window.location = json['redirect'];
                    }
                },
            })
        }

        return false;
    });

    $(".change-view").click(function(){
        var type = $(this).attr("data-type");
        changeScreen(type);
    })


    $('.validate-form .input100').each(function(){
        $(this).focus(function(){
           hideValidate(this);
           $(this).parent().removeClass('true-validate');
        });
    });

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

    function hideValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).removeClass('alert-validate');
    }
    


})(jQuery);