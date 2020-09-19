<div class="stepwizard col-md-offset-3">
	<div class="stepwizard-row setup-panel">
	  <ul>
	  	<li><span onclick="getStep(1)" data-step='1' data-toggle="tooltip" title="General settings">1</span></li>
	  	<li><span onclick="getStep(2,1)" data-step='2' data-toggle="tooltip" title="Admin Email">2</span></li>
	  	<li><span onclick="getStep(3,2)" data-step='3' data-toggle="tooltip" title="Mail Email">3</span></li>
	  	<li><span onclick="getStep(4,3)" data-step='4' data-toggle="tooltip" title="Currency & Language" 
	  		class="<?= isset($missing['currency']) ? 'step-alert' : '' ?>">4</span></li>
	  	<li><span onclick="getStep(5,4)" data-step='5' data-toggle="tooltip" title="Change Password">5</span></li>
	  	<li><span onclick="getStep(6,5)" data-step='6' data-toggle="tooltip" title="Thank You">6</span></li>
	  </ul>
	</div>

	<div class="stepwizard-body">
		<div class="row">
			<div class="col-sm-3"></div>	
			<div class="col-sm-6">
				<div class="stepwizard-body-inner">
					
				</div>
			</div>	
			<div class="col-sm-3"></div>	
		</div>
	</div>
</div>


<script type="text/javascript">
	
	function getStep(number,save) {
		$this = $(this);

		var formData = new FormData();
		if($("#stepwizard-form").length){
			formData = new FormData($("#stepwizard-form")[0]);
		}

		formData = formDataFilter(formData);
        formData.append("number", number);
        formData.append("save", save);

        $(".stepwizard-row ul li span[data-step="+ number +"]").html("*");
       
		$.ajax({
			url:'<?= base_url("firstsetting/get_step") ?>',
			type:'POST',
			dataType:'json',
			data:formData,
			processData: false,
			contentType: false,
			beforeSend:function(){
				
			},
			complete:function(){
			
			},
			success:function(json){
				$(".stepwizard-row ul li span[data-step="+ number +"]").html(number);

				if(json['html']){
					$(".stepwizard-row ul li span").removeClass("active");
					$(".stepwizard-row ul li span[data-step="+ number +"]").addClass("active");
					
					$(".stepwizard-body-inner").html(json['html'])
				}

				$container = $("#stepwizard-form");
				$container.find(".has-error").removeClass("has-error");
				$container.find("span.text-danger").remove();
				
				if(json['errors']){
				    $.each(json['errors'], function(i,j){
				        $ele = $container.find('[name="'+ i +'"]');
				        if($ele){
				            $ele.parents(".form-group").addClass("has-error");
				            $ele.after("<span class='text-danger'>"+ j +"</span>");
				        }
				    })
				}
			},
		})
	}

	<?php if(isset($missing['currency'])){ ?>
		getStep(4)
	<?php } else { ?>
		getStep(1)
	<?php } ?>
</script>