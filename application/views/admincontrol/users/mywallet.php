<?php
	$db =& get_instance();
		$userdetails=$db->userdetails();
?>
<div class="row">
	<div class="col-lg-3 col-md-6">
		<div class="card m-b-30">
			<div class="card-body">
				<a href="">
					<div class="d-flex flex-row">
						<div class="col-3 align-self-center">
							<div class="round ">
								<i class="mdi mdi-wallet"></i>
							</div>
						</div>
						<div class="col-9 align-self-center text-center">
							<div class="m-l-10 ">
								<h6 class="mt-0 round-inner">
								<?php echo $totals['all_clicks'] ?> /
								<?php echo c_format($totals['all_clicks_comm']) ?></h6>
								<p class="mb-0 text-muted"><?= __('admin.total_click_commition') ?></p>
							</div>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="card m-b-30">
			<div class="card-body">
				<div class="d-flex flex-row">
					<div class="col-3 align-self-center">
						<div class="round">
							<i class="mdi mdi-wallet"></i>
						</div>
					</div>
					<div class="col-9 text-center align-self-center">
						<div class="m-l-10 ">
							<h6 class="mt-0 round-inner">
							<?php echo $totals['total_sale_count']; ?>/
							<?php echo c_format($totals['all_sale_comm']); ?>
							</h6>
							<p class="mb-0 text-muted"><?= __('admin.sale_ommition') ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="card m-b-30">
			<div class="card-body">
				<div class="d-flex flex-row">
					<div class="col-3 align-self-center">
						<div class="round">
							<i class="mdi mdi-wallet"></i>
						</div>
					</div>
					<div class="col-9 text-center align-self-center">
						<div class="m-l-10 ">
							<h6 class="mt-0 round-inner">
							<?php echo c_format($totals['wallet_accept_amount']); ?>/
							<?php echo c_format($totals['wallet_request_sent_amount']); ?>/
							<?php echo c_format($totals['wallet_unpaid_amount']); ?>
							</h6>
							<p class="mb-0 text-muted"><?= __('admin.paid_request_unpaid') ?> </p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="card m-b-30">
			<div class="card-body">
				<div class="d-flex flex-row">
					<div class="col-3 align-self-center">
						<div class="round">
							<i class="mdi mdi-wallet"></i>
						</div>
					</div>
					<div class="col-9 text-center align-self-center">
						<div class="m-l-10 ">
							<h6 class="mt-0 round-inner"><?= (int)$totals['integration']['action_count'] ?> / <?= c_format($totals['integration']['action_amount']) ?></h6>
							<p class="mb-0 text-muted">Total Action/ Amount</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</br>
