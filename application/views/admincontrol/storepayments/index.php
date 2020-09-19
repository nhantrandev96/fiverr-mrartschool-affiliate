<?php
	$db =& get_instance();
	$userdetails=$db->userdetails();
	$store_setting =$db->Product_model->getSettings('store');
?>
<div class="row">
	<div class="col-lg-12 col-md-12">
		<?php if($this->session->flashdata('success')){?>
			<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<?php echo $this->session->flashdata('success'); ?> </div>
		<?php } ?>
		<?php if($this->session->flashdata('error')){?>
			<div class="alert alert-danger">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<?php echo $this->session->flashdata('error'); ?> </div>
		<?php } ?>
	</div>
</div>
	
<div class="row">
	<div class="col-sm-12">
	    <div class="card">
	    	<div class="card-header">
	    		<h4 class="card-title">Payment Methods</h4>
	    	</div>
	    	<div class="card-body p-0">
	    		<div class="table-responsive">
		    		<table class="table table-hover">
		    			<thead>
		    				<tr>
		    					<th width="1"></th>
		    					<th>Title</th>
		    					<th>Code</th>
		    					<th>Website</th>
		    					<th width="140px">Status</th>
		    					<th>#</th>
		    				</tr>
		    			</thead>
		    			<tbody>
			    			<?php foreach ($payment_methods as $key => $payment_method) { ?>
				    			<tr>
				    				<td><?php if($payment_method['icon']) { ?> <img src="<?= base_url($payment_method['icon']) ?>"> <?php } ?></td>	
				    				<td><?= $payment_method['title'] ?></td>	
				    				<td><?= $payment_method['code'] ?></td>	
				    				<td><?php if($payment_method['website']) {
				    					echo "<a target='_blank' href='". $payment_method['website'] ."'>". $payment_method['title'] ."</a>";
				    				} ?></td>	
				    				<td>
				    					<div class="wallet-status-switch" style="width: 133px;margin: 0">
											<div class="radio radio-inline">
												<label><input type="radio" class="status-change-rdo" name="status_<?= $payment_method['code'] ?>" data-id="<?= $payment_method['code'] ?>" <?= $payment_method['status'] ? '' : 'checked' ?> value="0"><span>Disabled</span></label>
											</div>
											<div class="radio radio-inline loading">
											    <img src="<?= base_url('assets/images/switch-loading.svg') ?>">
											</div>
											<div class="radio radio-inline">
												<label><input type="radio" class="status-change-rdo" name="status_<?= $payment_method['code'] ?>" data-id="<?= $payment_method['code'] ?>" <?= $payment_method['status'] ? 'checked' : '' ?> value="1"><span>Enabled</span></label>
											</div>
										</div>
				    						
			    					</td>	
				    				<td width="1">
				    					<a class="btn btn-primary btn-sm" href="<?= base_url('admincontrol/storepayments_edit/'. $payment_method['code']) ?>">Edit</a>
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

<script type="text/javascript">
	$(".status-change-rdo").on('change',function(){
		$this = $(this);
		var id = $this.attr("data-id");
		var val = $this.val();
		$loading = $this.parents(".wallet-status-switch").find(".loading");
		
		$.ajax({
			type:'POST',
			dataType:'json',
			data:{id:id,val:val},
			beforeSend:function(){$loading.show();},
			complete:function(){$loading.hide();},
			success:function(json){

			},
		})
	});
</script>
