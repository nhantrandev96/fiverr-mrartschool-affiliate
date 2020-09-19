<div class="row">
			<div class="col-12">
				<div class="card m-b-30">
					<div class="card-header">
						<h4 class="card-title pull-left"><?= __('admin.form') ?></h4>
						<div class="pull-right">
							<a class="btn btn-primary" href="<?= base_url('admincontrol/form_manage/')  ?>"><?= __('admin.add_new'); ?></a>
						</div>
					</div>
					<div class="card-body">
						<div class="table-rep-plugin">

							<?php if($product_count == 0){ ?>
								<div class="alert alert-danger">
									<strong>Admin Product: </strong> You need to create product first <a href="<?= base_url('admincontrol/addproduct') ?>">Create First Product</a>
								</div>
							<?php } ?>
						    
						    <?php if ($forms == null) {?>
                                <div class="text-center">
                                <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:100px;">
                                 <h3 class="m-t-40 text-center text-muted"><?= __('admin.no_forms') ?></h3></div>
                        	<?php } else { ?>
								<div class="table-responsive b-0" data-pattern="priority-columns">
									<button style="display:none;" type="button" class="btn btn-info" name="deletebutton" id="deletebutton" value="Save & Exit" onclick="deleteuserlistfunc('deleteAllforms');"><?= __('admin.delete_products') ?></button>
									<br>
									<form method="post" name="deleteAllforms" id="deleteAllforms" action="<?php echo base_url();?>admincontrol/deleteAllforms">
										<table id="tech-companies-1" class="table  table-striped">
											<thead>
												<tr>
													<th width="50px"><input name="checkbox[]" type="checkbox" value="" onclick="checkAll(this)"></th>
													<th ><?= __('admin.form_title'); ?></th>
													
													<th width="200px"><?= __('admin.vendor'); ?></th>
													<th width="100px"><?= __('admin.coupon_code'); ?></th>
													<th width="150px"><?= __('admin.coupon_use'); ?></th>
													<th width="150px"><?= __('admin.sales_commission'); ?></th>
													<th width="150px"><?= __('admin.clicks_commissio'); ?>n</th>
													<th width="150px"><?= __('admin.total_commission'); ?></th>
													<th width="100px"><?= __('admin.status'); ?></th>
													<th width="220px"><?= __('admin.action'); ?></th>
												</tr>
											</thead>
											<tbody>
												<?php 
													$form_setting = $this->Product_model->getSettings('formsetting');
												?>
												<?php foreach($forms as $form){ ?>
													<tr>
														<td ><input name="checkbox[]" type="checkbox" id="check<?php echo $form['form_id'];?>" value="<?php echo $form['form_id'];?>" onclick="checkonly(this,'check<?php echo $form['form_id'];?>')"></td>
														<td>
															<?= $form['title'] ?>
															<div><small>
																<a href="<?= $form['public_page'] ?>" target='_black'><?= __('admin.public_page'); ?></a>
																</small>
															</div>
															<?php 
																if($form['form_recursion_type']){
													           		if($form['form_recursion_type'] == 'custom'){
													           			if($form['form_recursion'] != 'custom_time'){
													           				echo '<b>Recurring </b> : ' . $form['form_recursion'];
													           			} else {
													           				echo '<b>Recurring </b> : '. timetosting($form['recursion_custom_time']);
													           			}
													           		} else{
																		if($form_setting['form_recursion'] == 'custom_time' ){
												           					echo '<b>Recurring </b> : '. timetosting($form_setting['recursion_custom_time']);
																		} else {
																			echo '<b>Recurring </b> : '. $form_setting['form_recursion'];
																		}
													           		}
													           	}
															?>
														</td>
														<td><?= $form['firstname'] ? $form['firstname'] ." ". $form['lastname'] : 'Admin' ?></td>
														<td><?= $form['coupon_code'] ? $form['coupon_code'] : 'N/A' ?></td>
														<td><?= ($form['coupon_name'] ? $form['coupon_name'] : 'N/A').' / '.$form['count_coupon'] ?></td>
														<td><?= (int)$form['count_commission'].' / '.c_format($form['total_commission']) ?></td>
														<td><?= (int)$form['commition_click_count'].' / '.c_format($form['commition_click']); ?></td>
														<td><?= c_format($form['total_commission']+$form['commition_click']); ?></td>
														<td><?= form_status($form['status']); ?></td>
														<td>
															<a href="<?= base_url('admincontrol/form_manage/'.$form['form_id'])  ?>" class="btn btn-primary btn-sm edit-button" id="<?= $lang['id'] ?>"><?= __("admin.edit") ?></a>
															<button data-href="<?= base_url('admincontrol/form_delete/'.$form['form_id'])  ?>" class="btn btn-danger btn-sm delete-button" id="<?= $lang['id'] ?>"><?= __("admin.delete") ?></button>

															<div class="code-share-<?= $key ?>"></div>
			                                                <script type="text/javascript">
			                                                    $(document).on('ready',function(){
			                                                        $(".code-share-<?= $key ?>").jsSocials({
			                                                            url: "<?= $form['public_page'] ?>",
			                                                            showCount: false,
			                                                            showLabel: false,
			                                                            shareIn: "popup",
			                                                            shares: ["email", "twitter", "facebook", "googleplus", "linkedin", "pinterest", "stumbleupon", "whatsapp"]
			                                                        });
			                                                    })
			                                                </script>
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
				</div> 
			</div> 
		</div>
<div id="overlay"></div>
		<div class="popupbox" style="display: none;">
			<div class="backdrop box">
				<div class="modalpopup" style="display:block;">
					<a href="javascript:void(0)" class="close js-menu-close" onclick="closePopup();"><i class="fa fa-times"></i></a>
					<div class="modalpopup-dialog">
						<div class="modalpopup-content">
							<div class="modalpopup-body">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
<script type="text/javascript" async="">
function shareinsocialmedia(url){
	window.open(url,'sharein','toolbar=0,status=0,width=648,height=395');
	return true;
}
function copy_text() {
	var copyText = document.getElementById("store-link");
	copyText.select();
	document.execCommand("Copy");
}
function closePopup(){
		$('.popupbox').hide();
		$('#overlay').hide();
}
function generateCode(form_id){	
	$('.popupbox').show();
	$('#overlay').show();
	$('.modalpopup-body').load('<?php echo base_url();?>admincontrol/generateformcode/'+form_id);
	$('.popupbox').ready(function () {
		$('.backdrop, .box').animate({
			'opacity': '.50'
		}, 300, 'linear');
		$('.box').animate({
			'opacity': '1.00'
		}, 300, 'linear');
		$('.backdrop, .box').css('display', 'block');
	});
}
function checkAll(bx) {
	var cbs = document.getElementsByTagName('input');
	if(bx.checked)
	{
		document.getElementById('deletebutton').style.display = 'block';
		} else {
		document.getElementById('deletebutton').style.display = 'none';
	}
	for(var i=0; i < cbs.length; i++) {
		if(cbs[i].type == 'checkbox') {
			cbs[i].checked = bx.checked;
		}
	}
}
function deleteuserlistfunc(formId){
	$('#'+formId).submit();
}
function checkonly(bx,checkid) {
	if(bx.checked)
	{
		document.getElementById('deletebutton').style.display = 'block';
		} else {
		document.getElementById('deletebutton').style.display = 'none';
	}
}
$(document).on('ready',function(){
	$('.delete-button').on('click',function(){
		var r = confirm("Are  You Sure want to Delete Form?");
		if (r == true) {		    
			location = $(this).data("href");
		}
		return false;
	})
})
</script>