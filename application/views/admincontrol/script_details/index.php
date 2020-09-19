<div class="card">
	<div class="card-header">
		<h6 class="m-0"><?= strtolower($licence['license']) == 'company site' ? 'Company Site' : 'Codecanyon' ?> License Details</h6>
	</div>
	
	<div class="card-body" >
		<div class="license-details row">
			<div class="col-sm-4">
				<div class='data-row'>
					<label>License Code</label>
					<span class="code"><?= $licence['code'] ?></span>
				</div>
			</div>
		    <div class="col-sm-4">
		    	<div class='data-row'>
					<label>Purchase Amount</label>
					<span><?= (float)$licence['amount'] ?> USD</span>
				</div>
			</div>
		    <div class="col-sm-4">
		    	<div class='data-row'>
					<label>Support Amount</label>
					<span><?= (float)$licence['support_amount'] ?> USD</span>
				</div>
			</div>
		    <div class="col-sm-4">
		    	<div class='data-row'>
					<label>Sold at</label>
					<span><?= $licence['sold_at'] ?></span>
				</div>
			</div>
		    <div class="col-sm-4">
		    	<div class='data-row'>
					<label>License Type</label>
					<span><?= $licence['license'] ?></span>
				</div>
			</div>
		    <div class="col-sm-4">
		    	<div class='data-row'>
					<label>Supported until</label>
					<span><?= $licence['supported_until'] ? $licence['supported_until'] : 'Free' ?></span>
				</div>
			</div>
		    <div class="col-sm-4">
		    	<div class='data-row'>
					<label>Buyer Username</label>
					<span><?= $licence['buyer'] ?></span>
				</div>
			</div>

			<div class="col-sm-4">
		    	<div class='data-row'>
					<label>Uninstall Script</label>
					<span>
						<button class="btn uninstall-script btn-danger btn-sm">Un-Install</button>
					</span>
				</div>
			</div>
			
		</div>
	</div>
</div>
<div class="card mt-4">
	<div class="card-header">
		<h6 class="m-0"><?= $product['name'] ?> Changelog</h6>
	</div>
	<div class="card-body" >
		<div class="change-history">
			<?php foreach ($versions as $key => $value) { ?>
				<div class="<?= $value['show_frame'] == "1" ? 'frame' : '' ?>">
					<h2><b>Version <?= $value['code'] ?></b> — <?= date('M d, Y',strtotime($value['date'])) ?></h2>
					<div class="b">
						<ul>
							<?php 
								$logs = json_decode($value['change_log'],1);
								foreach ($logs as $key => $log) {
									echo '<li>'. $log .'</li>';
								}
							?>
						</ul>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
	    $(".change-history ul li").each(function(i,el){
	    	$(el).html($(el).html().replace(new RegExp('NEW', 'gi'), "<strong class='text-success'>NEW</strong>"));
	    	$(el).html($(el).html().replace(new RegExp('ADDED', 'gi'), "<strong class='text-info'>ADDED</strong>"));
	    	$(el).html($(el).html().replace(new RegExp('IMPROVED', 'gi'), "<strong class='text-danger'>IMPROVED</strong>"));
	    	$(el).html($(el).html().replace(new RegExp('FIXED', 'gi'), "<strong class='text-primary'>FIXED</strong>"));
	    })
	});

	$(".uninstall-script").on("click",function(){
		Swal.fire({
		  title: 'Are you sure?',
		  html:`
		  	Once deleted, you need to install again script. You don\'t loss your settings and data if you use old file and database. <br><br><br><br>
		  	<div class="text-left  uninstall-script-form">
			  	<div class="form-group">
			  		<label class='control-label'> Admin Password </label>
			  		<input type='password' name='password' class='form-control'>
			  	</div>
			  	<div class="form-group">
			  		<label class='control-label'> Enter License</label>
			  		<input type='text' name='licence' class='form-control'>
			  	</div>
		  	</div>  	
		  	`,
		  showCancelButton: true,
		  confirmButtonText: 'Uninstall',
		  showLoaderOnConfirm: true,
		  preConfirm:  (login)  => {
		  	var data = {
		  		password: btoa($(".uninstall-script-form input[name=password]").val()),
		  		licence: btoa($(".uninstall-script-form input[name=licence]").val()),
		  	}

		    return fetch('<?= base_url('Installversion/uninstall_script') ?>/' + data.password + "/" + (data.licence ? data.licence : '00-00'))
		      .then(async response => {
		      	let json = await response.json();
		      	if (json['errors']) {
		      		$container = $(".uninstall-script-form");
					$container.find(".has-error").removeClass("has-error");
					$container.find("span.text-danger").remove();

				    $.each(json['errors'], function(i,j){
				        $ele = $container.find('[name="'+ i +'"]');
				        if($ele){
				            $ele.parents(".form-group").addClass("has-error");
				            $ele.after("<span class='text-danger'>"+ j +"</span>");
				        }
				    })
					
					throw new Error("Please fix errors")
		        }

		        return json;
		      })
		      .catch(error => {
		        Swal.showValidationMessage(error)
		      })
		  },
		  allowOutsideClick: () => !Swal.isLoading()
		}).then((result) => {
		  	if(result.value.success){
		  		window.location.href = '<?= base_url('/install') ?>';
		  	}
		})
	})
</script>