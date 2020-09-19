
<div class="row mb-5">

	<div class="col-sm-12">

		<div class="card">

			<div class="card-header" id="filter-form">

				<div class="row">

					<input type="hidden" name="is_admin" value="1">

                    <div class="col-sm-3">

                        <div class="form-group">

                            <label class="control-label"><?= __('admin.affiliate') ?></label>

                            <select name="user_id" class="form-control user-autocomplete">

                            </select>

                        </div>

                    </div>

                    

                    <div class="col-sm-3">

                        <div class="form-group">

                            <label class="control-label"><?= __('admin.date') ?></label>

                            <input autocomplete="off" type="text" name="date" value="" class="form-control daterange-picker">

                        </div>

                    </div>

                    <div class="col-sm-2">

                        <label class="control-label">&nbsp;</label>

                        <div>

                            <button class="btn btn-primary" onclick="table.ajax.reload();"> <i class="fa fa-search"></i> <?= __('admin.search') ?></button>

                            <button class="btn btn-primary export-excel" > <i class="fa fa-file-excel-o"></i> <?= __('admin.export') ?></button>

                        </div>

                    </div>

                    <div class="col-sm-4 text-right">

                        <label class="control-label">&nbsp;</label>

                        <div>

                        	<label class="control-label"><?= __('admin.statistics_total_affiliate') ?> : <span class="total-affiliate"></span> </label>

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

							<th><?= __('admin.affiliate') ?></th>

							

							<th colspan="2" class="text-center two-border"><?= __('admin.clicks') ?></th>

							<th colspan="3" class="text-center two-border"><?= __('admin.sale') ?></th>

							<th class="text-center two-border"><?= __('admin.cpa') ?></th>

							

							<th colspan="2" class="text-center two-border"><?= __('admin.total') ?></th>

						</tr>

						<tr class="sub-tr">

							<th>No</th>

							<th><?= __('admin.affiliate_name') ?></th>



							<th -width="90px"><?= __('admin.count') ?></th>

							<th -width="120px"><?= __('admin.commission') ?></th>



							<th -width="90px"><?= __('admin.count') ?></th>

							<th -width="90px"><?= __('admin.total') ?></th>

							<th -width="120px"><?= __('admin.commission') ?></th>

							<th -width="120px"><?= __('admin.cpa') ?></th>

							<th -width="90px"><?= __('admin.total_income') ?></th>

							<th -width="120px"><?= __('admin.total_commission') ?></th>

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

<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/jquery.dataTables.css">

<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/dataTables.bootstrap.min.css">



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

	    ajax: function(data,callback){

	    	$.ajax({

		    	url:"<?= base_url('incomereport/get_data') ?>",

		    	data: {

					is_admin : 1,

					date     : $(".daterange-picker").val(),

					user_id  : $("select[name=user_id]").val(),

			  	},

		    	dataType:'json',

		    	type:'post',

		    	complete:function(){

		    		

		    	},

		    	success:function(json){

		    		$(".total-affiliate").text(json.data.length)

		    		callback(json)

		    	},

		    })

	    },

	    buttons: [],

	    bFilter: false, 

        bPaginate : true,

        pagination : true,

        bInfo: false,

        processing: true,

        language: {

            'loadingRecords': '&nbsp;',

            'processing': 'Loading...'

        },

	});



	$(".user-autocomplete").select2({

		ajax: {

			url: '<?= base_url('incomereport/user_search') ?>',

			dataType: 'json',

			data: function(params) {

				return {

					p: params.term,

					page: params.page

				};

			},

			processResults: function(data, params) {

				var data = $.map(data, function(obj) {

					obj.id = obj.id;

					obj.text = obj.name;

					return obj;

				});

				params.page = params.page || 1;

				return {

					results: data,

					pagination: {

						more: (params.page * 30) < data.total_count

					}

				};

			},

			cache: true

		},

		escapeMarkup: function(markup) {

			return markup;

		},

		allowClear: true,

		minimumInputLength:0,

		placeholder: '',

    });



    $(".export-excel").on('click',function(){

    	$this = $(this);

    	$.ajax({

    		url:'<?= base_url('incomereport/get_data') ?>?export=excel&filter=is_admin=1&date=' + $(".daterange-picker").val(),

    		type:'POST',

    		dataType:'json',

    		data: {

	    		is_admin:1,

	    		date:$(".daterange-picker").val(),

	    		user_id: $("select[name=user_id]").val(),

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