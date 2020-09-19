
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
			    
			    <div class="row top-panel">
					<span class="col-sm--2">
						<div class="share-store-list">
							<a class="btn btn-lg btn-default btn-success" href="<?php echo base_url("pagebuilder/addtheme") ?>"><?= __('admin.add_theme') ?> <i class="fa fa-edit cursors" aria-hidden="true"></i></a>
						</div>
					</span>
				</div>
				<br>
				<div class="table-rep-plugin">
					<div class="table-responsive b-0" data-pattern="priority-columns">
						<table id="tech-companies-1" class="table  table-striped">
							<thead>
								<tr>
									
									<th><?= __('admin.name') ?></th>
									<th style="width: 80px;"><?= __('admin.action') ?></th>
								</tr>  
							</thead> 
							<tbody>
								<?php foreach($pagebuilder_themes as $themes){ ?>
									<tr>
										<td><?php echo $themes['name'];?></td>
										
										<td class="txt-cntr">
											<a class="btn btn-sm btn-primary" onclick="return confirm('Are you sure you want to Edit?');" href="<?php echo base_url();?>pagebuilder/addtheme/<?php echo $themes['theme_id'];?>"><i class="fa fa-edit cursors" aria-hidden="true"></i></a>
											
											<button class="btn btn-sm btn-danger btn-delete2" data-id="<?php echo $themes['theme_id'] ?>"><i class="fa fa-trash-o cursors" aria-hidden="true"></i></button>
										
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

<script >
	$(".btn-delete2").on('click',function(){
		if(!confirm("Are you sure ?")) return false;
		$this = $(this);
		$.ajax({
			url: '<?php echo base_url("pagebuilder/deleteTheme") ?>',
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