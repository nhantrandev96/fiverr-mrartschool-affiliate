<button class="btn btn-default" onclick='backCheckout()'>Back</button>
<button class="btn btn-primary" id="button-confirm">Confirm</button>

<script type="text/javascript">
	$("#button-confirm").click(function(){
		$this = $(this);
		$.ajax({
			url:'<?= $base_url ?>/store/confirm_payment',
			type:'POST',
			dataType:'json',
			data:{
				comment:$('textarea[name="comment"]').val(),
			},
			beforeSend:function(){
				$this.btn("loading");
			},
			complete:function(){
				$this.btn("reset");
			},
			success:function(json){
				if(json['redirect']){
					window.location = json['redirect'];
				}
				if(json['warning']){
					alert(json['warning'])
				}
			},
		})
	})
</script>

