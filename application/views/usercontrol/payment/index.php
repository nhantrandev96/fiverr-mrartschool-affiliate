<div class="right_col" role="main">
	<div class="col-md-12">
		<div class="x_panel">
			<div class="x_title">
				<h2><?= __('user.my_payment_details') ?></h2>
				<div class="clearfix"></div>
			</div>
			<section>
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-6">
							<h4 class="clr3"><?= __('user.your_account_details_list') ?></h4>
						</div>
						
						<div class="col-xs-12">
							<div class="table-responsive">
								<table class="mb-0 table table-bordered">
									<tbody>
										<tr>
											<th class="txt-cntr"><?= __('user.bank_name') ?></th>
											<th class="txt-cntr"><?= __('user.account_number') ?></th>
											<th class="txt-cntr"><?= __('user.account_name') ?></th>
											<th class="txt-cntr"><?= __('user.ifsc_code') ?></th>
										</tr>
									</tbody>
									<tbody>
										<?php foreach($paymentlist as $payment){ ?>
											<tr>
												<td class="txt-cntr">
													<?php echo $payment['payment_bank_name'];?>
												</td>
												<td class="txt-cntr">
													<?php echo $payment['payment_account_number'];?>
												</td>
												<td class="txt-cntr">
													<?php echo $payment['payment_account_name'];?>
												</td>
												<td class="txt-cntr">
													<?php echo $payment['payment_ifsc_code'];?>
												</td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					
					
				</div>
			</section>
		</div>
	</div>
</div>
<script type="text/javascript" async="">
	function shareinsocialmedia(url){
		window.open(url,'sharein','toolbar=0,status=0,width=648,height=395');
		return true;
	}
</script>