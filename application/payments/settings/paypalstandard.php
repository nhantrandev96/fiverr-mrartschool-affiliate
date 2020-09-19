<div class="row">
	<div class="col-sm-6">		
		<div class="form-group">
			<label class="control-label">Status</label>
			<select class="form-control" name="status">
				<option <?= (int)$setting_data['status'] == '0' ? 'selected' : '' ?> value="0">Disabled</option>
				<option <?= (int)$setting_data['status'] == '1' ? 'selected' : '' ?> value="1">Enabled</option>
			</select>
		</div>

		<div class="form-group">
			<label><?= __('admin.email') ?></label>
			<input name="email" class="form-control" value="<?php echo $setting_data['email']; ?>">
		</div>
		
		<div class="form-group">
			<label class="control-label">Sandbox Mode</label>
			<select class="form-control" name="sandbox_mode">
				<option <?= (int)$setting_data['sandbox_mode'] == '0' ? 'selected' : '' ?> value="0">Disabled</option>
				<option <?= (int)$setting_data['sandbox_mode'] == '1' ? 'selected' : '' ?> value="1">Enabled</option>
			</select>
		</div>


		<div class="form-group">
			<label class="control-label">Transaction Method</label>
			<select class="form-control" name="transaction">
				<option <?= (int)$setting_data['transaction'] == '0' ? 'selected' : '' ?> value="0">Authorization</option>
				<option <?= (int)$setting_data['transaction'] == '1' ? 'selected' : '' ?> value="1">Sale</option>
			</select>
		</div>

	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<label class="control-label" for="input-canceled-reversal-status">Canceled Reversal Status</label>
			<div class="">
				<select name="canceled_reversal_status_id" id="input-canceled-reversal-status" class="form-control">
					<?php foreach ($order_status as $order_status_id => $name) { ?>
						<?php if ($order_status_id == $setting_data['canceled_reversal_status_id']) { ?>
							<option value="<?php echo $order_status_id; ?>" selected="selected"><?= $name ?></option>
						<?php } else { ?>
							<option value="<?php echo $order_status_id; ?>"><?= $name ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label" for="input-completed-status">Completed Status</label>
			<div class="">
				<select name="completed_status_id" id="input-completed-status" class="form-control">
					<?php foreach ($order_status as $order_status_id => $name) { ?>
						<?php if ($order_status_id == $setting_data['completed_status_id']) { ?>
							<option value="<?php echo $order_status_id; ?>" selected="selected"><?= $name ?></option>
						<?php } else { ?>
							<option value="<?php echo $order_status_id; ?>"><?= $name ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label" for="input-denied-status">Denied Status</label>
			<div class="">
				<select name="denied_status_id" id="input-denied-status" class="form-control">
					<?php foreach ($order_status as $order_status_id => $name) { ?>
						<?php if ($order_status_id == $setting_data['denied_status_id']) { ?>
							<option value="<?php echo $order_status_id; ?>" selected="selected"><?= $name ?></option>
						<?php } else { ?>
							<option value="<?php echo $order_status_id; ?>"><?= $name ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label" for="input-expired-status">Expired Status</label>
			<div class="">
				<select name="expired_status_id" id="input-expired-status" class="form-control">
					<?php foreach ($order_status as $order_status_id => $name) { ?>
						<?php if ($order_status_id == $setting_data['expired_status_id']) { ?>
							<option value="<?php echo $order_status_id; ?>" selected="selected"><?= $name ?></option>
						<?php } else { ?>
							<option value="<?php echo $order_status_id; ?>"><?= $name ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label" for="input-failed-status">Failed Status</label>
			<div class="">
				<select name="failed_status_id" id="input-failed-status" class="form-control">
					<?php foreach ($order_status as $order_status_id => $name) { ?>
						<?php if ($order_status_id == $setting_data['failed_status_id']) { ?>
							<option value="<?php echo $order_status_id; ?>" selected="selected"><?= $name ?></option>
						<?php } else { ?>
							<option value="<?php echo $order_status_id; ?>"><?= $name ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label" for="input-pending-status">Pending Status</label>
			<div class="">
				<select name="pending_status_id" id="input-pending-status" class="form-control">
					<?php foreach ($order_status as $order_status_id => $name) { ?>
						<?php if ($order_status_id == $setting_data['pending_status_id']) { ?>
							<option value="<?php echo $order_status_id; ?>" selected="selected"><?= $name ?></option>
						<?php } else { ?>
							<option value="<?php echo $order_status_id; ?>"><?= $name ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label" for="input-processed-status">Processed Status</label>
			<div class="">
				<select name="processed_status_id" id="input-processed-status" class="form-control">
					<?php foreach ($order_status as $order_status_id => $name) { ?>
						<?php if ($order_status_id == $setting_data['processed_status_id']) { ?>
							<option value="<?php echo $order_status_id; ?>" selected="selected"><?= $name ?></option>
						<?php } else { ?>
							<option value="<?php echo $order_status_id; ?>"><?= $name ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label" for="input-refunded-status">Refunded Status</label>
			<div class="">
				<select name="refunded_status_id" id="input-refunded-status" class="form-control">
					<?php foreach ($order_status as $order_status_id => $name) { ?>
						<?php if ($order_status_id == $setting_data['refunded_status_id']) { ?>
							<option value="<?php echo $order_status_id; ?>" selected="selected"><?= $name ?></option>
						<?php } else { ?>
							<option value="<?php echo $order_status_id; ?>"><?= $name ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label" for="input-reversed-status">Reversed Status</label>
			<div class="">
				<select name="reversed_status_id" id="input-reversed-status" class="form-control">
					<?php foreach ($order_status as $order_status_id => $name) { ?>
						<?php if ($order_status_id == $setting_data['reversed_status_id']) { ?>
							<option value="<?php echo $order_status_id; ?>" selected="selected"><?= $name ?></option>
						<?php } else { ?>
							<option value="<?php echo $order_status_id; ?>"><?= $name ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label" for="input-void-status">Voided Status</label>
			<div class="">
				<select name="voided_status_id" id="input-void-status" class="form-control">
					<?php foreach ($order_status as $order_status_id => $name) { ?>
						<?php if ($order_status_id == $setting_data['voided_status_id']) { ?>
							<option value="<?php echo $order_status_id; ?>" selected="selected"><?= $name ?></option>
						<?php } else { ?>
							<option value="<?php echo $order_status_id; ?>"><?= $name ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</div>
		</div>
	</div>
</div>