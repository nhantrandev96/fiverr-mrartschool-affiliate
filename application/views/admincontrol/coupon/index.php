		<div class="row">
			<div class="col-12">
				<div class="card m-b-30">
					<div class="card-header">
						<h4 class="card-title pull-left"><?= __('admin.coupon') ?></h4>
						<div class="pull-right">
							<a class="btn btn-primary" href="<?= base_url('admincontrol/coupon_manage/')  ?>"><?= __('admin.add_new'); ?></a>
						</div>
					</div>
					<div class="card-body">
						<div class="table-rep-plugin">
						    
						    <?php if ($coupons == null) {?>
                                <div class="text-center">
                                <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:100px;">
                                 <h3 class="m-t-40 text-center text-muted"><?= __('admin.no_coupons') ?></h3></div>
                                <?php }
                                else {?>
                                
                                
							<?php if($this->session->flashdata('success')){?>
								<div class="alert alert-success alert-dismissable"> <?php echo $this->session->flashdata('success'); ?> </div>
							<?php } ?>
							<div class="table-responsive b-0" data-pattern="priority-columns">
								<table id="tech-companies-1" class="table  table-striped">
									<thead>
										<tr>
											<th ><?= __('admin.coupon_name'); ?></th>
											<th width="100px"><?= __('admin.count_product_use'); ?></th>
											<th width="100px"><?= __('admin.uses_total'); ?></th>
											<th width="100px"><?= __('admin.code'); ?></th>
											<th width="100px"><?= __('admin.discount'); ?></th>
											<th width="50px"><?= __('admin.date_start'); ?></th>
											<th width="50px"><?= __('admin.date_end'); ?></th>
											<th width="50px"><?= __("admin.status") ?></th>
											<th width="180px"></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($coupons as $coupon){ ?>
											<tr>
												<td><?= $coupon['name'] ?></td>
												<td><?= (int)$coupon['product_count'] .' / '. (int)$coupon['count_coupon'] ?></td>
												<td><?= $coupon['uses_total'] ?></td>
												<td><?= $coupon['code'] ?></td>
												<td><?= $coupon['discount'] ?></td>
												<td><?= $coupon['date_start'] ?></td>
												<td><?= $coupon['date_end'] ?></td>
												<td><?= $coupon['status'] == '0' ? 'Disabled' : 'Enabled' ?></td>
												<td>
													<a href="<?= base_url('admincontrol/coupon_manage/'.$coupon['coupon_id'])  ?>" class="btn btn-primary edit-button" id="<?= $coupon['id'] ?>"><?= __("admin.edit") ?></a>
													<a href="<?= base_url('admincontrol/coupon_delete/'.$coupon['coupon_id'])  ?>" class="btn btn-danger delete-button" id="<?= $coupon['id'] ?>"><?= __("admin.delete") ?></a>
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

		<script type="text/javascript">
			$(".delete-button").on('click',function(){
				return confirm("Are you sure? ");
			})
		</script>