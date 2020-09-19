<div class="card mt-3 mb-3">

	<div class="card-header">

		<h4 class="card-title pull-left"><?= __('admin.all_notification') ?></h4>

		<div class="pull-right">

			<a href="<?= base_url('admincontrol/notification?clearall=1') ?>" class="btn btn-sm clear_notification btn-danger"><?= __('admin.clear_notification') ?></a>

			<button class="btn btn-danger btn-sm delete-selected"><?= __('admin.delete_selected') ?></button>

		</div>

	</div>

	<div class="card-body">



        <?php if ($notifications == null) {?>

                <div class="text-center">

                <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:100px;">

                 <h3 class="m-t-40 text-center text-muted"><?= __('admin.no_notifications_found') ?></h3></div>

                <?php }

                else {?>

                

		<div class="table-responsive">

			<table class="table table-hover">

				<thead>

					<tr>

						<td colspan="4">

							<div class="checkbox">

								<label>

									<input type="checkbox" value="" class="select_all">

									<?= __('admin.select_all') ?>

								</label>

							</div>

						</td>

					</tr>

				</thead>

				<tbody>

					<?php foreach ($notifications as $key => $notification) { ?>

					<tr>

						<td width="50px">

							<div class="checkbox">

								<label><input type="checkbox" value="<?= $notification['notification_id'] ?>" name="notification[]" class="notification_id"></label>

							</div>

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

							<a class="btn btn-primary" href="javascript:void(0)" onclick="shownofication(<?php echo $notification['notification_id'] . ',\'' . base_url('admincontrol') . $notification['notification_url'] . '\''; ?>)" class="dropdown-item notify-item"> Details </a>

						</td>

					</tr>

					<?php } ?>

					<?php } ?>

				</tbody>

			</table>

		</div>

	</div>

	<div class="card-footer text-right">

		<ul class="pagination">

			<?php echo $pagination ?>

		</ul>

	</div>

</div>



<script type="text/javascript">



	$('.clear_notification').on('click',function(){

		if(!confirm("Are you sure you want to delete all notifications?")) return false;



		return true;

	});

	

	$('.delete-selected').on('click',function(){

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

	})

</script>