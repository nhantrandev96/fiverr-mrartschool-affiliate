        <div class="row">			
			<div class="col-lg-12 col-md-12 text-right">
				<div class="card-body">
					<button data-toggle="modal" data-target="#myModal" class="btn btn-primary delete-modal"> <?= __('admin.delete_order') ?> </button>
					<a href="<?php echo base_url();?>admincontrol/orderaction/<?php echo $order['id'];?>/sendemail" class="btn btn-primary btn-sm "><?= __('admin.send_email') ?></a>   &nbsp;&nbsp;&nbsp;&nbsp;
					<a href="<?php echo base_url();?>admincontrol/orderaction/<?php echo $order['id'];?>/print" target='_blank' class="btn btn-primary btn-sm "><?= __('admin.print_order') ?></a>   &nbsp;&nbsp;&nbsp;&nbsp;
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<?php if($this->session->flashdata('success')){?><div class="alert alert-success alert-dismissable"><?php echo $this->session->flashdata('success'); ?> </div>
				<?php } ?>
				
				<?php if($this->session->flashdata('error')){?><div class="alert alert-danger alert-dismissable"><?php echo $this->session->flashdata('error'); ?> </div>
				<?php } ?>
			</div>
			

		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="card m-b-30">
					<div class="card-body">
					    <h4 class="page-title">
						<?= __('admin.order') ?> : (<?= orderId($order['id']) ?>) <br>
						<?= __('admin.date') ?> : <?php  echo date("m-j-Y h:i A", strtotime($order['created_at'])); ?>
				    	</h4>
						<h5 class="header-title pb-3 mt-0">Product Info</h5>
						<table class="table table-striped">
							<thead>
								<tr>
									<th colspan="2">Name</th>
									<th width="100px">Unit Price</th>
									<th width="80px">Quantity</th>
									<th width="160px">Commission Type</th>
									<th width="190px">Commission Amount</th>
									<th width="190px">Total Discount</th>
									<th width="80px">Total</th>
								</tr>
								<?php foreach ($products as $key => $product) { ?>
									<tr>
										<td width="50px"><img src="<?= $product['image'] ?>" style="width: 50px;height: 50px"></td>
										<td>
											<?php echo $product['product_name']; ?>
											<?php if(isset($venders[$product['product_id']])) { ?>
												<br><hr>
												<b>Vender Name</b> : <?php echo $venders[$product['product_id']]['firstname']." ".$venders[$product['product_id']]['lastname'] ?>
												<br>
												<b>Vender Email</b> : <?php echo $venders[$product['product_id']]['email']; ?>
												<br>
												<b>Vender Commission</b> : <?php echo c_format($venders[$product['product_id']]['vendor_commission']); ?>
											<?php } ?>
											<?php if($product['commission']) { ?>
												<br><hr>
												<b><?= __('admin.name') ?></b> : <?php echo $product['refer_name']; ?>
												
												<br>
												<b><?= __('admin.email') ?></b> : <?php echo $product['refer_email']; ?>
												
												<br>
												<b><?= __('admin.product_commission') ?></b> : <?php echo c_format($product['commission']); ?>
											<?php } ?>
											<?php if($product['coupon_discount'] > 0){ ?>
				                                <p class="couopn-code-text">
				                                	Code : <span class="c-name"> <?= $product['coupon_code'] ?></span> Applied
				                                </p>
			                                <?php } ?>
			                                <?php if($order['status'] == 1 && $product['product_type'] == 'downloadable' && $product['downloadable_files']) { ?>
												<div class="download">	
												<?php foreach ($product['downloadable_files'] as $downloadable_filess) { ?>
													<a href="<?php echo base_url('store/downloadable_file/'. $downloadable_filess['name'] . '/' .$downloadable_filess['mask']) ?>" class="btn btn-link btn-sm" target="_blank"><?php echo $downloadable_filess['mask'] ?></a>
												<?php } ?>
												</div>
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
			<div class="col-lg-12 col-md-12">
				<div class="card m-b-30">
					<div class="card-body">
						<h5 class="header-title pb-3 mt-0"><?= __('admin.client_detail') ?></h5>
						
						<div class="">
							<table class="table table-hover">
								<thead>
									<tr>
										<th class="border-top-0"><?= __('admin.name') ?></th>
										<th class="border-top-0"><?php echo $order['firstname'];?> <?php echo $order['lastname'];?></th>
									</tr>
									<tr>
										<th class="border-top-0"><?= __('admin.email') ?></th>
										<th class="border-top-0"><?php echo $order['email'];?></th>
									</tr>
									
								</thead>
							</table>
						</div>
						
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-8 col-md-8">
				<div class="card m-b-30">
					<div class="card-body">
						<h5 class="header-title pb-3 mt-0"><?= __('admin.order_payment_info') ?></h5>
						<div class="">
							<table class="table table-striped">
								<thead>
									<th><?= __('admin.mode') ?></th>
									<th><?= __('admin.transaction_id') ?></th>
									<th><?= __('admin.payment_status') ?></th>
								</thead>
								<tbody>
									<?php foreach ($payment_history as $key => $value) { ?>
									<tr>
										<td><?php echo str_replace("_", " ", $value['payment_mode']) ?></td>
										<td><?php echo $order['txn_id'];?></td>
										<td><?php echo $value['paypal_status'] ?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<?php if($order['payment_method'] == 'bank_transfer'){ ?>
							<div class="form-group">
								<label class="control-label"><b><?= __('admin.bank_transfer_instruction') ?></b></label>
								<pre class="well"><?php echo $paymentsetting['bank_transfer_instruction'] ?></pre>
							</div>
						<?php } ?>

						<?php if($order['comment']){ ?>
							<div class="form-group">
								<label class="control-label"><b><?= __('admin.order_view_comment') ?></b></label>
								<pre class="well"><?php echo $order['comment'] ?></pre>
							</div>
						<?php } ?>

						<?php if($order['files']){ ?>
							<div class="form-group">
								<label class="control-label"><b><?= __('admin.order_attechments_download') ?></b></label>
								<div><?php echo $order['files'] ?></div>
							</div>
						<?php } ?>
						<?php if($order['order_country']){ ?>
							<div class="form-group">
								<label class="control-label"><b><?= __('admin.order_done_from') ?></b></label>
								<div>
									<?php echo $order['order_country'];?><?php echo $order['order_country_flag'];?>
								</div>
							</div>
						<?php  } ?>

						<?php if($orderProof){ ?>
							<div class="form-group">
								<label class="control-label"><b><?= __('store.payment_proof') ?></b>
									<a href="<?= $orderProof->downloadLink ?>" target='_blank'>: <?= __('store.download') ?></a>
								</label>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php if($order['allow_shipping']){ ?>
				<div class="col-lg-4 col-md-4">
					<div class="card m-b-30">
						<div class="card-body">
							<h5 class="header-title pb-3 mt-0">Shipping Details</h5>
								<div class="table-responsive">
									<table class="table table-hover">
										<thead>
											<tr>
												<th><?= __('admin.phone') ?></th>
												<td><?php echo $order['phone'] ?></td>
											</tr>
											<tr>
												<th><?= __('admin.address') ?></th>
												<td><?php echo $order['address'] ?></td>
											</tr>
											<tr>
												<th><?= __('admin.country') ?></th>
												<td><?php echo $order['country_name'] ?></td>
											</tr>
											<tr>
												<th><?= __('admin.state') ?></th>
												<td><?php echo $order['state_name'] ?></td>
											</tr>
											<tr>
												<th><?= __('admin.city') ?></th>
												<td><?php echo $order['city'] ?></td>
											</tr>
											<tr>
												<th><?= __('admin.postal_code') ?></th>
												<td><?php echo $order['zip_code'] ?></td>
											</tr>
										</thead>
									</table>
								</div>
						</div>
					</div>
				</div>
			<?php }  ?>
		</div>
		
		<div class="row"> 
			<div class="col-lg-12 col-sm-12 align-self-center">
				<div class="card bg-white m-b-30">
					<div class="card-body new-user">
						<h5 class="header-title mb-4 mt-0"><?= __('admin.update_order_status') ?></h5>
						<div class="row text-left">
							<div class="col-sm-6">
								<form class="form-horizontal" method="post" action=""  enctype="multipart/form-data">
									<div class="form-group">
										<label class="control-label"><?= __('admin.order_status') ?></label>
										<select name="payment_item_status" id="payment_item_status" required="required" class="form-control">
											<option value=""><?= __('admin.please_choose') ?></option>
											<?php foreach ($status as $key => $value) { ?>
												<option value="<?php echo $key ?>" ><?php echo $value ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group">
										<label class="control-label"><?= __('admin.comment') ?></label>
										<textarea name="remarks" id="remarks" class="form-control" cols="60" rows="3" required="required"></textarea>
									</div>
									<button name="submit" class="btn btn-primary" type="submit"><?= __('admin.submit') ?></button>
								</form>
							</div>
							<div class="col-sm-6">
								<table class="table table-striped">
									<thead>
										<tr>
											<th width="50px">#</th>
											<th width="150px"><?= __('admin.status') ?></th>
											<th><?= __('admin.comment') ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($order_history as $key => $value) { ?>
										<tr>
											<td>#<?= $key+1 ?></td>
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


<!-- The Modal -->
<div class="modal fade" id="myModal" role="dailog" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h4 class="text-center"><?= __('admin.are_you_sure') ?></h4>
        <br><br>
        <center>
        	<a href="<?php echo base_url();?>admincontrol/orderaction/<?php echo $order['id'];?>/delete/0" class="btn btn-danger"><?= __('admin.delete_order') ?></a>
        	<a href="<?php echo base_url();?>admincontrol/orderaction/<?php echo $order['id'];?>/delete/1" class="btn btn-danger"><?= __('admin.delete_order_with_commitions') ?></a>
        	<div class="clearfix"></div><br>
    		<button type="button" class="btn btn-primary" data-dismiss="modal"><?= __('admin.cancel') ?></button>
        </center>
      </div>
    </div>
  </div>
</div>