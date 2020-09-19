<?php foreach ($lists as $key => $value) { ?>
	<tr>
		<td><?= $value['id'] ?></td>
		<td><?= $value['username'] ?></td>
		<td><?= dateFormat($value['created_at'],'d F Y') ?></td>
		<td><?= $value['prefer_method'] ?></td>
		<td><?= $value['tran_ids'] ?></td>
		<td><?= c_format($value['total']) ?></td>
		<td><?= withdrwal_status($value['status']) ?></td>
		<td class="text-right">
			<a href="<?= base_url('admincontrol/wallet_requests_details/'. $value['id']) ?>" class="btn btn-primary btn-sm">Details</a>
			<button id='<?= $value['id'] ?>' class="btn btn-danger btn-sm btn-deletes">Delete</button>
		</td>
	</tr>
<?php  } ?>