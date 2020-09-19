<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/wallet.css?v='. time()) ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/flag/css/main.min.css?v='. time()) ?>">
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
		<div class="card">
			<div class="card-header">
				<h6 class="m-0">Withdraw Requests Details #<?= $request['id'] ?></h6>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-sm-4">
						<h6 class="font-14 text-info with-heading">Request Details</h6>
						<table class="details-dtable">
							<tr>
								<th>ID</th>
								<td> <?= $request['id'] ?></td>
							</tr>
							<tr>
								<th>Total</th>
								<td> <?= c_format($request['total']) ?></td>
							</tr>
							<tr>
								<th>Payment Method</th>
								<td> <?= $request['prefer_method'] ?></td>
							</tr>
							<tr>
								<th>Payment Status</th>
								<td> <?= withdrwal_status($request['status']) ?></td>
							</tr>
						</table>
					</div>
					<div class="col-sm-4">
						<h6 class="font-14 text-info with-heading">Submited Details</h6>
						<table class="details-dtable">
							<?php
								$data = json_decode($request['settings'],1);
								foreach ($data as $key => $value) { ?>
									<tr valign="top">
										<th class="text-capitalize"><?= str_replace("_", " ", $key) ?></th>
										<td style="white-space: pre-line;"><?= $value ?></td>
									</tr>
							<?php } ?>
						</table>
					</div>
					<div class="col-sm-4">
						<h6 class="font-14 text-info with-heading">Status History</h6>
						<table class="table table-bordered table-sm table-status-history">
							<thead>
								<tr>
									<th>Status</th>
									<th>Comment</th>
								</tr>
							</thead>
							<tbody id="history_container"></tbody>
						</table>
					</div>
				</div>
				

				<br><br>

				<h6 class="font-14 text-info with-heading">Transactions</h6>

				<div class="table-responsive">
					<table class="table transaction-table">
						<thead>
							<tr>
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
								foreach ($transaction as $key => $value) {
									$data = [];
									$data['value'] = $value;
									$data['class'] = $class;
									$data['stop_checkbox'] = 1; 
									$data['stop_child'] = 1; 
									$data['wallet_status'] = $status; 
									echo $this->Product_model->getHtml('usercontrol/users/parts/new_wallet_tr', $data);
								} 
							?>
						</tbody>
					</table>
				</div>


				<div class="row">
					<div class="col-sm-8">
						<?= $confirm ?>
					</div>
					<div class="col-sm-4">
						<h6 class="font-14 text-info with-heading">Add Custom Status History</h6>
						<div class="well add-history-form">
							<div class="form-group">
								<label class="form-control-label">Status</label>
								<select class="form-control" name="status">
									<option value="">Select Status</option>
									<?php foreach ($status_list as $key => $value) { ?>
										<option value="<?= $key ?>"><?= $value ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="form-group">
								<label>Comment</label>
								<textarea name="comment" class="form-control" rows="3"></textarea>
							</div>
							<div class="form-group mb-0 text-right">
								<button class="btn btn-sm btn-add-status btn-info">Add Status</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(".btn-add-status").on("click",function(){
		$this = $('.add-history-form');
		$.ajax({
			type:'POST',
			dataType:'json',
			data:$(".add-history-form :input"),
			beforeSend:function(){
				$('.btn-add-status').btn("loading");
			},
			complete:function(){
				$('.btn-add-status').btn("reset");
			},
			success:function(json){
				$container = $this;
				$container.find(".is-invalid").removeClass("is-invalid");
				$container.find("span.invalid-feedback").remove();
		
				if (json['success']) {
					if($(".add-history-form select[name=status]").val() == "1"){
						window.location.reload();
					} else{
						getHistory()
					}
					$('[name="status"], [name="comment"]').val('')
				}
				
				if(json['errors']){
				    $.each(json['errors'], function(i,j){
				        $ele = $container.find('[name="'+ i +'"]');
				        if($ele){
				            $ele.addClass("is-invalid");
				            if($ele.parent(".input-group").length){
				                $ele.parent(".input-group").after("<span class='invalid-feedback'>"+ j +"</span>");
				            } else{
				                $ele.after("<span class='invalid-feedback'>"+ j +"</span>");
				            }
				        }
				    })
				}
			},
		})
	})

	function getHistory() {
		$this = $(this);
		$.ajax({
			url:'<?= base_url('admincontrol/get_withdrwal_history/'. $request['id']) ?>',
			type:'POST',
			dataType:'json',
			beforeSend:function(){
				$("#history_container").html("<tr><td colspan='2' class='text-center'>Loading...</td></tr>");
			},
			success:function(json){
				$("#history_container").html(json['html']);
			},
		})
	}

	getHistory()

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