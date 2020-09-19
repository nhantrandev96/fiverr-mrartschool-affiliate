<?php
    $db =& get_instance();
    $userdetails=$db->userdetails();
?>


<div class="row">
    <div class="col-sm-12">
        <div class="card m-b-20">
            <div class="card-body">
                <h4 class="mt-0 header-title m-b-30"> <?= __('user.affiliates_links...') ?></h4>
                <div class="clearfix"></div>
                <div class="card-body p-0" style="height: 100%;overflow: auto;">
                    
                   <?php if ($data_list==null) {?>
                        <div class="text-center">
                            <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:25px;">
                            <h3 class="m-t-40 text-center"><?= __('user.no_banners_to_share_yet') ?></h3>
                        </div>
                    <?php } else { ?>

                        <div class="table-responsive b-0" >
                            <table id="product-list" class="table table-no-wrap">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="1"></th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Commission</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <?php $pagination = 100; ?>
                                <tbody>
                                    <?php foreach($data_list as $index => $product){ ?>
                                        <?php
                                            $display_class = $index >= $pagination ? 'd-none' : '';
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
                                                            $commissionType = $form_default_commission['product_commission_type'];
                                                            if($form_default_commission['product_commission_type'] == 'percentage'){
                                                                echo $form_default_commission['product_ppc'] .'% of Per '. $form_default_commission['product_noofpercommission'] .' Click';
                                                            }
                                                            else if($form_default_commission['product_commission_type'] == 'Fixed'){
                                                                echo c_format($form_default_commission['product_ppc']) .' of Per '. $form_default_commission['product_noofpercommission'] .' Click';
                                                            }
                                                        }
                                                        else if($product['click_commision_type'] == 'custom') {
                                                            echo c_format($product['click_commision_ppc']) .' of Per '. $product['click_commision_per'] .' Click';
                                                        }
                                                        ?>
                                                </td>
                                                <td>
                                                    <div class="form-group m-0">
                                                        <div class="input-group copy-input">
                                                            <input readonly="readonly" value="<?= $product['public_page'] ?>" class="form-control">
                                                            <button type="button" copyToClipboard="<?= $product['public_page'] ?>" class="input-group-addon">
                                                            
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="code-share-<?= $key ?>"></div>
                                                    <script type="text/javascript">
                                                        $(document).on('ready',function(){
                                                            $(".code-share-<?= $key ?>").jsSocials({
                                                                url: "<?= $product['public_page'] ?>",
                                                                showCount: false,
                                                                showLabel: false,
                                                                shareIn: "popup",
                                                                shares: ["email", "twitter", "facebook", "googleplus", "linkedin", "pinterest", "stumbleupon", "whatsapp"]
                                                            });
                                                        })
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
                                                            echo c_format($product['product_click_commision_per']) ." Per {$product['product_click_commision_ppc']} Click";
                                                        }
                                                        ?>
                                                </td>
                                                <td>
                                                    <div class="form-group m-0">
                                                        <div class="input-group copy-input">
                                                            <input readonly="readonly" value="<?= $productLink ?>" class="form-control">
                                                            <button type="button" copyToClipboard="<?= $productLink ?>" class="input-group-addon">
                                                            
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="code-share-<?= $key ?>"></div>
                                                    <script type="text/javascript">
                                                        $(document).on('ready',function(){
                                                            $(".code-share-<?= $key ?>").jsSocials({
                                                                url: "<?= $productLink ?>",
                                                                showCount: false,
                                                                showLabel: false,
                                                                shareIn: "popup",
                                                                shares: ["email", "twitter", "facebook", "googleplus", "linkedin", "pinterest", "stumbleupon", "whatsapp"]
                                                            });
                                                        })
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
                                                <td><img width="50px" height="50px" src="<?php echo resize('assets/images/share-icon.png' ,100,100) ?>" ></td>
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
                                                </td>
                                                <td>
                                                    <div class="form-group m-0">
                                                        <div class="input-group copy-input">
                                                            <input readonly="readonly" value="<?= $product['redirectLocation'][0] ?>" class="form-control">
                                                            <button type="button" copyToClipboard="<?= $product['redirectLocation'][0] ?>" class="input-group-addon">
                                                            
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="-d-sm-table-cell -d-none">
                                                    <div class="code-share-<?= $key ?>"></div>
                                                    <script type="text/javascript">
                                                        $(document).on('ready',function(){
                                                            $(".code-share-<?= $key ?>").jsSocials({
                                                                url: "<?= $product['redirectLocation'][0] ?>",
                                                                showCount: false,
                                                                showLabel: false,
                                                                shareIn: "popup",
                                                                shares: ["email", "twitter", "facebook", "googleplus", "linkedin", "pinterest", "stumbleupon", "whatsapp"]
                                                            });
                                                        })
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
                                </tbody>
                                <?php if($index > $pagination){ ?>
                                    <tfoot>
                                        <tr>
                                            <td colspan="100%">
                                                <button type="button" class="btn btn-primary show-more">Show More</button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                <?php } ?>
                            </table>
                        </div>

                        <div class="text-right">
                            <ul class="pagination">
                                <?= $pagination_link ?>
                            </ul>
                        </div>

                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="model-codemodal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="model-codeformmodal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="integration-code"><div class="modal-dialog"><div class="modal-content"></div></div></div>

<script type="text/javascript">
    $(".get-code").on('click',function(){
        $this = $(this);
        $.ajax({
            url:'<?= base_url("integration/tool_get_code/admincontrol") ?>',
            type:'POST',
            dataType:'json',
            data:{id:$this.attr("data-id")},
            beforeSend:function(){ $this.btn("loading"); },
            complete:function(){ $this.btn("reset"); },
            success:function(json){
                if(json['html']){
                    $("#integration-code .modal-content").html(json['html']);
                    $("#integration-code").modal("show");
                }
            },
        })
    })

    $(".toggle-child-tr").on('click',function(){
        $tr = $(this).parents("tr");
        $ntr = $tr.next("tr.detail-tr");

        if($ntr.css("display") == 'table-row'){
            $ntr.hide();
            $(this).find("i").attr("class","fa fa-plus");
        }else{
            $(this).find("i").attr("class","fa fa-minus");
            $ntr.show();
        }
    })
    $(".show-more").on('click',function(){
        $(this).parents("tfoot").remove();
        $("#product-list tr.d-none").hide().removeClass('d-none').fadeIn();
    });

    function generateCode(affiliate_id,t){
        $this = $(t);
        $.ajax({
            url:'<?php echo base_url();?>admincontrol/generateproductcode/'+affiliate_id,
            type:'POST',
            dataType:'html',
            beforeSend:function(){
                $this.btn("loading");
            },
            complete:function(){
                $this.btn("reset");
            },
            success:function(json){
                $('#model-codemodal .modal-body').html(json)
                $("#model-codemodal").modal("show")
            },
        })
    }

    function generateCodeForm(form_id,t){ 
        $this = $(t);
        $.ajax({
            url:'<?php echo base_url();?>admincontrol/generateformcode/'+form_id,
            type:'POST',
            dataType:'html',
            beforeSend:function(){
                $this.btn("loading");
            },
            complete:function(){
                $this.btn("reset");
            },
            success:function(json){
                $('#model-codeformmodal .modal-body').html(json)
                $("#model-codeformmodal").modal("show")
            },
        })
    }
   
   
</script>