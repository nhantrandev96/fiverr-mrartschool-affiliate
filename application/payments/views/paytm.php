<form action="<?php echo $action; ?>" method="POST" class="form-horizontal" id="paytm_form_redirect">
	<?php foreach($parameters as $k=>$v) { ?>
		<input type="hidden" name="<?php echo $k; ?>" value="<?php echo $v; ?>" />
	<?php } ?>
</form>

<div class="buttons">
	<div class="pull-right">
		<button type="button" class="btn btn-default" onclick='backCheckout()'>Back</button>
		<input type="button" value="Confirm" id="button-confirm" class="btn btn-primary" />
	</div>
</div>

<script type="text/javascript">
	$('#button-confirm').bind('click', function() {
		$('#paytm_form_redirect').submit();
	});
</script>