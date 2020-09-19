<div class="row">
	<div class="col-12">
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="tab" href="#tab-menu_statistics"><?= __('user.menu_statistics') ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#tab-menu_report_all_transactions"><?= __('user.menu_report_all_transactions') ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#tab-menu_report_statistics"><?= __('user.menu_report_statistics') ?></a>
			</li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane active" id="tab-menu_statistics">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title"><?= __('user.menu_statistics') ?></h4>
					</div>
					<div class="card-body">
						<div class="row mb-5">
							<div class="col-sm-4 mb-5">
								<div class="card">
									<div class="card-body">
										<h4 class="text-center"><span class="pull-left"> <?= (int)$statistics['clicks_count'] ?></span> <?= __('user.click_by_country') ?></h4>
										<?php if((int)$statistics['clicks_count'] > 0){ ?>
											<ul class="list-unstyled list-inline text-center">
							                    <?php $i = 0; foreach($statistics['clicks'] as $country => $counts){ ?>
							                        <li class="list-inline-item">
							                            <p><i class="mdi mdi-checkbox-blank-circle <?php echo 'color-'.$i++ % 5 ; ?> mr-2"></i><?php echo $country; ?></p>
							                        </li>
							                    <?php } ?>
											</ul>
											<div id="clicks-chart" style="height:300px;"></div>
										<?php } else { ?>
											<div class="empty-graph">
												NO ACTIVITY
											</div>
										<?php } ?>
									</div>
								</div>
							</div>

							<div class="col-sm-4 mb-5">
								<div class="card">
									<div class="card-body">
										<h4 class="text-center"><span class="pull-left"> <?= (int)$statistics['action_clicks_count'] ?></span> Action Click by Country</h4>
										<?php if((int)$statistics['action_clicks_count'] > 0){ ?>
											<ul class="list-unstyled list-inline text-center">
							                    <?php $i = 0; foreach($statistics['action_clicks'] as $country => $counts){ ?>
							                        <li class="list-inline-item">
							                            <p><i class="mdi mdi-checkbox-blank-circle <?php echo 'color-'.$i++ % 5 ; ?> mr-2"></i><?php echo $country; ?></p>
							                        </li>
							                    <?php } ?>
											</ul>
											<div id="action_click-chart" style="height:300px;"></div>
										<?php } else { ?>
											<div class="empty-graph">
												NO ACTIVITY
											</div>
										<?php } ?>
									</div>
								</div>
							</div>

							<div class="col-sm-4 mb-5">
								<div class="card">
									<div class="card-body">
										<h4 class="text-center"><span class="pull-left"> <?= (int)$statistics['sale_count'] ?></span> <?= __('user.sale_by_country') ?></h4>
										<?php if((int)$statistics['sale_count'] > 0){ ?>
											<ul class="list-unstyled list-inline text-center">
							                    <?php $i = 0; foreach($statistics['sale'] as $country => $counts){ ?>
							                        <li class="list-inline-item">
							                            <p><i class="mdi mdi-checkbox-blank-circle <?php echo 'color-'.$i++ % 5 ; ?> mr-2"></i><?php echo $country; ?></p>
							                        </li>
							                    <?php } ?>
											</ul>
											<div id="sale-chart" style="height:300px;"></div>
										<?php } else { ?>
											<div class="empty-graph">
												NO ACTIVITY
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>

						<div class="row ">
							<?php if($refer_status){ ?>
							<div class="col-sm-6 mb-5">
								<div class="card">
									<div class="card-body">
										<h4 class="text-center"><span class="pull-left"> <?= (int)$statistics['affiliate_user_count'] ?></span> <?= __('user.refered_user_by_country') ?></h4>
										<?php if((int)$statistics['affiliate_user_count'] > 0){ ?>
											<ul class="list-unstyled list-inline text-center">
							                    <?php $i = 0; foreach($statistics['affiliate_user'] as $country => $counts){ ?>
							                        <li class="list-inline-item">
							                            <p><i class="mdi mdi-checkbox-blank-circle <?php echo 'color-'.$i++ % 5 ; ?> mr-2"></i><?php echo $country; ?></p>
							                        </li>
							                    <?php } ?>
											</ul>
											<div id="affiliate_user-chart" style="height:300px;"></div>
										<?php } else { ?>
											<div class="empty-graph">
												NO ACTIVITY
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<?php } ?>

							<div class="col-sm-6 mb-5">
								<div class="card">
									<div class="card-body">
										<h4 class="text-center"><span class="pull-left"> <?= (int)$statistics['client_user_count'] ?></span> <?= __('user.client_by_country') ?></h4>
										<?php if((int)$statistics['client_user_count'] > 0){ ?>
											<ul class="list-unstyled list-inline text-center">
							                    <?php $i = 0; foreach($statistics['client_user'] as $country => $counts){ ?>
							                        <li class="list-inline-item">
							                            <p><i class="mdi mdi-checkbox-blank-circle <?php echo 'color-'.$i++ % 5 ; ?> mr-2"></i><?php echo $country; ?></p>
							                        </li>
							                    <?php } ?>
											</ul>
											<div id="client_user-chart" style="height:300px;"></div>
										<?php } else { ?>
											<div class="empty-graph">
												NO ACTIVITY
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane " id="tab-menu_report_all_transactions">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title"><?= __('user.menu_report_all_transactions') ?></h4>
					</div>
					<div class="card-body">
						<?php if ($transaction ==null) {?>
                            <div class="text-center">
                                <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:25px;">
                                <h3 class="m-t-40 text-center"><?= __('user.no_transactions') ?></h3>
                            </div>
                        <?php } else { ?>
		                    <div class="table-responsive" >
		                        <table class="table  table-striped">
									<thead>
										<tr>
											<th width="50px"></th>
											<th>Order Total</th>
											<th class="sortTr <?= sort_order('wallet.amount') ?>" width="100px"><a href="<?= sortable_link('ReportController/user_transaction','wallet.amount') ?>"><?= __('user.commission') ?></a></th>
											<th width="180px"><?= __('user.payment_method') ?></th>
											<th style="min-width: 250px"><?= __('user.comment') ?></th>
											<th class="sortTr <?= sort_order('wallet.created_at') ?>" width="200px"><a href="<?= sortable_link('ReportController/user_transaction','wallet.created_at') ?>"><?= __('user.date') ?></a></th>
											<th class="sortTr <?= sort_order('wallet.comm_from') ?>" width="150px"><a href="<?= sortable_link('ReportController/user_transaction','wallet.comm_from') ?>"><?= __('user.comm_from') ?></a></th>
											<th class="sortTr <?= sort_order('wallet.type') ?>" width="150px"><a href="<?= sortable_link('ReportController/user_transaction','wallet.type') ?>"><?= __('user.type') ?></a></th>
											<th class="sortTr <?= sort_order('wallet.status') ?>" width="150px"><a href="<?= sortable_link('ReportController/user_transaction','wallet.status') ?>"><?= __('user.status') ?></a></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($transaction as $key => $value) { ?>
										<tr>
											<td><?= $key + 1 ?></td>
											<td>
												<?php if($value['integration_orders_total']){ ?>
													<?= c_format($value['integration_orders_total']) ?>
												<?php } ?>
											</td>
											<td><?= $value['amount'] ?></td>
											<td><?= $value['payment_method'] ?></td>
											<td><?= $value['comment'] ?></td>
											<td><?= $value['created_at'] ?></td>
											<td><?= $value['comm_from'] ?></td>
											<td><?= $value['dis_type'] ?></td>
											<td class="text-center">
												<?= $value['status_icon'] ?>		
												<?= $value['status'] ?>		
											</td>
										</tr>
										<?php } ?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="100%" class="text-right">
												<div class="pagination">
													<?= $pagination ?>
												</div>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
			
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="tab-pane " id="tab-menu_report_statistics">
				<div class="card">
					<div class="card-header">
						<div class="row">
		                    <div class="col-sm-3">
		                        <div class="form-group">
		                            <label class="control-label"><?= __('user.date') ?></label>
		                            <input autocomplete="off" type="text" name="date" value="" class="form-control daterange-picker">
		                        </div>
		                    </div>
		                    <div class="col-sm-2">
		                        <label class="control-label">&nbsp;</label>
		                        <div>
		                            <button class="btn btn-primary" onclick="table.ajax.reload();"> <i class="fa fa-search"></i> <?= __('user.search') ?></button>
		                            <button class="btn btn-primary export-excel" > <i class="fa fa-file-excel-o"></i> <?= __('user.export') ?></button>
		                        </div>
		                    </div>
		                </div>
					</div>
					<div class="card-body p-0">
						<div class="table-responsive">
							<table class="table table-striped table-bordered" id="table-report">
								<thead>
									<tr class="main-tr">
										<th></th>
										<th><?= __('user.affiliate') ?></th>
										
										<th colspan="2" class="text-center two-border"><?= __('user.clicks') ?></th>
										<th colspan="3" class="text-center two-border"><?= __('user.sale') ?></th>
										<th class="text-center two-border"><?= __('user.cpa') ?></th>
										
										<th colspan="2" class="text-center two-border"><?= __('user.total') ?></th>
									</tr>
									<tr class="sub-tr">
										<th>No</th>
										<th><?= __('user.affiliate_name') ?></th>

										<th -width="90px"><?= __('user.count') ?></th>
										<th -width="120px"><?= __('user.commission') ?></th>

										<th -width="90px"><?= __('user.count') ?></th>
										<th -width="90px"><?= __('user.total') ?></th>
										<th -width="120px"><?= __('user.commission') ?></th>
										<th -width="120px"><?= __('user.cpa') ?></th>
										<th -width="90px"><?= __('user.total_income') ?></th>
										<th -width="120px"><?= __('user.total_commission') ?></th>
									</tr>
								</thead>
								<tbody class="tiny-table"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?= base_url('assets/plugins/datatable') ?>/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/jquery.dataTables.css?v=<?= av() ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/dataTables.bootstrap.min.css?v=<?= av() ?>">

<script src="<?= base_url('assets/plugins/datatable') ?>/moment.js"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.css?v=<?= av() ?>" />

<script type="text/javascript">
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		var hash = $(e.target).attr('href');
		localStorage.setItem("report_tab",hash)
		if(hash == '#tab-menu_statistics'){apply_chart()}
	});

	$(document).on('ready',function(){
		var hash = localStorage.getItem("report_tab")
		if (hash) { 
			if(hash == '#tab-menu_statistics'){apply_chart()}
			$('.nav-link[href="' + hash + '"]').tab('show'); 
		} else {
			apply_chart()
		}
	})

	var colorss = ['#40a4f1', '#5b6be8', '#c1c5e2', '#e785da', '#00bcd2'];
	var is_apply = false;

	function apply_chart(){
		if(!is_apply){
			is_apply = true;
			if($("#clicks-chart").length){
				var donutData = [
					<?php $str = '';
						foreach($statistics['clicks'] as $country=>$counts){ $str .= '{label: "' . $country . '", value: ' . $counts . '},'; }
						echo $str;
					?>
				];
				Morris.Donut({
					element: 'clicks-chart',
					data: donutData,
					resize: true,
					colors: colorss,
				});
			}

			if($("#action_click-chart").length){
				var donutData = [
					<?php $str = '';
						foreach($statistics['action_clicks'] as $country=>$counts){ $str .= '{label: "' . $country . '", value: ' . $counts . '},'; }
						echo $str;
					?>
				];
				Morris.Donut({
					element: 'action_click-chart',
					data: donutData,
					resize: true,
					colors: colorss,
				});
			}

			if($("#sale-chart").length){
				var donutData = [
					<?php $str = '';
						foreach($statistics['sale'] as $country=>$counts){ $str .= '{label: "' . $country . '", value: ' . $counts . '},'; }
						echo $str;
					?>
				];
				Morris.Donut({
					element: 'sale-chart',
					data: donutData,
					resize: true,
					colors: colorss,
				});
			}

			if($("#affiliate_user-chart").length){
				var donutData = [
					<?php $str = '';
						foreach($statistics['affiliate_user'] as $country=>$counts){ $str .= '{label: "' . $country . '", value: ' . $counts . '},'; }
						echo $str;
					?>
				];
				Morris.Donut({
					element: 'affiliate_user-chart',
					data: donutData,
					resize: true,
					colors: colorss,
				});
			}

			if($("#client_user-chart").length){
				var donutData = [
					<?php $str = '';
						foreach($statistics['client_user'] as $country=>$counts){ $str .= '{label: "' . $country . '", value: ' . $counts . '},'; }
						echo $str;
					?>
				];
				Morris.Donut({
					element: 'client_user-chart',
					data: donutData,
					resize: true,
					colors: colorss,
				});
			}
		}
	}

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
	var table = $('#table-report').DataTable({
	    dom: 'Bfrtip',
	    ajax:{
	    	url:"<?= base_url('incomereport/get_data') ?>",
	    	data: function ( d ) {
				d.date     = $(".daterange-picker").val();
		  	},
	    	dataType:'json',
	    	type:'post',
	    },
	    buttons: [],
	    bFilter: false, 
        bInfo: false,
        processing: true,
        language: {
            'loadingRecords': '&nbsp;',
            'processing': 'Loading...'
        },
	});

	$(".export-excel").on('click',function(){
    	$this = $(this);
    	$.ajax({
    		url:'<?= base_url('incomereport/get_data') ?>?export=excel&filter=is_admin=1&date=' + $(".daterange-picker").val(),
    		type:'POST',
    		dataType:'json',
    		data: {
	    		date:$(".daterange-picker").val(),
	    	},
    		beforeSend:function(){
    			$this.btn("loading");
    		},
    		complete:function(){
    			$this.btn("reset");
    		},
    		success:function(json){
    			if (json['download']) {
    				window.location.href = json['download'];
    			}
    		},
    	})
    })
</script>