<?php
    $db =& get_instance();
    $userdetails=$db->userdetails();
    $unique_url= base_url().'register/'.base64_encode( $userdetails['id']);
    $ShareUrl = urlencode($unique_url);
    
    $store_setting =$db->Product_model->getSettings('store');
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


<!--THIS IS THE TOP ROW OF 6 COLUMNS START-->
<div class="row">
    <div class="col-xl-12">
        <div class="row">
            <div class="col-xl-2">
                <span class="mini-stat-icon bg-success"><i class="fa fa-bell"></i></span>
                <div class="card-header text-center"><h6 class="mt-0 header-title"><?php echo __( 'user.total_balance') ?></h6></div>
                <div class="mini-stat clearfix bg-white">
                    <div class="mini-stat-info text-center">
                        <h4 class="counter mt-0 text-primary ajax-total_balance"><?php echo c_format($totals['wallet_accept_amount']) ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-2">
                <span class="mini-stat-icon bg-success"><i class="fa fa-bell"></i></span>
                <div class="card-header text-center"><h6 class="mt-0 header-title"><?php echo __( 'user.total_sales' ) ?></h6></div>
                <div class="mini-stat clearfix bg-white">
                    <div class="mini-stat-info text-center">
                        <h4 class="counter mt-0 text-primary ajax-total_balance font-16">
                            <?php echo (int)$totals['total_sale_count'] ?> / 
                            <?php echo c_format($totals['total_sale_amount']) ?> / 
                            <?php echo c_format($totals['all_sale_comm']) ?>
                        </h4>
                    </div>
                    <button class="btn-sm btn-window" data-log='sale'><i class="fa fa-eye"></i></button>
                </div>
            </div>
            
            <div class="col-xl-2">
                <span class="mini-stat-icon bg-success"><i class="fa fa-bell"></i></span>
                <div class="card-header text-center"><h6 class="mt-0 header-title"><?php echo __( 'user.clicks_statistic' ) ?></h6></div>
                <div class="mini-stat clearfix bg-white">
                    <div class="mini-stat-info text-center">
                        <h4 class="counter mt-0 text-primary ajax-all_clicks_comm"> <?php echo $totals['all_clicks'] ?></h4>
                    </div>
                    <button class="btn-sm btn-window" data-log='click'><i class="fa fa-eye"></i></button>
                </div>
            </div>
            <div class="col-xl-2">
                <span class="mini-stat-icon bg-success"><i class="fa fa-bell"></i></span>
                <div class="card-header text-center"><h6 class="mt-0 header-title"><b><?= __('user.total_actions_home') ?></b></h6></div>
                <div class="mini-stat clearfix bg-white">
                    <div class="mini-stat-info text-center">
                        <h4 class="counter mt-0 text-primary ajax-action_count_action_amount"><?php echo (int)$totals['integration']['action_count'] ?></h4>
                    </div>
                    <button class="btn-sm btn-window" data-log='action'><i class="fa fa-eye"></i></button>
                </div>
            </div>
            <div class="col-xl-2">
                <span class="mini-stat-icon bg-success"><i class="fa fa-bell"></i></span>
                <div class="card-header text-center"><h6 class="mt-0 header-title"><?= __('user.total_commission') ?></h6></div>
                <div class="mini-stat clearfix bg-white">
                    <div class="mini-stat-info text-center">
                        <h4 class="counter mt-0 text-primary ajax-total_balance">
                            <?php echo c_format($totals['all_clicks_comm'] + $totals['all_sale_comm'] + $totals['integration']['action_amount'] + $totals['admin_transaction'] ) ?>
                        </h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-1">
                <div class="card-header text-center"><h4 class="mt-0 header-title"><?php echo __('user.h_actions') ?></h4></div>
                <div class="mini-stat clearfix bg-white">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="mini-stat-info text-center">
                                <?php $class = ($totals['integration']['hold_action_count']==null) ? "" :  "blink_me"; ?>
                               
                                <a class="counter mt-0 text-success" href="<?= base_url('admincontrol/mywallet') ?>" role="button" data-toggle="tooltip" data-original-title="<?php echo __('user.h_actions') ?>">
                                    <span class="badge badge-setting badge-danger float-center <?= $class ?> ajax-hold_action_count"> <?= (int)$totals['hold_action_count'] ?></span>
                                </a>
                                
                            </div>
                        </div>
                        <button class="btn-sm btn-window" data-log='hold_actions'><i class="fa fa-eye"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-xl-1">
                <div class="card-header text-center"><h4 class="mt-0 header-title"><?php echo __('user.h_orders') ?></h4></div>
                <div class="mini-stat clearfix bg-white">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="mini-stat-info text-center">
                                <?php $class = ($totals['integration']['hold_orders']==null) ? "" :  "blink_me"; ?>
                                <a class="counter mt-0 text-success" href="<?= base_url('admincontrol/mywallet') ?>" role="button" data-toggle="tooltip" data-original-title="<?php echo __('user.h_orders') ?>">
                                    <span class="badge badge-setting badge-danger float-center ajax-hold_orders  <?= $class ?>"><?= (int)$totals['integration']['hold_orders'] ?></span>
                                </a>
                            </div>
                        </div>
                        <button class="btn-sm btn-window" data-log='hold_orders'><i class="fa fa-eye"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--THIS IS THE TOP ROW OF 6 COLUMNS END-->


