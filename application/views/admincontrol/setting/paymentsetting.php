<div class="card">
		<div class="card-body">
			<form class="form-horizontal" autocomplete="off" method="post" action=""  enctype="multipart/form-data" id="setting-form">
				<div class="row">
					<div class="col-sm-12">
						<ul class="nav nav-pills nav-stacked setting-nnnav" role="tablist">
							<li class="nav-item">
								<a class="nav-link active show" data-toggle="tab" href="#site-setting" role="tab"><?= __('admin.site_setting') ?></a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#site-fronttemplate" role="tab"><?= __('admin.front_template') ?></a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#email-setting" role="tab"><?= __('admin.email_setting') ?></a>
							</li>
							
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#tnc-page" role="tab"><?= __('admin.terms_and_condition') ?></a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#cfru-setting" role="tab"><?= __('admin.commission_for_refer_user') ?></a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#tracking" role="tab"><?= __('admin.tracking') ?></a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#wallet-setting" role="tab"><?= __('admin.wallet_setting') ?></a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#googlerecaptcha-setting" role="tab"><?= __('admin.googlerecaptcha') ?></a>
							</li>

							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#cron_jobs-setting" role="tab"><?= __('admin.cron_jobs') ?></a>
							</li>
						</ul>
					</div>
					<div class="col-sm-12">
						<div class="tab-content">
							<?php if($this->session->flashdata('success')){?>
								<div class="alert alert-success alert-dismissable"> <?php echo $this->session->flashdata('success'); ?> </div>
							<?php } ?>

							<div class="tab-pane p-3" id="site-fronttemplate" role="tabpanel">
								<br><br>
								<div class="row theme-container">
									<?php foreach ($front_themes as $theme) {  ?>
									<div class="col-sm-4">
										<div class="theme-box <?= $login['front_template'] == $theme['id'] ? 'selected' : '' ?> ">
											<img class="theme-image" src="<?= resize('assets/images/themes/'.$theme['image'],392,192) ?>">
											<div class="theme-bottom">
												<div class="theme-name"><span class="theme-status">Active :</span> <?= $theme['name'] ?></div>
												<div class="theme-buttons">
													<button type="button" data-id='<?= $theme['id'] ?>' class="theme-btn btn-theme-active">Active</button>
													<a href="<?= base_url('?tmp_theme='. $theme['id']) ?>" target="_blank" class="theme-btn btn-theme-preview">Preview</a>
													<!-- Button trigger modal -->
                                                        <i class="fa fa-edit" data-toggle="modal" data-target="#exampleModalCenter"></i>
												</div>
											</div>
										</div>
									</div>
								<?php } ?>
								</div>
							</div>
							
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Theme</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    Edit Theme Option Is Coming Soon...
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                            
							<div class="tab-pane p-3" id="cron_jobs-setting" role="tabpanel">
								<div class="row">
									<div class="col-sm-6">
										<h5>WHAT IS A CRON JOB?</h5>
										<p>A cron job is simply a task that you schedule to run automatically at specific intervals. For example, if you want to back up a file every six hours, you can set this up easily using cPanel's cron jobs feature.</p>

										<h6>To add a cron job, follow these steps:</h6>

										<ol>
											<li>Log in to cPanel</li>
											<li>In the <b>ADVANCED</b> section of the cPanel home screen, click <b>Cron Jobs</b>:</li>
											<li>Under <b>Add New Cron Job</b>, specify the interval for the command you want.</li>
											<li>In the <b>Common Settings</b>, select  <b>Once Per Minute(* * * * *)</b>.</li>
											<li>In the <b>Command</b> box add <div> <code>curl <?= base_url('/cronJob/transaction') ?></code></div> </li>
											<li>Click Add New Cron Job. cPanel creates the cron job.</li>
										</ol>
									</div>
									<div class="col-sm-6">
										<img src="<?= base_url('assets/images/cronjob.png') ?>" class='img-responsive'>
									</div>
								</div>
							</div>

							<div class="tab-pane p-3" id="cfru-setting" role="tabpanel">
								<div class="form-group">
									<label class="control-label">Status</label>
									<div class="radio-group">
										
										<label class="radio radio-inline"><input type="radio" class="referlevel_status" <?= (int)$referlevel['status'] == 
											1 ? 'checked' : '' ?> name="referlevel[status]" value="1" > Enable </label>
										
										
										<label class="radio radio-inline"><input type="radio" class="referlevel_status" <?= (int)$referlevel['status'] == 
											0 ? 'checked' : '' ?> name="referlevel[status]" value="0" > Disable </label>
										
										
										<label class="radio radio-inline"><input type="radio" class="referlevel_status" <?= (int)$referlevel['status'] == 
											2 ? 'checked' : '' ?> name="referlevel[status]" value="2" > Disable Only For Selected Users </label>
									</div>

								
									<div class="div-toggle status-1">
										<div class="well">
											<div class="m-0 alert alert-info">Enable For all Users</div>
										</div>
									</div>
									<div class="div-toggle status-0">
										<div class="well">
											<div class="m-0 alert alert-info">Disable For all Users</div>
										</div>
									</div>
									<div class="div-toggle status-2">
										<div class="well">
											<div class="alert alert-info">Disable only for selected users</div>
											<?php
												$_selected = json_decode( (isset($referlevel['disabled_for']) ? $referlevel['disabled_for'] : '[]') , 1);
											?>
											<div style="max-height: 200px;overflow: auto;">
												<ul class="list-unstyled">
													<?php foreach ($users_list as $key => $value) { ?>
														<li>
															<div class="checkbox">
																<label><input <?= in_array($value['id'], $_selected) ? 'checked' : '' ?> type="checkbox" name="referlevel[disabled_for][]" value="<?= $value['id'] ?>"> &nbsp; <?= $value['name'] ?></label>
															</div>
														</li>
													<?php } ?>
												</ul>
											</div>
										</div>
									</div>

									<script type="text/javascript">
										$('.referlevel_status').on('change',function(){
											$(".div-toggle").hide();
											$(".div-toggle.status-"+ $('.referlevel_status:checked').val()).show();
										})

										$('.referlevel_status:checked').trigger('change')
									</script>
								</div>
								<br>
								<div class="form-group">
									<label class="control-label"><?= __('admin.local_store_refer_sale_commission') ?></label>
									<select class="form-control" name="referlevel[autoacceptlocalstore]">
										<option value="0">On Hold</option>
										<option value="1" <?= $referlevel['autoacceptlocalstore'] ? 'selected' : '' ?>>In Wallet</option>
									</select>
								</div>

								<div class="form-group">
									<label class="control-label"><?= __('admin.external_store_refer_sale_commission') ?></label>
									<select class="form-control" name="referlevel[autoacceptexternalstore]">
										<option value="0">On Hold</option>
										<option value="1" <?= $referlevel['autoacceptexternalstore'] ? 'selected' : '' ?>>In Wallet</option>
									</select>
								</div>

								<div class="form-group">
									<label class="control-label"><?= __('admin.action_refer_commission') ?></label>
									<select class="form-control" name="referlevel[autoacceptaction]">
										<option value="0">On Hold</option>
										<option value="1" <?= $referlevel['autoacceptaction'] ? 'selected' : '' ?>>In Wallet</option>
									</select>
								</div>
								
								<?php $levels = isset($referlevel['levels']) ? (int)$referlevel['levels'] : 3;  ?>
								<div class="form-group">
									<label class="control-label"><?= __('admin.refer_level') ?></label>
									<select class="form-control" id="referlevel_select" name="referlevel[levels]">
										<option <?= $levels == "1" ? 'selected': '' ?> value="1">1</option>
										<option <?= $levels == "2" ? 'selected': '' ?> value="2">2</option>
										<option <?= $levels == "3" ? 'selected': '' ?> value="3">3</option>
										<option <?= $levels == "4" ? 'selected': '' ?> value="4">4</option>
										<option <?= $levels == "5" ? 'selected': '' ?> value="5">5</option>
										<option <?= $levels == "6" ? 'selected': '' ?> value="6">6</option>
										<option <?= $levels == "7" ? 'selected': '' ?> value="7">7</option>
										<option <?= $levels == "8" ? 'selected': '' ?> value="8">8</option>
										<option <?= $levels == "9" ? 'selected': '' ?> value="9">9</option>
										<option <?= $levels == "10" ? 'selected': '' ?> value="10">10</option>
									</select>
								</div>
										
								<div class="new-comm">
									<div class="table-responsive">
										<table class="table" id="tbl_refer_level">
											<thead>
												<tr>
													<th>Level</th>
													<th>Number of click per commission</th>
													<th>
														Sale Commission 
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

												<?php for ($level =1; $level <= $levels; $level++) { ?>
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

								<?php if(false){ ?>
								<div class="commi-cube">
									<div class="row">
										<div class="col-sm-3">
											<div class="comm-cube-box">
												<div class="form-group">
													<label  class="control-label"><?= __('admin.no_of_click_per_commission') ?></label>
													<input name="referlevel[click]" value="<?php echo $referlevel['click']; ?>" class="form-control" step="any" type="number" placeholder='<?= __('admin.no_of_click_per_commission') ?>'>
												</div>
												<?php foreach (array('1','2','3') as $key => $v) { ?>
													<fieldset>
														<legend><?= __('admin.level') ?> <?= $v ?>:</legend>
														
															<div class="form-group">
																<label  class="control-label"><?= __('admin.refer_setting_click_commission') ?> (<?= $CurrencySymbol ?></span>)</label>
																<input name="referlevel_<?php echo $v ?>[commition]" value="<?php echo ${"referlevel_$v"}['commition']; ?>" class="form-control" step="any" type="number">
															</div>
													</fieldset>
												<?php } ?>
											</div>
										</div>
										<div class="col-sm-3">
											<div class="comm-cube-box">
												<div class="form-group">
													<label  class="control-label"><?= __('admin.fix_amount_or_per') ?></label>
													<select class="form-control refer-symball-select" name="referlevel[sale_type]">
														<option symbal='%' <?php if($referlevel['sale_type'] == 'percentage') { ?> selected <?php } ?> value="percentage">Percentage(%)</option>
														<option symbal='<?= $CurrencySymbol ?>' <?php if($referlevel['sale_type'] == 'fixed') { ?> selected <?php } ?>  value="fixed">Fixed</option>
													</select>
												</div>
												<?php foreach (array('1','2','3') as $key => $v) { ?>
													<fieldset>
														<legend><?= __('admin.level') ?> <?= $v ?>:</legend>
															<div class="form-group">
																<label  class="control-label"><?= __('admin.refer_setting_sale_commission') ?> (<span class="refer-symball"></span>)</label>
																<input name="referlevel_<?php echo $v ?>[sale_commition]" value="<?php echo ${"referlevel_$v"}['sale_commition']; ?>" class="form-control" step="any" type="number">
															</div>
													</fieldset>
												<?php } ?>
											</div>
										</div>
										<div class="col-sm-3">
											<div class="comm-cube-box">
												<div class="form-group">
													<label  class="control-label">External Click</label>
													<input name="referlevel[ex_click]" value="<?php echo $referlevel['ex_click']; ?>" class="form-control" step="any" type="number" placeholder='External Click'>
												</div>
												<?php foreach (array('1','2','3') as $key => $v) { ?>
													<fieldset>
														<legend><?= __('admin.level') ?> <?= $v ?>:</legend>
															<div class="form-group">
																<label  class="control-label">External Click Commission  (<?= $CurrencySymbol ?></span>)</label>
																<input name="referlevel_<?php echo $v ?>[ex_commition]" value="<?php echo ${"referlevel_$v"}['ex_commition']; ?>" class="form-control" step="any" type="number">
															</div>
													</fieldset>
												<?php } ?>
											</div>
										</div>
										<div class="col-sm-3">
											<div class="comm-cube-box">
												<div class="form-group">
													<label  class="control-label">External Action Click</label>
													<input name="referlevel[ex_action_click]" value="<?php echo $referlevel['ex_action_click']; ?>" class="form-control" step="any" type="number" placeholder='External Action Click'>
												</div>
												<?php foreach (array('1','2','3') as $key => $v) { ?>
													<fieldset>
														<legend><?= __('admin.level') ?> <?= $v ?>:</legend>
															<div class="form-group">
																<label  class="control-label">External Action Click Commission  (<?= $CurrencySymbol ?></span>)</label>
																<input name="referlevel_<?php echo $v ?>[ex_action_commition]" value="<?php echo ${"referlevel_$v"}['ex_action_commition']; ?>" class="form-control" step="any" type="number">
															</div>
													</fieldset>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
								<?php } ?>

								<script type="text/javascript">
									function chnage_teigger() {
										var symbal = $(".refer-symball-select").find("option:selected").attr("symbal");
										$(".refer-symball").html(symbal);
									}
									$(".refer-symball-select").change(chnage_teigger)
									chnage_teigger();
								</script>
							</div>

							<div class="tab-pane p-3" id="wallet-setting" role="tabpanel">
								<div class="form-group">
									<label  class="control-label"><?= __('admin.minimum_withdraw') ?></label>
									<input name="site[wallet_min_amount]" value="<?php echo $site['wallet_min_amount']; ?>" class="form-control" type="number">
								</div>

								<div class="form-group">
									<label  class="control-label"><?= __('admin.minimum_withdraw_message') ?></label>
									<textarea name="site[wallet_min_message]" class="form-control summernote"><?php echo $site['wallet_min_message']; ?></textarea>
								</div>
							</div>

							<div class="tab-pane p-3 active show" id="site-setting" role="tabpanel">
								<div class="form-group">
									<label  class="control-label"><?= __('admin.website_name') ?></label>
									<input name="site[name]" value="<?php echo $site['name']; ?>" class="form-control" type="text">
								</div>
								<div class="form-group">
									<label class="control-label">Show Sponser</label>
									<select class="form-control" name="site[show_sponser]">
										<option value="">Show Admin As Sponser</option>
										<option <?= $site['show_sponser'] == 'none' ? 'selected' : '' ?> value="none">Not Show</option>
										<option <?= $site['show_sponser'] == 'real_sponser' ? 'selected' : '' ?> value="real_sponser">Real Sponser</option>
									</select>
								</div>
								<div class="form-group">
									<label  class="control-label">Sponser Name</label>
									<input name="site[sponser_name]" value="<?php echo $site['sponser_name']; ?>" class="form-control" type="text">
								</div>
								<div class="form-group">
									<label class="control-label">Registration form</label>
									<select class="form-control" name="store[registration_status]">
										<option value="0"><?= __('admin.disable') ?></option>
										<option value="1" <?= $store['registration_status'] ? 'selected' : '' ?>><?= __('admin.enable') ?></option>
									</select>
								</div>
								<div class="form-group">
									<label class="control-label">Default Action Status</label>
									<select class="form-control" name="referlevel[default_action_status]">
										<option value="0" <?= (int)$referlevel['default_action_status'] == 0 ? 'selected' : '' ?>>On Hold</option>
										<option value="1" <?= (int)$referlevel['default_action_status'] == 1 ? 'selected' : '' ?>>In Wallet</option>
									</select>
								</div>
								<div class="form-group">
									<label  class="control-label"><?= __('admin.notification_email') ?></label>
									<input name="site[notify_email]" value="<?php echo $site['notify_email']; ?>" class="form-control" type="email">
								</div>
								<div class="form-group">
									<label  class="control-label"><?= __('admin.footer_text') ?></label>
									<input name="site[footer]" value="<?php echo $site['footer']; ?>" class="form-control" type="text">
								</div>
								<?php
									$zones_array = array();
									$timestamp = time();
									foreach(timezone_identifiers_list() as $key => $zone) {
										date_default_timezone_set($zone);
										$zones_array[$zone] = date('P', $timestamp) . " {$zone} ";
									}
								?>
								<div class="form-group">
									<label  class="control-label"><?= __('admin.time_zone') ?></label>
									<select class="form-control select2-input" name="site[time_zone]">
										<?php foreach ($zones_array as $key => $value) { ?>
											<option value="<?= $key ?>" <?= $site['time_zone'] == $key ? 'selected' : '' ?> > <?= $value ?></option>
										<?php } ?>
									</select>
								</div>

								<div class="row">
									<!-- <div class="col-sm-6">
										<div class="form-group">
											<label  class="control-label"><?= __('admin.front_template') ?></label>
											<select class="form-control" name="login[front_template]">
												<option value="">Default</option>
									
												<option value="landing" <?= $login['front_template'] == "landing" ? 'selected' : '' ?>>Landing Page</option>
												<?php foreach ($themes as $key => $value) { ?>
													<option value="<?= $value['theme_id'] ?>" <?= $login['front_template'] == $value['theme_id'] ? 'selected' : '' ?> ><?= $value['name'] ?> </option>
												<?php } ?>
											</select>
										</div>
									</div> -->
									<div class="col-sm-12">
										<div class="form-group">
											<label class="control-label"><?= __('admin.show_language_dropdown') ?></label>
											<select class="form-control" name="store[language_status]">
												<option value="0"><?= __('admin.disable') ?></option>
												<option value="1" <?= $store['language_status'] ? 'selected' : '' ?>><?= __('admin.enable') ?></option>
											</select>
										</div>
									</div>
								</div>
								


								<fieldset>
									<legend><?= __('admin.website_logo') ?></legend>
									<div class="row">
										<div class="col-sm-6 p-4">
											<?php $img = $site['logo'] ? base_url('assets/images/site/'. $site['logo']) : base_url('assets/vertical/assets/images/users/avatar-1.jpg'); ?>
											<img style="width: 150px;" src="<?= $img ?>" class='img-responsive_setting' id='site-logo'>
										</div>
										<div class="col-sm-6">
											<input type="file" onchange="readURL(this,'#site-logo')" name="site_logo">
										</div>
									</div>
								</fieldset>
								<br>
								<fieldset>
									<legend><?= __('admin.website_favicon') ?></legend>
									<div class="row">
										<div class="col-sm-6 p-4">
											<?php $img = $site['favicon'] ? base_url('assets/images/site/'. $site['favicon']) : base_url('assets/vertical/assets/images/users/avatar-1.jpg'); ?>
											<img style="width: 150px;" src="<?= $img ?>" class='img-responsive_setting' id='site-favicon'>
										</div>
										<div class="col-sm-6">
											<input type="file" name="site_favicon" onchange="readURL(this,'#site-favicon')">
										</div>
									</div>
								</fieldset>
								<br>	
								<fieldset>
									<legend><?= __('admin.meta_tag') ?></legend>
									<div class="form-group">
										<label  class="control-label"><?= __('admin.description') ?></label>
										<input name="site[meta_description]" value="<?php echo $site['meta_description']; ?>" class="form-control" type="text">
									</div>
									<div class="form-group">
										<label  class="control-label"><?= __('admin.keywords') ?></label>
										<input name="site[meta_keywords]" value="<?php echo $site['meta_keywords']; ?>" class="form-control" type="text">
									</div>
									<div class="form-group">
										<label  class="control-label"><?= __('admin.author') ?></label>
										<input name="site[meta_author]" value="<?php echo $site['meta_author']; ?>" class="form-control" type="text">
									</div>
								</fieldset>
								
								<br>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label"><?= __('admin.google_analytics_for_site_page') ?></label>
											<textarea rows="8" name="site[google_analytics]" class="form-control site-google_analytics"><?php echo $site['google_analytics']; ?></textarea>

											<a href="https://support.google.com/analytics/answer/1008080?hl=en" target="_blank">Get Analytics Code</a>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label">Example</label>
											<img class="img-responsive_setting w-100" src="<?= base_url('assets/images/google_analytics.png') ?>">
										</div>
									</div>
								</div>

								<br>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label"><?= __('admin.faceboook_pixel_for_site_page') ?></label>
											<textarea rows="8" name="site[faceboook_pixel]" class="form-control site-faceboook_pixel"><?php echo $site['faceboook_pixel']; ?></textarea>

											<a href="https://developers.facebook.com/docs/facebook-pixel/implementation" target="_blank">Get Faceboook Pixel Code</a>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label">Example</label>
											<img class="img-responsive_setting w-100" src="<?= base_url('assets/images/faceboook_pixel.png') ?>">
										</div>
									</div>
								</div>

								<br>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label"><?= __('admin.global_script') ?></label>
											<textarea rows="8" name="site[global_script]" class="form-control site-global_script"><?php echo $site['global_script']; ?></textarea>
										</div>
									</div>
									<div class="col-sm-6">
										<?php  $global_script_status = (array)json_decode($site['global_script_status'],1); ?>
										<div class="form-group">
											<label class="control-label"><?= __('admin.show_global_script') ?></label>
											<div>
												<div>
													<label>
														<input type="checkbox" <?= in_array('admin', $global_script_status) ? 'checked' : '' ?> name="site[global_script_status][]" value="admin"> <?= __('admin.option_admin_side') ?>
													</label>
												</div>
												<div>
													<label>
														<input type="checkbox" <?= in_array('affiliate', $global_script_status) ? 'checked' : '' ?> name="site[global_script_status][]" value="affiliate"> <?= __('admin.option_affiliate_side') ?>
													</label>
												</div>
												<div>
													<label>
														<input type="checkbox" <?= in_array('front', $global_script_status) ? 'checked' : '' ?> name="site[global_script_status][]" value="front"> <?= __('admin.option_front_side') ?>
													</label>
												</div>
												<div>
													<label>
														<input type="checkbox" <?= in_array('store', $global_script_status) ? 'checked' : '' ?> name="site[global_script_status][]" value="store"> <?= __('admin.option_store_side') ?>
													</label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<div class="tab-pane p-3" id="login-2" role="tabpanel">	
							</div>

							<div class="tab-pane p-3" id="email-setting" role="tabpanel">
								<div class="form-group">
									<label  class="control-label"><?= __('admin.from_email') ?></label>
									<input name="email[from_email]" value="<?php echo $email['from_email']; ?>" class="form-control" type="text">
								</div>
								<div class="form-group">
									<label  class="control-label"><?= __('admin.from_name') ?></label>
									<input name="email[from_name]" value="<?php echo $email['from_name']; ?>" class="form-control" type="text">
								</div>
								
								<div class="form-group">
									<label  class="control-label"><?= __('admin.smtp_hostname') ?></label>
									<input name="email[smtp_hostname]" value="<?php echo $email['smtp_hostname']; ?>" class="form-control" type="text">
								</div>
								<div class="form-group">
									<label  class="control-label"><?= __('admin.smtp_username') ?></label>
									<input name="email[smtp_username]" value="<?php echo $email['smtp_username']; ?>" class="form-control" type="text">
								</div>
								
								<div class="form-group">
									<label  class="control-label"><?= __('admin.smtp_password') ?></label>
									<div class="input-group password-group">
										
									  	<input readonly="" onfocus="this.removeAttribute('readonly');" onblur="this.setAttribute('readonly','readonly');" autocomplete="off" type="password" class="form-control" name="email[smtp_password]" value="<?php echo $email['smtp_password']; ?>">
									  	<div class="input-group-prepend">
										    <button class="btn btn-outline-secondary" type="button"><i class="fa fa-eye"></i></button>
									 	</div>
									</div>
								</div>
								<div class="form-group">
									<label  class="control-label"><?= __('admin.smtp_port') ?></label>
									<input name="email[smtp_port]" value="<?php echo $email['smtp_port']; ?>" class="form-control" type="text">
								</div>
								<fieldset>
									<legend><?= __('admin.testing') ?></legend>
									<div class="input-group mb-3">
									  <input type="text" class="form-control testingemail" placeholder="<?= __('admin.test_email_send_on') ?>" aria-label="Recipient's username" aria-describedby="basic-addon2">
									  <div class="input-group-append cp">
									    <span class="input-group-text send-test-mail" id="basic-addon2"><?= __('admin.send_test_mail') ?></span>
									  </div>
									</div>
								</fieldset>
							</div>

							<div class="tab-pane p-3" id="tnc-page" role="tabpanel">
								<div class="form-group">
									<label  class="control-label"><?= __('admin.page_title') ?></label>
									<input placeholder="<?= __('admin.enter_page_title') ?>" name="tnc[heading]" value="<?php echo $tnc['heading']; ?>" class="form-control"  type="text">
								</div>
								<div class="form-group">
									<label  class="control-label"><?= __('admin.page_content') ?></label>
									<textarea name="tnc[content]" class="form-control summernote"><?php echo $tnc['content']; ?></textarea>
								</div>
							</div>

							<div class="tab-pane p-3" id="tracking" role="tabpanel">
								<div class="form-group">
									<label  class="control-label"><?= __('admin.affiliate_cookie') ?></label>
									<input class="form-control input-affiliate_cookie" type="number" value="<?= $store['affiliate_cookie'] ?>" name="store[affiliate_cookie]">
								</div>
							</div>

							<div class="tab-pane p-3" id="googlerecaptcha-setting" role="tabpanel">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label  class="control-label"><?= __('admin.text_site_key') ?></label>
											<input class="form-control" type="text" value="<?= $googlerecaptcha['sitekey'] ?>" name="googlerecaptcha[sitekey]">
										</div>
										<div class="form-group">
											<label  class="control-label"><?= __('admin.text_secret_key') ?></label>
											<input class="form-control" type="text" value="<?= $googlerecaptcha['secretkey'] ?>" name="googlerecaptcha[secretkey]">
										</div>

										<div class="form-group">
											<label class="control-label">Admin Login</label>
											<select class="form-control" name="googlerecaptcha[admin_login]">
												<option value="0"><?= __('admin.disable') ?></option>
												<option value="1" <?= $googlerecaptcha['admin_login'] ? 'selected' : '' ?>><?= __('admin.enable') ?></option>
											</select>
										</div>

										<div class="row">
											<div class="col-sm-6">
												<div class="form-group">
													<label class="control-label">Affiliate Login</label>
													<select class="form-control" name="googlerecaptcha[affiliate_login]">
														<option value="0"><?= __('admin.disable') ?></option>
														<option value="1" <?= $googlerecaptcha['affiliate_login'] ? 'selected' : '' ?>><?= __('admin.enable') ?></option>
													</select>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group">
													<label class="control-label">Affiliate Register</label>
													<select class="form-control" name="googlerecaptcha[affiliate_register]">
														<option value="0"><?= __('admin.disable') ?></option>
														<option value="1" <?= $googlerecaptcha['affiliate_register'] ? 'selected' : '' ?>><?= __('admin.enable') ?></option>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group">
													<label class="control-label">Client Login</label>
													<select class="form-control" name="googlerecaptcha[client_login]">
														<option value="0"><?= __('admin.disable') ?></option>
														<option value="1" <?= $googlerecaptcha['client_login'] ? 'selected' : '' ?>><?= __('admin.enable') ?></option>
													</select>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group">
													<label class="control-label">Client Register</label>
													<select class="form-control" name="googlerecaptcha[client_register]">
														<option value="0"><?= __('admin.disable') ?></option>
														<option value="1" <?= $googlerecaptcha['client_register'] ? 'selected' : '' ?>><?= __('admin.enable') ?></option>
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<h4 class="mb-3 mt-3">How to Get Site Key and Secret Key ?</h4>

										<p>Almost every internet user has a Google account. I assume that you have one too. If you don't have one, first, <a href="https://accounts.google.com" class="link" target="_blank">create a new google account</a>. Then, visit <a href="https://www.google.com/recaptcha/" class="link" target="_blank">google recaptcha</a>, click <strong>GET RECAPTCHA</strong> button (the button in the top right corner in below image) and sign in with your Google account.</p>

										<p>In the form, add any label you like. Then, select <strong>reCAPTCHA v2</strong> and add your domains. Here, you can even add a fake domain which you use in your localhost and it will function very well.</p>

										<img src="<?= base_url("assets/images/grecaptcha/grecaptcha-2.png") ?>" class='img-thumbnail'>

										<p>Next, find your site key and secret key.</p>

										<img src="<?= base_url("assets/images/grecaptcha/grecaptcha-3.png") ?>" class='img-thumbnail'>
									</div>
								</div>

								
							</div>
						</div>
					</div>
					<div class="col-sm-12 text-right">
						<button type="submit" class="btn btn-sm btn-primary btn-submit"><?= __('admin.save_settings') ?></button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</div>

<link href="<?php echo base_url(); ?>assets/js/summernote-0.8.12-dist/summernote-bs4.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>assets/js/summernote-0.8.12-dist/summernote-bs4.js"></script>


<script type="text/javascript">


$('.setting-nnnav li a').on('shown.bs.tab', function(event){
    var x = $(event.target).attr('href');         // active tab
	$(".btn-submit").hide();

    if(x != '#site-fronttemplate'){
    	$(".btn-submit").show();
    }
    localStorage.setItem("last_pill", x);
});
$("#setting-form").on('submit',function(){
	$("#setting-form .alert-error").remove();
	var affiliate_cookie = parseInt($(".input-affiliate_cookie").val());
	if(affiliate_cookie <= 0 || affiliate_cookie > 365){
		$(".input-affiliate_cookie").after("<div class='alert alert-danger alert-error'>Days Between 1 and 365</div>");
	}
	if($("#setting-form .alert-error").length == 0) return true;
	return false;
})
$(".items-holder").delegate(".remove-items",'click',function(){
	$(this).parent(".input-group").remove();
})
$(".add-items").on('click',function(){
	$(".items-holder").append('\
		<div class="input-group mb-3">\
		<input type="text" name="login[text_list][]" class="form-control" placeholder="List Items" >\
		<div class="input-group-append remove-items">\
		<span class="input-group-text"><i class="fa fa-trash"></i></span>\
		</div>\
		</div>\
		');
})
$(document).on('ready',function() {
	$('.summernote').summernote({
		tabsize: 2,
		height: 400
	});
	var last_pill = localStorage.getItem("last_pill");
	if(last_pill){ $('[href="'+ last_pill +'"]').click() }
});
$('.send-test-mail').on('click',function(){
	$this = $(this);
	$.ajax({
		type:'POST',
		dataType:'json',
		data:{send_test_mail:$(".testingemail").val()},
		beforeSend:function(){ $this.btn("loading"); },
		complete:function(){$this.btn("reset"); },
		success:function(json){ },
	})
})

$(".theme-container").delegate(".btn-theme-active","click",function(evt){
	$this = $(this);

	$.ajax({
        type:'POST',
        dataType:'json',
        data:{
        	id: $this.attr("data-id"),
        	action:'active_theme',
        },
        beforeSend:function(){ $this.btn("loading"); },
		complete:function(){$this.btn("reset"); },
        success:function(result){
            $(".alert-dismissable").remove();


            $this.find(".has-error").removeClass("has-error");
            $this.find("span.text-danger").remove();
            
            if(result['success']){
	            $(".theme-box.selected").removeClass('selected');
	            $this.parents('.theme-box').addClass('selected')

                $(".tab-content").prepend('<div class="alert mt-4 alert-info alert-dismissable">'+ result['success'] +'</div>');
                setTimeout(function(){ $(".alert-dismissable").remove() }, 3000);
                var body = $("html, body");
				body.stop().animate({scrollTop:0}, 500, 'swing', function() { });

				var div = $(".theme-box.selected").parents(".col-sm-4").clone()
				$(".theme-box.selected").parents(".col-sm-4").remove();
				div.prependTo(".theme-container");

				$(".btn-theme-active").removeClass("btn-loading");
            }
        },
    })
});
$(".btn-submit").on('click',function(evt){
    evt.preventDefault();

    $(".site-global_script").val( btoa( $(".site-global_script").val() ) );
    $(".site-faceboook_pixel").val( btoa( $(".site-faceboook_pixel").val() ) );
    $(".site-google_analytics").val( btoa( $(".site-google_analytics").val() ) );

    var formData = new FormData($("#setting-form")[0]);

    $(".site-global_script").val( atob( $(".site-global_script").val() ) );
    $(".site-faceboook_pixel").val( atob( $(".site-faceboook_pixel").val() ) );
    $(".site-google_analytics").val( atob( $(".site-google_analytics").val() ) );

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
            $this.find("span.text-danger").remove();
            
            if(result['location']){
                window.location = result['location'];
            }

            if(result['success']){
                $(".tab-content").prepend('<div class="alert mt-4 alert-info alert-dismissable">'+ result['success'] +'</div>');
                var body = $("html, body");
				body.stop().animate({scrollTop:0}, 500, 'swing', function() { });
            }

            if(result['errors']){
                $.each(result['errors'], function(i,j){
                    $ele = $this.find('[name="'+ i +'"]');
                    if($ele){
                        $ele.parents(".form-group").addClass("has-error");
                        $ele.after("<span class='d-block text-danger'>"+ j +"</span>");
                    }
                });
            }
        },
    })
    return false;
});
var levels = {};

