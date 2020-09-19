<?php 
	$db =& get_instance(); 
	$userdetails =$db->Product_model->userdetails('user'); 
	$SiteSetting =$db->Product_model->getSiteSetting();
?>
</div> <!-- content -->


<?php 
	$global_script_status = (array)json_decode($SiteSetting['global_script_status'],1);
	if(in_array('affiliate', $global_script_status)){
		echo $SiteSetting['global_script'];
	}
?>

<footer class="footer"><?= $SiteSetting['footer'] ?></footer>
</div>
<!-- End Right content here -->
</div>

<script src="<?php echo base_url('assets/js/jquery-confirm.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/popper.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/modernizr.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/detect.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/fastclick.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.slimscroll.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.blockUI.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/waves.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.nicescroll.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.scrollTo.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vertical/assets/plugins/skycons/skycons.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vertical/assets/plugins/raphael/raphael-min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vertical/assets/plugins/morris/morris.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/dashborad.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jssocials-1.4.0/jssocials.min.js'); ?>"></script>

<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/js/jssocials-1.4.0/jssocials.css'); ?>" />
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/js/jssocials-1.4.0/jssocials-theme-flat.css'); ?>" />

<link href="<?php echo base_url('assets/js/summernote-0.8.12-dist/summernote-bs4.css'); ?>" rel="stylesheet">
<script src="<?php echo base_url('assets/js/summernote-0.8.12-dist/summernote-bs4.js'); ?>"></script>

<link href="<?php echo base_url('assets/vertical/assets/css/style.css'); ?>?v=<?= av() ?>" rel="stylesheet" type="text/css">

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
<!-- App js -->
<script src="<?php echo base_url(); ?>assets/js/app.js"></script>

<script type="text/javascript">
	$(".select2-input").select2();

	$(document).delegate(".only-number-allow","keypress",function (e) {
     	if (e.which != 46 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
       		return false;
    	}
   	});

	function readURL(input,placeholder) {
	  if (input.files && input.files[0]) {
	    var reader = new FileReader();
	    
	    reader.onload = function(e) {
	      $(placeholder).attr('src', e.target.result);
	    }
	    
	    reader.readAsDataURL(input.files[0]);
	  }
	}

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
<script>
	/* BEGIN SVG WEATHER ICON */
	if (typeof Skycons !== 'undefined'){
		var icons = new Skycons(
		{"color": "#fff"},
		{"resizeClear": true}
		),
		list  = [
		"clear-day", "clear-night", "partly-cloudy-day",
		"partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",
		"fog"
		],
		i;
		
		for(i = list.length; i--; )
		icons.set(list[i], list[i]);
		icons.play();
	};
	
	// scroll
	
	$(document).on('ready',function() {
        if($("#boxscroll").length > 0){
			$("#boxscroll").niceScroll({cursorborder:"",cursorcolor:"#cecece",boxzoom:true});
		}
		if($("#boxscroll2").length > 0){
			$("#boxscroll2").niceScroll({cursorborder:"",cursorcolor:"#cecece",boxzoom:true}); 
		}
	});
	
	function shownofication(id,url){
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>usercontrol/updatenotify",
			data:{'id':id},
			dataType:'json',
			success: function(data){
				window.location.href=data['location'];
			}
		});
	}
</script>
<!-- <script src="<?php echo base_url(); ?>assets/vertical/assets/plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js" type="text/javascript"></script> -->

<?php 
	$usercontrol = true;
	require APPPATH . 'views/common/setting_widzard.php'; 
?>
</body>
</html>
