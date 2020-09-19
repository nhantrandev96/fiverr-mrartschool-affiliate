<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-body">
			<h4 class="modal-title"><?php echo $title ?></h4>
          	<a href="<?php echo $link ?>">
				<?php if(isset($image)){ ?>
	                <img src="<?php echo $image ?>" class="img-fluid" alt="">
	            <?php } ?>
	            <div class="video">
	              <?php if(isset($video)){ echo $video; }  ?>
	            
	            </div>
          	</a>
            <p><?php echo $description ?></p>
		</div>
		<textarea style="opacity: 0;" name="" id="dummyInput"></textarea>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><?= __('user.close') ?></button>
			<button type="button" onclick="copyCode()" class="btn btn-primary"><?= __('user.copy_html') ?></button>
		</div>
	</div>
</div>
<script type="text/javascript">
	function copyCode() {
		var $html = $("<div>" +  $("#modal-image .modal-body").html() + "</div>");
		$html.find('[class]').removeAttr("class")
		$("#dummyInput").val($html.html());
	  	var copyText = document.getElementById("dummyInput");
	  	copyText.select();
	  	document.execCommand("copy");
	  	alert("<?= __('user.html_code_copied') ?>");
	}
</script>
