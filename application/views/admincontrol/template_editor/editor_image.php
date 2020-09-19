<div class="col-sm-12">
    <div class="img-tool">
        <a href="<?php echo $parent; ?>" data-toggle="tooltip" title="Parent" id="button-parent" class="btn btn-default"><i class="fa fa-level-up"></i></a> 
        <a href="<?php echo $url; ?>" data-toggle="tooltip" title="Refresh" id="button-refresh" class="btn btn-default"><i class="fa fa-refresh"></i></a>
        <button type="button" data-toggle="tooltip" title="Upload" id="button-upload" class="btn btn-primary"><i class="fa fa-upload"></i></button>
        <button type="button" data-toggle="tooltip" title="Make Folder" id="button-folder" class="btn btn-default"><i class="fa fa-folder"></i></button>
        <button type="button" data-toggle="tooltip" title="Delete" id="button-delete" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
    </div>
</div>
<hr />
<br><br>
<div class="col-sm-12 ">    
    <div class="image-tool-row">
        <?php foreach ($images as $image) { ?>
            <div class="img-items text-center">
                <?php if ($image['type'] == 'directory') { ?>
                    <div class="text-center">
                        <a href="<?php echo $image['href']; ?>" class="directory" style="vertical-align: middle;"><i class="fa fa-folder fa-5x"></i></a>
                    </div>
                    <label>
                        <input type="checkbox" name="path[]" value="<?php echo $image['path']; ?>" />
                        <?php echo $image['name']; ?>
                    </label>
                <?php } ?>

                <?php if ($image['type'] == 'image') { ?>
                    <label>
                        <div class="thumbnail">
                            <img src="<?php echo $image['thumb']; ?>" alt="<?php echo $image['name']; ?>" title="<?php echo $image['name']; ?>" class='thumb-lg ' />
                        </div>
                        <input type="checkbox" name="path[]" value="<?php echo $image['path']; ?>" />
                        <?php echo $image['name']; ?>
                    </label>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>

<script>
    <?php if ($target) { ?>
        $('a.thumbnail.img_block').on('click', function(e) {
            e.preventDefault();
        });
    <?php } ?>
    $('a.thumbnail.img_block').on('click', function(e) {
        e.preventDefault();  
    })
    $('a.directory').on('click', function(e) {
        e.preventDefault();
        $('.model_image').load($(this).attr('href'));
    });

    $('#button-parent').on('click', function(e) {
        e.preventDefault();
        $('.model_image').load($(this).attr('href'));
    });

    $('#button-refresh').on('click', function(e) {
        e.preventDefault();
        $('.model_image').load($(this).attr('href'));
    });

    $('input[name=\'search\']').on('keydown', function(e) {
        if (e.which == 13) {
            $('#button-search').trigger('click');
        }
    });

    $('#button-search').on('click', function(e) {
        var url = '<?= $fun_url ?>?directory=<?php echo $directory; ?>';
        var filter_name = $('input[name=\'search\']').val();

        if (filter_name) {
            url += '&filter_name=' + encodeURIComponent(filter_name);
        }

        <?php if ($thumb) { ?>
            url += '&thumb=' + '<?php echo $thumb; ?>';
        <?php } ?>

        <?php if ($target) { ?>
            url += '&target=' + '<?php echo $target; ?>';
        <?php } ?>

        $('.model_image').load(url);
    });
//-->
</script>
<script type="text/javascript"><!--
    $('#button-upload').on('click', function() {
        $('#form-upload').remove();

        $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" value="" /></form>');

        $('#form-upload input[name=\'file\']').trigger('click');

        if (typeof timer != 'undefined') {
            clearInterval(timer);
        }

        timer = setInterval(function() {
            if ($('#form-upload input[name=\'file\']').val() != '') {
                clearInterval(timer);

                $.ajax({
                    url: '<?= $image_upload ?>?directory=<?php echo $directory; ?>',
                    type: 'post',
                    dataType: 'json',
                    data: new FormData($('#form-upload')[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#button-upload i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                        $('#button-upload').prop('disabled', true);
                    },
                    complete: function() {
                        $('#button-upload i').replaceWith('<i class="fa fa-upload"></i>');
                        $('#button-upload').prop('disabled', false);
                    },
                    success: function(json) {
                        if (json['error']) {
                            alert(json['error']);
                        }

                        if (json['success']) {
                            alert(json['success']);
                            $('#button-refresh').trigger('click');
                        }
                        
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        }, 500);
    });
    $(document).on('ready',function(){

        $('#button-folder').popover({
            html: true,
            placement: 'bottom',
            trigger: 'click',
            title: '<?php echo $entry_folder; ?>',
            content: function() {
                html  = '<div class="input-group">';
                html += '  <input type="text" name="folder" value="" placeholder="<?php echo $entry_folder; ?>" class="form-control">';
                html += '  <span class="input-group-btn"><button type="button" title="<?php echo $button_folder; ?>" id="button-create" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></span>';
                html += '</div>';

                return html;
            }
        });
    })

    $('#button-folder').on('shown.bs.popover', function() {
        $('#button-create').on('click', function() {
            $.ajax({
                url: '<?= $folder_url ?>?directory=<?php echo $directory; ?>',
                type: 'post',
                dataType: 'json',
                data: 'folder=' + encodeURIComponent($('input[name=\'folder\']').val()),
                beforeSend: function() {
                    $('#button-create').prop('disabled', true);
                },
                complete: function() {
                    $('#button-create').prop('disabled', false);
                },
                success: function(json) {
                  $('#button-folder').popover('hide')
                    if (json['error']) {
                        alert(json['error']);
                    }

                    if (json['success']) {
                        alert(json['success']);

                        $('#button-refresh').trigger('click');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
    });

    $('#button-delete').on('click', function(e) {
        if (confirm('<?php echo $text_confirm; ?>')) {
            $.ajax({
                url: '<?= $delete_image_url ?>',
                type: 'post',
                dataType: 'json',
                data: $('input[name^=\'path\']:checked'),
                beforeSend: function() {
                    $('#button-delete').prop('disabled', true);
                },
                complete: function() {
                    $('#button-delete').prop('disabled', false);
                },
                success: function(json) {
                    if (json['error']) {
                        alert(json['error']);
                    }

                    if (json['success']) {
                        alert(json['success']);

                        $('#button-refresh').trigger('click');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    });
</script>