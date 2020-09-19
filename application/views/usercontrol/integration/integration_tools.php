<div class="row">
	<div class="col-12">
		<div class="card m-b-20">
			<div class="card-body">
				<h4 class="mt-0 header-title"><?= __('user.integration_tools') ?></h4>
				<div class="pull-left">
					<input class="table-search" id="txt_name" onkeyup="myFunction()" placeholder="Search" type="search">
				</div>

			</div>

			<div class="table-rep-plugin">
				<?php if ($tools ==null) {?>
					<div class="text-center">
						<img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:25px;">
						<h3 class="m-t-40 text-center"><?= __('user.no_banners') ?></h3></div>
					<?php }
					else {?>

						<div class="table-responsive b-0" data-pattern="priority-columns">
							<table id="myTable" class="table  table-striped">
								<thead>
									<tr>
										<th><?= __('user.id') ?></th>
										<th width="200px"><?= __('user.name') ?></th>
										<th><?= __('user.type') ?></th>
										<th><?= __('user.program_name') ?> / <?= __('user.type') ?></th>
										<th><?= __('user.sale_commisssion') ?></th>
										<th><?= __('user.product_click') ?></th>
										<th><?= __('user.general_click') ?></th>
										<th><?= __('user.action_click') ?></th>

										<th width="180px"><?= __('user.created_date') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($tools as $key => $tool) { ?>
										<tr>
											<td class="text-center"><?= $tool['id'] ?></td>
											<td style="word-break: break-all;white-space: initial;">
												<?= $tool['name'] ?>
												<div>
													<a class="get-code" href="javascript:void(0)" data-id="<?= $tool['id'] ?>"><?= __('user.get_code') ?></a>
												</div>
											</td>
											<td><?= $tool['type'] ?></td>
											<td><?= $tool['program_name'] ? $tool['program_name'] .' / ' : '' ?>  <?= $tool['tool_type'] ?></td>
											<td>
												<div class="wallet-toggle ">
													<div class="<?= $tool['_tool_type'] == 'program' && $tool['sale_status'] ? '' : 'hide' ?>">
														<?php 
														$comm = '';
														if($tool['commission_type'] == 'percentage'){ $comm = $tool['commission_sale'].'%'; }
														else if($tool['commission_type'] == 'fixed'){ $comm = c_format($tool['commission_sale']); }

														echo "<small>You Will Get : {$comm} <br>";
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
														echo "<small>You Will Get : ";
														echo c_format($tool["commission_click_commission"]). " per ". $tool['commission_number_of_click'] ." Clicks <br>";

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
														echo "<small>You Will Get : ";
														echo c_format($tool["general_amount"]). " per ". $tool['general_click'] ." Clicks <br>";

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
														echo "<small>You Will Get : ";
														echo c_format($tool["action_amount"]). " per ". $tool['action_click'] ." Actions <br>";

														echo "Count : ". (int)$tool['total_action_click_count'] ."<br>";
														echo "Amount : ". $tool['total_action_click_amount'] ."</small>";
														?>
													</div>
													<a href="javascript:void(0)" class="tog"> <?= __('user.toggle_data') ?> </a>
												</div>
											</td>
											<td><?= $tool['created_at'] ?></td>
										</tr>
									<?php } ?>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="integration-code"><div class="modal-dialog"><div class="modal-content"></div></div></div>

<script type="text/javascript">
	function myFunction() {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("txt_name");
		filter = input.value.toUpperCase();
		table = document.getElementById("myTable");
		tr = table.getElementsByTagName("tr");
		for (i = 0; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td")[1];
			if (td) {
				txtValue = td.textContent || td.innerText;
				if (txtValue.toUpperCase().indexOf(filter) > -1) {
					tr[i].style.display = "";
				} else {
					tr[i].style.display = "none";
				}
			}       
		}
	}

	$(".wallet-toggle .tog").on('click',function(){
		$(this).parents(".wallet-toggle").find("> div").toggleClass("hide");
	})

	$(".tool-remove-link").on('click',function(){
		if(!confirm("Are you sure?")) return false;
		return true;
	})

	$(".get-code").on('click',function(){
		$this = $(this);
		$.ajax({
			url:'<?= base_url("integration/tool_get_code/usercontrol") ?>',
			type:'POST',
			dataType:'json',
			data:{id:$this.attr("data-id")},
			beforeSend:function(){ $this.btn("loading"); },
			complete:function(){ $this.btn("reset"); },
			success:function(json){
				if(json['html']){
					$("#integration-code .modal-content").html(json['html']);
					$("#integration-code").modal("show");
				}
			},
		})
	})
</script>