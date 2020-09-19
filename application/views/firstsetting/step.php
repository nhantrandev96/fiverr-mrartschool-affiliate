<form id="stepwizard-form">
	
	<div class="stepwizard-data">
		<?php if($number == 1){ ?>
			<div class="stepwizard-title">General settings</div>

			<div class="form-group">
				<label>Are you going to use our local store ?</label>
				<select class="form-control" name="store[status]">
					<option value="1" <?= (int)$store['status'] == 1 ? 'selected' : '' ?> >Enable</option>
					<option value="0" <?= (int)$store['status'] == 0 ? 'selected' : '' ?> >Disable</option>
				</select>
			</div>

			<?php
				$zones_array = array();
				$timestamp = time();
				foreach(timezone_identifiers_list() as $key => $zone) {
					date_default_timezone_set($zone);
					$zones_array[$zone] = date('P', $timestamp) . " {$zone} ";
				}
			?>
			<div class="form-group">
				<label  class="col-form-label"><?= __('admin.time_zone') ?></label>
				<select class="form-control select2-input" name="site[time_zone]">
					<?php foreach ($zones_array as $key => $value) { ?>
						<option value="<?= $key ?>" <?= $site['time_zone'] == $key ? 'selected' : '' ?> > <?= $value ?></option>
					<?php } ?>
				</select>
			</div>

			<div class="form-group">
				<label class="control-label">Registration form</label>
				<select class="form-control" name="store[registration_status]">
					<option value="1" ><?= __('admin.enable') ?></option>
					<option value="0" <?= (int)$store['registration_status'] == 0 ? 'selected' : '' ?>><?= __('admin.disable') ?></option>
				</select>
			</div>

			<div class="form-group">
				<label  class="col-form-label"><?= __('admin.front_template') ?></label>
				<select class="form-control" name="login[front_template]">
					<?php foreach ($themes as $key => $value) { ?>
						<option value="<?= $value['id'] ?>" <?= $login['front_template'] == $value['id'] ? 'selected' : '' ?> ><?= $value['name'] ?></option>
					<?php } ?>
				</select>
			</div>

			<div class="form-group">
				<label class="control-label">Default Action Status</label>
				<select class="form-control" name="referlevel[default_action_status]">
					<option value="0" <?= (int)$referlevel['default_action_status'] == 0 ? 'selected' : '' ?>>On Hold</option>
					<option value="1" <?= (int)$referlevel['default_action_status'] == 1 ? 'selected' : '' ?>>In Wallet</option>
				</select>
			</div>

			<div class="form-group">
				<label  class="col-form-label"><?= __('admin.affiliate_cookie') ?></label>
				<input class="form-control input-affiliate_cookie" type="number" value="<?= $store['affiliate_cookie'] ?>" name="store[affiliate_cookie]">
			</div>

			<div class="form-group">
				<label class="control-label">Refer Level Status</label>
				<div class="radio-group">
					<select class="form-control" name="referlevel[status]">
						<option value="1" <?= (int)$referlevel['status'] == 1 ? 'selected' : '' ?>> Enable </option>
						<option value="0" <?= (int)$referlevel['status'] == 0 ? 'selected' : '' ?>> Disable </option>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label  class="col-form-label"><?= __('admin.minimum_withdraw') ?></label>
				<input name="site[wallet_min_amount]" value="<?php echo $site['wallet_min_amount']; ?>" class="form-control" type="number">
			</div>
		<?php } ?>

		<?php if($number == 2) { ?>
			<div class="stepwizard-title">Admin Email Profile</div>

			<div class="form-group">
				<label  class="col-form-label"><?= __('admin.email') ?></label>
				<input name="profile_email" value="<?php echo $profile_email; ?>" class="form-control" type="text">
			</div>
		<?php } ?>

		<?php if($number == 3) { ?>
			<div class="stepwizard-title">Email Setting</div>

			<div class="form-group">
				<label  class="col-form-label"><?= __('admin.from_email') ?></label>
				<input name="email[from_email]" value="<?php echo $setting['from_email']; ?>" class="form-control" type="text">
			</div>
			<div class="form-group">
				<label  class="col-form-label"><?= __('admin.from_name') ?></label>
				<input name="email[from_name]" value="<?php echo $setting['from_name']; ?>" class="form-control" type="text">
			</div>
			<div class="form-group">
				<label  class="col-form-label"><?= __('admin.smtp_hostname') ?></label>
				<input name="email[smtp_hostname]" value="<?php echo $setting['smtp_hostname']; ?>" class="form-control" type="text">
			</div>
			<div class="form-group">
				<label  class="col-form-label"><?= __('admin.smtp_username') ?></label>
				<input name="email[smtp_username]" value="<?php echo $setting['smtp_username']; ?>" class="form-control" type="text">
			</div>
			<div class="form-group">
				<label  class="col-form-label"><?= __('admin.smtp_password') ?></label>
				<input name="email[smtp_password]" value="<?php echo $setting['smtp_password']; ?>" class="form-control" type="text">
			</div>
			<div class="form-group">
				<label  class="col-form-label"><?= __('admin.smtp_port') ?></label>
				<input name="email[smtp_port]" value="<?php echo $setting['smtp_port']; ?>" class="form-control" type="text">
			</div>


			<div class="form-group">
				<label  class="col-form-label"><?= __('admin.notification_email') ?></label>
				<input name="site[notify_email]" value="<?php echo $site['notify_email']; ?>" class="form-control" type="email">
			</div>
		<?php } ?>

		<?php if($number == 4) { ?>
			<div class="stepwizard-title">Default Currency & Language</div>

			<div class="form-group">
				<label>Select Default Currency</label>
				<select class="form-control" name="currency">
					<option value="">-- Select --</option>
					<?php foreach ($currency as $key => $value) { ?>
						<option <?= $value['is_default'] ? 'selected' : '' ?> value="<?= $value['currency_id'] ?>"><?=  $value['title'] ?></option>
					<?php } ?>
				</select>
			</div>

			<div class="form-group">
				<label>Select Default Language</label>
				<select class="form-control" name="language">
					<option value="">-- Select --</option>
					<?php foreach ($language as $key => $value) { ?>
						<option <?= $value['is_default'] ? 'selected' : '' ?> value="<?= $value['id'] ?>"><?=  $value['name'] ?></option>
					<?php } ?>
				</select>
			</div>
		<?php } ?>

		<?php if($number == 5) { ?>
			<div class="stepwizard-title">Change Password</div>

			<div class="form-group">
				<label  class="col-form-label">Enter new password</label>
				<input name="password" value="" class="form-control" type="password">
			</div>

			<div class="form-group">
				<label  class="col-form-label">Confirm password</label>
				<input name="c_password" value="" class="form-control" type="password">
			</div>
			
		<?php } ?>

		<?php if($number == 6) { ?>
			<h2 class="text-center thank-you-title">Thank you</h2>
			<hr><br>
			<p class="text-left">Now for starting the work with the script, you have to do three things:</p>

			<ol>
				<li>Set and choose your external integration to your website <a href="<?= base_url('integration') ?>">press here</a></li>
				<li>Set and add your first affiliate program  <a href="<?= base_url('integration/programs') ?>">press here</a></li>
				<li>Set and Add your first affiliate banner <a href="<?= base_url('integration/integration_tools_form/banner') ?>">press here</a></li>
			</ol>
		<?php } ?> 


	</div>

</form>

<div class="stepwizard-footer">
	<?php if($number > 1){ ?>
		<button class="btn btn-primary" onclick="getStep(<?= $number-1 ?>)">Prev</button>
	<?php } ?>
	<?php if($number < $total_step){ ?>
		<button class="btn btn-primary btn-next" onclick="getStep(<?= $number+1 ?>, <?= $number ?>)">Next</button>
	<?php } ?>
</div>