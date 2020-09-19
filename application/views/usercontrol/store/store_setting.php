<div class="card m-t-30">
	<div class="card-header">
		<h6 class="card-title m-0 pull-left">Vendor Settings</h6>
	</div>
	<form id="setting-form">
		<div class="card-body">

			<?php if($this->session->flashdata('success')){?>
				<div class="alert alert-success alert-dismissable"><?php echo $this->session->flashdata('success'); ?> </div>
			<?php } ?>
			<?php if($this->session->flashdata('error')){?><div class="alert alert-danger alert-dismissable"><?php echo $this->session->flashdata('error'); ?> </div><?php } ?>
			<div class="row">
				<div class="col-lg-12">
					<div class=" m-b-30">
						<div class="form-group">
							<label class="control-label">Other Affiliates Selling My Products?</label>
							<select class="form-control" name="vendor_status">
								<option value="0">No</option>
								<option value="1" <?= (int)$setting['vendor_status'] == 1 ? 'selected' : '' ?>>Yes</option>
							</select>
						</div>

						<fieldset class="custom-design mb-2">
                            <legend>Product Commission</legend>
							<div class="form-group">
								<label class="control-label"><?= __('user.affiliate_click_commission'); ?></label>
								<div class="form-group">
									<div class="comm-group">
										<div>
											<div class="input-group mt-2">
												<div class="input-group-prepend"><span class="input-group-text">Click</span></div>
												<input name="affiliate_click_count"  class="form-control" value="<?php echo $setting['affiliate_click_count']; ?>" type="text" placeholder='Clicks'>
											</div>
										</div>
										<div>
											<div class="input-group mt-2">
												<div class="input-group-prepend"><span class="input-group-text">$</span></div>
												<input name="affiliate_click_amount" class="form-control" value="<?php echo $setting['affiliate_click_amount']; ?>" type="text" placeholder='Amount'>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-sm-6">
										<label class="control-label"><?= __('user.affiliate_sale_commission'); ?></label>
										<div>
											<?php
											$commission_type= array(
												'percentage' => 'Percentage (%)',
												'fixed'      => 'Fixed',
											);
											?>
											<select name="affiliate_sale_commission_type" class="form-control affiliate_sale_commission_type">
												<?php foreach ($commission_type as $key => $value) { ?>
													<option <?= $setting['affiliate_sale_commission_type'] == $key ? 'selected' : '' ?> value="<?= $key ?>"><?= $value ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="toggle-container">
											<div class="percentage-value">										
												<div class="form-group">
													<label class="control-label m-0 d-block">&nbsp;</label>
													<input name="affiliate_commission_value" id="affiliate_commission_value" class="form-control mt-2" value="<?php echo $setting['affiliate_commission_value']; ?>" type="text" placeholder='Sale Commission'>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
		</div>
		<div class="card-footer">
			<button class="btn btn-primary btn-submit">Save Settings</button>
		</div>
	</form>
</div>

<script type="text/javascript">
	$("#setting-form").on('submit',function(evt){
	    evt.preventDefault();	    
    	var formData = new FormData($("#setting-form")[0]);  

	    $(".btn-submit").btn("loading");
	    formData = formDataFilter(formData);
	    $this = $("#setting-form");

	    $.ajax({
	        type:'POST',
	        dataType:'json',
	        cache:false,
	        contentType: false,
	        processData: false,
	        data:formData,
	        success:function(result){
	            $(".btn-submit").btn("reset");
	            $(".alert-dismissable").remove();

	            $this.find(".has-error").removeClass("has-error");
	            $this.find(".is-invalid").removeClass("is-invalid");
	            $this.find("span.text-danger").remove();
	            
	            if(result['location']){
	                window.location = result['location'];
	            }

	            if(result['success']){
	                $("#setting-form .card-body").prepend('<div class="alert mb-4 alert-info alert-dismissable">'+ result['success'] +'</div>');
	                var body = $("html, body");
					body.stop().animate({scrollTop:0}, 500, 'swing', function() { });
	            }

	            if(result['errors']){
	                $.each(result['errors'], function(i,j){
	                    $ele = $this.find('[name="'+ i +'"]');
	                    if(!$ele.length){ 
	                    	$ele = $this.find('.'+ i);
	                    }
	                    if($ele){
	                        $ele.addClass("is-invalid");
	                        $ele.parents(".form-group").addClass("has-error");
	                        $ele.after("<span class='d-block text-danger'>"+ j +"</span>");
	                    }
	                });

					errors = result['errors'];
					$('.formsetting_error').text(errors['formsetting_recursion_custom_time']);
					$('.productsetting_error').text(errors['productsetting_recursion_custom_time']);
	            }
	        },
	    });
		
	    return false;
	});
</script>