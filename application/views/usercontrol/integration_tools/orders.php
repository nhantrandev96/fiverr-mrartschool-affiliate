<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<div class="pull-left">
					<h5 class="pull-left"><?= __('admin.integration_orders') ?></h5>
				</div>
				<div class="pull-right">
					<div class="btn-group d-none btn-group-md delete-multiple-container" role="group">
				    </div>
				</div>
			</div>

			<div class="card-body">
				<div class="table-rep-plugin">
				    
				    <div class="text-center">
                        <?php if ($orders ==null) {?>
                        	<img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:100px;">
                         	<h3 class="m-t-40 text-center"><?= __('admin.no_orders') ?></h3>
                    	<?php } else { ?>

                    	<div class="table-responsive b-0" data-pattern="priority-columns">
                            <table id="tech-companies-1" class="table user-table  table-striped">
								<thead>
									<tr>
										<th width="50px">
											<?= __('admin.id') ?>		
										</th>
										<th width="90px"><?= __('admin.order_id') ?></th>
										<th width="180px"><?= __('admin.user_name') ?></th>
										<th><?= __('admin.product_ids') ?></th>
										<th><?= __('admin.total') ?></th>
										<th><?= __('admin.currency') ?></th>
										<th width="90px"><?= __('admin.commission_type') ?></th>
										<th><?= __('admin.commission') ?></th>
										<th><?= __('admin.ip') ?></th>
										<th><?= __('admin.country_code') ?></th>
										<th><?= __('admin.website') ?></th>
										<th><?= __('admin.script_name') ?></th>
										<th width="180px"><?= __('admin.created_at') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($orders as $key => $order) { ?>
										<tr>
											<td><?= $order['id'] ?></td>
											<td><?= $order['order_id'] ?></td>
											<td><?= $order['user_name'] ?></td>
											<td><?= $order['product_ids'] ?></td>
											<td><?= $order['total'] ?></td>
											<td><?= $order['currency'] ?></td>
											<td><?= $order['commission_type'] ?></td>
											<td><?= c_format($order['commission']) ?></td>
											<td><?= $order['ip'] ?></td>
											<td><?= $order['country_code'] ?>&nbsp;<img title="<?= $order['country_code'] ?>" src="<?= base_url('assets/vertical/assets/images/flags/'. strtolower($order['country_code'])) ?>.png" width='25' height='15'></td>
											<td><a href="//<?= $order['base_url'] ?>" target='_blank'><?= $order['base_url'] ?></a></td>
											<td><img class="img-integration" src="<?= base_url('assets/integration/small/' .$order['script_name'].'.png') ?>"></td>
											<td><?= $order['created_at'] ?></td>
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
</div>