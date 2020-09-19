
<div class="row">
	<div class="col-12">
		<div class="card m-b-30">
			<div class="card-header">
				<h4 class="card-title pull-left"><?= __("admin.language") ?></h4>
				<div class="pull-right">
					<a href="<?= base_url('admincontrol/translation_edit/'.$lang['id'])  ?>" class="btn btn-primary add-new" id="<?= $lang['id'] ?>"><?= __("admin.add_new") ?></a>
				</div>
			</div>
			<div class="card-body">
				<div class="table-rep-plugin">
					<div class="table-responsive b-0" data-pattern="priority-columns">
						<?php if($this->session->flashdata('success')){?>								
						<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><?php echo $this->session->flashdata('success'); ?> </div>
						<?php } ?>	
						<?php if($this->session->flashdata('error')){?>								
						<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><?php echo $this->session->flashdata('error'); ?></div>
						<?php } ?>
						<table class="table">
							<thead>
								<tr>
									<th width="50px"><?= __("admin.flag") ?></th>
									<th><?= __("admin.name") ?></th>
									<th width="100px"></th>
									<th width="50px"><?= __("admin.status") ?></th>
									<th width="180px"></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($language as $lang){ ?>
									<tr>
										<td>
											<img src="<?= base_url('application/language/'. $lang['id'] .'/flag.png?t='. time()) ?>" style="height: 20px">
										</td>
										<td>
											<?= $lang['name'] ?>
											<?php if($lang['is_default']){ echo "<small> ( Default ) </small>"; } ?>
										</td>
										<td>
											<?= $lang['count']['missing'] ?> /
											<?= $language_count['all'] ?>
										</td>
										<td>
											<?= $lang['status'] == '0' ? 'Disabled' : 'Enabled' ?>
										</td>
										<td>
											
											<button class="btn btn-primary open-details">Import/Export</button>
											<a href="<?= base_url('admincontrol/translation_edit/'.$lang['id'])  ?>" class="btn btn-primary edit-button" id="<?= $lang['id'] ?>"><?= __("admin.edit") ?></a>
											<?php if($lang['id'] != 1){ ?>
												<a class="btn btn-primary edit-button" href="<?= base_url('admincontrol/translation/'.$lang['id'])  ?>"> <?= __("admin.translation") ?> </a>
											<?php } ?>
											<?php if($lang['is_default'] == '0' && $lang['id'] != 1){ ?>
												<button class="btn btn-danger detele-button" id="<?= $lang['id'] ?>"><?= __("admin.delete") ?></button>
											<?php } ?>
										</td>
									</tr>
									
									<tr style="display: none" class="details-tr">
										<td colspan="100%" class="p-0">
											<div class="well" style="border-radius: 0;margin: 0;">
												<div class="lang-uploader">
													<div class="text-right download-link">
														Click here to <a href="<?= base_url("admincontrol/language_export/".$lang['id']) ?>" target="_blank">Export Langauge</a> file.
													</div>
													<div class="file-input">
														<?php if($lang['id'] != 1){ ?>
															<form class="form-language">
																<input type="file" name="file">
																<input type="hidden" name="id" value="<?= $lang['id'] ?>">
																<button type="submit" class="btn btn-submit btn-primary">Import</button>
																<div class="lang-message"></div>
															</form>
														<?php } else { ?>
															<p>You can not import main language</p>
														<?php } ?>
													</div>
												</div>
											</div>
										</td>
									</tr>
									

								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div> 
	</div> 
</div>

<script type="text/javascript">
	$(".open-details").on('click',function(){
		$tr = $(this).parents("tr").next(".details-tr");

		if($tr.css("display") == 'none'){
			$tr.show();
		} else {
			$tr.hide();
		}
	})

	$(".detele-button").on('click',function(){
		if(!confirm("Are you sure ?")) return false;
		
		$this = $(this);
		$.ajax({
			url:'<?= base_url("admincontrol/delete_update_language") ?>',
			type:'POST',
			dataType:'json',
			data:{id:$this.attr("id")},
			beforeSend:function(){
				$this.prop("disabled",true);
			},
			complete:function(){
				$this.prop("disabled",false);
			},
			success:function(json){
				window.location.reload();
			},
		})
	})

	$(".form-language").submit(function(evt){
        evt.preventDefault();
        var formData = new FormData($(this)[0]);
        formData = formDataFilter(formData);
        $this = $(this);
        
        $this.find('.btn-submit').btn("loading");
        $.ajax({
            url:'<?= base_url('admincontrol/language_import') ?>',
            type:'POST',
            dataType:'json',
            cache:false,
            contentType: false,
            processData: false,
            data:formData,
            error:function(){
            	$this.find('.btn-submit').btn("reset");
            },
            success:function(json){
                $this.find('.btn-submit').btn("reset");
                $this.find(".lang-message").html('');

                if(json['success']){
                	$this.find(".lang-message").html('<div class="d-inline-block text-success">'+ json['success'] +'</div>');
                	$this[0].reset();
                }
                if(json['warning']){
                	$this.find(".lang-message").html('<div class="d-inline-block text-danger">'+ json['warning'] +'</div>');
                }
            },
        })
        return false;
    });
</script>