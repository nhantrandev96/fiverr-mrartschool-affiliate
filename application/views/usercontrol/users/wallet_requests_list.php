

<ul class="nav nav-tabs nav-tabs-custom">
  <li class="nav-item"><a class="active nav-link" data-toggle="tab" href="#new-request">New Requests</a></li>
  <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#old-request">Old Requests</a></li>
</ul>

<div class="tab-content">
  	<div id="new-request" class="tab-pane fade in active show">
  		<div class="row">
			<div class="col-12">
				<div class="table-responsive">
					<div class="card">
						<div class="card-header">
							<h6 class="m-0">Withdraw Requests List</h6>
						</div>
						<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table transaction-table table-striped ">
									<thead>
										<tr>
											<th width="100px">Request ID</th>
											<th>DATE</th>
											<th>Transactions Ids</th>
											<th>TOTAL</th>
											<th>STATUS</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($lists as $key => $value) { ?>
											<tr>
												<td><?= $value['id'] ?></td>
												<td><?= dateFormat($value['created_at'],'d F Y') ?></td>
												<td><?= $value['tran_ids'] ?></td>
												<td><?= c_format($value['total']) ?></td>
												<td><?= withdrwal_status($value['status']) ?></td>
												<td class="text-right">
													<a href="<?= base_url('usercontrol/wallet_requests_details/'. $value['id']) ?>" class="btn btn-primary btn-sm">Details</a>
												</td>
											</tr>
										<?php  } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
  	</div>
  	<div id="old-request" class="tab-pane fade">
		<div class="card">
			<div class="card-header">
				<h6 class="card-title m-0">Old Withdraw Requests List</h6>
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
</div>
