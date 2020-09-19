<div class="payment-list">
	<?php if(!$payment_methods){ ?>
		<div class="alert alert-info">Warning: No Payment options are available. Please contact the store owner for assistance!</div>
	<?php } ?>
	<?php foreach ($payment_methods as $key => $method) { ?>
		<div class="payment-method-step">
			<label>
				<input type="radio" name="payment_method" value="<?= $method['code'] ?>" >
				<div>
					<?php 
						if($method['icon']){ 
							echo '<img src="'. base_url($method['icon']) .'">';
						}
						if(isset($method['html']) && $method['html']){ 
							echo $method['html'];
						} 
					?>
				</div>
			</label>
		</div>
	<?php } ?>
</div>

<script type="text/javascript">
	$(".payment-method-step input[type=radio]").eq(0).prop("checked",1)
</script>