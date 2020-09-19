<?php
    $db =& get_instance();
    $Product_model = $db->Product_model;
    $userdetails = $db->Product_model->userdetails('user');
?>

<br>
<div class="card">
	<div class="card-header">
		<h5 class="m-0 card-title pull-left">Products</h5>
		<div class="pull-right">
			<a class="btn btn-lg btn-default btn-success" href="<?php echo base_url("usercontrol/store_edit_product") ?>"><?= __('admin.add_product') ?></a>
			<button type="button" class="btn d-none btn-danger" id="deletebutton" onclick="deleteuserlistfunc('deleteAllproducts');"><?= __('admin.delete_products') ?></button>
		</div>
	</div>
	<div class="card-body">
		<?php if ($productlist == null) {?>
            <div class="text-center">
            <img class="img-responsive" src="<?php echo base_url('assets/vertical/assets/images/no-data-2.png'); ?>" style="margin-top:100px;">
         	<h3 class="m-t-40 text-center text-muted"><?= __('admin.no_products') ?></h3></div>
        <?php } else { ?>
        	<div class="table-responsive b-0" data-pattern="priority-columns">
				<form method="post" name="deleteAllproducts" id="deleteAllproducts" action="<?php echo base_url('usercontrol/deleteAllproducts'); ?>">
					<table id="tech-companies-1" class="table  table-striped">
						<thead>
							<tr>
								<th><input name="product[]" type="checkbox" value="" onclick="checkAll(this)"></th>
								<th width="220px"><?= __('admin.product_name') ?></th>
								<th><?= __('admin.featured_image') ?></th>
								<th><?= __('admin.price') ?></th>
								<th><?= __('admin.sku') ?></th>
								<th width="220px"><?= __('admin.get_ncommission') ?></th>
								<th><?= __('admin.sales_/_commission') ?></th>
								<th><?= __('admin.clicks_/_commission') ?></th>
								<th><?= __('admin.total') ?></th>
								<th><?= __('admin.status') ?></th>
								<th><?= __('admin.action') ?></th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$pro_setting = $Product_model->getSettings('productsetting');
								$vendor_setting = $Product_model->getSettings('vendor');
							?>

							<?php foreach($productlist as $product){ ?>
								<?php 
									$productLink = base_url('store/'. base64_encode($userdetails['id']) .'/product/'.$product['product_slug'] ).'?preview=1';
								?>
								<tr>
									<td><input name="product[]" type="checkbox" id="check<?php echo $product['product_id'];?>" value="<?php echo $product['product_id'];?>"></td>
									<td>
										<div class="tooltip-copy">
											<?php if($product['product_type'] == 'downloadable'){ ?>
												<img src="<?= base_url('assets/images/download.png') ?>" width="20px">
											<?php } ?>
											<span><?php echo $product['product_name'];?></span>
											<div><small><a target="_blank" href="<?= $productLink ?>">Public Page</a></small></div>
										</div>
									</td>
									<td>
										<div class="tooltip-copy">
											<img width="50px" height="50px" src="<?php echo resize('assets/images/product/upload/thumb/'. $product['product_featured_image'] ,100,100) ?>" ><br>
										</div>
									</td>
									<td class="txt-cntr"><?php echo c_format($product['product_price']); ?></td>
									<td class="txt-cntr"><?php echo $product['product_sku'];?></td>
									<td class="txt-cntr commission-tr">
										<?php 
											if($product['seller_id']){
												$seller = $Product_model->getSellerFromProduct($product['product_id']);
												$seller_setting = $Product_model->getSellerSetting($seller->user_id);

												$commnent_line = "";
												if($seller->affiliate_sale_commission_type == 'default'){ 
													if($seller_setting->affiliate_sale_commission_type == ''){
														$commnent_line .= ' Warning : Default Commission Not Set';
													}
													else if($seller_setting->affiliate_sale_commission_type == 'percentage'){
														$commnent_line .= 'Percentage : '. (float)$seller_setting->affiliate_commission_value .'%';
													}
													else if($seller_setting->affiliate_sale_commission_type == 'fixed'){
														$commnent_line .= 'Fixed : '. c_format($seller_setting->affiliate_commission_value);
													}
												} else if($seller->affiliate_sale_commission_type == 'percentage'){
													$commnent_line .= 'Percentage : '. (float)$seller->affiliate_commission_value .'%';
												} else if($seller->affiliate_sale_commission_type == 'fixed'){
													$commnent_line .= 'Fixed : '. c_format($seller->affiliate_commission_value);
												} 

												echo '<b>Sale</b> : ' .$commnent_line;

												$commnent_line = "";
												if($seller->affiliate_click_commission_type == 'default'){ 
													$commnent_line .= c_format($seller_setting->affiliate_click_amount) ." Per ". (int)$seller_setting->affiliate_click_count ." Clicks";
												} else{
													$commnent_line .= c_format($seller->affiliate_click_amount) ." Per ". (int)$seller->affiliate_click_count ." Clicks";
												} 
												echo '<br><b>Click</b> : ' .$commnent_line;


												
												$commnent_line = '';
												if($seller->admin_click_commission_type == '' || $seller->admin_click_commission_type == 'default'){
													$commnent_line =  c_format($vendor_setting['admin_click_amount']) ." Per ". (int)$vendor_setting['admin_click_count'] ." Clicks";
												} else{ 
													$commnent_line =  c_format($seller->admin_click_amount) ." Per ". (int)$seller->admin_click_count ." Clicks";
												} 

												echo '<br><b>Admin Click</b> : ' .$commnent_line;

												$commnent_line = '';
												if($seller->admin_sale_commission_type == '' || $seller->admin_sale_commission_type == 'default'){ 
													if($vendor_setting['admin_sale_commission_type'] == ''){
														$commnent_line .= ' Warning : Default Commission Not Set';
													}
													else if($vendor_setting['admin_sale_commission_type'] == 'percentage'){
														$commnent_line .= ''. (float)$vendor_setting['admin_commission_value'] .'%';
													}
													else if($vendor_setting['admin_sale_commission_type'] == 'fixed'){
														$commnent_line .= ''. c_format($vendor_setting['admin_commission_value']);
													}
												} else if($seller->admin_sale_commission_type == 'percentage'){
													$commnent_line .= ''. (float)$seller->admin_commission_value .'%';
												} else if($seller->admin_sale_commission_type == 'fixed'){
													$commnent_line .= ''. c_format($seller->admin_commission_value);
												} else {
													$commnent_line .= ' Warning : Commission Not Set';
												} 

												echo '<br><b>Admin Sale</b> : ' .$commnent_line;
											} 
										?>


										<?php 
											if($product['product_recursion_type']){
								           		if($product['product_recursion_type'] == 'custom'){
								           			if($product['product_recursion'] != 'custom_time'){
								           				echo '<br><b>'. __('admin.recurring') .' </b> : ' .  __('admin.'.$product['product_recursion']);
								           			} else {
								           				echo '<br><b>'. __('admin.recurring') .' </b> : '. timetosting($product['recursion_custom_time']);
								           			}
								           		} else{
													if($pro_setting['product_recursion'] == 'custom_time' ){
							           					echo '<br><b>'. __('admin.recurring') .' </b> : '. timetosting($pro_setting['recursion_custom_time']);
													} else {
														echo '<br><b>'. __('admin.recurring') .' </b> : '.  __('admin.'.$pro_setting['product_recursion']);
													}
								           		}
								           	}
										?>
									</td>
									<td class="txt-cntr">
										<?php echo $product['order_count'];?> / 
										<?php echo c_format($product['commission']) ;?>
									</td>
									<td class="txt-cntr">
										<?php echo (int)$product['commition_click_count'];?> / 
										<?php echo c_format($product['commition_click']) ;?>
									</td>
									<td class="txt-cntr">
										<?php echo
											c_format((float)$product['commition_click'] + (float)$product['commission']);
										?>
									</td>
									<td class="txt-cntr">
										<?= product_status_on_store($product['on_store']) ?>
										<?= product_status($product['product_status']) ?>	
									</td>
									<td class="txt-cntr">
										<?php if((int)$product['product_status'] == 0){ ?>
											<button type="button" class="btn btn-sm btn-primary edit-product" data-href="<?php echo base_url('usercontrol/store_edit_product/'. $product['product_id']);?>"><i class="fa fa-edit cursors" aria-hidden="true"></i></button>
										<?php } else { ?>
											<a class="btn btn-sm btn-primary" onclick="return confirm('Are you sure you want to Edit?');" href="<?php echo base_url('usercontrol/store_edit_product/'. $product['product_id']);?>"><i class="fa fa-edit cursors" aria-hidden="true"></i></a>
										<?php } ?>

										<a class="btn btn-sm btn-primary"  href="<?php echo base_url('usercontrol/duplicateProduct/'. $product['product_id']);?>">Duplicate</a>

										<a class="btn btn-sm btn-primary" href="<?php echo base_url('usercontrol/productupload/'. $product['product_id']);?>"><i class="fa fa-image cursors"></i></a>
                            			<a class="btn btn-sm btn-primary" href="<?php echo base_url('usercontrol/videoupload/'. $product['product_id']);?>"><i class="fa fa-youtube cursors"></i></a>
										<button class="btn btn-danger btn-sm delete-product" type="button" data-id="<?= $product['product_id'] ?>"> <i class="fa fa-trash"></i> </button>

										<div>
											<div class="code-share-<?= $key ?>"></div>
                                            <script type="text/javascript">
                                                $(document).on('ready',function(){
                                                    $(".code-share-<?= $key ?>").jsSocials({
                                                        url: "<?= $productLink ?>",
                                                        showCount: false,
                                                        showLabel: false,
                                                        shareIn: "popup",
                                                        shares: ["email", "twitter", "facebook", "googleplus", "linkedin", "pinterest", "stumbleupon", "whatsapp"]
                                                    });
                                                })
                                            </script>
										</div>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</form>
			</div>
		<?php } ?>
	</div>
</div>


<div class="modal" id="model-reviewmessage">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="m-0 modal-title">Review</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>This product is in review and if you try to edit then review will be cancel and you need send to review again..!</p>
			</div>
			<div class="modal-footer">
				<a href="" class="btn btn-primary" id="edit_product_link">Okay Edit</a>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(".delete-product").on('click',function(){
		if(! confirm("Are you sure ?")) return false;
		window.location = $("#deleteAllproducts").attr("action") + "?delete_id=" + $(this).attr("data-id");
	})

	$(".edit-product").on('click',function(){
		$("#model-reviewmessage").modal("show");
		$("#edit_product_link").attr("href", $(this).attr("data-href"))
	})
	
	function checkAll(bx) {
		var cbs = $(bx).prop("checked");

		if(cbs){
			$('#deletebutton').removeClass('d-none');
		} else {
			$('#deletebutton').addClass('d-none');
		}

		$("tbody input[type=checkbox]").prop("checked", cbs);
	}

	$("tbody input[type=checkbox]").on("change",function(){
		if($("tbody input[type=checkbox]:checked").length){
			$('#deletebutton').removeClass('d-none');
		} else {
			$('#deletebutton').addClass('d-none');
		}
	})

	function deleteuserlistfunc(formId){
		if(! confirm("Are you sure ?")) return false;
		$('#'+formId).submit();
	}
</script>