<!-- Start cubes top-->
<!--<div class="row">
    <div class="col-md-6 col-xl-3">
        <div class="mini-stat clearfix bg-white">
            <span class="mini-stat-icon bg-primary"><i class="mdi mdi-cart-outline"></i></span>
            <div class="mini-stat-info text-right">
                <h3 class="counter mt-0 text-primary"><?php echo c_format($totals['wallet_accept_amount']) ?></h3>
            </div>
            <div class="clearfix"></div>
            <p class=" mb-0 m-t-20 text-muted"><?= __('user.total_balance') ?> : <?php echo c_format($totals['wallet_accept_amount']) ?> <span class="float-right"><i class="fa fa-caret-up text-success m-r-5"></i>10.25%</span></p>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="mini-stat clearfix bg-white">
            <span class="mini-stat-icon bg-success"><i class="mdi mdi-currency-usd"></i></span>
            <div class="mini-stat-info text-right">
                <h5>
                    <?php echo (int)$totals['total_sale_count'] ?> / 
                    <?php echo c_format($totals['total_sale_amount']) ?> / 
                    <?php echo c_format($totals['all_sale_comm']) ?>
                </h5>
            </div>
            <div class="clearfix"></div>
            <p class="text-muted mb-0 m-t-20"
            <h4 class="counter mt-0 text-success"><?= __('user.total_sales') ?> / Amount / Commissions</h4>
            <span class="float-right"><i class="fa fa-caret-down text-danger m-r-5"></i>8.38%</span></p>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="mini-stat clearfix bg-white">
            <span class="mini-stat-icon bg-primary"><i class="mdi mdi-cube-outline"></i></span>
            <div class="mini-stat-info text-right">
                <h3 class="counter mt-0 text-primary"> <?php echo c_format($totals['all_clicks_comm'] + $totals['all_sale_comm'] + $totals['integration']['action_amount'] + $totals['admin_transaction'] ) ?></h3>
            </div>
            <div class="clearfix"></div>
            <p class="text-muted mb-0 m-t-20"><?= __('user.total_commission') ?>: <?php echo c_format($totals['all_clicks_comm'] + $totals['all_sale_comm'] + $totals['integration']['action_amount'] + $totals['admin_transaction'] ) ?><span class="float-right"><i class="fa fa-caret-up text-success m-r-5"></i>3.05%</span></p>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="mini-stat clearfix bg-white">
            <span class="mini-stat-icon bg-success"><i class="mdi mdi-currency-btc"></i></span>
            <div class="mini-stat-info text-right">
                <h3 class="counter mt-0 text-success"><?= (int)$totals['all_clicks'] ?></h3>
            </div>
            <div class="clearfix"></div>
            <p class="text-muted mb-0 m-t-20"><?= (int)$totals['all_clicks'] ?> <?= __('user.total_clicks') ?> <span class="float-right"><i class="fa fa-caret-up text-success m-r-5"></i>22.58%</span></p>
        </div>
    </div>
