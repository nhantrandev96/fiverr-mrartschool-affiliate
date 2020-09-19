<tr class="<?= ($class == 'child' || $class == 'child-recurring') ? 'child-row' : '' ?> wallet-id-<?= $value['id'] ?> <?= $recurring ? 'recurring recurringof-'.$recurring : '' ?>" group_id='<?= $value['group_id'] ?>' >
	<td>
		<div class="checkbox-td">
			<label>
				<input type="checkbox" class="wallet-checkbox" value="<?= $value['id'] ?>">
			</label>
		</div>
	</td>
	<td class="text-center p-relative <?= $force_class ?> <?= $class == 'child' ? 'child-arrow' : '' ?>">
		<?php if($has_child && $class != 'child'){ ?>
			<button class="show-child-transaction"><i class="fa fa-angle-down"></i></button>
			<div class="button-line"></div>
		<?php } ?>
	</td>
	<td>
		<div class="no-wrap"><?= dateFormat($value['created_at'],'d F Y') ?></div>
		<div class="no-wrap">
			<span class="badge badge-secondary">ID: <?= $value['id'] ?></span>
		</div>
	</td>
	<td>
		<?php echo $value['username']; ?>
		<div>
			<span class="badge badge-default font-weight-normal"><?= wallet_whos_commission($value) ?></span>
		</div>		
	</td>
	<td>
		<?= wallet_ex_type($value) ?>
		<div>
			<?php if($value['integration_orders_total']){ ?>
				<small class="badge badge-default payment-method"><?= c_format($value['integration_orders_total']) ?></small>
			<?php } ?>
			<?php if($value['local_orders_total']){ ?>
				<small class="badge badge-default payment-method"><?= c_format($value['local_orders_total']) ?></small>
			<?php } ?>

			<?php if($value['payment_method']){ ?>
			 	<small class="badge badge-default payment-method"><?= payment_method($value['payment_method']) ?></small>
			<?php } ?>
		</div>
	</td>
	<td >
		<div class="no-wrap">
			<div class="dpopver-content d-none">
				<?php
					list($message,$ip_details) = parseMessage($value['comment'],$value,'admincontrol',true);
					echo "<div>". $message ."</div>";
				?>
			</div>
			<div 
				class="badge badge-<?= is_need_to_pay($value) ? 'danger' : 'secondary' ?> py-1 pl-2 font-14" 
				toggle="popover"
			> 
				<?= c_format($value['amount']) ?> 
			</div>
			<button toggle="popover" class="wallet-popover btn-wallet-info">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
			</button>
		</div>
		<?php
			$ip_details = json_decode($value['ip_details'], true);
		?>
		<ul class="ip-list list-inline mb-0 mt-2">
			<?php for ($i=0; $i < 2; $i++) { ?>
				<?php if(isset($ip_details[$i])){ ?>
					<li class="list-inline-item">
						<a href="javascript:void(0)" title="<?= $ip_details[$i]['country_code'] . ' - ' . $ip_details[$i]['ip'] ?>" data-toggle="tooltip" >
							<?= $ip_details[$i]['country_code'] ?>
						</a>
					</li>
				<?php } ?>
			<?php } ?>
			
			<li class="list-inline-item dropdown">
				<a href="javascript:void(0)" title="" data-toggle="dropdown" aria-expanded="false">
					<i class="fa fa-align-justify"></i>
				</a>

				<ul class="dropdown-menu country-dropdown">
					<?php foreach ($ip_details as  $ip) { ?>
				    	<li>
				    		<span class="flag"><i class="flag-sm m-auto d-block flag-sm-<?= strtoupper($ip['country_code']) ?>"></i> </span>
				    		<span class="ip"> <?= $ip['country_code'] ?> <?= $ip['ip'] ?></span>
				    	</li>
				    <?php } ?>
				  </ul>
			</li>
			
		</ul>
	</td>
	
	<td><div class="transaction-type"><?= wallet_type($value) ?></div></td>
	<td class="text-center">
		<?= $status_icon[$value['status']] ?>
		<?php echo wallet_paid_status($value['status']); ?>
	</td> 	
	<td>
		<?php if($class != 'child'){ ?>
			<?php if(($value['status'] == 0 || $value['status'] == 1) && $value['comm_from'] == 'ex' && (!in_array($value['type'], ['external_click_comm_pay','external_click_commission','click_commission','external_click_comm_admin']) || $value['reference_id_2'] == 'vendor_action' || $value['reference_id_2'] == 'admin_action' ) ){ ?>
				<div class="wallet-status-switch mb-2">
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
		<?php } ?>
	</td>
	<td class="text-right">
		<div class="text-center actions no-wrap">
			<button class="hover-danger wallet-btn remove-tran" data-id="<?= $value['id'] ?>">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
			</button>

			<?php if(!$value['parent_id']){  ?>
				<button data-toggle="tooltip" title="Set Recurring Transition" class="wallet-btn <?= $value['wallet_recursion_status'] ? 'hover-danger' : 'hover-info' ?> recursion-tran" data-id="<?= $value['id'] ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
				</button>
			<?php } ?>
			
			<?php if($value['wallet_recursion_id']){ ?>
				<button type="button" class="wallet-btn" title="<?= cycle_details($value['total_recurring'], $value['wallet_recursion_next_transaction'], $value['wallet_recursion_endtime'], $value['total_recurring_amount']) ?>" data-toggle="tooltip" data-id="<?= $value['id'] ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
				</button>
				<?php if((int)$value['total_recurring']){ ?>
					<button data-toggle="tooltip" title="Show Recurring Transition" class="wallet-btn show-recurring-transition" data-id="<?= $value['id'] ?>">
						<span class="plus">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
						</span>
						<span class="minus">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="8" y1="12" x2="16" y2="12"></line></svg>
						</span>
					</button>
				<?php } ?>
			<?php } ?>
		</div>
	</td>
</tr>