<div class="row">
	<div class="col-12">
		<div class="card m-b-30">
			<div class="card-header">
				<h4 class="card-title pull-left"><?= __('admin.manage_admin') ?></h4>
				<div class="pull-right">
					<a class="btn btn-primary" href="<?= base_url('admincontrol/admin_user/')  ?>"><?= __('admin.cancel') ?></a>
				</div>
			</div>
			<div class="card-body">
				<form id="admin-form">
					<input type="hidden" name="user_id" value="<?= (int)$user->id ?>">

					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label"><?= __('admin.first_name') ?></label>
								<input placeholder="<?= __('admin.enter_your_first_name') ?>" name="firstname" value="<?php echo $user->firstname; ?>" class="form-control" type="text">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label"><?= __('admin.last_name') ?></label>
								<input placeholder="<?= __('admin.enter_your_last_name') ?>" name="lastname" class="form-control" value="<?php echo $user->lastname; ?>" type="text">
							</div>
						</div>
					</div>


					<div class="form-group">
						<label class="control-label"><?= __('admin.your_email') ?> </label>
						<input placeholder="<?= __('admin.enter_your_email_address') ?>" name="email" id="email" class="form-control" value="<?php echo $user->email; ?>"  type="email">
					</div>
					<div class="form-group">
						<label class="control-label"><?= __('admin.username') ?> </label>
						<input placeholder="<?= __('admin.enter_username_address') ?>" name="username" id="username" class="form-control" value="<?php echo $user->username; ?>"  type="text">
					</div>

					<div class="form-group">
						<label class="control-label"><?= __('admin.phone_number') ?></label>
						<input placeholder="<?= __('admin.enter_your_mobile_number') ?>"  name="PhoneNumber" value="<?php echo $user->PhoneNumber; ?>" class="form-control" id="phonenumber" type="text">
					</div>

					<div class="form-group">
						<label class="control-label"><?= __('admin.country') ?></label>
						<select name="Country" class="form-control countries" id="Country" >
							<option value="" selected="selected" ><?= __('admin.select_country') ?></option>
							<?php foreach($country as $countries): ?>
							<option <?php if(!empty($user->Country) && $user->Country == $countries->id) { ?> selected <?php }?> value="<?php echo $countries->id; ?>"><?php echo $countries->name; ?></option>
							<?php endforeach; ?> 
						</select>
					</div>
					
					<div class="form-group">
						<label class="control-label"><?= __('admin.city') ?></label>
						<input class="form-control" placeholder="<?= __('admin.enter_your_city') ?>" name="City" id="City" value="<?php echo $user->City;?>" type="text">
					</div>

					<div class="form-group">
						<label class="control-label"><?= __('admin.pincode') ?></label>
						<input class="form-control" placeholder="<?= __('admin.enter_your_pincode') ?>" name="Zip" id="Zip" value="<?php echo $user->Zip;?>" type="text">
					</div>

					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label"><?= __('admin.password') ?></label>
								<input class="form-control"  name="password" type="password">
							</div>		
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label"><?= __('admin.confirm_password') ?></label>
								<input class="form-control"  name="cpassword" type="password">
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label"><?= __('admin.member_image') ?></label>
						
						<div class="fileUpload btn btn-sm btn-primary">
							<span><?= __('admin.choose_file') ?></span>
							<input id="uploadBtn" name="avatar" class="upload" type="file">
						</div>
						<?php $avatar = $user->avatar != '' ? $user->avatar : 'no-user_image.jpg' ; ?>
						<img src="<?php echo base_url();?>assets/images/<?php echo $avatar; ?>" id="blah" class="thumbnail" border="0" width="220px">
					</div>

					<div class="form-group">
						<button type="button" class="btn btn-primary btn-submit"> Submit </button>
						<span class="loading-submit"></span>
					</div>
				</form>
			</div>
		</div> 
	</div> 
</div>

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

	$(".btn-submit").on('click',function(evt){
        $this = $("#admin-form");
        $(".btn-submit").btn("loading");
		$('.loading-submit').show();

        evt.preventDefault();
        var formData = new FormData($("#admin-form")[0]);

        formData = formDataFilter(formData);
        
        $.ajax({
            url:'<?= base_url('admincontrol/admin_user_form') ?>',
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
            complete:function(result){
            	$(".btn-submit").btn("reset");
            },
            success:function(result){
                $('.loading-submit').hide();
                $this.find(".has-error").removeClass("has-error");
                $this.find("span.text-danger").remove();
                
                if(result['location']){
                    window.location = result['location'];
                }
                if(result['errors']){
                    $.each(result['errors'], function(i,j){
                        $ele = $this.find('[name="'+ i +'"]');
                        if($ele){
                            $ele.parents(".form-group").addClass("has-error");
                            $ele.after("<span class='text-danger'>"+ j +"</span>");
                        }
                    });
                }
            },
        })
        return false;
    });
</script>
