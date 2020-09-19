<div class="page-content-wrapper ">
	<div class="container">
		<h1><?= __('store.shipping_details') ?></h1>
		<form action="<?php echo base_url('store/shipping') ?>" class="form-horizontal" method="post" id="profile-frm" enctype="multipart/form-data">
			<div class="row">
				<div class="col-12">
					<div class="m-b-30">
						<div class="card-body">
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<label class="control-label"><?= __('store.country') ?></label>
										<?php $selected =  isset($shipping) ? $shipping['country_id'] : '' ?>
										<select class="form-control" name="country">
											<?php foreach ($country as $key => $value) { ?>
												<option <?= $selected == $value->id ? 'selected' : '' ?> value="<?= $value->id ?>"><?= $value->name ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<label class="control-label"><?= __('store.state') ?></label>
										<select class="form-control" name="state"></select>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<label class="control-label"><?= __('store.city') ?></label>
										<input class="form-control" name="city" type="text" value="<?= isset($shipping) ? $shipping['city'] : '' ?>">
									</div>
									<?php if($errors && isset($errors['city'])) { ?>
									<div class="text-danger"><?php echo $errors['city'] ?></div>
									<?php } ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label"><?= __('store.postal_code') ?></label>
										<input class="form-control" name="zip_code" type="text" value="<?= isset($shipping) ? $shipping['zip_code'] : '' ?>">
									</div>
									<?php if($errors && isset($errors['zip_code'])) { ?>
									<div class="text-danger"><?php echo $errors['zip_code'] ?></div>
									<?php } ?>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label"><?= __('store.phone_number') ?></label>
										<input class="form-control" name="phone" type="text" value="<?= isset($shipping) ? $shipping['phone'] : '' ?>">
									</div>
									<?php if($errors && isset($errors['phone'])) { ?>
									<div class="text-danger"><?php echo $errors['phone'] ?></div>
									<?php } ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
										<label class="control-label"><?= __('store.full_address') ?></label>
										<textarea class="form-control" name="address"><?= isset($shipping) ? $shipping['address'] : '' ?></textarea>
									</div>
									<?php if($errors && isset($errors['address'])) { ?>
									<div class="text-danger"><?php echo $errors['address'] ?></div>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="text-right">
							<button class="btn btn-default btn-success" id="update-profile" type="submit"><i class="fa fa-save"></i> <?= __('client.update_shipping') ?> </button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var selected_state = '<?= isset($shipping) ? $shipping['state_id'] : '' ?>';
	$(document).on('ready',function(){
		$('[name="country"]').trigger("change");
	});
	$(document).delegate('[name="country"]',"change",function(){
		$this = $(this);
		$.ajax({
			url:'<?= base_url('store/getState') ?>',
			type:'POST',
			dataType:'json',
			data:{id:$this.val()},
			beforeSend:function(){$this.prop("disabled",true);},
			complete:function(){$this.prop("disabled",false);},
			success:function(json){
				var html = '';
				$.each(json['states'], function(i,j){
					var s = '';
					if(selected_state && selected_state == j['id']){
						s = 'selected';selected_state = 0;
					}
					html += "<option "+ s +" value='"+ j['id'] +"'>"+ j['name'] +"</option>";
				})
				$('[name="state"]').html(html);
			},
		})
	})
</script>
