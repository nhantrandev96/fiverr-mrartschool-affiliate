<?php if(isset($warning)){ ?>
    <div class="modal-header">
        <h5 class="modal-title mt-0">Withdrawal limit</h5>
    </div>
    <div class="modal-body p-0">        
        <div class="p-3 py-3 wallet-warning">
            <div class="alert alert-warning"><?= $warning ?></div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
<?php } else { ?>
<div class="modal-body p-0">
    <div id="accordion">
        <?php $index = 0; foreach ($payment_methods as $key => $value) { if ($value['status']) { ?>
            <div class="wpayment border-radius-0">
                <div class="border-bottom border-radius-0 wpayment-header p-3 <?= $index == 0 ? 'active-payment' : '' ?>" data-tab='collapse-<?= $value['code'] ?>'>
                    <h5 class="m-0 font-16" >
                        <?= $value['title'] ?>
                    </h5>
                </div>
            </div>
        <?php $index++; } } ?>

        <div class="wpayment-container">
            <?php $index = 0; foreach ($payment_methods as $key => $value) { if ($value['status']) { ?>
                <div id="collapse-<?= $value['code'] ?>" class="<?= $index == 0 ? '' : 'd-none'  ?> wpayment-body">
                    <h3 class="payment-heading">Get Paid with <?= $value['title'] ?></h3>
                    <form id="payment-form-<?= $value['code'] ?>">
                        <input type="hidden" name="ids" value="<?= $ids ?>">
                        <input type="hidden" name="code" value="<?= $value['code'] ?>">
                        <?= $value['user_setting'] ?>
                        <div class="text-right">
                            <button class="btn btn-submit btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            <?php $index++; } } ?>
        </div>

        <?php if ($index == 0) { ?>
            <div class="border-right-0 font-20 mb-0 well">
                <p class="text-center">Warning: No Payment options are available. Please contact us for assistance!</p>
            </div>
        <?php } ?>
    </div>
</div>
<script type="text/javascript">
    $(".wpayment-header").click(function(){
        $(".wpayment-container .wpayment-body").addClass("d-none");
        var tab = $(this).attr("data-tab");
        $("#" + tab).removeClass("d-none");
        $(".wpayment-header.active-payment").removeClass("active-payment");
        $(this).addClass("active-payment");
    })
</script>

<?php  } ?>