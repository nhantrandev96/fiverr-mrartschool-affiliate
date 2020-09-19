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

<div class="plugin-uploader">
	<p class="text-center text-help">If you have a plugin in a .zip format, you may install it by uploading it here. <br>If you want to create custom payment gateway than follow this <a href="<?= base_url('admincontrol/withdrawal_payment_gateways_doc') ?>">documentation</a></p>

	<div class="contain">
		<div class="div-input">
			<input type="file" id="plugin-file" name="plugin">
			<div class="bg-danger px-2 py-1 text-left text-light warning d-none"></div>
		</div>
		<div class="div-button">
			<button class="btn btn-primary btn-sm" id="plugin-file-button" disabled="">Install  Now</button>
		</div>
	</div>
	
</div>

<div class="card">
	<div class="card-header">
		<h6 class="card-title m-0 pull-left">Payments</h6>
		<div class="pull-right">
			<button id="toggle-uploader" class="btn btn-outline-primary btn-sm">Install Payment Gateway</button>
		</div>
	</div>
	<div class="card-body p-0">
		<div class="table-responsive">
			<table class="table-striped table">
				<thead>
					<tr>
						<th>Payment Method</th>
						<th width="150px"></th>
						<th width="100px">Status</th>
						<th width="260px" class="text-right">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php if(count($payment_methods) == 0){ ?>
						<tr>
							<td class="text-center" colspan="100%">No Payment Methods Available</td>
						</tr>
					<?php } ?>
					<?php foreach ($payment_methods as $key => $payment) { ?>
						<tr>
							<td><?= $payment['title'] ?></td>
							<td><img style="width: 70px;" src="<?= base_url($payment['icon']) ?>"></td>
							<td><?= $payment['status'] == '1' ? 'Enabled' : 'Disabled' ?></td>
							<td class="text-right">
								<?php if($payment['is_install'] == '1'){ ?>
									<a href="<?= base_url('admincontrol/withdrawal_payment_gateways_edit/'. $payment['code']) ?>" class="btn btn-sm btn-info">Edit</a>
								<?php } ?>
								<a onclick="return confirm('Are you sure?')" href="<?= base_url('admincontrol/withdrawal_payment_gateways_status_change/'. $payment['code']) ?>" class="btn btn-sm btn-<?= $payment['is_install'] == "1" ? "danger" : "success" ?>"><?= $payment['is_install'] == "1" ? "Un-Install" : "Install" ?></a>

								<a onclick="return confirm('Are you sure?')" href="<?= base_url('payment/delete_plugin/'.$payment['code']) ?>" class="btn btn-sm btn-danger">Delete</a>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
	$("#toggle-uploader").on("click",function(){
		$(".plugin-uploader").slideToggle();
	})

	$("#plugin-file").on("change",function(){
		if($(this).val() == ''){
			$("#plugin-file-button").prop("disabled",1)
		} else{
			$("#plugin-file-button").prop("disabled",0)
		}
	})

	$("#plugin-file-button").on("click", function(evt){
		evt.preventDefault();
        $btn = $(this);

        $(".plugin-uploader .warning").addClass('d-none');

        var formData = new FormData();
        formData.append("plugin", $("#plugin-file")[0].files[0]);
       	$btn.btn("loading");
        
        $.ajax({
            url:'<?= base_url('payment/installPayementGateway') ?>',
            type:'POST',
            dataType:'json',
            cache:false,
            contentType: false,
            processData: false,
            data:formData,
            error:function(){ $btn.btn("reset"); },
            success:function(result){            	
            	$btn.btn("reset");
                
                if(result['location']){
                    window.location.reload();
                }
                if(result['warning']){
                    $(".plugin-uploader .warning").html(result['warning']);
                    $(".plugin-uploader .warning").removeClass('d-none');
                }
            },
        });
	})
</script>