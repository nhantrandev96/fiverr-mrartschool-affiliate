<div class="alert alert-primary" role="alert">
  <p>Mail your users from one place! Any message you need, in one click.</p>
</div>


  <!-- Modal Info -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Users Newsletter Service</h4>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        <div class="modal-body">
            <ul class="list-group">
            <li class="list-group-item">You have the ability to mail all your users from this page, just choose your users and press on button "send mail", 
            you will see mail box that opening for you and from there just edit your mail , add a subject and send.</br>
            Important! make sure you set your smtp setting under Global setting>>Configuration>>Site setting.
            </br>
            <img class="zoom" src="<?php echo base_url(); ?>assets/guide_images/users_img1_send_mail.png" alt="" style="width:80%;height:80%; margin-right:0; margin-left:0;">
            </br>
            <img class="zoom" src="<?php echo base_url(); ?>assets/guide_images/users_img2_send_mail.png" alt="" style="width:80%;height:80%; margin-right:0; margin-left:0;">
            </li>
            </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
  
<div class="row">
	<div class="col-12">
		<div class="card m-b-30">
		    <div class="card-body">
		      
	            <button class="btn_info" data-toggle="modal" data-target="#myModal"><i class="fa fa-info"></i></button>
	            </br>
		
				<form accept="" action="" method="GET" id='search-form'>
					<div class="row">
						<div class="col-sm-3">
							<div class="form-group">
								<p class="header-title"><?= __('admin.select_country_filter') ?></p>
								<select class="form-control" name="country_id">
									<option value=""><?= __('admin.---select_country---') ?></option>
									<?php foreach ($country_list as $key => $value) { ?>
										<option value="<?= $value->id ?>" 
											<?= (isset($_GET['country_id']) && $_GET['country_id'] == $value->id) ? 'selected' : '' ?> 
										><?= $value->name ?> ( <?= $value->sortname ?> )</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label d-block">&nbsp;</label>
								<button type="button" class="btn btn-primary" onclick="getPage(1,this)">Filter</button>
								<button type="button" class="btn btn-default email-to" > Send Mail </button>
							</div>
						</div>
					</div>
				</form>

				<div class="selection-message d-none">
					All <span class="selected-count"></span> users on this page are selected. <a href="javascript:void(0)" class="select-all-users">Select all <span class="total-user"></span> users </a> <a href="javascript:void(0)" class="clear-selection">Clear selection</a>
				</div>

				<div class="message-box"></div>

                <div class="dimmer">
                	<div class="loader"></div>
                	<div class="dimmer-content">
						<div class="table-responsive b-0">
							<table class="table table-striped user-table">
								<thead>
									<tr>
										<th><input class="select-all" type="checkbox"></th>
										<th><?= __('admin.first_name') ?></th>
										<th><?= __('admin.last_name') ?></th>
										<th><?= __('admin.country') ?></th>
                                        <th><?= __('admin.email') ?></th>
										<th><?= __('admin.username') ?></th>
										<?php foreach ($data as $key => $value) { if($value['type'] == 'header') continue; ?>
											<th><?= $value['label'] ?></th>
										<?php } ?>
									</tr>  
								</thead> 
								<tbody></tbody>
								<tfoot>
									<tr>
										<td colspan="100%" class="text-right">
											<div class="pagination">
												<?= $pagination ?>
											</div>
										</td>
									</tr>
								</tfoot>
							</table>
						</div>
                	</div>
                </div>

			</div>
		</div> 
	</div>
</div>

<div id="affiliateMailModel" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?= __('admin.send_mail') ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form class="mail-form">
        	<div class="form-group">
        		<label class="control-label"><?= __('admin.to') ?> (<span class="selected-count"></span> users selected) </label>
        		<input type="text" name="to" readonly="" class="form-control">
        	</div>
        	<div class="form-group">
        		<label class="control-label"><?= __('admin.subject') ?></label>
        		<input type="subject" name="subject" class="form-control">
        	</div>

        	<div class="form-group">
        		<label class="control-label"><?= __('admin.message') ?></label>
        		<textarea -id="editor1" name="message" class="form-control summernote-img" data-height="300"></textarea>
        	</div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary send-affiliate-email"><?= __('admin.send') ?></button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><?= __('admin.close') ?></button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript" async="">
	var selected = {};
	var all_emails = [];
	$('.clear-selection').on('click',function(){
		selected = {};
		$(".selection-message").addClass('d-none');
		$(".select-all").prop("checked",  false);
		changeViews();
	});

	function changeViews() {
		$(".select-single").prop("checked",  false);

		if(Object.keys(selected).length == 0){
			$(".selection-message").addClass('d-none');
		} else {
			$(".selection-message").removeClass('d-none');
			$(".selected-count").text(Object.keys(selected).length);
		}

		$(".select-all-users").show();
		if(Object.keys(selected).length == all_emails.length){
			$(".select-all-users").hide();
		}

		$.each(selected, function(i,j){
			$('.select-single[value="'+ j +'"]').prop("checked",true);
		})
	}

	$('.select-all').on('change',function(){
		$(".select-single").prop("checked",  $(this).prop("checked"));

		$('.select-single').each(function(){
			var val = $(this).val();
			if($(this).prop("checked")){ selected[val]=val; } 
			else { delete selected[val]; }

		})
		changeViews();
	})

	$(".user-table").delegate(".select-single","change",function(){
		var status = $(this).prop("checked");

		if(!status) delete selected[$(this).val()]
		else selected[$(this).val()] = $(this).val();

		changeViews();
	})

	$(".select-all-users").on('click',function(){
		$this = $(this);
		$.ajax({
			type:'POST',
			dataType:'json',
			data:{action:'get_all_emails'},
			beforeSend:function(){ $this.btn("loading");},
			complete:function(){ $this.btn("reset"); },
			success:function(json){
				$.each(json['emails'],function(i,email){
					selected[email]= email;
				})
				$(".selected-count").text(Object.keys(selected).length);
				all_emails = json['emails'];

				changeViews();
			},
		})
	})

	$(".send-affiliate-email").on('click',function(){
		$this = $(this);
		$.ajax({
			url:'<?php echo base_url("admincontrol/sendAffiliateEmail") ?>/',
			type:'POST',
			dataType:'json',
			data:$("#affiliateMailModel form").serialize(),
			beforeSend:function(){$this.btn("loading");},
			complete:function(){$this.btn("reset");},
			success:function(json){
				if (json['success']) {
					$(".message-box").html('<div class="alert alert-success">'+ json['success'] +'</div>');
					$("#affiliateMailModel").modal("hide");
				}

				$container = $("#affiliateMailModel");
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
	});

	$(".email-to").on('click',function(){
		if($('.select-single:checked').length){
			$("#affiliateMailModel").modal("show");
			$("#affiliateMailModel input[name=to]").val( Object.keys(selected).join(",") );
			$("#affiliateMailModel input[name=subject]").val('');
			
			$('#affiliateMailModel .summernote-img').summernote('reset');
		} else {
			alert("Select at least one user to send mail");
		}
	});

	function getPage(page,t) {
		$this = $(t);
		$.ajax({
			url:'<?= base_url("admincontrol/userslistmail") ?>?per_page=' + page,
			type:'POST',
			dataType:'json',
			data:$("#search-form").serialize(),
			beforeSend:function(){$this.btn("loading");},
			complete:function(){$this.btn("reset");},
			success:function(json){
				$(".user-table tbody").html(json['html']);				
				$(".total-user").html(json['total']);				
				if(json['pagination']){
					$(".pagination").html(json['pagination'])
				}

				changeViews();
			},
		})
	}

	$(".pagination").delegate("a","click", function(e){
		e.preventDefault();
		getPage($(this).attr("data-ci-pagination-page"),$(this));
	})

	getPage(1)
	
</script>
	