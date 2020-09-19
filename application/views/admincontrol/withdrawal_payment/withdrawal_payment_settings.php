<div class="card">
	<div class="card-header">
		<h6 class="card-title pull-left m-0">Settings <small><?= $details['title'] ?></small></h6>
		<div class="card-options pull-right">
			<button class="btn btn-primary btn-sm btn-submit">Save Settings</button>
		</div>
	</div>
	<div class="card-body">
		<form method="post" action="" id='form-setting'>
			<div class="form-group">
				<label class="form-control-label">Status</label>
				<select  name="status" class="form-control">
					<option value="0">Disabled</option>
					<option value="1" <?= $setting_data['status'] == "1" ? 'selected' : '' ?>>Enabled</option>
				</select>
			</div>

			<?= $html  ?>
		</form>
	</div>
</div>

<script type="text/javascript">
	$(".btn-submit").click(function(){
		$this = $(this);

		$.ajax({
			url:'<?= base_url("admincontrol/withdrawal_payment_gateways_setting_save/". $details['code']) ?>',
			type:'POST',
			dataType:'json',
			data:$("#form-setting").serialize(),
			beforeSend:function(){
				$this.btn("loading");
			},
			complete:function(){
				$this.btn("reset");
			},
			success:function(json){
				$container = $("#form-setting");
				$container.find(".is-invalid").removeClass("is-invalid");
				$container.find("span.invalid-feedback").remove();

				if (json['redirect']) {
					window.location.href=json['redirect'];
				}
				
				if(json['errors']){
				    $.each(json['errors'], function(i,j){
				        $ele = $container.find('[name="'+ i +'"]');
				        if($ele){
				            $ele.addClass("is-invalid");
				            if($ele.parent(".input-group").length){
				                $ele.parent(".input-group").after("<span class='invalid-feedback'>"+ j[0] +"</span>");
				            } else{
				                $ele.after("<span class='invalid-feedback'>"+ j[0] +"</span>");
				            }
				        }
				    })
				}
			},
		})
	})
</script>