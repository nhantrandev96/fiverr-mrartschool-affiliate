<div class="row">
			<div class="col-12">
				<div class="card m-b-30">
					<div class="card-body">
						<div class="table-rep-plugin">
							<div class="table-responsive b-0" data-pattern="priority-columns">
								<table id="tech-companies-1" class="table  table-striped">
									<thead>
										<tr>
											<th data-priority="1"><?= __('user.order_id') ?></th>
											<th data-priority="3"><?= __('user.featured_image') ?></th>
											<th data-priority="1"><?= __('user.price') ?></th>
											<th data-priority="3"><?= __('user.payment_mode') ?></th>
											<th data-priority="3"><?= __('user.payment_status') ?></th>
											<th data-priority="3"><?= __('user.transaction') ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($buyproductlist as $product){ ?>
											<tr>
												<td>
													<div class="tooltip-copy">
														<span style="font-size: 18px;"><?php echo $product ['payment_id'];?></span><br>
													</div>
												</td>
												<td>
													<div class="tooltip-copy">
														<img width="50px" height="50px" src="<?php echo base_url();?>/assets/images/product/featured/<?php echo $product['product_featured_image'];?>" ><br>
													</div>
												</td>
												<td class="txt-cntr">
													<span style="font-size: 18px;"><?php echo $product['payment_amount'];?></span><br>
													
												</td>
												<td class="txt-cntr">
													<?php echo $product['payment_mode'];?>
												</td>
												<td class="txt-cntr">
													<?php echo $product['payment_item_status'];?>
												</td>
												<td class="txt-cntr">
													<?php echo $product['payment_id'];?>
												</td>
												
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