</div>-->
<!-- End cubes top-->






<!--START GRAPH CHART-->
<div class="row">
    <div class="col-xl-7">
        <div class="card m-b-20">
            <div class="card-body">
                <div id="stacked-bar-chart">
                    <div class="pull-right ml-2">
                        <select class="yearSelection" style="transition: 320ms;">
                            <?php for($i=2016; $i<= date("Y"); $i++){ ?> 
                            <option value="<?= $i ?>" <?php echo $i==date("Y") ? "selected='selected'" : '' ?>><?= $i ?></option>
                            <?php  } ?>
                        </select>
                    </div>
                    <div class="pull-right">
                        <select class="renderChart">
                            <option value="day" ><?= __('user.day') ?></option>
                            <option value="week"><?= __('user.week') ?></option>
                            <option value="month" selected=""><?= __('user.month') ?></option>
                            <option value="year"><?= __('user.year') ?></option>
                        </select>
                    </div>
                </div>
                <h4 class="mt-0 mb-4 header-title"><?= __('user.overview') ?></h4>
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
                <div class="clearfix"></div>
                <ul class="legend">
                    <li>
                        <span class="cart_colorbox sale"></span> 
                        <p> Sale</p>
                    </li>
                    <li>
                        <span class="cart_colorbox order"></span> 
                        <p> Order</p>
                    </li>
                    <li>
                        <span class="cart_colorbox commissions"></span> 
                        <p> Commissions</p>
                    </li>
                    <li>
                        <span class="cart_colorbox action"></span> 
                        <p> Action</p>
                    </li>
                    <li>
                        <span class="cart_colorbox actioncommissions"></span> 
                        <p> Action Commissions</p>
                    </li>
                </ul>
                <div class="clearfix"></div>
                <div id="stacked-bar-chart2" class="ct-chart ct-golden-section" style="height: 390px;"></div>
                <div id="stacked-bar-chart2-empty" style="height: 242px;">
                    <div class="text-center">
                        <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:20px;">
                        <h3 class="m-t-40 text-center text-muted"><?= __('user.not_activity_yet') ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="card m-b-20">
            <div class="card-body">
                <h4 class="mt-0 header-title">Affiliates Payments Overview</h4>
                <?php 
                    $_t_paid_comm = (float)($totals['wallet_accept_amount']);
                    $_t_unpaid_comm = (float)($totals['unpaid_commition']);
                    $_t_request_comm = (float)($totals['wallet_request_sent_amount']);
                    $_t_total_commission = (float)($totals['all_clicks_comm'] + $totals['all_sale_comm'] + $totals['integration']['action_amount']);
                    ?>
                <h4 class="mt-0 header-title" style="color: #000000; text-align:center;"><?= __( 'TOTAL COMMISSION' ) ?>: <span class="font-12" style="color: #d17905;"><?php echo c_format($_t_total_commission) ?></span></h4>
                <ul class="list-inline widget-chart-22 row m-t-20 m-b-15 text-center">
                    <li class="col-sm-4">
                        <i class="mdi mdi-arrow-down-bold-circle-outline text-danger"></i>
                        <h6 class="mb-0"><?php echo c_format($_t_paid_comm) ?></h6>
                        <h6 class="font-10" style="color: #6362BB"><?= __( 'user.paid_commission' ) ?></h6>
                    </li>
                    <li class="col-sm-4">
                        <i class="mdi mdi-arrow-down-bold-circle-outline text-danger"></i>
                        <h6 class="mb-0"><?php echo c_format($_t_unpaid_comm) ?></h6>
                        <h6 class="font-10" style="color: #FFBB44"><?= __('user.unpaid_commition') ?></h6>
                    </li>
                    <li class="col-sm-4">
                        <i class="mdi mdi-arrow-down-bold-circle-outline text-danger"></i>
                        <h6 class="mb-0"><?php echo c_format($_t_request_comm) ?></h6>
                        <h6 class="font-10" style="color: #00BDFF"> <?= __('user.in_request_commition') ?></h6>
                    </li>
                    <!--<li class="col-sm-3">
                        <i class="mdi mdi-arrow-down-bold-circle-outline text-danger"></i>
                        <h5 class="mb-0"><?php echo c_format($_t_total_commission) ?></h5>
                        <h5 class="font-12" style="color: #D17905"> <?= __( 'user.total_commission' ) ?></h5>
                        </li>-->
                </ul>
                <?php if (($_t_paid_comm + $_t_unpaid_comm + $_t_request_comm) == 0) { ?>
                <div id="puiq-pie-empty">
                    <div class="text-center">
                        <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:40px;">
                        <h3 class="m-t-40 text-center text-muted"><?= __('user.not_activity_yet') ?></h3>
                    </div>
                    <script type="text/javascript">
                        function loadpaichart(){}
                    </script>
                </div>
                <?php }else{ ?> 
                    <div id="puiq-pie" class="ct-chart ct-golden-section simple-pie-chart-chartist"></div>
                    <script type="text/javascript">
                        function loadpaichart(){
                          var data = google.visualization.arrayToDataTable([
                            ['Label', 'Commissions'],
                            ['<?= __( 'user.paid_commission' ) ?>',     <?= (float)$_t_paid_comm ?>],
                            ['<?= __('user.unpaid_commition') ?>',      <?= (float)$_t_unpaid_comm ?>],
                            ['<?= __('user.in_request_commition') ?>',  <?= (float)$_t_request_comm ?>],
                          ]);

                          var options = {
                            legend: { position: 'bottom', maxLines: 3 },
                            is3D: true,
                            colors: ['#D70206','#F05B4F','#F4C63D']
                          };

                          var chart = new google.visualization.PieChart(document.getElementById('puiq-pie'));
                          chart.draw(data, options);
                        }
                    </script>
                    </br>
                    <p class="mt-3 mb-0 text-center text-muted">View Your Payment Status Dashboard of your Payments.</p>
                    <p class="mt-3 mb-0 text-center text-muted">Your Unpaid payments - Your in request payments - Your paid payments.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<!--END GRAPH CHART-->






<!------AFFILIATE LINKS START--------->
<div class="row">
    <div class="col-sm-12">
        <div class="card m-b-20">
            <div class="card-body">
                <h4 class="mt-0 header-title m-b-30"> <?= __('user.affiliates_links...') ?></h4>
                <div class="clearfix"></div>
                <div class="card-body p-0" style="height: 528px;overflow: auto;">
                    
                   <?php if ($tools==null) {?>
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
                                <?php $pagination = 5; ?>
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
                                                <td><?= $product['title'] ?></td>
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
                                                            echo c_format($product['click_commision_per']) .' of Per '. $product['click_commision_ppc'] .' Click';
                                                        }
                                                        ?>
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
                                                <td><?php echo $product['product_name'];?></td>
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
                                                        <div class="copy-input input-group">
                                                            <input readonly="readonly" value="<?= $productLink ?>" class="form-control">
                                                            <button type="button" copyToClipboard="<?= $productLink ?>" class="input-group-addon" >
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
                                                <td><?= $product['name'] ?></td>
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
                                                        <div class="copy-input input-group">
                                                            <input readonly="readonly" value="<?= $product['redirectLocation'][0] ?>" class="form-control">
                                                            <button type="button" copyToClipboard="<?= $product['redirectLocation'][0] ?>" class="input-group-addon" >
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
                                            <td colspan="10%">
                                                <button type="button" class="btn btn-primary show-more">Show More</button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                <?php } ?>
                            </table>
                        </div>
                        
                        
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!------AFFILIATE LINKS END--------->




