		<div class="row">
			<div class="col-12">
				<div class="card m-b-30">					
					<div class="card-body">
						<div class="table-rep-plugin">
						    
			            <?php if ($forms ==null) {?>
                            <div class="text-center">
                            <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:25px;">
                             <h3 class="m-t-40 text-center"><?= __('user.no_forms') ?></h3></div>
                        <?php } else { ?>
							<div class="table-responsive b-0" data-pattern="priority-columns">
								<table id="tech-companies-1" class="table  table-striped">
									<thead>
										<tr>
											<th ><?= __('user.form_title'); ?></th>
											<th width="150px"><?= __('admin.vendor'); ?></th>
											<th width="150px"><?= __('admin.coupon_code'); ?></th>
											<th width="150px"><?= __('admin.coupon_use'); ?></th>
											<th width="150px"><?= __('user.sales_commission'); ?></th>
											<th width="150px"><?= __('user.clicks_commission'); ?></th>
											<th width="150px"><?= __('user.total_commission'); ?></th>
											<th width="180px"><?= __('user.action'); ?></th>											
										</tr>
									</thead>
									<tbody>
										<?php foreach($forms as $form){ ?>
											<tr>
												<td><?= $form['title'] ?>
													<div>
														<small>
															<a href="javascript:void(0)" copyToClipboard="<?= $form['public_page'] ?>">Copy link</a> /
															<a href="<?= $form['public_page'] ?>"  target='_black'><?= __('user.public_page'); ?></a> /
															<a href="javascript:void(0);" onclick="generateCode(<?php echo $form['form_id'];?>);" ><?= __('user.get_ncode') ?></a>
														</small>
													</div>
												</td>
												<td><?= $form['firstname'] ?> <?= $form['lastname'] ?></td>
												<td><b><?= $form['coupon_code'] ?></b></td>
												<td><?= ($form['coupon_name'] ? $form['coupon_name'] : '-').' / '.$form['count_coupon'] ?></td>
												<td><?= (int)$form['count_commission'].' / '.c_format($form['total_commission']) ?></td>
												<td><?= (int)$form['commition_click_count'].' / '.c_format($form['commition_click']); ?></td>
												<td><?= c_format($form['total_commission']+$form['commition_click']); ?></td>
												<td>
													<div class="dropdown">
															<div class="dropdown-content">
																<a onclick="shareinsocialmedia('https://www.facebook.com/sharer/sharer.php?u=<?php echo $form['public_page']?>/<?php echo $user['id'];?>&amp;title=Buy Product and earn by affiliate program')" href=""><i class="fa fa-facebook fa-6" aria-hidden="true"></i></a>
																<a onclick="shareinsocialmedia('https://plus.google.com/share?url=<?php echo $form['public_page']?>/<?php echo $user['id'];?>')" href=""><i class="fa fa-google-plus fa-6" aria-hidden="true"></i></a>
																<a onclick="shareinsocialmedia('http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $form['public_page']?>/<?php echo $user['id'];?>&amp;title=Buy Product and earn by affiliate program')" href=""><i class="fa fa-linkedin fa-6" aria-hidden="true"></i></a>
																<a onclick="shareinsocialmedia('http://twitter.com/home?status=Buy Product and earn by affiliate program+<?php echo $form['public_page']?>/<?php echo $user['id'];?>')" href=""><i class="fa fa-twitter fa-6" aria-hidden="true"></i></a>
																<a href="mailto:?subject=Buy Product and earn by affiliate program&amp;body=Check out this site <?php echo $form['public_page']?>/<?php echo $user['id'];?>" title="Share by Email">
																	<i class="fa fa-envelope cursors" aria-hidden="true" style="color:#2a3f54"></i>
																</a>
															</div>
														</div></td>												
											</tr>
										<?php } ?>
									</tbody>
								</table>
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
	$('.modalpopup-body').load('<?php echo base_url();?>usercontrol/generateformcode/'+form_id);
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
</script>