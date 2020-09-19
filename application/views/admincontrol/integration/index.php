<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h5><?= __('admin.integration_modules') ?></h5>
			</div>

			<div class="card-body">
				<div class="row integration-modules">
					<?php foreach ($integration_modules as $key => $module) {?>
						<div class="col-sm-2">
							<div class="text-center integration-modules-list">
								<a href="<?= base_url('integration/instructions/'. $key) ?>">
									<img src="<?= $module['image'] ?>" class="w-100">
									<div class="modules-title"><?= $module['name'] ?></div>
								</a>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>