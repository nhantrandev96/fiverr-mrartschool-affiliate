<br>

<?php if($this->session->flashdata('success')){?>
	<div class="alert alert-success alert-dismissable my_alert_css">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<?php echo $this->session->flashdata('success'); ?> 
	</div>
<?php } ?>

<?php if($this->session->flashdata('error')){?>
	<div class="alert alert-danger alert-dismissable my_alert_css">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<?php echo $this->session->flashdata('error'); ?> 
	</div>
<?php } ?>

<form class="form-horizontal" method="post" action=""  enctype="multipart/form-data">
	<div class="row sh">
		<div class="col-sm-8">
			<div class="card h-100">
				<div class="card-header"><h4 class="header-title"><?= __('admin.all_videos_images') ?></h4></div>
				<div class="card-body">
					<?php foreach($videoimageslist as $images){ ?>
						<div class="popup-gallery">
							<a class="pull-left" href="<?php echo base_url();?>/assets/images/product/upload/thumb/<?php echo $images['product_media_upload_video_image'];?>">
								<div class="img-responsive">
									<img width="200px" height="200px" src="<?php echo base_url();?>/assets/images/product/upload/thumb/<?php echo $images['product_media_upload_video_image'];?>" ><br>
								</div>
							</a>
							<span class="delete_item" onclick="delete_image(<?php echo $images['product_media_upload_id'];?>);" >&times;</span>
						</div>
					<?php } ?>
				</div>
			</div> 
		</div>

		<div class="col-sm-4">
			<div class="card ">
				<div class="card-header">
					<h4 class="header-title"><?= __('admin.product_video') ?></h4>
				</div>
				<div class="card-body">
					<div class="form-group">
						<label class="control-label"><?= __('admin.product_video') ?> </label>
						<input placeholder="Enter your Product Video Link(Youtube/Vimeo URL}" name="product_media_upload_path" id="product_media_upload_path" class="form-control" value="" type="text">
						<small class="text-mute"><?= __('admin.example_youtube_link') ?></small>
					</div>
					<div class="form-group form-image-group">
						<div>
							<label class="control-label d-block"><?= __('admin.product_video_image') ?></label>
							<div class="fileUpload btn btn-sm btn-primary">
								<span><?= __('admin.choose_file') ?></span>
								<input id="video_thumbnail_image" onchange="readURL(this,'#multipleimage')" name="video_thumbnail_image" class="upload" type="file">
							</div>
							<?php $video_thumbnail_image = 'no-image.jpg' ; ?>
							<img src="<?php echo base_url();?>assets/images/thumbs/<?php echo $video_thumbnail_image; ?>" id="multipleimage" class="thumbnail"  border="0" width="220px">
						</div>
					</div>
					<button class="btn btn-block btn-default btn-success" id="update-product" type="submit"><i class="fa fa-save"></i> <?= __('admin.submit') ?></button>
				</div>
			</div>
		</div>

		
	</div>
</form>


	<script type="text/javascript">
		
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
							success: function(){
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