<div class="alert alert-primary" role="alert">
	<p class="mb-0">If you are using an External store like External store like: Woocommerce, Magento, Prestashop, OpenCart, Shopify, BigCommerce, OsCommerce, ZenCart, Xcart, </p>
	<p class="mb-0">you will be able to create your commission program for sale and clicks and share it with your affiliates.</p>
</div>


<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<div>
					<h4 class="mt-0 header-title pull-left"><?= __('admin.integration_programs') ?></h4>
					<div class="pull-right">
						<a class="btn btn-primary btn-sm" href="<?= base_url('usercontrol/programs_form') ?>"><?= __('admin.add_new') ?></a>
					</div>
				</div>
			</div>
			<div class="body">
				<div class="table-rep-plugin">
					<div class="table-responsive b-0" data-pattern="priority-columns">

						<div class="text-center">
							<?php if ($programs ==null) {?>
								<img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:100px;">
								<h3 class="m-t-40 text-center"><?= __('admin.not_activity_yet') ?></h3>
							<?php } else { ?>

								<table id="tech-companies-1" class="table  table-striped">
									<thead>
										<tr>
											<th><?= __('admin.id') ?></th>
											<th><?= __('admin.name') ?></th>
											<th><?= __('admin.sale_commission') ?></th>
											<th><?= __('admin.click_commission') ?></th>
											<th><?= __('admin.sale_status') ?></th>
											<th><?= __('admin.click_status') ?></th>
											<th><?= __('admin.status') ?></th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($programs as $key => $program) { ?>
											<tr>
												<td><?= $program['id'] ?></td>
												<td><?= $program['name'] ?></td>
												<td>
													<?php 
														if($program['vendor_id']){
															echo "Admin : ";
															if($program['admin_sale_status']){
																if($program['admin_commission_type'] == 'percentage'){ echo $program['admin_commission_sale'].'%'; }
																else if($program['admin_commission_type'] == 'fixed'){ echo c_format($program['admin_commission_sale']); }
																else { echo "Not set."; }
															} else{
																echo "Not set.";
															}

															echo "<br>Affiliate : ";
															if($program['sale_status']){
																if($program['commission_type'] == 'percentage'){ echo $program['commission_sale'].'%'; }
																else if($program['commission_type'] == 'fixed'){ echo c_format($program['commission_sale']); }
																else { echo "Not set."; }
															} else{
																echo "Not set.";
															}
														} else{
															if($program['sale_status']){
																if($program['commission_type'] == 'percentage'){ echo $program['commission_sale'].'%'; }
																else if($program['commission_type'] == 'fixed'){ echo c_format($program['commission_sale']); }
																else { echo "Not set."; }
															} else{
																echo "Not set.";
															}
														}
													?>
												</td>
												<td>
													<?php
														if($program['vendor_id']){
															echo "Admin : ";
															if($program['admin_click_status']){
																if($program["admin_commission_click_commission"] && $program['admin_commission_number_of_click']){
																	echo c_format($program["admin_commission_click_commission"]). " per ". $program['admin_commission_number_of_click'] ." Clicks";
																} else { echo "Not set."; }
															} else{
																echo "Not set.";
															}

															echo "<br>Affiliate : ";
															if($program['click_status']){
																echo c_format($program["commission_click_commission"]). " per ". $program['commission_number_of_click'] ." Clicks";
															} else{
																echo "Not set.";
															}
														} else{
															if($program['click_status']){
																echo c_format($program["commission_click_commission"]). " per ". $program['commission_number_of_click'] ." Clicks";
															} else{
																echo "Not Set";
															}
														}
													?>
												</td>
												<td>
													<?php
														if($program['vendor_id']){
															echo "Admin : ". ($program['admin_sale_status'] ? 'Enable' : 'Disable');
															echo "<br> Affiliate : ". ($program['sale_status'] ? 'Enable' : 'Disable');
														} else {
															echo (int)$program['sale_status'] ? 'Enable' : 'Disable';
														}
													?>
												<td>
													<?php
														if($program['vendor_id']){
															echo "Admin : ". ($program['admin_click_status'] ? 'Enable' : 'Disable');
															echo "<br> Affiliate : ". ($program['click_status'] ? 'Enable' : 'Disable');
														} else {
															echo (int)$program['click_status'] ? 'Enable' : 'Disable';
														}
													?>	
												</td>
											</td>
												<td><?= program_status($program['status']) ?></td>
												<td>
													<a class="btn btn-primary btn-sm" href="<?= base_url('usercontrol/programs_form/'. $program['id']) ?>"><?= __('admin.edit') ?></a>
													<button <?= $program['associate_programns'] ? 'disabled' : '' ?> class="btn btn-danger btn-sm delete-program" data-id="<?= $program['id'] ?>"><?= __('admin.delete') ?></button>
												</td>
											</tr>
										<?php } ?>

									</tbody>
								</table>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="message-model">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body text-center"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(".delete-program").on('click',function(){
		$this = $(this);
		if(!confirm("Are you sure?")) return false;
		$.ajax({
			url:'<?= base_url('usercontrol/delete_programs_form/') ?>',
			type:'POST',
			dataType:'json',
			data:{id: $this.attr("data-id")},
			beforeSend:function(){$this.btn("loading");},
			complete:function(){$this.btn("reset");},
			success:function(json){
				if(json['success']){
					$this.parents("tr").remove();
					//location.reload();
				}

				if(json['message']){
					$("#message-model .modal-body").html(json['message']);
					$("#message-model").modal("show");
				}
			},
		})
	})
</script>