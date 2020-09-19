<?php
    $db =& get_instance();
    $userdetails=$db->userdetails();
    $unique_url= base_url().'register/'.base64_encode( $userdetails['id']);
    $ShareUrl = urlencode($unique_url);
    $store_setting =$db->Product_model->getSettings('store');
    $products = $db->Product_model;
    ?>
<script type="text/javascript" src="<?= base_url('assets/vertical/assets/plugins/chartist/js/chartist.min.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('/assets/vertical/assets/plugins/jvectormap/jquery-jvectormap-2.0.2.css') ?>">
<link href="<?php echo base_url('assets/vertical/assets/plugins/chartist/css/chartist.min.css'); ?>?v=<?= av() ?>" rel="stylesheet" type="text/css">
<script src="<?= base_url('assets/vertical/assets/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js') ?>"></script>
<script src="<?= base_url('assets/vertical/assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') ?>"></script>
<script src="<?= base_url('assets/vertical/assets/plugins/jvectormap/gdp-data.js') ?>"></script>
<script src="<?= base_url('assets/vertical/assets/plugins/jvectormap/jquery-jvectormap-us-aea-en.js') ?>"></script>
<script src="<?= base_url('assets/vertical/assets/plugins/jvectormap/jquery-jvectormap-uk-mill-en.js') ?>"></script>
<script src="<?= base_url('assets/vertical/assets/plugins/jvectormap/jquery-jvectormap-us-il-chicago-mill-en.js') ?>"></script>
<script src="<?php echo base_url(); ?>assets/vertical/assets/plugins/chartist/js/chartist-plugin-tooltip.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vertical/assets/plugins/jquery-knob/excanvas.js"></script>
<script src="<?php echo base_url(); ?>assets/vertical/assets/plugins/jquery-knob/jquery.knob.js"></script>

<!--THIS IS THE TOP ROW OF 4 COLUMNS START-->
<div class="row">
    <div class="col-xl-12">
        <div class="row">
            <div class="col-xl-3">
                <span class="mini-stat-icon bg-success"><i class="fa fa-bell"></i></span>
                <div class="card-header text-center"><h6 class="mt-0 header-title"><?php echo __( 'user.total_balance') ?></h6></div>
                <div class="mini-stat clearfix bg-white">
                    <div class="mini-stat-info text-center">
                        <h4 class="counter mt-0 text-primary ajax-total_balance"><?php echo c_format($totals['wallet_accept_amount'] + $totals['wallet_unpaid_amount']) ?></h4>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3">
                <span class="mini-stat-icon bg-success"><i class="fa fa-bell"></i></span>
                <div class="card-header text-center bg-white"><h6 class="mt-0 header-title"><?php echo __( 'user.total_sales' ) ?></h6></div>
                <div class="mini-stat clearfix bg-white">
                    <div class="mini-stat-info text-center">
                        
                        <h4 class="counter mt-0 text-primary ajax-total_balance">
                            <?php echo ((int)$totals['total_sale_count'] + (int)$totals['vendor_order_count']) ?> / 
                            <?php echo c_format((float)$totals['total_sale_amount'] + (float)$totals['total_vendor_sale']) ?> / 

                            <?php echo c_format($totals['all_sale_comm']) ?>
                        </h4>
                    </div>
                    <button class="btn-sm btn-window" data-log='sale'><i class="fa fa-eye"></i></button>
                </div>
            </div>
            
             <div class="col-xl-3">
                <span class="mini-stat-icon bg-success"><i class="fa fa-bell"></i></span>
                <div class="card-header text-center bg-white"><h6 class="mt-0 header-title"><?php echo __( 'user.clicks_statistic' ) ?></h6></div>
                <div class="mini-stat clearfix bg-white">
                    <div class="mini-stat-info text-center">
                        <h4 class="counter mt-0 text-primary ajax-all_clicks_comm"> <?php echo $totals['all_clicks'] ?></h4>
                    </div>
                    <button class="btn-sm btn-window" data-log='click'><i class="fa fa-eye"></i></button>
                </div>
            </div>
            <div class="col-xl-3">
                <span class="mini-stat-icon bg-success"><i class="fa fa-bell"></i></span>
                <div class="card-header text-center bg-white"><h6 class="mt-0 header-title"><b><?= __('user.total_actions_home') ?></b></h6></div>
                <div class="mini-stat clearfix bg-white">
                    <div class="mini-stat-info text-center">
                        <h4 class="counter mt-0 text-primary ajax-action_count_action_amount"><?php echo (int)$totals['integration']['action_count'] ?></h4>
                    </div>
                    <button class="btn-sm btn-window" data-log='action'><i class="fa fa-eye"></i></button>
                </div>
            </div>
            
            
        </div>
    </div>
