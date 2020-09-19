<script type="text/javascript" src='<?= base_url('assets/plugins/color-picker/spectrum.js') ?>'></script>
<link rel='stylesheet' href='<?= base_url('assets/plugins/color-picker/spectrum.css') ?>?v=<?= av() ?>' />

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<div>
					<h5 class="pull-left"><?= __('admin.integration_tools') ?>(<?= ucfirst(str_replace("_", " ", $type)) ?>) </h5>
					<div class="pull-right">
						<a class="btn btn-primary btn-sm" href="<?= base_url('usercontrol/integration_tools') ?>"><?= __('admin.back') ?></a>
					</div>
				</div>
			</div>

			<div class="card-body">
				<form action="" method="get" id="form_tools">
					<ul class="nav nav-tabs">
						<li class="nav-item">
							<a class="nav-link active" data-toggle="tab" href="#home">General Setting</a>
						</li>
						<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#menu1">Level Setting</a></li>
						<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#menu2">Recurring Setting</a></li>
						<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#postback-setting">Postback Setting</a></li>
					</ul>

					<br>
					<div class="tab-content">
						<div class="tab-pane col-sm-12 active" id="home">
							<input type="hidden" name="type" value="<?= $type ?>">
							<input type="hidden" name="program_tool_id" value="<?= isset($tool) ? $tool['id'] : '0' ?>">

							<div class="row">
								<div class="col-sm-7">
									<div class="form-group">
										<label class="control-label"><?= __('admin.tool_type') ?></label>
										<select class="form-control" name="tool_type">
											<option value=""><?= __('admin.select_tool_type') ?></option>
											<option <?= (isset($tool) && $tool['tool_type'] == 'program') ? 'selected' : '' ?> value="program">Program</option>
											<option <?= (isset($tool) && $tool['tool_type'] == 'action') ? 'selected' : '' ?> value="action">Action</option>
											<option <?= (isset($tool) && $tool['tool_type'] == 'general_click') ? 'selected' : '' ?> value="general_click">General Click</option>
										</select>
									</div>

									<div class="form-group">
										<label class="control-label"><?= __('admin.name') ?></label>
										<input class="form-control" value="<?= isset($tool) ? $tool['name'] : '' ?>" name="name" type="text">
									</div>

									<div class="form-group">
										<label class="control-label"><?= __('admin.target_link') ?></label>
										<input class="form-control" value="<?= isset($tool) ? $tool['target_link'] : '' ?>" name="target_link" type="text">
									</div>

									

									<div class="form-group">
										<label class="col-form-label"><?= __('admin.categories') ?></label>
										<div class="category-container">
											<input name="category_auto" placeholder="<?= __('admin.categories') ?>" id="category_auto" class="form-control" autocomplete="off">
											<ul class="category-selected">
												<?php if(isset($categories)){ ?>
													<?php foreach ($categories as $key => $category) { ?>
														<li>
															<i class="fa fa-trash remove-category"></i>
															<span><?= $category['label'] ?></span>
															<input type="hidden" name="category[]" type="" value="<?= $category['value'] ?>">
														</li>
													<?php } ?>
												<?php } ?>
											</ul>
										</div>
									</div>
								</div>
								<div class="col-sm-5">
									<div class="form-group">
				                        <label class="control-label"><?= __('user.status'); ?> : </label>
				                        <?= ads_status($tool['status']) ?>	
				                    </div>

									<label class="control-label">Other Affiliate Commission Settings</label>
									<div class="well">
										<div class="for-program-tool">
											<div class="form-group">
												<label class="control-label"><?= __('admin.select_program') ?> </label>
												<select class="form-control" name="program_id">
													<option value=""><?= __('admin.select_market_program') ?></option>
													<?php foreach ($programs as $key => $program) { ?>
														<option 
															data-commission_type='<?= $program['admin_commission_type'] ?>'
												            data-commission_sale='<?= $program['admin_commission_type'] == 'fixed' ? c_format($program['admin_commission_sale']) : (int)$program['admin_commission_sale'] ."%" ?>'
												            data-commission_number_of_click='<?= $program['admin_commission_number_of_click'] ?>'
												            data-commission_click_commission='<?= c_format($program['admin_commission_click_commission']) ?>'
												            data-click_status='<?= $program['admin_click_status'] ?>'
												            data-sale_status='<?= $program['admin_sale_status'] ?>'
														<?= (isset($tool) && $tool['program_id'] == $program['id']) ? 'selected' : '' ?> value="<?= $program['id'] ?>"><?= $program['name'] ?></option>
													<?php } ?>
												</select>
											</div>

											<div class="form-group">
												<label class="control-label">Admin Commission</label>
												<div class="program-oac"></div>
											</div>

											<script type="text/javascript">
												$('select[name="program_id"]').change(function(){
													var data = $('select[name="program_id"] option:selected').data();
													var string = '';
													if(Object.keys(data).length){
														string += '<b> Click </b> : ';
														if(data['click_status']){
															string += data['commission_click_commission'] + ' Per ' + data['commission_number_of_click'] + " Clicks";
														} else{
															string += 'Disabled';
														}

														string += ' &nbsp; | &nbsp; <b> Sale </b> : ';
														if(data['sale_status']){
															string += data['commission_sale'];
														} else{
															string += 'Disabled';
														}
													} else{
														string += 'Program not selected';
													}

													$(".program-oac").html(string)
												})
												$('select[name="program_id"]').trigger("change")
											</script>
										</div>

										<div class="for-action-tool">
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
														<label class="control-label"><?= __('admin.number_of_action_per_commission') ?></label>
														<input class="form-control" name="action_click" value="<?= isset($tool) ? $tool['action_click'] : '' ?>">
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label class="control-label"><?= __('admin.cost_per_action') ?> ($)</label>
														<input class="form-control" name="action_amount" value="<?= isset($tool) ? $tool['action_amount'] : '' ?>">
													</div>
												</div>
											</div>

											<div class="form-group">
												<label class="control-label">
													<?= __('admin.action_code') ?>
													<span data-toggle="tooltip" data-original-title="Code of action commission should be a string without spaces and special characters that you'll later use in the tracking script to specify that this action should be used."></span>
												</label>
												<input class="form-control" name="action_code" value="<?= isset($tool) ? $tool['action_code'] : '' ?>">
											</div>

											<div class="form-group">
												<label class="control-label">Admin Setting: 
													<?= ($tool['admin_action_amount'] && (int)$tool['admin_action_click']) ? c_format($tool['admin_action_amount']) ." Per ". (int)$tool['admin_action_click'] ." Clicks" : "Not Set" ?>
												</label>
											</div>
										</div>

										<div class="for-general_click-tool">
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
														<label class="control-label"><?= __('admin.number_of_click') ?></label>
														<input class="form-control" name="general_click" value="<?= isset($tool) ? $tool['general_click'] : '' ?>">
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label class="control-label"><?= __('admin.cost_per_click') ?>($)</label>
														<input class="form-control" name="general_amount" value="<?= isset($tool) ? $tool['general_amount'] : '' ?>">
													</div>
												</div>
											</div>

											<div class="form-group">
												<label class="control-label"><?= __('admin.general_code') ?>
													<span data-toggle="tooltip" data-original-title="Code of general click should be a string without spaces and special characters that you'll later use in the tracking script to specify that this general click should be used."></span>
												</label>
												<input class="form-control" name="general_code" value="<?= isset($tool) ? $tool['general_code'] : '' ?>">
											</div>

											<div class="form-group">
												<label class="control-label">Admin Setting: 
													<?= ($tool['admin_general_amount'] && (int)$tool['admin_general_click']) ? c_format($tool['admin_general_amount']) ." Per ". (int)$tool['admin_general_click'] ." Clicks" : "Not Set" ?>
												</label>
											</div>
										</div>
									</div>

									<div class="card mt-3">
										<div class="card-header "><p class="m-0">Vendor Comments</p></div>
										<div class="card-body chat-card">
											<?php $comment = json_decode($tool['comment'],1); ?>
											<?php if($comment){ ?>
												<ul class="comment-products">
													<?php foreach ($comment as $key => $value) { ?>
														<li class="<?= $value['from'] == 'affiliate' ? 'me' : 'other' ?>"> <div><?= $value['comment'] ?></div> </li>
													<?php } ?>
												</ul>
											<?php } else echo '<ul class="comment-products"></ul>'; ?>
											<div class="bg-white form-group m-0 p-2">
												<textarea class="form-control" placeholder="Enter message and save program to send" name="comment"></textarea>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label d-block"><?= __('admin.featured_image') ?></label>

								<div class="fileUpload btn btn-sm btn-primary">
									<span><?= __('admin.choose_file') ?></span>
									<input onchange="readURL(this,'#featured_image')" id="product_featured_image" name="featured_image" class="upload" type="file">
								</div>

								<?php $featured_image = $tool['featured_image'] != '' ? 'assets/images/product/upload/thumb/' . $tool['featured_image'] : 'assets/images/no_product_image.png' ; ?>
								<input type="hidden" name="old_featured_image" value="<?= $tool['featured_image'] ?>">
								<img src="<?php echo base_url($featured_image); ?>" id='featured_image' class="thumbnail" border="0" width="100px">
							</div>

							<?php if($type == 'banner'){ ?>
								<div class="well">
									<div class="bg-white p-3">
										<h5><?= __('admin.banner_images') ?></h5>

										<div class="table-responsive">
											<table class="table banner-table">
												<thead>
													<tr>
														<th><?= __('admin.image') ?></th>
														<th width="180px"><?= __('admin.size') ?></th>
														<th width="50px"></th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($tool['ads'] as $key => $ads) { ?>
														<tr>
															<td>
																<input type="hidden" name="keep_ads[]" value="<?= $ads['id'] ?>">
																<img src="<?= $ads['value'] ?>">
																<input type="file" accept="image/*" class="file-input" name="custom_banner[]">
															</td>
															<td><input type="text"  class="form-control size-input" value="<?= $ads['size'] ?>" readonly="" name="custom_banner_size[]"></td>
															<td><button type="button" class="btn btn-sm btn-danger remove-custom-image"><i class="fa fa-trash"></i></button></td>
														</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>

										<div class="text-right">
											<button type="button" class="btn add-banner btn-primary btn-sm"> <?= __('admin.add_banner') ?></button>
										</div>
									</div>
								</div>
							<?php } else if($type == 'text_ads'){ ?>
								<?php 
								$_text_ads = isset($tool['ads'][0]) ? $tool['ads'][0] : array();
								?>
								<div class="form-group">
									<label class="control-label"><?= __('admin.content') ?></label>
									<textarea class="form-control" rows="10" name="text_ads_content"><?= isset($_text_ads['value']) ? $_text_ads['value'] : '' ?></textarea>
								</div>

								<div class="row">
									<div class="col-sm-12">
										<label class="control-label"><?= __('admin.text_size_px') ?></label>
										<input class="form-control" name="text_size" value="<?= isset($_text_ads['text_size']) ? $_text_ads['text_size'] : '' ?>">
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-sm-4">
										<label class="control-label"><?= __('admin.text_color') ?></label>
										<input class="form-control color-picker" name="text_color" value="<?= isset($_text_ads['text_color']) ? $_text_ads['text_color'] : '' ?>">
									</div>
									<div class="col-sm-4">
										<label class="control-label"><?= __('admin.background_color') ?></label>
										<input class="form-control color-picker" name="text_bg_color" value="<?= isset($_text_ads['text_bg_color']) ? $_text_ads['text_bg_color'] : '' ?>">
									</div>
									<div class="col-sm-4">
										<label class="control-label"><?= __('admin.border_color') ?></label>
										<input class="form-control color-picker" name="text_border_color" value="<?= isset($_text_ads['text_border_color']) ? $_text_ads['text_border_color'] : '' ?>">
									</div>	
								</div>

							<?php } else if($type == 'link_ads'){ ?>
								<?php 
								$link_ads = isset($tool['ads'][0]) ? $tool['ads'][0] : array();
								?>
								<div class="form-group">
									<label class="control-label"><?= __('admin.link_title') ?></label>
									<input class="form-control" name="link_title" value="<?= isset($link_ads['value']) ? $link_ads['value'] : '' ?>">
								</div>

							<?php } else if($type == 'video_ads'){ ?>
								<?php 
								$video_ads = isset($tool['ads'][0]) ? $tool['ads'][0] : array();
								?>
								<div class="form-group">
									<label class="control-label"><?= __('admin.video_link') ?></label>
									<div class="video-url-input">
										<input class="form-control parse-video" name="video_link" value="<?= isset($video_ads['value']) ? $video_ads['value'] : '' ?>">

										<input class="form-control video-priview" readonly="" >
									</div>
									<div class="clearfix"></div>
								</div>

								<div class="form-group">
									<label class="control-label">Autoplay</label>
									<div>
										<label class="radio-inline"> <input type="radio" checked="" name="autoplay" value="0"> <?= __('admin.disable') ?> </label>
										<label class="radio-inline"> <input type="radio" <?= (isset($video_ads) && $video_ads['autoplay']) ? 'checked' : '' ?> name="autoplay" value="1"> <?= __('admin.enable') ?> </label>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-6">
										<label class="control-label"><?= __('admin.height_px') ?></label>
										<input class="form-control" name="video_height" value="<?= isset($video_ads['video_height']) ? $video_ads['video_height'] : '' ?>">
									</div>
									<div class="col-sm-6">
										<label class="control-label"><?= __('admin.width_px') ?></label>
										<input class="form-control" name="video_width" value="<?= isset($video_ads['video_width']) ? $video_ads['video_width'] : '' ?>">
									</div>	
								</div>

								<br>

								<div class="form-group">
									<label class="control-lable"><?= __('admin.button_text') ?></label>
									<input class="form-control" name="button_text" value="<?= isset($video_ads['size']) ? $video_ads['size'] : '' ?>">
								</div>	

							<?php } ?>


							<?php  $allow_for = array_filter(explode(",", $tool['allow_for'])); ?>
							<div class="form-group">
								<label class="control-label"><?= __('admin.allow_for') ?></label>
								<div>
									<label class="radio-inline">
										<input type="radio" <?= count($allow_for) == 0 ? 'checked' : ''  ?> name="allow_for_radio" class="allow_for" value="0"> <?= __('admin.all') ?>
									</label>
									<label class="radio-inline">
										<input type="radio" <?= count($allow_for) > 0 ? 'checked' : ''  ?> name="allow_for_radio" class="allow_for" value="1"> <?= __('admin.selected_affiliate') ?>
									</label>
								</div>
							</div>

							<div class="show-allow_for">
								<div class="bg-light p-3 border" style="height: 200px;overflow: auto;">
									<?php foreach ($users as $v) { ?>
										<label class="d-block">
											<input type="checkbox" <?= in_array($v['id'],$allow_for) ? 'checked' : '' ?> name="allow_for[]" value="<?= $v['id'] ?>"> <?= $v['name'] ?>
										</label>
									<?php } ?>
								</div>
							</div>
							<script type="text/javascript">
								$(".allow_for").on('change',function(){
									$(".show-allow_for").hide();

									if($(this).val() == '1'){
										$(".show-allow_for").show();
									}
								})
								$(".allow_for:checked").trigger("change");
							</script>
						</div>
						<div class="tab-pane col-sm-12 fade" id="menu2">
							<div class="form-group">
								<label for="example-text-input" class="control-label">Recursion</label>
								<?php  $recursion = $tool['recursion'];  ?>

								<select name="recursion" class="form-control" id="recursion_type">
									<option value="">Select recursion</option>
									<option <?php if($recursion == 'every_day') { ?> selected <?php } ?> value="every_day"><?=  __('admin.every_day') ?></option>
									<option <?php if($recursion == 'every_week') { ?> selected <?php } ?>  value="every_week"><?=  __('admin.every_week') ?></option>
									<option <?php if($recursion == 'every_month') { ?> selected <?php } ?>  value="every_month"><?=  __('admin.every_month') ?></option>
									<option <?php if($recursion == 'every_year') { ?> selected <?php } ?>  value="every_year"><?=  __('admin.every_year') ?></option>
									<option <?php if($recursion == 'custom_time') { ?> selected <?php } ?>  value="custom_time"><?=  __('admin.custom_time') ?></option>
								</select>
							</div>
							<div class="form-group custom_time <?php echo ($recursion != 'custom_time') ? 'hide' : '';  ?>">

								<?php
								$minutes = $tool['recursion_custom_time'];

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
										<select class="form-control" id="recur_minute">
											<?php 
											for ($x = 0; $x <= 59; $x++) {
												$selected = ($x == $minute ) ? 'selected="selected"' : '';
												echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
											}
											?>
										</select>
									</div>                      
								</div>                                  
							</div>

							<br>
							<div class="endtime-chooser row">
								<div class="col-sm-12">
									<div class="form-group">
										<label class="control-label d-block"><?= __('admin.choose_custom_endtime') ?> <input <?= $tool['recursion_endtime'] ? 'checked' : '' ?>  id='setCustomTime' name='recursion_endtime_status' type="checkbox"> </label>
										<div style="<?= !$tool['recursion_endtime'] ? 'display:none' : '' ?>" class='custom_time_container'>
											<input type="text" class="form-control" value="<?= $tool['recursion_endtime'] ? date("d-m-Y H:i",strtotime($tool['recursion_endtime'])) : '' ?>" name="recursion_endtime" id="endtime" placeholder="Choose EndTime" >
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane col-sm-12 fade" id="postback-setting">
							<?php $marketpostback = json_decode($tool['marketpostback'],1); ?>
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
										<label class="control-label">Postback Status</label>
										<select class="form-control marketpostback-status" name="marketpostback[status]">
											<option value=""><?= __('admin.disable') ?></option>
											<option value="default" <?= $marketpostback['status'] == 'default' ? 'selected' : '' ?>><?= __('admin.default') ?></option>
											<option value="custom" <?= $marketpostback['status'] == 'custom' ? 'selected' : '' ?>><?= __('admin.custom') ?></option>
										</select>
									</div>
									<div class="marketpostback-default m-2">
										<div class="card">
											<div class="card-header"><h6 class="m-0">DEFAULT POSTBACK SETTINGS</h6></div>
											<div class="card-body">
												<div>
													<b>Status:</b> <?= (int)$default_marketpostback['status'] == 1 ?  __('admin.enable') : __('admin.disable') ?>
												</div>

												<div>
													<b>Postback URL:</b> <?= $default_marketpostback['url'] ? $default_marketpostback['url'] : 'N/A' ?>
												</div>

												<?php 
												$marketpostback_dynamicparam = json_decode($default_marketpostback['dynamicparam'],1);
												$marketpostback_static = json_decode($default_marketpostback['static'],1);
												$dynamicparam = [
													'city' => 'City',
													'regionCode' => 'Region Code',
													'regionName' => 'Region Name',
													'countryCode' => 'Country Code',
													'countryName' => 'Country Name',
													'continentName' => 'Continent Name',
													'timezone' => 'Timezone',
													'currencyCode' => 'Currency Code',
													'currencySymbol' => 'Currency Symbol',
													'ip' => 'IP',
													'id' => 'ID (Sale ID OR Click ID)',
												];
												?>
												<div>
													<b>Dynamic Params</b> 
													<ol>
														<?php foreach ($marketpostback_dynamicparam as $key => $value) { ?>
															<li><?= $dynamicparam[$value] ?></li>
														<?php } ?>
													</ol>									
												</div>

												<div>
													<b>Static Params</b> 
													<ol>
														<?php foreach ($marketpostback_static as $key => $value) { ?>
															<li>
																<b><?= $value['key'] ?></b>: 
																<span><?= $value['value'] ?></span>
															</li>
														<?php } ?>
													</ol>
												</div>
											</div>
										</div>
									</div>
									<div class="marketpostback-custom">
										<div class="form-group">
											<label class="control-label">Postback URL</label>
											<input type="text" name="marketpostback[url]" value="<?= $marketpostback['url'] ?>" class="form-control marketpostback-url">
										</div>
										<div class="form-group">
											<label class="control-label">Dynamic Params</label>
											<div>
												<?php 
												$dynamicparam = [
													'city' => 'City',
													'regionCode' => 'Region Code',
													'regionName' => 'Region Name',
													'countryCode' => 'Country Code',
													'countryName' => 'Country Name',
													'continentName' => 'Continent Name',
													'timezone' => 'Timezone',
													'currencyCode' => 'Currency Code',
													'currencySymbol' => 'Currency Symbol',
													'ip' => 'IP',
													'type' => 'Type action,general_click,product_click,sale',
													'id' => 'ID (Sale ID OR Click ID)',
												];
												$marketpostback_dynamicparam = $marketpostback['dynamicparam'];
												$marketpostback_static = $marketpostback['static'];
												?>
												<div class="row">
													<?php foreach ($dynamicparam as $key => $value) { ?>
														<div class="col-sm-3">
															<label class="checkbox font-weight-light">
																<input type="checkbox" <?= isset($marketpostback_dynamicparam[$key]) ? 'checked' : '' ?> name="marketpostback[dynamicparam][<?= $key ?>]" value="<?= $key ?>">
																<span> <b><?= $key ?></b> - <?= $value ?> </span>
															</label>
														</div>
													<?php } ?>
												</div>
											</div>
										</div>

										<div class="card">
											<div class="card-header">
												<h6 class="card-title m-0">Static Params</h6>
											</div>
											<div class="card-body p-0">
												<div class="static-params table-responsive">
													<table class="table table-striped table-bordered ">
														<thead>
															<tr>
																<td>Param Key</td>
																<td>Param Value</td>
																<td width="50px">#</td>
															</tr>
														</thead>
														<tbody></tbody>
														<tfoot>
															<tr>
																<td colspan="3"><button class="pull-right btn btn-sm btn-primary add-static-params" type="button">Add</button></td>
															</tr>
														</tfoot>
													</table>
												</div>
											</div>
										</div>

										<script type="text/javascript">
											$(".add-static-params").click(function(){
												addStaticParam('','');
											})

											<?php foreach ($marketpostback_static as $key => $value) {
												echo "addStaticParam('". $value['key'] ."','". $value['value'] ."');";
											} ?>

											var addStaticParamIndex = 0;
											function addStaticParam(key,val) {
												var html = `<tr>
												<td>
												<input type="text" value='${key}' name="marketpostback[static][${addStaticParamIndex}][key]" placeholder="Param Key" class="form-control">
												</td>
												<td>
												<input type="text" name="marketpostback[static][${addStaticParamIndex}][value]" value='${val}' placeholder="Param Value" class="form-control">
												</td>
												<td>
												<button class="pull-right btn btn-sm btn-danger remove-static-params" type="button"><i class="fa fa-trash"></i></button>
												</td>
												</tr>`;

												addStaticParamIndex++;
												$(".static-params tbody").append(html);
											}

											$(".static-params").delegate(".remove-static-params","click",function(){
												$(this).parents("tr").remove();
											})
										</script>
									</div>

									<script type="text/javascript">
										$(".marketpostback-status").change(function(){
											var val = $(this).val();
											$(".marketpostback-default, .marketpostback-custom").hide();

											if(val == 'default') $(".marketpostback-default").show();
											else if(val == 'custom') $(".marketpostback-custom").show();
										})
										$(".marketpostback-status").trigger("change");
									</script>
								</div>
							</div>
						</div>
						<div class="tab-pane col-sm-12 fade" id="menu1">
							<div class="form-group">
								<label class="control-label">Commission Type </label>
								<select class="form-control" name="commission_type">
									<option value="">Default</option>
									<option <?= (isset($tool) && $tool['commission_type'] == 'custom') ? 'selected' : '' ?> value="custom">Custom</option>
								</select>
							</div>

							<div class="commi-cube">

								<div class="new-comm">
									<div class="table-responsive">
										<table class="table" id="tbl_refer_level">
											<thead>
												<tr>
													<th>Level</th>
													<th>Number of click per commission</th>
													<th>Sale Commission 
														<select class="form-control refer-symball-select" name="referlevel[sale_type]">
															<option symbal='%' <?php if($referlevel['sale_type'] == 'percentage') { ?> selected <?php } ?> value="percentage">Percentage(%)</option>
															<option symbal='<?= $CurrencySymbol ?>' <?php if($referlevel['sale_type'] == 'fixed') { ?> selected <?php } ?>  value="fixed">Fixed</option>
														</select>
													</th>
													<th>External Click</th>
													<th>External Action Click</th>
												</tr>
											</thead>
											<tbody>
												<?php for ($level =1; $level <= $max_level; $level++) { ?>
													<tr>
														<td><?= $level ?></td>
														<td><input type="number" step="any" name="referlevel_<?= $level ?>[commition]" value="<?php echo ${"referlevel_". $level}['commition'] ?>" class="form-control" /></td>
														<td>
															<div class="input-group">
																<input type="number" step="any" name="referlevel_<?= $level ?>[sale_commition]" value="<?php echo ${"referlevel_". $level}['sale_commition'] ?>" class="form-control" />
																<div class="input-group-append"><span class="input-group-text refer-symball"></span></div>
															</div>
														</td>
														<td>
															<div class="input-group">
																<input type="number" step="any" name="referlevel_<?= $level ?>[ex_commition]" value="<?php echo ${"referlevel_". $level}['ex_commition'] ?>" class="form-control" />
																<div class="input-group-append"><span class="input-group-text"><?= $CurrencySymbol ?></span></div>
															</div>
														</td>
														<td>
															<div class="input-group">
																<input type="number" step="any" name="referlevel_<?= $level ?>[ex_action_commition]" value="<?php echo ${"referlevel_". $level}['ex_action_commition'] ?>" class="form-control" />
																<div class="input-group-append"><span class="input-group-text"><?= $CurrencySymbol ?></span></div>
															</div>
														</td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>	
			</div>

			<div class="card-footer text-right">
				<?php if(isset($tool['id'])){ ?>
					<a class="get-code btn btn-info" href="javascript:void(0)" data-id="<?= $tool['id'] ?>"><?= __('admin.get_code') ?></a>
				<?php } ?>
				<button class="btn btn-primary btn-save save-n-close"><span class="loading-submit"></span> <?= __('admin.save') ?></button>
				<button class="btn btn-primary btn-save "><span class="loading-submit"></span> <?= __('admin.save_close') ?></button>
			</div>
		</div>
	</div>
</div>



<div class="modal fade" id="integration-code">
	<div class="modal-dialog">
		<div class="modal-content"></div>
	</div>
</div>

<script type="text/javascript" src="<?= base_url('assets/plugins/ui/jquery-ui.min.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/plugins/ui/jquery-ui.min.css") ?>">
<script type="text/javascript">
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

	$("select[name=commission_type]").on('change',function(){
		$(".commi-cube").hide();
		if($(this).val() == 'custom'){
			$(".commi-cube").show();
		}
	})

	$("select[name=commission_type]").trigger("change");

	function chnage_teigger() {
		var symbal = $(".refer-symball-select").find("option:selected").attr("symbal");
		$(".refer-symball").html(symbal);
	}
	$(".refer-symball-select").change(chnage_teigger)
	chnage_teigger();

	$('[name="tool_type"]').on('change',function(){
		$(".for-action-tool,.for-program-tool,.for-general_click-tool").hide()
		$(".for-"+ $(this).val() +"-tool").show()
	})
	$('[name="tool_type"]').trigger("change");


	$(".parse-video").on('keyup',function(){
		var url = $(this).val();
		url.match(/(http:|https:|)\/\/(player.|www.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com))\/(video\/|embed\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/);

		if (RegExp.$3.indexOf('youtu') > -1) {
			var type = 'Youtube';
		} else if (RegExp.$3.indexOf('vimeo') > -1) {
			var type = 'Vimeo';
		}

		$(".video-priview").val(type);
	})
	$(".parse-video").trigger("keyup");


	$(".add-banner").on('click',function(){
		if($(".banner-table tbody tr").length < 5){

			$(".banner-table tbody").append('<tr>\
				<td>\
				<img >\
				<input type="file" accept="image/*" class="file-input" name="custom_banner[]">\
				</td>\
				<td><input type="text"  class="form-control size-input" readonly="" name="custom_banner_size[]"></td>\
				<td><button type="button" class="btn btn-sm btn-danger remove-custom-image"><i class="fa fa-trash"></i></button></td>\
				</tr>');
		}

		if($(".banner-table tbody tr").length >= 5){
			$(".add-banner").hide();
		}
	})

	$(".banner-table tbody").delegate(".remove-custom-image","click",function(){
		if(!confirm("Are you sure ?")) return false;

		$(".add-banner").show();
		$(this).parents("tr").remove();
	})

	$(".banner-table tbody").delegate(".file-input","change",function(){
		var input = this;
		$this = $(this);

		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function(e) {
				$tr = $this.parents("tr");
				var img = new Image;

				img.onload = function() {
					$tr.find(".size-input").val( img.width + " x " + img.height );
				};
				img.src = e.target.result;
				$tr.find("img").attr('src', e.target.result)
				$tr.find("[name=keep_ads]").val('0');
			}

			reader.readAsDataURL(input.files[0]);
		}
	});


	$(".btn-save").on('click',function(){
		$btn = $(this);
		$this = $("#form_tools");

		var formData = new FormData($this[0]);
		if($(this).hasClass('save-n-close')){
			formData.append("save_close",true);
		}
		formData = formDataFilter(formData);
		$btn.prop("disabled",true);


		$.ajax({
			url:'<?= base_url('usercontrol/integration_tools_form_post') ?>',
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
						$btn.find('.loading-submit').text(percentComplete + "%").show();
					}
				}, false );

				jqXHR.addEventListener( "progress", function ( evt ){
					if ( evt.lengthComputable ){
						var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
						$btn.find('.loading-submit').hide();
					}
				}, false );
				return jqXHR;
			},
			error:function(){
				$btn.find('.loading-submit').hide();
				$btn.prop("disabled",false);
			},
			success:function(result){
				$btn.find('.loading-submit').hide();
				$btn.prop("disabled",false);
				$this.find(".has-error").removeClass("has-error");
				$this.find("span.text-danger").remove();


				if(result['location']){ window.location = result['location']; }

				if(result['errors']){
					$.each(result['errors'], function(i,j){
						$ele = $this.find('[name="'+ i +'"]');
						if(!$ele.length) $ele = $this.find('.'+ i)
							if($ele){
								$ele.parents(".form-group").addClass("has-error");
								$ele.after("<span class='text-danger'>"+ j +"</span>");
							}
						});
				}
			},
		})
	});

	$(document).on('change', '#recursion_type', function(){
		var recursion_type = $(this).val();     

		if( recursion_type == 'custom_time' ){
			$('.custom_time').show();
		}else{
			$('.custom_time').hide();
		}
	});

	$(document).on('change', '#recur_day, #recur_hour, #recur_minute', function(){
		var days = $('#recur_day').val();
		var hours = $('#recur_hour').val();
		var minutes = $('#recur_minute').val();
		var total_minutes;      

		total_hours = parseInt(days*24) + parseInt(hours);
		total_minutes = parseInt(total_hours*60) + parseInt(minutes);
		$('.custom_time').find('input[name="recursion_custom_time"]').val(total_minutes);

	});

	$(".color-picker").spectrum({
		showInput: true,
		showInitial: true,
		showPalette: true,
		showSelectionPalette: true,
		showAlpha: true,
		maxPaletteSize: 10,
		preferredFormat: "hex",
		palette: [
		["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)","rgb(204, 204, 204)", "rgb(217, 217, 217)","rgb(255, 255, 255)"],
		["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
		"rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
		["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)",
		"rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)",
		"rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)",
		"rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)",
		"rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)",
		"rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
		"rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
		"rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
		"rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)",
		"rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
		]
	});

	$(".get-code").on('click',function(){
		$this = $(this);
		$.ajax({
			url:'<?= base_url("usercontrol/tool_get_code") ?>',
			type:'POST',
			dataType:'json',
			data:{id:$this.attr("data-id")},
			beforeSend:function(){ $this.btn("loading"); },
			complete:function(){ $this.btn("reset"); },
			success:function(json){
				if(json['html']){
					$("#integration-code .modal-content").html(json['html']);
					$("#integration-code").modal("show");
				}
			},
		})
	});

	var cache ={};
	$("#category_auto").autocomplete({
		source: function( request, response ) {
			var term = request.term;
			if ( term in cache ) {response( cache[ term ] );return;}

			$.getJSON( '<?= base_url('usercontrol/integration_category_auto') ?>', request, function( data, status, xhr ) {
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
</script>