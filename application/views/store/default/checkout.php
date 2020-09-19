<div class="container">
    <div class="row">
        <div class="col-sm-12">
			<br><h1><?= __('store.checkout') ?></h1><br><br>
			<?php if(!$is_logged){ ?>
			
				<div class="checkout-setp auth-step">
					<div class="step-head">
						<h4><?= __('store.personal_details') ?></h4>
					</div>
					<?php 
						$db =& get_instance(); 
        				$products = $db->Product_model; 
						$googlerecaptcha =$db->Product_model->getSettings('googlerecaptcha');

					?>
					<div class="step-body">
						<div class="row">
							<div class="col-sm-6">
								<h5 class="sub-title"><?= __('store.login_with_existing_account') ?></h5>
								<form id="login-form">
									<input class="form-control" name="store_checkout" type="hidden" value="1">
									<div class="form-group">
										<label class="control-label"><?= __('store.username') ?></label>
										<input class="form-control" name="username" type="text">
									</div>
									<div class="form-group">
										<label class="control-label"><?= __('store.password') ?></label>
										<input class="form-control" name="password" type="password">
									</div>
									<?php if (isset($googlerecaptcha['client_login']) && $googlerecaptcha['client_login']) { ?>
										<div class="form-group">
	                                        <div class="captch mb-3">
	                                            
	                                            <div class="g-recaptcha" id='client_login'></div>
	                                        </div>
	                                        <input type="hidden" name="captch_response">
										</div>
                                    <?php } ?>
                                    
									<div class="form-group text-right">
										<button class="btn btn-primary btn-submit"><?= __('store.login') ?></button>
									</div>
								</form>
							</div>
							<div class="col-sm-6">
								<h5 class="sub-title"><?= __('store.create_a_new_account') ?></h5>
								<form id="register-form">
									<input class="form-control" name="store_checkout" type="hidden" value="1">
									<div class="form-group">
										<label class="control-label"><?= __('store.first_name') ?></label>
										<input class="form-control" name="f_name" type="text">
									</div>
									<div class="form-group">
										<label class="control-label"><?= __('store.last_name') ?></label>
										<input class="form-control" name="l_name" type="text">
									</div>
									<div class="form-group">
										<label class="control-label"><?= __('store.username') ?></label>
										<input class="form-control" name="username" type="text">
									</div>
									
									<div class="form-group">
										<label class="control-label"><?= __('store.email') ?></label>
										<input class="form-control" name="email" type="email">
									</div>
									<link rel="stylesheet" href="<?= base_url('assets/plugins/tel/css/intlTelInput.css') ?>?v=<?= av() ?>">
									<script src="<?= base_url('assets/plugins/tel/js/intlTelInput.js') ?>"></script>
									<input type="hidden" name='<?= $name ?>' id="phonenumber-input" value="" class="form-control" placeholder="Phone Number"  >
									<div class="form-group">
										<label for="">Phone Number</label>
										<div>
											<input id="phone" type="text" name="phone" value="">
										</div>
									</div>
									<script type="text/javascript">
										var tel_input = intlTelInput(document.querySelector("#phone"), {
										  initialCountry: "auto",
										  utilsScript: "<?= base_url('/assets/plugins/tel/js/utils.js?1562189064761') ?>",
										  separateDialCode:true,
										  geoIpLookup: function(success, failure) {
										    $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
										      var countryCode = (resp && resp.country) ? resp.country : "";
										      success(countryCode);
										    });
										  },
										});
									</script>
									<div class="form-group">
										<label class="control-label"><?= __('store.password') ?></label>
										<input class="form-control" name="password" type="password">
									</div>
									<div class="form-group">
										<label class="control-label"><?= __('store.confirm_password') ?></label>
										<input class="form-control" name="c_password" type="password">
									</div>
									<?php if (isset($googlerecaptcha['client_register']) && $googlerecaptcha['client_register']) { ?>
										<div class="form-group">
	                                        <div class="captch mb-3">
	                                            <div class="g-recaptcha" id='client_register'></div>
	                                        </div>
	                                        <input type="hidden" name="captch_response">
										</div>
                                    <?php } ?>

                                    <?php if (
                                    	(isset($googlerecaptcha['client_register']) && $googlerecaptcha['client_register']) ||
                                    	(isset($googlerecaptcha['client_login']) && $googlerecaptcha['client_login']) 
                                    ) { ?>
                                    <script async defer src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit'></script>
                                    <script type="text/javascript" charset="utf-8">
                                    	var gcaptch = {};
									    var onloadCallback = function() {
									        var recaptchas = document.querySelectorAll('div[class=g-recaptcha]');

									        for( i = 0; i < recaptchas.length; i++) {
									            gcaptch[recaptchas[i].id] =  grecaptcha.render( recaptchas[i].id, {
									            'sitekey' : '<?= $googlerecaptcha['sitekey'] ?>',
									            });
									        }
									    }
									</script>
									<?php } ?>
									<div class="form-group text-right">
										<button class="btn btn-primary btn-submit"><?= __('store.register') ?></button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="step-footer"></div>
				</div>
				<?php } ?>
				<div class="checkout-setp cart-step">
					<div class="step-head">
						<h4> <?= __('store.purchase_of_details') ?></h4>
					</div>
					<div class="step-body">
						<div class="cart-loader"></div>
						<div class="cart-body"></div>
					</div>
					<div class="step-footer"></div>
				</div>
				<div class="non-confirm">
					<?php if($allow_shipping){ ?>
					<div class="checkout-setp shipping-step">
						<div class="step-head">
							<h4><?= __('store.shipping_details') ?></h4>
						</div>
						<div class="step-body">
							<?php if($show_blue_message){ ?>
								<div class="alert alert-info"><?= $shipping_error_message ?></div>
							<?php } ?>
							<?php if(isset($shipping_not_allow_error_message)){ ?>
								<div class="alert alert-danger">
									<?= $shipping_not_allow_error_message ?>
								</div>
							<?php } ?>
							<div class="cart-loader"></div>
							<div class="cart-body"></div>
						</div>
						<div class="step-footer"></div>
					</div>
					<?php } else{ ?>
						
					<?php } ?>
					<div class="checkout-setp">
						<div class="step-head">
							<h4><?= __('store.payment_methods') ?></h4>
						</div>
						<div class="step-body">
							<div class="dynamic-payment"></div>
							<br>
							<?php if($allow_upload_file){ ?>
								<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/jquery.uploadPreviewer.css') ?>?v=<?= av() ?>">
								<div class="form-group downloadable_file_div well" style="white-space: inherit;">
									<div class="file-preview-button btn btn-primary">
							            <?= __('store.order_upload_file') ?>
							            <input type="file" class="downloadable_file_input" multiple="">
							        </div>

							        <div id="priview-table" class="table-responsive" style="display: none;">
							            <table class="table table-hover">
							                <tbody></tbody>
							            </table>
							        </div>
							    </div>
							<?php } ?>

							<div class="checkbox">
								<label>
									<input type="checkbox" value="1" name="agree">
									<?= __('store.agree_text') ?>
								</label>
							</div>
							<br><div class="warning-div"></div><br>
							<br>
						</div>
						<div class="step-footer">
							<button class="btn btn-info confirm-order"><?= __('store.confirm_and_pay') ?></button>
						</div>
					</div>
				</div>

				<!-- <div class="checkout-setp">
					<div class="step-head">
						<h4><?= __('store.confirm_order') ?></h4>
					</div>
					<div class="step-body">
						<div class="payment-method-step">
							<br><div class="warning-div"></div><br>
							<div class="text-right">
								<span class="loading-submit"></span>
								<button class="btn btn-info confirm-order"><?= __('store.confirm_and_pay') ?></button>
							</div>
						</div>
					</div>
					<div class="step-footer"></div>
				</div> -->

				<div class="confirm-checkout">
					<div class="checkout-setp confirm-step">
						<div class="step-head">
							<h4><?= __('store.confirm_order') ?></h4>
						</div>
						<div class="step-body">
							<div class="">
								<div id="checkout-confirm"></div>
								
							</div>
						</div>
						<div class="step-footer"></div>
					</div>
				</div>
        </div>
    </div>
