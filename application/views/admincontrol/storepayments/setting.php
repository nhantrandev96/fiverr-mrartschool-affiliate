<?php
	$db =& get_instance();
	$userdetails=$db->userdetails();
	$store_setting =$db->Product_model->getSettings('store');
?>
<div class="row">
	<div class="col-lg-12 col-md-12">
		<?php if($this->session->flashdata('success')){?>
			<div  class="alert alert-success alert-dismissable">
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
	
<form id="setting-form">
	<div class="row">
		<div class="col-sm-12">
		    <div class="card">
		    	<div class="card-header">
		    		<h4 class="card-title">Payment Methods (<?= $payment_method['title'] ?>)</h4>
		    	</div>
		    	<div class="card-body">
		    		<?= $payment_method['setting'] ?>
		    	</div>
		    	<div class="card-footer">
		    		<button type="submit" class="btn btn-submit btn-primary">Save Settings</button>
		    	</div>
			</div>
	    </div>
	</div>
</form>

<script type="text/javascript">
	$("#setting-form").on('submit',function(){
		$this = $(this);
		$.ajax({
			type:'POST',
			dataType:'json',
			data:$this.serialize(),
			beforeSend:function(){
				$this.find('.btn-submit').btn("loading");
			},
			complete:function(){
				$this.find('.btn-submit').btn("reset");
			},
			success:function(json){
				if(json['redirect']){
					window.location.href = json['redirect'];
				}
			},
		})

		return false;
	})
</script>