<div class="row vc">
    <div class="col-xl-12">
        <div class="card h-100 m-b-20">
            <div class="card-header">
                <div class="pull-left">
                    <h4 class="mt-0 header-title"><?= __('admin.website_integration_store') ?></h4>
                </div>
                <div class="pull-right">
                    <select name="filter_integration[year]">
                        <?php foreach ($years as $key => $value) { ?>
                        <option value="<?= $value ?>" <?php if(date('Y') == $value) { ?>selected="selected"<?php } ?>><?= $value ?></option>
                        <?php } ?>
                    </select>
                    <select name="filter_integration[month]">
                        <?php foreach ($months as $key => $value) { ?>
                            <option value="<?= $value ?>"><?= $value ?></option>
                        <?php } ?>
                    </select>

                    <?php if($totals['integration']['hold_orders']){?>
                        <div   data-toggle="tooltip" title="Hold Orders">
                            <a href="<?= base_url('admincontrol/mywallet') ?>" class="order-hold-noti">
                                <i class="fa fa-bell"></i>
                                <span><?= $totals['integration']['hold_orders'] ?></span>
                            </a>
                        </div>
                    <?php } ?>
                    <?php if($totals['integration']['hold_action_count']){?>
                        <div   data-toggle="tooltip" title="Hold Actions">
                            <a href="<?= base_url('admincontrol/mywallet') ?>" class="order-hold-noti">
                                <i class="fa fa-bell"></i>
                                <span><?= $totals['integration']['hold_action_count'] ?></span>
                            </a>
                        </div>
                    <?php } ?>
                </div>
                <div class="clearfix"></div>
            </div>
            <div role="tabpanel" id="integration-data">
                <div class="data-here"></div>
                <div id="integration-chart" class="ct-chart ct-golden-section"></div>
                <script type="text/javascript">
                    $("#integration-data").delegate(".show-tabs","change",function(){
                        $("#integration-data .tab-pane").hide();
                        $("#site-" + $("#integration-data option:selected").attr("data-id") ).show();
                        getTotalIntegration();
                    })

                    function renderIntegrationChart(json){
                        if(json){
                            var _data = [];
                            _data.push(['key','Balance','Total Order Amount','Sales','Total Clicks','Total Click Amount','Total Actions','Total Action Amount','Commissions','Total Orders']);
                            $.each(json,function(i,j){
                                _data.push([
                                    i,
                                    ((j && j['balance']) ? j['balance'] : 0),
                                    ((j && j['total_orders_amount']) ? j['total_orders_amount'] : 0),
                                    ((j && j['sale']) ? j['sale'] : 0),
                                    ((j && j['click_count']) ? j['click_count'] : 0),
                                    ((j && j['click_amount']) ? j['click_amount'] : 0),
                                    ((j && j['action_count']) ? j['action_count'] : 0),
                                    ((j && j['action_amount']) ? j['action_amount'] : 0),
                                    ((j && j['total_commission']) ? j['total_commission'] : 0),
                                    ((j && j['total_orders']) ? j['total_orders'] : 0)
                                ])
                            });
                            var data = google.visualization.arrayToDataTable(_data);

                            var options = {
                                width: "100%",
                                height: "100%",
                                legend: { position: 'top', maxLines: 1 },
                                bar: { groupWidth: '50%' },
                                isStacked: true
                            };
                            var chart = new google.visualization.BarChart(document.getElementById('integration-chart'));
                            chart.draw(data, options);
                        }else{
                            var html = '<div class="tab-content"><div class="text-center">\
                            <img class="img-responsive" src="<?= base_url("assets/vertical/assets/images/no-data-2.png") ?>" style="margin-top:50px;">\
                            </div></div>';
                            $('#integration-chart').html(html);   
                        }
                    }

                    function getTotalIntegration(){
                        var integration_data_year = $('select[name="filter_integration[year]"]').val(); 
                        var integration_data_month = $('select[name="filter_integration[month]"]').val(); 
                        $.ajax({
                            url: '<?= base_url('usercontrol/get_integartion_data') ?>',
                            type: 'post',
                            dataType:'json',
                            data: {
                                integration_data_year: integration_data_year, 
                                integration_data_month: integration_data_month,
                                integration_data_selected : $("#integration-chart-type").val(),
                                integration_data_website_selected:$('#integration-data select[name="integration-chart-type"] option:selected').attr('data-website'),
                            },
                            success: function (json) {
                                if(json){
                                    $('#integration-data .data-here').html(json['html']);
                                    renderIntegrationChart(json['chart']);
                                    //$(".show-tabs").trigger('change');

                                     $("#integration-data .tab-pane").hide();
                                    $("#site-" + $("#integration-data option:selected").attr("data-id") ).show();
                                }
                            }
                        });
                    }

                    $('select[name="filter_integration[year]"], select[name="filter_integration[month]"]').on('change', getTotalIntegration);
                </script>
            </div>
        </div>
    </div>
