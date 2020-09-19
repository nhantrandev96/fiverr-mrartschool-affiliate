<?php if (isset($request)) { ?>
		
	<button onclick="payWithPaypal()" type="button" class="btn btn-info btn-lg">Pay with paypal</button>

	<script type="text/javascript">
		function payWithPaypal(){
			Swal.fire({
				title: 'Are you sure?',
				text: "Are you sure to pay with paypal ?",
				icon: 'info',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, Pay!'
			}).then((result) => {
				if(result.value){
					window.location.href='<?= base_url('payment/call_payment_function/paypal/doPayment/'. $request['id']) ?>';
				}
			})
		}
	</script>
<?php } ?>