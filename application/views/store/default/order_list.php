<div class="page-content-wrapper ">
	<div class="container">
		<h1><?= __('store.orders') ?></h1>
		<!-- end page title end breadcrumb -->		
		<div class="row">
			<div class="col-12">
				<div class="card m-b-30">
					<div class="card-body">
						<div class="table-rep-plugin">
							<div class="table-responsive b-0" data-pattern="priority-columns">
								<table class="table  table-striped">
									<thead>
										<tr>
											<th><?= __('client.order_id') ?></th>
											<th class="txt-cntr"><?= __('client.price') ?></th>
											<th class="txt-cntr"><?= __('client.order_status') ?></th>
											<th class="txt-cntr"><?= __('client.payment_method') ?></th>
											<th class="txt-cntr"><?= __('client.transaction') ?></th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php if($buyproductlist) { ?>
											<?php foreach($buyproductlist as $product){ ?>
												<tr>
													<td><?php echo $product['id'];?></td>
													<td class="txt-cntr"><?php echo c_format($product['total_sum']); ?></td>
													<td class="txt-cntr"><?php echo $status[$product['status']]; ?></td>
													<td class="txt-cntr"><?php echo str_replace("_", " ", $product['payment_method']);?></td>
													<td class="txt-cntr"><?php echo $product['txn_id'];?></td>
													<td>
														<a href="<?= base_url('store/vieworder/'. $product['id']) ?>" class="btn btn-primary btn-sm"><?= __('client.details') ?></a>
													</td>
												</tr>
											<?php } ?>
										<?php } else { ?>
											<tr>
												<td colspan="5"><div class="text-info text-center">No Orders Found!</div></td>
											</tr>
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
</div>