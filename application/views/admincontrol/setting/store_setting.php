<?php
	$db =& get_instance();
	$userdetails=$db->userdetails();
	$store_setting =$db->Product_model->getSettings('store');
?>
<div class="row">
	<div class="col-lg-12 col-md-12">
		<?php if($this->session->flashdata('success')){?>
			<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<?php echo $this->session->flashdata('success'); ?> </div>
		<?php } ?>
		<?php if($this->session->flashdata('error')){?>
			<div class="alert alert-danger">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<?php echo $this->session->flashdata('error'); ?> </div>
		<?php } ?>
	</div>
</div>

<form class="form-horizontal" method="post" action=""  enctype="multipart/form-data" id="setting-form">
<div class="row">
	<div class="col-sm-12">
	    <div class="card">
	    	<div class="card-header">
	    		<h4 class="card-title"><?= __('admin.page_title_store_setting') ?></h4>
	    	</div>
	    	<div class="card-body p-0">
	    		<div class="tab-pane p-3" id="store-setting" role="tabpanel">
					<div role="tabpanel">
						
						<ul class="nav nav-pills" role="tablist">
							<li role="presentation" class="active nav-item">
								<a class="nav-link active show" href="#store_main" aria-controls="store_main" role="tab" data-toggle="tab"><?= __('admin.store_setting') ?></a>
							</li>
							<li role="presentation" class="nav-item">
								<a class="nav-link" href="#product_setting" aria-controls="product_setting" role="tab" data-toggle="tab"><?= __('admin.product_setting') ?></a>
							</li>
							
							<li role="presentation" class="nav-item">
								<a class="nav-link" href="#form_setting" aria-controls="form_setting" role="tab" data-toggle="tab"><?= __('admin.form_setting') ?></a>
							</li>
							<li role="presentation" class="nav-item">
								<a class="nav-link" href="#vendor_setting" aria-controls="vendor_setting" role="tab" data-toggle="tab"><?= __('admin.vendor_setting') ?></a>
							</li>
							<li role="presentation" class="nav-item">
								<a class="nav-link" href="#payment_api_documentation" aria-controls="payment_api_documentation" role="tab" data-toggle="tab"><?= __('admin.payment_api_documentation') ?></a>
							</li>
							<li role="presentation" class="nav-item">
								<a class="nav-link" href="#shipping_setting" aria-controls="shipping_setting" role="tab" data-toggle="tab"><?= __('admin.shipping_setting') ?></a>
							</li>
						</ul>
						<hr>
						
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane" id="shipping_setting">
								
								<!-- <div class="card mb-4">
									<div class="card-header"><h6 class="m-0"><?= __('admin.shipping_country') ?></h6></div>
									<div class="card-body">
										<div class="form-group">
											<label class="control-label"><?= __('admin.allow_shipping_in_all_country') ?></label>
											<div>
												<label class="radio-inline"><input type="radio" <?= (int)$shipping_setting['shipping_in_limited'] == 0 ? 'checked' : '' ?> class="shipping_in_limited" name="shipping_setting[shipping_in_limited]" value="0"> 
													<?= __('admin.yes_all_country') ?>
												</label>
												<label class="radio-inline"><input type="radio" <?= (int)$shipping_setting['shipping_in_limited'] == 1 ? 'checked' : '' ?> class="shipping_in_limited" name="shipping_setting[shipping_in_limited]" value="1"> 
													<?= __('admin.no_custom_country') ?>
												</label>
											</div>
										</div>

										<div class="shipping-country-table">

											<div class="form-group">
												<label class="control-label"><?= __('admin.shipping_error_message') ?></label>
												<input type="text" value="<?= $shipping_setting['shipping_error_message'] ?>" name="shipping_setting[shipping_error_message]" class="form-control">
											</div>
											
											<table class="table table-striped ">
												<thead>
													<tr>
														<th><?= __('admin.country') ?></th>
														<th width="50px"></th>
													</tr>
												</thead>
												<tbody>
													
												</tbody>
												<tfoot>
													<tr>
														<td colspan="2" class="text-right">
															<button class="btn btn-shipping-country btn-sm" type="button" ><?= __('admin.add_new_country') ?></button>
														</td>
													</tr>
												</tfoot>
											</table>
										</div>
									</div>
								</div> -->

								<div class="card">
									<div class="card-header"><h6 class="m-0"><?= __('admin.shipping_charge') ?></h6></div>
									<div class="card-body">

										<div class="form-group">
											<label class="control-label"><?= __('admin.allow_shipping_in_all_country') ?></label>
											<div>
												<label class="radio-inline"><input type="radio" <?= (int)$shipping_setting['shipping_in_limited'] == 0 ? 'checked' : '' ?> class="shipping_in_limited" name="shipping_setting[shipping_in_limited]" value="0"> 
													<?= __('admin.yes_all_country') ?>
												</label>
												<label class="radio-inline"><input type="radio" <?= (int)$shipping_setting['shipping_in_limited'] == 1 ? 'checked' : '' ?> class="shipping_in_limited" name="shipping_setting[shipping_in_limited]" value="1"> 
													<?= __('admin.no_custom_country') ?>
												</label>
											</div>
										</div>

										<div class="form-group">
											<label class="control-label"><?= __('admin.shipping_error_message') ?></label>
											<input type="text" value="<?= $shipping_setting['shipping_error_message'] ?>" name="shipping_setting[shipping_error_message]" class="form-control">
										</div>

										<table class="table table-striped shipping-table">
											<thead>
												<tr>
													<th><?= __('admin.country') ?></th>
													<th width="180px"><?= __('admin.shipping_cost') ?></th>
													<th width="50px"></th>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
											<tfoot>
												<tr>
													<td colspan="3" class="text-right">
														<button class="btn btn-shipping-rule btn-sm" type="button" ><?= __('admin.add_new_rule') ?></button>
													</td>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
								
								<script type="text/javascript">
									var shipping_index = 0;
									<?php 
										$country_options = '';
										foreach ($country as $key => $value) { 
											$country_options .= '<option value="'. $value->id .'">'. str_replace("'", '', $value->name) .'</option>';
									 	} 
									?>

									$(".shipping_in_limited").on("change",function(){
										var val = $(this).val();

										$(".shipping-country-table").hide();
										if(val == 1){
											$(".shipping-country-table").show();
										}
									})

									$(".shipping_in_limited:checked").trigger("change");

									function addShippingCountry(country) {
										var shipping_html = '';
										shipping_html += '<tr>';
										shipping_html += '	<td>';
										shipping_html += '		<select name="shipping_setting[allow_country][]" class="form-control">';
										shipping_html += '			<option value="">Choose Country</option>';
										shipping_html += '			<?= $country_options ?>';
										shipping_html += '		</select>';
										shipping_html += '	</td>';
										shipping_html += '	<td>';
										shipping_html += '		<button class="btn btn-danger remove-tr" type="button"><i class="fa fa-trash"></i></button>';
										shipping_html += '	</td>';
										shipping_html += '</tr>';

										$ship = $(shipping_html);
										$ship.find("select").val(country);

										$ship.appendTo(".shipping-country-table tbody");
										shipping_index++;
									}

									$(".btn-shipping-country").click(function(){
										addShippingCountry('');
									})

									$(".shipping-country-table, .shipping-table").delegate(".remove-tr","click", function(){
										$(this).parents("tr").remove();
									})

									$(".btn-shipping-rule").click(function(){
										addShippingRule('',0);
									})

									function addShippingRule(country,cost) {
										var shipping_html = '';
										shipping_html += '<tr>';
										shipping_html += '	<td>';
										shipping_html += '		<select name="shipping_setting[cost]['+ shipping_index +'][country]" class="form-control ssc-'+ shipping_index +'">';
										shipping_html += '			<option value="">Choose Country</option>';
										shipping_html += '			<?= $country_options ?>';
										shipping_html += '		</select>';
										shipping_html += '	</td>';
										shipping_html += '	<td><input type="" name="shipping_setting[cost]['+ shipping_index +'][cost]" onkeydown="if(event.key===\'.\'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,\'\');" class="form-control ssv-'+ shipping_index +'"></td>';
										shipping_html += '	<td>';
										shipping_html += '		<button class="btn btn-danger  remove-tr" type="button"><i class="fa fa-trash"></i></button>';
										shipping_html += '	</td>';
										shipping_html += '</tr>';

										$ship = $(shipping_html);
										$ship.find("select").val(country);
										$ship.find("input").val(cost);

										$ship.appendTo(".shipping-table tbody");
										shipping_index++;
									}

									<?php 

										$allow_country = (array)(isset($shipping_setting['allow_country']) ? json_decode($shipping_setting['allow_country'],1) : []);
										foreach (array_unique($allow_country) as $key => $value) {
											echo "addShippingCountry('". (int)$value ."');";
										}

										$cost = (array)(isset($shipping_setting['cost']) ? json_decode($shipping_setting['cost'],1) : []);
										foreach ($cost as $key => $value) {
											echo "addShippingRule('". (int)$value['country'] ."','". (float)$value['cost'] ."');";
										}
									?>
								</script>
							</div>
							<div role="tabpanel" class="tab-pane active" id="store_main">
								<div class="form-group">
									<label class="control-label"><?= __('admin.status') ?></label>
									<select class="form-control" name="store[status]">
										<option value="0"><?= __('admin.disable') ?></option>
										<option value="1" <?= $store['status'] ? 'selected' : '' ?>><?= __('admin.enable') ?></option>
									</select>
								</div>

								<div class="form-group">
									<label class="control-label">Store Theme</label>
									<select class="form-control" name="store[theme]">
										<option value="0">Default</option>
										<option value="theme1" <?= $store['theme'] == 'theme1' ? 'selected' : '' ?>>Theme 1</option>
									</select>
								</div>
								<div class="form-group">
									<label  class="control-label"><?= __('admin.google_analytics_for_store_page') ?></label>
									<textarea name="store[google_analytics]" class="form-control"><?php echo $store['google_analytics']; ?></textarea>
								</div>
								<fieldset>
									<legend><?= __('admin.store_logo') ?></legend>
									<div class="row">
										<div class="col-sm-6 p-4">
											<?php $img = $store['logo'] ? base_url('assets/images/site/'. $store['logo']) : base_url('assets/vertical/assets/images/users/avatar-1.jpg'); ?>
											<img style="width: 150px;" src="<?= $img ?>" class='img-responsive'>
										</div>
										<div class="col-sm-6">
											<input type="file" name="store_logo">
										</div>
									</div>
								</fieldset>
								<fieldset>
									<legend><?= __('admin.store_favicon_icon') ?></legend>
									<div class="row">
										<div class="col-sm-6 p-4">
											<?php $img = $store['favicon'] ? base_url('assets/images/site/'. $store['favicon']) : base_url('assets/vertical/assets/images/users/avatar-1.jpg'); ?>
											<img style="width: 150px;" src="<?= $img ?>" class='img-responsive'>
										</div>
										<div class="col-sm-6">
											<input type="file" name="store_favicon">
										</div>
									</div>
								</fieldset>
								<div class="form-group">
									<label  class="control-label"><?= __('admin.store_name') ?></label>
									<input  name="store[name]" value="<?php echo $store['name']; ?>" class="form-control"  type="text">
								</div>
								<div class="form-group">
									<label  class="control-label"><?= __('admin.store_title') ?></label>
									<input  name="store[title]" value="<?php echo $store['title']; ?>" class="form-control"  type="text">
								</div>
								<div class="form-group">
									<label  class="control-label"><?= __('admin.store_content') ?></label>
									<textarea name="store[content]" class="form-control summernote"><?php echo $store['content']; ?></textarea>
								</div>
								<div class="form-group">
									<label  class="control-label"><?= __('admin.about_page_content') ?></label>
									<textarea name="store[about_content]" class="form-control summernote"><?php echo $store['about_content']; ?></textarea>
								</div>
								<div class="form-group">
									<label  class="control-label"><?= __('admin.contact_page_content') ?></label>
									<textarea name="store[contact_content]" class="form-control summernote"><?php echo $store['contact_content']; ?></textarea>
								</div>
								<div class="form-group">
									<label  class="control-label"><?= __('admin.policy_page_content') ?></label>
									<textarea name="store[policy_content]" class="form-control summernote"><?php echo $store['policy_content']; ?></textarea>
								</div>
								
								<div class="form-group">
									<label  class="control-label"><?= __('admin.footer_text') ?></label>
									<input name="store[footer]" value="<?php echo $store['footer']; ?>" class="form-control"  type="text">
								</div>
								
								<div class="form-group">
									<label class="control-label"><?= __('admin.recaptcha') ?></label>
									<input name="formsetting[recaptcha]" value="<?php echo $formsetting['recaptcha']; ?>" class="form-control"  type="text">
								</div>
								<a href="https://www.google.com/recaptcha/admin#list" target="_blank"><?= __('admin.google_captcha') ?></a>
							</div>

							<div role="tabpanel" class="tab-pane" id="vendor_setting">
								<div class="form-group">
									<label class="control-label">Vendor Store Status</label>
									<select class="form-control" name="vendor[storestatus]">
										<option value="0"><?= __('admin.disable') ?></option>
										<option value="1" <?= $vendor['storestatus'] ? 'selected' : '' ?>><?= __('admin.enable') ?></option>
									</select>
								</div>

								<div class="form-group">
									<label class="control-label"><?= __('admin.click_commission'); ?></label>
									<div class="form-group">
										<div class="comm-group">
											<div>
												<div class="input-group mt-2">
												  	<div class="input-group-prepend"><span class="input-group-text">Click</span></div>
													<input name="vendor[admin_click_count]"  class="form-control" value="<?php echo $vendor['admin_click_count']; ?>" type="text" placeholder='Clicks'>
												</div>
											</div>
											<div>
												<div class="input-group mt-2">
												  	<div class="input-group-prepend"><span class="input-group-text">$</span></div>
													<input name="vendor[admin_click_amount]" class="form-control" value="<?php echo $vendor['admin_click_amount']; ?>" type="text" placeholder='Amount'>
												</div>
											</div>
										</div>
									</div>								
								</div>
								<div class="form-group">
									<div class="row">
										<div class="col-sm-6">
											<label class="control-label"><?= __('admin.sale_commission'); ?></label>
											<div>
												<?php
													$commission_type= array(
														'percentage' => 'Percentage (%)',
														'fixed'      => 'Fixed',
													);
												?>
												<select name="vendor[admin_sale_commission_type]" class="form-control admin_sale_commission_type">
													<?php foreach ($commission_type as $key => $value) { ?>
														<option <?= $vendor['admin_sale_commission_type'] == $key ? 'selected' : '' ?> value="<?= $key ?>"><?= $value ?></option>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="toggle-container">
												<div class="percentage-value d-none">										
													<div class="form-group">
														<label class="control-label m-0 d-block">&nbsp;</label>
														<input name="vendor[admin_commission_value]" id="admin_commission_value" class="form-control mt-2" value="<?php echo $vendor['admin_commission_value']; ?>" type="text" placeholder='Sale Commission'>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									<script type="text/javascript">
										$("select.admin_sale_commission_type").on("change",function(){
											$con = $(this).parents(".form-group");
											$con.find(".toggle-container .percentage-value, .toggle-container .default-value").addClass('d-none');

											if($(this).val() == 'default'){
												$con.find(".toggle-container .default-value").removeClass("d-none");
											}else{
												$con.find(".toggle-container .percentage-value").removeClass("d-none");
											}
										})
										$("select.admin_sale_commission_type").trigger("change");
									</script>
								</div>
							</div>


							<div role="tabpanel" class="tab-pane" id="product_setting">
								<div class="form-group">
									<label class="control-label"><?= __('admin.commission_type') ?></label>
									<select name="productsetting[product_commission_type]" class="form-control">
										<option value=""><?= __('admin.select_product_commission_type') ?></option>
										<option <?php if($productsetting['product_commission_type'] == 'percentage') { ?> selected <?php } ?> value="percentage">Percentage(%)</option>
										<option <?php if($productsetting['product_commission_type'] == 'Fixed') { ?> selected <?php } ?>  value="Fixed">Fixed</option>
									</select>
								</div>
								<div class="form-group">
									<label class="control-label"><?= __('admin.commission_for_sale') ?></label>
									<input name="productsetting[product_commission]" value="<?php echo $productsetting['product_commission']; ?>" class="form-control"  type="text">
								</div>
								<div class="form-group">
									<label class="control-label"><?= __('admin.commission_for_ppc_visits_view') ?> (<?= $CurrencySymbol ?>)</label>
									<input  name="productsetting[product_ppc]" value="<?php echo $productsetting['product_ppc']; ?>" class="form-control"  type="text">
								</div>
								<div class="form-group">
									<label class="control-label"><?= __('admin.number_of_clicks_per_commission') ?></label>
									<input  name="productsetting[product_noofpercommission]" value="<?php echo $productsetting['product_noofpercommission']; ?>" class="form-control"  type="text">
								</div>

								
								<div class="form-group">
									<label class="control-label">Product Recursion</label>									

									<select name="productsetting[product_recursion]" class="form-control form-group" id="recursion_type">
										<option value="">Select recursion</option>
										<option <?php if($productsetting['product_recursion'] == 'every_day') { ?> selected <?php } ?> value="every_day"><?=  __('admin.every_day') ?></option>
										<option <?php if($productsetting['product_recursion'] == 'every_week') { ?> selected <?php } ?>  value="every_week"><?=  __('admin.every_week') ?></option>
										<option <?php if($productsetting['product_recursion'] == 'every_month') { ?> selected <?php } ?>  value="every_month"><?=  __('admin.every_month') ?></option>
										<option <?php if($productsetting['product_recursion'] == 'every_year') { ?> selected <?php } ?>  value="every_year"><?=  __('admin.every_year') ?></option>
										<option <?php if($productsetting['product_recursion'] == 'custom_time') { ?> selected <?php } ?>  value="custom_time"><?=  __('admin.custom_time') ?></option>
									</select>

								  <div class="custom_time <?php echo ($productsetting['product_recursion'] != 'custom_time') ? 'hide' : '';  ?>">
																		
									<?php
									$minutes = $productsetting['recursion_custom_time'];

									$day = floor ($minutes / 1440);
									$hour = floor (($minutes - $day * 1440) / 60);
									$minute = $minutes - ($day * 1440) - ($hour * 60);
									?>
									<input type="hidden" name="productsetting[recursion_custom_time]" value="<?php echo $minutes; ?>" class="recursion_custom_time">
									<div class="row">
										<div class="col-sm-4">
											<label class="control-label">Days : </label>
											<input placeholder="Days" type="number" class="form-control recur_day" value="<?= $day ? $day : '' ?>" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">

											
										</div>						
										<div class="col-sm-4">
											<label class="control-label">Hours : </label>
											<select class="form-control recur_hour">
												<?php 
												for ($x = 0; $x <= 23; $x++) {
													$selected = ($x == $hour ) ? 'selected="selected"' : '';
													echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
												}
												?>
											</select>
										</div>						
										<div class="col-sm-4">
											<label class="control-label">Minutes : </label>
											<select class="form-control recur_minute">
												<?php 
												for ($x = 0; $x <= 59; $x++) {
													$selected = ($x == $minute ) ? 'selected="selected"' : '';
													echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
												}
												?>
											</select>
										</div>						
									</div>
									<small class="error productsetting_error"></small>
								  </div>

								  	<br>
									<div class="endtime-chooser row">
										<div class="col-sm-12">
											<div class="form-group">
												<label class="control-label d-block"><?= __('admin.choose_custom_endtime') ?> <input <?= $productsetting['recursion_endtime'] ? 'checked' : '' ?>  class='setCustomTime' name='recursion_endtime_status' type="checkbox"> </label>
												<div style="<?= !$productsetting['recursion_endtime'] ? 'display:none' : '' ?>" class='custom_time_container'>
													<input type="text" class="form-control" value="<?= $productsetting['recursion_endtime'] ? date("d-m-Y H:i",strtotime($productsetting['recursion_endtime'])) : '' ?>" name="productsetting[recursion_endtime]" id="endtime" placeholder="Choose EndTime" >
												</div>
											</div>
										</div>
									</div>


								</div>
										   

							</div>
							<div role="tabpanel" class="tab-pane" id="form_setting">
								<div class="form-group">
									<label class="control-label"><?= __('admin.commission_type') ?></label>
									<select name="formsetting[product_commission_type]" class="form-control">
										<option value=""><?= __('admin.select_product_commission_type') ?></option>
										<option <?php if($formsetting['product_commission_type'] == 'percentage') { ?> selected <?php } ?> value="percentage">Percentage(%)</option>
										<option <?php if($formsetting['product_commission_type'] == 'Fixed') { ?> selected <?php } ?>  value="Fixed">Fixed</option>
									</select>
								</div>
								<div class="form-group">
									<label class="control-label"><?= __('admin.commission_for_sale') ?></label>
									<input name="formsetting[product_commission]" value="<?php echo $formsetting['product_commission']; ?>" class="form-control"  type="text">
								</div>
								<div class="form-group">
									<label class="control-label"><?= __('admin.commission_for_ppc_visits_view') ?> (<?= $CurrencySymbol ?>)</label>
									<input  name="formsetting[product_ppc]" value="<?php echo $formsetting['product_ppc']; ?>" class="form-control"  type="text">
								</div>
								<div class="form-group">
									<label class="control-label"><?= __('admin.number_of_clicks_per_commission') ?></label>
									<input  name="formsetting[product_noofpercommission]" value="<?php echo $formsetting['product_noofpercommission']; ?>" class="form-control"  type="text">
								</div>
								<div class="form-group">
									<label class="control-label"><?= __('admin.form_recursion') ?></label>									

									<select name="formsetting[form_recursion]" class="form-control form-group" id="form_recursion_type">
										<option value=""><?= __('admin.select_recursion') ?></option>
										<option <?php if($formsetting['form_recursion'] == 'every_day') { ?> selected <?php } ?> value="every_day"><?=  __('admin.every_day') ?></option>
										<option <?php if($formsetting['form_recursion'] == 'every_week') { ?> selected <?php } ?>  value="every_week"><?=  __('admin.every_week') ?></option>
										<option <?php if($formsetting['form_recursion'] == 'every_month') { ?> selected <?php } ?>  value="every_month"><?=  __('admin.every_month') ?></option>
										<option <?php if($formsetting['form_recursion'] == 'every_year') { ?> selected <?php } ?>  value="every_year"><?=  __('admin.every_year') ?></option>
										<option <?php if($formsetting['form_recursion'] == 'custom_time') { ?> selected <?php } ?>  value="custom_time"><?=  __('admin.custom_time') ?></option>
									</select>

								<div class="custom_time <?php echo ($formsetting['form_recursion'] != 'custom_time') ? 'hide' : '';  ?>">
																		
									<?php
									$form_minutes = $formsetting['recursion_custom_time'];

									$f_day = floor ($form_minutes / 1440);
									$f_hour = floor (($form_minutes - $f_day * 1440) / 60);
									$f_minute = $form_minutes - ($f_day * 1440) - ($f_hour * 60);
									?>
									<input type="hidden" name="formsetting[recursion_custom_time]" value="<?php echo $form_minutes; ?>" class="recursion_custom_time">
									<div class="row">
										<div class="col-sm-4">
											<label class="control-label">Days : </label>
											<input placeholder="Days" type="number" class="form-control recur_day" value="<?= $f_day ? $f_day : '' ?>" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">

										</div>						
										<div class="col-sm-4">
											<label class="control-label">Hours : </label>
											<select class="form-control recur_hour">
												<?php 
												for ($x = 0; $x <= 23; $x++) {
													$selected = ($x == $f_hour ) ? 'selected="selected"' : '';
													echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
												}
												?>
											</select>
										</div>						
										<div class="col-sm-4">
											<label class="control-label">Minutes : </label>
											<select class="form-control recur_minute">
												<?php 
												for ($x = 0; $x <= 59; $x++) {
													$selected = ($x == $f_minute ) ? 'selected="selected"' : '';
													echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
												}
												?>
											</select>
										</div>						
									</div>
									<small class="error formsetting_error"></small>

								</div>
								<br>
								<div class="endtime-chooser row">
									<div class="col-sm-12">
										<div class="form-group">
											<label class="control-label d-block"><?= __('admin.choose_custom_endtime') ?> <input <?= $formsetting['recursion_endtime'] ? 'checked' : '' ?>  class='setCustomTime' name='recursion_endtime_form_status' type="checkbox"> </label>
											<div style="<?= !$formsetting['recursion_endtime'] ? 'display:none' : '' ?>" class='custom_time_container'>
												<input type="text" class="form-control datetime-picker" value="<?= $formsetting['recursion_endtime'] ? date("d-m-Y H:i",strtotime($formsetting['recursion_endtime'])) : '' ?>" name="formsetting[recursion_endtime]" id="endtime" placeholder="Choose EndTime" >
											</div>
										</div>
									</div>
								</div>
								</div>
								
							</div>
							<div role="tabpanel" class="tab-pane" id="payment_api_documentation">
								<div class="clearfix text-right">
									<button type="button" onclick="download()" class="btn btn-export-pdf btn-primary btn-sm">Download As PDF</button>
								</div>
								<br>
								<link rel="stylesheet" type="text/css" href="<?= base_url('assets/integration/prism/css.css') ?>?v=<?= av() ?>">
								<script type="text/javascript" src="<?= base_url('assets/integration/prism/js.js') ?>"></script>
								<script type="text/javascript" src="<?= base_url('assets/plugins/html2canvas/html2canvas.js') ?>"></script>
								<script type="text/javascript" src="<?= base_url('assets/plugins/html2canvas/jspdf.debug.js') ?>"></script>
								<script type="text/javascript">
									function download(){
										$(".no-pdf").hide();
										$(".btn-export-pdf").btn("loading");

										var HTML_Width = $("#doc-html").width();
										var HTML_Height = $("#doc-html").height();
										var top_left_margin = 15;
										var PDF_Width = HTML_Width+(top_left_margin*2);
										var PDF_Height = (PDF_Width*1.5)+(top_left_margin*2);
										var canvas_image_width = HTML_Width;
										var canvas_image_height = HTML_Height;
										
										var totalPDFPages = Math.ceil(HTML_Height/PDF_Height)-1;

										html2canvas($("#doc-html")[0],{allowTaint:true}).then(function(canvas) {
											canvas.getContext('2d');
											
											var imgData = canvas.toDataURL("image/jpeg", 1.0);
											var pdf = new jsPDF('p', 'pt',  [PDF_Width, PDF_Height]);
										    pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);
											
											for (var i = 1; i <= totalPDFPages; i++) { 
												pdf.addPage(PDF_Width, PDF_Height);
												pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
											}
											
										    pdf.save("<?= __('admin.payment_api_documentation') ?>.pdf");

										    $(".no-pdf").show();
										    $(".btn-export-pdf").btn("reset");
								        });
									}
								</script>


								<?php 
									function ___h($text,$lan){
										$text = implode("\n", $text);
										$text = htmlentities($text);
										$text = '<pre class="language-'.$lan.'"><code class="language-'.$lan.'">'.$text.'</code></pre>';
										return $text;
									}

									$base_url  = base_url();
								?>
								<div id="doc-html">
									<div class="row">
										<div class="col-sm-12">
										    <div class="card">
										    	<div class="card-header">
										    		<h4 class="card-title">How to create payment method</h4>
										    	</div>
										    	<div class="card-body payment-doc">
										    		<p>There are 3 payment methods available in the local store itself and. Although sometimes you'll find yourself in the situation where you need something different, either there is no method available for your choice of payment gateway or you want some different logic. In either case, you're left with the only option: To create a new payment method module in Store.</p>

										    		<p>We'll assume that our custom payment method name is "custom". There are at least three files you need to create in order to set up the things. Let's check the same in detail.</p>

										    		<p>You need to create three file. each file are required</p>
										    		<ol>
										    			<li>controller</li>
										    			<li>views</li>
										    			<li>setting</li>
										    		</ol>

										    		<div class="steps">
											    		<div class="steps-header"><h3>Setting Up the Setting File</h3></div>
											    		<div class="steps-body">
												    		<p>Go ahead and create the setting file at <code>application/payments/settings/custom.php</code>. Paste the following contents in the newly created setting file custom.php.</p>
												    		<?php
																$code = array();
																$code[] = '<div class="form-group">';
																$code[] = '	<label class="control-label">Status</label>';
																$code[] = '	<select class="form-control" name="status">';
																$code[] = '		<option <?= (int)$setting_data[\'status\'] == "0" ? "selected" : "" ?> value="0">Disabled</option>';
																$code[] = '		<option <?= (int)$setting_data[\'status\'] == "1" ? "selected" : "" ?> value="1">Enabled</option>';
																$code[] = '	</select>';
																$code[] = '</div>';
																echo ___h($code,'php');
															?>

															<p>in this file you can define all setting for admin. like get status, api key, sanbbox details etc.. all setting data you will get inside <code>setting_data</code> variable. and all your setting are save under setting group name conversation like "storepayment_[payment_method_name]" you can get inside controller file</p>
														</div>
										    		</div>

													<div class="steps">
														<div class="steps-header"><h3>Setting Up the View</h3></div>
														<div class="steps-body">
												    		<p>Go ahead and create the view file at <code>application/payments/views/custom.php</code>. Paste the following contents in the newly created view file custom.php.</p>

												    		<?php
																$code = array();
																$code[] = '<button class="btn btn-default" onclick="backCheckout()">Back</button>';
																$code[] = '<button class="btn btn-primary" id="button-confirm">Confirm</button>';
																$code[] = '<script type="text/javascript">';
																$code[] = '	$("#button-confirm").click(function(){';
																$code[] = '		$this = $(this);';
																$code[] = '		$.ajax({';
																$code[] = '			url:\'<?= $base_url ?>/store/confirm_payment\',';
																$code[] = '			type:"POST",';
																$code[] = '			dataType:"json",';
																$code[] = '			data:{';
																$code[] = '				comment:$(\'textarea[name="comment"]\').val(),';
																$code[] = '			},';
																$code[] = '			beforeSend:function(){';
																$code[] = '				$this.btn("loading");';
																$code[] = '			},';
																$code[] = '			complete:function(){';
																$code[] = '				$this.btn("reset");';
																$code[] = '			},';
																$code[] = '			success:function(json){';
																$code[] = '				if(json[\'redirect\']){';
																$code[] = '					window.location = json[\'redirect\'];';
																$code[] = '				}';
																$code[] = '				if(json[\'warning\']){';
																$code[] = '					alert(json[\'warning\'])';
																$code[] = '				}';
																$code[] = '			},';
																$code[] = '		})';
																$code[] = '	})';
																$code[] = '</script>';


																echo ___h($code,'php');
															?>

															<p>this file is last step of checkout. its confirm order you have to do confirm order on <code>/store/confirm_paymen</code> this url call your confirm method on controller file</p>
														</div>
													</div>

													<div class="steps">
											    		<div class="steps-header"><h3>Setting Up the Controller</h3></div>
											    		<div class="steps-body">
												    		<p>Go ahead and create the controller file at <code>application/payments/controllers/custom.php</code>. Paste the following contents in the newly created controller file custom.php.</p>

												    		<?php
																$code = array();
																$code[] = 'class custom {';
																$code[] = '	public $title = \'Custom name\';';
																$code[] = '	public $icon = "assets/images/payments/custom.png";';
																$code[] = '	public $website = "http:://custom.com";';
																$code[] = '';
																$code[] = '	function __construct($api){ $this->api = $api; }';
																$code[] = '';
																$code[] = '	public function confirm($data) {';
																$code[] = '		$json[\'success\'] = true;';
																$code[] = '		$json[\'redirect\'] = $data[\'thankyou_url\'];';
																$code[] = '		$this->api->confirm_order_api($data[\'order_info\'][\'id\'],7);';
																$code[] = '		return $json;';
																$code[] = '	}';
																$code[] = '';
																$code[] = '	public function getConfirm($data) {';
																$code[] = '		$view = APPPATH.\'payments/views/custom.php\';';
																$code[] = '		require $view;';
																$code[] = '	}';
																$code[] = '';
																$code[] = '	public function getMethod($data){';
																$code[] = '		return array(';
																$code[] = '			\'html\' => \'<p>Custom name</p>\',';
																$code[] = '			\'image\' => \'\',';
																$code[] = '		);';
																$code[] = '	}';
																$code[] = '}';
																echo ___h($code,'php');
															?>

															<div class="func-desc">
																<?php
																	$code = array();
																	$code[] = 'public function getMethod($data){}';
																	echo ___h($code,'php');
																?>
																<p>This function use to get a name or image of payment gateway. image param is optional</p>

																Inside <code>getMethod($data)</code> you will get following values in side $data array
																<ol>
																	<li><code>products</code> : cart products list</li>
																	<li><code>is_logged</code> : customer is login on not</li>
																	<li><code>base_url</code> : base url of store</li>
																	<li><code>sub_total</code> : sub total of cart</li>
																</ol>
															</div>

															<div class="func-desc">
																<?php
																	$code = array();
																	$code[] = 'public function getConfirm($data){}';
																	echo ___h($code,'php');
																?>
																<p>This function use to get a confirm step of your payment method.</p>

																Inside <code>getConfirm($data)</code> you will get following values in side $data array
																<ol>
																	<li><code>products</code> : cart products list</li>
																	<li><code>is_logged</code> : customer is login on not</li>
																	<li><code>base_url</code> : base url of store</li>
																	<li><code>sub_total</code> : sub total of cart</li>
																</ol>
															</div>

															<div class="func-desc">
																<?php
																	$code = array();
																	$code[] = 'public function confirm($data){}';
																	echo ___h($code,'php');
																?>
																<p>This function use to confirm order. functon is call from view file</p>

																Inside <code>confirm($data)</code> you will get following values in side $data array
																<ol>
																	<li><code>base_url</code> : base url of application</li>
																	<li><code>thankyou_url</code> : url of thank you page</li>
																	<li><code>order_info</code> : array of order info</li>
																	<li><code>products</code> : array of order products</li>
																</ol>
															</div>

															<div class="func-desc">
																<?php
																	$code = array();
																	$code[] = '$this->api->confirm_order_api($order_id, $status, $transaction_id = "", $comment = "")';
																	echo ___h($code,'php');
																?>
																<p>this function Use to confirm order. add order history</p>
																<ol>
																	<li><code>order_id</code> : order id you can get from $data['order_info']['id']</li>
																	<li><code>status</code> : status id you can get from below list</li>
																	<li><code>transaction_id</code> : its optional. if you have transaction_id than pass it</li>
																	<li><code>comment</code> : its optional. if have any comment for order than pass it</li>
																</ol>
															</div>
															<div class="func-desc">
																<p>How to get config setting data</p>
																<?php
																	$code = array();
																	$code[] = '$setting = $this->api->Product_model->getSettings(\'storepayment_custom\');';
																	echo ___h($code,'php');
																?>
															</div>
															<div class="func-desc">
																<p>How to call your custom method outside from controllers file</p>
																<p>
																	[base_url]/callbackfunctions/[payment_method_name]/[custom_function_name]/[function_argument]
																</p>
																<p> 
																	For example inside your controller have notify($order_id) method. than your calling url is like this
																	<?php
																		$code = array();
																		$code[] = '$url = base_url("store/callbackfunctions/custom/notify/1");';
																		echo ___h($code,'php');
																	?>
																</p>
															</div>
														</div>
													</div>

													<div class="steps">
														<div class="steps-header"><h3>Order Status ID and Titles</h3></div>
														<div class="steps-body p-0">
															<table class="table-striped table table-sm">
																<tr><th width="90px">Status ID</th><th>Title</th></tr>
																<tr><td>0</td><td>Waiting For Payment</td></tr>
														        <tr><td>1</td><td>Complete</td></tr>
														        <tr><td>2</td><td>Total not match</td></tr>
														        <tr><td>3</td><td>Denied</td></tr>
														        <tr><td>4</td><td>Expired</td></tr>
														        <tr><td>5</td><td>Failed</td></tr>
														        <tr><td>6</td><td>Pending</td></tr>
														        <tr><td>7</td><td>Processed</td></tr>
														        <tr><td>8</td><td>Refunded</td></tr>
														        <tr><td>9</td><td>Reversed</td></tr>
														        <tr><td>10</td><td>Voided</td></tr>
														        <tr><td>11</td><td>Canceled Reversal</td></tr>
															</table>
														</div>
													</div>

										    	</div>
											</div>
									    </div>
									</div>

								</div>								
							</div>
						</div>	
					</div>		
				</div>
	    	</div>
	    	<div class="card-footer text-right">
	    		<button type="submit" class="btn btn-sm btn-primary btn-submit"><?= __('admin.save_settings') ?></button>
	    	</div>
		</div>
    </div>
