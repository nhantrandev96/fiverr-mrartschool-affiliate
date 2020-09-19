<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<h1><?= __('store.contact_us') ?></h1>
			<?php echo $store_setting['contact_content'] ?>
			<div class="clearfix"></div>
			<br><br>
			<div class="row">
				<div class="col-sm-12">
					<form class="form-horizontal" action="" method="post">
						<fieldset>
							<legend class="text-left"><?= __('store.contact_us') ?></legend>
							<hr>
							<div class="form-group">
								<label class="col-md-3 control-label" for="name"><?= __('store.name') ?></label>
								<div class="col-md-9">
									<input id="name" value="<?php echo set_value('name'); ?>" name="name" type="text" placeholder="Your name" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label" for="email"><?= __('store.your_e_mail') ?></label>
								<div class="col-md-9">
									<input id="email" value="<?php echo set_value('email'); ?>" name="email" type="text" placeholder="Your email" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label" for="phone"><?= __('store.phone') ?></label>
								<div class="col-md-9">
									<input id="phone" value="<?php echo set_value('phone'); ?>" name="phone" type="text" placeholder="Your phone" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label" for="message"><?= __('store.your_message') ?></label>
								<div class="col-md-9">
									<textarea class="form-control" id="message" value="<?php echo set_value('message'); ?>" name="message" placeholder="<?= __('store.please_enter_your_message_here') ?>" rows="5"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label" for="message"></label>
								<div class="col-md-9">
									<?php 
										$v= validation_errors(); 
										if($v != ''){
											echo '<div class="alert alert-danger">'. $v .'</div>';
										}
									?>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12 text-left">
									<button type="submit" class="btn btn-info"><?= __('store.submit') ?></button>
								</div>
							</div>
						</fieldset>
					</form>  
				</div>
			</div>
			<br>
		</div>
	</div>
</div>