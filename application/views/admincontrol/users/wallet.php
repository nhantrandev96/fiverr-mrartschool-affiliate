<?php
	$db =& get_instance();
	$userdetails=$db->userdetails();
?>
<div class="row">
	<div class="col-xl-3">
        <div class="card m-b-20">
            <div class="card-header p-1">
                <span class="d-none bg-success m-0 mini-stat-icon pull-left"><i class="fa fa-bell"></i></span>
                <h2 class="header-title m-0 text-center text-uppercase">Admin Balance</h2>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <ul class="list-inline row mb-0 clearfix">
                        <li class="col-12">
                            <p class="m-b-5 font-18 font-500 counter text-primary"><?= c_format($admin_totals['admin_balance']) ?></p>
                            <p class="mb-0 text-muted">Total Admin Balance</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card m-b-20">
            <div class="card-header p-1">
                <span class="d-none bg-success m-0 mini-stat-icon pull-left"><i class="fa fa-bell"></i></span>
                <h2 class="header-title m-0 text-center text-uppercase">Total Sales</h2>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <ul class="list-inline row mb-0 clearfix">
                        <li class="col-6">
                            <p class="m-b-5 font-18 font-500 counter text-primary"><?= c_format($admin_totals['sale_localstore_total'] + $admin_totals['order_external_total']) ?></p>
                            <p class="mb-0 text-muted">Admin Store</p>
                        </li>
                        <li class="col-6">
                            <p class="m-b-5 font-18 font-500 counter text-primary"><?= c_format($admin_totals['sale_localstore_vendor_total']) ?></p>
                            <p class="mb-0 text-muted">Vendor Store</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card m-b-20">
            <div class="card-header p-1">
                <span class="d-none bg-success m-0 mini-stat-icon pull-left"><i class="fa fa-bell"></i></span>
                <h2 class="header-title m-0 text-center text-uppercase">Actions</h2>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <ul class="list-inline row mb-0 clearfix">
                        <li class="col-12">
                            <p class="m-b-5 font-18 font-500 counter text-primary"><?= (int)$admin_totals['click_action_total'] ?> / <?= c_format($admin_totals['click_action_commission']) ?></p>
                            <p class="mb-0 text-muted">Admin Actions</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card m-b-20">
            <div class="card-header p-1">
                <span class="d-none bg-success m-0 mini-stat-icon pull-left"><i class="fa fa-bell"></i></span>
                <h2 class="header-title m-0 text-center text-uppercase">Clicks</h2>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <ul class="list-inline row mb-0 clearfix">
                        <li class="col-12">
                            <p class="m-b-5 font-18 font-500 counter text-primary">
                                <?= (int)(
                                    $admin_totals['click_localstore_total'] +
                                    $admin_totals['click_integration_total'] +
                                    $admin_totals['click_form_total'] 
                                ) ?> /
                                <?= c_format(
                                    $admin_totals['click_localstore_commission'] +
                                    $admin_totals['click_integration_commission'] +
                                    $admin_totals['click_form_commission']
                                ) ?>
                            </p>
                            <p class="mb-0 text-muted">All Clicks</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="card border-0">
			<div class="card-header border-top border-left border-right">
				<div class="clearfix">
					<div class="pull-left">
						<h5 class="card-title m-0">Filter Transactions</h5>
					</div>
					<div class="pull-right">
						<button class="filter-toggle btn btn-icon btn-sm btn-default"><i class="fa fa-filter"></i></button>
						<button class="btn btn-danger btn-sm delete-multiple" type="button">Delete Selected <span class="selected-count"></span></button>	
					</div>
				</div>
				<form method="GET" class="wallet-filter mt-2" style="display: none;">
					<div class="row">
						<div class="col-sm-3">
							<div class="form-group">
								<select class="form-control" name="user_id">
									<option value="">Filter By User</option>
									<?php foreach ($users as $key => $value) { ?>
									<option <?= isset($user_id) && $user_id == $value['id'] ? 'selected' : '' ?> value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
									<?php } ?>
								</select>
							</div>

							<div class="form-group">
								<input autocomplete="off" type="text" name="date" value="<?= isset($_GET['date']) ? $_GET['date'] : '' ?>" class="form-control daterange-picker" placeholder='Filter By Date'>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<select name="status" class="form-control">
									<option value="">Filter By Status</option>
									<option value="0" <?= isset($_GET['status']) && $_GET['status'] == '0' ? 'selected' : '' ?>>On Hold</option>
									<option value="1" <?= isset($_GET['status']) && $_GET['status'] == '1' ? 'selected' : '' ?>>In Wallet</option>
									<option value="2" <?= isset($_GET['status']) && $_GET['status'] == '2' ? 'selected' : '' ?>>Request Send</option>
									<option value="3" <?= isset($_GET['status']) && $_GET['status'] == '3' ? 'selected' : '' ?>>Accept</option>
									<option value="4" <?= isset($_GET['status']) && $_GET['status'] == '4' ? 'selected' : '' ?>>Reject</option>
								</select>
							</div>
						
							<div class="form-group">
								<select name="recurring" class="form-control">
									<option value="">Filter By Recurring transaction</option>
									<option value="0" <?= isset($_GET['status']) && $_GET['recurring'] == '0' ? 'selected' : '' ?>><?= __('admin.not_recurring') ?></option>
									<option value="1" <?= isset($_GET['status']) && $_GET['recurring'] == '1' ? 'selected' : '' ?>><?= __('admin.recurring') ?></option>
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<select name="type" class="form-control">
									<option value="">Filter By Type</option>
									<option value="actions" <?= isset($_GET['type']) && $_GET['type'] == 'actions' ? 'selected' : '' ?>>Actions</option>
									<option value="clicks" <?= isset($_GET['type']) && $_GET['type'] == 'clicks' ? 'selected' : '' ?>>Clicks</option>
									<option value="sale" <?= isset($_GET['type']) && $_GET['type'] == 'sale' ? 'selected' : '' ?>>Sale</option>
									<option value="external_integration" <?= isset($_GET['type']) && $_GET['type'] == 'external_integration' ? 'selected' : '' ?>>External Integration</option>
								</select>
							</div>

							<div class="form-group">
								<select name="paid_status" class="form-control">
									<option value="">Filter By Paid Type</option>
									<option value="paid" <?= isset($_GET['paid_status']) && $_GET['paid_status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
									<option value="unpaid" <?= isset($_GET['paid_status']) && $_GET['paid_status'] == 'unpaid' ? 'selected' : '' ?>>UnPaid</option>
								</select>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<button class="btn btn-primary">Filter</button>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="card-body p-0">
				
				<div class="text-center1">
					<?php if ($transaction ==null) {?>
						<center>
							<img class="img-responsive mt-5" src="<?php echo base_url('assets/vertical/assets/images/no-data-2.png'); ?>">
							<h3 class="m-t-40 text-center mb-5"><?= __('admin.no_transactions') ?></h3>
						</center>
					<?php } else { ?>
						<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/wallet.css?v='. time()) ?>">
						<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/flag/css/main.min.css?v='. time()) ?>">

						<div class="table-responsive">
							<table class="table transaction-table">
								<thead>
									<tr>
										<th width="50px"></th>
										<th width="30px"></th>
										<th>DATE</th>
										<th>USER</th>
										<th>ORDER</th>
										<th width="110px">COMMISSION</th>
										<th>TYPE</th>
										<th>STATUS</th>
										<th></th>
										<th class="text-center">ACTIONS</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$group_changed = 1;
										$html = '';
										$lastRow = count($transaction)-1;
										foreach ($transaction as $key => $value) { 
											$class = '';
							     			if($current_group_id && $current_group_id == $value['group_id']){
							     				$class = 'child';
							     			} else{
							     				$current_group_id = $value['group_id'];
							     				$group_changed =1;
							     			}

							     			$data = [];
							     			$data['value'] = $value;
							     			$data['class'] = $class;
							     			$data['wallet_status'] = $status;
							     			$data['has_child'] = (isset($transaction[$key+1]) && $transaction[$key+1]['group_id'] &&  $transaction[$key+1]['group_id'] == $value['group_id']) ? 1  : 0;

							     			$html .= $this->Product_model->getHtml('admincontrol/users/part/new_wallet_tr', $data);

							     			if($group_changed || $lastRow == $key){
							     				echo $html;
							     				$html = '';
							     				$group_changed = 0;
							     			}
										} 
									?>
								</tbody>
							</table>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
	
<div class="modal fade" id="modal-confirm">
	<div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-body"></div></div></div>
</div>
<div class="modal fade" id="modal-confirmstatus">
	<div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-body"></div></div></div>
</div>
<div class="modal fade" id="modal-recursion">
	<div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-body"></div></div></div>
</div>

<script src="<?= base_url('assets/plugins/datatable') ?>/moment.js"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.css" />

<script type="text/javascript">
	$(document).delegate(".show-child-transaction","click",function(){
		$tr = $(this).parents("tr");
		var status = $(this).find("i").hasClass('fa-angle-down') ? 1 : 0;
		var group_id = $tr.attr("group_id");
		
		if(status){
			$('.transaction-table .child-row[group_id='+ group_id +']').show();
			$(this).find("i").removeClass('fa-angle-down');
			$(this).find("i").addClass('fa-angle-up');
			$tr.addClass('opened')
			$('.transaction-table [group_id='+ group_id +']').addClass('highlight');
		} else{
			$('.transaction-table .child-row[group_id='+ group_id +']').hide();
			$(this).find("i").removeClass('fa-angle-up');
			$(this).find("i").addClass('fa-angle-down');
			$tr.removeClass('opened')
			$('.transaction-table [group_id='+ group_id +']').removeClass('highlight');
		}

		$('.transaction-table .child-row[group_id='+ group_id +']:last').addClass("last-group-row");
	})

	$(".filter-toggle").on("click", function(){
		$(".wallet-filter").slideToggle('fast');
	})

	$(document).delegate('.selectall-wallet-checkbox','change',function(){
		$(".wallet-checkbox").prop("checked", $(this).prop("checked")).trigger("change");
	});

	$(document).delegate(".wallet-checkbox",'change',function(){
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

	$(document).delegate(".recursion-tran",'click',function(){
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

	$(document).delegate(".show-recurring-transition","click",function(){
		$this = $(this);
		var id = $this.attr("data-id");
		$this.find("i").toggleClass("mdi-plus mdi-minus")
		$nextAll = $this.parents("tr").nextAll("tr.recurringof-"+id);

		$this.parents("tr").nextAll("tr.recurringof-"+id+":last").addClass('last-recurring');

		if($nextAll.length){
			if($nextAll.eq(0).css("display") == 'table-row'){
				$this.parents("tr").removeClass('opened-recurring');
				$nextAll.hide();
			} else {
				$this.parents("tr").addClass('opened-recurring');
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
				newtr:1,
				ischild:$this.parents("tr").hasClass("child-row")
			},
			beforeSend:function(){$this.btn("loading");},
			complete:function(){$this.btn("reset");},
			success:function(json){
				if(json['table']){
					$this.parents("tr").addClass('opened-recurring');
					$this.parents("tr").after(json['table']);
					$this.parents("tr").nextAll("tr.recurringof-"+id+":last").addClass('last-recurring');

					$(".wallet-popover").popover({
						placement : 'right',
						html : true,
					});
				}
			},
		})
	})

	$(document).delegate(".wallet-popover","click",function(){
		$tr= $(this).parents("tr");
		var html = $tr.find(".dpopver-content").html();
		$tr.find('.wallet-popover').attr('data-content',html);

		if($('.popover').hasClass('show')){
			$('.popover').remove()
		} else {
			$(this).popover('show');
		}
	})

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