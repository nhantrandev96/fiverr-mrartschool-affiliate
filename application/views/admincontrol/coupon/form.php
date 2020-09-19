<link href="<?php echo base_url(); ?>assets/css/datepicker.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.js"></script>

        <div class="row">

            <div class="col-12">

                <div class="card m-b-30">

                    <div class="card-header translation-header">

                        <h4 class="card-title pull-left main-h4 "><?= __('admin.coupon_manage'); ?></h4>

                    </div>

                    <div class="card-body">

                        

                        <div class="table-rep-plugin">

                            <div class="table-responsive b-0">

                                <form id="coupon_form">

                                    <input type="hidden" class="form-control" name="id" value="<?= (int)$coupon['coupon_id'] ?>">

                                    <div class="form-group">

                                        <label class="control-label" ><?= __('admin.coupon_name') ?></label>

                                        <input type="text" class="form-control" name="name" value="<?= $coupon['name'] ?>">

                                    </div>

                                    <div class="form-group">

                                        <label class="control-label" ><?= __('admin.coupon_code') ?></label>

                                        <input type="text" class="form-control" name="code" value="<?= $coupon['code'] ?>">

                                    </div>

                                    <div class="form-group">

                                        <label class="control-label" ><?= __('admin.type') ?></label>

                                        <select class="form-control" name="type">

                                            <option value="P"><?= __('admin.percentage') ?></option>

                                            <option value="F"><?= __('admin.fixed_amount') ?></option>

                                        </select>

                                    </div>

                                    <div class="form-group">

                                        <label class="control-label" ><?= __('admin.allow_for_product') ?></label>

                                        <select class="form-control" name="allow_for">

                                            <option value="A"><?= __('admin.all') ?></option>

                                            <option value="S" <?= $coupon['allow_for'] == 'S' ? 'selected': '' ?>><?= __('admin.selected_only') ?></option>

                                        </select>

                                    </div>

                                    <div class="select-product">

                                        <div class="well">

                                            <?php $ids =explode(",", $coupon['products']);

                                            foreach ($product as $key => $p) { ?>

                                                <div class="checkbox">

                                                    <label><input type="checkbox" <?= in_array($p['product_id'], $ids) ? 'checked' : '' ?> name="products[]" value="<?= $p['product_id'] ?>"> <?= $p['product_name'] ?></label>

                                                </div>

                                            <?php } ?>

                                        </div>

                                    </div>

                                    <div class="form-group">

                                        <label class="control-label" ><?= __('admin.discount') ?></label>

                                        <input type="text" class="form-control" name="discount" value="<?= $coupon['discount'] ?>">

                                    </div>

                                    <div class="row">

                                        <div class="col-sm-6">

                                            <div class="form-group">

                                                <label class="control-label" ><?= __('admin.date_start') ?></label>

                                                <input type="text" class="form-control datepicker" name="date_start" value="<?= $coupon['date_start'] ?>">

                                            </div>

                                        </div>

                                        <div class="col-sm-6">

                                            <div class="form-group">

                                                <label class="control-label" ><?= __('admin.date_end') ?></label>

                                                <input type="text" class="form-control datepicker" name="date_end" value="<?= $coupon['date_end'] ?>">

                                            </div>

                                        </div>

                                    </div>

                                    <div class="form-group">

                                        <label class="control-label" ><?= __('admin.uses_per_customer') ?></label>

                                        <input type="text" class="form-control" name="uses_total" value="<?= $coupon['uses_total'] ?>">

                                    </div>

                                    <div class="form-group">

                                        <label class="control-label" ><?= __('admin.status') ?></label>

                                        <select class="form-control" name="status">

                                            <option value="1"><?= __('admin.enable') ?></option>

                                            <option value="0" <?= $coupon['status'] == '0' ? 'selected': '' ?>><?= __('admin.disable') ?></option>

                                        </select>

                                    </div>

                                  

                                    <div class="form-group text-right"> 

                                        <button type="submit" class="btn btn-primary btn-submit"><?= __('admin.save') ?></button>

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

    

    $("#coupon_form").on('submit',function(){

        $this = $(this);

        $.ajax({

            url:'<?= base_url('admincontrol/save_coupon') ?>',

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