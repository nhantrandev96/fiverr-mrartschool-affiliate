<div class="row">
			<div class="col-12">
				<div class="card m-b-30">
					<div class="card-header">
						<h4 class="card-title pull-left"><?= __('admin.form_coupon'); ?></h4>
						<div class="pull-right">
							<a class="btn btn-primary" href="<?= base_url('admincontrol/form_coupon_manage/')  ?>"><?= __('admin.add_new'); ?></a>
						</div>
					</div>
					<div class="card-body">
						<div class="table-rep-plugin">
						    
						     <?php if ($form_coupons == null) {?>
                                <div class="text-center">
                                <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:100px;">
                                 <h3 class="m-t-40 text-center text-muted"><?= __('admin.no_form_coupons') ?></h3></div>
                                <?php }
                                else {?>
                                
                                
							<div class="table-responsive b-0" data-pattern="priority-columns">
								
								<table id="tech-companies-1" class="table  table-striped">
									<thead>
										<tr>
											<th ><?= __('admin.form_coupon_name'); ?></th>
											<th width="100px"><?= __('admin.code'); ?></th>
											<th width="100px"><?= __('admin.discount'); ?></th>
											<th width="50px"><?= __('admin.date_start'); ?></th>
											<th width="50px"><?= __('admin.date_end'); ?></th>
											<th width="50px"><?= __("admin.status") ?></th>
											<th width="180px"></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($form_coupons as $form_coupon){ ?>
											<tr>
												<td><?= $form_coupon['name'] ?></td>
												<td><?= $form_coupon['code'] ?></td>
												<td><?= $form_coupon['discount'] ?></td>
												<td><?= $form_coupon['date_start'] ?></td>
												<td><?= $form_coupon['date_end'] ?></td>
												<td><?= $lang['status'] == '0' ? 'Disabled' : 'Enabled' ?></td>
												<td>
													<a href="<?= base_url('admincontrol/form_coupon_manage/'.$form_coupon['form_coupon_id'])  ?>" class="btn btn-primary edit-button" id="<?= $lang['id'] ?>"><?= __("admin.edit") ?></a>
												</td>
											</tr>
										<?php } ?>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div> 
			</div> 
		</div>
