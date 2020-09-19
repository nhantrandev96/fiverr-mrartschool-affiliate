<link href="<?php echo base_url(); ?>assets/css/datepicker.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.js"></script>

        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-header translation-header">
                        <h4 class="card-title pull-left main-h4 "><?= __('admin.form_coupon_manage'); ?></h4>
                    </div>
                    <div class="card-body">
                        
                        <div class="table-rep-plugin">
                            <div class="table-responsive b-0">
                                <form id="form_coupon_form">
                                    <input type="hidden" class="form-control" name="id" value="<?= (int)$form_coupon['form_coupon_id'] ?>">
                                    <div class="form-group">
                                        <label class="control-label" ><?= __('admin.form_coupon_name');?></label>
                                        <input type="text" class="form-control" name="name" value="<?= $form_coupon['name'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" ><?= __('admin.form_coupon_code');?></label>
                                        <input type="text" class="form-control" name="code" value="<?= $form_coupon['code'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" ><?= __('admin.type');?></label>
                                        <select class="form-control" name="type">
                                            <option value="P"><?= __('admin.percentage');?></option>
                                            <option value="F"><?= __('admin.fixed_amount');?></option>
                                        </select>
                                    </div>
                                   
                                    <div class="form-group">
                                        <label class="control-label" ><?= __('admin.discount');?></label>
                                        <input type="text" class="form-control" name="discount" value="<?= $form_coupon['discount'] ?>">
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label" ><?= __('admin.date_start');?></label>
                                                <input type="text" class="form-control datepicker" name="date_start" value="<?= $form_coupon['date_start'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label" ><?= __('admin.date_end');?></label>
                                                <input type="text" class="form-control datepicker" name="date_end" value="<?= $form_coupon['date_end'] ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" ><?= __('admin.uses_per_customer');?></label>
                                        <input type="text" class="form-control" name="uses_total" value="<?= $form_coupon['uses_total'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" ><?= __('admin.status');?></label>
                                        <select class="form-control" name="status">
                                            <option value="1"><?= __('admin.enable');?></option>
                                            <option value="0" <?= $form_coupon['allow_for'] == '0' ? 'selected': '' ?>><?= __('admin.disable');?></option>
                                        </select>
                                    </div>
                                  
                                    <div class="form-group text-right"> 
                                        <button type="submit" class="btn btn-primary btn-submit"><?= __('admin.save');?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> 
            </div> 
        </div>
<script type="text/javascript">
    $(".datepicker").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
        format:"dd-mm-yyyy"
    })
    $('[name="allow_for"]').on('change',function(){
        $(".select-product").hide();
        if($(this).val() == 'S') $(".select-product").show();
    });
    $('[name="allow_for"]').trigger("change");
    $(".datepicker").each(function(){
        var d= $(this).val().split("-");
        if(d[0]){
            var date = d[1]  + "-" + d[2] + "-" + d[0];
            $(this).datepicker('update', new Date(date))
        }
        else{ $(this).val(''); }
    })
    
    $("#form_coupon_form").on('submit',function(){
        $this = $(this);
        $.ajax({
            url:'<?= base_url('admincontrol/save_form_coupon') ?>',
            type:'POST',
            dataType:'json',
            data:$this.serialize(),
            beforeSend:function(){$this.find(".btn-submit").button("loading");},
            complete:function(){$this.find(".btn-submit").button("reset");},
            success:function(result){
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
                    })
                }
            },
        })
        return false;
    })
</script>