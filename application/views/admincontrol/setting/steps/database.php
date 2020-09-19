<div>
	<form id="database-files" enctype="multipart/form-data">
		<div class="form-group">
			<div class="file-upload-wrapper-container">
				<p class="m-0 text-muted"><?= __('admin.database_ins1') ?></p>
				<div>
					<div class="file-upload-wrapper" data-text="Select Database File.">
				      <input name="database" type="file" class="file-upload-field" value="">
				    </div>
				    <div>
				    	<a data-toggle='modal' href='#model-databaseinstruction' class="info-btn"><i class="fa fa-info"></i></a>
				    </div>
				</div>
			</div>
		</div>

		<div class="text-center">
			<a href="<?= base_url('Installversion/downloadDatabase') ?>" target='_blank'>Download Database Structure</a>

			<div class="text-primary font-500">Upload file size limit is <?= file_upload_max_size() ?> <span class="upload-file-size"></span></div>
		</div>
		<div class="progress-w">
		    <div class="progress-w-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="max-width:0%">
		    	<span class="title">0%</span>
	    	</div>
	  	</div>
	</form>
</div>

<div class="modal" id="model-databaseinstruction">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?= __('admin.how_to_find_database_sql_file') ?></h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <img src="<?= base_url('assets/images/update_db.png') ?>" class='img-responsive w-100'>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><?= __('admin.close') ?></button>
      </div>
    </div>
  </div>
</div>

<div class="step-action">
	<div class="text-center">
		<button class="btn btn-success btn-upload-database"><?= __('admin.migrate_database') ?></button>
	</div>
</div>

<script type="text/javascript">
	function updateProgressBar(percentComplete) {
		$('.progress-w-bar').css("max-width",percentComplete + "%");
		var text = 'Uploading '+ percentComplete + "%";
		if(percentComplete >=99 ){
			text = 'Migrating sql file...';
		}

		$('.progress-w-bar .title,#swal2-title').text(text);
	}

	$('input[name="database"]').change(function(event) {
        var _size = this.files[0].size;
        var fSExt = new Array('Bytes', 'KB', 'MB', 'GB'),
    	i=0;while(_size>900){_size/=1024;i++;}
        var exactSize = (Math.round(_size*100)/100)+' '+fSExt[i];
        
        $('.upload-file-size').html(" and uploading file size is : "+ exactSize).show();
    });

	function migrateDatabase(t) {
		$this = $(this);

		

		var form = $('#database-files')[0];
		var formData = new FormData(form);
		$.ajax({
			url:'<?= base_url("installversion/migrateDatabase") ?>',
			type:'POST',
			dataType:'json',
			data:formData,
			contentType: false,
    		processData: false,
			beforeSend:function(){
				$this.btn("loading");$(".progress-w").show();
				Swal.fire({
					icon: 'info',
					allowOutsideClick: false,
					showCancelButton: false, 
					showConfirmButton: false,
					title: '0%',
					footer: 'Uploading Database',
					html: 'Please do not refresh the page and wait <br> while we are proccessing.'
				})
			},
			complete:function(){
				$this.btn("reset");
				Swal.close();
			},
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
                        updateProgressBar(percentComplete);
                    }
                }, false );

                jqXHR.addEventListener( "progress", function ( evt ){
                    if ( evt.lengthComputable ){
                        var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
                        updateProgressBar(percentComplete);
                    }
                }, false );
                return jqXHR;
            },
			success:function(json){
				Swal.close();
				$container = $("#database-files");
				$container.find(".has-error").removeClass("has-error");
				$container.find("span.text-danger").remove();
				
				if(json['success']){
					$(".step-container .step-body").html(json['success']);
				}

				if(json['errors']){
					$(".progress-w").hide()
				    $.each(json['errors'], function(i,j){
				        $ele = $container.find('[name="'+ i +'"]');
				        if($ele){
				            $ele.parents(".file-upload-wrapper-container").addClass("has-error");
				            $ele.parents(".file-upload-wrapper-container").append("<span class='text-danger'>"+ j +"</span>");
				        }
				    })
				}
			},
		})
	}

	$(".btn-upload-database").click(function(){
		confirm_password(this,function(){
			migrateDatabase(this);
		},'database');
	})
</script>