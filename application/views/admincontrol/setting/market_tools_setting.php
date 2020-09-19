<div class="card">
		<div class="card-body">
			<form class="form-horizontal" method="post" action=""  enctype="multipart/form-data" id="setting-form">
				<div class="row">
					<div class="col-sm-12">
						<ul class="nav nav-pills nav-stacked setting-nnnav" role="tablist">
							<li class="nav-item">
								<a class="nav-link active show" data-toggle="tab" href="#market_vendor-setting" role="tab">Market Tools Vendor</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#marketpostback-setting" role="tab"><?= __('admin.marketpostback') ?></a>
							</li>
						</ul>
					</div>
					<div class="col-sm-12">
						<div class="tab-content">
							<?php if($this->session->flashdata('success')){?>
								<div class="alert alert-success alert-dismissable"> <?php echo $this->session->flashdata('success'); ?> </div>
							<?php } ?>
							<div class="tab-pane p-3" id="marketpostback-setting" role="tabpanel">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<label class="control-label">Postback Status</label>
											<select class="form-control" name="marketpostback[status]">
												<option value="0"><?= __('admin.disable') ?></option>
												<option value="1" <?= $marketpostback['status'] ? 'selected' : '' ?>><?= __('admin.enable') ?></option>
											</select>
										</div>
										
										<div class="form-group">
											<label class="control-label">Postback URL</label>
											<input type="text" name="marketpostback[url]" value="<?= $marketpostback['url'] ?>" class="form-control">
										</div>
										<div class="form-group">
											<label class="control-label">Dynamic Params</label>
											<div>
												<?php 
													$dynamicparam = [
														'city' => 'City',
														'regionCode' => 'Region Code',
														'regionName' => 'Region Name',
														'countryCode' => 'Country Code',
														'countryName' => 'Country Name',
														'continentName' => 'Continent Name',
														'timezone' => 'Timezone',
														'currencyCode' => 'Currency Code',
														'currencySymbol' => 'Currency Symbol',
														'ip' => 'IP',
														'type' => 'Type action,general_click,product_click,sale',
														'id' => 'ID (Sale ID OR Click ID)',
													];
													$marketpostback_dynamicparam = json_decode($marketpostback['dynamicparam'],1);
													$marketpostback_static = json_decode($marketpostback['static'],1);
												?>
												<div class="row">
													<?php foreach ($dynamicparam as $key => $value) { ?>
														<div class="col-sm-3">
															<label class="checkbox font-weight-light">
																<input type="checkbox" <?= isset($marketpostback_dynamicparam[$key]) ? 'checked' : '' ?> name="marketpostback[dynamicparam][<?= $key ?>]" value="<?= $key ?>">
																<span> <b><?= $key ?></b> - <?= $value ?> </span>
															</label>
														</div>
													<?php } ?>
												</div>
											</div>
										</div>

										<div class="card">
											<div class="card-header">
												<h6 class="card-title m-0">Static Params</h6>
											</div>
											<div class="card-body p-0">
												<div class="static-params table-responsive">
													<table class="table table-striped table-bordered ">
														<thead>
															<tr>
																<td>Param Key</td>
																<td>Param Value</td>
																<td width="50px">#</td>
															</tr>
														</thead>
														<tbody></tbody>
														<tfoot>
															<tr>
																<td colspan="3"><button class="pull-right btn btn-sm btn-primary add-static-params" type="button">Add</button></td>
															</tr>
														</tfoot>
													</table>
												</div>
											</div>
										</div>

										<script type="text/javascript">
											$(".add-static-params").click(function(){
												addStaticParam('','');
											})

											<?php foreach ($marketpostback_static as $key => $value) {
												echo "addStaticParam('". $value['key'] ."','". $value['value'] ."');";
											} ?>

											var addStaticParamIndex = 0;
											function addStaticParam(key,val) {
												var html = `<tr>
														<td>
															<input type="text" value='${key}' name="marketpostback[static][${addStaticParamIndex}][key]" placeholder="Param Key" class="form-control">
														</td>
														<td>
															<input type="text" name="marketpostback[static][${addStaticParamIndex}][value]" value='${val}' placeholder="Param Value" class="form-control">
														</td>
														<td>
															<button class="pull-right btn btn-sm btn-danger remove-static-params" type="button"><i class="fa fa-trash"></i></button>
														</td>
													</tr>`;

												addStaticParamIndex++;
												$(".static-params tbody").append(html);
											}

											$(".static-params").delegate(".remove-static-params","click",function(){
												$(this).parents("tr").remove();
											})
										</script>
									

									</div>
								</div>
							</div>

							<div class="tab-pane p-3 active show" id="market_vendor-setting" role="tabpanel">

								<div class="form-group">
									<label class="control-label">Vendor Store Status</label>
									<select class="form-control" name="market_vendor[marketvendorstatus]">
										<option value="0"><?= __('admin.disable') ?></option>
										<option value="1" <?= $market_vendor['marketvendorstatus'] ? 'selected' : '' ?>><?= __('admin.enable') ?></option>
									</select>
								</div>

								<h5>Program Settings</h5>
								<div class="row">
									<div class="col-sm-6">
										<div class="custom-card card">
											<div class="card-header"><p class="text-center"><?= __('admin.sale_settings') ?></p></div>

											<div class="card-body">
												<div class="row">
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label"><?= __('admin.commission_type') ?></label>
															<select name="market_vendor[commission_type]" class="form-control">
																<option value=""><?= __('admin.select_product_commission_type') ?></option>
																<option <?= ($market_vendor['commission_type'] == 'percentage') ? 'selected' : '' ?> value="percentage"><?= __('admin.percentage') ?></option>
																<option <?= ($market_vendor['commission_type'] == 'fixed') ? 'selected' : '' ?> value="fixed"><?= __('admin.fixed') ?></option>
															</select>
														</div>
													</div>
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label"><?= __('admin.commission_for_sale') ?> </label>
															<input class="form-control" name="market_vendor[commission_sale]" type="number" value="<?= isset($market_vendor) ? $market_vendor['commission_sale'] : '' ?>">
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="control-label"><?= __('admin.sale_status') ?></label>
													<div>
														<div class="radio radio-inline"> <label> <input type="radio" checked="" name="market_vendor[sale_status]" value="0"> <?= __('admin.disable') ?> </label> </div>
														<div class="radio radio-inline"> <label> <input <?= ($market_vendor['sale_status']) ? 'checked' : '' ?> type="radio" name="market_vendor[sale_status]" value="1"> <?= __('admin.enable') ?> </label> </div>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="col-sm-6">
										<div class="custom-card card">
											<div class="card-header"><p class="text-center"><?= __('admin.click_settings') ?></p></div>

											<div class="card-body">
												
												<div class="row">
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label"><?= __('admin.number_of_click') ?></label>
															<input class="form-control" name="market_vendor[commission_number_of_click]" type="number" value="<?= isset($market_vendor) ? $market_vendor['commission_number_of_click'] : '' ?>">
														</div>
													</div>
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label"><?= __('admin.amount_per_click') ?></label>
															<input class="form-control" name="market_vendor[commission_click_commission]" type="number" value="<?= isset($market_vendor) ? $market_vendor['commission_click_commission'] : '' ?>">
														</div>
													</div>
												</div>
												

												<div class="form-group">
													<label class="control-label"><?= __('admin.click_status') ?></label>
													<div>
														<div class="radio radio-inline"> <label> <input type="radio" checked="" name="market_vendor[click_status]" value="0"> <?= __('admin.disable') ?> </label> </div>
														<div class="radio radio-inline"> <label> <input type="radio" <?= ($market_vendor['click_status']) ? 'checked' : '' ?> name="market_vendor[click_status]" value="1"> <?= __('admin.enable') ?> </label> </div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 text-right">
						<button type="submit" class="btn btn-sm btn-primary btn-submit"><?= __('admin.save_settings') ?></button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</div>

