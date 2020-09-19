<?php foreach ($tools as $key => $tool) { ?>
	<tr>
		<td class="text-center">
			<img width="50px" height="50px" src="<?php echo resize('assets/images/product/upload/thumb/'. $tool['featured_image'],100,100,1) ?>" >

			<div class="dropdown">
			  <a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    Actions
			  </a>

			  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

			  	<a  href="<?= base_url('usercontrol/integration_tools_form/'. $tool['_type'] .'/' . $tool['id']) ?>" class="dropdown-item"><?php echo __('admin.edit') ?></a>
			

				<a href="javascript:void(0)" class="dropdown-item btn-show-code" data-id='<?= $tool['id'] ?>'>Code</a>
				<a href="<?= base_url('usercontrol/integration_tools_duplicate/'. $tool['id']) ?>" class="dropdown-item"><?php echo __('admin.duplicate') ?></a>
			    <div class="dropdown-divider"></div>

				<a href="<?= base_url('usercontrol/integration_tools_delete/'. $tool['id']) ?>" class="dropdown-item tool-remove-link"><?php echo __('admin.delete') ?></a>

			  </div>
			</div>
		</td>
		<td class="text-center"><?= $key+1 ?></td>
		<td width="100px">
			<?= $tool['name'] ?>
			<div>
				<a class="get-code" href="javascript:void(0)" data-id="<?= $tool['id'] ?>"><?php echo __('admin.get_code') ?></a>
			</div>
			<div>
				<a class="btn-show-code" href="javascript:void(0)" data-id='<?= $tool['id'] ?>'> Website Code </a>
			</div>
		</td>
		<td>
			<div><?= $tool['type'] ?></div>
			<?php 
				if($tool['recursion']){
           			if($tool['recursion'] != 'custom_time'){
           				echo '<b>'. __('admin.recurring') .' </b> : ' .  __('admin.'.$tool['recursion']);
           			} else {
           				echo '<b>'. __('admin.recurring') .' </b> : '. timetosting($tool['recursion_custom_time']);
           			}
	           	}
			?>	
		</td>
		<td><?= $tool['program_name'] ? $tool['program_name'] .' / ' : '' ?>  <?= $tool['tool_type'] ?></td>
		
		<td>
			<div class="wallet-toggle ">
				<div class="<?= $tool['_tool_type'] == 'program' && $tool['sale_status'] ? '' : 'hide' ?>">
					<?php 
						$comm = '';
						if($tool['commission_type'] == 'percentage'){ $comm = $tool['commission_sale'].'%'; }
						else if($tool['commission_type'] == 'fixed'){ $comm = c_format($tool['commission_sale']); }

						echo "<small>Affiliate Will Get : {$comm} <br>";
						if($tool['vendor_id']){
							$comm = '';
							if($tool['admin_commission_type'] == 'percentage'){ $comm = $tool['admin_commission_sale'].'%'; }
							else if($tool['admin_commission_type'] == 'fixed'){ $comm = c_format($tool['admin_commission_sale']); }

							echo "Admin Will Get : {$comm} <br>";
						}

						echo "Count : ". (int)$tool['total_sale_count'] ."<br>";
						echo "Amount : ". $tool['total_sale_amount'] ."</small>";

						
					?>
				</div>
				<a href="javascript:void(0)" class="tog"> Toggle Data </a>
			</div>
		</td>
		<td>
			<div class="wallet-toggle ">
				<div class="<?= $tool['_tool_type'] == 'program' && $tool['click_status'] ? '' : 'hide' ?>">
					<?php 
						echo "<small>Affiliate Will Get : ";
						echo c_format($tool["commission_click_commission"]). " per ". $tool['commission_number_of_click'] ." Clicks <br>";

						if($tool['vendor_id']){
							echo "Admin Will Get : ";
							echo c_format($tool["admin_commission_click_commission"]). " per ". $tool['admin_commission_number_of_click'] ." Clicks <br>";
						}

						echo "Count : ". (int)$tool['total_click_count'] ."<br>";
						echo "Amount : ". $tool['total_click_amount'] ."</small>";
					?>
				</div>
				<a href="javascript:void(0)" class="tog"> Toggle Data </a>
			</div>
		</td>
		<td>
			<div class="wallet-toggle ">
				<div class="<?= $tool['_tool_type'] == 'general_click' ? '' : 'hide' ?>">
					<?php 
						echo "<small>Affiliate Will Get : ";
						echo c_format($tool["general_amount"]). " per ". $tool['general_click'] ." Clicks <br>";

						if($tool['vendor_id']){
							echo "Admin Will Get : ";
							echo c_format($tool["admin_general_amount"]). " per ". $tool['admin_general_click'] ." Clicks <br>";
						}

						echo "Count : ". (int)$tool['total_general_click_count'] ."<br>";
						echo "Amount : ". $tool['total_general_click_amount'] ."</small>";
					?>
				</div>
				<a href="javascript:void(0)" class="tog"> Toggle Data </a>
			</div>
		</td>
		<td>
			<div class="wallet-toggle ">
				<div class="<?= $tool['_tool_type'] == 'action' ? '' : 'hide' ?>">
					<?php 
						echo "<small>Affiliate Will Get : ";
						echo c_format($tool["action_amount"]). " per ". $tool['action_click'] ." Actions <br>";
						if($tool['vendor_id']){
							echo "Admin Will Get : ";
							echo c_format($tool["admin_action_amount"]). " per ". $tool['admin_action_click'] ." Actions <br>";
						}
						echo "Count : ". (int)$tool['total_action_click_count'] ."<br>";
						echo "Amount : ". $tool['total_action_click_amount'] ."</small>";
					?>
				</div>
				<a href="javascript:void(0)" class="tog"> Toggle Data </a>
			</div>
		</td>
		<td><?= ads_status($tool['status']) ?></td>
		<td><?= $tool['created_at'] ?></td>
	</tr>
<?php } ?>