</div>
</form>

<script type="text/javascript">

	

	$('#endtime,.datetime-picker').datetimepicker({
		format:'d-m-Y H:i',
		inline:true,
	});

	$('.setCustomTime').on('change', function(){
		$parents = $(this).parents(".form-group");
		$parents.find(".custom_time_container").hide();
		if($(this).prop("checked")){
			$parents.find(".custom_time_container").show();
		}
	});

	$(document).on('ready',function() {
		$('.summernote').summernote({
			tabsize: 2,
			height: 400
		});
	});

	$(".btn-submit").on('click',function(evt){
	    evt.preventDefault();

	  
	 //    if( $('.custom_time').is(':visible') ){
		// 	var days = $('#recur_day').val();
		// 	var hours = $('#recur_hour').val();
		// 	var minutes = $('#recur_minute').val();
		// 	var total_minutes;		
			
		// 	total_hours = parseInt(days*24) + parseInt(hours);
		// 	total_minutes = parseInt(total_hours*60) + parseInt(minutes);
		// 	$('.custom_time').find('input[name="productsetting[recursion_custom_time]"]').val(total_minutes);

		// 	if( total_minutes > 0 ){
		// 		$('.custom_time').find('.error').text("");
		// 		error = 0;
		// 	}else{
		// 		$('.custom_time').find('.error').text("Time is required.");
		// 		alert("Recursion Time is required.");
		// 		error++;
		// 	}
		// }else{
		// 	$('.custom_time').find('input[name="productsetting[recursion_custom_time]"]').val(0);
		// }	    

	    
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
	                $(".tab-content").prepend('<div class="alert mt-4 alert-info alert-dismissable">'+ result['success'] +'</div>');
	                var body = $("html, body");
					body.stop().animate({scrollTop:0}, 500, 'swing', function() { });

					$('.formsetting_error').text("");
					$('.productsetting_error').text("");
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

	$(document).on('change', '#recursion_type, #form_recursion_type', function(){
		var recursion_type = $(this).val();		

		if( recursion_type == 'custom_time' ){
			$(this).parent().find('.custom_time').show();
		}else{
			$(this).parent().find('.custom_time').hide();
		}

	});

	$(document).on('change', '.recur_day, .recur_hour, .recur_minute', function(){
		var days = $(this).parents('.custom_time').find('.recur_day').val();
		var hours = $(this).parents('.custom_time').find('.recur_hour').val();
		var minutes = $(this).parents('.custom_time').find('.recur_minute').val();
		var total_minutes;		
		
		total_hours = parseInt(days*24) + parseInt(hours);
		total_minutes = parseInt(total_hours*60) + parseInt(minutes);
		$(this).parents('.custom_time').find('.recursion_custom_time').val(total_minutes);
	});


</script>