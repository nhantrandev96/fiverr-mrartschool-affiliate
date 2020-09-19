<div class="row mb-5">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header" id="filter-form">
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


<script type="text/javascript" src="<?= base_url('assets/plugins/datatable') ?>/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/jquery.dataTables.css?v=<?= av() ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/dataTables.bootstrap.min.css?v=<?= av() ?>">

<script src="<?= base_url('assets/plugins/datatable') ?>/moment.js"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.css" />

<script type="text/javascript">
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