</div>
<!--THIS IS THE TOP ROW OF 4 COLUMNS END-->


<!--THIS IS GRAPHS START-->
<div class="row">
    <div class="col-xl-7">
        <div class="card h-100">
            <li class="list-group-item list-group-item-white font-weight-bold" style="text-align:center;"><?php echo __('user.user_overview') ?>
            <div class="pull-left">
            </div>
             <div class="pull-right">
                        <select class="renderChart">
                            <option value="day" ><?= __('user.day') ?></option>
                            <option value="week"><?= __('user.week') ?></option>
                            <option value="month" selected=""><?= __('user.month') ?></option>
                            <option value="year"><?= __('user.year') ?></option>
                        </select>
            </div>

                <div class="pull-right">
                <div id="stacked-bar-chart">
                        <select class="yearSelection" style="transition: 320ms;">
                            <?php for($i=2016; $i<= date("Y"); $i++){ ?> 
                                <option value="<?= $i ?>" <?php echo $i==date("Y") ? "selected='selected'" : '' ?>><?= $i ?></option>
                            <?php  } ?>
                        </select>
                    </div>
                   
                </div>
            </li>
    
                <div class="row">
                    <div class="col-xl-12">
                         <ul class="list-inline widget-chart m-t-20 m-b-15 text-center">
                    <li class="list-inline-item">
                        <i class="mdi mdi-arrow-up-bold-circle-outline text-success"></i>
                        <h5 class="mb-0"><?php echo c_format($totals['weekly_balance']) ?></h5>
                        <p class="text-muted font-14">Weekly Earnings</p>
                    </li>
                    <li class="list-inline-item">
                        <i class="mdi mdi-arrow-down-bold-circle-outline text-danger"></i>
                        <h5 class="mb-0"><?php echo c_format($totals['monthly_balance']) ?></h5>
                        <p class="text-muted font-14">Monthly Earnings</p>
                    </li>
                    <li class="list-inline-item">
                        <i class="mdi mdi-arrow-up-bold-circle-outline text-success"></i>
                        <h5 class="mb-0"><?php echo c_format($totals['yearly_balance']) ?></h5>
                        <p class="text-muted font-14">Yearly Earnings</p>
                    </li>
                </ul>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="clearfix"></div>
                <div id="stacked-bar-chart2" class="ct-chart ct-golden-section"></div>
                <div id="stacked-bar-chart2-empty" class="ct-chart ct-golden-section">
                    <div class="text-center">
                        <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:10px;">
                        <h3 class="m-t-40 text-center"><?= __('user.not_activity_yet') ?></h3>
                    </div>
                </div>
        </div>
    </div>
    
    <div class="col-xl-5">
        <div class="card h-100">
            <li class="list-group-item list-group-item-white font-weight-bold" style="text-align:center;"><?php echo __('user.user_statistics') ?></li>
                <ul class="list-group">

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?= base_url('usercontrol/mywallet/') ?>"> <?php echo __('user.hold_actions') ?></a></span>
                        <span class="counter mt-0 badge badge-primary cursor-pointer badge-pill float-center btn-window2" data-log="hold_actions" style='width: 50px;right:0;'>
                            <?= (int)$totals['full_hold_action_count'] ?></span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?= base_url('usercontrol/store_orders/') ?>">  <?= __('user.vendor_orders') ?> </a></span>
                        <span class="counter mt-0 badge badge-primary cursor-pointer badge-pill float-center" style='width: 50px;right:0;'><?= (int)$totals['vendor_order_count'] ?></span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?= base_url('usercontrol/mywallet/') ?>">  <?= __('user.vendor_unpaid_amount') ?> </a></span>
                        <span class="counter mt-0 badge badge-primary cursor-pointer badge-pill float-center" style='width: 50px;right:0;'><?= c_format($totals['vendor_wallet_unpaid_amount']) ?></span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?= base_url('usercontrol/mywallet/') ?>">  <?= __('user.vendor_on_hold amount') ?> </a></span>
                        <span class="counter mt-0 badge badge-primary cursor-pointer badge-pill float-center" style='width: 50px;right:0;'><?= c_format($totals['vendor_wallet_on_hold_amount']) ?></span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?= base_url('usercontrol/mywallet/') ?>">  <?= __('user.vendor_paid_amount') ?> </a></span>
                        <span class="counter mt-0 badge badge-primary cursor-pointer badge-pill float-center" style='width: 50px;right:0;'><?= c_format($totals['vendor_wallet_accept_amount']) ?></span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?= base_url('usercontrol/mywallet/') ?>">  <?= __('user.vendor_in_request_amount') ?> </a></span>
                        <span class="counter mt-0 badge badge-primary cursor-pointer badge-pill float-center" style='width: 50px;right:0;'><?= c_format($totals['vendor_wallet_request_sent_amount']) ?></span>
                    </li>
                        

                
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?= base_url('usercontrol/store_orders/') ?>"> <?php echo __('user.digital_store_hold_order') ?></a></span>
                        <span class="counter mt-0 badge badge-primary cursor-pointer badge-pill float-center btn-window2" data-log="hold_orders" style='width: 50px;right:0;'>
                            <?= (int)$totals['full_hold_orders'] ?></span>
                        </li>
                    
                    
                  
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?= base_url('usercontrol/store_orders/') ?>"> <?php echo __('user.digital_store_process_approved_order') ?></a></span>
                        <span class="counter mt-0 badge badge-primary cursor-pointer badge-pill float-center btn-window2" data-log="orders" style='width: 50px;right:0;'>
                            <?= (int)$totals['full_digital_orders'] ?></span>
                        </li>
                      
                      
                 
                     <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?= base_url('usercontrol/store_orders/') ?>"> <?php echo __('user.external_inte_order') ?></a></span>
                        <span class="counter mt-0 badge badge-primary cursor-pointer badge-pill float-center btn-window2" data-log="ex_orders" style='width: 50px;right:0;'>
                            <?= (int)$totals['external_inte_order'] ?></span>
                        </li>
                        
                      
                      <!----------Start---------->
                    <?php 
                        $_t_paid_comm = (float)($totals['wallet_accept_amount']);
                        $_t_unpaid_comm = (float)($totals['unpaid_commition']);
                        $_t_request_comm = (float)($totals['wallet_request_sent_amount']);
                        $_t_total_commission = (float)($totals['all_clicks_comm'] + $totals['all_sale_comm'] + $totals['integration']['action_amount']);
                    ?>
                        
                    
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= __( 'user.total_paid_commission' ) ?>
                        <span class="badge badge-primary badge-pill ajax-_t_paid_comm"><?php echo c_format($_t_paid_comm) ?></span>
                    </li>
                      
                      
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= __('user.total_unpaid_commition') ?>
                        <span class="badge badge-primary badge-pill ajax-_t_unpaid_comm"><?php echo c_format($_t_unpaid_comm) ?></span>
                    </li>
                      
                      
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= __('user.total_in_request_commition') ?>
                        <span class="badge badge-primary badge-pill ajax-_t_request_comm"><?php echo c_format($_t_request_comm) ?></span>
                    </li>
                      
                      
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                       <?= __('user.total_affiliates_comm') ?>
                        <span class="badge badge-primary badge-pill ajax-_t_total_commission"><?php echo c_format($_t_total_commission) ?></span>
                    </li>
                      <!----------End---------->
                      
                    </ul>
                    
                    

    <!------WINDOWS LOGS START--------->
        <div class="card h-100">
            <li class="list-group-item list-group-item-white font-weight-bold" style="text-align:center;"><?php echo __('user.live_logs') ?>
                <div class="pull-right">
                    <button class="btn btn-outline-warning btn-sm btn-count-notification" data-toggle="tooltip" title='All Notifications' data-key='live_log' data-type='admin'>
                        <span class="count-notifications"><?= count($live_window) ?></span>
                        <i class="fa fa-bell"></i>
                    </button>
                </div>
            </li>
           
            <div class="card-body p-0" id="ajax-live_window">
                <div class="text-center m-4 empty-data" style="display: <?= !empty($live_window) ? 'none' : 'block'; ?>">
                    <img class="img-responsive" src="<?php echo base_url("assets/vertical/assets/images/no-data-2.png"); ?>" style="margin-top:10px;">
                    <h3 class="m-t-40 text-center"><?= __('admin.not_activity_yet') ?></h3>
                </div>

                <ul class="ajax-live_window h-100" style="display: <?= empty($live_window) ? 'none' : 'block'; ?>;">
                    <?php foreach ($live_window as $key => $value) { ?>
                        <li><?= $value['title'] ?></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
<!------WINDOWS LOGS END--------->
    </div>
    </div> 
</div>
<!--THIS IS GRAPHS END-->

</br>

<!------AFFILIATE LINKS START--------->
<div class="row">
    <div class="col-sm-12">
        <div class="card h-100">
            <li class="list-group-item list-group-item-white font-weight-bold" style="text-align:left;"><?= __('user.affiliates_links...') ?></li>
                <div class="card-body p-0 h-100"; overflow: auto;">
                    
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
                                <?php 
                                    $pro_setting    = $this->Product_model->getSettings('productsetting');
                                    $form_setting   = $this->Product_model->getSettings('formsetting');
                                    $vendor_setting = $this->Product_model->getSettings('vendor');
                                ?>
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
                                                <td><img width="50px" height="50px" src="<?php echo resize('assets/images/share-icon.png' ,100,100) ?>" ></td>
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
                                                                        echo '<b>Recurring </b> : ' . $product['form_recursion'];
                                                                    } else {
                                                                        echo '<b>Recurring </b> : '. timetosting($product['recursion_custom_time']);
                                                                    }
                                                                } else{
                                                                    if($form_setting['form_recursion'] == 'custom_time' ){
                                                                        echo '<b>Recurring </b> : '. timetosting($form_setting['recursion_custom_time']);
                                                                    } else {
                                                                        echo '<b>Recurring </b> : '. $form_setting['form_recursion'];
                                                                    }
                                                                }
                                                            }
                                                        ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group m-0">
                                                        <div class="input-group">
                                                            <input readonly="readonly" value="<?= $product['public_page'] ?>" class="form-control">
                                                            <button type="button" copyToClipboard="<?= $product['public_page'] ?>" class="input-group-addon" style="    border-left: 0;">
                                                            <img src="<?= base_url() ?>/assets/images/clippy.svg" class="tooltiptext" width="20px" height="20px" >
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="code-share-<?= $index ?>"></div>
                                                    <script type="text/javascript">
                                                        $(document).on('ready',function(){
                                                            $(".code-share-<?= $index ?>").jsSocials({
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


                                                            
                                                            /*$commnent_line = '';
                                                            if($seller->admin_click_commission_type == '' || $seller->admin_click_commission_type == 'default'){
                                                                $commnent_line =  c_format($vendor_setting['admin_click_amount']) ." Per ". (int)$vendor_setting['admin_click_count'] ." Clicks";
                                                            } else{ 
                                                                $commnent_line =  c_format($seller->admin_click_amount) ." Per ". (int)$seller->admin_click_count ." Clicks";
                                                            } 

                                                            echo '<br><b>Admin Click</b> : ' .$commnent_line;

                                                            $commnent_line = '';
                                                            if($seller->admin_sale_commission_type == '' || $seller->admin_sale_commission_type == 'default'){ 
                                                                if($vendor_setting['admin_sale_commission_type'] == ''){
                                                                    $commnent_line .= ' Warning : Default Commission Not Set';
                                                                }
                                                                else if($vendor_setting['admin_sale_commission_type'] == 'percentage'){
                                                                    $commnent_line .= ''. (float)$vendor_setting['admin_commission_value'] .'%';
                                                                }
                                                                else if($vendor_setting['admin_sale_commission_type'] == 'fixed'){
                                                                    $commnent_line .= ''. c_format($vendor_setting['admin_commission_value']);
                                                                }
                                                            } else if($seller->admin_sale_commission_type == 'percentage'){
                                                                $commnent_line .= ''. (float)$seller->admin_commission_value .'%';
                                                            } else if($seller->admin_sale_commission_type == 'fixed'){
                                                                $commnent_line .= ''. c_format($seller->admin_commission_value);
                                                            } else {
                                                                $commnent_line .= ' Warning : Commission Not Set';
                                                            } 

                                                            echo '<br><b>Admin Sale</b> : ' .$commnent_line;*/
                                                        } else {
                                                    ?>
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
                                                    <?php } ?>
                                                    <div>
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
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group m-0">
                                                        <div class="input-group">
                                                            <input readonly="readonly" value="<?= $productLink ?>" class="form-control">
                                                            <button type="button" copyToClipboard="<?= $productLink ?>" class="input-group-addon" style="    border-left: 0;">
                                                            <img src="<?= base_url() ?>/assets/images/clippy.svg" class="tooltiptext" width="20px" height="20px" >
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="code-share-<?= $index ?>"></div>
                                                    <script type="text/javascript">
                                                        $(document).on('ready',function(){
                                                            $(".code-share-<?= $index ?>").jsSocials({
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
                                                <td><img width="50px" height="50px" src="<?php echo resize('assets/images/product/upload/thumb/'. $product['featured_image'],100,100,1) ?>" ></td>
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
                                                                echo '<b>Recurring </b> : ' . $product['recursion'];
                                                            } else {
                                                                echo '<b>Recurring </b> : '. timetosting($product['recursion_custom_time']);
                                                            }
                                                        }
                                                    ?>  
                                                </td>
                                                <td>
                                                    <div class="form-group m-0">
                                                        <div class="input-group">
                                                            <input readonly="readonly" value="<?= $product['redirectLocation'][0] ?>" class="form-control">
                                                            <button type="button" copyToClipboard="<?= $product['redirectLocation'][0] ?>" class="input-group-addon" style="    border-left: 0;">
                                                            <img src="<?= base_url() ?>/assets/images/clippy.svg" class="tooltiptext" width="20px" height="20px" >
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="-d-sm-table-cell -d-none">
                                                    <div class="code-share-<?= $index ?>"></div>
                                                    <script type="text/javascript">
                                                        $(document).on('ready',function(){
                                                            $(".code-share-<?= $index ?>").jsSocials({
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
                                <?php if($index >= $pagination){ ?>
                                    <tfoot>
                                        <tr>
                                            <td colspan="10%">
                                                <button type="button" class="btn btn-primary show-more">Show More</button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                <?php } ?>
                            </table>
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
                        

                        <script type="text/javascript">
                            /*$(".get-code").on('click',function(){
                                $this = $(this);
                                $.ajax({
                                    url:'<?= base_url("integration/tool_get_code/usercontrol") ?>',
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
                            })*/

                            function generateCode(affiliate_id,t){
                                $this = $(t);
                                $.ajax({
                                    url:'<?php echo base_url();?>usercontrol/generateproductcode/'+affiliate_id,
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
                                    url:'<?php echo base_url();?>usercontrol/generateformcode/'+form_id,
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
                    
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

<!------AFFILIATE LINKS END--------->
</br>


<!------INTEGRATION DATA + REFFER DATA START--------->
<div class="row">
    <div class="col-xl-<?= $refer_status ? '8' : '12' ?>">
    </div>
    <?php if($refer_status) { ?>
        <div class="col-xl-6">
            <div class="card m-b-20">
                <div class="card-header m-b-20">
                    <h4 class="header-title pull-left m-20"><?= __('user.Total_sales_clicks_actions_levels') ?></h4>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <?= __('user.referals_product_click_commissions') ?> 
                            <span class="badge badge-primary badge-pill font-14 pull-right">
                            <?= (int)$refer_total['total_product_click']['clicks'] ?> /
                            <?= c_format($refer_total['total_product_click']['amounts']) ?>
                            </span>
                        </li>
                        <li class="list-group-item">
                            <?= __('user.referals_sale_commissions') ?>
                            <span class="badge badge-primary badge-pill font-14 pull-right">
                            <?= (int)$refer_total['total_product_sale']['counts'] ?> /
                            <?= c_format($refer_total['total_product_sale']['amounts']) ?>
                            </span>
                        </li>
                        <li class="list-group-item">
                            <?= __('user.referals_general_click_commissions') ?>
                            <span class="badge badge-primary badge-pill font-14 pull-right">
                            <?= (int)$refer_total['total_ganeral_click']['total_clicks'] ?> /
                            <?= c_format($refer_total['total_ganeral_click']['total_amount']) ?>
                            </span>
                        </li>
                        <li class="list-group-item">
                            <?= __('user.referals_action_commissions') ?>
                            <span class="badge badge-primary badge-pill font-14 pull-right">
                            <?= (int)$refer_total['total_action']['click_count'] ?> /
                            <?= c_format($refer_total['total_action']['total_amount']) ?>
                            </span>
                        </li>
                    </ul>
                    </br>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="col-sm-6">
        <div class="card">
            <div class="card-header m-b-20">
                <h4 class="header-title pull-left m-20"><?= __('user.popular_affiliates') ?></h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <?php foreach ($populer_users as $key => $users) { ?>
                                <tr class="<?= $key == 0 ? 'bg-primary text-white' : '' ?>">
                                    <?php
                                        $flag = '';
                                        if ($users['sortname'] != '') {
                                            $flag = base_url('assets/vertical/assets/images/flags/' . strtolower($users['sortname']) . '.png');
                                        }
                                    ?>
                                    <td><img class="populer-user-img" src="<?= $products->getAvatar($users['avatar']) ?>"></td>
                                    <td><?php echo $users['firstname']; ?> <?php echo $users['lastname']; ?></td>
                                    <td><?= strtoupper($users['sortname']) ?> <img class="country-flag" src="<?php echo $flag; ?>"></td>
                                    <td><?php echo c_format($users['amount']); ?> </br>Sales</td>
                                    <td><?php echo c_format($users['all_commition']); ?></br>Commissions</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>   
            </div>
        </div>
    </div>
</div>
<!------INTEGRATION DATA + REFFER DATA END--------->





<!------REFFER LINKS START--------->                
<?php if(($store['registration_status'] && $refer_status) || $store_setting['status']){ ?>
<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                <h4 class="mt-0 header-title"><?= __('user.your_unique_reseller_link') ?> ( Your Affiliate ID : <?= $userdetails['id'] ?> ) </h4>
                <div class="card-body">
                    <h4 class="mt-0 header-title"><?= __('user.your_unique_reseller_link') ?></h4>
                    <?php if($store['registration_status'] && $refer_status) { ?>
                    <div class="ltop">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" readonly="readonly" value="<?= $unique_url ?>" class="form-control" id="unique_re_link">
                                        <button copyToClipboard="<?= $unique_url ?>" class="input-group-addon">
                                        <img src="<?php echo base_url();?>assets/images/clippy.svg" class="tooltiptext" width="25px" height="25px" >
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="webpanel-link"></div>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(document).on('ready',function(){
                                $(".webpanel-link").jsSocials({
                                    url: "<?= $unique_url ?>",
                                    showCount: false,
                                    showLabel: false,
                                    shareIn: "popup",
                                    shares: ["email", "twitter", "facebook", "googleplus", "linkedin", "pinterest", "stumbleupon", "whatsapp"]
                                });
                            })
                        </script>
                    </div>
                    <hr>
                    <?php } ?>
                    <?php if($store_setting['status']) { ?>
                    <b><?= __('user.store_url') ?></b>
                    <div class="ltop">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <?php  $store_url = base_url('store/'. base64_encode($userdetails['id'])); ?>
                                    <div class="input-group">
                                        <input type="text" readonly="readonly" value="<?= $store_url ?>" class="form-control" id="unique_re_link">
                                        <button copyToClipboard="<?= $store_url ?>" class="input-group-addon">
                                        <img src="<?php echo base_url();?>assets/images/clippy.svg" class="tooltiptext" width="25px" height="25px" >
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="store-link"></div>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(document).ready(function(){
                                $(".store-link").jsSocials({
                                    url: "<?= $store_url ?>",
                                    showCount: false,
                                    showLabel: false,
                                    shareIn: "popup",
                                    shares: ["email", "twitter", "facebook", "googleplus", "linkedin", "pinterest", "stumbleupon", "whatsapp"]
                                });
                            })
                        </script>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<!------REFFER LINKS END--------->


<div class="modal fade" id="integration-code">
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>

<script type="text/javascript">
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

    $(".wallet-toggle .tog").on('click',function(){
        $(this).parents(".wallet-toggle").find("> div").toggleClass("d-none");
    })
    
    $(".tool-remove-link").on('click',function(){
        if(!confirm("Are you sure?")) return false;
        return true;
    })
    
    $(".get-code").on('click',function(){
        $this = $(this);
        $.ajax({
            url:'<?= base_url("integration/tool_get_code/usercontrol") ?>',
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
</script>
<script type="text/javascript" async >
    function shareinsocialmedia(url){
        window.open(url,'sharein','toolbar=0,status=0,width=648,height=395');
        return true;
    }
</script>
<script>
    function myFunction() {
        var copyText = document.getElementById("unique_re_link");
        copyText.select();
        document.execCommand("Copy");
        var tooltip = document.getElementById("myTooltip");
        tooltip.innerHTML = "Copied: " + copyText.value;
    }
    function outFunc() {
        var tooltip = document.getElementById("myTooltip");
        tooltip.innerHTML = "Copy to clipboard";
    }
</script>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    $(document).on('ready',function(){
        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(charloadfirsttime);

        function charloadfirsttime() {
            renderStackedBarChart('month');
            //loadpaichart();
            //getTotalIntegration();
        }
        
        function toArray(myObj) {
            return $.map(myObj, function(value, index) {
                return [value];
            });
        }

        function renderStackedBarChart(group) {
        var selectedyear = $('.yearSelection').val();
        $.ajax({
          type: 'POST',
          dataType: 'json',
          data: {renderChart: group,selectedyear:selectedyear},
          success: function (json) {
            $("#stacked-bar-chart2").show();
            $("#stacked-bar-chart2-empty").hide();
            if (toArray(json['y'])[0] == null) {
              $("#stacked-bar-chart2").hide();
              $("#stacked-bar-chart2-empty").show();
            }
            
            var series={
                'sale' : toArray(json['series_new']['sale']),
                'order' : toArray(json['series_new']['order']),
                'commissions' : toArray(json['series_new']['commissions']),
                'action' : toArray(json['series_new']['action']),
                'actioncommissions' : toArray(json['series_new']['actioncommissions']),
              }


              var _data = [];
              _data.push(['key','Sale','Order','Commissions','Action','Action Commissions'])
             
              $.each(json['series_new']['keys'],function(i,j){
                  i =  parseInt(i) + 1;
                  _data.push([
                    j.toString(),
                    series['sale'][i],
                    series['order'][i],
                    series['commissions'][i],
                    series['action'][i],
                    series['actioncommissions'][i],
                  ])
                
              })

              var data = google.visualization.arrayToDataTable(_data);
              var options = {
                width: '100%',
                height: 300,
                legend: { position: 'bottom', maxLines: 3 },
                bar: { groupWidth: '40' },
                isStacked: true,
              };

              var chart = new google.visualization.ColumnChart(document.getElementById('stacked-bar-chart2'));
              chart.draw(data, options);


          },
      })
    }

    if ($(".renderChart").val() !='month') {
        $('.yearSelection').css('opacity',0);
    }else{
        $('.yearSelection').css('opacity',1);
    }


    $(".renderChart").change(function () {
        // renderChart($(this).val());
        console.log($(this).val());
        if ($(this).val()!='month') {
            $('.yearSelection').css('opacity',0);
        }else{
            $('.yearSelection').css('opacity',1);
        }
        renderStackedBarChart($(this).val());
      })
      // renderChart('day');
    $('.yearSelection').change(function () {
        $(".renderChart").trigger('change');
    })
      //renderStackedBarChart('month');
    })
</script>