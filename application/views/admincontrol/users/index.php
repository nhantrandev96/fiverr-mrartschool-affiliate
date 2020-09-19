<div class="modal fade" id="info-modal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Users Page Info</h4>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			<div class="modal-body">
				<ul class="list-group">
					<li class="list-group-item">Using this Tools bar will give you the ability to Add new affiliate, export affiliate to excel and then import affiliates from excel template.</br>
						<img class="zoom" src="<?php echo base_url(); ?>assets/guide_images/users_img1.png" alt="" style="width:80%;height:80%; margin-right:0; margin-left:0;">
					</li>
					<li class="list-group-item">Pressing the + icon will give you the abilty to to view your users data by press the + icon, view user commissions and more!</br>
						<img class="zoom" src="<?php echo base_url(); ?>assets/guide_images/users_img2.png" alt="" style="width:80%;height:80%">
					</li>
					<li class="list-group-item">In every row you have Action bar where you can Edit user, Block user, Delete user, view downline of user.</br>
						<img class="zoom" src="<?php echo base_url(); ?>assets/guide_images/users_img3.png?v=1.2" alt="" style="width:80%;height:80%">
					</li>
					<li class="list-group-item">Payment info of your user, you can view user payment info so able to pay him his commission.</br>
						<img class="zoom" src="<?php echo base_url(); ?>assets/guide_images/users_img4.png" alt="" style="width:80%;height:80%">
					</li>
					<li class="list-group-item">Delete user option with all his commission or without commission. </br>IMPORTANT, this action does not have a role back!</br>
						<img class="zoom" src="<?php echo base_url(); ?>assets/guide_images/users_img5.png" alt="" style="width:80%;height:80%">
					</li>
				</ul>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>

<div class="modal fade" id="importUsersModel" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Import Users</h4>
				<button type="button" class="close btn-close" data-dismiss="modal">&times;</button>
				<a href="" class="close hidden-close d-none">&times;</a>
			</div>
			<div class="modal-body">
				<form id="import_form">
					<label class="file">
						<input name="import_control" type="file" id="import_control" aria-label="File browser example">
						<span class="file-custom"></span>
					</label>
				</form>

				<div id="import-status"></div>
				<div id="import-log"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
				<a href="" class="btn btn-default hidden-close d-none">Close</a>
				<button type="button" class="btn btn-default btn_import_data" data-dismiss="modal">Upload</button>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?= __('admin.menu_list_affiliates') ?></h4>
			</div>
			<div class="card m-b-30">
				<div class="card-body">

					<div class="alert alert-primary" role="alert">
						<p class="m-0">Users page Actions Are: Add/Edit/Delete/Block/Export/Import/Add Transactions/View Downline/View Payments, users and more.
						</p>
					</div>


					<?php if($this->session->flashdata('success')){?>
						<div class="alert alert-success alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<?php echo $this->session->flashdata('success'); ?> 
						</div>
					<?php } ?>

					<?php if($this->session->flashdata('error')){?>
						<div class="alert alert-danger alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<?php echo $this->session->flashdata('error'); ?> 
						</div>
					<?php } ?>

					<form id="search-form">
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label">Name</label>
									<input type="search" name="name" class="form-control">
								</div>
							</div>

							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label">Email</label>
									<input type="search" name="email" class="form-control">
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label d-block">&nbsp;</label>
									<div>
										<div class="btn-group mb-1 d-inline-block btn-group-md" role="group" aria-label="Export/Import Users">
											<button type="button" class="btn btn-info pl-3 pr-3" data-toggle="modal" data-target="#info-modal"><i class="fa fa-info"></i></button>
											<a class="btn btn-primary" href="<?php echo base_url("admincontrol/addusers") ?>"><?= __('admin.add_affiliate') ?></a>
											<button type="button" class="btn btn-dark export-excel" > <i class="fa fa-file-excel-o"></i> <?= __('admin.export') ?></button>
											<button type="button" class="btn btn-info import-excel" data-toggle="modal" data-target="#importUsersModel"> <i class="fa fa-file-excel-o"></i> <?= __('admin.import') ?></button>
										</div>
										<div class="btn-group mb-1 d-inline-block btn-group-md delete-multiple-container">
											<button class="btn btn-danger delete-multiple" type="button">Delete Selected <span class="selected-count"></span></button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>

					<div class="selection-message d-none">
						All <span class="selected-count"></span> users on this page are selected. <a href="javascript:void(0)" class="select-all-users">Select all <span class="total-user"></span> users </a> <a href="javascript:void(0)" class="clear-selection">Clear selection</a>
					</div>

					<div class="dimmer">
						<div class="loader"></div>
						<div class="dimmer-content">
							<div class="table-rep-plugin">
								<div class="table-responsive b-0" data-pattern="priority-columns">
									<table id="tech-companies-1" class="table table-hover user-table">
										<thead class="thead-light">
											<tr>
												<th><input type="checkbox" class="selectall-wallet-checkbox"></th>
												<th><?= __('admin.first_last')?></th>
												<th><?= __('admin.country')?></th>
												<th><?= __('admin.email')?></th>
												<th><?= __('admin.username')?></th>
												<th><?= __('admin.Under_Affiliate')?></th>

												<?php foreach ($data as $key => $value) { if($value['type'] == 'header') continue; ?>
												<th ><?= $value['label'] ?></th>
											    <?php } ?>
											    
											    <th><?= __('admin.vendor')?></th>
											    <th><?= __('admin.action')?></th>
										  </tr>  
									</thead> 
									<tbody></tbody>
									<tfoot>
										<tr>
											<td colspan="100%" class="text-right">
												<div class="pagination"></div>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
