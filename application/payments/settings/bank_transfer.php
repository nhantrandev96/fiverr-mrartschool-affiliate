<div class="form-group">
	<label class="control-label">Status</label>
	<select class="form-control" name="status">
		<option <?= (int)$setting_data['status'] == '0' ? 'selected' : '' ?> value="0">Disabled</option>
		<option <?= (int)$setting_data['status'] == '1' ? 'selected' : '' ?> value="1">Enabled</option>
	</select>
</div>

<div class="form-group">
	<label class="control-label">Upload Proof Status</label>
	<select class="form-control" name="proof">
		<option <?= (int)$setting_data['proof'] == '0' ? 'selected' : '' ?> value="0">Disabled</option>
		<option <?= (int)$setting_data['proof'] == '1' ? 'selected' : '' ?> value="1">Enabled</option>
	</select>
</div>

<div class="form-group">
	<label class="control-label">Bank Details 1</label>
	<textarea class="form-control" rows="8" name="bank_details"><?= $setting_data['bank_details'] ?></textarea>
</div>

<div class="additional-bank">
	<?php
		if(isset($setting_data['additional_bank_details'])){
			$additional_bank_details = (array)json_decode($setting_data['additional_bank_details'],1);
			foreach ($additional_bank_details as $key => $value) {
				echo '<div class="form-group">';
				echo '	<label class="control-label w-100">Bank Details '. ($key+2) .' <span class="text-danger cursor-pointer pull-right remove-bank">Remove</span></label>';
				echo '	<textarea class="form-control" rows="8" name="additional_bank_details[]">'. $value .'</textarea>';
				echo '</div>';
			}
		}
	?>
</div>

<div class="text-right">	
	<button type="button" class="btn btn-add-bank btn-primary">Add Bank</button>
</div>

<script type="text/javascript">
	$(".btn-add-bank").on("click", function(){
		var html = '';
		html += '<div class="form-group">';
		html += '	<label class="control-label w-100">Bank Details '+ ($(".additional-bank > div").length + 2) +' <span class="text-danger cursor-pointer pull-right remove-bank">Remove</span></label>';
		html += '	<textarea class="form-control" rows="8" name="additional_bank_details[]"></textarea>';
		html += '</div>';

		$(".additional-bank").append(html)
	})

	$(".additional-bank").delegate(".remove-bank","click",function(){
		$(this).parents(".form-group").remove();
	})
</script>