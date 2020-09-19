<div class="row">
			<div class="col-12">
				<div class="card m-b-30">
					<div class="card-header">
						<h4 class="card-title pull-left"><?= __("admin.registration_builder") ?></h4>
						<div class="pull-right">
							<button class="btn btn-primary save-form"><?= __('admin.save') ?></button>
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
								
					
								<script type="text/javascript" src="<?= base_url('assets/plugins/ui/jquery-ui.min.js') ?>"></script>
								<script type="text/javascript" src="<?= base_url('assets/plugins/registration_builder/js/form-builder.js') ?>"></script>

								<div id="build-wrap"></div>
								
								<div id="form-data" style='display:none'><?= htmlspecialchars($builder['registration_builder']) ?></div>

								<script type="text/javascript">
									const fbTemplate = document.getElementById('build-wrap');
									var fields = [
									    {
									      label: "Static Field",
									      type: "header",
									      subtype: "header",
									      icon: "",
									    }
									];
									var formBuilder = $(fbTemplate).formBuilder({
										fields:fields,
								     	typeUserAttrs: {
									        text: {
										        mobile_validation: {
											      label: 'Mobile Validation',
											      value: false,
											    }
									        }
								       	},
								       	disabledFieldButtons: {
										    header: ['remove','edit','copy']
									  	},
										disableFields:['button','autocomplete','file','header','hidden','paragraph'],
										disabledActionButtons:['clear','save','save'],
										disabledAttrs:['access','description','inline','other','rows','step','style','subtype','toggle']
									});

									setTimeout(function(){ 
										formBuilder.actions.setData($("#form-data").html());
									}, 1000);
									
								</script>
							</div>
						</div>
					</div>
				</div> 
			</div> 
		</div>

<script type="text/javascript">

	$(".save-form").on('click',function(){
		$this = $(this);
		$.ajax({
			url:'',
			type:'POST',
			dataType:'json',
			data:{
				registration_builder:formBuilder.actions.getData(),
			},
			beforeSend:function(){ $this.btn("loading"); },
			complete:function(){ $this.btn("reset"); },
			success:function(json){
				
			},
		})
	})
</script>