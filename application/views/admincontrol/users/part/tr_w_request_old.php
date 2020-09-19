<?php foreach ($transaction as $key => $value) { ?>
	<tr>
		<td><?php echo $key + 1 ?></td>
		<td><?php echo $value['username'] ?></td>
		<td>
			<?php if($value['integration_orders_total']){ ?>
				<?= c_format($value['integration_orders_total']) ?>
			<?php } ?>
		</td>
		<td>
			<div class="dpopver-content d-none">
				<?php
					list($message,$ip_details) = parseMessage($value['comment'],$value,'admincontrol',true);
					echo "<div>". $message ."</div>";
				?>
			</div>
			<div 
				class="wallet-popover badge badge-<?= $value['amount'] >= 0 ? 'secondary' : 'danger' ?> py-1 pl-2 font-14" 
				toggle="popover"
			> 
				<?= c_format($value['amount']) ?> 
			</div>
		</td>
		<td><?= wallet_ex_type($value) ?></td>
		<td><?php echo $value['created_at'] ?></td>
		<td><?php echo $request_status[$value['status']] ?></td>
	</tr>
<?php } ?>