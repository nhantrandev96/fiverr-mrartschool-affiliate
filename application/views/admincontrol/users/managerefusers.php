<div class="row">
			<div class="col-12">
				<div class="card m-b-30">
					<div class="card-body">
						<div class="table-rep-plugin">
							<div class="table-responsive b-0" data-pattern="priority-columns">
								<table id="tech-companies-1" class="table  table-striped">
									<thead>
										<tr>
											<th data-priority="1"><?= __('admin.name') ?></th>
											<th data-priority="3"><?= __('admin.last_name') ?></th>
											<th data-priority="1"><?= __('admin.email') ?></th>
											<th data-priority="3"><?= __('admin.username') ?></th>
											<th data-priority="3"><?= __('admin.clicks') ?></th>
											<th data-priority="1"><?= __('admin.sales') ?></th>
											<th data-priority="3"><?= __('admin.total_commissions') ?></th>
											<th data-priority="3"><?= __('admin.action') ?></th>
										</tr>  
									</thead> 
									<tbody>
										<?php foreach($refUsers as $users){ ?>
											<tr>
												<td>
													
													<?php echo $users['firstname'];?>
												</td>
												<td>
													
													<?php echo $users['lastname'];?>
												</td>
												<td class="txt-cntr">
													<?php echo $users['email'];?>
												</td>
												<td class="txt-cntr">
													<?php echo $users['username'];?>
												</td>
												<td>
													<div class="tooltip-copy">
														<?php echo $users['product_total_click'] + $users['affiliate_total_click'];?>
													</div>
												</td>
												<td class="txt-cntr">
													<?php echo $users['product_total_sale'];?> 
													
												</td>
												<td class="txt-cntr">
													<?php echo number_format((float)$users['product_commission'], 2, '.', '') + number_format((float)$users['affiliate_commission'], 2, '.', '') + number_format((float)$users['sale_commission'], 2, '.', '');?>
												</td>
												<td class="txt-cntr">
													
													<a onclick="return confirm('<?= __('admin.are_you_sure') ?>');" href="<?php echo base_url();?>admincontrol/addusers/<?php echo $users['id'];?>"><i class="fa fa-edit cursors" aria-hidden="true" style="color:#2a3f54"></i></a>
													<div class="dropdown">
														<i class="dropbtn fa fa-share-alt cursors" aria-hidden="true" style="color:#2a3f54"></i>
														<div class="dropdown-content">
															<a onclick="shareinsocialmedia('https://www.facebook.com/sharer/sharer.php?u=<?php echo base_url();?>usercontrol/<?php echo $users['product_slug'];?>/<?php echo $user['id'];?>&amp;title=Buy Product and earn by affiliate program')" href=""><i class="fa fa-facebook fa-6" aria-hidden="true"></i></a>
															<a onclick="shareinsocialmedia('https://plus.google.com/share?url=<?php echo base_url();?>product/<?php echo $product['product_slug'];?>/<?php echo $user['id'];?>')" href=""><i class="fa fa-google-plus fa-6" aria-hidden="true"></i></a>
															<a onclick="shareinsocialmedia('http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo base_url();?>product/<?php echo $product['product_slug'];?>/<?php echo $user['id'];?>&amp;title=Buy Product and earn by affiliate program')" href=""><i class="fa fa-linkedin fa-6" aria-hidden="true"></i></a>
															<a onclick="shareinsocialmedia('http://twitter.com/home?status=Buy Product and earn by affiliate program+<?php echo base_url();?>product/<?php echo $product['product_slug'];?>/<?php echo $user['id'];?>')" href=""><i class="fa fa-twitter fa-6" aria-hidden="true"></i></a>
															<a href="mailto:?subject=Buy Product and earn by affiliate program&amp;body=Check out this site <?php echo base_url();?>product/<?php echo $product['product_slug'];?>/<?php echo $user['id'];?>" title="Share by Email">
																<i class="fa fa-envelope cursors" aria-hidden="true" style="color:#2a3f54"></i>
															</a>
														</div>
													</div>
													
												</td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div> <!-- end col -->
			</div> <!-- end row -->
		</div><!-- container -->

<script type="text/javascript" async="">
	function shareinsocialmedia(url){
		window.open(url,'sharein','toolbar=0,status=0,width=648,height=395');
		return true;
	}
</script>											