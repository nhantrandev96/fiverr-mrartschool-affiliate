<?php foreach($categories as $index => $category){ ?>
	<tr>
		<td><?= $category['id'] ?></td>
		<td><?= $category['name'] ?></td>
	    <td><?= $category['created_at'] ?></td>
		<td>
			<a class="btn btn-sm btn-primary" href="<?= base_url('integration/integration_category_add/'. $category['id']) ?>"><?= __('admin.edit') ?></a>
			<a class="btn btn-sm btn-danger" onclick="if(!confirm('Are you sure ?')) return false" href="<?= base_url('integration/integration_category_delete/'. $category['id']) ?>"><?= __('admin.delete') ?></a>
		</td>
	</tr>
       
<?php } ?>

<?php if(empty($category)){ ?>
	<tr>
		<td colspan="100%">
			<div class="text-center m-2">
			 	<img class="img-responsive" src="<?php echo base_url("assets/vertical/assets/images/no-data-2.png"); ?>" style="margin-top:100px;">
				<h3 class="m-t-40 text-center"><?= __('admin.not_activity_yet') ?></h3>
			</div>       
		</td>
	</tr>
<?php } ?>