		<div class="row">
			<div class="col-12">
				<div class="card m-b-30">
					<div class="card-body">
					    
					    <?php if ($buyproductlist ==null) {?>
                                    <div class="text-center">
                                    <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:25px;">
                                     <h3 class="m-t-40 text-center"><?= __('user.no_orders') ?></h3></div>
                                    <?php }
                                    else {?>
                                    
                                    
						<div class="table-rep-plugin">
							<div class="table-responsive b-0" data-pattern="priority-columns">
								<table id="tech-companies-1" class="table  table-striped">
									<thead>
										<tr>
											<th width="50px"><?= __('user.order_id') ?></th>
											<th width="80px"><?= __('user.price') ?></th>
											<th class="txt-cntr"><?= __('user.order_status') ?></th>
											<th><?= __('user.payment_method') ?></th>
											<th width="80px"><?= __('user.ip') ?></th>
											<th><?= __('user.transaction') ?></th>
											<th width="80px"></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($buyproductlist as $product){ ?>
											<tr>
												<td><?php echo $product['id'];?></td>
												<td class="txt-cntr"><?php echo c_format($product['total_sum']); ?></td>
												<td class="txt-cntr"><?php echo $status[$product['status']]; ?></td>
												<td class="txt-cntr"><?php echo str_replace("_", " ", $product['payment_method']) ?></td>
												<td class="txt-cntr"><?php echo $product['order_country_flag'];?></td>
												<td class="txt-cntr"><?php echo $product['txn_id'];?></td>
												<td>
													<a href="<?= base_url('usercontrol/vieworder/'. $product['id']) ?>" class="btn btn-primary btn-sm"><?= __('user.details') ?></a>
												</td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>		