</div>
</div>

<div class="modal fade" id="modal-delete">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div id="message"></div>
				<hr>
				<div>
					<div class="checkbox">
						<label>
							<input type="checkbox" value="delete_transaction" id="delete_transaction">
							<?= __('admin.delete_all_transaction_or_commission') ?>	
						</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?= __('admin.cancel') ?></button>
				<button type="button" class="btn btn-primary confirm-delete" data-id="0"><?= __('admin.delete') ?></button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-tree">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?= __('admin.close') ?></button>
			</div>
		</div>
	</div>
</div>

<script src="<?= base_url('assets/plugins/tree') ?>/jquery-ui-1.10.4.custom.min.js"></script>
<script src="<?= base_url('assets/plugins/tree') ?>/jquery.tabelizer.js"></script>
<link href="<?= base_url('assets/plugins/tree') ?>/tabelizer.min.css?v=<?= av() ?>" media="all" rel="stylesheet" type="text/css" />

<script type="text/javascript" async="">
	var selected = {};
	var all_ids = [];
	$('.clear-selection').on('click',function(){
		selected = {};
		$(".selection-message").addClass('d-none');
		$('.selectall-wallet-checkbox').prop("checked",0);
		changeViews();
	});

	function changeViews() {
		$(".wallet-checkbox").prop("checked",  false);

		if(Object.keys(selected).length == 0){
			$(".selection-message").addClass('d-none');
		} else {
			$(".selection-message").removeClass('d-none');
			$(".selected-count").text(Object.keys(selected).length);
		}

		$(".select-all-users").show();
		if(Object.keys(selected).length == all_ids.length){
			$(".select-all-users").hide();
		}

		$.each(selected, function(i,j){
			$('.wallet-checkbox[value="'+ j +'"]').prop("checked",true);
		})

		if(Object.keys(selected).length == 0){
			$(".delete-multiple").hide()
		} else {
			$(".delete-multiple").show();
		}
	}

	$('.selectall-wallet-checkbox').on('change',function(){
		$(".wallet-checkbox").prop("checked",  $(this).prop("checked"));

		$('.wallet-checkbox').each(function(){
			var val = $(this).val();
			if($(this).prop("checked")){ selected[val]=val; } 
			else { delete selected[val]; }

		})
		changeViews();
	})

	$(".user-table").delegate(".wallet-checkbox","change",function(){
		var status = $(this).prop("checked");

		if(!status) delete selected[$(this).val()]
			else selected[$(this).val()] = $(this).val();

		changeViews();
	})

	$(".select-all-users").on('click',function(){
		$this = $(this);
		$.ajax({
			type:'POST',
			dataType:'json',
			data:{action:'get_all_ids'},
			beforeSend:function(){ $this.btn("loading");},
			complete:function(){ $this.btn("reset"); },
			success:function(json){
				$.each(json['ids'],function(i,id){
					selected[id]= id;
				})
				$(".selected-count").text(Object.keys(selected).length);
				all_ids = json['ids'];

				changeViews();
			},
		})
	})

	$(".user-table").delegate(".checkbox-label","click",function(e){
		e.stopPropagation();
	});

	$(".delete-multiple").on('click',function(e){
		$this = $(this);

		var ids = Object.keys(selected).join(",");
		e.preventDefault();
		e.stopPropagation();

		if(!confirm("Are you sure ?")) return false;
		$.ajax({
			url: '<?php echo base_url("admincontrol/deleteAllusersMultiple") ?>',
			type:'POST',
			dataType:'json',
			data:{ids:ids},
			beforeSend:function(){ $this.btn("loading"); },
			complete:function(){ $this.btn("reset"); },
			success:function(json){
				$("#modal-delete #message").html(json['message']);
				$("#modal-delete .confirm-delete").attr("data-id",ids);
				$("#modal-delete").modal("show");
			},
		})
	})

	var xhr;
	function getPage(page) {
		$this = $(this);
		if(xhr && xhr.readyState != 4) xhr.abort();

		var data = $("#search-form").serialize();
		xhr = $.ajax({
			type:'POST',
			dataType:'json',
			data: data + "&page="+page,
			beforeSend:function(){
				$(".dimmer").addClass("active");
			},
			complete:function(){
				$(".dimmer").removeClass("active");
			},
			success:function(json){
				if(json['table']){
					$('.selectall-wallet-checkbox').prop("checked",false)
					$(".user-table tbody").html(json['table']);

					/*$.each(selecttion, function(i,j){
						$('.wallet-checkbox[value="'+ j +'"]').prop("checked",1);
					})*/

					changeViews();
				}
				if(json['pagination']){
					$(".user-table tfoot .pagination").html(json['pagination']);
				}
			},
		})
	}

	$("#search-form input").on('keyup',function(){
		getPage(1);
	})

	$(".user-table tfoot .pagination").delegate("a","click", function(e){
		e.preventDefault();
		getPage($(this).attr("data-ci-pagination-page"));
	})


	getPage(1)

	$(".user-table").delegate(".btn-remove",'click',function(e){
		if(!confirm("Are you sure ?")) e.preventDefault();
		return true;
	});

	$(".user-table").delegate(".show-tree",'click',function(e){
		e.preventDefault();
		e.stopPropagation();

		$this = $(this);
		$.ajax({
			url: '<?php echo base_url("admincontrol/showTree") ?>',
			type:'POST',
			dataType:'json',
			data:{id:$this.attr("data-id")},
			beforeSend:function(){ $this.btn("loading"); },
			complete:function(){ $this.btn("reset"); },
			success:function(json){
				$("#modal-tree .modal-body").html(json['html']);
				$("#modal-tree").modal("show");
			},
		})
	})

	$(".user-table").delegate(".btn-delete2",'click',function(e){

		e.preventDefault();
		e.stopPropagation();

		$this = $(this);
		if(!confirm("Are you sure ?")) return false;
		$.ajax({
			url: '<?php echo base_url("admincontrol/deleteAllusers") ?>',
			type:'POST',
			dataType:'json',
			data:{id:$this.attr("data-id")},
			beforeSend:function(){ $this.btn("loading"); },
			complete:function(){ $this.btn("reset"); },
			success:function(json){
				$("#modal-delete #message").html(json['message']);
				$("#modal-delete .confirm-delete").attr("data-id",$this.attr("data-id"));
				$("#modal-delete").modal("show");
			},
		})
	})
	
	$(document).delegate(".confirm-delete",'click',function(e){
		e.preventDefault();
		e.stopPropagation();

		$this = $(this);
		$.ajax({
			url: '<?php echo base_url("admincontrol/deleteUsersConfirm") ?>',
			type:'POST',
			dataType:'json',
			data:{
				id:$this.attr("data-id"),
				delete_transaction:$("#delete_transaction").prop("checked")
			},
			beforeSend:function(){ $this.btn("loading"); },
			complete:function(){ $this.btn("reset"); },
			success:function(json){
				window.location.reload();
			},
		})
	})

	$(".export-excel").on('click',function(){
		$this = $(this);
		$.ajax({
			url:'<?= base_url('admincontrol/get_user_data') ?>',
			type:'POST',
			dataType:'json',
			data: {
				action:'export',
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

	$(".btn_import_data").on('click',function(){
		$this = $("#import_form");
		var formData = new FormData($this[0]);
		formData.append("action",'import');

		formData = formDataFilter(formData);
		$(".btn_import_data").prop("disabled",true);

		$.ajax({
			url:'<?= base_url('admincontrol/get_user_data') ?>',
			type:'POST',
			dataType:'json',
			cache:false,
			contentType: false,
			processData: false,
			data:formData,
			xhr: function (){
				var jqXHR = null;

				if ( window.ActiveXObject ){
					jqXHR = new window.ActiveXObject( "Microsoft.XMLHTTP" );
				}else {
					jqXHR = new window.XMLHttpRequest();
				}

				jqXHR.upload.addEventListener( "progress", function ( evt ){
					if ( evt.lengthComputable ){
						var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
						$('#import-status').text("Uploading - " + percentComplete + "%").show();
					}
				}, false );

				jqXHR.addEventListener( "progress", function ( evt ){
					if ( evt.lengthComputable ){
						var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
						$('#import-status').html('Import Users...');
					}
				}, false );
				return jqXHR;
			},
			error:function(){
				$(".btn_import_data").prop("disabled",false);
			},
			success:function(result){
				$(".btn_import_data").prop("disabled",false);

				if(result['location']){ window.location = result['location']; }

				$(".hidden-close").removeClass("d-none");
				$(".btn-close").remove();

				$("#import-log").html('');
				$("#import-status").html('');
				if(result['errors']){
					$("#import-log").html(result['errors']);
				}
			},
		})
	});
	
	$('.accordian-body').on('show.bs.collapse', function () {
		$(this).closest("table")
		.find(".collapse.in")
		.not(this)
		.collapse('toggle')
	})
</script>

<script type="text/javascript">
  /*$(".dummyscroll > div").width($(".top-scroll")[0].scrollWidth)

  $(".dummyscroll").on('scroll',function(){
    $(".orig-scroll").scrollLeft($(".dummyscroll").scrollLeft());
  });
  $(".orig-scroll").on('scroll',function(){
    $(".dummyscroll").scrollLeft($(".orig-scroll").scrollLeft());
});*/
</script>




