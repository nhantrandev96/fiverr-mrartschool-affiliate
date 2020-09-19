<?php 
    $payment_done = true;
    if($paymentlist['payment_account_number'] != '' || $paymentlist['payment_bank_name'] != '' || $paymentlist['payment_account_name'] != '' || $paymentlist['payment_ifsc_code'] != ''){
        $payment_done = false;
    }
    if($paymentlist['paypalaccounts']['paypal_email'] != ''){
        $payment_done = false;
    }
    if($payment_done){
?>

<div class="alert alert-warning" role="alert">
  <?= __('user.please_update_your_bank_details_or_paypal_account_details') ?> <a href="<?php echo base_url('usercontrol/addpayment') ?>"><?= __('user.click_here') ?></a>
</div>
<?php } ?>

<div class="row">
	<div class="col-12">
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="tab" href="#tab-wallet">My Wallet</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#tab-payout">My Payout</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#tab-paymentdetails">Payment Details</a>
			</li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane active" id="tab-wallet">
				<div class="card m-b-20">
					<?php 
						$allow_with = false;
						if( (float)$totals['wallet_unpaid_amount'] >= (float)$site_setting['wallet_min_amount']){
							$allow_with = true;
						}
					?>

                    <div class="card">
						<div class="card-header">
						    
						    <div class="row">
					<div class="col-sm-3">
						<div class="card m-b-30 text-white bg-info">
                            <div class="card-body">
                                <blockquote class="card-bodyquote mb-0">
                                 <h6 class="mt-0 round-inner">
									<?php echo $totals['all_clicks'] ?> / 
									<?php echo c_format($totals['all_clicks_comm']) ?></h6>
                                 	<p class="mb-0 text-muted"><?= __('user.total_click_commition') ?></p>
                                </blockquote>
                            </div>
                        </div>
					</div>
					<div class="col-sm-3">
						<div class="card m-b-30 text-white bg-info">
                            <div class="card-body">
                                <blockquote class="card-bodyquote mb-0">
                                    	<h6 class="mt-0 round-inner">
								<?php echo $totals['total_sale_count']; ?>/
								<?php echo c_format($totals['all_sale_comm']); ?>
							</h6>
                                 	<p class="mb-0 text-muted"><?= __('user.sale_ommition') ?></p>
                                </blockquote>
                            </div>
                        </div>
					</div>
					<div class="col-sm-3">
						<div class="card m-b-30 text-white bg-info">
                            <div class="card-body">
                                <blockquote class="card-bodyquote mb-0">
                                    	<h6 class="mt-0 round-inner">
								<?php echo c_format($totals['wallet_accept_amount']); ?>/
								<?php echo c_format($totals['wallet_request_sent_amount']); ?>/
								<?php echo c_format($totals['unpaid_commition']); ?>
							</h6>
                                 	<p class="mb-0 text-muted"><?= __('user.paid_request_unpaid') ?> </p>
                                </blockquote>
                            </div>
                        </div>
					</div>
					<div class="col-sm-3">
						<div class="card m-b-30 text-white bg-info">
                            <div class="card-body">
                                <blockquote class="card-bodyquote mb-0">
                                   <h6 class="mt-0 round-inner"> <?= (int)$totals['integration']['action_count'] ?> / <?= c_format($totals['integration']['action_amount']) ?></h6>
                                 	<p class="mb-0 text-muted">Total Action/ Amount</p>
                                </blockquote>
                            </div>
                        </div>
					</div>
					
				</div>
				
				
							<form method="GET">
								<div class="row">
									<div class="col-sm-2">
										<div class="form-group">
											<label class="control-label">Type</label>
											<select name="type" class="form-control">
												<option value="">All</option>
												<option value="actions" <?= isset($_GET['type']) && $_GET['type'] == 'actions' ? 'selected' : '' ?>>Actions</option>
												<option value="clicks" <?= isset($_GET['type']) && $_GET['type'] == 'clicks' ? 'selected' : '' ?>>Clicks</option>
												<option value="sale" <?= isset($_GET['type']) && $_GET['type'] == 'sale' ? 'selected' : '' ?>>Sale</option>
												<option value="external_integration" <?= isset($_GET['type']) && $_GET['type'] == 'external_integration' ? 'selected' : '' ?>>External Integration</option>
											</select>
										</div>
									</div>
									<div class="col-sm-5">
										<div class="form-group">
											<label class="control-label d-block">&nbsp;</label>
											<button class="btn btn-primary">Filter</button>
											<?php if($allow_with){ ?>
												<button type="button" class="btn btn-primary withdrawal-all" >Withdrawal all <small><?= c_format($totals['wallet_unpaid_amount']) ?></small></button>
											<?php } else { ?>
												<button type="button" class="btn btn-primary" data-toggle="modal" href='#withdrawal-limit'>Withdrawal all <small><?= c_format($totals['wallet_unpaid_amount']) ?></small></button>
											<?php } ?>
										</div>
									</div>
									
								</div>
							</form>
						</div>
						<div class="card-body">
							<?php if ($transaction ==null) {?>
				                <div class="text-center">
				                <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:25px;">
				                 <h3 class="m-t-40 text-center"><?= __('user.no_transactions') ?></h3></div>
				            <?php } else { ?>         
								<div class="table-responsive">
									<table class="table table-sortable wallet-table">
										<thead>
											<tr>
												<th scope="col">#</th>
												<th></th>
												<th scope="col">Order Total</th>
												<th class="sortTr <?= sort_order('wallet.amount') ?>" scope="col"><a href="<?= sortable_link('usercontrol/mywallet/','wallet.amount') ?>"><?= __('user.commission') ?></a></th>
												<th scope="col"><?= __('user.payment_method') ?></th>
												<th class="sortTr <?= sort_order('wallet.created_at') ?>" scope="col"><a href="<?= sortable_link('usercontrol/mywallet/','wallet.created_at') ?>"><?= __('user.date') ?></a></th>
												<th class="sortTr <?= sort_order('wallet.comm_from') ?>" scope="col"><a href="<?= sortable_link('admincontrol/mywallet','wallet.comm_from') ?>"><?= __('user.comm_from') ?></a></th>
												<th class="sortTr <?= sort_order('wallet.type') ?>" scope="col"><a href="<?= sortable_link('usercontrol/mywallet/','wallet.type') ?>"><?= __('user.type') ?></a></th>
												<th class="sortTr <?= sort_order('wallet.status') ?>" scope="col"><a href="<?= sortable_link('usercontrol/mywallet/','wallet.status') ?>"><?= __('user.status') ?></a></th>
											</tr>
										</thead>
										<tbody>
											<?= $table ?>
										</tbody>
									</table>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="tab-payout">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">My Payout</h4>
					</div>
					<div class="card-body">
						
						<?php if ($payout_transaction ==null) {?>
                            <div class="text-center">
                                <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:25px;">
                                <h3 class="m-t-40 text-center"><?= __('user.no_transactions') ?></h3>
                         	</div>
                        <?php } else { ?>
							<div class="card-body p-0">
								<table class="table table-striped">
									<thead>
										<tr>
											<th width="50px">#</th>
											<th width="80px"><?= __('user.amount') ?></th>
											<th style="min-width: 250px"><?= __('user.comment') ?></th>
											<th width="200px"><?= __('user.date') ?></th>
											<th width="150px"><?= __('user.type') ?></th>
											<th width="150px"><?= __('user.status') ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($payout_transaction as $key => $value) { ?>
										<tr>
											<td><?= $key + 1 ?></td>
											<td><?= c_format($value['amount']) ?></td>
											<td><?= parseMessage($value['comment'],$value,'usercontrol') ?></td>
											<td><?= $value['created_at'] ?></td>
											<td><?= wallet_type($value) ?></td>
											<td><?= $status[$value['status']] ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="tab-paymentdetails">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">My Payment Details</h4>
					</div>
					<div class="card-body">
						
						<form class="form-horizontal" method="post" action="<?= base_url('usercontrol/addpayment') ?>"  enctype="multipart/form-data">
							<?php if($this->session->flashdata('success')){?>
								<div class="alert alert-success alert-dismissable">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<?php echo $this->session->flashdata('success'); ?> </div>
							<?php } ?>
							
							<?php if($this->session->flashdata('error')){?>
								<div class="alert alert-danger alert-dismissable">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<?php echo $this->session->flashdata('error'); ?> </div>
							<?php } ?>

							<input type="hidden" name="payment_id" value="<?= $paymentlist['payment_id'] ?>">
							<div class="form-group row">
								<label class="col-sm-3 col-form-label"><?= __('user.bank_name') ?> </label>
								<div class="col-sm-9">
									<input placeholder="Enter your Bank Name" name="payment_bank_name" value="<?php echo $paymentlist['payment_bank_name']; ?>" class="form-control" required="required" type="text">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 col-form-label"><?= __('user.account_number') ?></label>
								<div class="col-sm-9">
									<input placeholder="Enter your Account Number" name="payment_account_number" value="<?php echo $paymentlist['payment_account_number']; ?>" class="form-control" required="required" type="text">
								</div>
							</div>
							
							<div class="form-group row">
								<label class="col-sm-3 col-form-label"><?= __('user.account_name') ?></label>
								<div class="col-sm-9">
									<input placeholder="Enter your Account Name" name="payment_account_name" class="form-control" value="<?php echo $paymentlist['payment_account_name']; ?>" required="required" type="text">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 col-form-label"><?= __('user.ifsc_code') ?> </label>
								<div class="col-sm-9">
									<input placeholder="Enter your IFSC Code" name="payment_ifsc_code" id="payment_ifsc_code" class="form-control" value="<?php echo $paymentlist['payment_ifsc_code']; ?>" required="required" type="text">
								</div>
							</div>
							<div class="form-group text-right">
								<button class="btn btn-default btn-success" id="update-payment"  type="submit"><i class="fa fa-save"></i> <?= __('user.submit') ?></button>
							</div>
						</form>

					</div>
				</div>
				<div class="card m-t-30">
					<div class="card-header">
						<h5 class="card-title"><?= __('user.add_paypal_account') ?></h5>
					</div>
					<div class="card-body">
						<form class="form-horizontal" method="post" action="<?= base_url('usercontrol/addpayment') ?>" enctype="multipart/form-data">
							<div class="form-group row">
								<label class="col-sm-2 col-form-label"><?= __('user.paypal_email') ?> </label>
								<div class="col-sm-10">
									<input type="hidden" name="id" value="<?= $paypalaccounts['id'] ?>">
									<input name="paypal_email" class="form-control" value="<?= $paypalaccounts['paypal_email'] ?>" required="required" type="email">
								</div>
							</div>
							
							<div class="form-group text-right">
								<input name="add_paypal" value="Submit" type="submit" class="btn btn-default btn-success">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
</div>
</div>


<div class="modal fade" id="withdrawal-limit">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body"><?= $site_setting['wallet_min_message'] ?></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="clearfix"></div><br>
<script type="text/javascript">
	$(".show-recurring-transition").on("click",function(){
		$this = $(this);
		var id = $this.attr("data-id");

		$this.find("i").toggleClass("mdi-plus mdi-minus")

		$nextAll = $this.parents("tr").nextAll("tr.recurringof-"+id);
		if($nextAll.length){
			if($nextAll.eq(0).css("display") == 'table-row'){
				$nextAll.hide();
			} else {
				$nextAll.show();
			}
			return false;
		}

		$this.parents("tr").nextAll("tr.recurringof-"+id).remove();

		$.ajax({
			url:'<?= base_url('usercontrol/getRecurringTransaction') ?>',
			type:'POST',
			dataType:'json',
			data:{
				id:id,
			},
			beforeSend:function(){
				$this.btn("loading");
			},
			complete:function(){
				$this.btn("reset");
			},
			success:function(json){
				if(json['table']){
					$this.parents("tr").after(json['table'])
				}
			},
		})
	})

	$('.withdrawal-all').on('click',function(){

		if(!confirm("Are you sure ?")) return false;

		$this = $(this);
		$.ajax({
			type:'POST',
			dataType:'json',
			data:{request_payment_all: true},
			beforeSend:function(){ $this.btn("loading"); },
			complete:function(){ $this.btn("reset"); },
			success:function(json){
				window.location.reload();
			},
		})
	});

	$('.send-request').on('click',function(){
		$this = $(this);
		$.ajax({
			type:'POST',
			dataType:'json',
			data:{request_payment: $this.attr("data-id")},
			beforeSend:function(){ $this.btn("loading"); },
			complete:function(){ $this.btn("reset"); },
			success:function(json){
				$this.parents("tr").remove();
			},
		})
	})

	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		var hash = $(e.target).attr('href');
		if (history.pushState) {
		    history.pushState(null, null, hash);
		} else {
		    location.hash = hash;
		}
	});

	$(document).on('ready',function(){
		var hash = window.location.hash;
		if (hash) { $('.nav-link[href="' + hash + '"]').tab('show'); }
	})


	$(document).delegate('.wallet-popover','click', function(){
		var html = $(this).parents("tr").find(".dpopver-content").html();
        $(this).attr('data-content',html);
	    if($('.popover').hasClass('show')){
	        $('.popover').remove()
	    } else {
	        $(this).popover('show');
	    }
	});

	$('html').on('click', function(e) {
	  if (typeof $(e.target).data('original-title') == 'undefined' &&
	     !$(e.target).parents().is('.popover.in')) {
	    $('[data-original-title]').popover('hide');
	  }
	});

	$(document).ready(function(){
		$(".wallet-popover").popover({
	        placement : 'right',
		    html : true,
	    });
	})
</script>