</div>
<script type="text/javascript">
	$('[name="payment_method"]').on('change',function(){
		if($(this).val() == 'bank_transfer'){
			$('.bank-transfer-instruction').slideDown();
		}else{
			$('.bank-transfer-instruction').slideUp();
		}
	});
	$(".cart-step").delegate(".btn-remove-cart","click",function(){
		$this = $(this);
		$.ajax({
			url:$this.attr("data-href"),
			type:'POST',
			dataType:'json',
			beforeSend:function(){},
			complete:function(){},
			success:function(json){
				getCart();				
			},
		})
		return false;
	});
	
	var xhr;
	$(".cart-step").delegate(".qty-input","change",function(){
		if(xhr && xhr.readyState != 4) xhr.abort();

		$this = $(this);
		xhr = $.ajax({
			url:'<?= $cart_update_url ?>',
			type:'POST',
			dataType:'json',
			data:$("#checkout-cart-form").serialize(),
			beforeSend:function(){},
			complete:function(){},
			success:function(json){
				getCart();				
			},
		})
		return false;
	})

	var cart_xhr;
	function getCart() {
		//$(".cart-step .cart-body").load('<?= base_url('store/checkout-cart') ?>');
		if(cart_xhr && cart_xhr.readyState !=4) cart_xhr.abort();

		cart_xhr = $.ajax({
			url:'<?= base_url('store/checkout-cart') ?>',
			type:'POST',
			dataType:'html',
			beforeSend:function(){},
			complete:function(){},
			success:function(html){
				$(".cart-step .cart-body").html(html);
			},
		})
	}
	function getShipping() {
		$(".shipping-step .cart-body").load('<?= base_url('store/checkout_shipping') ?>');
	}
	/*function getConfirm() {
		$("#checkout-confirm").load('<?= base_url('store/checkout_confirm') ?>');
	}*/

	function getPaymentMethods(){
		$.ajax({
			url:'<?= base_url('store/get_payment_mothods') ?>',
			type:'POST',
			dataType:'json',
			data:{
				data:$("#checkout-cart-form").serialize(),
			},
			beforeSend:function(){},
			complete:function(){},
			success:function(json){
				$(".dynamic-payment").html(json['html']);
			},
		})
	}
	<?php if(!$allow_shipping){ ?>
		getCart();
	<?php } ?>
	getShipping();getPaymentMethods();
	//getConfirm();
	$('.shipping-step').delegate('[name="country"]',"change",function(){
		$this = $(this);
		$.ajax({
			url:'<?= base_url('store/getState') ?>',
			type:'POST',
			dataType:'json',
			data:{id:$this.val(),checkShipping:true},
			beforeSend:function(){$('[name="state"]').prop("disabled",true);},
			complete:function(){$('[name="state"]').prop("disabled",false);},
			success:function(json){
				$(".shipping-warning").html('');

				var html = '<option value="">Select State</option>';
				$.each(json['states'], function(i,j){
					var s = '';
					if(selected_state && selected_state == j['id']){
						s = 'selected';selected_state = 0;
					}
					html += "<option "+ s +" value='"+ j['id'] +"'>"+ j['name'] +"</option>";
				})
				$('[name="state"]').html(html);

				/*if(json['shipping_error_message'] && json['allow_shipping'] == 0){
					$(".shipping-warning").html('<div class="alert alert-danger">'+ json['shipping_error_message'] +'</div>');
				}*/

				getCart();
			},
		})
	})
	
	$(".confirm-order").on('click',function(){
		$this = $(this);
		$container = $(".checkout-setp");		 
		var formData = new FormData();

		$container.find("input[type=text],input[type=file],select,input[type=checkbox]:checked,input[type=radio]:checked,textarea").each(function(i,j){
			formData.append($(j).attr("name"),$(j).val());
		})
		if(typeof fileArray != 'undefined'){
			$.each(fileArray, function(i,j){ formData.append("downloadable_file[]", j.rawData); });
		}
		
		formData = formDataFilter(formData);

		$.ajax({
			url:'<?= $base_url ?>confirm_order',
			type:'POST',
			dataType:'json',
			cache:false,
            contentType: false,
            processData: false,
            data:formData,
            xhr: function (){
                var jqXHR = null;

                if ( window.ActiveXObject ){
                    jqXHR = new window.ActiveXObject( "Microsoft.XMLHTTP" );
                }else {
                    jqXHR = new window.XMLHttpRequest();
                }
                
                jqXHR.upload.addEventListener( "progress", function ( evt ){
                    if ( evt.lengthComputable ){
                        var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
                        console.log( 'Uploaded percent', percentComplete );
                        $('.loading-submit').text(percentComplete + "% Loading");
                    }
                }, false );

                jqXHR.addEventListener( "progress", function ( evt ){
                    if ( evt.lengthComputable ){
                        var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
                        $('.loading-submit').text("Save");
                    }
                }, false );
                return jqXHR;
            },
			beforeSend:function(){$this.btn("loading");},
			complete:function(){$this.btn("reset");},
			success:function(result){
				$container.find(".has-error").removeClass("has-error");
				$container.find("span.text-danger,.alert-danger").remove();
				$('.loading-submit').hide();
				
				/*if(result['success']){}
				if(result['location']){
					window.location = result['location']
				}*/
				if(result['confirm']){
					$("#checkout-confirm").html(result['confirm']);
					$(".confirm-checkout").show();
					$(".non-confirm").hide();
				}
				if(result['error']){
					$(".warning-div").html('<div class="alert alert-danger">'+ result['error'] +'</div>');
				}
				if(result['errors']){
				    $.each(result['errors'], function(i,j){
				        $ele = $container.find('[name="'+ i +'"]');
				        if($ele){
				            $ele.parents(".form-group").addClass("has-error");
				            $ele.after("<span class='text-danger'>"+ j +"</span>");
				        }
				    })
				}
			},
		})
	});

	function backCheckout(){
		$("#checkout-confirm").html('');
		$(".confirm-checkout").hide();
		$(".non-confirm").show();
	}

	$("#login-form").on('submit',function(){
		$this = $(this);
		$.ajax({
			url:'<?= $base_url ?>ajax_login',
			type:'POST',
			dataType:'json',
			data:$this.serialize(),
			beforeSend:function(){$this.find(".btn-submit").btn("loading");},
			complete:function(){$this.find(".btn-submit").btn("reset");},
			success:function(result){
				$this.find(".has-error").removeClass("has-error");
				$this.find("span.text-danger").remove();
				
				if(result['success']){
					location = '<?= $checkout_url ?>';
				}

				if(result['errors']){
				    $.each(result['errors'], function(i,j){
				    	if(i=='captch_response'){
				    		if(typeof gcaptch['client_login'] != 'undefined'){
				    			grecaptcha.reset(gcaptch['client_login']);
				    		}
				    	}

				        $ele = $this.find('[name="'+ i +'"]');
				        if($ele){
				            $ele.parents(".form-group").addClass("has-error");
				            $ele.after("<span class='text-danger'>"+ j +"</span>");
				        }
				    })
				}
			},
		})
		return false;
	})
	$("#register-form").on('submit',function(){
		$this = $(this);

		var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
		is_valid = false;
		var errorInnerHTML = '';
		if ($("#phone").val().trim()) {
			if (tel_input.isValidNumber()) {
				is_valid = true;
				$("#phonenumber-input").val("+"+tel_input.selectedCountryData.dialCode + $("#phone").val().trim())
			} else {
				var errorCode = tel_input.getValidationError();
				errorInnerHTML = errorMap[errorCode];
			}
		} else {
			errorInnerHTML = 'The Mobile Number field is required.'
		}
		$("#phone").parents(".form-group").removeClass("has-error");
		$("#register-form .text-danger").remove();

		if(!is_valid){
			$("#phone").parents(".form-group").addClass("has-error");
			$("#phone").parents(".form-group").find('> div').after("<span class='text-danger'>"+ errorInnerHTML +"</span>");
			return false;
		}
		
		$.ajax({
			url:'<?= $base_url ?>ajax_register',
			type:'POST',
			dataType:'json',
			data:$this.serialize(),
			beforeSend:function(){$this.find(".btn-submit").btn("loading");},
			complete:function(){$this.find(".btn-submit").btn("reset");},
			success:function(result){
				$this.find(".has-error").removeClass("has-error");
				$this.find("span.text-danger").remove();
				if(result['success']){
					location = '<?= $checkout_url ?>';
				}
				
				if(result['errors']){
				
				    $.each(result['errors'], function(i,j){
				    	if(i=='captch_response'){
				    		if(typeof gcaptch['client_register'] != 'undefined'){
				    			grecaptcha.reset(gcaptch['client_register']);
				    		}
				    	}

				        $ele = $this.find('[name="'+ i +'"]');
				        if($ele){
				            $ele.parents(".form-group").addClass("has-error");
				            $ele.after("<span class='text-danger'>"+ j +"</span>");
				        }
				    })
				}
			},
		})
		return false;
	})
