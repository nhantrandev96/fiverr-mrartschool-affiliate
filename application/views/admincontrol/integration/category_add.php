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
						<div class="col-sm-12">
							<div class="form-group">
								<label class="control-label"><?= __('admin.category_name') ?></label>
								<input type="text" name="name" class="form-control" value="<?= isset($category['name']) ? $category['name'] : '' ?>">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<script type="text/javascript">
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
				