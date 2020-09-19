<br>
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
		<form class="form-horizontal" method="post" action=""  enctype="multipart/form-data">
			<div class="row sh">
				<div class="col-sm-8">
					<div class="card h-100">
						<div class="card-header"><h4 class="mt-0 header-title"><?= __('admin.all_product_images') ?></h4></div>
						<div class="card-body">
							<?php foreach($imageslist as $images){ ?>
								<div class="popup-gallery">
									<a class="pull-left" href="<?php echo base_url();?>assets/images/product/upload/thumb/<?php echo $images['product_media_upload_path'];?>">
										<div class="img-responsive">
											<img width="200px" height="200px" src="<?php echo base_url();?>/assets/images/product/upload/thumb/<?php echo $images['product_media_upload_path'];?>" ><br>
										</div>
									</a>
	                                <span class="delete_item" onclick="delete_image(<?php echo $images['product_media_upload_id'];?>);" >&times;</span>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="card h-100">
						<div class="card-header">
							<h4 class="card-title m-0"><?= __('admin.product_multiple_images') ?></h4>
						</div>
						<div class="card-body">
							<div class="form-group form-image-group">
								<div>
									<label class="control-label"><?= __('admin.product_multiple_images') ?></label><br>
									<div class="col-sm-9">
										<div class="fileUpload btn btn-sm btn-primary">
											<span><?= __('admin.choose_file') ?></span>
											<input id="gallery-photo-add" name="product_multiple_image[]" class="upload" type="file" multiple="">
										</div>
										<?php $product_multiple_image = 'no-image.jpg' ; ?>
										<img src="<?php echo base_url();?>assets/images/thumbs/<?php echo $product_multiple_image; ?>" id="multipleimage" class="thumbnail" border="0" width="220px">
									</div>
								</div>
							</div>
							<div class="fileUpload-gallery"></div>
							<button class="btn btn-block btn-default btn-success" id="update-product" type="submit"><i class="fa fa-save"></i> <?= __('admin.submit') ?></button>
						</div>
					</div>
				</div>
			</div>
		</form>
		
	

	<script type="text/javascript">

		var imagesPreview = function(input, placeToInsertImagePreview) {
	        if (input.files) {
	            var filesAmount = input.files.length;
	            for (i = 0; i < filesAmount; i++) {
	                var reader = new FileReader();

	                reader.onload = function(event) {
	                    $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
	                }

	                reader.readAsDataURL(input.files[i]);
	            }
	        }
	    };

	    $('#gallery-photo-add').on('change', function() {
	        imagesPreview(this, 'div.fileUpload-gallery');
	    });

        function delete_image(id){
            $.confirm({
                title: '<?= __('admin.delete_image') ?>',
                content: '<?= __('admin.do_you_want_to_delete_this_image') ?>',
                buttons: {
                    confirm: function () {
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url();?>usercontrol/delete_image",
                            data:'image_id='+id,
                            success: function(data){
                                location.reload();
                            }
                        });
                    },
                    cancel: function () {
                        $.alert('Canceled!');
                    }
                }
            });
        }
</script>
