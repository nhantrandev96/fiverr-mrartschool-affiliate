<?php 
	$unique_url= base_url().'register/'.base64_encode( $userdetails['id']);
	$store_url = base_url('store/'. base64_encode($userdetails['id']));
?>
<?php foreach($clicks as $index => $order){ ?>
	<?php if($order['type'] == 'store' || $order['type'] == 'store_admin' || $order['type'] == 'store_other_aff'){ ?>
	<tr>
		<td><button type="button" class="d-inline-block toggle-child-tr"><i class="fa fa-plus"></i></button> <?= $index + $start_from ?></td>
		<td><?= $order['action_id'] ?></td>
		<td><?= $unique_url ?></td>
	    <td><?= $order['flag'] ?> <?= $order['ip'] ?> - <small><?= $order['country_code'] ?></small></td>
	    <td><?= $order['created_at'] ?></td>
	    <td>
	    	<?php 
	    		if($order['type'] == 'store') echo "Store Product Click";
	    		if($order['type'] == 'store_admin') echo "Store Product Click (Admin)";
	    		if($order['type'] == 'store_other_aff') echo "Store Product Click (Other Affiliate)";
	    	?>
	    </td>
	</tr>
	<tr class="detail-tr">
		<td colspan="100%">
            <div>
                <ul>
					<li><b>Product ID : </b> <span><?= $order['product_id'] ?></span></li>
                </ul>
           	</div>
      	</td>
	</tr>
	<?php } else if($order['type'] == 'order') { ?>
		<tr>
			<td><button type="button" class="d-inline-block toggle-child-tr"><i class="fa fa-plus"></i></button> <?= $index + $start_from ?></td>
			<td><?= $order['id'] ?></td>
			<td><?= $store_url ?></td>
            <td><?= $order['flag'] ?> <?= $order['ip'] ?> - <small><?= $order['country_code'] ?></small></td>
            <td><?= $order['created_at'] ?></td>
            <td>Store Order</td>
		</tr>
		<tr class="detail-tr">
			<td colspan="100%">
	            <div>
	                <ul>
						<li><b><?= __('user.payment_method') ?> :</b> <span><?= $order['payment_method']; ?></span> </li>
						<li><b><?= __('user.transaction') ?> :</b> <span><?= $order['txn_id'] ?></span> </li>
						<li><b><?= __('user.ip') ?> :</b> <span><?= $order['ip'] ?></span> </li>
						<li><b><?= __('user.country_code') ?> :</b> <span><?= $order['country_code'] ?></span> </li>
						<li><b><?= __('user.currency_code') ?> :</b> <span><?= $order['currency_code'] ?></span> </li>
	                </ul>
	           	</div>
	      	</td>
		</tr>
	<?php } else  { ?>
		<tr>
			<td><button type="button" class="d-inline-block toggle-child-tr"><i class="fa fa-plus"></i></button> <?= $index + $start_from ?></td>
			<td><?= $order['id'] ?></td>
			<td><?= $order['base_url'] ?></td>
            <td><?= $order['flag'] ?> <?= $order['ip'] ?> - <small><?= $order['country_code'] ?></small></td>
            <td><?= $order['created_at'] ?></td>
            <td><?= $order['click_type'] ?></td>
		</tr>
		<tr class="detail-tr">
			<td colspan="100%">
	            <div>
	                <ul>
						<li><b>Page : </b> <span><?= $order['link'] ?></span></li>
						<li><b>Browser : </b> <span><?= $order['browserName'] ?> - <small><?= $order['browserVersion'] ?></small></span></li>
						<li><b>Os Platform : </b> <span><?= $order['osPlatform'] ?> -  <small> Version : <?= $order['osVersion'] ?></small></span></li>
						<li><b>Mobile Name : </b> <span><?= $order['mobileName'] ?></span></li>
	                </ul>
	           	</div>
	      	</td>
		</tr>
	<?php } ?>
<?php } ?>