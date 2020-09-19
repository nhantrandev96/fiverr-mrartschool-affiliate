<div class="alert alert-primary" role="alert">
	<p class="m-0">Integrations Tools: In this page, you can add a new integration tool like banner/link/text/video and publish it to your affiliates.
	</p>
</div>

<div class="row">
	<div class="col-sm-3">
		<div class="card m-b-30 text-white bg-primary">
			<div class="card-body">
				<blockquote class="card-bodyquote mb-0">
					<h3><?php echo __('admin.banners') ?></h3>
					<a href="<?= base_url('usercontrol/integration_tools_form/banner') ?>" class="btn btn-dark waves-effect waves-light"><i class="fa fa-plus"></i> <?php echo __('admin.create_new') ?></a>
				</blockquote>
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="card m-b-30 text-white bg-secondary">
			<div class="card-body">
				<blockquote class="card-bodyquote mb-0">
					<h3><?= __('admin.text_ads') ?></h3>
					<a href="<?= base_url('usercontrol/integration_tools_form/text_ads') ?>" class="btn btn-dark waves-effect waves-light"><i class="fa fa-plus"></i> <?php echo __('admin.create_new') ?></a>
				</blockquote>
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="card m-b-30 text-white bg-danger">
			<div class="card-body">
				<blockquote class="card-bodyquote mb-0">
					<h3><?php echo __('admin.invisible_links') ?></h3>
					<a href="<?= base_url('usercontrol/integration_tools_form/link_ads') ?>" class="btn btn-dark waves-effect waves-light"><i class="fa fa-plus"></i><?php echo __('admin.create_new') ?></a>
				</blockquote>
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="card m-b-30 text-white bg-warning">
			<div class="card-body">
				<blockquote class="card-bodyquote mb-0">
					<h3><?php echo __('admin.viral_videos') ?></h3>
					<a href="<?= base_url('usercontrol/integration_tools_form/video_ads') ?>" class="btn btn-dark waves-effect waves-light"><i class="fa fa-plus"></i> <?php echo __('admin.create_new') ?></a>
				</blockquote>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<div class="card m-b-20">
			<div class="card-body">

				<div class="row">
					<div class="col-12">
						<div>
							<h5 class=""><?php echo __('admin.integration_tools') ?></h5>

							<div class="row mb-3">
								<div class="col-sm-3">
									<label class="control-label">Search By Category</label>
									<select class="form-control category_id" >
										<option value="">All Categories</option>
										<?php foreach ($categories as $key => $value) { ?>
											<option value="<?= $value['value'] ?>"><?= $value['label'] ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-sm-3">
									<label class="control-label">Search By Ads Name</label>
									<input class="table-search form-control ads_name" placeholder="Search" type="search">
								</div>
							</div>
						</div>
					</div>

					<div class="text-center col-12 empty-div d-none">
                        <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:25px;">
                        <h3 class="m-t-40 text-center"><?= __('user.no_banners_to_share_yet') ?></h3>
                    </div>
					<div class="table-responsive b-0" data-pattern="priority-columns">
                        
						<table id="myTable" class="table table-striped">
							<thead>
								<tr>
									<th width="50px" class="text-center"></th>
									<th width="50px" class="text-center"><?php echo __('admin.id') ?></th>
									<th width="200px"><?php echo __('admin.name') ?></th>
									<th width="100px"><?= __('admin.tool_type') ?></th>
									<th width="200px"><?php echo __('admin.program_name') ?> / <?php echo __('admin.type') ?></th>

									<th  width="140px"><?= __('admin.sale_commisssion') ?></th>
									<th  width="140px"><?= __('admin.product_click') ?></th>
									<th  width="140px"><?= __('admin.general_click') ?></th>
									<th  width="140px"><?= __('admin.action_click') ?></th>

									<th width="100px"><?php echo __('admin.status') ?></th>
									<th width="180px"><?php echo __('admin.created_date') ?></th>
								</tr>
							</thead>
							<tbody></tbody>
							<tfoot>
								<tr>
									<td colspan="12" class="text-right">
										<ul class="pagination pagination-td"></ul>
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

<div class="modal fade" id="integration-code">
	<div class="modal-dialog">
		<div class="modal-content"></div>
	</div>
</div>

<div class="modal fade" id="showcode-code"></div>


<script type="text/javascript">
	var xhr;
	function getPage(url){
		$this = $(this);

		if(xhr && xhr.readyState != 4) xhr.abort();

		xhr = $.ajax({
			url:url,
			type:'POST',
			dataType:'json',
			data:{
				category_id: $(".category_id").val(),
				ads_name: $(".ads_name").val(),
			},
			beforeSend:function(){$(".btn-search").btn("loading");},
			complete:function(){$(".btn-search").btn("reset");},
			success:function(json){
				if(json['view']){
					$("#myTable tbody").html(json['view']);
					$("#myTable").show();
					$(".empty-div").addClass("d-none");
				} else {
					$(".empty-div").removeClass("d-none");
					$("#myTable").hide();
				}
				
				$("#myTable .pagination-td").html(json['pagination']);
			},
		})
	}

	$(".category_id").on("change",function(){
		getPage('<?= base_url("usercontrol/integration_tools/") ?>/1');
	});
	$(".ads_name").on("keyup",function(){
		getPage('<?= base_url("usercontrol/integration_tools/") ?>/1');
	});
	
	getPage('<?= base_url("usercontrol/integration_tools") ?>/1');

	$("#myTable .pagination-td").delegate("a","click",function(){
		getPage($(this).attr("href"));
		return false;
	})

	$("#myTable").delegate(".btn-show-code",'click',function(){
		$this = $(this);
		$.ajax({
			url:'<?= base_url("usercontrol/integration_code_modal_new") ?>',
			type:'POST',
			dataType:'json',
			data:{id: $this.attr("data-id")},
			beforeSend:function(){$this.btn("loading");},
			complete:function(){$this.btn("reset");},
			success:function(json){
				if(json['html']){
					$("#showcode-code").html(json['html']);
					$("#showcode-code").modal("show");
				}
			},
		})
	})

	$("#myTable").delegate(".wallet-toggle .tog",'click',function(){
		$(this).parents(".wallet-toggle").find("> div").toggleClass("hide");
	})
	$("#myTable").delegate(".tool-remove-link",'click',function(){
		if(!confirm("Are you sure?")) return false;
		return true;
	})

	$("#myTable").delegate(".get-code",'click',function(){
		$this = $(this);
		$.ajax({
			url:'<?= base_url("usercontrol/tool_get_code") ?>',
			type:'POST',
			dataType:'json',
			data:{id:$this.attr("data-id")},
			beforeSend:function(){ $this.btn("loading"); },
			complete:function(){ $this.btn("reset"); },
			success:function(json){
				if(json['html']){
					$("#integration-code .modal-content").html(json['html']);
					$("#integration-code").modal("show");
				}
			},
		})
	})
</script>