</div>

</br>







<!------INTEGRATION ORDERS START--------->
<div class="row">
    <div class="col-xl-12">
        <div class="card m-b-20">
            <div class="card-body">
                <h4 class="mt-0 header-title"><?= __('user.integration_orders') ?></h4>
                <?php if ($integration_orders ==null) {?>
                    <div class="text-center">
                        <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png">
                        <h3 class="m-t-40 text-center text-muted"><?= __('user.no_orders') ?></h3>
                    </div>
                <?php } else { ?>
                    <div class="table-rep-plugin">
                        <div class="table-responsive b-0" data-pattern="priority-columns">
                            <table id="tech-companies-1" class="table  table-striped">
                                <thead>
                                    <tr>
                                        <th width="50px"><?= __('user.id') ?></th>
                                        <th width="90px"><?= __('user.order_id') ?></th>
                                        <th width="180px"><?= __('user.user_name') ?></th>
                                        <th><?= __('user.product_ids') ?></th>
                                        <th><?= __('user.total') ?></th>
                                        <th><?= __('user.currency') ?></th>
                                        <th width="90px"><?= __('user.commission_type') ?></th>
                                        <th><?= __('user.commission') ?></th>
                                        <th><?= __('user.ip') ?></th>
                                        <th><?= __('user.country_code') ?></th>
                                        <th><?= __('user.website') ?></th>
                                        <th><?= __('user.script_name') ?></th>
                                        <th width="180px"><?= __('user.created_at') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($integration_orders as $key => $order) { ?>
                                    <tr>
                                        <td><?= $order['id'] ?></td>
                                        <td><?= $order['order_id'] ?></td>
                                        <td><?= $order['user_name'] ?></td>
                                        <td><?= $order['product_ids'] ?></td>
                                        <td><?= $order['total'] ?></td>
                                        <td><?= $order['currency'] ?></td>
                                        <td><?= $order['commission_type'] ?></td>
                                        <td><?= c_format($order['commission']) ?></td>
                                        <td><?= $order['ip'] ?></td>
                                        <td><?= $order['country_code'] ?>&nbsp;<img title="<?= $order['country_code'] ?>" src="<?= base_url('assets/vertical/assets/images/flags/'. strtolower($order['country_code'])) ?>.png" width='25' height='15'></td>
                                        <td><a href="//<?= $order['base_url'] ?>" target='_blank'><?= $order['base_url'] ?></a></td>
                                        <td><img class="img-integration" src="<?= base_url('assets/integration/small/' .$order['script_name'].'.png') ?>"></td>
                                        <td><?= $order['created_at'] ?></td>
                                    </tr>
                                    <?php } ?>
                                   
                                </tbody>
                            </table>
                        </div>
                    </div>
                <div class="card-footer text-right border-0"><a href="<?= base_url('integration/user_orders') ?>"><?= __('user.integration_user_orders') ?></a></div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<!------INTEGRATION ORDERS END--------->






<!------WINDOWS LOGS START--------->
<div class="row">
    <div class="col-xl-12">
        <div class="card mt-2 mb-2">
            <div class="card-header">
                <h4 class="mt-0 header-title pull-left">LIVE LOGS WINDOW...</h4>
                <div class="pull-right">
                    <button class="btn btn-outline-warning btn-sm btn-count-notification" data-toggle="tooltip" title='All Notifications' data-key='live_log' data-type='admin'>
                        <span class="count-notifications"><?= count($live_window) ?></span>
                        <i class="fa fa-bell"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0" id="ajax-live_window">
                <div class="text-center m-4 empty-data" style="display: <?= !empty($live_window) ? 'none' : 'block'; ?>">
                    <img class="img-responsive" src="<?php echo base_url("assets/vertical/assets/images/no-data-2.png"); ?>" style="margin-top:10px;">
                    <h3 class="m-t-40 text-center"><?= __('admin.not_activity_yet') ?></h3>
                </div>

                <ul class="ajax-live_window" style="display: <?= empty($live_window) ? 'none' : 'block'; ?>;    height: 100%;">
                    <?php foreach ($live_window as $key => $value) { ?>
                        <li><?= $value['title'] ?></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<!------WINDOWS LOGS END--------->





<!------INTEGRATION DATA + REFFER DATA START--------->
<div class="row">
    <div class="col-xl-<?= $refer_status ? '8' : '12' ?>">
        <!--<div class="card m-b-20">
            <div class="card-header m-b-20">
                <h6 class="header-title pull-left m-10"><?= __('user.website_integration_store') ?></h6>
            </div>
            <div role="tabpanel" id="integration-data">
                <select class="form-control show-tabs select2-input">
                    <option value="all">All</option>
                    <?php $index = 0; foreach ($totals['integration']['all'] as $website => $value) { ?>
                    <option value="<?= ++$index ?>"><?= $website ?></option>
                    <?php } ?>
                </select>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane" id="site-all" style="display:block">
                        <ul class="list-group p-t-10" style="height:100%;">
                            <li class="list-group-item">
                                <?php echo __( 'user.total_balance' ) ?>
                                <span class="badge badge-light font-14 pull-right">
                                <?php echo c_format($totals['integration']['user_balance']) ?>        
                                </span>
                            </li>
                            <li class="list-group-item">
                                <?php echo __( 'user.total_sales' ) ?>
                                <span class="badge badge-light font-14 pull-right">
                                <?php echo (int)$totals['integration']['total_count'] ?> / <?php echo c_format($totals['integration']['sale']) ?>        
                                </span>
                            </li>
                            <li class="list-group-item">
                                <?php echo __( 'user.total_clicks' ) ?>
                                <span class="badge badge-light font-14 pull-right">
                                <?php echo (int)$totals['integration']['click_count'] ?> / <?php echo c_format($totals['integration']['click_amount']) ?>
                                </span>
                            </li>
                            <li class="list-group-item">
                                Total Actions
                                <span class="badge badge-light font-14 pull-right">
                                <?php echo (int)$totals['integration']['action_count'] ?> / <?php echo c_format($totals['integration']['action_amount']) ?>
                                </span>
                            </li>
                            <li class="list-group-item">
                                <?php echo __( 'user.total_commission' ) ?>
                                <span class="badge badge-light font-14 pull-right">
                                <?php echo c_format($totals['integration']['total_commission']) ?> 
                                </span>
                            </li>
                            <li class="list-group-item">
                                <?php echo __( 'user.total_orders' ) ?>
                                <span class="badge badge-light font-14 pull-right">
                                <?php echo (int)$totals['integration']['total_orders'] ?> 
                                </span>
                            </li>
                        </ul>
                    </div>
                    <?php $index = 0; foreach ($totals['integration']['all'] as $website => $value) { ?>
                    <div role="tabpanel" class="tab-pane" id="site-<?= ++$index ?>" style="height:360px;display: none;">
                        <ul class="list-group p-t-10" >
                            <li class="list-group-item">
                                <?php echo __( 'user.total_balance' ) ?>
                                <span class="badge badge-light font-14 pull-right">
                                <?php echo c_format($value['user_balance']) ?>
                                </span>
                            </li>
                            <li class="list-group-item">
                                <?php echo __( 'user.total_sales' ) ?>
                                <span class="badge badge-light font-14 pull-right">
                                <?php echo (int)$value['total_count'] ?> / <?php echo c_format($value['sale']) ?>        
                                </span>
                            </li>
                            <li class="list-group-item">
                                <?php echo __( 'user.total_clicks' ) ?>
                                <span class="badge badge-light font-14 pull-right">
                                <?php echo (int)$value['click_count'] ?> / <?php echo c_format($value['click_amount']) ?>
                                </span>
                            </li>
                            <li class="list-group-item">
                                Total Actions
                                <span class="badge badge-light font-14 pull-right">
                                <?php echo (int)$value['action_count'] ?> / <?php echo c_format($value['action_amount']) ?>
                                </span>
                            </li>
                            <li class="list-group-item">
                                <?php echo __( 'user.total_commission' ) ?>
                                <span class="badge badge-light font-14 pull-right">
                                <?php echo c_format($value['click_amount'] + $value['sale'] + $value['action_amount']) ?> 
                                </span>
                            </li>
                            <li class="list-group-item">
                                <?php echo __( 'user.total_orders' ) ?>
                                <span class="badge badge-light font-14 pull-right">
                                <?php echo (int)$value['total_orders'] ?> 
                                </span>
                            </li>
                            <li class="list-group-item">
                                <a class="btn btn-lg btn-default btn-success" href="http://<?= $website ?>" target="_blank"><?php echo __( 'user.priview_store' ) ?></a>
                            </li>
                        </ul>
                    </div>
                    <?php } ?>
                </div>
                <script type="text/javascript">
                    $(".show-tabs").on('change',function(){
                        $("#integration-data .tab-pane").hide();
                        $("#site-" + $(this).val()).show();
                    })
                </script>
            </div>
        </div>-->
    </div>
    <?php if($refer_status) { ?>
    <div class="col-xl-12">
        <div class="card m-b-20">
            <div class="card-header m-b-20">
                <h4 class="header-title pull-left m-20"><?= __('user.Total_sales_clicks_actions_levels') ?></h4>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        <?= __('user.referals_product_click_commissions') ?> 
                        <span class="badge badge-light font-14 pull-right">
                        <?= (int)$refer_total['total_product_click']['clicks'] ?> /
                        <?= c_format($refer_total['total_product_click']['amounts']) ?>
                        </span>
                    </li>
                    <li class="list-group-item">
                        <?= __('user.referals_sale_commissions') ?>
                        <span class="badge badge-light font-14 pull-right">
                        <?= (int)$refer_total['total_product_sale']['counts'] ?> /
                        <?= c_format($refer_total['total_product_sale']['amounts']) ?>
                        </span>
                    </li>
                    <li class="list-group-item">
                        <?= __('user.referals_general_click_commissions') ?>
                        <span class="badge badge-light font-14 pull-right">
                        <?= (int)$refer_total['total_ganeral_click']['total_clicks'] ?> /
                        <?= c_format($refer_total['total_ganeral_click']['total_amount']) ?>
                        </span>
                    </li>
                    <li class="list-group-item">
                        <?= __('user.referals_action_commissions') ?>
                        <span class="badge badge-light font-14 pull-right">
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
</div>
<!------INTEGRATION DATA + REFFER DATA END--------->








<!------REFFER LINKS START--------->				
<?php if(($store['registration_status'] && $refer_status) || $store_setting['status']){ ?>
<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                <h4 class="mt-0 header-title"><?= __('user.your_unique_reseller_link') ?></h4>
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
            loadpaichart();
            getTotalIntegration();
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
                  i =  i + 1;
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