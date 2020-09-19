<?php include(APPPATH.'/views/usercontrol/login/multiple_pages/header.php'); ?>

<div class="jumbotron jumbotron-fluid before-nav-spacer">
  <div class="container section-title">
    <h2>Contact Us</h2>
    <p>This is a modified jumbotron that occupies the entire horizontal space of its parent.</p>
  </div>
</div>

<div class="container py-5">
    <a href="<?= site_url('/') ?>" class="back-btn"><i class="fas fa-arrow-circle-left mr-2"></i>Back To Home</a>
    <div class="row h-100">
        <div class="col-lg-6">
            <form id="mail-form" class="mb-4">
            	<input type="hidden" name="send_contact_form">
				<div class="row">
					<div class="col-lg-12">
						<div class=" m-b-30">
							<div class="form-group">
								<label class="control-label">Email Address</label>
								<input type="text" name="email" class="form-control" value="">
							</div>

							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label">First Name</label>
										<input type="text" name="fname" class="form-control" value="">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label">Last Name</label>
										<input type="text" name="lname" class="form-control" value="">
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label">Phone Number</label>
								<input type="text" name="phone" class="form-control" value="">
							</div>

							<div class="form-group">
								<label class="control-label">Subject</label>
								<input type="text" name="subject" class="form-control">
							</div>

							<div class="form-group">
								<label class="control-label">Body</label>
								<textarea rows="10"  name="body" class="form-control"></textarea>
							</div>
						</div>
					</div>
					<div class="col-lg-12">
					    <button class="btn btn-primary btn-submit w-100">Send Mail</button>
					</div>
				</div>
			</form>
        </div>
        <div class="col-lg-6 pt-4">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d55565170.29301636!2d-132.08532758867793!3d31.786060306224!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x54eab584e432360b%3A0x1c3bb99243deb742!2sUnited%20States!5e0!3m2!1sen!2sph!4v1592929054111!5m2!1sen!2sph" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
            <div class="row pt-4">
                <div class="col-1">
                    <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                </div>
                <div class="col-6">
                    <p>The U.S. is a country of 50 states covering a vast swath of North America</p>
                </div>
            </div>
            <div class="row">
                <div class="col-1">
                    <i class="fas fa-phone-square-alt fa-2x text-primary"></i>
                </div>
                <div class="col-6">
                    <p>+999 999 999</p>
                </div>
            </div>
            <div class="social-icons mt-3">
                <a href=""><i class="fab fa-youtube"></i></a>
                <a href=""><i class="fab fa-facebook-f"></i></a>
                <a href=""><i class="fab fa-twitter"></i></a>
                <a href=""><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	(function ($) {
        $.fn.btn = function (action) {
            var self = $(this);
            if (action == 'loading') { $(self).addClass("btn-loading"); }
            if (action == 'reset') { $(self).removeClass("btn-loading"); }
        }
    })(jQuery);

	$("#mail-form").on('submit',function(evt){
	    evt.preventDefault();	    
    	var formData = new FormData($("#mail-form")[0]);
	    $(".btn-submit").btn("loading");
	    $this = $("#mail-form");

	    $.ajax({
	        type:'POST',
	        dataType:'json',
	        cache:false,
	        contentType: false,
	        processData: false,
	        data:formData,
	        success:function(result){
	            $(".btn-submit").btn("reset");
	            $(".alert-dismissable").remove();

	            $this.find(".has-error").removeClass("has-error");
	            $this.find(".is-invalid").removeClass("is-invalid");
	            $this.find("span.text-danger").remove();	            

	            if(result['success']){
	                $("#mail-form").prepend('<div class="alert mb-4 alert-info alert-dismissable">'+ result['success'] +'</div>');
	                var body = $("html, body");
	                $("#mail-form")[0].reset()
					body.stop().animate({scrollTop:0}, 500, 'swing', function() { });
	            }

	            if(result['errors']){
	                $.each(result['errors'], function(i,j){
	                    $ele = $this.find('[name="'+ i +'"]');
	                    if(!$ele.length){ 
	                    	$ele = $this.find('.'+ i);
	                    }
	                    if($ele){
	                        $ele.addClass("is-invalid");
	                        $ele.parents(".form-group").addClass("has-error");
	                        $ele.after("<span class='d-block text-danger'>"+ j +"</span>");
	                    }
	                });

					errors = result['errors'];
					$('.formsetting_error').text(errors['formsetting_recursion_custom_time']);
					$('.productsetting_error').text(errors['productsetting_recursion_custom_time']);
	            }
	        },
	    });
		
	    return false;
	});
</script>
<?php include(APPPATH.'/views/usercontrol/login/multiple_pages/footer.php'); ?>
