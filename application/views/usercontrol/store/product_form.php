<?php
	$db =& get_instance();
	$userdetails=$db->userdetails();
?>

<script type="text/javascript" src="<?= base_url('assets/plugins/ui/jquery-ui.min.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/plugins/ui/jquery-ui.min.css") ?>">

<br>
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

<form class="form-horizontal" method="post" action=""  enctype="multipart/form-data" id="form_form">
	<div class="row">
		<div class="col-sm-8">
			<div class="card">
				<div class="card-header">
					<h4 class="header-title"><?= (int)$product->product_id == 0 ? __('user.lbl_create_product') : __('user.lbl_update_product') ?></h4>
				</div>
				<div class="card-body">
					<input type="hidden" name="product_id" value="<?php echo $product->product_id ?>">

					<div class="form-group">
						<label class="col-form-label"><?= __('user.product_name') ?></label>
						<div>
							<input placeholder="<?= __('user.enter_your_product_name') ?>" name="product_name" value="<?php echo $product->product_name; ?>" class="form-control" type="text">
						</div>
					</div>

					<div class="row">
						<div class="col-sm-8">
							<div class="form-group">
								<label class="col-form-label"><?= __('user.product_price') ?></label>
								<div>
									<input placeholder="<?= __('user.enter_your_product_price') ?>" name="product_price" class="form-control" value="<?php echo $product->product_price; ?>" type="number">
								</div>
							</div>
							<div class="form-group">
								<label class="col-form-label"><?= __('user.product_sku') ?> </label>
								<div>
									<input placeholder="<?= __('user.enter_your_product_sku') ?>" name="product_sku" id="product_sku" class="form-control" value="<?php echo $product->product_sku; ?>" type="text">
								</div>
							</div>
							<div class="form-group" style="display: none;">
								<label class="col-form-label"><?= __('user.product_video_') ?></label>
								<div>
									<input placeholder="<?= __('user.enter_your_product_video_link{youtube/vimeo}') ?>" name="product_video" id="product_video" class="form-control" value="<?php echo $product->product_video; ?>" type="text">
								</div>
							</div>
							<div class="form-group">
								<label class="col-form-label"><?= __('user.short_description') ?></label>
								<div>
									<textarea rows="3" placeholder="<?= __('user.enter_your_product_short_description') ?>" class="form-control" name="product_short_description"  type="text"><?php echo $product->product_short_description; ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-form-label"><?= __('user.categories') ?></label>
								<div class="category-container">
									<input name="category_auto" placeholder="<?= __('user.categories') ?>" id="category_auto" class="form-control" autocomplete="off">
									<ul class="category-selected">
										<?php if(isset($categories)){ ?>
											<?php foreach ($categories as $key => $category) { ?>
												<li>
								            		<i class="fa fa-trash remove-category"></i>
								            		<span><?= $category['name'] ?></span>
								            		<input type="hidden" name="category[]" type="" value="<?= $category['id'] ?>">
								            	</li>
											<?php } ?>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group form-image-group">
								<div>
									<label class="col-form-label"><?= __('user.product_featured_image') ?></label><br>
									<div class="fileUpload btn btn-sm btn-primary">
										<span><?= __('user.choose_file') ?></span>
										<input onchange="readURL(this,'#featureImage')" id="product_featured_image" name="product_featured_image" class="upload" type="file">
									</div>
									<?php $product_featured_image = $product->product_featured_image != '' ? 'assets/images/product/upload/thumb/' . $product->product_featured_image : 'assets/images/no_product_image.png' ; ?>
									<img src="<?php echo base_url($product_featured_image); ?>" id="featureImage" class="thumbnail" border="0" width="220px">
								</div>
							</div>
							
						</div>
					</div>

					<div class="form-group">
						<label class="control-label">Country Location</label>
						<div class="row">
							<div class="col-sm-3">
								<div class="radio">
	                                <label><input type="radio" name="allow_country" value="0" checked=""> <?= __('user.disable'); ?></label> &nbsp;
	                                <label><input type="radio" name="allow_country" value="1" <?= (int)$product->state_id >= 1 ? 'checked' : '' ?> > <?= __('user.enable'); ?></label>
	                            </div>
							</div>
							<div class="col-sm-9">
								<div class="country-chooser">
									<div class="row">
										<div class="col">
											<select class="form-control" name="country_id" id="country_id">
												<option value="0">Select Country</option>
												<?php foreach ($country_list as $key => $value) { ?>
													<option <?= $product_state->country_id == $value->id ? 'selected' : '' ?> value="<?= $value->id ?>"><?= $value->name ?></option>
												<?php } ?>
											</select>
										</div>
										<div class="col">
											<select class="form-control" name="state_id" id="state_id">
												<option value="">Select State</option>
												<?php foreach ($states as $key => $value) { ?>
													<option <?= $product_state->id == $value->id ? 'selected' : '' ?> value="<?= $value->id ?>"><?= $value->name ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
						<script type="text/javascript">
							$("input[name=allow_country]").change(function(){
								if($("input[name=allow_country]:checked").val() == "0"){
									$(".country-chooser").hide();
								} else {
									$(".country-chooser").show();
								}
							})
							$("input[name=allow_country]:checked").trigger('change');

							$("#country_id").on('change',function(){
							    var country = $(this).val();
							    $('#state_id').prop("disabled",true)
							    $.ajax({
							        url: '<?php echo base_url('get_state') ?>',
							        type: 'post',
							        dataType: 'json',
							        data: {country_id : country},
							        success: function (json) {
							        	$('#state_id').prop("disabled",false)
							            if(json){
							                var html = '<option value="">Select State</option>';
							                $.each(json, function(k,v){
							                    html += '<option value="'+v.id+'">'+v.name+'</option>';
							                });
							                $('#state_id').html(html);
							            }
							        }
							    });
							});
						</script>
					</div>

					<div class="form-group">
						<label class="col-form-label"><?= __('user.product_description') ?></label>
						<div>
							<textarea data-height='300px' placeholder="<?= __('user.enter_your_product_description') ?>" class="product_description form-control summernote-img" name="product_description"  type="text"><?php echo $product->product_description; ?></textarea>
						</div>
					</div>

					<div class="row">
						<!-- <div class="col-sm-4">
							<fieldset class="custom-design mb-2">
								<legend><?= __('user.product_sale_commission'); ?></legend>
								<div class="form-group">
									<div>
										<?php
											$selected_commition_type = $product->product_commision_type;
											$selected_commision_value = $product->product_commision_value;
											$commission_type= array(
												'default'    => 'Default',
												'percentage' => 'Percentage (%)',
												'fixed'      => 'Fixed',
											);
										?>
										<select name="product_commision_type" class="form-control">
											<?php foreach ($commission_type as $key => $value) { ?>
												<option <?= $key == $selected_commition_type ? 'selected' : '' ?> value="<?= $key ?>"><?= $value ?></option>
											<?php } ?>
										</select>
									</div>

									<div class="toggle-container">
										<div class="default-value d-none">
											<p class="text-muted">
												<?php
												$commnent_line = "Default Commission ";
												if($setting['product_commission_type'] == 'percentage'){
													$commnent_line .= 'Percentage : '. $setting['product_commission'] .'%';
												}
												else if($setting['product_commission_type'] == 'Fixed'){
													$commnent_line .= 'Fixed : '. $setting['product_commission'];
												}
												echo $commnent_line;
												?>
											</p>
										</div>
										<div class="percentage-value d-none">
											<input placeholder="Enter Product Sale Commission Value " name="product_commision_value" id="product_commision_value" class="form-control mt-2" value="<?php echo $selected_commision_value; ?>" type="text">
										</div>
									</div>
									<script type="text/javascript">
										$("select[name=product_commision_type]").on("change",function(){
											$con = $(this).parents(".form-group");
											$con.find(".toggle-container .percentage-value, .toggle-container .default-value").addClass('d-none');

											if($(this).val() == 'default'){
												$con.find(".toggle-container .default-value").removeClass("d-none");
											}else{
												$con.find(".toggle-container .percentage-value").removeClass("d-none");
											}
										})
										$("select[name=product_commision_type]").trigger("change");
									</script>
								</div>
							</fieldset>
						</div>

						<div class="col-sm-4">
							<fieldset class="custom-design mb-2">
								<legend><?= __('user.product_click_commission'); ?></legend>
								<div class="form-group">
									<div>
										<?php
											$selected_commition_type = $product->product_click_commision_type;
											$product_click_commision_ppc = $product->product_click_commision_ppc;
											$product_click_commision_per = $product->product_click_commision_per;
										?>
										<select name="product_click_commision_type" class="form-control">
											<option <?= 'default' == $selected_commition_type ? 'selected' : '' ?> value="default"><?= __('user.default') ?></option>
											<option <?= 'custom' == $selected_commition_type ? 'selected' : '' ?> value="custom"><?= __('user.custom') ?></option>
										</select>
									</div>
									<div class="toggle-container mt-2">
										<div class="d-none default-value">
											<p class="text-muted">
												<?php
													 echo " PPC : " . $setting['product_noofpercommission'] . " Clicks for: " . c_format($setting['product_ppc']);
												?>
											</p>
										</div>
										<div class="d-none custom-value">
											<div class="row">
												<div class="col-sm-6">
													<div data-tip="<?= __('user.commission_amount') ?>">
														<input placeholder="<?= __('user.commission_amount') ?>" name="product_click_commision_ppc" id="product_click_commision_ppc" class="form-control" value="<?php echo $product_click_commision_ppc; ?>" type="text">
													</div>
												</div>
												<div class="col-sm-6">
													<div data-tip="<?= __('user.number_of_clicks_per_commission') ?>">
														<input placeholder="<?= __('user.number_of_clicks_per_commission') ?>" name="product_click_commision_per" id="product_click_commision_value" class="form-control" value="<?php echo $product_click_commision_per; ?>" type="text">
													</div>
												</div>
											</div>
										</div>
									</div>

									<script type="text/javascript">
										$("select[name=product_click_commision_type]").on("change",function(){
											$con = $(this).parents(".form-group");
											$con.find(".toggle-container .custom-value, .toggle-container .default-value").addClass('d-none');

											if($(this).val() == 'default'){
												$con.find(".toggle-container .default-value").removeClass("d-none");
											}else{
												$con.find(".toggle-container .custom-value").removeClass("d-none");
											}
										})
										$("select[name=product_click_commision_type]").trigger("change");
									</script>
								</div>
							</fieldset>
						</div> -->

						<div class="col-sm-12">
							<fieldset class="custom-design mb-2">
								<legend>Product Recursion</legend>
								<div class="form-group">
									<div>
										<?php
											$product_recursion_type = $product->product_recursion_type;
											$product_recursion = $product->product_recursion;
										?>
										<select name="product_recursion_type" class="form-control">
											<option <?= '' == $product_recursion_type ? 'selected' : '' ?> value=""><?=  __('user.none') ?></option>
											<option <?= 'default' == $product_recursion_type ? 'selected' : '' ?> value="default"><?= __('user.default') ?></option>
											<option <?= 'custom' == $product_recursion_type ? 'selected' : '' ?> value="custom">Custom</option>								
										</select>							
									</div>
									<div class="toggle-container mt-2">
										<div class="d-none default-value">
											<p class="text-muted">
												<?php
													if($setting['product_recursion'] == 'custom_time'){
														echo __('user.default_recursion'). " : " . timetosting($setting['recursion_custom_time']). " | EndTime: " . dateFormat($setting['recursion_endtime']);
													}else{
														echo __('user.default_recursion'). " : " . __('user.'.$setting['product_recursion']). " | EndTime: " . dateFormat($setting['recursion_endtime']);
													}
												?>
											</p>
										</div>

										<div class="d-none custom-value">
											<div class="custom_recursion">
												<div class="form-group">
													<select name="product_recursion" class="form-control" id="recursion_type">
														<option value="">Select recursion</option>
														<option <?php if($product_recursion == 'every_day') { ?> selected <?php } ?> value="every_day"><?=  __('user.every_day') ?></option>
														<option <?php if($product_recursion == 'every_week') { ?> selected <?php } ?>  value="every_week"><?=  __('user.every_week') ?></option>
														<option <?php if($product_recursion == 'every_month') { ?> selected <?php } ?>  value="every_month"><?=  __('user.every_month') ?></option>
														<option <?php if($product_recursion == 'every_year') { ?> selected <?php } ?>  value="every_year"><?=  __('user.every_year') ?></option>
														<option <?php if($product_recursion == 'custom_time') { ?> selected <?php } ?>  value="custom_time"><?=  __('user.custom_time') ?></option>
													</select>
												</div>

												<div class="form-group custom_time">
													<?php
														$minutes = $product->recursion_custom_time;
														$day = floor ($minutes / 1440);
														$hour = floor (($minutes - $day * 1440) / 60);
														$minute = $minutes - ($day * 1440) - ($hour * 60);
													?>

													<input type="hidden" name="recursion_custom_time" value="<?php echo $minutes; ?>">
													<div class="row">
														<div class="col-sm-4">
															<label class="control-label">Days : </label>
															<input placeholder="Days" type="number" class="form-control" value="<?= $day ? $day : '' ?>" id="recur_day" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">
														</div>						
														<div class="col-sm-4">
															<label class="control-label">Hours : </label>
															<select class="form-control" id="recur_hour">
																<?php for ($x = 0; $x <= 23; $x++) {
																	$selected = ($x == $hour ) ? 'selected="selected"' : '';
																	echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
																} ?>
															</select>
														</div>						
														<div class="col-sm-4">
															<label class="control-label">Minutes : </label>
															<select class="form-control" id="recur_minute">
																<?php for ($x = 0; $x <= 59; $x++) {
																	$selected = ($x == $minute ) ? 'selected="selected"' : '';
																	echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
																} ?>
															</select>
														</div>						
													</div>									
												</div>
												<br>
												<div class="endtime-chooser row">
													<div class="col-sm-12">
														<div class="form-group">
															<label class="control-label d-block"><?= __('user.choose_custom_endtime') ?> <input <?= $product->recursion_endtime ? 'checked' : '' ?>  id='setCustomTime' name='recursion_endtime_status' type="checkbox"> </label>
															<div style="<?= !$product->recursion_endtime ? 'display:none' : '' ?>" class='custom_time_container'>
																<input type="text" class="form-control" value="<?= $product->recursion_endtime ? date("d-m-Y H:i",strtotime($product->recursion_endtime)) : '' ?>" name="recursion_endtime" id="endtime" placeholder="Choose EndTime" >
															</div>
														</div>
													</div>
												</div>
											</div>								
										</div>
									</div>

									<script type="text/javascript">
										$("select[name=product_recursion_type]").on("change",function(){
											$con = $(this).parents(".form-group");
											$con.find(".toggle-container .custom-value, .toggle-container .default-value").addClass('d-none');

											if($(this).val() == 'default'){
												$con.find(".toggle-container .default-value").removeClass("d-none");
											}else if($(this).val() == 'custom'){
												$con.find(".toggle-container .custom-value").removeClass("d-none");
											}
										})
										$("select[name=product_recursion_type]").trigger("change");


										$("select[name=product_recursion]").on("change",function(){
											$con = $(this).parents(".custom_recursion");
											$con.find(".custom_time").addClass('d-none');

											if($(this).val() == 'custom_time'){
												$con.find(".custom_time").removeClass("d-none");
											}
										})
										$("select[name=product_recursion]").trigger("change");
									</script>
								</div>
							</fieldset>
						</div>
					</div>

					<fieldset class="custom-design mb-2">
						<legend><?= __('user.product_type'); ?></legend>
						<div class="form-group">
	                        <div>
	                            <label class="radio-inline">
	                                <input type="radio" name="product_type" value="virtual" <?= ($product->product_type == 'virtual' || $product->product_type == '') ? 'checked="checked"' : '' ?> > <?= __('user.virtual_product'); ?>
	                            </label>
	                            &nbsp;
	                            <label class="radio-inline">
	                                <input type="radio" name="product_type" value="downloadable" <?= ($product->product_type == 'downloadable') ? 'checked="checked"' : '' ?> > <?= __('user.downloadable_product'); ?>
	                            </label>
	                            <div class="form-group downloadable_file_div well" style="display: none;">
	                                <div class="file-preview-button btn btn-primary">
	                                    <?= __('user.downloadable_file'); ?>
	                                    <input type="file" class="downloadable_file_input" name="downloadable_files" multiple="">
	                                </div>

	                                <div id="priview-table" class="table-responsive">
	                                    <table class="table table-hover">
	                                        <thead>
	                                            <?php foreach ($downloads as $key => $value) { ?>
	                                                <tr>
	                                                    <td width="70px"> <div class="upload-priview up-<?= $value['type'] ?>" ></div></td>
	                                                    <td>
	                                                        <?= $value['mask'] ?>
	                                                        <input type="hidden" name="keep_files[]" value="<?= $key ?>">
	                                                    </td>
	                                                    <td width="70px"><button type="button" class="btn btn-danger btn-sm remove-priview-server" data-id="'+ i +'" >Remove</button></td>
	                                                </tr>
	                                            <?php } ?>
	                                        </thead>
	                                        <tbody>
	                                            
	                                        </tbody>
	                                    </table>
	                                </div>
	                            </div>
	                        </div>
                		</div>
                   	</fieldset>
					
					<div class="row mt-4">
						<div class="col-sm-3">
							<div class="form-group mb-0">
								<label class="control-label"><?= __('user.allow_comment'); ?></label>
	                            <div class="radio">
	                                <label><input type="radio" name="allow_comment" value="0" checked=""> <?= __('user.disable'); ?></label> &nbsp;
	                                <label><input type="radio" name="allow_comment" value="1" <?= $product->allow_comment ? 'checked' : '' ?> > <?= __('user.enable'); ?></label>
	                            </div>
		                    </div>
						</div>
						<div class="col-sm-3">
							<div class="form-group mb-0 ">
								<label class="control-label"><?= __('user.allow_upload_file'); ?></label>
	                            <div class="radio">
	                                <label><input type="radio" name="allow_upload_file" value="0" checked=""> <?= __('user.disable'); ?></label> &nbsp;
	                                <label><input type="radio" name="allow_upload_file" value="1" <?= $product->allow_upload_file ? 'checked' : '' ?> > <?= __('user.enable'); ?></label>
	                            </div>
		                    </div>
						</div>
						<div class="col-sm-3">
							<div class="form-group mb-0 ">
								<label class="control-label"><?= __('user.show_on_store'); ?></label>
	                            <div class="radio">
	                                <label><input type="radio" name="on_store" value="0" checked=""> <?= __('user.no'); ?></label> &nbsp;
	                                <label><input type="radio" name="on_store" value="1" <?= (int)$product->on_store ? 'checked' : '' ?> > <?= __('user.yes'); ?></label>
	                            </div>
		                    </div>
						</div>
						<div class="col-sm-3">
							<div class="form-group mb-0 ">
								<label class="control-label"><?= __('user.enable_shipping'); ?></label>
	                            <div class="radio">
	                                <label><input type="radio" name="allow_shipping" value="0" checked=""> <?= __('user.disable'); ?></label> &nbsp;
	                                <label><input type="radio" name="allow_shipping" value="1" <?= $product->allow_shipping ? 'checked' : '' ?> > <?= __('user.enable'); ?></label>
	                            </div>
		                    </div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="card commission-setting">
				<div class="card-header"><h4 class="header-title">Commission</h4></div>

				<div class="card-body">

					<div class="form-group">
                        <label class="control-label"><?= __('user.status'); ?> : </label>
                        <?= product_status($product->product_status) ?>	
                    </div>

                 
                    <fieldset class="custom-design mb-2">
						<legend><?= __('admin.commission_for_affiliate'); ?></legend>
						<div class="form-group">
							<label class="control-label"><?= __('admin.click_commission'); ?></label>
							<div>
								<?php
									$commission_type= array(
										'default'    => 'Default',
										'fixed'      => 'Fixed',
									);
								?>
								<select name="affiliate_click_commission_type" class="form-control">
									<?php foreach ($commission_type as $key => $value) { ?>
										<option <?= $seller->affiliate_click_commission_type == $key ? 'selected' : '' ?> value="<?= $key ?>"><?= $value ?></option>
									<?php } ?>
								</select>
							</div>

							<div class="toggle-container">
								<div class="default-value d-none">
									<small class="text-muted d-block">
										<?php
											$commnent_line = "<b>Default Commission: </b>";
											if($seller_setting->affiliate_click_amount && $seller_setting->affiliate_click_count){
												$commnent_line .= c_format($seller_setting->affiliate_click_amount) ." Per ". (int)$seller_setting->affiliate_click_count ." Clicks";
											}
											else{
												$commnent_line .= ' Warning : Default Commission Not Set';
											}
											echo $commnent_line;
										?>
									</small>
								</div>
								<div class="custom-value d-none">										
									<div class="form-group">
										<div class="comm-group">
											<div>
												<div class="input-group mt-2">
												  	<div class="input-group-prepend"><span class="input-group-text">Click</span></div>
													<input name="affiliate_click_count"  class="form-control" value="<?php echo $seller->affiliate_click_count; ?>" type="text" placeholder='Clicks'>
												</div>
											</div>
											<div>
												<div class="input-group mt-2">
												  	<div class="input-group-prepend"><span class="input-group-text">$</span></div>
													<input name="affiliate_click_amount" class="form-control" value="<?php echo $seller->affiliate_click_amount; ?>" type="text" placeholder='Amount'>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<script type="text/javascript">
								$("select[name=affiliate_click_commission_type]").on("change",function(){
									$con = $(this).parents(".form-group");
									$con.find(".toggle-container .percentage-value, .toggle-container .custom-value").addClass('d-none');

									if($(this).val() == 'default'){
										$con.find(".toggle-container .default-value").removeClass("d-none");
									}else{
										$con.find(".toggle-container .custom-value").removeClass("d-none");
									}
								})
								$("select[name=affiliate_click_commission_type]").trigger("change");
							</script>								
						</div>

						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<label class="control-label"><?= __('admin.sale_commission'); ?></label>
									<div>
										<?php
											$commission_type= array(
												'default'    => 'Default',
												'percentage' => 'Percentage (%)',
												'fixed'      => 'Fixed',
											);
										?>
										<select name="affiliate_sale_commission_type" class="form-control">
											<?php foreach ($commission_type as $key => $value) { ?>
												<option <?= $seller->affiliate_sale_commission_type == $key ? 'selected' : '' ?> value="<?= $key ?>"><?= $value ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="toggle-container">
										<div class="default-value d-none">
											<label class="control-label">Default Commission</label>
											<small class="text-muted d-block">
												<?php
													$commnent_line = "";
													if($seller_setting->affiliate_sale_commission_type == ''){
														$commnent_line .= ' Warning : Default Commission Not Set';
													}
													else if($seller_setting->affiliate_sale_commission_type == 'percentage'){
														$commnent_line .= 'Percentage : '. (float)$seller_setting->affiliate_commission_value .'%';
													}
													else if($seller_setting->affiliate_sale_commission_type == 'fixed'){
														$commnent_line .= 'Fixed : '. c_format($seller_setting->affiliate_commission_value);
													}
													echo $commnent_line;
												?>
											</small>
										</div>
										<div class="percentage-value d-none">										
											<div class="form-group">
												<label class="control-label m-0">Sale Commission</label>
												<input name="affiliate_commission_value" id="affiliate_commission_value" class="form-control mt-2" value="<?php echo $seller->affiliate_commission_value; ?>" type="text" placeholder='Sale'>
											</div>
										</div>
									</div>
								</div>
							</div>


							<script type="text/javascript">
								$("select[name=affiliate_sale_commission_type]").on("change",function(){
									$con = $(this).parents(".form-group");
									$con.find(".toggle-container .percentage-value, .toggle-container .default-value").addClass('d-none');

									if($(this).val() == 'default'){
										$con.find(".toggle-container .default-value").removeClass("d-none");
									}else{
										$con.find(".toggle-container .percentage-value").removeClass("d-none");
									}
								})
								$("select[name=affiliate_sale_commission_type]").trigger("change");
							</script>
						</div>
					</fieldset>

					<fieldset class="custom-design mb-2">
						<legend><?= __('admin.commission_for_admin'); ?></legend>

						<div class="form-group mb-1">
							<label class="control-label"><?= __('admin.click_commission'); ?> : </label> 
							<span>
								<?php 
									if((int)$product->product_id == 0 || $seller->admin_click_commission_type == '' || $seller->admin_click_commission_type == 'default'){
										echo c_format($vendor_setting['admin_click_amount']) ." Per ". (int)$vendor_setting['admin_click_count'] ." Clicks";
									} else{ 
										echo c_format($seller->admin_click_amount) ." Per ". (int)$seller->admin_click_count ." Clicks";
									} 
								?>
							</span>
						</div>

						<div class="form-group mb-1">
							<label class="control-label"><?= __('admin.sale_commission'); ?> : </label> 
							<span>
								<?php 
									$commnent_line = "";
									
									if((int)$product->product_id == 0 || $seller->admin_sale_commission_type == '' || $seller->admin_sale_commission_type == 'default'){ 
										if($vendor_setting['admin_sale_commission_type'] == ''){
											$commnent_line .= ' Warning : Default Commission Not Set';
										}
										else if($vendor_setting['admin_sale_commission_type'] == 'percentage'){
											$commnent_line .= 'Percentage : '. (float)$vendor_setting['admin_commission_value'] .'%';
										}
										else if($vendor_setting['admin_sale_commission_type'] == 'fixed'){
											$commnent_line .= 'Fixed : '. c_format($vendor_setting['admin_commission_value']);
										}
									} else if($seller->admin_sale_commission_type == 'percentage'){
										$commnent_line .= 'Percentage : '. (float)$seller->admin_commission_value .'%';
									} else if($seller->admin_sale_commission_type == 'fixed'){
										$commnent_line .= 'Fixed : '. c_format($seller->admin_commission_value);
									} else {
										$commnent_line .= ' Warning : Commission Not Set';
									} 

									echo $commnent_line;

								?>
								
							</span>
						</div>
					</fieldset>

					<fieldset class="custom-design mb-2">
						<legend><?= __('user.finalize_commission'); ?></legend>
						<div class="row">
							<div class="col-sm-4">
								<label class="control-label"><?= __('user.vendor') ?>  <span data-toggle='tooltip' title="<?= __('user.info_lbl_product_owner') ?>"></span></label>
								<input type="text" readonly="" value="0" class="form-control" id="ipt-vendor_commission">
							</div>
							<div class="col-sm-4">
								<label class="control-label"><?= __('user.admin') ?> <span data-toggle='tooltip' title="<?= __('user.info_lbl_admin') ?>"></span></label>
								<input type="text" readonly="" value="0" class="form-control" id="ipt-admin_sale_com">
							</div>
							<div class="col-sm-4">
								<label class="control-label"><?= __('user.affiliate') ?> <span data-toggle='tooltip' title="<?= __('admin.info_lbl_product_other_affiliate') ?>"></span></label>
								<input type="text" readonly="" value="0" class="form-control" id="ipt-affiliate_sale_com">
							</div>
						</div>
					</fieldset>
					
				</div>
			</div>

			<div class="card">
				<div class="card-header"><h4 class="header-title">Admin Comments</h4></div>
				<div class="card-body chat-card">
					<?php $comment = json_decode($seller->comment,1); ?>
					<?php if($comment){ ?>
						<ul class="comment-products">
							<?php foreach ($comment as $key => $value) { ?>
								<li class="<?= $value['from'] == 'affiliate' ? 'me' : 'other' ?>"> <div><?= $value['comment'] ?></div> </li>
							<?php } ?>
						</ul>
					<?php } ?>
					<div class="bg-white form-group m-0 p-2">
						<textarea class="form-control" placeholder="Enter message and save product to send" name="admin_comment"></textarea>
					</div>
				</div>
				<div class="card-footer">
					<div class="text-right">
						<span class="loading-submit"></span>
						<?php if((int)$product->product_id > 0){ ?>
							<button type="submit" class="btn btn-lg btn-default btn-submit btn-success" name="ask_to_review">Send to review</button
								>
						<?php } ?>
						<button type="submit" class="btn btn-lg btn-default btn-submit btn-success" name="save">
							<?= (int)$product->product_id == 0 ? 'Save & Submit For Review' : __('user.save') ?>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>


<script type="text/javascript">
	var cache = {};

	$(".comment-products").animate({ scrollTop: $('.comment-products').prop("scrollHeight")}, 1000);

	$(".commission-setting :input, input[name=product_price]").on("change",calcCommission);
	var xhrCommission;
	function calcCommission(){
		$this = $(this);
		if(xhrCommission && xhrCommission.readyState != 4){
			xhrCommission.abort()
		}

		xhrCommission = $.ajax({
			url:'<?= base_url('usercontrol/calc_commission') ?>',
			type:'POST',
			dataType:'json',
			data:$(".commission-setting :input, input[name=product_price], input[name=product_id]"),
			success:function(json){
				if(json['success']){
					$("#ipt-vendor_commission").val(json['commission']['vendor_commission']);
					$("#ipt-admin_sale_com").val(json['commission']['admin_sale_com']);
					$("#ipt-affiliate_sale_com").val(json['commission']['affiliate_sale_com']);
				}
			},
		})
	}calcCommission();

	$("#category_auto").autocomplete({
        source: function( request, response ) {
	        var term = request.term;
	        if ( term in cache ) {response( cache[ term ] );return;}
	 
	        $.getJSON( '<?= base_url('usercontrol/category_auto') ?>', request, function( data, status, xhr ) {
	          cache[ term ] = data;
	          response( data );
	        });
	    },
        minLength: 0,
        select: function (event, ui) {
            $("#category_auto").blur();
            event.preventDefault();
            if($(".category-selected input[value='"+ ui.item.value +"']").length == 0){
	            $(".category-selected").append('\
	            	<li>\
	            		<i class="fa fa-trash remove-category"></i>\
	            		<span>'+ ui.item.label +'</span>\
	            		<input type="hidden" name="category[]" type="" value="'+ ui.item.value +'">\
	            	</li>\
	        	');
            }
        },
    }).on('focus',function(){
        $(this).data("uiAutocomplete").search($(this).val());
    });

    $(".category-selected").delegate(".remove-category",'click', function(){
    	$(this).parents("li").remove();
    })

	
	/*$(".showonchange").on('change',function(){
		var val = $(this).val();
		$pare = $(this).parents('.row').eq(0);
		$pare.find(".toggle-divs p, .toggle-divs input, .toggle-divs .custom_recursion").hide();
		if(val == 'default'){
			$pare.find(".toggle-divs p").show();
		}else if(val){
			$pare.find(".toggle-divs input").show();
			$pare.find(".toggle-divs .custom_recursion").show();
		}
	})
	$(".showonchange").trigger('change');*/

	function readURLBanner(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#bannerImage').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}

	$(".btn-submit").on('click',function(evt){
        evt.preventDefault();
        $btn = $(this);
        var formData = new FormData($("#form_form")[0]);

        $.each(fileArray, function(i,j){ formData.append("downloadable_file[]", j.rawData); });
        formData.append("action", $(this).attr("name"));
		
        formData = formDataFilter(formData);
        $this = $("#form_form");	       
        
       	$btn.btn("loading");
        $.ajax({
            url:'<?= base_url('usercontrol/store_save_product') ?>',
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
            error:function(){ $btn.btn("reset"); },
            success:function(result){            	
            	$btn.btn("reset");
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
        });
	    
        return false;
    });

    /*$(document).on('change', '#recursion_type', function(){
		var recursion_type = $(this).val();		

		if( recursion_type == 'custom_time' ){
			$('.custom_time').show();
		}else{
			$('.custom_time').hide();
		}

	});*/

	$(document).on('change', '#recur_day, #recur_hour, #recur_minute', function(){
		var days = $('#recur_day').val();
		var hours = $('#recur_hour').val();
		var minutes = $('#recur_minute').val();
		var total_minutes;		
		
		total_hours = parseInt(days*24) + parseInt(hours);
		total_minutes = parseInt(total_hours*60) + parseInt(minutes);
		$('.custom_time').find('input[name="recursion_custom_time"]').val(total_minutes);

	});

	$('#endtime').datetimepicker({
		format:'d-m-Y H:i',
		inline:true,
	});

	$('#setCustomTime').on('change', function(){
		$(".custom_time_container").hide();
		if($(this).prop("checked")){
			$(".custom_time_container").show();
		}
	});

    $(document).on('ready',function() {
        $('input[name="product_type"]:checked').trigger('change');
        $('[name="allow_for"]').trigger("change");
        sumNote($('.summernote-img'));
    });

    var fileArray = [];
    $('.downloadable_file_input').change(function(e){
        $.each(e.target.files, function(index, value){
            var fileReader = new FileReader(); 
            fileReader.readAsDataURL(value);
            fileReader.name = value.name;
            fileReader.rawData = value;
            fileArray.push(fileReader);
        });

        render_priview();
    });

    var getFileTypeCssClass = function(filetype) {
        var fileTypeCssClass;
        fileTypeCssClass = (function() {
            switch (true) {
                case /image/.test(filetype): return 'image';
                case /video/.test(filetype): return 'video';
                case /audio/.test(filetype): return 'audio';
                case /pdf/.test(filetype): return 'pdf';
                case /csv|excel/.test(filetype): return 'spreadsheet';
                case /powerpoint/.test(filetype): return 'powerpoint';
                case /msword|text/.test(filetype): return 'document';
                case /zip/.test(filetype): return 'zip';
                case /rar/.test(filetype): return 'rar';
                default: return 'default-filetype';
            }
        })();
        return fileTypeCssClass;
    };

    function render_priview() {
        var html = '';

        $.each(fileArray, function(i,j){
            html += '<tr>';
            html += '    <td width="70px"> <div class="upload-priview up-'+ getFileTypeCssClass(j.rawData.type) +'" ></div></td>';
            html += '    <td>'+ j.name +'</td>';
            html += '    <td width="70px"><button type="button" class="btn btn-danger btn-sm remove-priview" data-id="'+ i +'" >Remove</button></td>';
            html += '</tr>';
        })

        $("#priview-table tbody").html(html);
    }

    $("#priview-table").delegate('.remove-priview','click', function(){
        if(!confirm("Are you sure ?")) return false;

        var index = $(this).attr("data-id");
        fileArray.splice(index,1);
        render_priview()
    })

    $(".remove-priview-server").on('click',function(){
        if(!confirm("Are you sure ?")) return false;
        $(this).parents("tr").remove();
    })

    $('input[name="product_type"]').on('change',function(){
        var val = $(this).val();
        if(val == 'downloadable'){ 
        	$('.downloadable_file_div').show(); 
        	$('.allow_shipping-option').hide(); 
        }
        else{ 
        	$('.downloadable_file_div').hide(); 
        	$('.allow_shipping-option').show(); 
        }
    });
</script>
				