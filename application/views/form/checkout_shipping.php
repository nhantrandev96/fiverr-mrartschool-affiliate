<div class="alert alert-info">
	<strong>Shipping !</strong> Shipping charge will be on phone with store owner
</div>
<div class="row">
	<div class="col-12 col-sm-4">
		<div class="form-group">
			<?php $selected =  isset($shipping) ? $shipping->country_id : '' ?>
			<select class="form-control" name="country">
					<option value="">Select Country</option>
				<?php foreach ($countries as $key => $value) { ?>
					<option <?= $selected == $value->id ? 'selected' : '' ?> value="<?= $value->id ?>"><?= $value->name ?></option>
				<?php } ?>
			</select>
		</div>
	</div>
	<div class="col-12 col-sm-4">
		<div class="form-group">
			<select class="form-control" name="state"></select>
		</div>
	</div>
	<div class="col-12 col-sm-4">
		<div class="form-group">
			<input class="form-control" name="city" placeholder="City" type="text" value="<?= isset($shipping) ? $shipping->city : '' ?>">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12 col-sm-6">
		<div class="form-group">
			<input class="form-control" name="zip_code" placeholder="Postal Code" type="text" value="<?= isset($shipping) ? $shipping->zip_code : '' ?>">
		</div>
	</div>
	<div class="col-12 col-sm-6">
		<div class="form-group">
			<input class="form-control" name="phone" placeholder="Phone Number" type="text" value="<?= isset($shipping) ? $shipping->phone : '' ?>">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<div class="form-group">
			<textarea class="form-control" placeholder="Full Address" name="address"><?= isset($shipping) ? $shipping->address : '' ?></textarea>
		</div>
	</div>
</div>
<script>
	$('[name="country"]').trigger("change");
</script>