</script>

<script type="text/javascript">
	var fileArray = [];
    $('.downloadable_file_input').on('change',function(e){
        $.each(e.target.files, function(index, value){
            var fileReader = new FileReader(); 
            fileReader.readAsDataURL(value);
            fileReader.name = value.name;
            fileReader.rawData = value;
            fileArray.push(fileReader);
        });

        render_priview();
    });

    var getFileTypeCssClass = function(filetype) {
        var fileTypeCssClass;
        fileTypeCssClass = (function() {
            switch (true) {
                case /image/.test(filetype): return 'image';
                case /video/.test(filetype): return 'video';
                case /audio/.test(filetype): return 'audio';
                case /pdf/.test(filetype): return 'pdf';
                case /csv|excel/.test(filetype): return 'spreadsheet';
                case /powerpoint/.test(filetype): return 'powerpoint';
                case /msword|text/.test(filetype): return 'document';
                case /zip/.test(filetype): return 'zip';
                case /rar/.test(filetype): return 'rar';
                default: return 'default-filetype';
            }
        })();
        return fileTypeCssClass;
    };

    function render_priview() {
        var html = '';

        $.each(fileArray, function(i,j){
            html += '<tr>';
            html += '    <td width="70px"> <div class="upload-priview up-'+ getFileTypeCssClass(j.rawData.type) +'" ></div></td>';
            html += '    <td>'+ j.name +'</td>';
            html += '    <td width="70px"><button type="button" class="btn btn-danger btn-sm remove-priview" onClick="removeTr(this)" data-id="'+ i +'" >Remove</button></td>';
            html += '</tr>';
        })

        $("#priview-table tbody").html(html);
        if(html) {
        	$("#priview-table").show();
        } else {
        	$("#priview-table").hide();
        }
    }

    function removeTr(t){
        if(!confirm("Are you sure ?")) return false;

        var index = $(t).attr("data-id");
        fileArray.splice(index,1);
        render_priview()
    }
</script>