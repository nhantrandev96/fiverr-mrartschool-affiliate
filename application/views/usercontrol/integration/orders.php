<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<div>
							<h5 class="pull-left"><?= __('user.integration_orders') ?></h5>
						</div>
					</div>

					<div class="card-body">
					    
					    <?php if ($orders ==null) {?>
                        	<div class="text-center">
                                <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:25px;">
                                 <h3 class="m-t-40 text-center"><?= __('user.no_orders') ?></h3>
                         	</div>
                        <?php } else { ?>

							<table class="table table-hover responsive" id="integration-order">
								<thead>
									<tr>
										<th data-priority="1" width="50px"><?= __('user.id') ?></th>
										<th data-priority="2" width="90px"><?= __('user.order_id') ?></th>
										<!--<th width="180px"><?= __('user.user_name') ?></th>-->
										<th><?= __('user.product_ids') ?></th>
										<th data-priority="3"><?= __('user.total') ?></th>
										<th data-priority="4"><?= __('user.currency') ?></th>
										<th width="90px"><?= __('user.commission_type') ?></th>
										<th><?= __('user.commission') ?></th>
										<th><?= __('user.ip') ?></th>
										<th><?= __('user.country_code') ?></th>
										<th><?= __('user.website') ?></th>
										<!--<th><?= __('user.script_name') ?></th>-->
										<th width="180px"><?= __('user.created_at') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($orders as $key => $order) { ?>
										<tr>
											<td><?= $order['id'] ?></td>
											<td><?= $order['order_id'] ?></td>
										<!--	<td><?= $order['user_name'] ?></td>-->
											<td><?= $order['product_ids'] ?></td>
											<td><?= $order['total'] ?></td>
											<td><?= $order['currency'] ?></td>
											<td><?= $order['commission_type'] ?></td>
											<td><?= c_format($order['commission']) ?></td>
											<td><?= $order['ip'] ?></td>
											<td><?= $order['country_code'] ?>&nbsp;<img title="<?= $order['country_code'] ?>" src="<?= base_url('assets/vertical/assets/images/flags/'. strtolower($order['country_code'])) ?>.png" width='25' height='15'></td>
											<td><a href="//<?= $order['base_url'] ?>" target='_blank'><?= $order['base_url'] ?></a></td>
											<!--<td><?= ucfirst($order['script_name']) ?></td>-->
											<td><?= $order['created_at'] ?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
							<div class="table-responsive">
							</div>

							<!-- <link rel="stylesheet" type="text/css" href="<?= base_url("assets/plugins/table/datatables.min.css") ?>">
							<script type="text/javascript" src="<?= base_url("assets/plugins/table/datatables.min.js") ?>"></script>
							<script type="text/javascript" src="<?= base_url("assets/plugins/table/dataTables.responsive.min.js") ?>"></script>
							<script type="text/javascript">
		                        var dataTableUser = $("#integration-order").dataTable({
		                            "paging":   false,
		                            "ordering": false,
		                            "searching": false,
		                            "info":     false
		                        })
		                    </script> -->
						<?php } ?>
					</div>
				</div>
			</div>
		</div>

<div class="modal fade" id="message-model">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body text-center"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?= __('user.close') ?></button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

</script>