<?php if($this->session->flashdata('success')){?>
	<div class="alert alert-success alert-dismissable my_alert_css">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<?php echo $this->session->flashdata('success'); ?> </div>
<?php } ?>
<?php if($this->session->flashdata('error')){?>
	<div class="alert alert-danger alert-dismissable my_alert_css">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<?php echo $this->session->flashdata('error'); ?> </div>
<?php } ?>
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-header">
				<form method="GET">
					<div class="row">
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label">User</label>
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
								<label class="control-label">Date</label>
								<input autocomplete="off" type="text" name="date" value="<?= isset($_GET['date']) ? $_GET['date'] : '' ?>" class="form-control daterange-picker">

							</div>
						</div>

						

						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label d-block">&nbsp;</label>
								<button class="btn btn-primary">Filter</button>
							</div>
						</div>

						<div class="col-sm-12">
							<div class="form-group text-right">

								<a href="<?= base_url('admincontrol/ask_again_withdrawal') ?>" type="button" class=" btn-sm btn btn-success "> Ask Again For Withdrawal </a>
								<!-- <button status="3" type="button" class=" btn-sm btn btn-success selected-option  show-on-select"> Accept Selected </button>
								<button status="4" type="button" class=" btn-sm btn btn-danger selected-option show-on-select"> Reject Selected </button>
								<button status="3" class=" btn-sm btn btn-success accept-all" type="button">Accept All (<small><?= (int)$totals['wallet_request_sent_count'] ?> / <?= c_format($totals['wallet_request_sent_amount']) ?></small>) </button>
								<button status="4" class=" btn-sm btn btn-danger accept-all" type="button">Reject All (<small><?= (int)$totals['wallet_request_sent_count'] ?> / <?= c_format($totals['wallet_request_sent_amount']) ?></small>) </button> -->
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="table-rep-plugin">
			    
			    <div class="text-center">
                                <?php if ($transaction ==null) {?>
                                <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:100px;">
                                 <h3 class="m-t-40 text-center"><?= __('admin.no_wallet_withdraw') ?></h3>
                                <?php }
                                else {?>
                                
                                
                   <div class="table-responsive b-0" data-pattern="priority-columns">
                          <table id="tech-companies-1" class="table  table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" class="select-all" ></th>
								<th>#</th>
								<th><?= __('admin.username') ?></th>
								<th>Order Total</th>
								<th><?= __('admin.commission') ?></th>
								<th></th>
								<th><?= __('admin.date') ?></th>
								<th><?= __('admin.status') ?></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($transaction as $key => $value) { ?>
							<tr>
								<td><input type="checkbox" class="select-single" amount='<?= $value['amount'] ?>' value="<?= $value['id'] ?>" ></td>
								<td><?php echo $key + 1 ?></td>
								<td><?php echo $value['username'] ?></td>
								<td>
									<?php if($value['integration_orders_total']){ ?>
										<?= c_format($value['integration_orders_total']) ?>
									<?php } ?>
								</td>
								<td>
									<div class="dpopver-content d-none">
										<?php
											list($message,$ip_details) = parseMessage($value['comment'],$value,'admincontrol',true);
											echo "<div>". $message ."</div>";
										?>
									</div>
									<div 
										class="wallet-popover badge badge-<?= $value['amount'] >= 0 ? 'secondary' : 'danger' ?> py-1 pl-2 font-14" 
										toggle="popover"
									> 
										<?= c_format($value['amount']) ?> 
									</div>
								</td>
								<!-- <td style="min-width: 250px"><?= parseMessage($value['comment'],$value,'usercontrol') ?></td> -->
								<td><?= wallet_ex_type($value) ?></td>
								<td><?php echo $value['created_at'] ?></td>
								<td><?php echo $request_status[$value['status']] ?></td>
								<td>
									<!-- <button status="3" data-id="<?= $value['id'] ?>" class="btn btn-success change-status"><?= __('admin.accept') ?></button>
									<button status="4" data-id="<?= $value['id'] ?>" class="btn btn-danger change-status"><?= __('admin.reject') ?></button>
									<br>
									<a href="javascript:void(0)" payment_detail="<?php echo $value['user_id'] ?>"><?= __('admin.view_payment_detail') ?></a> -->
								</td>
							</tr>
							<?php } ?>
							<?php } ?>
						</tbody>
					</table>
				</div>	
			</div>
		</div>
	</div>
</div>


<script src="<?= base_url('assets/plugins/datatable') ?>/moment.js"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.css?v=<?= av() ?>" />

<script type="text/javascript">

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

	/*function conf(success,cancel,title,body){
		var model = '';
		model += '<div id="confirm_modal" class="modal fade" role="dialog">';
		model += '  <div class="modal-dialog">';
		model += '    <div class="modal-content">';
		model += '      <div class="modal-header">';
		model += '        <button type="button" class="close" data-dismiss="modal">&times;</button>';
		model += '        <h4 class="modal-title">'+ title +'</h4>';
		model += '      </div>';
		model += '      <div class="modal-body">';
		model += '        <p>'+ body +'</p>';
		model += '      </div>';
		model += '      <div class="modal-footer">';
		model += '        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
		model += '      </div>';
		model += '    </div>';
		model += '  </div>';
		model += '</div>';

		$("body").append();
		$("#confirm_modal").modal("show");
	}*/

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

	$('.change-status').on('click',function(){
		$this = $(this);
		var status = $this.attr("status");
		$.ajax({
			type:'POST',
			dataType:'json',
			data:{status:status,request_payment: $this.attr("data-id")},
			beforeSend:function(){$this.btn("loading")},
			complete:function(){$this.btn("reset")},
			success:function(json){
				$this.parents("tr").remove();
			},
		})
	})
	$('.accept-all').on('click',function(){
		$this = $(this);
		var status = $this.attr("status");

		if(!confirm('Are you sure you want to '+ (status == '3' ? 'accept' : 'reject') +' all transaction..')) return false;
		$.ajax({
			type:'POST',
			dataType:'json',
			data:{status:status,request_payment_all: true},
			beforeSend:function(){$this.btn("loading")},
			complete:function(){$this.btn("reset")},
			success:function(json){
				window.location.reload();
			},
		})
	})

	$(".select-all").on('change',function(){
		$('.select-single').prop("checked", $(this).prop("checked"));
	})

	$(".select-single").on('change',function(){
		if($(".select-single:checked").length == 0){
			$(".show-on-select").hide();
		} else {
			$(".show-on-select").show();
		}
	})

	$('.selected-option').on('click',function(){
		$this = $(this);
		var status = $this.attr("status");
		var selected = $('.select-single:checked').on('map',function() {return this.value;}).get().join(',')

		if($(".select-single:checked").length > 0){
			if(!confirm('Are you sure you want to '+ (status == '3' ? 'accept' : 'reject') +' selected transaction..')) return false;

			$.ajax({
				type:'POST',
				dataType:'json',
				data:{status:status, selected_option: selected},
				beforeSend:function(){$this.btn("loading")},
				complete:function(){$this.btn("reset")},
				success:function(json){
					window.location.reload();
				},
			})
			
		} else {
			alert("Select any checkbox..");
		}
	})
</script>