<link href="<?php echo base_url(); ?>assets/css/datepicker.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.js"></script>
      
<div class="row">
    <div class="col-12">
        <form id="form_form">
            <div class="row">
                <div class="col-sm-8">
                    <div class="card m-b-30">
                        <div class="card-header"><h4 class="header-title m-0"><?= __('admin.form') ?></h4></div>
                        <div class="card-body">
                            <input type="hidden" class="form-control" name="id" value="<?= (int)$form['form_id'] ?>">
                            <input type="hidden" class="form-control redirect" name="redirect" value="">
                            <div class="form-group">
                                <label class="control-label" ><?= __('admin.title'); ?></label>
                                <input type="text" class="form-control" name="title" value="<?= $form['title'] ?>">
                            </div>
                            <div class="form-group">
                                <label class="control-label" ><?= __('admin.seo_title'); ?></label>
                                <input type="text" class="form-control" name="seo" value="<?= $form['seo'] ?>">
                            </div>
                            <div class="form-group">
                                <label class="control-label" ><?= __('admin.body_content'); ?></label>
                                <textarea data-height="300px" rows="3" placeholder="" class="form-control body_content summernote-img" name="description"  type="text"><?= $form['description'] ?></textarea>
                            </div>

                            <fieldset class="custom-design mb-2">
                                <legend>Form Recursion</legend>
                                <div class="form-group">
                                    <label class="control-label">Form Recursion</label>
                                    <div>
                                        <?php                                          
                                            $form_recursion_type = $form['form_recursion_type'];
                                            $form_recursion = $form['form_recursion'];
                                        ?>
                                        <select name="form_recursion_type" class="form-control">
                                            <option <?= '' == $form_recursion_type ? 'selected' : '' ?> value="">-- None --</option>
                                            <option <?= 'default' == $form_recursion_type ? 'selected' : '' ?> value="default"><?= __('admin.default') ?></option>
                                            <option <?= 'custom' == $form_recursion_type ? 'selected' : '' ?> value="custom">Custom</option>
                                        </select>                           
                                    </div>
                                    <div class="toggle-container mt-2">
                                        <div class="d-none default-value">
                                            <small class="text-muted">
                                                <?php
                                                    if($setting['form_recursion'] == 'custom_time'){
                                                        echo __('admin.default_recursion')." : " . timetosting($setting['recursion_custom_time']). " | EndTime: " . dateFormat($setting['recursion_endtime']);
                                                    }else{
                                                        echo __('admin.default_recursion')." : " . $setting['product_recursion']. " | EndTime: " . dateFormat($setting['recursion_endtime']);
                                                    }
                                                ?>
                                            </small>
                                        </div>
                                        <div class="d-none custom-value">
                                            <div class="custom_recursion">
                                                <select name="form_recursion" class="form-control" id="recursion_type">
                                                    <option value="">Select recursion</option>
                                                    <option <?php if($form_recursion == 'every_day') { ?> selected <?php } ?> value="every_day"><?=  __('admin.every_day') ?></option>
                                                    <option <?php if($form_recursion == 'every_week') { ?> selected <?php } ?>  value="every_week"><?=  __('admin.every_week') ?></option>
                                                    <option <?php if($form_recursion == 'every_month') { ?> selected <?php } ?>  value="every_month"><?=  __('admin.every_month') ?></option>
                                                    <option <?php if($form_recursion == 'every_year') { ?> selected <?php } ?>  value="every_year"><?=  __('admin.every_year') ?></option>
                                                    <option <?php if($form_recursion == 'custom_time') { ?> selected <?php } ?>  value="custom_time"><?=  __('admin.custom_time') ?></option>
                                                </select>
                                                
                                                <div class="form-group mt-2 custom_time">      
                                                    <?php
                                                        $minutes = $form['recursion_custom_time'];

                                                        $day = floor ($minutes / 1440);
                                                        $hour = floor (($minutes - $day * 1440) / 60);
                                                        $minute = $minutes - ($day * 1440) - ($hour * 60);
                                                    ?>
                                                    <input type="hidden" name="recursion_custom_time" value="<?php echo $minutes; ?>">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <label class="control-label">Days : </label>
                                                            <input placeholder="Days" type="number" class="form-control" value="<?= $day ? $day : '' ?>" id="recur_day" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">

                                                        </div>                      
                                                        <div class="col-sm-4">
                                                            <label class="control-label">Hours : </label>
                                                            <select class="form-control" id="recur_hour">
                                                                <?php 
                                                                for ($x = 0; $x <= 23; $x++) {
                                                                    $selected = ($x == $hour ) ? 'selected="selected"' : '';
                                                                    echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>                      
                                                        <div class="col-sm-4">
                                                            <label class="control-label">Minutes : </label>
                                                            <select class="form-control" id="recur_minute">
                                                                <?php 
                                                                for ($x = 0; $x <= 59; $x++) {
                                                                    $selected = ($x == $minute ) ? 'selected="selected"' : '';
                                                                    echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>                      
                                                    </div>                                  
                                                </div>
                                                <br>
                                                <div class="endtime-chooser row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label class="control-label d-block"><?= __('admin.choose_custom_endtime') ?> <input <?= $form['recursion_endtime'] ? 'checked' : '' ?>  id='setCustomTime' name='recursion_endtime_status' type="checkbox"> </label>
                                                            <div style="<?= !$form['recursion_endtime'] ? 'display:none' : '' ?>" class='custom_time_container'>
                                                                <input type="text" class="form-control" value="<?= $form['recursion_endtime'] ? date("d-m-Y H:i",strtotime($form['recursion_endtime'])) : '' ?>" name="recursion_endtime" id="endtime" placeholder="Choose EndTime" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>   
                                        </div>
                                    </div>

                                    <script type="text/javascript">
                                        $("select[name=form_recursion_type]").on("change",function(){
                                            $con = $(this).parents(".form-group");
                                            $con.find(".toggle-container .custom-value, .toggle-container .default-value").addClass('d-none');

                                            if($(this).val() == 'default'){
                                                $con.find(".toggle-container .default-value").removeClass("d-none");
                                            }else if($(this).val() == 'custom'){
                                                $con.find(".toggle-container .custom-value").removeClass("d-none");
                                            }
                                        })
                                        $("select[name=form_recursion_type]").trigger("change");


                                        $("select[name=form_recursion]").on("change",function(){
                                            $con = $(this).parents(".custom_recursion");
                                            $con.find(".custom_time").addClass('d-none');

                                            if($(this).val() == 'custom_time'){
                                                $con.find(".custom_time").removeClass("d-none");
                                            }
                                        })
                                        $("select[name=form_recursion]").trigger("change");
                                    </script>
                                </div>
                            </fieldset>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><?= __('admin.form_feature_image') ?></label><br>
                                <div class="col-sm-9">
                                    <div class="fileUpload btn btn-sm btn-primary">
                                        <span><?= __('admin.choose_file') ?></span>
                                        <input id="form_fevi_icon" onchange="readURL(this,'#form_fevi_icon-img')" name="form_fevi_icon" class="upload" type="file">
                                    </div>
                                    <?php $form_fevi_icon = $form['fevi_icon'] != '' ? 'assets/images/form/favi/'.$form['fevi_icon'] : 'assets/images/no_image_available.png' ; ?>
                                    <img src="<?php echo base_url($form_fevi_icon); ?>" id="form_fevi_icon-img" class="thumbnail" border="0" width="220px">
                                </div>
                            </div>
                           
                            <div class="form-group">
                                <label class="control-label" ><?= __('admin.allow_for_product'); ?></label>
                                <select class="form-control" name="allow_for">
                                    <option value="A"><?= __('admin.all'); ?></option>
                                    <option value="S" <?= $form['allow_for'] == 'S' ? 'selected': '' ?>>Selected Only</option>
                                </select>
                            </div>

                            <div class="select-product">
                                <div class="well">
                                    <table class="simple-table">
                                        <tr>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Type</th>
                                            <th>Allow Shipping</th>
                                        </tr>
                                        <tr><td colspan="100%">&nbsp;</td></tr>
                                        <?php $ids =explode(",", $form['product']);
                                        foreach ($product as $key => $p) { ?>
                                            <tr>
                                                <td>
                                                    <div class="checkbox">
                                                        <label><input type="checkbox" <?= in_array($p['product_id'], $ids) ? 'checked' : '' ?> name="product[]" value="<?= $p['product_id'] ?>"> <?= $p['product_name'] ?></label>
                                                    </div>
                                                </td>
                                                <td><?= c_format($p['product_price']) ?></td>
                                                <td><?= product_type($p['product_type']) ?></td>
                                                <td><?= $p['allow_shipping'] ? 'Yes' : 'No' ?></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                            </div>
                          
                            <div class="form-group">
                                <label class="control-label" ><?= __('admin.coupon'); ?></label>
                                <select class="form-control" name="coupon">
                                    <option value="">No Selected</option>
                                    <?php foreach ($coupons as $key => $value) { ?>
                                        <option value="<?= $value['form_coupon_id'] ?>" <?= $value['form_coupon_id'] == $form['coupon'] ? 'selected': '' ?>><?= $value['name'] ?></option>
                                    <?php } ?>                                            
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label" ><?= __('admin.footer_content'); ?></label>
                                <input type="text" class="form-control" name="footer_title" value="<?= $form['footer_title'] ?>">
                            </div>

                            <div class="form-group">
                                <label class="control-label" ><?= __('admin.footer_google_analitics'); ?></label>
                                <textarea cols="5" rows="5" class="form-control" name="google_analitics"><?= $form['google_analitics'] ?></textarea>                                        
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card mb-3">
                        <div class="card-header"><h4 class="header-title m-0">Commission Settings</h4></div>
                        <div class="card-body">
                            <fieldset class="custom-design mb-2">
                                <legend>Affiliate Commission</legend>
                                <div class="form-group">
                                    <label class="control-label"><?= __('admin.form_sale_commission') ?></label>
                                    <div>
                                        <?php
                                            $selected_commition_type = $form['sale_commision_type'];
                                            $selected_commision_value = $form['sale_commision_value'];
                                            $commission_type= array(
                                                'default'    => 'Default',
                                                'percentage' => 'Percentage (%)',
                                                'fixed'      => 'Fixed',
                                            );
                                        ?>
                                        <select name="form_commision_type" class="form-control showonchange">
                                            <?php foreach ($commission_type as $key => $value) { ?>
                                                <option <?= $key == $selected_commition_type ? 'selected' : '' ?> value="<?= $key ?>"><?= $value ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="toggle-container ">
                                        <div class="default-value">
                                            <small class="text-muted d-block">
                                                <?php
                                                    $commnent_line = "<b>Default Commission: </b>";
                                                    if($setting['product_commission_type'] == ''){
                                                        $commnent_line .= ' Warning : Default Commission Not Set';
                                                    }
                                                    else if($setting['product_commission_type'] == 'percentage'){
                                                        $commnent_line .= 'Percentage : '. (float)$setting['product_commission'] .'%';
                                                    }
                                                    else if($setting['product_commission_type'] == 'fixed'){
                                                        $commnent_line .= 'Fixed : '. c_format($setting['product_commission']);
                                                    }
                                                    echo $commnent_line;
                                                ?>
                                            </small>
                                        </div>
                                        <div class="fixed-value mt-1">
                                            <input placeholder="Enter form Sale Commission Value" name="form_commision_value" id="form_commision_value" class="form-control" value="<?php echo $selected_commision_value; ?>" type="text">
                                        </div>
                                    </div>

                                    <script type="text/javascript">
                                        $("select[name=form_commision_type]").on("change",function(){
                                            $con = $(this).parents(".form-group");
                                            $con.find(".toggle-container .fixed-value, .toggle-container .default-value").addClass('d-none');

                                            if($(this).val() == 'default'){
                                                $con.find(".toggle-container .default-value").removeClass("d-none");
                                            }else{
                                                $con.find(".toggle-container .fixed-value").removeClass("d-none");
                                            }
                                        })
                                        $("select[name=form_commision_type]").trigger("change");
                                    </script>
                                </div>
                                <div class="form-group ">
                                    <label class="control-label"><?= __('admin.form_click_commission') ?></label>
                                    <?php
                                        $selected_commition_type = $form['click_commision_type'];
                                        $form_click_commision_ppc = $form['click_commision_ppc'];
                                        $form_click_commision_per = $form['click_commision_per'];
                                    ?>
                                    <select name="form_click_commision_type" class="form-control showonchange">
                                        <option <?= 'default' == $selected_commition_type ? 'selected' : '' ?> value="default"><?= __('admin.default') ?></option>
                                        <option <?= 'custom' == $selected_commition_type ? 'selected' : '' ?> value="custom"><?= __('admin.custom') ?></option>
                                    </select>
                                    <div class="toggle-container">                                            
                                        <div class="default-value">
                                            <small class="text-muted d-block">
                                                <?php
                                                    $commnent_line = "<b>Default Commission: </b>";
                                                    if($setting['product_ppc'] && $setting['product_noofpercommission']){
                                                        $commnent_line .= c_format($setting['product_ppc']) ." Per ". (int)$setting['product_noofpercommission'] ." Clicks";
                                                    } else{
                                                        $commnent_line .= ' Warning : Default Commission Not Set';
                                                    }
                                                    echo $commnent_line;
                                                ?>
                                            </small>
                                        </div>
                                        <div class="fixed-value">
                                            <div class="comm-group">
                                                <div>
                                                    <div class="input-group mt-2">
                                                        <div class="input-group-prepend"><span class="input-group-text">Click</span></div>
                                                        <input placeholder="Clicks" name="form_click_commision_ppc" id="form_click_commision_ppc" class="form-control" value="<?php echo $form_click_commision_ppc; ?>" type="text">
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="input-group mt-2">
                                                        <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                                        <input placeholder="Amount" name="form_click_commision_per" id="form_click_commision_value" class="form-control" value="<?php echo $form_click_commision_per; ?>" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <script type="text/javascript">
                                            $("select[name=form_click_commision_type]").on("change",function(){
                                                $con = $(this).parents(".form-group");
                                                $con.find(".toggle-container .fixed-value, .toggle-container .default-value").addClass('d-none');

                                                if($(this).val() == 'default'){
                                                    $con.find(".toggle-container .default-value").removeClass("d-none");
                                                }else{
                                                    $con.find(".toggle-container .fixed-value").removeClass("d-none");
                                                }
                                            })
                                            $("select[name=form_click_commision_type]").trigger("change");
                                        </script>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="mt-2">
                        <div class="text-right">
                            <span class="loading-submit"></span>
                            <button type="button" rtype='save_stay' class="btn btn-lg btn-default btn-submit btn-success" name="save">Save</button>
                            <button type="button" rtype='' class="btn btn-lg btn-default btn-submit btn-success" name="save">Save & Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div> 
</div>

<script type="text/javascript">
    $('#endtime').datetimepicker({
        format:'d-m-Y H:i',
        inline:true,
    });

    $('#setCustomTime').on('change', function(){
        $(".custom_time_container").hide();
        if($(this).prop("checked")){
            $(".custom_time_container").show();
        }
    });

    var btn;
    $(".datepicker").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
        format:"dd-mm-yyyy"
    })

    
    $('.btn-submit').click(function(){
        $('.redirect').val($(this).attr('rtype'));
        $("#form_form").submit();
      
    })

    $('[name="allow_for"]').change(function(){
        $(".select-product").hide();
        if($(this).val() == 'S') $(".select-product").show();
    });

    $(".datepicker").each(function(){
        var d= $(this).val().split("-");
        if(d[0]){
            var date = d[1]  + "-" + d[2] + "-" + d[0];
            $(this).datepicker('update', new Date(date))
        }
        else{ $(this).val(''); }
    })


    $(document).on('ready',function() {
        sumNote($('.summernote-img'));
    });

    //$(".showonchange").trigger('change');
    $("#form_form").submit(function(evt){
        evt.preventDefault();
        var formData = new FormData(this);
        
        formData = formDataFilter(formData);
        $this = $(this);
        $btn = $('.btn-submit');

        $btn.btn("loading");
        $.ajax({
            url:'<?= base_url('admincontrol/save_form') ?>',
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
            error:function(){ $btn.btn("reset"); },
            success:function(result){
                $('.loading-submit').hide();
                $btn.btn("reset");
                $this.find(".has-error").removeClass("has-error");
                $this.find("span.text-danger").remove();
                
                if(result['location']){
                    window.location = result['location'];
                }
                if(result['errors']){
                    $.each(result['errors'], function(i,j){
                        $ele = $this.find('[name="'+ i +'"]');
                        if($ele){
                            $ele.parents(".form-group").addClass("has-error");
                            $ele.after("<span class='text-danger'>"+ j +"</span>");
                        }
                    });
                }
            },
        })
        return false;
    });

    $(document).on('change', '#recursion_type', function(){
        var recursion_type = $(this).val();     
        if( recursion_type == 'custom_time' ){
            $('.custom_time').show();
        }else{
            $('.custom_time').hide();
        }
    });

    $(document).on('change', '#recur_day, #recur_hour, #recur_minute', function(){
        var days = $('#recur_day').val();
        var hours = $('#recur_hour').val();
        var minutes = $('#recur_minute').val();
        var total_minutes;      
        
        total_hours = parseInt(days*24) + parseInt(hours);
        total_minutes = parseInt(total_hours*60) + parseInt(minutes);
        $('.custom_time').find('input[name="recursion_custom_time"]').val(total_minutes);

    });

    $(document).ready(function() {
        $('[name="allow_for"]').trigger("change");
    });
</script>