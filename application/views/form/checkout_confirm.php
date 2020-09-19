<?php if($allow_comment){ ?>
	<div class="form-group">
		<label class="control-label"><?= __('store.order_comment') ?></label>
		<textarea class="form-control" rows="8" name="comment" placeholder="<?= __('store.order_comment') ?>"></textarea>
	</div>
<?php } ?>
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

    <script type="text/javascript">
    	var fileArray = [];
	    $('.downloadable_file_input').change(function(e){
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
<?php } ?>
<br>
<div class="checkbox">
	<label>
		<input type="checkbox" value="1" name="agree">
		<?= __('store.agree_text') ?>
	</label>
</div>
<br>