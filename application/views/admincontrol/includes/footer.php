<?php
	$db =& get_instance(); 
	$products = $db->Product_model; 
	$userdetails=$db->Product_model->userdetails(); 
    $SiteSetting =$db->Product_model->getSiteSetting();	
    $userdetails=$db->userdetails(); 
    $license = $products->getLicese();

	if(isset($license['is_lifetime']) && $license['is_lifetime'] == false){ 
?>
	<div class="license-expire">
		<span>Your License is expire in  <span class="timer"><?= $license['remianing_time'] ?></span> </span>
	</div>

	<script type="text/javascript">
		var distance = <?= (float)$license['remianing_time'] ?>;
		var x = setInterval(function() {
			var days = Math.floor(distance / (60 * 60 * 24));
			var hours = Math.floor((distance % (60 * 60 * 24)) / (60 * 60));
			var minutes = Math.floor((distance % (60 * 60)) / (60));
			var seconds = Math.floor((distance % (60)));

			var timer = '';
			if(days > 0) timer += days + "d ";
			if(hours > 0) timer += hours + "h ";

			$(".license-expire .timer").html(timer +  minutes + "m " + seconds + "s ");

			distance--;
			if (distance < 0) {
				clearInterval(x);
				$(".license-expire .timer").html("EXPIRED");
				window.location.reload();
			}
		}, 1000);
	</script>
<?php } ?>



    </div> <!-- content -->

<?php 
	$global_script_status = (array)json_decode($SiteSetting['global_script_status'],1);
	if(in_array('admin', $global_script_status)){
		echo $SiteSetting['global_script'];
	}
?>

<?php require APPPATH . 'views/common/setting_widzard.php'; ?>
<footer class="footer">
   <span class="text-center"> <?= $SiteSetting['footer'] ?></span> <i class="mdi mdi-heart text-danger"></i>  <span class="text-center">
    <div title='Current Time : <?= date("Y-m-d h:i:s") . " " .date_default_timezone_get() ?>'></div></span>
   <!-- <?= __('admin.top_version') ?> : <?php echo  SCRIPT_VERSION ?> | <?php if($license['allow_update']) {echo "Auto Update Enable";}  else {echo "Auto Update Disabled";} ?>-->
</footer>

            </div>
            <!-- End Right content here -->

        </div>
        <!-- END wrapper -->
        
        
        
                
<!--<footer class="footer">
	<div class="row">
		<div class="col-sm text-left">
	    	<?= __('admin.top_version') ?> : <?php echo  SCRIPT_VERSION ?> | <?php if($license['allow_update']) {echo "Auto Update Enable";}  else {echo "Auto Update Disabled";} ?>
	    </div>
	    <div class="col-sm-6" title='Current Time : <?= date("Y-m-d h:i:s") . " " .date_default_timezone_get() ?>'><?= $SiteSetting['footer'] ?></div>
	    <div class="col-sm"></div>
	</div>
</footer>-->

<div class="modal" id="payment-detail_modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title mt-0"><?= __('admin.footer_user_payment_details') ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
      	<h4 class="modal-title mt-0"><?= __('admin.footer_bank_details') ?></h4>
            <div class="table-rep-plugin">
                <div class="table-responsive b-0" data-pattern="priority-columns">
            <table id="tech-companies-1" class="table  table-striped">
        	<thead>
        		<tr>
					<th class="txt-cntr"><?= __('admin.footer_bank_name') ?></th>
					<th class="txt-cntr"><?= __('admin.footer_account_number') ?></th>
					<th class="txt-cntr"><?= __('admin.footer_account_name') ?></th>
					<th class="txt-cntr"><?= __('admin.footer_ifsc_code') ?></th>
				</tr>
        	</thead>
        	<tbody class="bank-details"> </tbody>
        </table>
        </div>
        </div>
        
        
        <h4 class="modal-title mt-0"><?= __('admin.footer_paypal_emails') ?></h4>
        <div class="table-rep-plugin">
          <div class="table-responsive b-0" data-pattern="priority-columns">
            <table id="tech-companies-1" class="table  table-striped">
            <thead>
                <tr>
                    <th class="txt-cntr"></th>
                    <th class="txt-cntr"><?= __('admin.footer_email') ?></th>
                </tr>
            </thead>
            <tbody class="paypal-details"> </tbody>
        </table>
      </div>
     </div>
      
      <h4 class="modal-title mt-0"><?= __('admin.footer_user_details') ?></h4>
      <div class="table-rep-plugin">
          <div class="table-responsive b-0" data-pattern="priority-columns">
            <table id="tech-companies-1" class="table  table-striped">
        	<tbody class="user-details"></tbody>
        </table>
        </div>
    </div>
        
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><?= __('admin.footer_close') ?></button>
      </div>
    </div>
  </div>
