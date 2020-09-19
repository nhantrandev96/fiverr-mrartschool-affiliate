<div class="row mb-4">
    <div class="col-xl-3">
        <div class="card border mt-2">
            <div class="card-header">
                <h6 class='card-title text-center text-uppercase text-primary m-0'>Balance</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <ul class="list-inline row mb-0 clearfix">
                        <li class="col-12">
                            <p class="m-b-5 font-18 font-500 counter text-primary set-color"><?= c_format($user_totals['user_balance']) ?></p>
                            <p class="mb-0 text-muted">Balance</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card border mt-2">
            <div class="card-header">
                <h6 class='card-title text-center text-uppercase text-primary m-0'>Total Sales</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <ul class="list-inline row mb-0 clearfix">
                        <li class="col-6">
                            <p class="m-b-5 font-18 font-500 counter text-primary set-color"><?= c_format($user_totals['sale_localstore_total'] + $user_totals['order_external_total']) ?></p>
                            <p class="mb-0 text-muted">Admin Store</p>
                        </li>
                        <li class="col-6 border-left">
                            <p class="m-b-5 font-18 font-500 counter text-primary set-color"><?= c_format($user_totals['vendor_sale_localstore_total'] + $user_totals['vendor_order_external_total']) ?></p>
                            <p class="mb-0 text-muted">Vendor Store</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card border mt-2">
            <div class="card-header">
                <h6 class='card-title text-center text-uppercase text-primary m-0'>Actions</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <ul class="list-inline row mb-0 clearfix">
                        <li class="col-12">
                            <p class="m-b-5 font-18 font-500 counter text-primary">
                            	<span class="set-color"><?= (int)$user_totals['click_action_total'] + (int)$user_totals['vendor_action_external_total'] ?></span> / 
                            	<?= c_format($user_totals['click_action_commission']) ?>
                            		
                        	</p>
                            <p class="mb-0 text-muted">Vendor pay <span class="set-color"><?= c_format($user_totals['vendor_click_external_commission_pay']) ?></span></p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card border mt-2">
            <div class="card-header">
                <h6 class='card-title text-center text-uppercase text-primary m-0'>Clicks</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <ul class="list-inline row mb-0 clearfix">
                        <li class="col-12">
                            <p class="m-b-5 font-18 font-500 counter text-primary">
                                <span class="set-color">
                                    <?= (int)(
                                        $user_totals['click_localstore_total'] +
                                        $user_totals['vendor_click_localstore_total'] +
                                        $user_totals['click_external_total'] +
                                        $user_totals['vendor_click_external_total'] +
                                        $user_totals['click_form_total'] 
                                    ) ?>
                                </span> /
                                <span class="set-color">
                                    <?= c_format(
                                        $user_totals['click_localstore_commission'] +
                                        $user_totals['click_integration_commission'] +
                                        $user_totals['click_external_commission'] +
                                        $user_totals['click_form_commission']
                                    ) ?>
                                </span>
                            </p>
                            <p class="mb-0 text-muted">Vendor pay 
                                <span class="set-color">
                                    <?= c_format(
                                        $user_totals['vendor_click_localstore_commission_pay'] +
                                        $user_totals['vendor_click_external_commission_pay']
                                    ) ?>
                                </span>
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-12">
		<div class="card m-b-20">
			<div class="card">
				<div class="card-header">
					<form method="GET">
						<div class="row">
							<div class="col-sm-2">
								<div class="form-group mb-0">
									<select name="type" class="form-control">
										<option value="">Filter By Type</option>
										<option value="actions" <?= isset($_GET['type']) && $_GET['type'] == 'actions' ? 'selected' : '' ?>>Actions</option>
										<option value="clicks" <?= isset($_GET['type']) && $_GET['type'] == 'clicks' ? 'selected' : '' ?>>Clicks</option>
										<option value="sale" <?= isset($_GET['type']) && $_GET['type'] == 'sale' ? 'selected' : '' ?>>Sale</option>
										<option value="external_integration" <?= isset($_GET['type']) && $_GET['type'] == 'external_integration' ? 'selected' : '' ?>>External Integration</option>
									</select>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="form-group mb-0">
									<select name="paid_status" class="form-control">
										<option value="">Filter By Paid Type</option>
										<option value="paid" <?= isset($_GET['paid_status']) && $_GET['paid_status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
										<option value="unpaid" <?= isset($_GET['paid_status']) && $_GET['paid_status'] == 'unpaid' ? 'selected' : '' ?>>UnPaid</option>
									</select>
								</div>
							</div>
							<div class="col-sm-5">
								<div class="form-group mb-0">
									<button class="btn btn-primary">Filter</button>
									<button type="button" class="btn btn-info withdrawal-all" >Withdrawal All Selected</button>
									<button type="button" class="btn btn-primary withdrawal-unpaid" >Withdrawal All (<?= c_format($wallet_unpaid_amount) ?>)</button>
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
											<th width="50px" class="checkbox-td">
												<label>
													<input type="checkbox" class="selectall">
												</label>
											</th>
											<th width="30px"></th>
											<th>DATE</th>
											<th>USER</th>
											<th>ORDER</th>
											<th width="150px">COMMISSION</th>
											<th>TYPE</th>
											<th>STATUS</th>
											<th></th>
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


											if($value['type'] == 'vendor_sale_commission' && $value['is_vendor'] == 1){

												$tran = $Wallet_model->getTransaction([
													'group_id' => $value['group_id'],
													'id_ne' => $value['id'],
												]);

												if($tran){
													$data['has_child'] =1;
												}

												$html .= $this->Product_model->getHtml('usercontrol/users/parts/new_wallet_tr', $data);

												foreach ($tran as $_value) {
													$data['class'] = 'child';
													$data['value'] = $_value;
													$html .= $this->Product_model->getHtml('usercontrol/users/parts/new_wallet_tr', $data);
												}
											} else{
												$html .= $this->Product_model->getHtml('usercontrol/users/parts/new_wallet_tr', $data);
											}


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
</div>


<!-- <div class="modal fade" id="withdrawal-limit">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body"><?= $site_setting['wallet_min_message'] ?></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
 -->
<div class="modal fade" id="withdrawal-payments">
	<div class="modal-dialog">
		<div class="modal-content">
		</div>
	</div>
</div>

<div class="clearfix"></div><br>
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

	$(".show-recurring-transition").on("click",function(){
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
			url:'<?= base_url('usercontrol/getRecurringTransaction') ?>',
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

	$('.selectall').on('change',function(){
		$('.wallet-checkbox').prop("checked",$(this).prop("checked"));
		if($(".wallet-checkbox:checked").length){
			$('.withdrawal-all').fadeIn();
		} else{
			$('.withdrawal-all').fadeOut();
		}
	});

	$('.wallet-checkbox').on('change',function(){
		if($(".wallet-checkbox:checked").length){
			$('.withdrawal-all').fadeIn();
		} else{
			$('.withdrawal-all').fadeOut();
		}
	});

	$('.withdrawal-all').fadeOut();

	$('.withdrawal-unpaid').on('click',function(){
		withdrawal_payments('all',$(this));
	});

	$('.withdrawal-all').on('click',function(){
		var ids = $(".wallet-checkbox:checked").map(function(){ return $(this).val() }).toArray().join(",");
		withdrawal_payments(ids,$(this));
	});

	function withdrawal_payments(ids,_this) {
		$.ajax({
			url:'<?= base_url("usercontrol/get_withdrawal_modal") ?>',
			type:'POST',
			dataType:'json',
			data:{ids:ids},
			beforeSend:function(){_this.btn("loading");},
			complete:function(){_this.btn("reset");},
			success:function(json){
				$("#withdrawal-payments .modal-content").html(json['html']);
				$("#withdrawal-payments").modal("show");
			},
		})
	}

	$('.send-request').on('click',function(){
		$this = $(this);
		$.ajax({
			type:'POST',
			dataType:'json',
			data:{request_payment: $this.attr("data-id")},
			beforeSend:function(){ $this.btn("loading"); },
			complete:function(){ $this.btn("reset"); },
			success:function(json){
				$this.parents("tr").remove();
			},
		})
	})

	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		var hash = $(e.target).attr('href');
		if (history.pushState) {
		    history.pushState(null, null, hash);
		} else {
		    location.hash = hash;
		}
	});

	$(document).on('ready',function(){
		var hash = window.location.hash;
		if (hash) { $('.nav-link[href="' + hash + '"]').tab('show'); }
	})


	$(document).delegate('.wallet-popover','click', function(){
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