<?php 
	for ($i=1; $i <= 10; $i++) { 
		$v = 'referlevel_'.$i;
		if (isset($$v)) { ?>
				levels['<?= $i ?>'] = <?= json_encode($$v) ?>;
		<?php }
	}
?>
$('#referlevel_select').on('change',function(){
	var level =  $(this).val();
	
	var html = '';
	for(var i = 1; i <= level; i++){
		html += '<tr>';
			html += '<td>'+i+'</td>';
			html += '<td><input type="number" step="any" name="referlevel_'+i+'[commition]" value="'+(levels[i] ? levels[i]['commition'] : '' )+'" class="form-control" /></td>';
			html += '<td><div class="input-group"><input type="number" step="any" name="referlevel_'+i+'[sale_commition]" value="'+(levels[i] ? levels[i]['sale_commition'] : '' )+'" class="form-control" /><div class="input-group-append"><span class="input-group-text refer-symball"></span></div>															</div></td>';
			html += '<td><div class="input-group"><input type="number" step="any" name="referlevel_'+i+'[ex_commition]" value="'+(levels[i] ? levels[i]['ex_commition'] : '' )+'" class="form-control" /><div class="input-group-append"><span class="input-group-text"><?= $CurrencySymbol ?></span></div></div></td>';
			html += '<td><div class="input-group"><input type="number" step="any" name="referlevel_'+i+'[ex_action_commition]" value="'+(levels[i] ? levels[i]['ex_action_commition'] : '' )+'" class="form-control" /><div class="input-group-append"><span class="input-group-text"><?= $CurrencySymbol ?></span></div></div></td>';
		html += '</tr>';
	}
	$('#tbl_refer_level tbody').html(html);

	chnage_teigger();
});
</script>
