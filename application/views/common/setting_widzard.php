<div class="modal" id="setting-widzard"></div>
<div class="modal" id="log-widzard"></div>

<div class="modal" id="model-ajaxError">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <img src="<?= base_url('assets/images/ajax-warning.png') ?>">
        <div class="-body"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Dismiss</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="<?= base_url('assets/plugins/toastr/toastr.js') ?>"></script>
<script type="text/javascript">

	var serverErrorCode  = {
	    100 : 'Continue',
	    101 : 'Switching Protocols',
	    102 : 'Processing', 
	    200 : 'OK',
	    201 : 'Created',
	    202 : 'Accepted',
	    203 : 'Non-Authoritative Information', 
	    204 : 'No Content',
	    205 : 'Reset Content',
	    206 : 'Partial Content',
	    207 : 'Multi-Status', 
	    208 : 'Already Reported', 
	    226 : 'IM Used', 
	    300 : 'Multiple Choices',
	    301 : 'Moved Permanently',
	    302 : 'Found',
	    303 : 'See Other', 
	    304 : 'Not Modified',
	    305 : 'Use Proxy', 
	    306 : 'Switch Proxy',
	    307 : 'Temporary Redirect', 
	    308 : 'Permanent Redirect', 
	    400 : 'Bad Request',
	    401 : 'Unauthorized',
	    402 : 'Payment Required',
	    403 : 'Forbidden',
	    404 : 'Not Found',
	    405 : 'Method Not Allowed',
	    406 : 'Not Acceptable',
	    407 : 'Proxy Authentication Required',
	    408 : 'Request Timeout',
	    409 : 'Conflict',
	    410 : 'Gone',
	    411 : 'Length Required',
	    412 : 'Precondition Failed',
	    413 : 'Request Entity Too Large',
	    414 : 'Request-URI Too Long',
	    415 : 'Unsupported Media Type',
	    416 : 'Requested Range Not Satisfiable',
	    417 : 'Expectation Failed',
	    418 : 'I\'m a teapot', 
	    419 : 'Authentication Timeout', 
	    420 : 'Enhance Your Calm', 
	    420 : 'Method Failure', 
	    422 : 'Unprocessable Entity', 
	    423 : 'Locked', 
	    424 : 'Failed Dependency', 
	    424 : 'Method Failure', 
	    425 : 'Unordered Collection', 
	    426 : 'Upgrade Required', 
	    428 : 'Precondition Required', 
	    429 : 'Too Many Requests', 
	    431 : 'Request Header Fields Too Large', 
	    444 : 'No Response', 
	    449 : 'Retry With', 
	    450 : 'Blocked by Windows Parental Controls', 
	    451 : 'Redirect', 
	    451 : 'Unavailable For Legal Reasons', 
	    494 : 'Request Header Too Large', 
	    495 : 'Cert Error', 
	    496 : 'No Cert', 
	    497 : 'HTTP to HTTPS', 
	    499 : 'Client Closed Request', 
	    500 : 'Internal Server Error',
	    501 : 'Not Implemented',
	    502 : 'Bad Gateway',
	    503 : 'Service Unavailable',
	    504 : 'Gateway Timeout',
	    505 : 'HTTP Version Not Supported',
	    506 : 'Variant Also Negotiates', 
	    507 : 'Insufficient Storage', 
	    508 : 'Loop Detected', 
	    509 : 'Bandwidth Limit Exceeded', 
	    510 : 'Not Extended', 
	    511 : 'Network Authentication Required', 
	    598 : 'Network read timeout error', 
	    599 : 'Network connect timeout error', 
   	}

	toastr.options = {
	  "closeButton": true,
	  "debug": false,
	  "newestOnTop": true,
	  "progressBar": true,
	  "positionClass": "toast-top-right",
	  "preventDuplicates": false,
	  "onclick": null,
	  "showDuration": "300",
	  "hideDuration": "20000",
	  "timeOut": "20000",
	  "extendedTimeOut": "20000",
	  "showEasing": "swing",
	  "hideEasing": "linear",
	  "showMethod": "fadeIn",
	  "hideMethod": "fadeOut"
	}

	function show_tost(type,title,message) {
		toastr[type](message,title )
	}
	
	$(".btn-setting").on('click',function(){
		$this = $(this);
		$("#setting-widzard").modal({
			backdrop: 'static',
			keyboard: false
		});

		$("#setting-widzard").html('Loading');

		$.ajax({
			url:'<?= base_url('setting/getModal') ?>',
			type:'POST',
			dataType:'json',
			data:{
				'key' : $this.attr('data-key'),
				'type' : $this.attr('data-type'),
			},
			beforeSend:function(){ $this.btn("loading"); },
			complete:function(){ $this.btn("reset"); },
			success:function(json){
				if(json['html']){
					$("#setting-widzard").html(json['html']);
				}
			},
		})
	})

	
  	$(document).delegate('.allow-number','keypress keyup blur',function(event) {  		
    	$(this).val($(this).val().replace(/[^0-9\.]/g,''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
  	});

  	$(document).delegate("[data-log]",'click',function(){
  		$this = $(this);

  		var data = {};
  		var search = $this.attr('data-extra');
  		if(search){
			data = JSON.parse('{"' + decodeURI(search).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}')
  		}

  		data['type'] =$this.attr("data-log");

  		$.ajax({
  			url:'<?= base_url( $usercontrol ? 'usercontrol/logs' : 'admincontrol/logs') ?>',
  			type:'POST',
  			dataType:'json',
  			data:data,
  			beforeSend:function(){$this.btn("loading");},
  			complete:function(){$this.btn("reset");},
  			success:function(json){
  				if(json['html']){
  					$("#log-widzard").modal({
						backdrop: 'static',
						keyboard: false
					});
					$("#log-widzard").html(json['html']);
				}
  			},
  		})
  	})

  	$(".password-group .input-group-prepend button").on('click',function(){
		$input = $(this).parents(".password-group").find("input");
		$i = $(this).parents(".password-group").find("i");
  		if($i.hasClass("fa-eye")){
  			$i.addClass("fa-eye-slash");
  			$i.removeClass("fa-eye");
  			$input.attr('type','text');
  		} else {
  			$i.addClass("fa-eye");
  			$i.removeClass("fa-eye-slash");
  			$input.attr('type','password');
  		}
  	})

  	$(document).ajaxComplete(function myErrorHandler(event, xhr, ajaxOptions, thrownError) {
  		var statusCode = xhr.status;

  		
  		if(statusCode != 200 && ajaxOptions.type == 'POST'){  			
	  		var title = '';
	  		var body = '';

	  		title = '<?= __('admin.internal_server_error') ?>';
	  		body += '<h3><?= __('admin.sorry_an_error_has_occured') ?></h3>';

		  	if(serverErrorCode[statusCode]){
	  			body += "<p><?= __('admin.error_message') ?> : " + serverErrorCode[statusCode] + "</p>";
		  		body += "<p><?= __('admin.error_code') ?> : " + statusCode + "</p>";

				$("#model-ajaxError .modal-title").html(title);
				$("#model-ajaxError .modal-body .-body").html(body);
				$("#model-ajaxError").modal("show");
		  	} else {
			  	body += '<p><?= __('admin.error_message') ?> : <?= __('admin.uknown_error') ?> </p>';
			}

			$(".btn-loading").removeClass('btn-loading');
  		}
	});
</script>