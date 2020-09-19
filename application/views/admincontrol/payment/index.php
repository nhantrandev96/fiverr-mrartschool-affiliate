<?php 
	$db =& get_instance(); 
	$userdetails=$db->userdetails(); 
?>
<div class="page-content-wrapper ">
	
	<div class="container-fluid">
		
		<div class="row">
			<div class="col-sm-12">
				<div class="page-title-box">
					<div class="btn-group float-right">
						<ol class="breadcrumb hide-phone p-0 m-0">
							<li class="breadcrumb-item"><a href="#"><?= __('admin.admin') ?></a></li>
							<li class="breadcrumb-item active"><?= __('admin.payment') ?></li>
						</ol>
					</div>
					<h4 class="page-title"><?= __('admin.payment_control') ?></h4>
				</div>
			</div>
		</div>

		<div class="row">			
			<div class="col-lg-6 col-md-6">
				<div class="card m-b-30">
					<div class="card-body">
						<div class="col-12 align-self-center">
							<p class="mb-0 text-muted">
								<?= __('admin.total_product_commission_amount:') ?> <?php echo number_format((float)$userdetails['product_commission'], 2, '.', '');?>
							</p>
							<hr>
							<div class="col-12 align-self-center">
								<div class="col-4 align-self-center">
									<p class="mb-0 text-muted">
										<?= __('admin.paid_amount:') ?> <?php echo number_format((float)$userdetails['product_commission_paid'], 2, '.', '');?>
									</p>
								</div>
								<div class="col-4 align-self-center">
									<p class="mb-0 text-muted">
										<?= __('admin.unpaid_amount:') ?> <?php echo number_format((float)($userdetails['product_commission'] - $userdetails['product_commission_paid']), 2, '.', '');?>
									</p>
								</div>
								<div class="col-4 align-self-center">
									<p class="mb-0 text-muted">
										<?= __('admin.total_clicks:') ?> <?php echo $userdetails['product_total_click'];?>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-lg-6 col-md-6">
				<div class="card m-b-30">
					<div class="card-body">
						<div class="col-12 align-self-center">
							<p class="mb-0 text-muted">
								<?= __('admin.total_affiliate_commission_amount:') ?> <?php echo number_format((float)$userdetails['affiliate_commission'], 2, '.', '');?>
							</p>
							<hr>
							<div class="col-12 align-self-center">
								<div class="col-4 align-self-center">
									<p class="mb-0 text-muted">
										<?= __('admin.paid_amount:') ?> <?php echo number_format((float)$userdetails['affiliate_commission_paid'], 2, '.', '');?>
									</p>
								</div>
								<div class="col-4 align-self-center">
									<p class="mb-0 text-muted">
										<?= __('admin.unpaid_amount:') ?> <?php echo number_format((float)($userdetails['affiliate_commission'] - $userdetails['affiliate_commission_paid']), 2, '.', '');?>
									</p>
								</div>
								<div class="col-4 align-self-center">
									<p class="mb-0 text-muted">
										<?= __('admin.total_clicks:') ?> <?php echo $userdetails['affiliate_total_click'];?>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-lg-6 col-md-6">
				<div class="card m-b-30">
					<div class="card-body">
						<div class="col-12 align-self-center">
							<p class="mb-0 text-muted">
								<?= __('admin.total_sale_commission:') ?> <?php echo number_format((float)$userdetails['sale_commission'], 2, '.', '');?>
							</p>
							<hr>
							<div class="col-12 align-self-center">
								<div class="col-6 align-self-center">
									<p class="mb-0 text-muted">
										<?= __('admin.sale_paid:') ?> <?php echo number_format((float)$userdetails['sale_commission_paid'], 2, '.', '');?>
									</p>
								</div>
								<div class="col-6 align-self-center">
									<p class="mb-0 text-muted">
										<?= __('admin.sale_unpaid:') ?> <?php echo number_format((float)($userdetails['sale_commission'] - $userdetails['sale_commission_paid']), 2, '.', '') ;?>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row"> 
			<div class="col-lg-12 col-sm-12 align-self-center">
				<div class="card bg-white m-b-30">
					<div class="card-body new-user">
						<h6 class="header-title mb-4 mt-0"><?= __('admin.all_commission_affiliate') ?> </h6>
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th class="border-top-0"><?= __('admin.name') ?></th>
										<th class="border-top-0"><?= __('admin.email') ?></th>
										<th class="border-top-0"><?= __('admin.affiliate_clicks_commission_paid_commission_unpaid_commission') ?></th>
										<th class="border-top-0"><?= __('admin.product_clicks_commission_paid_commission_unpaid_commission') ?></th>
										<th class="border-top-0"><?= __('admin.sales_comission_paid_commission_unpaid_commission') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($allusers as $users){ ?>
										<tr>
											<td>
												<?php echo $users['firstname'];?> <?php echo $users['lastname'];?>
											</td>
											<td>                                                                
												<?php echo $users['email'];?>
											</td>
											<td>                                                                
												<?php echo !empty($users['affiliate_total_click'])? $users['affiliate_total_click'] : 0;?> / <?php echo number_format((float)$users['affiliate_commission'], 2, '.', '');?> / <?php echo !empty($users['affiliate_commission_paid'])? $users['affiliate_commission_paid'] : 0;?> / <?php echo number_format((float)($users['affiliate_commission'] - $users['affiliate_commission_paid']), 2, '.', '');?>
											</td>
											<td>                                                                
												<?php echo !empty($users['product_total_click'])? $users['product_total_click'] : 0;?> / <?php echo number_format((float)$users['product_commission'], 2, '.', '');?> / <?php echo !empty($users['product_commission_paid'])? $users['product_commission_paid'] : 0;?> / <?php echo number_format((float)($users['product_commission'] - $users['product_commission_paid']), 2, '.', '');?>
											</td>
											<td>                                                                
												<?php echo number_format((float)$users['sale_commission'], 2, '.', '');?> / <?php echo !empty($users['sale_commission_paid'])? $users['sale_commission_paid'] : 0;?> / <?php echo number_format((float)($users['sale_commission'] - $users['sale_commission_paid']), 2, '.', '');?>
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
	</div><!-- container -->
</div><!-- container -->