<div class="form-group">
	<label class="control-label">Choose Bank</label>
	<select name="bank_method" class="form-control">
		<option value="0"><?= substr($setting_data['bank_details'],0,50) ?>...</option>
		<?php
			if(isset($setting_data['additional_bank_details'])){
				$additional_bank_details = (array)json_decode($setting_data['additional_bank_details'],1);
				foreach ($additional_bank_details as $key => $value) {
					echo '<option value="'. ($key+1) .'">'. (substr($value,0,50)) .'...</option>';
				}
			}
		?>
	</select>
</div>

<div class="checkout-bank-details">
	<pre class="well d-none"><?= $setting_data['bank_details'] ?></pre>
	<?php
		if(isset($setting_data['additional_bank_details'])){
			$additional_bank_details = (array)json_decode($setting_data['additional_bank_details'],1);
			foreach ($additional_bank_details as $key => $value) {
				echo '<pre class="well d-none">'. $value .'</pre>';
			}
		}
	?>
</div>

<?php if($setting_data['proof']){ ?>
	<div class="form-group">
		<label class="control-label">Payment Proof</label>
		<input type="file" name="payment_proof" class="form-control">
	</div>
<?php } ?>

<button class="btn btn-default" onclick='backCheckout()'>Back</button>
<button class="btn btn-primary" id="button-confirm">Confirm</button>

<script type="text/javascript">
	$("select[name=bank_method]").change(function(){
		var val = $(this).val();
		$('.checkout-bank-details .well').addClass('d-none');
		$('.checkout-bank-details .well').eq(val).removeClass('d-none');
	});

	$("select[name=bank_method]").val('0').trigger("change");

	$("#button-confirm").click(function(){
		$this = $(this);
		var formData = new FormData();
		formData.append('comment', $('textarea[name="comment"]').val());
		formData.append('bank_method', $('select[name="bank_method"]').val());
		<?php if($setting_data['proof']){ ?>
			formData.append('payment_proof', ($('input[type=file][name=payment_proof]')[0] ? $('input[type=file][name=payment_proof]')[0].files[0] : null)); 
		<?php } ?>

		$.ajax({
			url:'<?= base_url("/store/confirm_payment") ?>',
			type:'POST',
			dataType:'json',
			data:formData,
			contentType: false,
    		processData: false,
			beforeSend:function(){$this.btn("loading");},
			complete:function(){$this.btn("reset");},
			success:function(json){
				if(json['redirect']){
					window.location = json['redirect'];
				}
				if(json['warning']){
					alert(json['warning'])
				}

				$container = $("#checkout-confirm");
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
	})
</script>

