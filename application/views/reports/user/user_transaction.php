
<div class="row">
	<div class="col-12">
		<div class="card m-b-20">
			<div class="card-body">
			    
			    <?php if ($transaction ==null) {?>
                                    <div class="text-center">
                                    <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:25px;">
                                     <h3 class="m-t-40 text-center"><?= __('user.no_transactions') ?></h3></div>
                                    <?php }
                                    else {?>
                                    
				<div class="table-rep-plugin">
                    <div class="table-responsive b-0" data-pattern="priority-columns">
                        <table id="tech-companies-1" class="table  table-striped">
						<thead>
							<tr>
								<th width="50px"></th>
								<th>Order Total</th>
								<th class="sortTr <?= sort_order('wallet.amount') ?>" width="100px"><a href="<?= sortable_link('ReportController/user_transaction','wallet.amount') ?>"><?= __('user.commission') ?></a></th>
								<th width="180px"><?= __('user.payment_method') ?></th>
								<th style="min-width: 250px"><?= __('user.comment') ?></th>
								<th class="sortTr <?= sort_order('wallet.created_at') ?>" width="200px"><a href="<?= sortable_link('ReportController/user_transaction','wallet.created_at') ?>"><?= __('user.date') ?></a></th>
								<th class="sortTr <?= sort_order('wallet.comm_from') ?>" width="150px"><a href="<?= sortable_link('ReportController/user_transaction','wallet.comm_from') ?>"><?= __('user.comm_from') ?></a></th>
								<th class="sortTr <?= sort_order('wallet.type') ?>" width="150px"><a href="<?= sortable_link('ReportController/user_transaction','wallet.type') ?>"><?= __('user.type') ?></a></th>
								<th class="sortTr <?= sort_order('wallet.status') ?>" width="150px"><a href="<?= sortable_link('ReportController/user_transaction','wallet.status') ?>"><?= __('user.status') ?></a></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($transaction as $key => $value) { ?>
							<tr>
								<td><?= $key + 1 ?></td>
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
			<?php } ?>
		</div>
	</div>
</div>
</div>