</div>
</div>


<?php if(true || count($status) > 0){ ?>
<!--<div class="modal fade" id="welcome-modal" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<h3 class="text-center">Welcome To Affiliate Pro Script</h3>
				
				<br>
				<p>Starting the work with Affiliate script you need to complete First Steps</p>
				<p>Click <a class="btn btn-primary" href="<?= base_url('firstsetting') ?>"><i class="fa fa-gear"></i></a> to start the First Setting Process</p>
				<p>For any issue contact with <a href="mailto:support@affiliatepro.org">support@affiliatepro.org</a></p>

				<br>
				<center>
				<iframe style="width: 100%;max-width: 500px;" height="315" src="https://www.youtube.com/embed/SyLHbe0oJag" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
				</center>
				
				<br>
				<b>Affiliate Pro Team</b>


				<br><br>
				<div class="welcome-footer ">
					<label class="hide-welcome">Don't show again</label>
					<a href="<?= base_url("firstsetting") ?>" class="close-button pull-right" >Take me to setting</a>
				</div>
			</div>
		</div>
	</div>
</div>-->

<script type="text/javascript">

	$(document).delegate(".only-number-allow","keypress",function (e) {
     	if (e.which != 46 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
       		return false;
    	}
   	});

	$(document).on('ready',function(){
		if(getCookie('hide_welcome') != 'true'){
			$("#welcome-modal").modal("show")
		}
	})
	function setCookie(cname, cvalue, exdays) {
	  var d = new Date();
	  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
	  var expires = "expires="+d.toUTCString();
	  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

	function readURL(input,placeholder) {
	  if (input.files && input.files[0]) {
	    var reader = new FileReader();
	    
	    reader.onload = function(e) {
	      $(placeholder).attr('src', e.target.result);
	    }
	    
	    reader.readAsDataURL(input.files[0]);
	  }
	}

	function getCookie(cname) {
	  var name = cname + "=";
	  var ca = document.cookie.split(';');
	  for(var i = 0; i < ca.length; i++) {
	    var c = ca[i];
	    while (c.charAt(0) == ' ') {
	      c = c.substring(1);
	    }
	    if (c.indexOf(name) == 0) {
	      return c.substring(name.length, c.length);
	    }
	  }
	  return "";
	}

	
	$('.hide-welcome').on('click',function(){
		setCookie("hide_welcome","true", 365)
		$("#welcome-modal").modal("hide");
	})

</script>

<?php } ?>


<div class="modal" id="model-shorturl">
  
</div>

<script type="text/javascript">
	$(".btn-delete").on('click',function(){
        return confirm("Are your sure ?");
    })

    /*function shortUrlConfig(longUrl, title){
    	$this = $(this);
    	$.ajax({
    		url:'<?= base_url("shorturl/configModal") ?>',
    		type:'POST',
    		dataType:'json',
    		data:{longUrl,longUrl,title:title},
    		beforeSend:function(){$this.btn("loading");},
    		complete:function(){$this.btn("reset");},
    		success:function(json){
    			if(json['html']){
    				$("#model-shorturl").html(json['html']);
    				$("#model-shorturl").modal("show");
    			}
    		},
    	})
    }*/
	
	$(document).delegate("[payment_detail]",'click',function(e){
		e.preventDefault();
		e.stopPropagation();
		$this = $(this);
		var user_id = $this.attr("payment_detail");
		$.ajax({
			url:'<?php echo base_url("admincontrol/getpaymentdetail") ?>/' + user_id,
			type:'POST',
			dataType:'json',
			beforeSend:function(){ $this.btn("loading"); },
			complete:function(){ $this.btn("reset"); },
			success:function(json){
				$('#payment-detail_modal').modal("show");
				var html = '';
				$.each(json['paymentlist'], function(i,j){
					html += '<tr>';
					html += '<th>'+ j['payment_bank_name'] +'</th>';
					html += '<th>'+ j['payment_account_number'] +'</th>';
					html += '<th>'+ j['payment_account_name'] +'</th>';
					html += '<th>'+ j['payment_ifsc_code'] +'</th>';
					html += '</tr>';
				})	
				$('#payment-detail_modal .bank-details').html(html);
				var html = '';
                $.each(json['paypalaccounts'], function(i,j){
                    html += '<tr>';
                    html += '<th>'+ (i+1) +'</th>';
                    html += '<th>'+ j['paypal_email'] +'</th>';
                    html += '</tr>';
                })  
                $('#payment-detail_modal .paypal-details').html(html);
                var html = '';
                html += '<tr>';
                html += '<th><?= __('admin.footer_firstname') ?></th>';
                html += '<td>'+ json.user.firstname +'</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<th><?= __('admin.footer_lastname') ?></th>';
                html += '<td>'+ json.user.lastname +'</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<th><?= __('admin.footer_username') ?></th>';
                html += '<td>'+ json.user.username +'</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<th><?= __('admin.footer_email') ?></th>';
                html += '<td>'+ json.user.email +'</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<th><?= __('admin.footer_phone') ?></th>';
                html += '<td>'+ json.user.phone +'</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<th><?= __('admin.footer_address') ?></th>';
                html += '<td>'+ json.user.address +'</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<th><?= __('admin.footer_state') ?></th>';
                html += '<td>'+ json.user.state +'</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<th><?= __('admin.footer_country') ?></th>';
                html += '<td>'+ json.user.country +'</td>';
                html += '</tr>';
				$('#payment-detail_modal .user-details').html(html);
			},	
		})
	})
</script>

<script src="<?php echo base_url(); ?>assets/js/jquery-confirm.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/popper.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/modernizr.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/detect.js"></script>
<script src="<?php echo base_url(); ?>assets/js/fastclick.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.blockUI.js"></script>
<script src="<?php echo base_url(); ?>assets/js/waves.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.nicescroll.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.scrollTo.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vertical/assets/plugins/skycons/skycons.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vertical/assets/plugins/raphael/raphael-min.js"></script>
<script src="<?php echo base_url(); ?>assets/vertical/assets/plugins/morris/morris.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vertical/assets/plugins/magnific-popup/jquery.magnific-popup.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/lightbox.js"></script>



<script src="<?php echo base_url(); ?>assets/js/jssocials-1.4.0/jssocials.min.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jssocials-1.4.0/jssocials.css" />
<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jssocials-1.4.0/jssocials-theme-flat.css" />

<script type="text/javascript">
	$(document).delegate(".copy-input input",'click', function(){
		$(this).select();
	})
	$(document).delegate('[copyToClipboard]',"click", function(){
		$this = $(this);
	  	var $temp = $("<input>");
	  	$("body").append($temp);
	  	$temp.val($(this).attr('copyToClipboard')).select();
	  	document.execCommand("copy");
	  	$temp.remove();
	  	$this.tooltip('hide').attr('data-original-title', 'Copied!').tooltip('show');
	  	setTimeout(function() { $this.tooltip('hide'); }, 500);
	});
	$('[copyToClipboard]').tooltip({
	  trigger: 'click',
	  placement: 'bottom'
	});
</script>
<script type="text/javascript">
    (function ($) {
        $.fn.button = function (action) {
            var self = $(this);
            if (action == 'loading') {
                if ($(self).attr("disabled") == "disabled") {
                    //e.preventDefault();
                }
                $(self).attr("disabled", "disabled");
                $(self).attr('data-btn-text', $(self).html());
                $(self).html('<i class="fa fa-spinner fa-spin"></i>' + $(self).text());
            }
            if (action == 'reset') {
                $(self).html($(self).attr('data-btn-text'));
                $(self).removeAttr("disabled");
            }
        }
    })(jQuery);
</script>
<script src="<?php echo base_url(); ?>assets/js/app.js"></script>
<link href="<?php echo base_url(); ?>assets/js/summernote-0.8.12-dist/summernote-bs4.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>assets/js/summernote-0.8.12-dist/summernote-bs4.js"></script>

<div class="modal fade" id="ip-flag_model">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">All IPs Details</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(".select2-input").select2();
	$(document).delegate(".view-all",'click',function(){
		var data = $(this).find("span").html();
		var html = '<table class="table table-hover">';
		data = JSON.parse(data);
		html += '<tr>';
		html += '	<th>IP</th>';
		html += '	<th width="30px">Country</th>';
		html += '</tr>';

		$.each(data, function(i,j){
			html += '<tr>';
			html += '	<td>'+ j['ip'] +'</td>';
			html += '	<td><img style="width: 20px;" src="<?= base_url('assets/vertical/assets/images/flags/') ?>'+ j['country_code'].toLowerCase() +'.png" ></td>';
			html += '</tr>';
		})
		html += '</table>';

		$("#ip-flag_model").modal("show");
		$("#ip-flag_model .modal-body").html(html);
	})
	$('[data-toggle="tooltip"]').tooltip();   
	
	if($('#morris-area-chart').length > 0) {
		var areaData = [
			{y: '2011', a: 10, b: 15},
			{y: '2012', a: 30, b: 35},
			{y: '2013', a: 10, b: 25},
			{y: '2014', a: 55, b: 45},
			{y: '2015', a: 30, b: 20},
			{y: '2016', a: 40, b: 35},
			{y: '2017', a: 10, b: 25},
			{y: '2018', a: 25, b: 30}
		];
		Morris.Area({
			element: 'morris-area-chart',
			pointSize: 3,
			lineWidth: 2,
			data: areaData,
			xkey: 'y',
			ykeys: ['a', 'b'],
			labels: ['Orders', 'Sales'],
			resize: true,
			hideHover: 'auto',
			gridLineColor: '#eef0f2',
			lineColors: ['#00c292', '#03a9f3'],
			lineWidth: 0,
			fillOpacity: 0.1,
			xLabelMargin: 10,
			yLabelMargin: 10,
			grid: false,
			axes: false,
			pointSize: 0
		});
	}
	$(document).on('ready',function(){
		if($('#morris-donut-chart').length > 0) {
			var donutData = [
				<?php $str = '';
					foreach($country_list as $key => $one_item){ 
						$str .= '{label: "' . $one_item->name . '", value: ' . (int)$one_item->num . '},'; 
					}
					echo rtrim($str,", ");
				?>
			];
			Morris.Donut({
				element: 'morris-donut-chart',
				data: donutData,
				resize: true,
				colors: ['#40a4f1', '#5b6be8', '#c1c5e2', '#e785da', '#00bcd2']
			});
		}
		
		if($("#boxscroll").length > 0){
			$("#boxscroll").niceScroll({cursorborder:"",cursorcolor:"#cecece",boxzoom:true});
		}
		if($("#boxscroll2").length > 0){
			$("#boxscroll2").niceScroll({cursorborder:"",cursorcolor:"#cecece",boxzoom:true}); 
		}
	
		if($(".clickable-row").length > 0){
			$(".clickable-row").on('click',function(){
				window.location = $(this).data("href");
			});
		}
		if($("#Country").length > 0){
			$('#Country').on('change', function(){
				country_id = $(this).val();
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>admincontrol/getstate",
					data:'country_id='+country_id,
					success: function(data){
						$("#StateProvince").html(data);
					}
				});
			});
		}
	});
	function shownofication(id,url){
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>admincontrol/updatenotify",
			data:'id='+id,
			dataType:'json',
			success: function(data){
				window.location.href=data['location'];
			}
		});
	}

	$(document).on('ready',function(){
        $('.summernote-img').each(function(){
            sumNote($(this));
        });
    });

    function sumNote(element){
    	
        var height = $(element).attr("data-height") ? $(element).attr("data-height") : 500;
        $(element).summernote({
            disableDragAndDrop: true,
            height: height,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'image', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            buttons: {
                image: function() {
                    var ui = $.summernote.ui;
                    // create button
                    var button = ui.button({
                        contents: '<i class="fa fa-image" />',
                        tooltip: false,
                        click: function () {
                            $('#modal-image').remove();
                        
                            $.ajax({
                                url: '<?= base_url("filemanager") ?>',
                                dataType: 'html',
                                beforeSend: function() {
                                },complete: function() {
                                },success: function(html) {
                                    $('body').append('<div id="modal-image" class="modal fade">' + html + '</div>');
                                    $('#modal-image').modal('show');
                                    $('#modal-image').delegate('.image-box .thumbnail','click', function(e) {
                                        e.preventDefault();
                                        $(element).summernote('insertImage', $(this).attr('href'));
                                        $('#modal-image').modal('hide');
                                    });
                                }
                            });                     
                        }
                    });
                
                    return button.render();
                }
            }
        });
    }
</script>	
</body></html>
						