<div class="page-content-wrapper ">
	<div class="container">
		<h1><?= __('store.profile') ?></h1>
		<form action="<?php echo base_url('store/profile') ?>" class="form-horizontal" method="post" id="profile-frm" enctype="multipart/form-data">
			<div class="row">
				<div class="col-12">
					<div class="m-b-30">
						<div class="card-body">
							<div class="form-group row">
								<label for="example-text-input" class="col-sm-3 col-form-label"><?= __('store.first_name') ?></label>
								<div class="col-sm-9">
									<input placeholder="Enter your First Name" name="firstname" value="<?php echo $userDetails['firstname']; ?>" class="form-control" type="text">
								<?php if($errors && isset($errors['firstname'])) { ?>
								<div class="text-danger"><?php echo $errors['firstname'] ?></div>
								<?php } ?>
								</div>
							</div>
							<div class="form-group row">
								<label for="example-text-input" class="col-sm-3 col-form-label"><?= __('store.last_name') ?></label>
								<div class="col-sm-9">
									<input placeholder="Enter your Last Name" name="lastname" class="form-control" value="<?php echo $userDetails['lastname']; ?>" type="text">
								<?php if($errors && isset($errors['lastname'])) { ?>
								<div class="text-danger"><?php echo $errors['lastname'] ?></div>
								<?php } ?>
								</div>
							</div>
							<div class="form-group row">
								<label for="example-text-input" class="col-sm-3 col-form-label"><?= __('store.your_email') ?> </label>
								<div class="col-sm-9">
									<input placeholder="Enter your Email Address" name="email" id="email" class="form-control" value="<?php echo $userDetails['email']; ?>" type="email">
								<?php if($errors && isset($errors['email'])) { ?>
								<div class="text-danger"><?php echo $errors['email'] ?></div>
								<?php } ?>
								</div>
							</div>
							<div class="form-group row">
								<label for="example-text-input" class="col-sm-3 col-form-label"><?= __('store.phone_number') ?></label>
								<div class="col-sm-9">

									<link rel="stylesheet" href="<?= base_url('assets/plugins/tel/css/intlTelInput.css') ?>?v=<?= av() ?>">
									<script src="<?= base_url('assets/plugins/tel/js/intlTelInput.js') ?>"></script>
									<div class="form-group">
										<input id="PhoneNumber" placeholder="Enter your Mobile Number" type="text" name="PhoneNumber" value="">
									</div>
									<script type="text/javascript">
										var tel_input = intlTelInput(document.querySelector("#PhoneNumber"), {
										  initialCountry: "auto",
										  utilsScript: "<?= base_url('/assets/plugins/tel/js/utils.js?1562189064761') ?>",
										  separateDialCode:true,
										  geoIpLookup: function(success, failure) {
										    $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
										      var countryCode = (resp && resp.country) ? resp.country : "";
										      success(countryCode);
										      setTimeout(function(){ $("#PhoneNumber").val('<?php echo $userDetails['PhoneNumber']; ?>') }, 100);
										    });
										  },
										});


									</script>


								<?php if($errors && isset($errors['PhoneNumber'])) { ?>
									<div class="text-danger"><?php echo $errors['PhoneNumber'] ?></div>
								<?php } ?>
								</div>
							</div>



							<div class="form-group row">
								<label for="example-text-input" class="col-sm-3 col-form-label"><?= __('store.country') ?></label>
								<div class="col-sm-9">
									<select name="Country" class="form-control countries" id="Country" >
										<option value="" selected="selected" ><?= __('store.select_country') ?></option>
										<?php foreach($country as $countries): ?>
										<option <?php if(!empty($userDetails['country']) && $userDetails['country'] == $countries->id) { ?> selected <?php }?> value="<?php echo $countries->id; ?>"><?php echo $countries->name; ?></option>
										<?php endforeach; ?> 
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="example-text-input" class="col-sm-3 col-form-label"><?= __('store.city') ?></label>
								<div class="col-sm-9">
									<input class="form-control" placeholder="Enter your City" name="City" id="City" value="<?php echo $userDetails['city'];?>" type="text">
								<?php if($errors && isset($errors['City'])) { ?>
								<div class="text-danger"><?php echo $errors['City'] ?></div>
								<?php } ?>
								</div>
							</div>
							<div class="form-group row">
								<label for="example-text-input" class="col-sm-3 col-form-label"><?= __('store.pincode') ?></label>
								<div class="col-sm-9">
									<input class="form-control" name="Zip" id="Zip" value="<?php echo $userDetails['zip'];?>" type="text">
								<?php if($errors && isset($errors['Zip'])) { ?>
								<div class="text-danger"><?php echo $errors['Zip'] ?></div>
								<?php } ?>
								</div>
							</div>
							
							<div class="form-group row">
								<label for="example-text-input" class="col-sm-3 col-form-label"><?= __('store.member_image') ?></label><br>
								<div class="col-sm-9">
									<div class="fileUpload btn btn-sm btn-primary">
										<span><?= __('store.choose_file') ?></span>
										<input id="uploadBtn" name="avatar" class="upload" type="file">
									</div>
									<?php $avatar = $userDetails['avatar'] != '' ? $userDetails['avatar'] : 'no-user_image.jpg' ; ?>
									<img src="<?php echo base_url('assets/images/users/'. $avatar);?>" id="blah" class="thumbnail" border="0" width="220px">		
								</div>
							</div>
							<h5>Change Password</h5>
							<hr>
							<div class="form-group row">
								<label for="example-text-input" class="col-sm-3 col-form-label">Enter new Password</label>
								<div class="col-sm-9">
									<input class="form-control" name="new_password" value="" type="password">
									<?php if($errors && isset($errors['new_password'])) { ?>
										<div class="text-danger"><?php echo $errors['new_password'] ?></div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group row">
								<label for="example-text-input" class="col-sm-3 col-form-label">Confirm Password</label>
								<div class="col-sm-9">
									<input class="form-control" name="c_password" value="" type="password">
									<?php if($errors && isset($errors['c_password'])) { ?>
										<div class="text-danger"><?php echo $errors['c_password'] ?></div>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="text-right">
							<button class="btn btn-default btn-success" id="update-profile" type="submit"><i class="fa fa-save"></i> <?= __('store.update_profile') ?> </button>
						</div>
					</form>
				</div>
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

	$("#profile-frm").submit(function(){
		var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
		is_valid = false;
		var errorInnerHTML = '';
		if ($("#PhoneNumber").val().trim()) {
			if (tel_input.isValidNumber()) {
				is_valid = true;
				$("#phonenumber-input").val("+"+tel_input.selectedCountryData.dialCode + $("#PhoneNumber").val().trim())
			} else {
				var errorCode = tel_input.getValidationError();
				errorInnerHTML = errorMap[errorCode];
			}
		} else {
			errorInnerHTML = 'The Mobile Number field is required.'
		}
		$("#PhoneNumber").parents(".form-group").removeClass("has-error");
		$("#profile-frm .text-danger").remove();

		if(!is_valid){
			$("#PhoneNumber").parents(".form-group").addClass("has-error");
			$(".iti").after("<span class='text-danger'>"+ errorInnerHTML +"</span>");
			return false;
		}
	})
</script>
