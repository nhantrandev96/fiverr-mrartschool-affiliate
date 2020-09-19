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
			<label><?= __('admin.paypal_api_username') ?></label>
			<input name="api_username" class="form-control" value="<?php echo $setting_data['api_username']; ?>">
		</div>
		<div class="form-group">
			<label><?= __('admin.paypal_api_password') ?></label>
			<input name="api_password" class="form-control" value="<?php echo $setting_data['api_password']; ?>">
		</div>
		<div class="form-group">
			<label><?= __('admin.paypal_api_signature') ?></label>
			<input name="api_signature" class="form-control" value="<?php echo $setting_data['api_signature']; ?>">
		</div>

		<?php 
		$business_currency =  array(
			'USD' => 'United States Dollars',
			'EUR' => 'Euro',
			'GBP' => 'United Kingdom Pounds',
			'DZD' => 'Algeria Dinars',
			'ARP' => 'Argentina Pesos',
			'AUD' => 'Australia Dollars',
			'ATS' => 'Austria Schillings',
			'BSD' => 'Bahamas Dollars',
			'BBD' => 'Barbados Dollars',
			'BEF' => 'Belgium Francs',
			'BMD' => 'Bermuda Dollars',
			'BRR' => 'Brazil Real',
			'BGL' => 'Bulgaria Lev',
			'CAD' => 'Canada Dollars',
			'CLP' => 'Chile Pesos',
			'CNY' => 'China Yuan Renmimbi',
			'CYP' => 'Cyprus Pounds',
			'CSK' => 'Czech Republic Koruna',
			'DKK' => 'Denmark Kroner',
			'NLG' => 'Dutch Guilders',
			'XCD' => 'Eastern Caribbean Dollars',
			'EGP' => 'Egypt Pounds',
			'FJD' => 'Fiji Dollars',
			'FIM' => 'Finland Markka',
			'FRF' => 'France Francs',
			'DEM' => 'Germany Deutsche Marks',
			'XAU' => 'Gold Ounces',
			'GRD' => 'Greece Drachmas',
			'HKD' => 'Hong Kong Dollars',
			'HUF' => 'Hungary Forint',
			'ISK' => 'Iceland Krona',
			'INR' => 'India Rupees',
			'IDR' => 'Indonesia Rupiah',
			'IEP' => 'Ireland Punt',
			'ILS' => 'Israel New Shekels',
			'ITL' => 'Italy Lira',
			'JMD' => 'Jamaica Dollars',
			'JPY' => 'Japan Yen',
			'JOD' => 'Jordan Dinar',
			'KRW' => 'Korea (South) Won',
			'LBP' => 'Lebanon Pounds',
			'LUF' => 'Luxembourg Francs',
			'MYR' => 'Malaysia Ringgit',
			'MXP' => 'Mexico Pesos',
			'NLG' => 'Netherlands Guilders',
			'NZD' => 'New Zealand Dollars',
			'NOK' => 'Norway Kroner',
			'PKR' => 'Pakistan Rupees',
			'XPD' => 'Palladium Ounces',
			'PHP' => 'Philippines Pesos',
			'XPT' => 'Platinum Ounces',
			'PLZ' => 'Poland Zloty',
			'PTE' => 'Portugal Escudo',
			'ROL' => 'Romania Leu',
			'RUR' => 'Russia Rubles',
			'SAR' => 'Saudi Arabia Riyal',
			'XAG' => 'Silver Ounces',
			'SGD' => 'Singapore Dollars',
			'SKK' => 'Slovakia Koruna',
			'ZAR' => 'South Africa Rand',
			'KRW' => 'South Korea Won',
			'ESP' => 'Spain Pesetas',
			'XDR' => 'Special Drawing Right (IMF)',
			'SDD' => 'Sudan Dinar',
			'SEK' => 'Sweden Krona',
			'CHF' => 'Switzerland Francs',
			'TWD' => 'Taiwan Dollars',
			'THB' => 'Thailand Baht',
			'TTD' => 'Trinidad and Tobago Dollars',
			'TRL' => 'Turkey Lira',
			'VEB' => 'Venezuela Bolivar',
			'ZMK' => 'Zambia Kwacha',
			'EUR' => 'Euro',
			'XCD' => 'Eastern Caribbean Dollars',
			'XDR' => 'Special Drawing Right (IMF)',
			'XAG' => 'Silver Ounces',
			'XAU' => 'Gold Ounces',
			'XPD' => 'Palladium Ounces',
			'XPT' => 'Platinum Ounces',
		);
		?>
		<div class="form-group">
			<label  class="control-label"><?= __('admin.business_currency') ?></label>
			<select name="payment_currency" class="form-control" >
				<?php foreach ($business_currency as $c => $name) { ?>
					<option <?= $setting_data['payment_currency'] == $c ? 'selected="selected"' : '' ?> value="<?= $c ?>"><?= $name ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="form-group">
			<label  class="control-label"><?= __('admin.mode') ?></label>
			<div class="">
				<div class="radio-inline">
					<label>
						<input type="radio" name="payment_mode"  value="live" <?php echo $setting_data['payment_mode'] == 'live' ? 'checked' : '' ?> >
						<?= __('admin.live') ?>
					</label>
				</div>
				<div class="radio-inline">
					<label>
						<input type="radio" name="payment_mode"  value="sandbox" <?php echo $setting_data['payment_mode'] == 'sandbox' ? 'checked' : '' ?> >
						<?= __('admin.sandbox') ?>
					</label>
				</div>
			</div>
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