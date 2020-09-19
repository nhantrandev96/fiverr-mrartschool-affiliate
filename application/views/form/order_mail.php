<label>Purchase Details</label>
<table class="table" style="width: 100%;border-collapse: collapse;border: 1px solid">
    <thead>
        <tr>
            <th colspan="2" style="border: 1px solid;font-size: 13px;padding: 5px 10px;">Name</th>
            <th style="border: 1px solid;font-size: 13px;padding: 5px 10px;">Unit Price</th>
            <th style="border: 1px solid;font-size: 13px;padding: 5px 10px;">Quantity</th>
            <th style="border: 1px solid;font-size: 13px;padding: 5px 10px;">Discount</th>
            <th style="border: 1px solid;font-size: 13px;padding: 5px 10px;">Total</th>
        </tr>
        <?php foreach ($products as $key => $product) { ?>
            <tr>
                <td style="border: 1px solid;font-size: 13px;padding: 5px 10px;"><img src="<?= $product['image'] ?>" style="width: 50px;height: 50px;border: 1px solid;font-size: 13px;padding: 5px 10px;"></td>
                <td style="border: 1px solid;font-size: 13px;padding: 5px 10px;">
                    <?php echo $product['product_name'];?>
                    
                    <?php if($product['commission'] && $show_commition) { ?>
                        <br><hr>
                        <b><?= __('admin.name') ?></b> : <?php echo $product['refer_name']; ?>
                        
                        <br>
                        <b><?= __('admin.email') ?></b> : <?php echo $product['refer_email']; ?>
                        
                        <br>
                        <b><?= __('admin.product_commission') ?></b> : <?php echo c_format($product['commission']); ?>
                    <?php } ?>
                    <?php if($product['coupon_discount'] > 0){ ?>
                        <p class="couopn-code-text">
                            Code : <span class="c-name"> <?= $product['coupon_code'] ?></span> Applied
                        </p>
                    <?php } ?>
                </td>
                <td style="border: 1px solid;font-size: 13px;padding: 5px 10px;"><?php echo c_format($product['price']); ?></td>
                <td style="border: 1px solid;font-size: 13px;padding: 5px 10px;"><?php echo $product['quantity']; ?></td>
                <td style="border: 1px solid;font-size: 13px;padding: 5px 10px;"><?php echo c_format($product['coupon_discount']); ?></td>
                <td style="border: 1px solid;font-size: 13px;padding: 5px 10px;"><?php echo c_format($product['total']); ?></td>
            </tr>
        <?php } ?>
        <?php foreach ($totals as $key => $total) { ?>
        <tr>
            <td colspan="5" style="text-align: right;"><?= $total['text'] ?></td>
            <td style="border: 1px solid;font-size: 13px;padding: 5px 10px;"><?php echo c_format($total['value']); ?></td>
        </tr>
        <?php } ?>
    </thead>
</table>
<?php if($order['payment_method'] == 'bank_transfer'){ ?>
    <br><br>
    <table width="100%" border="0" style="width: 100%;border-collapse: collapse;border: 1px solid">
        <tr>
            <td style="border: 1px solid;font-size: 13px;padding: 5px 10px;">
                <label class="control-label"><b>Bank Transfer Instruction</b></label>
                <pre class="well"><?php echo $paymentsetting['bank_transfer_instruction'] ?></pre>
            </td>
        </tr>
    </table>
<?php } ?>

<br><br>
<label>Payment History</label>
<table class="table" style="width: 100%;border-collapse: collapse;border: 1px solid">
    <thead>
        <th class="border-top-0" style="border: 1px solid;font-size: 13px;padding: 5px 10px;">Mode</th>
        <th class="border-top-0" style="border: 1px solid;font-size: 13px;padding: 5px 10px;">Transaction Id</th>
        <th class="border-top-0" style="border: 1px solid;font-size: 13px;padding: 5px 10px;">Payment Status</th>
    </thead>
    <tbody>
        <?php if($order['status'] == 0){ ?>
            <tr>
                <td colspan="100%" style="border: 1px solid;font-size: 13px;padding: 5px 10px;">
                    <p class="text-muted text-center"> Waiting for payment status </p>
                </td>
            </tr>
        <?php } ?>
        <?php foreach ($payment_history as $key => $value) { ?>
        <tr>
            <td style="border: 1px solid;font-size: 13px;padding: 5px 10px;"><?php echo $value['payment_mode'];?></td>
            <td style="border: 1px solid;font-size: 13px;padding: 5px 10px;"><?php echo $order['txn_id'];?></td>
            <td style="border: 1px solid;font-size: 13px;padding: 5px 10px;"><?php echo $value['paypal_status'] ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php if($order['allow_shipping']){ ?>
<br><br>
<label>Shipping Details</label>
<table class="table table-hover" style="width: 100%;border-collapse: collapse;border: 1px solid">
    <thead>
        <th style="border: 1px solid;font-size: 13px;padding: 5px 10px;">Address</th>
        <th style="border: 1px solid;font-size: 13px;padding: 5px 10px;">Country</th>
        <th style="border: 1px solid;font-size: 13px;padding: 5px 10px;">State</th>
        <th style="border: 1px solid;font-size: 13px;padding: 5px 10px;">City</th>
        <th style="border: 1px solid;font-size: 13px;padding: 5px 10px;">Postal Code</th>
    </thead>
    <tr>
        <td style="border: 1px solid;font-size: 13px;padding: 5px 10px;"><?php echo $order['address'] ?></td>
        <td style="border: 1px solid;font-size: 13px;padding: 5px 10px;"><?php echo $order['country_name'] ?></td>
        <td style="border: 1px solid;font-size: 13px;padding: 5px 10px;"><?php echo $order['state_name'] ?></td>
        <td style="border: 1px solid;font-size: 13px;padding: 5px 10px;"><?php echo $order['city'] ?></td>
        <td style="border: 1px solid;font-size: 13px;padding: 5px 10px;"><?php echo $order['zip_code'] ?></td>
    </tr>
</table>
<?php } ?>
<br><br>
<label>Order History</label>
<table class="table" style="width: 100%;border-collapse: collapse;border: 1px solid">
    <thead>
        <tr>
            <th width="50px">#</th>
            <th width="150px">Status</th>
            <th style="border: 1px solid;font-size: 13px;padding: 5px 10px;">Comment</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!$order_history){ ?>
            <tr>
                <td colspan="100%" style="border: 1px solid;font-size: 13px;padding: 5px 10px;">
                    <p style="text-align: center;">No any order history </p>
                </td>
            </tr>
        <?php } ?>
        <?php foreach ($order_history as $key => $value) { ?>
        <tr>
            <td style="border: 1px solid;font-size: 13px;padding: 5px 10px;">#<?= $key ?></td>
            <td style="border: 1px solid;font-size: 13px;padding: 5px 10px;"><?= $status[$value['order_status_id']] ?></td>
            <td style="border: 1px solid;font-size: 13px;padding: 5px 10px;"><?= $value['comment'] ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<br><br>