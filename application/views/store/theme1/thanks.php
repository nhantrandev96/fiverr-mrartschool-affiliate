<!DOCTYPE html>
<html lang="en">
	<head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
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
        <!-- Loader -->
        <div id="preloader"><div id="status"><div class="spinner"></div></div></div>
		<div class="page-content-wrapper ">
			<div class="container">
				<div class="no-print">
					<button class="btn btn-primary print"><?= __('store.print') ?></button>
					<a href="<?= base_url('store/order') ?>" class="btn btn-default"><?= __('store.back_to_dashboard') ?></a>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="page-title-box">
							<div class="card m-b-30">
								<div class="card-body">
									<center>
										<h1> <?= __('store.order_status') ?> (#<?php echo orderId($order['id']); ?>)</h1>
										<h4><?= __('store.thank_you_for_purchasing_an_order') ?><h4>
									</center>
									<br>
									<p><?= __('store.thank_msg') ?></p>
								</div>
							</div>
							</div>
						</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<div class="card m-b-30">
									<div class="card-body">
										<h5 class="header-title pb-3 mt-0"><?= __('store.product_info') ?></h5>
										<table class="table table-striped">
											<thead>
												<tr>
													<th colspan="2"><?= __('store.name') ?></th>
													<th><?= __('store.unit_price') ?></th>
													<th><?= __('store.quantity') ?></th>
													<th><?= __('store.discount') ?></th>
													<th><?= __('store.total') ?></th>
												</tr>
												<?php foreach ($products as $key => $product) { ?>
													<tr>
														<td><img src="<?= $product['image'] ?>" style="width: 50px;height: 50px"></td>
														<td>
															<?php echo $product['product_name'];?>
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
														<td><?php echo c_format($product['coupon_discount']); ?></td>
														<td><?php echo c_format($product['total']); ?></td>
													</tr>
												<?php } ?>
												<?php foreach ($totals as $key => $total) { ?>
												<tr>
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
										<h5 class="header-title pb-3 mt-0"><?= __('store.order_payment_info') ?></h5>
										<div class="table-responsive">
											<table class="table table-striped">
												<thead>
													<th class="border-top-0"><?= __('store.mode') ?></th>
													<th class="border-top-0"><?= __('store.transaction_id') ?></th>
													<th class="border-top-0"><?= __('store.payment_status') ?></th>
												</thead>
												<tbody>
													<?php if($order['status'] == 0){ ?>
														<tr>
															<td colspan="100%">
																<p class="text-muted text-center"> <?= __('store.waiting_for_payment_status') ?> </p>
															</td>
														</tr>
													<?php } ?>
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
												<label class="control-label"><b><?= __('store.order_done_from') ?></b></label>
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
									<div class="card m-b-30">
										<div class="card-body">
											<h5 class="header-title pb-3 mt-0"><?= __('store.shipping_details') ?></h5>
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
										</div>
									</div>
								</div>
							<?php }  ?>
						</div>
						<div class="row">
							<div class="col-lg-12 col-sm-12 align-self-center">
								<div class="card bg-white m-b-30">
									<div class="card-body new-user">
										<h5 class="header-title mb-4 mt-0"><?= __('store.update_order_status') ?></h5>
										<table class="table table-striped">
											<thead>
												<tr>
													<th width="50px">#</th>
													<th width="150px"><?= __('store.status') ?></th>
													<th><?= __('store.comment') ?></th>
												</tr>
											</thead>
											<tbody>
												<?php if(!$order_history){ ?>
													<tr>
														<td colspan="100%">
															<p class="text-muted text-center"><?= __('store.no_any_order_status') ?> </p>
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
					</div><!-- container -->
				</div><!-- container -->
				<div class="no-print">
					<button class="btn btn-primary print"><?= __('store.print') ?></button>
					<a href="<?= base_url('store/order') ?>" class="btn btn-default"><?= __('store.back_to_dashboard') ?></a>
				</div>
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
				<!-- App js -->
				<script src="<?php echo base_url(); ?>assets/js/app.js"></script>
				<script>
					/* BEGIN SVG WEATHER ICON */
					if (typeof Skycons !== 'undefined'){
						var icons = new Skycons(
						{"color": "#fff"},
						{"resizeClear": true}
						),
						list  = [
						"clear-day", "clear-night", "partly-cloudy-day",
						"partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",
						"fog"
						],
						i;
						for(i = list.length; i--; )
						icons.set(list[i], list[i]);
						icons.play();
					};
					// scroll
					$(document).on('ready',function() {
						if($("#boxscroll").length > 0){
							$("#boxscroll").niceScroll({cursorborder:"",cursorcolor:"#cecece",boxzoom:true});
						}
						if($("#boxscroll2").length > 0){
							$("#boxscroll2").niceScroll({cursorborder:"",cursorcolor:"#cecece",boxzoom:true});
						}
					});
					$(document).ready(function($) {
						if($(".clickable-row").length > 0){
							$(".clickable-row").on('click',function() {
								window.location = $(this).data("href");
							});
						}
					});
				</script>
				<!-- Responsive-table-->
				<script src="<?php echo base_url(); ?>assets/vertical/assets/plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js" type="text/javascript"></script>
				<script>
					$(".print").on('click',function(){
						window.print();
					})
				</script>
			</body>
		</html>
		