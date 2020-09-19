<?php foreach ($transaction as $key => $value) { ?>
<tr class="wallet-id-<?= $value['id'] ?> main-tr <?= $recurring ? 'recurring recurringof-'.$recurring : '' ?> " style="border: 10px;">
	<td class="p-3 mb-2 text-white bg-dark td-checkbox">
		<label>
			<input type="checkbox" class="wallet-checkbox" value="<?= $value['id'] ?>">
			<?= $value['id'] ?>
		</label>
	</td>
	<td>
		<?php
			echo $value['username'];
		?>
	</td>
	<td>
		<?php
			list($message,$ip_details) = parseMessage($value['comment'],$value,'admincontrol',true);
			echo "<div>". $message ."</div>";
		?>
	</td>
	<td>
		<?php if($value['integration_orders_total']){ ?>
		<?= c_format($value['integration_orders_total']) ?>
		<?php } ?>
		<?php if($value['local_orders_total']){ ?>
		<?= c_format($value['local_orders_total']) ?>
		<?php } ?>
	</td>
	<td>
		<div data-toggle="popover" data-content="Disabled popover"> <?= c_format($value['amount']) ?> </div>
	</td>
	<td><?= payment_method($value['payment_method']) ?></td>
	
	<td><?= dateFormat($value['created_at']) ?></td>
	<td><?= $value['comm_from'] == 'ex' ? 'External' : 'Store' ?></td>
	<td><?= wallet_type($value) ?></td>
	<td class="text-center">
		<?= $status_icon[$value['status']] ?>
		<?= $status[$value['status']] ?>
	</td>

	<td colspan="5" class="text-right">
		<?php if(!$value['parent_id']){  ?>
		<?php if($value['wallet_recursion_id']){ ?>
		<span class="badge badge-default p-2"><?= cycle_details($value['total_recurring'], $value['wallet_recursion_next_transaction'], $value['wallet_recursion_endtime'], $value['total_recurring_amount']) ?></span>
		<?php } ?>
		<button data-toggle="tooltip" title="Set Recurring Transition" class="btn btn-sm <?= $value['wallet_recursion_status'] ? 'btn-danger' : 'btn-default' ?> recursion-tran" data-id="<?= $value['id'] ?>"><i class="mdi mdi-wallet-travel"></i></button>
		<?php } ?>
		<?php if((int)$value['total_recurring']){ ?>
		<button data-toggle="tooltip" title="Show Recurring Transition" class="btn btn-sm btn-primary show-recurring-transition" data-id="<?= $value['id'] ?>"><i class="mdi mdi-plus"></i></button>
		<?php } ?>
		<button class="btn btn-sm btn-danger remove-tran" data-id="<?= $value['id'] ?>"><i class="fa fa-trash"></i></button>

		<!--ON HOLD / IN WALLET-->
		<?php if(($value['status'] == 0 || $value['status'] == 1) && $value['comm_from'] == 'ex'){ ?>
		<div class="wallet-status-switch">
			<div class="radio radio-inline">
				<label><input type="radio" checked="" class="status-change-rdo" name="status_<?= $value['id'] ?>" data-id='<?= $value['id'] ?>' value="0" ><span>On Hold</span></label>
			</div>
			<div class="radio radio-inline loading">
				<img src="<?=  base_url('assets/images/switch-loading.svg') ?>">
			</div>
			<div class="radio radio-inline">
				<label><input type="radio" <?= $value['status'] == 1 ? 'checked' : '' ?> class="status-change-rdo" name="status_<?= $value['id'] ?>" data-id='<?= $value['id'] ?>' value="1" ><span>In Wallet</span></label>
			</div>
		</div>
		<?php } ?>
		<!--ON HOLD / IN WALLET-->
	</td>
</tr>
<!-- <tr class="alert alert-secondary wallet-id-<?= $value['id'] ?> <?= $recurring ? 'recurring recurringof-'.$recurring : '' ?>">
	<td class="p-3 mb-2 text-white bg-secondary td-checkbox"></td>
	<td colspan="4" style="text-align:left";>
		<strong><?= __('admin.transaction_details') ?>:</strong>
		<?= $ip_details ?>
	</td>
</tr> -->
<?php } ?>