<div class="form-group">
	<label class="control-label">Status</label>
	<select class="form-control" name="status">
		<option <?= (int)$setting_data['status'] == '0' ? 'selected' : '' ?> value="0">Disabled</option>
		<option <?= (int)$setting_data['status'] == '1' ? 'selected' : '' ?> value="1">Enabled</option>
	</select>
</div>
<div class="form-group">
	<label class="control-label">E-Mail</label>
	<input class="form-control" name="email" value="<?= $setting_data['email'] ?>">
</div>

<div class="form-group">
	<label class="control-label">Secret</label>
	<input class="form-control" name="secret" value="<?= $setting_data['secret'] ?>">
</div>


<div class="form-group">
	<label class="control-label">Order Status</label>
	<select name="order_status" class="form-control">
		<?php foreach ($order_status as $order_status_id => $name) { ?>
			<?php if ($order_status_id == $setting_data['order_status']) { ?>
				<option value="<?php echo $order_status_id; ?>" selected="selected"><?= $name ?></option>
			<?php } else { ?>
				<option value="<?php echo $order_status_id; ?>"><?= $name ?></option>
			<?php } ?>
		<?php } ?>
	</select>
</div>

<div class="form-group">
	<label class="control-label">Pending Status</label>
	<select name="pending_status" class="form-control">
		<?php foreach ($order_status as $order_status_id => $name) { ?>
			<?php if ($order_status_id == $setting_data['pending_status']) { ?>
				<option value="<?php echo $order_status_id; ?>" selected="selected"><?= $name ?></option>
			<?php } else { ?>
				<option value="<?php echo $order_status_id; ?>"><?= $name ?></option>
			<?php } ?>
		<?php } ?>
	</select>
</div>

<div class="form-group">
	<label class="control-label">Canceled Status</label>
	<select name="canceled_status" class="form-control">
		<?php foreach ($order_status as $order_status_id => $name) { ?>
			<?php if ($order_status_id == $setting_data['canceled_status']) { ?>
				<option value="<?php echo $order_status_id; ?>" selected="selected"><?= $name ?></option>
			<?php } else { ?>
				<option value="<?php echo $order_status_id; ?>"><?= $name ?></option>
			<?php } ?>
		<?php } ?>
	</select>
</div>

<div class="form-group">
	<label class="control-label">Failed Status</label>
	<select name="failed_status" class="form-control">
		<?php foreach ($order_status as $order_status_id => $name) { ?>
			<?php if ($order_status_id == $setting_data['failed_status']) { ?>
				<option value="<?php echo $order_status_id; ?>" selected="selected"><?= $name ?></option>
			<?php } else { ?>
				<option value="<?php echo $order_status_id; ?>"><?= $name ?></option>
			<?php } ?>
		<?php } ?>
	</select>
</div>


<div class="form-group">
	<label class="control-label">Chargeback Status</label>
	<select name="chargeback_status" class="form-control">
		<?php foreach ($order_status as $order_status_id => $name) { ?>
			<?php if ($order_status_id == $setting_data['chargeback_status']) { ?>
				<option value="<?php echo $order_status_id; ?>" selected="selected"><?= $name ?></option>
			<?php } else { ?>
				<option value="<?php echo $order_status_id; ?>"><?= $name ?></option>
			<?php } ?>
		<?php } ?>
	</select>
</div>