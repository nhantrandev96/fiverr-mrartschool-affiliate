		<div class="row">
			<div class="col-lg-12 col-md-12">
				<?php if($this->session->flashdata('success')){?>
					<div class="alert alert-success alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<?php echo $this->session->flashdata('success'); ?> </div>
				<?php } ?>
				
				<?php if($this->session->flashdata('error')){?>
					<div class="alert alert-danger alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<?php echo $this->session->flashdata('error'); ?> </div>
				<?php } ?>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="card m-b-30">
					<div class="card-body">
						<div class="table-rep-plugin">
						    
						    <?php if ($getallorders == null) {?>
                                <div class="text-center">
                                <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:100px;">
                                 <h3 class="m-t-40 text-center text-muted"><?= __('admin.no_orders') ?></h3></div>
                                <?php }
                                else {?>
                                
                                
							<div class="table-responsive b-0" data-pattern="priority-columns">
								<table class="table  table-striped">
									<thead>
										<tr>
											<th width="50px"><?= __('admin.order_id') ?></th>
											<th width="80px"><?= __('admin.price') ?></th>
											<th class="txt-cntr"><?= __('admin.order_status') ?></th>
											<th><?= __('admin.payment_method') ?></th>
											<th width="80px"><?= __('admin.ip') ?></th>
											<th><?= __('admin.transaction') ?></th>
											<th><?= __('admin.status') ?></th>
											<th width="80px"></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($getallorders as $product){ ?>
											<tr>
												<td><?php echo $product['id'];?></td>
												<td class="txt-cntr"><?php echo c_format($product['total_sum']); ?></td>
												<td class="txt-cntr order-status"><?php echo $status[$product['status']]; ?></td>
												<td class="txt-cntr"><?php echo str_replace("_", " ", $product['payment_method']) ?></td>
												<td class="txt-cntr"><?php echo $product['order_country_flag'];?></td>
												<td class="txt-cntr"><?php echo $product['txn_id'];?></td>
												<td>
													<div class="wallet-status-switch m-0" style="width: 150px">
														<div class="radio radio-inline">
															<label><input type="radio" <?= $product['status'] == 1 ? 'checked' : '' ?> class="status-change-rdo" name="status_<?= $product['id'] ?>" data-id='<?= $product['id'] ?>' value="1" ><span>Complete</span></label>
														</div>
														<div class="radio radio-inline loading">
															<img src="<?=  base_url('assets/images/switch-loading.svg') ?>">
														</div>
														<?php if($product['status'] == 7 || $product['status'] == 1) { ?>
															<div class="radio radio-inline">
																<label><input type="radio" <?= $product['status'] == 7 ? 'checked' : '' ?> class="status-change-rdo" name="status_<?= $product['id'] ?>" data-id='<?= $product['id'] ?>' value="7" ><span>Processed</span></label>
															</div>
														<?php } else { ?>
															<div class="radio radio-inline">
																<label><input type="radio" checked class="status-change-rdo" name="status_<?= $product['id'] ?>" data-id='<?= $product['id'] ?>' value="<?= $product['status'] ?>" ><span><?= $status[$product['status']] ?></span></label>
															</div>
														<?php } ?>
													</div>
												</td>
												<td>
													<a href="<?= base_url('admincontrol/vieworder/'. $product['id']) ?>" class="btn btn-primary btn-sm"><?= __('admin.details') ?></a>
												</td>
											</tr>
										<?php } ?>
										<?php }?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div> 
			</div> 
		</div>


		<div class="modal" id="model-confirmodal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h6 class="modal-title m-0">Change Order Status</h6>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<p class="text-center">
							After order status will be change to completed than the commission will be release to affiliate  and can't be change 
						</p> 
						<p class="text-center">If your order will be cancel from some reason, you can always delete the transaction from your admin wallet</p>
						<br>
						<p class="text-center"><b>Are you sure ?</b></p>
						<div class="text-center modal-buttons">
							<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
		</div>

<script type="text/javascript">
	$(".status-change-rdo").change(function(e){
		$this = $(this);
		var id = $this.attr("data-id");
		var val = $this.val();

		if(val == "1"){
			e.preventDefault();

			$input = $this.parents(".wallet-status-switch").find("input:radio:not(:checked)");
			$input.prop("checked",1)

			$(".btn-status-change").remove();
			$btn = $('<button type="button" class="btn btn-status-change btn-primary">Yes Sure</button>');
			$btn.on('click',function(){
				changeStatus($this,id,val,1)
			});
			$btn.prependTo(".modal-buttons");
			$("#model-confirmodal").modal("show");
			return false;
		}

		changeStatus($this,id,val)
	});

	function changeStatus(t,id,val,is_modal){
		$loading = $(t).parents(".wallet-status-switch").find(".loading");
		
		$.ajax({
			url: '<?php echo base_url("admincontrol/order_change_status") ?>',
			type:'POST',
			dataType:'json',
			data:{id:id,val:val},
			beforeSend:function(){$loading.show(); $(".btn-status-change").btn("loading")},
			complete:function(){$loading.hide(); $(".btn-status-change").btn("reset")},
			success:function(json){
				if(json['status']){
					$(t).parents("tr").find('.order-status').html(json['status'])
				}
				if(is_modal){
					$("#model-confirmodal").modal("hide");
					$input = $(t).parents(".wallet-status-switch").find("input:radio:not(:checked)");
					$input.prop("checked",1)
				}
			},
		})
	}
</script>