<div class="row">
	<div class="col-xl-4">
		<div class="card m-b-30">
			<div class="card-body box-item-card bg-info text-light">
				<div class="d-flex flex-row">
					<div class="col-2 align-self-center"><div class="round"><i class="mdi mdi-wallet"></i></div></div>
					<div class="col-10 align-self-center text-center">
						<div class="m-l-10">
							<h6 class="mt-0 round-inner text-light">
								<?= $totals['all_clicks'] ?> / <?php echo c_format($totals['all_clicks_comm']) ?>
							</h6>
							<p class="mb-0 text-light"><?= __('admin.total_clicks') ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-4">
		<div class="card m-b-30">
			<div class="card-body box-item-card bg-info text-light">
				<div class="d-flex flex-row">
					<div class="col-2 align-self-center"><div class="round"><i class="mdi mdi-wallet"></i></div></div>
					<div class="col-10 align-self-center text-center">
						<div class="m-l-10">
							<h6 class="mt-0 round-inner text-light">
								<?= $totals['total_sale_count'] ?> / <?php echo c_format($totals['all_sale_comm']) ?>
							</h6>
							<p class="mb-0 text-light"><?= __('admin.total_sale') ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-xl-4">
		<div class="card m-b-30">
			<div class="card-body box-item-card bg-info text-light">
				<div class="d-flex flex-row">
					<div class="col-2 align-self-center"><div class="round"><i class="mdi mdi-wallet"></i></div></div>
					<div class="col-10 align-self-center text-center">
						<div class="m-l-10">
							<h6 class="mt-0 round-inner text-light">
								<?= (int)$totals['integration']['action_count'] ?> / <?= c_format($totals['integration']['action_amount']) ?>
							</h6>
							<p class="mb-0 text-light">Total Action/ Amount</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


	<!--<div class="col-xl-2 col-md-4 col-sm-6 col-12">
		<div class="card m-b-30">
			<div class="card-body box-item-card bg-info text-light">
				<div class="d-flex flex-row">
					<div class="col-2 align-self-center"><div class="round"><i class="mdi mdi-wallet"></i></div></div>
					<div class="col-10 align-self-center text-center">
						<div class="m-l-10">
							<h6 class="mt-0 round-inner text-light">
								<?= $totals['wallet_on_hold_count'] ?> / <?php echo c_format($totals['wallet_on_hold_amount']) ?>
							</h6>
							<p class="mb-0 text-light"><?= __('admin.total_on_hold') ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!--<div class="col-xl-2 col-md-4 col-sm-6 col-12">
		<div class="card m-b-30">
			<div class="card-body box-item-card bg-info text-light">
				<div class="d-flex flex-row">
					<div class="col-2 align-self-center"><div class="round"><i class="mdi mdi-wallet"></i></div></div>
					<div class="col-10 align-self-center text-center">
						<div class="m-l-10">
							<h6 class="mt-0 round-inner text-light">
								<?= $totals['wallet_request_sent_count'] ?> / <?php echo c_format($totals['wallet_request_sent_amount']) ?>
							</h6>
							<p class="mb-0 text-light"><?= __('admin.total_request_sent') ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-2 col-md-4 col-sm-6 col-12">
		<div class="card m-b-30">
			<div class="card-body box-item-card bg-info text-light">
				<div class="d-flex flex-row">
					<div class="col-2 align-self-center"><div class="round"><i class="mdi mdi-wallet"></i></div></div>
					<div class="col-10 align-self-center text-center">
						<div class="m-l-10">
							<h6 class="mt-0 round-inner text-light">
								<?= $totals['wallet_accept_count'] ?> / <?php echo c_format($totals['wallet_accept_amount']) ?>
							</h6>
							<p class="mb-0 text-light"><?= __('admin.total_accept') ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-2 col-md-4 col-sm-6 col-12">
		<div class="card m-b-30">
			<div class="card-body box-item-card bg-info text-light">
				<div class="d-flex flex-row">
					<div class="col-2 align-self-center"><div class="round"><i class="mdi mdi-wallet"></i></div></div>
					<div class="col-10 align-self-center text-center">
						<div class="m-l-10">
							<h6 class="mt-0 round-inner text-light">
								<?= $totals['wallet_cancel_count'] ?> / <?php echo c_format($totals['wallet_cancel_amount']) ?>
							</h6>
							<p class="mb-0 text-light"><?= __('admin.total_cancel') ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>-->






<div class="row">
	<div class="col-12">
		<div class="card m-b-20">
			<div class="card-body">	
				<div class="table-rep-plugin">
                    <div class="table-responsive b-0" data-pattern="priority-columns">
                        
                         <div class="text-center">
                              <?php if ($transaction ==null) {?>
                              <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:50px;"></div>
                              <h3 class="m-t-40 text-center text-muted"><?= __('admin.no_transactions_activity_yet') ?></h3>
                              <?php }
                              else {?>
                              
                        <table id="tech-companies-1" class="table  table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th class="sortTr <?= sort_order('users.username') ?>"><a href="<?= sortable_link('ReportController/admin_transaction','users.username') ?>"><?= __('admin.username') ?></a></th>
								<th>Order Total</th>
								<th class="sortTr <?= sort_order('wallet.amount') ?>"><a href="<?= sortable_link('ReportController/admin_transaction','wallet.amount') ?>"><?= __('admin.commission') ?></a></th>
								<th><?= __('admin.payment_method') ?></th>
								<th style="max-width: 150px"><?= __('admin.comment') ?></th>
								<th class="sortTr <?= sort_order('wallet.created_at') ?>"><a href="<?= sortable_link('ReportController/admin_transaction','wallet.created_at') ?>"><?= __('admin.date') ?></a></th>
								<th class="sortTr <?= sort_order('wallet.comm_from') ?>"><a href="<?= sortable_link('ReportController/admin_transaction','wallet.comm_from') ?>"><?= __('admin.comm_from') ?></a></th>
								<th class="sortTr <?= sort_order('wallet.type') ?>"><a href="<?= sortable_link('ReportController/admin_transaction','wallet.type') ?>"><?= __('admin.type') ?></a></th>
								<th class="sortTr <?= sort_order('wallet.status') ?>"><a href="<?= sortable_link('ReportController/admin_transaction','wallet.status') ?>"><?= __('admin.status') ?></a></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($transaction as $key => $value) { ?>
							<tr>
								<td><?= $key + 1 ?></td>

								<td><?php echo $value['username'] ?></td>
								<td>
									<?php if($value['integration_orders_total']){ ?>
										<?= c_format($value['integration_orders_total']) ?>
									<?php } ?>
								</td>
								<td><?= $value['amount'] ?></td>
								<td><?= $value['payment_method'] ?></td>
								<td><?= $value['comment'] ?></td>
								<td><?= $value['created_at'] ?></td>
								<td><?= $value['comm_from'] ?></td>
								<td><?= $value['dis_type'] ?></td>
								<td class="text-center">
									<?= $value['status_icon'] ?>		
									<?= $value['status'] ?>		
								</td>
							</tr>
							<?php } ?>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="100%" class="text-right">
									<div class="pagination">
										<?= $pagination ?>
									</div>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
</div>