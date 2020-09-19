<tr class="<?= ($class == 'child' || $class == 'child-recurring') ? 'child-row' : '' ?> wallet-id-<?= $value['id'] ?> <?= $recurring ? 'recurring recurringof-'.$recurring : '' ?>" group_id='<?= $value['group_id'] ?>' >

	<?php if (!isset($stop_checkbox)) { ?>
		<td>
			<?php if ($value['status'] == '1') { ?>
			<div class="checkbox-td">
				<label>
					<input type="checkbox" class="wallet-checkbox" value="<?= $value['id'] ?>">
				</label>
			</div>
			<?php } ?>
		</td>
	<?php } ?>
	<?php if (!isset($stop_child)) { ?>
		<td class="text-center p-relative <?= $force_class ?> <?= $class == 'child' ? 'child-arrow' : '' ?>">
			<?php if($has_child && $class != 'child'){ ?>
				<button class="show-child-transaction"><i class="fa fa-angle-down"></i></button>
				<div class="button-line"></div>
			<?php } ?>
		</td>
	<?php } ?>
	<td>
		<div class="no-wrap"><?= dateFormat($value['created_at'],'d F Y') ?></div>
		<span class="badge badge-secondary">ID: <?= $value['id'] ?></span>
	</td>
	<td>
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
					list($message,$ip_details) = parseMessage($value['comment'],$value,'usercontrol',true);
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
		<div class="text-center actions no-wrap">
			<?php if($value['wallet_recursion_id']){ ?>
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