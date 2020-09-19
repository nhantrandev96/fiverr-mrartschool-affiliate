<div class="row">
			<div class="col-12">
				<div class="card m-b-30">
					<div class="card-header">
						<h4 class="card-title pull-left"><?= __("admin.edit_language") ?></h4>
					</div>
					<div class="card-body">
						<form id="language-form" enctype="multipart/form-data" action="<?= base_url("admincontrol/update_language") ?>" method="POST">
							<?php if($this->session->flashdata('success')){?>								
			                     <div class="alert alert-success alert-dismissable">									
			                     	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			                     	<?php echo $this->session->flashdata('success'); ?> 
			                     </div>
		                     <?php } ?>	
		                     <?php if($this->session->flashdata('error')){?>								
			                     <div class="alert alert-danger alert-dismissable">									
			                     	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			                     	<?php echo $this->session->flashdata('error'); ?> 
			                     </div>
		                     <?php } ?>	
							
							
							<input class="form-control" type="hidden" name="id" value="<?= isset($lang) ? $lang['id'] : '0' ?>" >

							<div class="form-group">
								<label class="control-label"><?= __("admin.language_name") ?></label>
								<input class="form-control" type="text" name="name" value="<?= isset($lang) ? $lang['name'] : '' ?>" 
								<?= (isset($lang) && $lang['id'] == 1) ? 'readonly' : '' ?>>
							</div>
							
							<div class="flag-file-chooser">
								<ul>
									<?php
										$selected = $flags_files[0];
										if(isset($lang['flag'])) $selected = $lang['flag'];
									?>
									<?php foreach ($flags_files as $key => $value) { ?>
										<li>
											<label>
												<input <?= $selected == $value ? 'checked' : '' ?> type="radio" name="flag" value="<?= $value ?>">
												<img src="<?= base_url($value) ?>">
											</label>
										</li>
									<?php } ?>
								</ul>
							</div>
							<br>
							<div class="row">
								<div class="col-sm-2">
									<div class="form-group">
										<label class="control-label"><?= __("admin.status") ?> </label>
										<div>
											<label class="switch">
											  <input type="checkbox"  name="status" value="1" <?= (isset($lang) && $lang['status'] == '1') ? "checked" :  '' ?>>
											  <span class="slider"></span>
											</label>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<label class="control-label"><?= __("admin.set_default") ?></label>
										<div>
											<label class="switch">
											  <input type="checkbox" value="1" name="is_default" <?= (isset($lang) && $lang['is_default'] == '1') ? "checked" :  '' ?>  >
											  <span class="slider"></span>
											</label>
										</div>
									</div>
								</div>

								<div class="col-sm-3">
									<div class="form-group">
										<label class="control-label">Is RTL ?</label>
										<div>
											<label class="switch">
											  <input type="checkbox" value="1" name="is_rtl" <?= (isset($lang) && $lang['is_rtl'] == '1') ? "checked" :  '' ?>  >
											  <span class="slider"></span>
											</label>
										</div>
									</div>
								</div>
							</div>
							<button type="submit" class="btn btn-primary btn-submit"><?= __("admin.save_changes") ?></button>
							<a href="<?= base_url("admincontrol/language") ?>" class="btn btn-default " ><?= __("admin.cancel") ?></a>
						</form>
					</div>
				</div> 
			</div> 
		</div>
 
