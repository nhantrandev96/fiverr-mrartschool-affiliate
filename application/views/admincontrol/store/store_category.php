<div class="clearfix"></div>
<br>
<div class="card">
	<div class="card-header">
		<h4 class="card-title pull-left"><?= __('admin.categories') ?></h4>
		<div class="pull-right">
			<a href="<?= base_url('admincontrol/store_category_add') ?>" class="btn btn-primary"><?= __('admin.add_category') ?></a>
		</div>
	</div>
	<div class="card-body p-0">
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

        <div class="dimmer">
        	<div class="loader"></div>
        	<div class="dimmer-content">
				<div class="table-responsive m-0">
					<table class="table orders-table">
						<thead>
							<tr>
								<th width="80px">#</th>
								<th width="80px"><?= __('admin.id') ?></th>
								<th><?= __('admin.name') ?></th>
								<th><?= __('admin.parent') ?></th>
								<th><?= __('admin.total_product') ?></th>
								<th><?= __('admin.date') ?></th>
								<th width="180px">#</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
        	</div>
        </div>
	</div>
	<div class="card-footer text-right" style="display: none;"> <div class="pagination"></div> </div>
</div>

<script type="text/javascript">

	$(document).delegate("[product-category]",'click',function(){
  		$this = $(this);

  		var data = {};
  		data['category_id'] = $this.attr("product-category");

  		$.ajax({
  			url:'<?= base_url('admincontrol/product_logs') ?>',
  			type:'POST',
  			dataType:'json',
  			data:data,
  			beforeSend:function(){$this.btn("loading");},
  			complete:function(){$this.btn("reset");},
  			success:function(json){
  				if(json['html']){
  					$("#log-widzard").modal({
						backdrop: 'static',
						keyboard: false
					});
					$("#log-widzard").html(json['html']);
				}
  			},
  		})
  	})

	function getPage(page,t) {
		$this = $(t);
		var data ={
			page:page,
			filter_status:$(".filter_status").val()
		}
		$.ajax({
			url:'<?= base_url("admincontrol/store_category") ?>/' + page,
			type:'POST',
			dataType:'json',
			data:data,
			beforeSend:function(){
				$this.btn("loading");
				$(".dimmer").addClass("active");
			},
			complete:function(){
				$this.btn("reset");
				$(".dimmer").removeClass("active");
			},
			success:function(json){
				$(".orders-table tbody").html(json['html']);
				$(".card-footer").hide();
				
				if(json['pagination']){
					$(".card-footer").show();
					$(".card-footer .pagination").html(json['pagination'])
				}
			},
		})
	}

	$(".card-footer .pagination").delegate("a","click", function(e){
		e.preventDefault();
		getPage($(this).attr("data-ci-pagination-page"),$(this));
	})

	getPage(1)
</script>