<div class="card mt-3 mb-3">
	<div class="card-header">
		<h4 class="card-title pull-left"><?= __('user.all_notification') ?></h4>
		<div class="pull-right">
			<button class="btn btn-danger d-none btn-sm btn-delete-selected"><?= __('user.delete_selected') ?></button>
		</div>
	</div>
	<div class="card-body">
	    
	    <?php if ($notifications ==null) {?>
                    <div class="text-center">
                        <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png">
                        <h3 class="m-t-40 text-center text-muted"><?= __('user.no_notifications') ?></h3>
                    </div>
                <?php } else { ?>
                
                
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<td colspan="4">
							<div class="checkbox">
								<label>
									<input type="checkbox" value="" class="select_all">
									<?= __('user.select_all') ?>
								</label>
							</div>
						</td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($notifications as $key => $notification) { ?>
					<tr>
						<td width="50px">
							<?php if($notification['notification_view_user_id'] == $user_id){ ?>
								<div class="checkbox">
									<label><input type="checkbox" value="<?= $notification['notification_id'] ?>" name="notification[]" class="notification_id"></label>
								</div>
							<?php } ?>
						</td>
						<td width="50px">
							<div class="round">
								<i class="mdi mdi-cart-outline"></i>
							</div>
						</td>
						<td>
                        	<b><?php echo $notification['notification_title']; ?></b><br>
                        	<small class="text-muted"><?php echo $notification['notification_description']; ?></small>
						</td>
						<td width="80px">
							<a class="btn btn-primary" href="javascript:void(0)" onclick="shownofication(<?php echo $notification['notification_id'] . ',\'' . base_url('admincontrol') . $notification['notification_url'] . '\''; ?>)" class="dropdown-item notify-item"> <?= __('user.details') ?> </a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<?php } ?>
	</div>
	<div class="card-footer text-right">
		<ul class="pagination">
			<?php echo $pagination ?>
		</ul>
	</div>
</div>
<!-- <script type="text/javascript" src="http://code.jquery.com/jquery-1.12.0.min.js"></script> -->
<script type="text/javascript">
	$('.btn-delete-selected').on('click',function(){
		var ids = [];
		if($('.notification_id:checked').length > 0){
			$('.notification_id:checked').each(function(){
				ids.push($(this).val());
			})
			$this = $(this);
			$.ajax({
				type:'POST',
				dataType:'json',
				data:{delete_ids:ids},
				beforeSend:function(){
					$this.prop("disabled", true);
				},
				complete:function(){
					$this.prop("disabled", false);
				},
				success:function(json){
					window.location.reload();
				},
			})
		}
		else
		{
			alert("Select Notification");
		}
	})
	$('.select_all').on('click',function(){
		$('.notification_id').prop("checked", $(this).prop("checked") );
		chnageStatus()
	})
	$('.notification_id').change(function(){
		chnageStatus()
	})

	function chnageStatus() {
		if($('.notification_id:checked').length){
			$(".btn-delete-selected").removeClass("d-none");
		} else{
			$(".btn-delete-selected").addClass("d-none");
		}
	}
</script>