		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<!-- page content -->
					<div class="x_panel">
						<div class="row top_tiles">
							<?php if($affiliateads_type != 'banner') { ?>
								<div class="col-lg-3">
									<div class="card m-b-30 text-block card-warning">
										<div class="card-body">
											<blockquote class="card-bodyquote">
												<h5><?= __('user.affiliate_banners') ?></h5>
												<footer>
													<a href="<?php echo base_url();?>usercontrol/affiliateadslist/banner" class="btn btn-success" title="view"><i class="fa fa-search"></i></a>
												</footer>
											</blockquote>
										</div>
									</div>
								</div>
							<?php } ?>
							<?php if($affiliateads_type != 'html') { ?>
								<div class="col-lg-3">
									<div class="card m-b-30 text-block card-default">
										<div class="card-body">
											<blockquote class="card-bodyquote">
												<h5><?= __('user.affiliate_html_ads') ?></h5>
												<footer>
													<a href="<?php echo base_url();?>usercontrol/affiliateadslist/html" class="btn btn-success" title="view"><i class="fa fa-search"></i></a>
												</footer>
											</blockquote>
										</div>
									</div>
								</div>
							<?php } ?>
							<?php if($affiliateads_type != 'invisilinks') { ?>
								<div class="col-lg-3">
									<div class="card m-b-30 text-block card-info">
										<div class="card-body">
											<blockquote class="card-bodyquote">
												<h5><?= __('user.invisible_links') ?></h5>
												<footer>
													<a href="<?php echo base_url();?>usercontrol/affiliateadslist/invisilinks" class="btn btn-success" title="view"><i class="fa fa-search"></i></a>
												</footer>
											</blockquote>
										</div>
									</div>
								</div>
							<?php } ?>
							<?php if($affiliateads_type != 'viralvideo') { ?>
								<div class="col-lg-3">
									<div class="card m-b-30 text-block card-danger">
										<div class="card-body">
											<blockquote class="card-bodyquote">
												<h5><?= __('user.affiliate_viral_videos') ?></h5>
												<footer>
													<a href="<?php echo base_url();?>usercontrol/affiliateadslist/viralvideo" class="btn btn-success" title="view"><i class="fa fa-search"></i></a>
												</footer>
											</blockquote>
										</div>
									</div>
								</div>
							<?php } ?>
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
					<div class="row">
						<div class="col-12">
							<div class="card m-b-30">
								<div class="card-body">
									<div class="table-rep-plugin">
										<div class="table-responsive b-0" data-pattern="priority-columns">
											<?php if($this->session->flashdata('success')){?>
												<div class="alert alert-success alert-dismissable my_alert_css">
													<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
													<?php echo $this->session->flashdata('success'); ?>
												</div>
											<?php } ?>
											<?php if($this->session->flashdata('error')){?>
												<div class="alert alert-danger alert-dismissable my_alert_css">
													<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
													<?php echo $this->session->flashdata('error'); ?>
												</div>
											<?php } ?>
											<table id="tech-companies-1" class="table  table-striped">
												<thead>
													<tr>
														<th><?= __('user.name_title') ?></th>
														<th><?= __('user.get_commission_click') ?></th>
														<th><?= __('user.clicks_commission') ?></th>
														<th><?= __('user.paid_unpaid_commissions') ?></th>
														<th><?= __('user.type') ?></th>
													</tr>
												</thead>
												<tbody>
													<?php foreach($getAffiliate as $affiliate){ ?>
														<tr>
															<td>
																<div class="tooltip-copy">
																	<?php
																		if(!empty($affiliate)){
																			$getData = json_decode($affiliate['affiliateads_metadata'],true);
																		}
																	?>
																	<span>
																		<?php 
																			if($affiliate['affiliateads_type'] == 'banner') { 
																				echo $getData['postdata']['banner_name'];
																			} 
																			else if($affiliate['affiliateads_type'] == 'html') { 
																				echo $affiliate['affiliateads_type'];
																			} 
																			else if($affiliate['affiliateads_type'] == 'invisilinks') {
																				echo $affiliate['affiliateads_type'];
																			} 
																			else if($affiliate['affiliateads_type'] == 'viralvideo') {
																				echo $affiliate['affiliateads_type'];
																			} 
																		?>
																	</span>
																	<br>
																	<small>
																		
																		<a target='_blank' href="<?= base_url('usercontrol/market_priview/'. $affiliate['affiliateads_id'] .'/priview') ?>"> Priview Landing</a> /
																		<?php if($affiliate['affiliateads_type'] == 'banner') { ?>
																			<a href="javascript:void(0);" onclick="generateCode(<?php echo $affiliate['affiliateads_id'];?>,'<?php echo $affiliate['affiliateads_type'];?>',this);" >Preview On Website</a>
																		<?php } ?>
																		<?php if($affiliate['affiliateads_type'] == 'html') { ?>
																			<a href="javascript:void(0);" onclick="generateCode(<?php echo $affiliate['affiliateads_id'];?>,'<?php echo $affiliate['affiliateads_type'];?>',this);" >Preview On Website</a>
																		<?php } ?>
																		<?php if($affiliate['affiliateads_type'] == 'invisilinks') { ?>
																			<a href="javascript:void(0);" onclick="generateCode(<?php echo $affiliate['affiliateads_id'];?>,'<?php echo $affiliate['affiliateads_type'];?>',this);" >Preview On Website</a>
																		<?php } ?>
																		<?php if($affiliate['affiliateads_type'] == 'viralvideo') { ?>
																			<a href="javascript:void(0);" onclick="generateCode(<?php echo $affiliate['affiliateads_id'];?>,'<?php echo $affiliate['affiliateads_type'];?>',this);" >Preview On Website</a>
																		<?php } ?>
																	</small>
																</div>
															</td>
															<td>
																<?php echo c_format($config['affiliate_commission']) ?>/
																<?php echo $config['affiliate_ppc'] ?>
															</td>
															<td>
																<div class="tooltip-copy">
																	<?php echo (int)$affiliate['total_click'];?>/
																	<?php echo c_format((float)$affiliate['total_unpaid'] + (float)$affiliate['total_paid']); ?>
																</div>
															</td>
															<td class="txt-cntr">
																<?php echo c_format($affiliate['total_paid']); ?>/
																<?php echo c_format($affiliate['total_unpaid']); ?>
															</td>
															<td class="txt-cntr">
																<?php echo ucfirst($affiliate['affiliateads_type']);?>
															</td>
														</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
<script type="text/javascript" async="">
	
	function generateCode(affiliate_id,type,t){
		$this = $(t);
		$.ajax({
			url:'<?php echo base_url();?>usercontrol/generatecode/'+affiliate_id+'/'+type,
			type:'GET',
			dataType:'html',
			beforeSend:function(){ 
				$this.button("loading"); 
				var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
			    if ($this.html() !== loadingText) {
			      $this.data('original-text', $this.html());
			      $this.html(loadingText);
			    }
			},
			complete:function(){ $this.html($this.data('original-text')); },
			success:function(html){
				$('#modal-image').remove();
				$('body').append('<div id="modal-image" class="modal">' + html + '</div>');
				$('#modal-image').modal('show');
			},
		})
	}
</script>	