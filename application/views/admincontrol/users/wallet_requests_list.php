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

<div class="row">
	<div class="col-12">

		<ul class="nav nav-tabs nav-tabs-custom">
		  <li class="nav-item"><a class="active nav-link" data-toggle="tab" href="#new-request">New Requests</a></li>
		  <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#old-request">Old Requests</a></li>
		</ul>

		<div class="tab-content">
		  <div id="new-request" class="tab-pane fade in active show">
			    <div class="card">
					<div class="card-header">
						<form method="GET" onsubmit="return new_filter()" id="new_filter">
							<input type="hidden" name="get_new" value="1">
							<div class="row">
								<div class="col-sm-12 mb-2">
									<h6 class="m-0">Withdraw Requests List</h6>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<select class="form-control" name="user_id">
											<option value="">Filter By User</option>
											<?php foreach ($users as $key => $value) { ?>
												<option <?= isset($user_id) && $user_id == $value['id'] ? 'selected' : '' ?> value="<?= $value['id'] ?>"><?= $value['username'] ?></option>	
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<input autocomplete="off" type="text" name="date" value="<?= isset($_GET['date']) ? $_GET['date'] : '' ?>" class="form-control daterange-picker" placeholder='Filter By Date'>

									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<button class="btn btn-primary btn-new-filter">Filter</button>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="card-body p-0">
						<div class="new-empty text-center d-none">
				    		<img class="img-responsive" src="<?php echo base_url('assets/vertical/assets/images/no-data-2.png'); ?>" style="margin: 49px 0 0 0;">
                            <h3 class="m-b-30 text-center"><?= __('admin.no_wallet_withdraw') ?></h3>
				    	</div>
						<div class="table-responsive new-datatable">
							<table class="table transaction-table table-striped ">
								<thead>
									<tr>
										<th width="100px">Request ID</th>
										<th>USER</th>
										<th>DATE</th>
										<th>Payment Method</th>
										<th>Transactions Ids</th>
										<th>TOTAL</th>
										<th>STATUS</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
		  </div>
		  <div id="old-request" class="tab-pane fade">
		    <div class="card">
				<div class="card-header">
					<form method="GET" onsubmit="return old_filter()" id="old_filter">
						<input type="hidden" name="get_old" value="1">
						<div class="row">
							<div class="col-sm-12 mb-2">
								<h6 class="m-0">Old Withdraw Requests List</h6>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<select class="form-control" name="user_id">
										<option value="">Filter By User</option>
										<?php foreach ($users as $key => $value) { ?>
											<option <?= isset($user_id) && $user_id == $value['id'] ? 'selected' : '' ?> value="<?= $value['id'] ?>"><?= $value['username'] ?></option>	
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<input autocomplete="off" type="text" name="date" value="<?= isset($_GET['date']) ? $_GET['date'] : '' ?>" class="form-control daterange-picker" placeholder='Filter By Date'>

								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<button type="submit" class="btn btn-old-filter btn-primary">Filter</button>

									<a href="<?= base_url('admincontrol/ask_again_withdrawal?backto=wallet_requests_list') ?>" type="button" class="btn btn-success "> Ask Again For Withdrawal </a>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="table-rep-plugin">
				    <div class="text-center">
				    	<div class="old-empty d-none">
				    		<img class="img-responsive" src="<?php echo base_url('assets/vertical/assets/images/no-data-2.png'); ?>" style="margin: 49px 0 0 0;">
                            <h3 class="m-b-30 text-center"><?= __('admin.no_wallet_withdraw') ?></h3>
				    	</div>
	                   	<div class="table-responsive old-datatable b-0">
                          	<table class="table table-striped">
								<thead>
									<tr>
										<th>#</th>
										<th><?= __('admin.username') ?></th>
										<th>Order Total</th>
										<th><?= __('admin.commission') ?></th>
										<th></th>
										<th><?= __('admin.date') ?></th>
										<th><?= __('admin.status') ?></th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
		  	</div>
		</div>
	</div>
</div>

<script src="<?= base_url('assets/plugins/datatable') ?>/moment.js"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.css?v=<?= av() ?>" />
<script type="text/javascript">

	function new_filter() {
		$.ajax({
			type:'POST',
			dataType:'json',
			data:$("#new_filter").serialize(),
			beforeSend:function(){
				$('.btn-new-filter').btn("loading");
			},
			complete:function(){
				$('.btn-new-filter').btn("reset");
			},
			success:function(json){
				if(json['html']){
					$(".new-datatable tbody").html(json['html']);
					$(".new-empty").addClass('d-none');
					$(".new-datatable").show();
				} else{
					$(".new-datatable").hide();
					$(".new-empty").removeClass('d-none');
				}
			},
		})

		return false;
	} new_filter();


	function old_filter() {
		$.ajax({
			type:'POST',
			dataType:'json',
			data:$("#old_filter").serialize(),
			beforeSend:function(){
				$('.btn-old-filter').btn("loading");
			},
			complete:function(){
				$('.btn-old-filter').btn("reset");
			},
			success:function(json){
				if(json['html']){
					$(".old-datatable tbody").html(json['html']);
					$(".old-empty").addClass('d-none');
					$(".old-datatable").show();
				} else{
					$(".old-datatable").hide();
					$(".old-empty").removeClass('d-none');
				}
			},
		})

		return false;
	} old_filter();

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

	$(document).delegate(".btn-deletes",'click',function(){
		$this = $(this);

		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Delete!'
		}).then((result) => {
			if (result.value) {
				var ids = $(".wallet-checkbox:checked").map(function(){ return $(this).val() }).toArray();

				$this = $(this);
				$.ajax({
					type:'POST',
					dataType:'json',
					data:{delete_request: true,id:$this.attr("id")},
					beforeSend:function(){ $this.btn("loading"); },
					complete:function(){ $this.btn("reset"); },
					success:function(json){
						if (json['error']) {
							Swal.fire("Error", json['error'], "error");
						}
						if (json['success']) {
							$this.parents("tr").remove();
							Swal.fire({
								title: 'Success',
								text: "Request Deleted Successfully.. and all transactions are put in wallet",
								icon: 'success',
							}).then((result) => {
								//window.location.reload();
							})
						}
					},
				})
			}
		})
	})

	/*$('.wallet-popover').on('click', function(){
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
	})*/

	<?php if (isset($_GET['tab'])) { ?>
		$(document).ready(function(){
			$('[href="#old-request"]').click()
		});
	<?php } ?>
</script>