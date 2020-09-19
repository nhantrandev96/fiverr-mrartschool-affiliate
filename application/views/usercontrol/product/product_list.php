<?php
    $db =& get_instance();
    $userdetails=$db->userdetails();
    $store_setting =$db->Product_model->getSettings('store');
?>

<?php foreach($productlist as $product){ ?>
    <?php
        if(empty($product['view'])){
            $product['view'] = 0;
        }
        if(empty($product['click'])){
            $product['click'] = 0;
        }
        if(empty($product['commission'])){
            $product['commission'] = 0;
        }
    
        $productLink = base_url('store/'. base64_encode($userdetails['id']) .'/product/'.$product['product_slug'] );
    ?>
    <tr>
        <td>

            <div class="tooltip-copy">

                <?php if($product['product_type'] == 'downloadable'){ ?>
                    <img src="<?= base_url('assets/images/download.png') ?>" width="20px">  
                <?php } ?>
                <?php echo $product['product_name'];?>
                <div><small>
                    <a href="javascript:void(0)" copyToClipboard="<?= $productLink ?>">Copy link</a> /
                    <a target="_blank" href="<?= $productLink ?>"><?= __('user.public_npage') ?></a> / 
                    <a href="javascript:void(0);" onclick="generateCode(<?php echo $product['product_id'];?>);" ><?= __('user.get_ncode') ?></a>
                </small></div>
            </div>
        </td>
        <td>
            <div class="tooltip-copy">
                <img width="50px" height="50px" src="<?php echo base_url();?>/assets/images/product/upload/thumb/<?php echo $product['product_featured_image'];?>" ><br>
            </div>
        </td>
        <td class="txt-cntr"><?php echo c_format($product['product_price']); ?>
            <br>
        </td>
        <td class="txt-cntr">
            <?php echo $product['product_sku'];?>
        </td>
        <td class="txt-cntr">
            <b>Sale</b> : 
            <?php
                if($product['seller_id']){
                    $seller = $this->Product_model->getSellerFromProduct($product['product_id']);
                    $seller_setting = $this->Product_model->getSellerSetting($seller->user_id);

                    $commnent_line = "";
                    if($seller->affiliate_sale_commission_type == 'default'){ 
                        if($seller_setting->affiliate_sale_commission_type == ''){
                            $commnent_line .= ' Warning : Default Commission Not Set';
                        }
                        else if($seller_setting->affiliate_sale_commission_type == 'percentage'){
                            $commnent_line .=  (float)$seller_setting->affiliate_commission_value .'%';
                        }
                        else if($seller_setting->affiliate_sale_commission_type == 'fixed'){
                            $commnent_line .= c_format($seller_setting->affiliate_commission_value);
                        }
                    } else if($seller->affiliate_sale_commission_type == 'percentage'){
                        $commnent_line .=  (float)$seller->affiliate_commission_value .'%';
                    } else if($seller->affiliate_sale_commission_type == 'fixed'){
                        $commnent_line .= c_format($seller->affiliate_commission_value);
                    } 

                    echo '<b>Sale</b> : ' .$commnent_line;

                    $commnent_line = "";
                    if($seller->affiliate_click_commission_type == 'default'){ 
                        $commnent_line .= c_format($seller_setting->affiliate_click_amount) ." Per ". (int)$seller_setting->affiliate_click_count ." Clicks";
                    } else{
                        $commnent_line .= c_format($seller->affiliate_click_amount) ." Per ". (int)$seller->affiliate_click_count ." Clicks";
                    } 
                    echo '<br><b>Click</b> : ' .$commnent_line;
                } else {

                    if($product['product_commision_type'] == 'default'){
                        if($default_commition['product_commission_type'] == 'percentage'){
                            echo $default_commition['product_commission']. "% Per Sale";
                        } else {
                            echo c_format($default_commition['product_commission']) ." Per Sale";
                        }
                    } else if($product['product_commision_type'] == 'percentage'){
                        echo $product['product_commision_value']. "% Per Sale";
                    } else{
                        echo c_format($product['product_commision_value']) ." Per Sale";
                    }
                    
                    echo '<br> <b>Click</b> :';
                    
                    if($product['product_click_commision_type'] == 'default'){
                        echo "<small>{$default_commition['product_noofpercommission']} Click for  ";    
                        echo c_format($default_commition['product_ppc']);
                        echo "</small>";
                    }
                    else{
                        echo "<small>PPC : {$product['product_click_commision_per']} Click for : ";
                        echo c_format($product['product_click_commision_ppc']) ."</small>";
                    }
                }
            ?>
            

            <?php 
                if($product['product_recursion_type']){
                    if($product['product_recursion_type'] == 'custom'){
                        if($product['product_recursion'] != 'custom_time'){
                            echo '<b>Recurring </b> : ' . $product['product_recursion'];
                        } else {
                            echo '<b>Recurring </b> : '. timetosting($product['recursion_custom_time']);
                        }
                    } else{
                        if($pro_setting['product_recursion'] == 'custom_time' ){
                            echo '<b>Recurring </b> : '. timetosting($pro_setting['recursion_custom_time']);
                        } else {
                            echo '<b>Recurring </b> : '. $pro_setting['product_recursion'];
                        }
                    }
                }
            ?>
        </td>
        <td class="txt-cntr">
            <?php echo $product['order_count'];?> / <?php echo c_format($product['commission']); ?>
        </td>
        <td class="txt-cntr">
            <?php echo (int)$product['commition_click_count'];?> / <?php echo c_format($product['commition_click']); ?>
        </td>
        <td class="txt-cntr">
            <?php echo c_format(
                ((float)$product['commition_click'] + (float)$product['commission'])
                ); ?>
        </td>
        <td class="txt-cntr">
            <div class="share-list">
                <a onclick="shareinsocialmedia('https://www.facebook.com/sharer/sharer.php?u=<?= $productLink ?>&amp;title=Buy Product and earn by affiliate program')" href="javascript:void(0)"><i class="fa fa-facebook fa-6" aria-hidden="true"></i></a>
                <a onclick="shareinsocialmedia('https://plus.google.com/share?url=<?= $productLink ?>')" href="javascript:void(0)"><i class="fa fa-google-plus fa-6" aria-hidden="true"></i></a>
                <a onclick="shareinsocialmedia('http://www.linkedin.com/shareArticle?mini=true&amp;url=<?= $productLink ?>&amp;title=Buy Product and earn by affiliate program')" href="javascript:void(0)"><i class="fa fa-linkedin fa-6" aria-hidden="true"></i></a>
                <a onclick="shareinsocialmedia('http://twitter.com/home?status=Buy Product and earn by affiliate program+<?= $productLink ?>')" href="javascript:void(0)"><i class="fa fa-twitter fa-6" aria-hidden="true"></i></a>
                <a href="mailto:?subject=Buy Product and earn by affiliate program&amp;body=Check out this site <?= $productLink ?>" title="Share by Email">
                    <i class="fa fa-envelope cursors" aria-hidden="true" style="color:#2a3f54"></i>
                </a>
            </div>
        </td>
    </tr>
<?php } ?>