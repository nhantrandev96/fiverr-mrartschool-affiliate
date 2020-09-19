<form class="form-horizontal" method="post" action=""  enctype="multipart/form-data" id="category-form">
	<div class="row">
		<div class="col-12">
			<div class="card m-b-30">
				<div class="card-header">
					<h5 class="pull-left"><?= __('admin.edit_category') ?></h5>
					<div class="pull-right">
						<button type="submit" class="btn-submit btn-primary btn"><?= __('admin.save') ?></button>
					</div>
				</div>
				<div class="card-body">
					<input type="hidden" name="category_id" value="<?= isset($category['id']) ? $category['id'] : '' ?>">
					<div class="row">
						<div class="col-sm-8">
							<div class="form-group">
								<label class="control-label"><?= __('admin.category_name') ?></label>
								<input type="text" name="name" class="form-control" value="<?= isset($category['name']) ? $category['name'] : '' ?>">
							</div>

							<div class="form-group">
								<label class="control-label"><?= __('admin.parent_category') ?></label>
								<select name="parent_id" class="form-control">
									<option value="">-- None --</option>
									<?php foreach ($categories as $key => $value) { ?>
										<option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
									<?php } ?>
								</select>
							</div>

							<div class="form-group">
								<label class="control-label"><?= __('admin.description') ?></label>
								<textarea data-height='300' class="form-control summernote-img" name="description"><?= isset($category['description']) ? $category['description'] : '' ?></textarea>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label"><?= __('admin.image') ?></label>
								<div>
									<?php $category_image = $category['image'] != '' ? 'assets/images/product/upload/thumb/' . $category['image'] : 'assets/images/no_image_available.png'; ?>
									<img src="<?php echo base_url($category_image); ?>" id="featureImage" class="thumbnail" border="0" width="220px">
									<div>
										<div class="fileUpload btn btn-sm btn-primary">
											<span><?= __('admin.choose_file') ?></span>
											<input id="category_image" name="category_image" class="upload" type="file">
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
</form>

<script type="text/javascript">
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#featureImage').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}

	document.getElementById("category_image").onchange = function () { readURL(this); };

	$(".btn-submit").on('click',function(evt){
        evt.preventDefault();
        var formData = new FormData($("#category-form")[0]);
        formData = formDataFilter(formData);
        $this = $("#category-form");

        $(".btn-submit").btn("loading");
        
        $.ajax({
            type:'POST',
            dataType:'json',
            cache:false,
            contentType: false,
            processData: false,
            data:formData,
            error:function(){$(".btn-submit").btn("reset");},
            success:function(result){
                $(".btn-submit").btn("reset");
                $this.find(".has-error").removeClass("has-error");
                $this.find("span.text-danger").remove();
                
                if(result['location']){ window.location = result['location']; }

                if(result['errors']){
                    $.each(result['errors'], function(i,j){
                        $ele = $this.find('[name="'+ i +'"]');
                        if($ele){
                            $ele.parents(".form-group").addClass("has-error");
                            $ele.after("<span class='text-danger'>"+ j +"</span>");
                        }
                    });
                }
            },
        })
        return false;
    });
</script>
				