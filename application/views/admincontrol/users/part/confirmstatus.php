<h6 class="text-center"> <?= __('admin.recurring_amount') ?> : <?= c_format($tran->total_recurring_amount) ?> </h6>
<hr>
<p class="text-center"> <?= __('admin.transaction_id') ?> : <?= $tran->id ?> </p>
<p class="text-center"> <?= __('admin.change_status_to_all_recurring_transaction') ?></p>

<hr><br>
<div class="row">
	<div class="col-sm-4"><button data-id='<?= $tran->id ?>' class="btn close-modal btn-default btn-block"><?= __('admin.cancel') ?></button></div> 
	<div class="col-sm-4"><button class="btn btn-primary btn-block" data-type='changeallnone' status-tran-confirm="<?= $tran->id ?>"><?= __('admin.no') ?></button></div> 
	<div class="col-sm-4"><button class="btn btn-danger  btn-block" data-type='changeall' status-tran-confirm="<?= $tran->id ?>"><?= __('admin.yes_change') ?></button></div> 
</div>

<script type="text/javascript">
	$("[status-tran-confirm]").click(function(){
		$this = $(this);
		$.ajax({
			url: '<?php echo base_url("admincontrol/wallet_change_status") ?>',
			type:'POST',
			dataType:'json',
			data:{
				confirm:$this.attr("data-type"),
				id:'<?= $tran->id ?>',
				val:'<?= $status ?>',
			},
			beforeSend:function(){$this.button('loading');},
			complete:function(){$this.button('reset');},
			success:function(json){
				if(json['success']){
					window.location.reload();
				}
			},
		})
	})
</script>