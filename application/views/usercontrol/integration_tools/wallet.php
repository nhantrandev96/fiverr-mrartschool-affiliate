<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">Integration Wallet</div>
			<div class="card-body">
				<?php if ($transaction ==null) {?>
					<div class="text-center">
						<img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:25px;">
						<h3 class="m-t-40 text-center"><?= __('user.no_transactions') ?></h3>
					</div>
				<?php } else { ?>         
					<div class="table-responsive">
						<table class="table table-sortable wallet-table">
							<thead>
								<tr>
									<th scope="col">#</th>
									<th scope="col"><?= __('user.comment') ?></th>
									<th scope="col">Order Total</th>
									<th scope="col"><?= __('user.commission') ?></th>
									<th scope="col"><?= __('user.date') ?></th>
									<th scope="col"><?= __('user.type') ?></th>
									<th scope="col"><?= __('user.status') ?></th>
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
<div class="clearfix"></div><br>