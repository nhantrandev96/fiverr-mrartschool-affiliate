<div class="form-group">
	<label class="control-label">Status</label>
	<select class="form-control" name="status">
		<option <?= (int)$setting_data['status'] == '0' ? 'selected' : '' ?> value="0">Disabled</option>
		<option <?= (int)$setting_data['status'] == '1' ? 'selected' : '' ?> value="1">Enabled</option>
	</select>
</div>

<div class="form-group">
	<label class="control-label"><span data-toggle="tooltip" title="" data-original-title="Enter your Merchant ID provided by Paytm">Merchant ID</span></label>
	<input class="form-control" name="merchant_id" value="<?= $setting_data['merchant_id'] ?>">
</div>

<div class="form-group">
	<label class="control-label"><span data-toggle="tooltip" title="" data-original-title="Enter your Merchant Key provided by Paytm">Merchant Key</span></label>
	<input class="form-control" name="merchant_key" value="<?= $setting_data['merchant_key'] ?>">
</div>

<div class="form-group">
	<label class="control-label"><span data-toggle="tooltip" title="" data-original-title="Enter your Website Name provded by Paytm" aria-describedby="tooltip962321">Website Name</span></label>
	<input class="form-control" name="website_name" value="<?= $setting_data['website_name'] ?>">
</div>

<div class="form-group">
	<label class="control-label"><span data-toggle="tooltip" title="" data-original-title="Eg. Retail, Entertainment etc.">Industry Type</span></label>
	<input class="form-control" name="industry_type" value="<?= $setting_data['industry_type'] ?>">
</div>

<div class="form-group">
	<label class="control-label"><span data-toggle="tooltip" title="" data-original-title="Enter Transaction URL provided by Paytm">Transaction URL</span></label>
	<input class="form-control" name="transaction_url" value="<?= $setting_data['transaction_url'] ?>">
</div>

<div class="form-group">
	<label class="control-label"><span data-toggle="tooltip" title="" data-original-title="Enter Transaction Status URL provided by Paytm" aria-describedby="tooltip789753">Transaction Status URL</span></label>
	<input class="form-control" name="transaction_status_url" value="<?= $setting_data['transaction_status_url'] ?>">
</div>



<div class="form-group">
	<label class="control-label"><span data-toggle="tooltip" title="" data-original-title="Order status that will set for Successful Payment">Order Success Status</span></label>
	<select name="order_success_status_id" class="form-control">
		<?php foreach ($order_status as $order_status_id => $name) { ?>
			<?php if ($order_status_id == $setting_data['order_success_status_id']) { ?>
				<option value="<?php echo $order_status_id; ?>" selected="selected"><?= $name ?></option>
			<?php } else { ?>
				<option value="<?php echo $order_status_id; ?>"><?= $name ?></option>
			<?php } ?>
		<?php } ?>
	</select>
</div>

<div class="form-group">
	<label class="control-label"><span data-toggle="tooltip" title="" data-original-title="Order status that will set for Failed Payment" aria-describedby="tooltip372536">Order Failed Status</span></label>
	<select name="order_failed_status_id" class="form-control">
		<?php foreach ($order_status as $order_status_id => $name) { ?>
			<?php if ($order_status_id == $setting_data['order_failed_status_id']) { ?>
				<option value="<?php echo $order_status_id; ?>" selected="selected"><?= $name ?></option>
			<?php } else { ?>
				<option value="<?php echo $order_status_id; ?>"><?= $name ?></option>
			<?php } ?>
		<?php } ?>
	</select>
</div>