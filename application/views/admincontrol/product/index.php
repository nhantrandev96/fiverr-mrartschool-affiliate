<?php
	$db =& get_instance();
	$userdetails=$db->userdetails();
	$store_setting =$db->Product_model->getSettings('store');
	$Product_model =$db->Product_model;
?>


<div id="overlay"></div>
<div class="popupbox" style="display: none;">
	<div class="backdrop box">
		<div class="modalpopup" style="display:block;">
			<a href="javascript:void(0)" class="close js-menu-close" onclick="closePopup();"><i class="fa fa-times"></i></a>
			<div class="modalpopup-dialog">
				<div class="modalpopup-content">
					<div class="modalpopup-body">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12">
		<?php if($this->session->flashdata('success')){?>
			<div class="alert alert-success alert-dismissable">
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
		<div class="card m-b-30">
			<div class="card-body">
				<b>Store URL</b>
				<div class="row top-panel">
					<span class="m-b-5">
						<div class="share-store-list">
							<a class="btn btn-lg btn-default btn-success" href="<?php echo base_url("admincontrol/addproduct") ?>"><?= __('admin.add_product') ?></a>
							<a class="btn btn-lg btn-default btn-success" href="<?php echo base_url("admincontrol/form_manage") ?>"><?= __('admin.add_form') ?></a>
						</div>
					</span>
					<span class="m-b-5" style="width: 400px;">
						<?php $store_url = base_url('store'); ?>
						<div class="input-group">
							<input type="text" id="store-link" readonly="readonly" value="<?php echo $store_url ?>" class="form-control">
							<button onclick="copy_text()" class="input-group-addon">
								<img src="<?php echo base_url('assets/images/clippy.svg') ?>" class="tooltiptext" width="25px" height="25px" alt="Copy to clipboard">
							</button>
						</div>
					</span>
					<span class="m-b-5">
						<div class="share-store-list">
							<a class="btn btn-lg btn-default btn-success" href="<?php echo $store_url ?>" target="_blank"><?= __('admin.priview_store') ?></a>
						</div>
					</span>
					<span>
						<button style="display:none;" type="button" class="btn btn-lg btn-danger" name="deletebutton" id="deletebutton" value="Save & Exit" onclick="deleteuserlistfunc('deleteAllproducts');"><?= __('admin.delete_products') ?></button>
					</span>
				</div>
				<br>

				<div class="filter">
					<form id="filter-form">
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label">Category</label>
									<select name="category_id" class="form-control">
										<?php $selected = isset($_GET['category_id']) ? $_GET['category_id'] : ''; ?>
										<option value="">All Category</option>
										<?php foreach ($categories as $key => $value) { ?>
											<option <?= $selected == $value['id'] ? 'selected' : '' ?> value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label">Vendor</label>
									<select name="seller_id" class="form-control">
										<?php $selected = isset($_GET['seller_id']) ? $_GET['seller_id'] : ''; ?>
										<option value="">All Vendor</option>
										<?php foreach ($vendors as $key => $value) { ?>
											<option <?= $selected == $value['id'] ? 'selected' : '' ?> value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
										<?php } ?>
									</select>
								</div>
							</div>	
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label d-block">&nbsp;</label>
									<button type="submit" class="btn btn-primary">Search</button>
								</div>
							</div>	
						</div>
					</form>
				</div>
				
			    <?php if ($productlist == null) {?>
                    <div class="text-center">
                    <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:100px;">
                 	<h3 class="m-t-40 text-center text-muted"><?= __('admin.no_products') ?></h3></div>
                <?php } else { ?>
                	<div class="table-responsive b-0" data-pattern="priority-columns">
						<form method="post" name="deleteAllproducts" id="deleteAllproducts" action="<?php echo base_url('admincontrol/deleteAllproducts'); ?>">
							<table id="tech-companies-1" class="table  table-striped">
								<thead>
									<tr>
										<th><input name="product[]" type="checkbox" value="" onclick="checkAll(this)"></th>
										<th width="220px"><?= __('admin.product_name') ?></th>
										<th><?= __('admin.featured_image') ?></th>
										<th><?= __('admin.vendor_name') ?></th>
										<th><?= __('admin.price') ?></th>
										<th><?= __('admin.sku') ?></th>
										<th width="220px"><?= __('admin.get_ncommission') ?></th>
										<th><?= __('admin.sales_/_commission') ?></th>
										<th><?= __('admin.clicks_/_commission') ?></th>
										<th><?= __('admin.total') ?></th>
										<th><?= __('admin.status') ?></th>
										<th><?= __('admin.action') ?></th>
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
						</form>
					</div>
				<?php } ?>
			</div>
		</div> <!-- end col -->
	</div> <!-- end row -->
</div>


		


<script type="text/javascript" async="">

	$(".show-more").on('click',function(){
		$(this).parents("tfoot").remove();
		$("#product-list tr.d-none").hide().removeClass('d-none').fadeIn();
	});

	$(".delete-button").on('click',function(){
		if(!confirm("Are you sure ?")) return false;
	});
	$(".toggle-child-tr").on('click',function(){
		$tr = $(this).parents("tr");
		$ntr = $tr.next("tr.detail-tr");

		if($ntr.css("display") == 'table-row'){
			$ntr.hide();
			$(this).find("i").attr("class","fa fa-plus");
		}else{
			$(this).find("i").attr("class","fa fa-minus");
			$ntr.show();
		}
	})
	
	function checkAll(bx) {
		var cbs = document.getElementsByTagName('input');
		if(bx.checked)
		{
			document.getElementById('deletebutton').style.display = 'block';
			} else {
			document.getElementById('deletebutton').style.display = 'none';
		}
		for(var i=0; i < cbs.length; i++) {
			if(cbs[i].type == 'checkbox') {
				cbs[i].checked = bx.checked;
			}
		}
	}
	function checkonly(bx,checkid) {
		if($(".list-checkbox:checked").length){
			$('#deletebutton').show();
		} else {
			$('#deletebutton').hide();
		}
	}
	function deleteuserlistfunc(formId){
		if(! confirm("Are you sure ?")) return false;

		$('#'+formId).submit();
	}
	

	$("#filter-form").on("submit",function(){
		getPage('<?= base_url("admincontrol/listproduct_ajax/") ?>/1');
		return false;
	})

	function getPage(url){
		$this = $(this);

		$.ajax({
			url:url,
			type:'POST',
			dataType:'json',
			data:$("#filter-form").serialize(),
			beforeSend:function(){$this.btn("loading");},
			complete:function(){$this.btn("reset");},
			success:function(json){
				if(json['view']){
					$("#tech-companies-1 tbody").html(json['view']);
					$("#tech-companies-1").show();
				} else {
					$(".empty-div").removeClass("d-none");
					$("#tech-companies-1").hide();
				}
				
				$("#tech-companies-1 .pagination-td").html(json['pagination']);
			},
		})
	}

	getPage('<?= base_url("admincontrol/listproduct_ajax/") ?>/1');
	$("#tech-companies-1 .pagination-td").delegate("a","click",function(){
		getPage($(this).attr("href"));
		return false;
	})

	function closePopup(){
		$('.popupbox').hide();
		$('#overlay').hide();
	}
	function generateCode(affiliate_id){
		$('.popupbox').show();
		$('#overlay').show();
		$('.modalpopup-body').load('<?php echo base_url();?>admincontrol/generateproductcode/'+affiliate_id);
		$('.popupbox').ready(function () {
			$('.backdrop, .box').animate({
				'opacity': '.50'
			}, 300, 'linear');
			$('.box').animate({
				'opacity': '1.00'
			}, 300, 'linear');
			$('.backdrop, .box').css('display', 'block');
		});
	}

	$(document).delegate(".delete-product",'click',function(){
		if(! confirm("Are you sure ?")) return false;
		window.location = $("#deleteAllproducts").attr("action") + "?delete_id=" + $(this).attr("data-id");
	})
</script>			