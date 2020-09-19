<?php 
    $db =& get_instance();
    $userdetails=$db->userdetails();
    $pro_setting = $this->Product_model->getSettings('productsetting');
    $form_setting = $this->Product_model->getSettings('formsetting');
?>
<?php foreach($data_list as $index => $product){ ?>
    <?php
        //$display_class = $index >= $pagination ? 'd-none' : '';
        $display_class = '';
    ?>
    <?php if(isset($product['is_form'])){ ?>
        <tr class="<?= $display_class ?>">
            <td class="text-center">
                <button type="button" class="toggle-child-tr"><i class="fa fa-plus"></i></button>
            </td>
            <td><img width="50px" height="50px" src="<?php echo resize($product['fevi_icon'],100,100) ?>" ></td>
            <td>
                <?= $product['title'] ?>
                <div>
                    <small>
                        <a href="<?= $product['public_page'] ?>"  target='_black'><?= __('user.public_page'); ?></a> /
                        <a href="javascript:void(0);" onclick="generateCodeForm(<?php echo $product['form_id'];?>,this);" ><?= __('user.get_ncode') ?></a>
                    </small>    
                </div>
            </td>
            <td>
                <?php
                    echo "<b>You Will Get</b> ";
                    if($product['sale_commision_type'] == 'default'){
                        $commissionType = $form_default_commission['product_commission_type'];
                        if($form_default_commission['product_commission_type'] == 'percentage'){
                            echo $form_default_commission['product_commission'] .'% Per Sale';
                        }
                        else if($form_default_commission['product_commission_type'] == 'Fixed'){
                            echo c_format($form_default_commission['product_commission']) .' Per Sale';
                        }
                    }
                    else if($product['sale_commision_type'] == 'percentage'){
                        echo $product['sale_commision_value'] .'% Per Sale';
                    }
                    else if($product['sale_commision_type'] == 'fixed'){
                        echo c_format($product['sale_commision_value']) .' Per Sale';
                    }
                    
                    echo "<br> <b>You Will Get</b> ";
                    if($product['click_commision_type'] == 'default'){
                        if((int)$product['vendor_id']){
                            $vendor_setting = $this->db->query("SELECT * FROM vendor_setting WHERE user_id=". (int)$product['vendor_id'] ." ")->row();
                            echo c_format($vendor_setting->form_affiliate_click_amount) .' of Per '. (int)$vendor_setting->form_affiliate_click_count .' Click';
                        } else {
                            $commissionType = $form_default_commission['product_commission_type'];
                            if($form_default_commission['product_commission_type'] == 'percentage'){
                                echo c_format($form_default_commission['product_ppc']) .' of Per '. $form_default_commission['product_noofpercommission'] .' Click';
                            }
                            else if($form_default_commission['product_commission_type'] == 'Fixed'){
                                echo c_format($form_default_commission['product_ppc']) .' of Per '. $form_default_commission['product_noofpercommission'] .' Click';
                            }
                        }
                    }
                    else if($product['click_commision_type'] == 'custom') {
                        echo c_format($product['click_commision_per']) .' of Per '. $product['click_commision_ppc'] .' Click';
                    }
                    ?>
                <div>
                    <?php 
                        if($product['form_recursion_type']){
                            if($product['form_recursion_type'] == 'custom'){
                                if($product['form_recursion'] != 'custom_time'){
                                    echo '<b>'. __('user.recurring') .' </b> : ' . __('user.'. $product['form_recursion']);
                                } else {
                                    echo '<b>'. __('user.recurring') .' </b> : '. timetosting($product['recursion_custom_time']);
                                }
                            } else{
                                if($form_setting['form_recursion'] == 'custom_time' ){
                                    echo '<b>'. __('user.recurring') .' </b> : '. timetosting($form_setting['recursion_custom_time']);
                                } else {
                                    echo '<b>'. __('user.recurring') .' </b> : '. __('user.'. $form_setting['form_recursion']);
                                }
                            }
                        }
                    ?>
                </div>
            </td>
            <td>
                <div class="form-group m-0">
                    <div class="copy-input input-group">
                        <input readonly="readonly" value="<?= $product['public_page'] ?>" class="form-control">
                        <button type="button" copyToClipboard="<?= $product['public_page'] ?>" class="input-group-addon" >
                        </button>
                    </div>
                </div>
            </td>
            <td>
                <div class="code-share-<?= $index?>"></div>
                <script type="text/javascript">
                   
                        $(".code-share-<?= $index ?>").jsSocials({
                            url: "<?= $product['public_page'] ?>",
                            showCount: false,
                            showLabel: false,
                            shareIn: "popup",
                            shares: ["email", "twitter", "facebook", "googleplus", "linkedin", "pinterest", "stumbleupon", "whatsapp"]
                        });
                
                </script>
            </td>
        </tr>
        <tr class="detail-tr">
            <td colspan="100%">
                <div>
                    <ul>
                        <li><b><?= __('admin.coupon_code'); ?>: </b> <span><?= $product['coupon_code'] ? $product['coupon_code'] : 'N/A' ?></span></li>
                        <li><b><?= __('admin.coupon_use'); ?>: </b> <span><?= ($product['coupon_name'] ? $product['coupon_name'] : '-').' / '.$product['count_coupon'] ?></span></li>
                        <li><b><?= __('admin.sales_commission'); ?>: </b> <span><?= (int)$product['count_commission'].' / '.c_format($product['total_commission']) ?></span></li>
                        <li><b><?= __('admin.clicks_commission'); ?>: </b> <span><?= (int)$product['commition_click_count'].' / '.c_format($product['commition_click']); ?></span></li>
                        <li><b><?= __('admin.total_commission'); ?>: </b> <span><?= c_format($product['total_commission']+$product['commition_click']); ?></span></li>
                    </ul>
                </div>
            </td>
        </tr>
    <?php } else if(isset($product['is_product'])) { ?>
        <?php 
            $productLink = base_url('store/'. base64_encode($userdetails['id']) .'/product/'.$product['product_slug'] );
        ?>
        <tr class="<?= $display_class ?>">
            <td class="text-center">                                                    
                <button type="button" class="toggle-child-tr"><i class="fa fa-plus"></i></button>
            </td>
            <td><img width="50px" height="50px" src="<?php echo resize('assets/images/product/upload/thumb/'. $product['product_featured_image'] ,100,100) ?>" ></td>
            <td>
                <?php echo $product['product_name'];?>
                <div>
                    <small>
                        <a target="_blank" href="<?= $productLink ?>"><?= __('user.public_npage') ?></a> / 
                        <a href="javascript:void(0);" onclick="generateCode(<?php echo $product['product_id'];?>,this);" ><?= __('user.get_ncode') ?></a>
                    </small>
                </div>        
            </td>
            <td>
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

                    } else { ?>

                <b>You Will Get</b> : 
                <?php
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
                    ?>
                <br><b>You Will Get</b> :
                <?php
                    if($product['product_click_commision_type'] == 'default'){
                        echo c_format($default_commition['product_ppc']) ." Per {$default_commition['product_noofpercommission']} Click";   
                        echo "</small>";
                    } else{
                        echo c_format($product['product_click_commision_ppc']) ." Per {$product['product_click_commision_per']} Click";
                    }
                    ?>

                    <div>
                        <?php 
                            if($product['product_recursion_type']){
                                if($product['product_recursion_type'] == 'custom'){
                                    if($product['product_recursion'] != 'custom_time'){
                                        echo '<b>'. __('user.recurring') .' </b> : ' . __('user.'.$product['product_recursion']);
                                    } else {
                                        echo '<b>'. __('user.recurring') .' </b> : '. timetosting($product['recursion_custom_time']);
                                    }
                                } else{
                                    if($pro_setting['product_recursion'] == 'custom_time' ){
                                        echo '<b>'. __('user.recurring') .' </b> : '. timetosting($pro_setting['recursion_custom_time']);
                                    } else {
                                        echo '<b>'. __('user.recurring') .' </b> : '. __('user.'.$pro_setting['product_recursion']);
                                    }
                                }
                            }
                        ?>
                    </div>
                <?php } ?>
            </td>
            <td>
                <div class="form-group m-0">
                    <div class="copy-input input-group">
                        <input readonly="readonly" value="<?= $productLink ?>" class="form-control">
                        <button type="button" copyToClipboard="<?= $productLink ?>" class="input-group-addon" >
                        </button>
                    </div>
                </div>
            </td>
            <td>
                <div class="code-share-<?= $index?>"></div>
                <script type="text/javascript">
                    //$(document).on('ready',function(){
                        $(".code-share-<?= $index ?>").jsSocials({
                            url: "<?= $productLink ?>",
                            showCount: false,
                            showLabel: false,
                            shareIn: "popup",
                            shares: ["email", "twitter", "facebook", "googleplus", "linkedin", "pinterest", "stumbleupon", "whatsapp"]
                        });
                    //})
                </script>
            </td>
        </tr>
        <tr class="detail-tr">
            <td colspan="100%">
                <div>
                    <ul>
                        <li><b><?= __('admin.price') ?> :</b><span><?php echo c_format($product['product_price']); ?></span></li>
                        <li><b><?= __('admin.sku') ?> :</b><span><?php echo $product['product_sku'];?></span></li>
                        <li>
                            <b><?= __('admin.sales_/_commission') ?> :</b>
                            <span>
                            <?php echo $product['order_count'];?> / 
                            <?php echo c_format($product['commission']) ;?>
                            </span>
                        </li>
                        <li>
                            <b><?= __('admin.clicks_/_commission') ?> :</b>
                            <span>
                            <?php echo (int)$product['commition_click_count'];?> / <?php echo c_format($product['commition_click']) ;?>
                            </span>
                        </li>
                        <li>
                            <b><?= __('admin.total') ?> :</b>
                            <span>
                            <?php echo c_format((float)$product['commition_click'] + (float)$product['commission']); ?>
                            </span>
                        </li>
                        <li><b><?= __('admin.display') ?> :</b> <span><?= $product['on_store'] == '1' ? 'Yes' : 'No' ?></span></li>
                    </ul>
                </div>
            </td>
        </tr>
    <?php } else{ ?>
        <tr class="<?= $display_class ?>">
            <td><button type="button" class="toggle-child-tr"><i class="fa fa-plus"></i></button></td>
            <td>
                <img width="50px" height="50px" src="<?php echo resize('assets/images/product/upload/thumb/'. $product['featured_image'],100,100,1) ?>" >
            </td>
            <td>
                <?= $product['name'] ?>
                <div>
                    <small>
                        <a class="get-code" href="javascript:void(0)" data-id="<?= $product['id'] ?>"><?= __('user.get_code') ?></a>
                    </small>
                </div>          
            </td>
            <td>
                <div class="wallet-toggle ">
                    <div class="<?= $product['_tool_type'] == 'program' && $product['sale_status'] ? '' : 'd-none' ?>">
                        <?php 
                            $comm = '';
                            if($product['commission_type'] == 'percentage'){ $comm = $product['commission_sale'].'%'; }
                            else if($product['commission_type'] == 'fixed'){ $comm = c_format($product['commission_sale']); }
                            
                            echo "<b>You Can Earn :</b><small> {$comm} per Sale </small><br>";
                            ?>
                    </div>
                </div>
                <div class="wallet-toggle ">
                    <div class="<?= $product['_tool_type'] == 'program' && $product['click_status'] ? '' : 'd-none' ?>">
                        <?php 
                            echo "<b>You Can Earn :</b><small> ";
                            echo c_format($product["commission_click_commission"]). " per ". $product['commission_number_of_click'] ." Clicks </small><br>";
                            ?>
                    </div>
                </div>
                <div class="wallet-toggle ">
                    <div class="<?= $product['_tool_type'] == 'general_click' ? '' : 'd-none' ?>">
                        <?php 
                            echo "<b>You Can Earn :</b><small> ";
                            echo c_format($product["general_amount"]). " per ". $product['general_click'] ." General Clicks </small><br>";
                            ?>
                    </div>
                </div>
                <div class="wallet-toggle ">
                    <div class="<?= $product['_tool_type'] == 'action' ? '' : 'd-none' ?>">
                        <?php 
                            echo "<b>You Can Earn :</b><small> ";
                            echo c_format($product["action_amount"]). " per ". $product['action_click'] ." Actions </small><br>"; 
                            ?>
                    </div>
                </div>
                <?php 
                    if($product['recursion']){
                        if($product['recursion'] != 'custom_time'){
                            echo '<b>'. __('user.recurring') .' </b> : ' . __('user.'.$product['recursion']);
                        } else {
                            echo '<b>'. __('user.recurring') .' </b> : '. timetosting($product['recursion_custom_time']);
                        }
                    }
                ?>  
            </td>
            <td>
                <div class="form-group m-0">
                    <div class="copy-input input-group">
                        <input readonly="readonly" value="<?= $product['redirectLocation'][0] ?>" class="form-control">
                        <button type="button" copyToClipboard="<?= $product['redirectLocation'][0] ?>" class="input-group-addon" >
                        </button>
                    </div>
                </div>
            </td>
            <td class="-d-sm-table-cell -d-none">
                <div class="code-share-<?= $index?>"></div>
                <script type="text/javascript">
                    //$(document).on('ready',function(){
                        $(".code-share-<?= $index ?>").jsSocials({
                            url: "<?= $product['redirectLocation'][0] ?>",
                            showCount: false,
                            showLabel: false,
                            shareIn: "popup",
                            shares: ["email", "twitter", "facebook", "googleplus", "linkedin", "pinterest", "stumbleupon", "whatsapp"]
                        });
                    //})
                </script>
            </td>
        </tr>
        <tr class="detail-tr">
            <td colspan="100%">
                <div>
                    <ul>
                        
                        <?php 
                            if($product['_tool_type'] == 'program' && $product['sale_status']){ 
                                echo "<li><b>Sale Count :</b> <span>". (int)$product['total_sale_count'] ."</span></li>";
                                echo "<li><b>Sale Amount :</b> <span>". $product['total_sale_amount'] ."</span></li>";
                            }

                            if($product['_tool_type'] == 'program' && $product['click_status']){
                                echo "<li><b>Click Count :</b> <span>". (int)$product['total_click_count'] ."</span></li>";
                                echo "<li><b>Click Amount :</b> <span>". $product['total_click_amount'] ."</span></li>";
                            }

                            if($product['_tool_type'] == 'general_click'){
                                echo "<li><b>General Count</b> : <span>". (int)$product['total_general_click_count'] ."</span></li>";
                                echo "<li><b>General Amount</b> : <span>". $product['total_general_click_amount'] ."</span></li>";
                            }

                            if($product['_tool_type'] == 'action'){
                                echo "<li><b>Action Count :</b> <span>". (int)$product['total_action_click_count'] ."</span></li>";
                                echo "<li><b>Action Amount :</b> <span>". $tool['total_action_click_amount'] ."</span></li>";
                            }
                        ?>
                    </ul>
                </div>
            </td>
        </tr>
    <?php } ?>
<?php } ?>