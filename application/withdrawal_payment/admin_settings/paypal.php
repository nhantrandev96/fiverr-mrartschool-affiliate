
<div class="form-group">
	<label class="form-control-label d-block">ClientID
		<small class="pull-right">
			<a href="https://help.csod.com/help/csod_0/Content/Edge/Integration_Center/PayPal_Payment_Gateway/Obtain_Client_ID_and_Client_Secret_from_PayPal.htm" target="_blank">Obtain Client ID and Client Secret from PayPal</a>
		</small>
	</label>
	<input class="form-control" name="ClientID" value="<?= $setting_data['ClientID'] ?>">
</div>

<div class="form-group">
	<label class="form-control-label">ClientSecret</label>
	<input class="form-control" name="ClientSecret" value="<?= $setting_data['ClientSecret'] ?>" >
</div>


<div class="well">
	<div class="form-group">
		<label class="control-label">Denied Status</label>
		<div class="">
			<select name="denied_status_id" class="form-control">
				<?php foreach ($status_list as $status_id => $name) { ?>
					<?php if ($status_id == $setting_data['denied_status_id']) { ?>
						<option value="<?php echo $status_id; ?>" selected="selected"><?= $name ?></option>
					<?php } else { ?>
						<option value="<?php echo $status_id; ?>"><?= $name ?></option>
					<?php } ?>
				<?php } ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label">Pending Status</label>
		<div class="">
			<select name="pending_status_id" class="form-control">
				<?php foreach ($status_list as $status_id => $name) { ?>
					<?php if ($status_id == $setting_data['pending_status_id']) { ?>
						<option value="<?php echo $status_id; ?>" selected="selected"><?= $name ?></option>
					<?php } else { ?>
						<option value="<?php echo $status_id; ?>"><?= $name ?></option>
					<?php } ?>
				<?php } ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label">Processing Status</label>
		<div class="">
			<select name="processing_status_id" class="form-control">
				<?php foreach ($status_list as $status_id => $name) { ?>
					<?php if ($status_id == $setting_data['processing_status_id']) { ?>
						<option value="<?php echo $status_id; ?>" selected="selected"><?= $name ?></option>
					<?php } else { ?>
						<option value="<?php echo $status_id; ?>"><?= $name ?></option>
					<?php } ?>
				<?php } ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label">Success Status</label>
		<div class="">
			<select name="success_status_id" class="form-control">
				<?php foreach ($status_list as $status_id => $name) { ?>
					<?php if ($status_id == $setting_data['success_status_id']) { ?>
						<option value="<?php echo $status_id; ?>" selected="selected"><?= $name ?></option>
					<?php } else { ?>
						<option value="<?php echo $status_id; ?>"><?= $name ?></option>
					<?php } ?>
				<?php } ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label">Canceled Status</label>
		<div class="">
			<select name="canceled_status_id" class="form-control">
				<?php foreach ($status_list as $status_id => $name) { ?>
					<?php if ($status_id == $setting_data['canceled_status_id']) { ?>
						<option value="<?php echo $status_id; ?>" selected="selected"><?= $name ?></option>
					<?php } else { ?>
						<option value="<?php echo $status_id; ?>"><?= $name ?></option>
					<?php } ?>
				<?php } ?>
			</select>
		</div>
	</div>
</div>