<form class="form-horizontal" method="post" action=""  enctype="multipart/form-data">
			<div class="row">
				<div class="col-12">
					<div class="card m-b-30">
						<div class="card-body">
							<?php if($theme_id) { ?>
							<div class="pull-right">
								<a class="btn btn-sm btn-default btn-success" href="<?php echo base_url("pagebuilder/addpage/$theme_id") ?>"><?= __('admin.add_page') ?> <i class="fa fa-edit cursors" aria-hidden="true"></i></a>
								<button type="button" class="btn btn-primary btn-sm" onclick="$('.toggle-div').slideToggle()">Edit Setting</button>	
							</div>
							<?php } ?>

							<div class="clearfix"></div>
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

							<div class="toggle-div">
								<br>
								<div class="well">
									<div class="row">
										<div class="col-sm-8">
											<div class="form-group">
												<label for="example-text-input" class="col-form-label d-block"><b>Theme Name</b></label>
												<input placeholder="Enter theme Name" name="name" value="<?php echo $theme['name']; ?>" class="form-control" required="required" type="text">
											</div>
										</div>
										<div class="col-sm-4">
											<label for="example-text-input" class="col-form-label d-block">&nbsp;</label>
											<button class="btn btn-default btn-success" id="update-product" type="submit"><i class="fa fa-save"></i>  Save Changes</button>
										</div>
									</div>	
								</div>
							</div>
							
							<br>
							<br>
							<?php if($theme_id) { ?>

							<div class="table-rep-plugin">
								<div class="table-responsive b-0" data-pattern="priority-columns">
									<table id="tech-companies-1" class="table  table-striped">
										<thead>
											<tr>
												
												<th><?= __('admin.name') ?></th>
												<th><?= __('admin.metatags_title') ?></th>
												<th><?= __('admin.metatags_desc') ?></th>
												<th><?= __('admin.metatags_keyword') ?></th>
												<th><?= __('admin.slug') ?></th>
												<th width="100px"><?= __('admin.status') ?></th>
												<th width="100px"><?= __('admin.menu_sort_order') ?></th>
												<th style="width: 180px;"><?= __('admin.action') ?></th>
											</tr>  
										</thead> 
										<tbody>
											<?php foreach($theme_page as $page){ ?>
												<tr>
													<td><?php echo $page['name'];?></td>
													<td><?php echo $page['meta_tag_title'];?></td>
													<td><?php echo $page['meta_tag_description'];?></td>
													<td><?php echo $page['meta_tag_keywords'];?></td>
													<td><?php echo $page['slug'];?></td>
													<td><?php echo $page['status'] ? 'Publish' : 'Draft';?></td>
													<td class="menu_sort_order" data-id="<?= $page['page_id'] ?>"><?= $page['sort_order'] ?></td>
													<td class="txt-cntr">
														<a class="btn btn-sm btn-primary" onclick="return confirm('Are you sure you want to Edit?');" href="<?php echo base_url();?>pagebuilder/addpage/<?php echo $page['theme_id'];?>/<?= $page['page_id'] ?>"><i class="fa fa-edit cursors" aria-hidden="true"></i></a>
														
														<button class="btn btn-sm btn-danger btn-delete2" data-id="<?php echo $page['page_id'] ?>"><i class="fa fa-trash-o cursors" aria-hidden="true"></i></button>
														<a target="_blank" class="btn btn-sm btn-primary"  href="<?php echo base_url();?>pagebuilder/addDesignNew/<?php echo $page['theme_id'];?>/<?= $page['page_id'] ?>">Design <i class="fa fa-edit cursors" aria-hidden="true"></i></a>
														<a class="btn btn-sm btn-primary" href="<?= base_url('page/'. $page['slug']) ?>" target='_blank'>Preview</a>
													</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>

								</div>

								<link href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" rel="stylesheet">
								<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
								<script type="text/javascript">
									$( "#tech-companies-1 tbody" ).sortable({
										update:function(){
											fill_order()
										}
									});
									var save_xhr;
									function fill_order() {
										var sort_order = []; 
										$( "#tech-companies-1 tbody tr" ).each(function(i,j){
											$(this).find('.menu_sort_order').text(i+1);
											sort_order.push($(this).find('.menu_sort_order').attr("data-id"));

											if(save_xhr && save_xhr.readyState != '4') save_xhr.abort();

											save_xhr = $.ajax({
												type:'POST',
												dataType:'json',
												data:{
													sort_order:sort_order,
													action:'sort_order',
												},
												beforeSend:function(){
													$(".loading-state").html("<span class='text-success'> Saving.. </span>");
												},
												complete:function(){
													$(".loading-state").html("");
												},
												success:function(json){
													
												},
											})
										});
									}

									
								</script>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</form>

<script >
	$(".btn-delete2").on('click',function(){
		if(!confirm("Are you sure ?")) return false;
		$this = $(this);
		$.ajax({
			url: '<?php echo base_url("pagebuilder/deletePage") ?>',
			type:'POST',
			dataType:'json',
			data:{
				id:$this.attr("data-id"),
			},
			beforeSend:function(){ $this.button("loading"); },
			complete:function(){ $this.button("reset"); },
			success:function(json){
				window.location.reload();
			},
		})
	})
</script>