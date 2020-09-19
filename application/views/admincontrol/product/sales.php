<div class="page-content-wrapper ">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="page-title-box">
					<div class="btn-group float-right">
						<ol class="breadcrumb hide-phone p-0 m-0">
							<li class="breadcrumb-item"><a href="#"><?= __('admin.admin') ?></a></li>
							<li class="breadcrumb-item"><a href="#"><?= __('admin.manage') ?></a></li>
						<li class="breadcrumb-item active"><?= __('admin.orders_sales') ?></li>
						</ol>
					</div>
					<h4 class="page-title"><?= __('admin.all_sales_order_list') ?></h4>
				</div>
			</div>
		</div>
		<!-- end page title end breadcrumb -->
		
		<div class="row">
			<div class="col-12">
				<div class="card m-b-30">
					<div class="card-body">
						<div class="table-rep-plugin">
							<div class="table-responsive b-0" data-pattern="priority-columns">
								<table id="tech-companies-1" class="table  table-striped">
									<thead>
										<tr>
											<th data-priority="1"><?= __('admin.order_id') ?></th>
											<th data-priority="3"><?= __('admin.product_name') ?></th>
											<th data-priority="1"><?= __('admin.billing_name') ?></th>
											<th data-priority="1"><?= __('admin.price') ?></th>
											<th data-priority="3"><?= __('admin.transaction_id') ?></th>
											<th data-priority="3"><?= __('admin.payment_status') ?></th>
											<th data-priority="3"><?= __('admin.payment_mode') ?></th>
											<th data-priority="3"><?= __('admin.order_status') ?></th>
											<td data-priority="3"><?= __('admin.action') ?></td>
										</tr>
									</thead>
									<tbody>
										<?php foreach($getallorders as $product){ ?>
											<tr>
												<td>
													<div class="tooltip-copy">
														<span style="font-size: 18px;">#0000<?php echo $product['payment_id'];?></span><br>
														
													</div>
												</td>
												<td>
													<div class="tooltip-copy">
														<?php echo $product['product_name'];?>
													</div>
												</td>
												
												<td class="txt-cntr">
													<?php echo $product['firstname'];?> <?php echo $product['lastname'];?>
												</td>
												<td class="txt-cntr">
													<span style="font-size: 18px;"><?php echo $product['payment_price'].'.00';?></span><br>
													
												</td>
												
												<td class="txt-cntr">
													<?php echo $product['payment_transaction_id'];?>
												</td>
												<td class="txt-cntr">
													<?php echo $product['payment_mode'];?>
												</td>
												<td class="txt-cntr">
													<?php echo $product['payment_status'];?>
												</td>
												
												<td class="txt-cntr">
													<?php echo $product['payment_item_status'];?>
												</td>
												
												<td class="txt-cntr">
													<a href="<?php echo base_url();?>admincontrol/vieworder/<?php echo $product['payment_id'];?>"><?= __('admin.view') ?></a>
													
												</td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div> <!-- end col -->
			</div> <!-- end row -->
			
		</div><!-- container -->
		
	</div> <!-- Page content Wrapper -->
</div> <!-- Page content Wrapper -->
<script type="text/javascript" async="">
	function shareinsocialmedia(url){
		window.open(url,'sharein','toolbar=0,status=0,width=648,height=395');
		return true;
	}
</script>											