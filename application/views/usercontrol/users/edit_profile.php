		<div class="alert alert-warning" id="message" style="display: none;">
		</div>
		<div class="form-horizontal" method="post" id="profile-frm" enctype="multipart/form-data">
			<div class="row">
				<div class="col-12">
					<div class="card m-b-30">
						<div class="card-body">

							<?php if($this->session->flashdata('success')){?>
								<div class="alert alert-success alert-dismissable my_alert_css">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<?php echo $this->session->flashdata('success'); ?> </div>
							<?php } ?>
							
							<?php if($this->session->flashdata('error')){?>
								<div class="alert alert-danger alert-dismissable my_alert_css">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<?php echo $this->session->flashdata('error'); ?> </div>
							<?php } ?>

							<div class="row">
								<div class="col-sm-8">
									<?= $html_form ?>
									<button class="btn btn-block btn-default btn-success" id="update-user" type="submit"><i class="fa fa-save"></i> <?= __('user.update_profile') ?> <span class="loading-submit"></span> </button>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<label for="example-text-input" class="col-form-label"><?= __('user.member_image') ?></label><br>

										<?php $avatar = $user['avatar'] != '' ? 'assets/images/users/'.$user['avatar'] : 'assets/vertical/assets/images/users/avatar-1.jpg' ; ?>
										<img src="<?php echo base_url($avatar); ?>" id="blah" class="thumbnail" border="0" width="220px">
										<br>

										<div class="fileUpload btn btn-sm btn-primary">
											<span><?= __('user.choose_file') ?></span>
											<input id="uploadBtn" name="avatar" class="upload" type="file">
										</div>
									</div>
								</div>
							</div>
							
					</div>
				</div>
			</div>
		</div>



<script type="text/javascript">
	$("#update-user").click(function(evt){
		$this = $(".reg_form");
		evt.preventDefault();

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

        var formData = new FormData($this[0]);

        formData.append("action", $(this).attr("name"));
        formData.append("avatar", $("#uploadBtn")[0].files[0]);

        formData = formDataFilter(formData);

        
		if(is_valid){
			$.ajax({
				url:'',
				type:'POST',
	            dataType:'json',
	            cache:false,
	            contentType: false,
	            processData: false,
	            data:formData,
	            xhr: function (){
	                var jqXHR = null;

	                if ( window.ActiveXObject ){
	                    jqXHR = new window.ActiveXObject( "Microsoft.XMLHTTP" );
	                }else {
	                    jqXHR = new window.XMLHttpRequest();
	                }
	                
	                jqXHR.upload.addEventListener( "progress", function ( evt ){
	                    if ( evt.lengthComputable ){
	                        var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
	                        console.log( 'Uploaded percent', percentComplete );
	                        $('.loading-submit').text(percentComplete + "% Loading");
	                    }
	                }, false );

	                jqXHR.addEventListener( "progress", function ( evt ){
	                    if ( evt.lengthComputable ){
	                        var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
	                        $('.loading-submit').text("Save");
	                    }
	                }, false );
	                return jqXHR;
	            },
				beforeSend:function(){},
				complete:function(){},
				success:function(json){
					if(json['location']){
						window.location = json['location'];
					}

					$this.find(".has-error").removeClass("has-error");
					$this.find("span.text-danger").remove();
					if(json['errors']){
					    $.each(json['errors'], function(i,j){
					        $ele = $this.find('[name="'+ i +'"]');
					        if($ele){
					            $ele.parents(".form-group").addClass("has-error");
					            $ele.after("<span class='text-danger'>"+ j +"</span>");
					        }
					    })
					}	
				}
			})
		}
	})
</script>

<script type="text/javascript">
function readURL(input) {
	if (input.files && input.files[0]) {
	var reader = new FileReader();
		reader.onload = function(e) {
			jQuery('#blah').attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);
	}
}
document.getElementById("uploadBtn").onchange = function () {
	readURL(this);
};
var state_id = '<?php echo $user->state ?>';

$("#Country").on('change',function(){
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
                    if(v.id == state_id){
                        html += '<option value="'+v.id+'" selected="selected">'+v.name+'</option>';
                    }else{
                        html += '<option value="'+v.id+'">'+v.name+'</option>';
                    }
                });
                $('#states').html(html);
            }
        }
    });
});
$("#Country").trigger('change');
</script>