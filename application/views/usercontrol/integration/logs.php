<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<div>
							<h5 class="pull-left"><?php echo __('user.integration_logs') ?></h5>
						</div>
					</div>

					<div class="card-body">

						<div class="well">
							<form>
								<div class="row">
									<div class="col-sm-3">
										<div class="form-group">
											<label class="control-label">Type</label>
											<?php $selected = isset($_GET['type']) ? $_GET['type'] : ''; ?>
											<select class="form-control" name="type">
												<option value=""><?= __('user.all') ?></option>
												<option <?= $selected == 'action' ? 'selected' : '' ?> value="action"><?= __('user.action') ?></option>
												<option <?= $selected == 'integration_sale' ? 'selected' : '' ?> value="integration_sale"><?= __('user.integration_sale') ?></option>
												<option <?= $selected == 'product_click' ? 'selected' : '' ?> value="product_click"><?= __('user.product_click') ?></option>
												<option <?= $selected == 'store_sale' ? 'selected' : '' ?> value="store_sale"><?= __('user.store_sale') ?></option>
											</select>
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<label class="control-label d-block">&nbsp;</label>
											<div>
												<button class="btn btn-primary" type="submit">Filter</button>
											</div>
										</div>
									</div>
									<div class="col-sm-3"></div>
								</div>
							</form>
						</div>
					    
					    <div class="table-rep-plugin">
					            <?php if ($logs ==null) {?>
                                    <div class="text-center">
                                    <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:25px;">
                                     <h3 class="m-t-40 text-center"><?= __('user.no_logs') ?></h3></div>
                                    <?php }
                                    else {?>
                                    
                                    
						<div class="table-responsive">
							<table class="table-tiny toggle-tr">
								<thead>
									<tr>
										<th width="50px" class="text-left">ID</th>
										<th class="text-left">Website</th>
							            <th class="text-left">Ip</th>
							            <th class="text-left">Created At</th>
							            <th class="text-left">Click Type</th>
							            <th width="20px">#</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($logs as $key => $log) { ?>
										<tr class="toggler">
											<td class="text-left"><?= $log['id'] ?></td>
											<td class="text-left"><?= $log['base_url'] ?></td>
								            
								            <td class="text-left"><?= $log['flag'] ?> <?= $log['ip'] ?> - <small><?= $log['country_code'] ?></small></td>
								            <td class="text-left"><?= $log['created_at'] ?></td>
								            <td class="text-left"><?= $log['click_type'] ?></td>
								            <td class="text-left"><button class="btn btn-primary btn-sm"><i class="fa fa-info"></i></button></td>
										</tr>
										<tr style="display: none">
												<td></td>
												<td colspan="6">
													<div class="row">
														<div class="col-sm-3">
															<label>Page : </label> <span><?= $log['link'] ?></span>
														</div>
														<div class="col-sm-3">
															<label>Browser : </label> <span><?= $log['browserName'] ?> - <small><?= $log['browserVersion'] ?></small></span>
														</div>
														<div class="col-sm-3">
															<label>Os Platform : </label> <span><?= $log['osPlatform'] ?> -  <small> Version : <?= $log['osVersion'] ?></small></span>
														</div>
														<div class="col-sm-3">
															<label>Mobile Name : </label> <span><?= $log['mobileName'] ?></span>
														</div>
													</div>
												</td>
											</tr>
									<?php } ?>

								</tbody>
								<tfoot>
									<tr>
										<td colspan="100%"><ul class="pagination"><?= $pagination ?></ul></td>
									</tr>
								</tfoot>
							</table>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>


<div class="modal fade" id="integration-code"><div class="modal-dialog"><div class="modal-content"></div></div></div>

<script type="text/javascript">
	$(".wallet-toggle .tog").on('click',function(){
		$(this).parents(".wallet-toggle").find("> div").toggleClass("hide");
	})
	$(".tool-remove-link").on('click',function(){
		if(!confirm("Are you sure?")) return false;
		return true;
	})

	$(".toggle-tr tbody tr.toggler").on('click',function(){
		$(this).next('tr').slideToggle('fast');
	})

	$(".get-code").on('click',function(){
		$this = $(this);
		$.ajax({
			url:'<?= base_url("integration/tool_get_code") ?>',
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