<link href="<?php echo base_url(); ?>assets/js/summernote-0.8.12-dist/summernote-bs4.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>assets/js/summernote-0.8.12-dist/summernote-bs4.js"></script>


<script type="text/javascript">


$('.setting-nnnav li a').on('shown.bs.tab', function(event){
    var x = $(event.target).attr('href');         // active tab
	$(".btn-submit").hide();

    if(x != '#site-fronttemplate'){
    	$(".btn-submit").show();
    }
    localStorage.setItem("last_pill", x);
});
$("#setting-form").on('submit',function(){
	$("#setting-form .alert-error").remove();
	var affiliate_cookie = parseInt($(".input-affiliate_cookie").val());
	if(affiliate_cookie <= 0 || affiliate_cookie > 365){
		$(".input-affiliate_cookie").after("<div class='alert alert-danger alert-error'>Days Between 1 and 365</div>");
	}
	if($("#setting-form .alert-error").length == 0) return true;
	return false;
})

$(".btn-submit").on('click',function(evt){
    evt.preventDefault();
    var formData = new FormData($("#setting-form")[0]);

    $(".btn-submit").btn("loading");
    formData = formDataFilter(formData);
    $this = $("#setting-form");
    
    $.ajax({
        type:'POST',
        dataType:'json',
        cache:false,
        contentType: false,
        processData: false,
        data:formData,
        success:function(result){
            $(".btn-submit").btn("reset");
            $(".alert-dismissable").remove();

            $this.find(".has-error").removeClass("has-error");
            $this.find("span.text-danger").remove();
            
            if(result['location']){
                window.location = result['location'];
            }

            if(result['success']){
                $(".tab-content").prepend('<div class="alert mt-4 alert-info alert-dismissable">'+ result['success'] +'</div>');
                var body = $("html, body");
				body.stop().animate({scrollTop:0}, 500, 'swing', function() { });
            }

            if(result['errors']){
                $.each(result['errors'], function(i,j){
                    $ele = $this.find('[name="'+ i +'"]');
                    if($ele){
                        $ele.parents(".form-group").addClass("has-error");
                        $ele.after("<span class='d-block text-danger'>"+ j +"</span>");
                    }
                });
            }
        },
    })
    return false;
});

</script>
