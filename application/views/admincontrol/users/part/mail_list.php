<?php foreach($userslist as $users){ ?>
    <?php
        if(empty($users['amount'])){ $users['amount'] = 0; }
        if(empty($users['click'])){ $users['click'] = 0; }
        if(empty($users['af_click'])){ $users['af_click'] = 0; }
    ?>
	<tr>
		<td><input value="<?= $users['email'] ?>" class="select-single" type="checkbox"></td>
		<td><?php echo $users['firstname'];?></td>
		<td><?php echo $users['lastname'];?></td>
        <td class="txt-cntr">
        	<?php
                if ($users['Country'] != '') { $flag = 'flags/' . strtolower($users['sortname']) . '.png'; } 
                else { $flag = 'users/avatar-1.png'; }
            ?>
            <img class="rounded-circle"
                 src="<?php echo base_url('assets/vertical/assets/images/'. $flag); ?>"
                 alt="<?= $users['sortname'] ?>" style="width:30px;height: 30px">
            <?= $users['sortname'] ?>
        </td>
        <td class="txt-cntr"><?php echo $users['email'];?></td>
		<td class="txt-cntr"><?php echo $users['username'];?></td>
		<?php $v= json_decode($users['value'],1); 
    		foreach ($data as $key => $value) { if($value['type'] == 'header') continue; ?>
			<td class="txt-cntr"><?php echo $v['custom_'.$value['name']] ?></td>
		<?php } ?>
	</tr>
<?php } ?>