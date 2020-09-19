<!DOCTYPE html>
<html lang="en">
	<head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title><?= __('admin.admin_control_panel') ?> </title>
        <meta content="Admin Dashboard" name="description" />
        <meta content="Mannatthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<link rel="shortcut icon" href="assets/images/favicon.ico">
        <link href="<?php echo base_url(); ?>assets/vertical/assets/plugins/morris/morris.css?v=<?= av() ?>" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/vertical/assets/css/bootstrap.min.css?v=<?= av() ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>assets/vertical/assets/css/icons.css?v=<?= av() ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>assets/vertical/assets/css/style.css?v=<?= av() ?>" rel="stylesheet" type="text/css">
		<link href="<?php echo base_url(); ?>assets/vertical/assets/plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css?v=<?= av() ?>" rel="stylesheet" type="text/css" media="screen">
	</head>
	
	<body class="fixed-left">
<div class="page-content-wrapper ">
	<div class="container-fluid">
		
		<div class="row">
			<div class="col-sm-12">
				<div class="page-title-box">
					<div class="btn-group float-right">
						<ol class="breadcrumb hide-phone p-0 m-0">
							<li class="breadcrumb-item"><a href="#"><?= __('admin.admin') ?></a></li>
							<li class="breadcrumb-item active"><?= __('admin.order') ?></li>
						</ol>
					</div>
					<h4 class="page-title"><?= __('admin.order') ?> (<?= orderId($order['id']) ?>)</h4>
				</div>
			</div>
		</div>
		<!-- end page title end breadcrumb -->
		<div class="row">
			
			<div class="col-lg-12">
				<div class="card m-b-30">
					<div class="card-body">
						<h5 class="header-title pb-3 mt-0"><?= __('admin.order_user_by') ?></h5>
						<div class="table-responsive">
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
									<tr>
										<th class="border-top-0"><?= __('admin.product_commission') ?></th>
										<th class="border-top-0">
											<?php echo c_format($order['commission']); ?>
										</th>
									</tr>

								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-12">
				<div class="card m-b-30">
					<div class="card-body">
						<h5 class="header-title pb-3 mt-0">Product Info</h5>
						<table class="table table-striped">
							<thead>
								<tr>
									<th colspan="2">Name</th>
									<th width="100px">Unit Price</th>
									<th width="80px">Total</th>
									<th width="80px">Quantity</th>
									<th width="160px">Commission Type</th>
									<th width="190px">Commission Amount</th>
								</tr>
								<?php foreach ($products as $key => $product) { ?>
									<tr>
										<td width="50px"><img src="<?= $product['image'] ?>" style="width: 50px;height: 50px"></td>
										<td>
											<?php echo $product['product_name']; ?>
											<?php if($product['commission']) { ?>
												<br><hr>
												<b><?= __('admin.name') ?></b> : <?php echo $product['refer_name']; ?>
												
												<br>
												<b><?= __('admin.email') ?></b> : <?php echo $product['refer_email']; ?>
												
												<br>
												<b><?= __('admin.product_commission') ?></b> : <?php echo $product['commission']; ?>
											<?php } ?>
										</td>
										<td><?php echo c_format($product['price']); ?></td>
										<td><?php echo $product['quantity']; ?></td>
										<td><?php echo c_format($product['quantity'] * $product['price']); ?></td>
										<td><?php echo $product['commission_type']; ?></td>
										<td><?php echo c_format($product['commission']);  ?></td>
									</tr>
								<?php } ?>
								<?php foreach ($totals as $key => $total) { ?>
								<tr>									
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
								<label class="control-label"><b><?= __('store.bank_transfer_instruction') ?></b></label>
								<pre class="well"><?php echo $paymentsetting['bank_transfer_instruction'] ?></pre>
							</div>
						<?php } ?>

						<?php if($order['comment']){ ?>
							<div class="form-group">
								<label class="control-label"><b><?= __('store.order_view_comment') ?></b></label>
								<pre class="well"><?php echo $order['comment'] ?></pre>
							</div>
						<?php } ?>

						<?php if($order['files']){ ?>
							<div class="form-group">
								<label class="control-label"><b><?= __('store.order_attechments_download') ?></b></label>
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
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-4">
				<div class="card m-b-30">
					<div class="card-body">
						<h5 class="header-title pb-3 mt-0">Shipping Details</h5>
						<?php if($order['allow_shipping']){ ?>
							<div class="table-responsive">
								<table class="table table-hover">
									<thead>
										<tr>
											<th><?= __('store.address') ?></th>
											<td><?php echo $order['address'] ?></td>
										</tr>
										<tr>
											<th><?= __('store.country') ?></th>
											<td><?php echo $order['country_name'] ?></td>
										</tr>
										<tr>
											<th><?= __('store.state') ?></th>
											<td><?php echo $order['state_name'] ?></td>
										</tr>
										<tr>
											<th><?= __('store.city') ?></th>
											<td><?php echo $order['city'] ?></td>
										</tr>
										<tr>
											<th><?= __('store.postal_code') ?></th>
											<td><?php echo $order['zip_code'] ?></td>
										</tr>
									</thead>
								</table>
							</div>
						<?php } else { ?>
							<div class="alert alert-info"><?= __('store.shipping_not_allows') ?></div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row"> 
			<div class="col-lg-12 col-sm-12 align-self-center">
				<div class="card bg-white m-b-30">
					<div class="card-body new-user">
						<h5 class="header-title mb-4 mt-0"><?= __('admin.update_order_status') ?></h5>
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
	</div><!-- container -->
</div><!-- container -->
<!-- jQuery  -->
<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/popper.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/modernizr.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/detect.js"></script>
<script src="<?php echo base_url(); ?>assets/js/fastclick.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.blockUI.js"></script>
<script src="<?php echo base_url(); ?>assets/js/waves.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.nicescroll.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.scrollTo.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vertical/assets/plugins/skycons/skycons.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vertical/assets/plugins/raphael/raphael-min.js"></script>
<script src="<?php echo base_url(); ?>assets/vertical/assets/plugins/morris/morris.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/dashborad.js"></script>
<script src="<?php echo base_url(); ?>assets/js/app.js"></script>
<script>
	window.print();
</script>
</body>
</html>