</br>
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-header">
				<form method="GET">
					<div class="row">
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label">User</label>
								<select class="form-control" name="user_id">
									<option value="">Filter By User</option>
									<?php foreach ($users as $key => $value) { ?>
									<option <?= isset($user_id) && $user_id == $value['id'] ? 'selected' : '' ?> value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label">Date</label>
								<input autocomplete="off" type="text" name="date" value="<?= isset($_GET['date']) ? $_GET['date'] : '' ?>" class="form-control daterange-picker">
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label">Status</label>
								<select name="status" class="form-control">
									<option value="">All</option>
									<option value="0" <?= isset($_GET['status']) && $_GET['status'] == '0' ? 'selected' : '' ?>>On Hold</option>
									<option value="1" <?= isset($_GET['status']) && $_GET['status'] == '1' ? 'selected' : '' ?>>In Wallet</option>
									<option value="2" <?= isset($_GET['status']) && $_GET['status'] == '2' ? 'selected' : '' ?>>Request Send</option>
									<option value="3" <?= isset($_GET['status']) && $_GET['status'] == '3' ? 'selected' : '' ?>>Accept</option>
									<option value="4" <?= isset($_GET['status']) && $_GET['status'] == '4' ? 'selected' : '' ?>>Reject</option>
								</select>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label"><?= __('admin.recurring_transaction') ?></label>
								<select name="recurring" class="form-control">
									<option value=""><?= __('admin.all') ?></option>
									<option value="0" <?= isset($_GET['status']) && $_GET['recurring'] == '0' ? 'selected' : '' ?>><?= __('admin.not_recurring') ?></option>
									<option value="1" <?= isset($_GET['status']) && $_GET['recurring'] == '1' ? 'selected' : '' ?>><?= __('admin.recurring') ?></option>
								</select>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label">Type</label>
								<select name="type" class="form-control">
									<option value="">All</option>
									<option value="actions" <?= isset($_GET['type']) && $_GET['type'] == 'actions' ? 'selected' : '' ?>>Actions</option>
									<option value="clicks" <?= isset($_GET['type']) && $_GET['type'] == 'clicks' ? 'selected' : '' ?>>Clicks</option>
									<option value="sale" <?= isset($_GET['type']) && $_GET['type'] == 'sale' ? 'selected' : '' ?>>Sale</option>
									<option value="external_integration" <?= isset($_GET['type']) && $_GET['type'] == 'external_integration' ? 'selected' : '' ?>>External Integration</option>
								</select>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label d-block">&nbsp;</label>
								<button class="btn btn-primary">Filter</button>
								<button class="btn btn-danger delete-multiple" type="button">Delete Selected <span class="selected-count"></span></button>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="card-body p-0">
				
				<div class="text-center">
					<?php if ($transaction ==null) {?>
					<img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:100px;">
					<h3 class="m-t-40 text-center"><?= __('admin.no_transactions') ?></h3>
					<?php }
					else { ?>
					<div class="table-responsive">
						<table class="table table-striped table-sortable wallet-table">
							<thead>
								<tr>
									<th>ID</th>
									<th>USER</th>
									<th></th>
									<!-- <th>IP</th> -->
									<th></th>
									<th>ORDER</th>
									<th width="200px">COMMISSION</th>
									<th>PAID STATUS</th>
									<th>METHOD</th>
									<th>DATE</th>
									
									<th>TYPE</th>
									<th>STATUS</th>
									<th>WALLET</th>
									<th>ACTIONS</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($transaction as $key => $value) { ?>
								<tr class="wallet-id-<?= $value['id'] ?> main-tr <?= $recurring ? 'recurring recurringof-'.$recurring : '' ?> ">
									<td class="p-3 mb-2 text-white bg-secondary td-checkbox">
										<label>
											<input type="checkbox" class="wallet-checkbox" value="<?= $value['id'] ?>">
											<?= $value['id'] ?>
										</label>
									</td>
									<td><?php echo $value['username']; ?></td>
									<td><?= wallet_whos_commission($value) ?></td>
									<td><?= wallet_ex_type($value) ?></td>
									<td>
										<?php if($value['integration_orders_total']){ ?>
											<?= c_format($value['integration_orders_total']) ?>
										<?php } ?>
										<?php if($value['local_orders_total']){ ?>
											<?= c_format($value['local_orders_total']) ?>
										<?php } ?>
									</td>
									<td >
										<div class="dpopver-content d-none">
											<?php
												list($message,$ip_details) = parseMessage($value['comment'],$value,'admincontrol',true);
												echo "<div>". $message ."</div>";
											?>
										</div>
										<div 
											class="wallet-popover badge badge-<?= is_need_to_pay($value) ? 'danger' : 'secondary' ?> py-1 pl-2 font-14" 
											toggle="popover"
										> 
											<?= c_format($value['amount']) ?> 
										</div>
										<span toggle="popover" class="wallet-popover btn btn-icon btn-sm fa fa-info" style="font: normal normal normal 14px/1 FontAwesome;"></span>
									</td>
									<td><?php echo wallet_paid_status($value['status']);?></td>
									<td><?= payment_method($value['payment_method']) ?></td>
									
									<td><?= dateFormat($value['created_at']) ?></td>
									<td><?= wallet_type($value) ?></td>
									<td class="text-center">
										<?= $status_icon[$value['status']] ?>
									</td>
									
									<!--ON HOLD / IN WALLET-->
									<td>
										<?php if(($value['status'] == 0 || $value['status'] == 1) && $value['comm_from'] == 'ex' && !in_array($value['type'], ['external_click_comm_pay','external_click_commission','click_commission','external_click_comm_admin'])){ ?>
										<div class="wallet-status-switch">
											<div class="radio radio-inline">
												<label><input type="radio" checked="" class="status-change-rdo" name="status_<?= $value['id'] ?>" data-id='<?= $value['id'] ?>' value="0" ><span>On Hold</span></label>
											</div>
											<div class="radio radio-inline loading">
												<img src="<?=  base_url('assets/images/switch-loading.svg') ?>">
											</div>
											<div class="radio radio-inline">
												<label><input type="radio" <?= $value['status'] == 1 ? 'checked' : '' ?> class="status-change-rdo" name="status_<?= $value['id'] ?>" data-id='<?= $value['id'] ?>' value="1" ><span>In Wallet</span></label>
											</div>
										</div>
										<?php } ?>
									</td> 
									<!--ON HOLD / IN WALLET-->
									
									<td class="text-right">
										
										<button class="btn btn-sm btn-danger remove-tran" data-id="<?= $value['id'] ?>">
										<i class="fa fa-trash"></i>
										</button>
										
										
										<?php if(!$value['parent_id']){  ?>
										
										<button data-toggle="tooltip" title="Set Recurring Transition" class="btn btn-sm <?= $value['wallet_recursion_status'] ? 'btn-danger' : 'btn-default' ?> recursion-tran" data-id="<?= $value['id'] ?>"><i class="mdi mdi-wallet-travel"></i></button>
										<?php } ?>
										
										<?php if($value['wallet_recursion_id']){ ?>
										<button type="button" class="btn btn-sm btn-warning" title="<?= cycle_details($value['total_recurring'], $value['wallet_recursion_next_transaction'], $value['wallet_recursion_endtime'], $value['total_recurring_amount']) ?>" data-toggle="tooltip" data-id="<?= $value['id'] ?>">
										<i class="fa fa-cog fa-spin fa-1x fa-fw"></i>
										</button>
										<?php if((int)$value['total_recurring']){ ?>
										<button data-toggle="tooltip" title="Show Recurring Transition" class="btn btn-sm btn-primary show-recurring-transition" data-id="<?= $value['id'] ?>"><i class="mdi mdi-plus"></i>
										</button>
										<?php } ?>
										<?php } ?>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						</div>
						<?php if(isset($pagination_link)) { ?>
						<div class="pagination_div">
							<?php echo $pagination_link; ?>
						</div>
						<?php } ?>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade" id="modal-confirm">
		<div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div></div></div>
	</div>
	<div class="modal fade" id="modal-confirmstatus">
		<div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div></div></div>
	</div>
	<div class="modal fade" id="modal-recursion">
		<div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div></div></div>
	</div>
	<script src="<?= base_url('assets/plugins/datatable') ?>/moment.js"></script>
	<script type="text/javascript" src="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.css" />
	<script type="text/javascript">
		$('.selectall-wallet-checkbox').on('change',function(){
			$(".wallet-checkbox").prop("checked", $(this).prop("checked")).trigger("change");
		});
		$(".wallet-checkbox").on('change',function(){
			if($(".wallet-checkbox:checked").length == 0){
				$(".delete-multiple").hide();
			} else {
				$(".delete-multiple").show();
				$(".selected-count").text($(".wallet-checkbox:checked").length);
			}
		})
		$(".delete-multiple").on('click',function(){
			var ids = $(".wallet-checkbox:checked").map(function(){ return $(this).val() }).toArray().join(",");
			$this = $(this);
			$.ajax({
	url: '<?php echo base_url("admincontrol/info_remove_tran_multiple") ?>',
	type:'POST',
	dataType:'json',
	data:{ids:ids},
	beforeSend:function(){ $this.button("loading"); },
	complete:function(){ $this.button("reset"); },
	success:function(json){
	$("#modal-confirm .modal-body").html(json['html']);
	$("#modal-confirm").modal("show");
	},
	})
	})
	$("#modal-confirm .modal-body").delegate("[delete-mmultiple-confirm]","click",function(){
	$this = $(this);
	$.ajax({
	url: '<?php echo base_url("admincontrol/confirm_remove_tran_multi") ?>',
	type:'POST',
	dataType:'json',
	data:{id:$this.attr("delete-mmultiple-confirm")},
	beforeSend:function(){ $this.button("loading"); },
	complete:function(){ $this.button("reset"); },
	success:function(json){
	window.location.reload();
	},
	})
	})
	$('.daterange-picker').daterangepicker({
	opens: 'left',
	autoUpdateInput: false,
	ranges: {
	'Today': [moment(), moment()],
	'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
	'Last 7 Days': [moment().subtract(6, 'days'), moment()],
	'Last 30 Days': [moment().subtract(29, 'days'), moment()],
	'This Month': [moment().startOf('month'), moment().endOf('month')],
	'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
	},
	locale: {
	cancelLabel: 'Clear',
	format: 'DD-M-YYYY'
	}
	});
	$('.daterange-picker').on('apply.daterangepicker', function(ev, picker) {
	$(this).val(picker.startDate.format('DD-M-YYYY') + ' - ' + picker.endDate.format('DD-M-YYYY'));
	});
	$('.daterange-picker').on('cancel.daterangepicker', function(ev, picker) {
	$(this).val('');
	});
	$(document).delegate(".status-change-rdo",'change',function(){
	$this = $(this);
	var id = $this.attr("data-id");
	var val = $this.val();
	$loading = $this.parents(".wallet-status-switch").find(".loading");
	
	$.ajax({
	url: '<?php echo base_url("admincontrol/wallet_change_status") ?>',
	type:'POST',
	dataType:'json',
	data:{id:id,val:val},
	beforeSend:function(){$loading.show();},
	complete:function(){$loading.hide();},
	success:function(json){
	if(json['ask_confirm']){
	$("#modal-confirmstatus .modal-body").html(json['html']);
	$("#modal-confirmstatus").modal({
	backdrop: 'static',
	keyboard: false
	});
	}
	if(json['success']){
	window.location.reload();
	}
	},
	})
	});
	$("#modal-confirmstatus").delegate(".close-modal","click",function(){
	var id = $(this).attr("data-id");
	$(".status-change-rdo[name=status_"+ id +"]:not(:checked)").prop("checked",1)
	$("#modal-confirmstatus").modal("hide");
	})
	$(document).delegate(".remove-tran",'click',function(){
	$this = $(this);
	$.ajax({
	url: '<?php echo base_url("admincontrol/info_remove_tran") ?>',
	type:'POST',
	dataType:'json',
	data:{id:$this.attr("data-id")},
	beforeSend:function(){ $this.button("loading"); },
	complete:function(){ $this.button("reset"); },
	success:function(json){
	$("#modal-confirm .modal-body").html(json['html']);
	$("#modal-confirm").modal("show");
	},
	})
	});
	$("#modal-confirm .modal-body").delegate("[delete-tran-confirm]","click",function(){
	$this = $(this);
	$.ajax({
	url: '<?php echo base_url("admincontrol/confirm_remove_tran") ?>',
	type:'POST',
	dataType:'json',
	data:{id:$this.attr("delete-tran-confirm")},
	beforeSend:function(){ $this.button("loading"); },
	complete:function(){ $this.button("reset"); },
	success:function(json){
	window.location.reload();
	},
	})
	});
	$(".recursion-tran").on('click',function(){
	$this = $(this);
	$.ajax({
	url: '<?php echo base_url("admincontrol/info_recursion_tran") ?>',
	type:'POST',
	dataType:'json',
	data:{id:$this.attr("data-id")},
	beforeSend:function(){ $this.button("loading"); },
	complete:function(){ $this.button("reset"); },
	success:function(json){
	$("#modal-recursion .modal-body").html(json['html']);
	$("#modal-recursion").modal("show");
	if( json['recursion_type'] == 'custom_time' ){
	$('.custom_time').show();
	}else{
	$('.custom_time').hide();
	}
	},
	})
	});
	
	$('[name="user_id"]').select2();
	$(".show-recurring-transition").on("click",function(){
	$this = $(this);
	var id = $this.attr("data-id");
	$this.find("i").toggleClass("mdi-plus mdi-minus")
	$nextAll = $this.parents("tr").nextAll("tr.recurringof-"+id);
	if($nextAll.length){
	if($nextAll.eq(0).css("display") == 'table-row'){
	$nextAll.hide();
	} else {
	$nextAll.show();
	}
	return false;
	}
	$this.parents("tr").nextAll("tr.recurringof-"+id).remove();
	$.ajax({
	url:'<?= base_url('admincontrol/getRecurringTransaction') ?>',
	type:'POST',
	dataType:'json',
	data:{
	id:id,
	},
	beforeSend:function(){
	$this.btn("loading");
	},
	complete:function(){
	$this.btn("reset");
	},
	success:function(json){
	if(json['table']){
	$this.parents("tr").after(json['table'])
	}
	},
	})
	})

	$('.wallet-popover').on('click', function(){
		var html = $(this).parents("tr").find(".dpopver-content").html();
        $(this).attr('data-content',html);
	    if($('.popover').hasClass('show')){
	        $('.popover').remove()
	    } else {
	        $(this).popover('show');
	    }
	});

	$('html').on('click', function(e) {
	  if (typeof $(e.target).data('original-title') == 'undefined' &&
	     !$(e.target).parents().is('.popover.in')) {
	    $('[data-original-title]').popover('hide');
	  }
	});

	$(document).ready(function(){
		$(".wallet-popover").popover({
	        placement : 'right',
		    html : true,
	    });
	})
	</script>