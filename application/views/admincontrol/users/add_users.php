<div class="form-horizontal" method="post" action=""  enctype="multipart/form-data">
			<div class="row">
				<div class="col-12">
					<div class="card m-b-30">
						<div class="card-body">
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

							<ul class="nav nav-tabs border-0">
							  <li class="active mr-1"><a data-toggle="tab" href="#user-edit" class="btn btn-primary">User</a></li>
							  <?php if($user['id'] > 0){ ?>
							  	<li><a data-toggle="tab" href="#add-transaction" class="btn btn-primary">Add Transaction</a></li>
							  <?php } ?>
							</ul>

							<br>

							<div class="tab-content">
							  <div id="user-edit" class="tab-pane active">
								<?= $html_form ?> 
								<button class="btn btn-block btn-default btn-success" id="update-user"><i class="fa fa-save"></i> <?= __('admin.submit') ?></button>
							  </div>
							  <?php if($user['id'] > 0){ ?>
								  <div id="add-transaction" class="tab-pane fade">
								    <h3>Add Transaction  </h3>
								    
								    <input type="hidden" name="user_id" class="input-transaction" value="<?= isset($user) ? $user['id'] : '' ?>">
								    <div class="form-group">
								    	<label class="control-label">Amount <small>(Total Commission <?= c_format($totals['unpaid_commition']) ?>)</small> </label>
								    	<input class="form-control input-transaction" type="number" name="amount" value="">
								    </div>

								    <div class="form-group">
								    	<label class="control-label">Comment</label>
								    	<input class="form-control input-transaction" type="text" name="comment" value="">
								    </div>

								    <button class="btn btn-primary add-transaction">Add Transaction</button>

								  </div>
								<?php } ?>
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
<script>
var state_id = '<?php echo $user->state ?>';

$("#Country").on('change',function(){
    var country = $(this).val();
    $.ajax({
        url: '<?php echo base_url('get_state') ?>',
        type: 'post',
        dataType: 'json',
        data: {
            country_id : country
        },
        success: function (json) {
            if(json){
                var html = '';
                $.each(json, function(k,v){
                    if(v.id == state_id){
                        html += '<option value="'+v.id+'" selected="selected">'+v.name+'</option>';
                    }else{
                        html += '<option value="'+v.id+'">'+v.name+'</option>';
                    }
                });
                $('#states').html(html);
            }
        }
    });
});
$("#Country").trigger('change');

$("#update-user").on('click',function(){
	$this = $(".reg_form");
	var is_valid = true;

    if(tel_input){
      var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
      is_valid = false;
      var errorInnerHTML = '';
      if ($("#phone").val().trim()) {
        if (tel_input.isValidNumber()) {
          is_valid = true;
          $("#phonenumber-input").val("+"+tel_input.selectedCountryData.dialCode + $("#phone").val().trim())
        } else {
          var errorCode = tel_input.getValidationError();
          errorInnerHTML = errorMap[errorCode];
        }
      } else {
        errorInnerHTML = 'The Mobile Number field is required.'
      }
      $("#phone").parents(".form-group").removeClass("has-error");
      $(".reg_form .text-danger").remove();

      if(!is_valid){
        $("#phone").parents(".form-group").addClass("has-error");
        $("#phone").parents(".form-group").find('> div').after("<span class='text-danger'>"+ errorInnerHTML +"</span>");
      }
    }

    if(is_valid){
		$.ajax({
			url:'',
			type:'post',
			dataType:'json',
			data:$(".reg_form").serialize(),
			beforeSend:function(){ $(".add-transaction").btn("loading") },
			complete:function(){ $(".add-transaction").btn("reset") },
			success:function(json){
				if(json['location']){
					window.location = json['location'];
				}

				$this.find(".has-error").removeClass("has-error");
				$this.find("span.text-danger").remove();
				if(json['errors']){
				    $.each(json['errors'], function(i,j){
				        $ele = $this.find('[name="'+ i +'"]');
				        if($ele){
				            $ele.parents(".form-group").addClass("has-error");
				            $ele.after("<span class='text-danger'>"+ j +"</span>");
				        }
				    })
				}	
			}
		})
    }
})

$(".add-transaction").on('click',function(){
	$this = $("#add-transaction");
	
	$.ajax({
		url:'<?= base_url("admincontrol/add_transaction") ?>',
		type:'post',
		dataType:'json',
		data:$(".input-transaction"),
		beforeSend:function(){ $(".add-transaction").btn("loading") },
		complete:function(){ $(".add-transaction").btn("reset") },
		success:function(json){
			if(json['location']){
				window.location = json['location'];
			}

			$this.find(".has-error").removeClass("has-error");
			$this.find("span.text-danger").remove();

			if(json['errors']){
			    $.each(json['errors'], function(i,j){
			        $ele = $this.find('[name="'+ i +'"]');
			        if($ele){
			            $ele.parents(".form-group").addClass("has-error");
			            $ele.after("<span class='text-danger'>"+ j +"</span>");
			        }
			    })
			}	
		}
	})
})
</script>