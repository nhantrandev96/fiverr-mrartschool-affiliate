
<div class="card m-t-30">
	<div class="card-header">
		<h4 class="card-title pull-left"> <?= __('user.order_details') ?> </h4>
	</div>
	<div class="card-body">
		
		<?php if($this->session->flashdata('success')){?>
			<div class="alert alert-success alert-dismissable"><?php echo $this->session->flashdata('success'); ?> </div>
		<?php } ?>
		<?php if($this->session->flashdata('error')){?><div class="alert alert-danger alert-dismissable"><?php echo $this->session->flashdata('error'); ?> </div><?php } ?>
		
		<div class="row">
			<div class="col-lg-12">
				<div class=" m-b-30">
					<div class="card-body">
						<table class="table table-striped">
							<thead>
								<tr>
									<th colspan="2"><?= __('user.name') ?></th>
									<th><?= __('user.unit_price') ?></th>
									<th><?= __('user.quantity') ?></th>
									<th><?= __('user.commission_type') ?></th>
									<th><?= __('user.commission_amount') ?></th>
									<th><?= __('user.total_discount') ?></th>
									<th><?= __('user.total') ?></th>
								</tr>
								<?php foreach ($products as $key => $product) { ?>
									<tr>
										<td><img src="<?= $product['image'] ?>" style="width: 50px;height: 50px"></td>
										<td>
											<?php echo $product['product_name'];?>
											<?php if($product['coupon_discount'] > 0){ ?>
				                                <p class="couopn-code-text">
				                                	<?= __('user.code') ?> : <span class="c-name"> <?= $product['coupon_code'] ?></span> <?= __('user.applied') ?>
				                                </p>
			                                <?php } ?>
										</td>
										<td><?php echo c_format($product['price']); ?></td>
										<td><?php echo $product['quantity']; ?></td>
										<td><?php echo $product['commission_type']; ?></td>
										<td><?php echo c_format($product['commission']);  ?></td>
										<td><?php echo c_format($product['coupon_discount']);  ?></td>
										<td><?php echo c_format($product['total']); ?></td>
									</tr>
								<?php } ?>
								<?php foreach ($totals as $key => $total) { ?>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td><?= $total['text'] ?></td>
									<td><?php echo c_format($total['value']); ?></td>
								</tr>
								<?php } ?>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-8 col-md-8">
				<div class=" m-b-30">
					<div class="card-body">
						<h5 class="header-title pb-3 mt-0"><?= __('user.order_payment_info') ?></h5>
						<div class="table-responsive">
							<table class="table table-striped">
								<thead>
									<th class="border-top-0"><?= __('user.mode') ?></th>
									<th class="border-top-0"><?= __('user.transaction_id') ?></th>
									<th class="border-top-0"><?= __('user.payment_status') ?></th>
								</thead>
								<tbody>
									<?php if($order['status'] == 0){ ?>
										<tr>
											<td colspan="100%">
												<p class="text-muted text-center"> <?= __('user.waiting_for_payment_status') ?> </p>
											</td>
										</tr>
									<?php } ?>
									<?php foreach ($payment_history as $key => $value) { ?>
									<tr>
										<td><?php echo $value['payment_mode'];?></td>
										<td><?php echo $order['txn_id'];?></td>
										<td><?php echo $value['paypal_status'] ?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<?php if($order['order_country']){ ?>
							<div class="form-group">
								<label class="control-label"><b><?= __('user.order_done_from') ?></b></label>
								<div>
									<?php echo $order['order_country'];?><?php echo $order['order_country_flag'];?>
								</div>
							</div>
						<?php  } ?>
					</div>
				</div>
			</div>
			<?php if($order['allow_shipping']){ ?>
				<div class="col-lg-4 col-md-4">
					<div class=" m-b-30">
						<div class="card-body">
							<h5 class="header-title pb-3 mt-0"><?= __('user.shipping_details') ?></h5>
							<div class="">
								<table class="table table-hover">
									<thead>
										<tr>
											<th><?= __('user.address') ?></th>
											<td><?php echo $order['address'] ?></td>
										</tr>
										<tr>
											<th><?= __('user.country') ?></th>
											<td><?php echo $order['country_name'] ?></td>
										</tr>
										<tr>
											<th><?= __('user.state') ?></th>
											<td><?php echo $order['state_name'] ?></td>
										</tr>
										<tr>
											<th><?= __('user.city') ?></th>
											<td><?php echo $order['city'] ?></td>
										</tr>
										<tr>
											<th><?= __('user.postal_code') ?></th>
											<td><?php echo $order['zip_code'] ?></td>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-sm-12 align-self-center">
				<div class=" m-b-30">
					<div class="card-body new-user">
						<h5 class="header-title mb-4 mt-0"><?= __('user.update_order_status') ?></h5>
						<table class="table table-striped">
							<thead>
								<tr>
									<th width="50px">#</th>
									<th width="150px"><?= __('user.status') ?></th>
									<th><?= __('user.comment') ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if(!$order_history){ ?>
									<tr>
										<td colspan="100%">
											<p class="text-muted text-center"><?= __('user.no_any_order_status') ?></p>
										</td>
									</tr>
								<?php } ?>
								<?php foreach ($order_history as $key => $value) { ?>
								<tr>
									<td>#<?= $key ?></td>
									<td><?= $status[$value['order_status_id']] ?></td>
									<td><?= $value['comment'] ?></td>
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