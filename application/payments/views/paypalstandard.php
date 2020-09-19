

<?php if ($setting_data['sandbox_mode']) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> Test mode is on</div>
<?php } ?>

<form action="<?php echo $action; ?>" method="post">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="<?php echo $setting_data['email']; ?>">
    <input type="hidden" name="item_name" value="Donation">
    <input type="hidden" name="item_number" value="1">
    <input type="hidden" name="amount" value="<?php echo $order_info['total']; ?>">
    <input type="hidden" name="no_shipping" value="0">
    <input type="hidden" name="no_note" value="1">
    <input type="hidden" name="currency_code" value="USD">
    <input type="hidden" name="lc" value="AU">
    <input type="hidden" name="bn" value="PP-BuyNowBF">

    <input type="hidden" name="return" value="<?php echo $return; ?>" />
  <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>" />
  <input type="hidden" name="cancel_return" value="<?php echo $cancel_return; ?>" />
  <input type="hidden" name="paymentaction" value="<?php echo $paymentaction; ?>" />
  <input type="hidden" name="custom" value="<?php echo $custom; ?>" />
    <div class="buttons">
      <div class="pull-right">
        <button class="btn btn-default" onclick='backCheckout()'>Back</button>
          <input type="submit" value="Confirm" class="btn btn-primary" />
      </div>
    </div>
    <!-- <input type="image" src="https://www.paypal.com/en_AU/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
    <img alt="" border="0" src="https://www.paypal.com/en_AU/i/scr/pixel.gif" width="1" height="1"> -->
</form>

<!-- <form action="<?php echo $action; ?>" method="post">
  <input type="hidden" name="cmd" value="_cart" />
  <input type="hidden" name="upload" value="1" />
  <input type="hidden" name="business" value="<?php echo $setting_data['email']; ?>" />
  
  <?php foreach ($products as $i => $product) { ?>
	  <input type="hidden" name="item_name_<?php echo $i; ?>" value="<?php echo $product['product_name']; ?>" />
	  <input type="hidden" name="amount_<?php echo $i; ?>" value="<?php echo round($product['price'],2); ?>" />
	  <input type="hidden" name="quantity_<?php echo $i; ?>" value="<?php echo $product['quantity']; ?>" />
	  <input type="hidden" name="weight_<?php echo $i; ?>" value="0" />
  <?php } ?>

  <input type="hidden" name="currency_code" value="USD" />
  <input type="hidden" name="first_name" value="<?php echo $order_info['firstname']; ?>" />
  <input type="hidden" name="last_name" value="<?php echo $order_info['lastname']; ?>" />
  <input type="hidden" name="address1" value="<?php echo $order_info['address']; ?>" />
  <input type="hidden" name="address2" value="" />
  <input type="hidden" name="city" value="<?php echo $order_info['city']; ?>" />
  <input type="hidden" name="zip" value="<?php echo $order_info['zip_code']; ?>" />
  <input type="hidden" name="country" value="" />
  <input type="hidden" name="address_override" value="0" />
  <input type="hidden" name="email" value="<?php echo $order_info['email']; ?>" />
  <input type="hidden" name="invoice" value="INV-<?php echo $order_info['id']; ?>" />
  <input type="hidden" name="lc" value="" />
  <input type="hidden" name="rm" value="2" />
  <input type="hidden" name="no_note" value="1" />
  <input type="hidden" name="no_shipping" value="1" />
  <input type="hidden" name="charset" value="utf-8" />
  <input type="hidden" name="return" value="<?php echo $return; ?>" />
  <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>" />
  <input type="hidden" name="cancel_return" value="<?php echo $cancel_return; ?>" />
  <input type="hidden" name="paymentaction" value="<?php echo $paymentaction; ?>" />
  <input type="hidden" name="custom" value="<?php echo $order_info['id']; ?>" />
  <input type="hidden" name="bn" value="OpenCart_2.0_WPS" />
  <div class="buttons">
    <div class="pull-right">
    	<button class="btn btn-default" onclick='backCheckout()'>Back</button>
      	<input type="submit" value="Confirm" class="btn btn-primary" />
    </div>
  </div>
</form> -->

