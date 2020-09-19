<?php
$db =& get_instance();
$userdetails = get_object_vars($db->user_info());
$store_setting =$db->Product_model->getSettings('store');
$products = $db->Product_model;
//$notifications = $products->getnotificationnew('admin',null,5);
$notifications_count = $products->getnotificationnew_count('admin',null);
?>
<link rel="stylesheet" type="text/css" href="<?= base_url('/assets/vertical/assets/plugins/chartist/css/chartist.min.css') ?>?v=<?= av() ?>">
<script type="text/javascript" src="<?= base_url('assets/vertical/assets/plugins/chartist/js/chartist.min.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('/assets/vertical/assets/plugins/jvectormap/jquery-jvectormap-2.0.2.css') ?>?v=<?= av() ?>">
<script src="<?= base_url('assets/vertical/assets/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js') ?>"></script>
<script src="<?= base_url('assets/vertical/assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') ?>"></script>
<script src="<?= base_url('assets/vertical/assets/plugins/jvectormap/gdp-data.js') ?>"></script>
<script src="<?= base_url('assets/vertical/assets/plugins/jvectormap/jquery-jvectormap-us-aea-en.js') ?>"></script>
<script src="<?= base_url('assets/vertical/assets/plugins/jvectormap/jquery-jvectormap-uk-mill-en.js') ?>"></script>
<script src="<?= base_url('assets/vertical/assets/plugins/jvectormap/jquery-jvectormap-us-il-chicago-mill-en.js') ?>"></script>
<script src="<?php echo base_url(); ?>assets/vertical/assets/plugins/chartist/js/chartist-plugin-tooltip.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vertical/assets/plugins/jquery-knob/excanvas.js"></script>
<script src="<?php echo base_url(); ?>assets/vertical/assets/plugins/jquery-knob/jquery.knob.js"></script>

<?php if($this->session->flashdata('success')){?>
    <div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <?php echo $this->session->flashdata('success'); ?> </div>
<?php } ?>
             


  
<div class="row">
    <div class="col-xl-12">
        <div class="row">
            <div class="col-xl-3">
                <span class="mini-stat-icon bg-success"><i class="fa fa-bell"></i></span>
                <div class="card-header text-center bg-white"><h6 class="mt-0 header-title"><?php echo __( 'admin.total_balance') ?></h6></div>
                <div class="mini-stat clearfix bg-white">
                    <div class="mini-stat-info text-center">
                        <h5 class="counter mt-0 text-primary ajax-total_sale_balance"><?php echo $totals['total_sale_balance'] ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <span class="mini-stat-icon bg-success"><i class="fa fa-bell"></i></span>
                <div class="card-header text-center bg-white"><h6 class="mt-0 header-title"><?php echo __( 'admin.total_sales' ) ?></h6></div>
                <div class="mini-stat clearfix bg-white">
                    <div class="mini-stat-info text-center">
                        <h5 class="counter mt-0 text-primary ">
                            <span data-toggle='tooltip' title="Total Orders" class="no-icon ajax-total_sale_count"><?= (int)$totals['total_sale_count'] ?></span> /
                            <span data-toggle='tooltip' title="Vendor Orders" class="no-icon ajax-vendor_order_count"><?= (int)$totals['vendor_order_count'] ?></span> /
                            <span data-toggle='tooltip' title="Sales" class="no-icon ajax-total_balance"><?php echo $totals['full_total_balance'] ?></span>
                                
                        </h5>
                    </div>
                    <button class="btn-sm btn-window" data-log='sale'><i class="fa fa-eye"></i></button>
                </div>
            </div>
            <div class="col-xl-3">
                <span class="mini-stat-icon bg-success"><i class="fa fa-bell"></i></span>
                <div class="card-header text-center bg-white"><h6 class="mt-0 header-title"><?php echo __( 'admin.clicks_statistic' ) ?></h6></div>
                <div class="mini-stat clearfix bg-white">
                    <div class="mini-stat-info text-center">
                        <h5 class="counter mt-0 text-primary ajax-all_clicks_comm"> <?php echo $totals['full_all_clicks_comm'] ?></h5>
                    </div>
                    <button class="btn-sm btn-window" data-log='click'><i class="fa fa-eye"></i></button>
                </div>
            </div>
            <div class="col-xl-3">
                <span class="mini-stat-icon bg-success"><i class="fa fa-bell"></i></span>
                <div class="card-header text-center bg-white"><h6 class="mt-0 header-title"><b><?= __('admin.total_actions_home') ?></b></h6></div>
                <div class="mini-stat clearfix bg-white">
                    <div class="mini-stat-info text-center">
                        <h5 class="counter mt-0 text-primary ajax-action_count_action_amount"><?php echo $totals['full_action_count_action_amount'] ?></h5>
                    </div>
                    <button class="btn-sm btn-window" data-log='action'><i class="fa fa-eye"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!---------VENDOR ROW DATA---------->
<div class="row">
    <div class="col-xl-12">
        <div class="row">
            <div class="col-xl-3">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="<?= base_url('admincontrol/mywallet/') ?>"><?= __('admin.vendor_paid_amount') ?></a></span>
                    <span class="counter mt-0 ajax-vendor_wallet_accept_amount  badge badge-primary cursor-pointer badge-pill float-center" style='width: 50px;right:0;'><?= c_format($totals['vendor_wallet_accept_amount']) ?></span>
                </li>
            </div>
            
            <div class="col-xl-3">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="<?= base_url('admincontrol/mywallet/') ?>"><?= __('admin.vendor_in_request_amount') ?> </a></span>
                    <span class="counter mt-0 ajax-vendor_wallet_request_sent_amount  badge badge-primary cursor-pointer badge-pill float-center" style='width: 50px;right:0;'><?= c_format($totals['vendor_wallet_request_sent_amount']) ?></span>
                </li>
                                        
            </div>
            
            <div class="col-xl-3">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="<?= base_url('admincontrol/mywallet/') ?>"><?= __('admin.vendor_unpaid_amount') ?> </a></span>
                    <span class="counter mt-0 ajax-vendor_wallet_unpaid_amount  badge badge-primary cursor-pointer badge-pill float-center" style='width: 50px;right:0;'><?= c_format($totals['vendor_wallet_unpaid_amount']) ?></span>
                </li>
            </div>
            
            <div class="col-xl-3">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="<?= base_url('admincontrol/listorders/') ?>"><?= __('admin.vendor_orders') ?> </a></span>
                    <span class="counter  ajax-vendor_order_count mt-0 badge badge-primary cursor-pointer badge-pill float-center" style='width: 50px;right:0;'><?= (int)$totals['vendor_order_count'] ?></span>
                </li>
            </div>
        </div>
    </div>
</div>
<!---------VENDOR ROW DATA---------->
<br>

<div class="row">
<div class="col-xl-7">
        <div class="card h-100">
            <li class="list-group-item list-group-item-white font-weight-bold" style="text-align:center;"><?php echo __('admin.admin_overview') ?>
            <div class="pull-left">
            </div>
            <div class="pull-right">
                <select class="renderChart">
                    <option value="day" ><?= __('admin.day') ?></option>
                    <option value="week"><?= __('admin.week') ?></option>
                    <option value="month" selected=""><?= __('admin.month') ?></option>
                    <option value="year"><?= __('admin.year') ?></option>
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
                    <li class="list-inline-item d-block d-sm-inline-block m-auto">
                        <i class="mdi mdi-arrow-up-bold-circle-outline text-success"></i>
                        <h5 class="mb-0 ajax-weekly_balance"><?= $totals['full_weekly_balance'] ?></h5>
                        <p class="font-14"><?php echo __('admin.weekly_earnings') ?></p>
                    </li>
                    <li class="list-inline-item d-block d-sm-inline-block m-auto">
                        <i class="mdi mdi-arrow-down-bold-circle-outline text-danger"></i>
                        <h5 class="mb-0 ajax-monthly_balance"><?= $totals['full_monthly_balance'] ?></h5>
                        <p class="font-14"><?php echo __('admin.monthly_earnings') ?></p>
                    </li>
                    <li class="list-inline-item d-block d-sm-inline-block m-auto">
                        <i class="mdi mdi-arrow-up-bold-circle-outline text-success"></i>
                        <h5 class="mb-0 ajax-yearly_balance"><?= $totals['full_yearly_balance'] ?></h5>
                        <p class="font-14"><?php echo __('admin.yearly_earnings') ?></p>
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
                <h3 class="m-t-40 text-center"><?= __('admin.not_activity_yet') ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-5">
    <div class="card h-100">
    <li class="list-group-item list-group-item-white font-weight-bold" style="text-align:center;"><?php echo __('admin.admin_statistics') ?></li>
    
                                    <div class="table-responsive">
                                        <table class="table">
                                        
                                            <tbody>
                                                <?php 
                                                    $top_user = isset($populer_users[0]) ? $populer_users[0] : false;
                                                    //unset($populer_users[0]);
                                                    $flag = '';
                                                    if ($top_user['sortname'] != '') {
                                                        $flag = base_url('assets/vertical/assets/images/flags/' . strtolower($top_user['sortname']) . '.png');
                                                    }
                                                    if($top_user){ ?>
                                                    
                                                    <tr class="<?= $key == 0 ? 'bg-primary text-white' : '' ?>">
                                                        <!--<td><?php echo $top_user['id']; ?></td>-->
                                                      
                                                        <?php
                                                            $flag = '';
                                                            if ($top_user['sortname'] != '') {
                                                                $flag = base_url('assets/vertical/assets/images/flags/' . strtolower($top_user['sortname']) . '.png');
                                                            }
                                                        ?>
                                    
                                                        <td><img class="populer-user-img" src="<?= $products->getAvatar($top_user['avatar']) ?>"></td>
                                                        <td><?= __( 'admin.top_affiliate' ) ?></br><?php echo $top_user['firstname']; ?> <?php echo $top_user['lastname']; ?></td>
                                                        <td><?= __('admin.top_aff_country') ?></br><?= strtoupper($top_user['sortname']) ?> <img class="country-flag" src="<?php echo $flag; ?>"></td>
                                                        <td><?= __('admin.top_aff_sales') ?></br><?php echo c_format($top_user['amount']); ?></td>
                                                        <td class="ajax-all_commition"><?= __('admin.top_aff_commission') ?></br><?php echo c_format($top_user['all_commition']); ?></td>
                                                    </tr>
                                                <?php } ?> 
                                            </tbody>
                                    </table>
                                    </div>

                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <a href="<?= base_url('admincontrol/userslist') ?>"> <?php echo __( 'admin.total_affiliate' ) ?></a></span>
                                            <span class="counter mt-0 badge badge-primary ajax-user_count cursor-pointer badge-pill float-center btn-window2" data-log="member" style='width: 50px;right:0;'>
                                            <?php echo !empty($user_count) ? count($user_count) : '0'; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?php echo __( 'admin.online_admins' ) ?></span>
                                            <div id="online_admin">
                                                <span class="counter count mt-0 badge badge-primary cursor-pointer badge-pill float-center" style='width: 50px;right:0;'>
                                                <?= $online_count['admin']['online'] ?></span>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?php echo __( 'admin.online_users' ) ?></span>
                                            <div id="online_user">
                                                <span class="counter count mt-0 badge badge-primary cursor-pointer badge-pill float-center" style='width: 50px;right:0;'>
                                                <?= $online_count['user']['online'] ?></span>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <a href="<?= base_url('admincontrol/mywallet') ?>"> <?php echo __('admin.hold_actions') ?></a></span>
                                            <span class="counter ajax-full_hold_action_count mt-0 badge badge-primary cursor-pointer badge-pill float-center btn-window2" data-log="hold_actions" style='width: 50px;right:0;'>
                                            <?= (int)$totals['full_hold_action_count'] ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <a href="<?= base_url('admincontrol/listorders') ?>"> <?php echo __('admin.digital_store_hold_order') ?></a></span>
                                            <span class="counter mt-0 ajax-hold_orders badge badge-primary cursor-pointer badge-pill float-center btn-window2" data-log="hold_orders" style='width: 50px;right:0;'>
                                            <?= (int)$totals['full_hold_orders'] ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <a href="<?= base_url('admincontrol/listorders') ?>"> <?php echo __('admin.digital_store_approved_order') ?></a></span>
                                            <span class="counter mt-0 ajax-full_digital_orders badge badge-primary cursor-pointer badge-pill float-center btn-window2" data-log="orders" style='width: 50px;right:0;'>
                                            <?= (int)$totals['full_digital_orders'] ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <a href="<?= base_url('integration/orders') ?>"> <?php echo __('admin.external_inte_order') ?></a></span>
                                            <span class="counter mt-0 badge badge-primary cursor-pointer badge-pill float-center ajax-external_inte_order btn-window2" data-log="ex_orders" style='width: 50px;right:0;'>
                                            <?= (int)$totals['external_inte_order'] ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <a href="<?= base_url('admincontrol/mywallet/') ?>"><?= __('admin.vendor_on_hold amount') ?> </a></span>
                                            <span class="counter mt-0 ajax-vendor_wallet_on_hold_amount  badge badge-primary cursor-pointer badge-pill float-center" style='width: 50px;right:0;'><?= c_format($totals['vendor_wallet_on_hold_amount']) ?></span>
                                        </li>
                                        
                                        <?php
                                            $_t_paid_comm = (float)($totals['wallet_accept_amount']);
                                            $_t_unpaid_comm = (float)($totals['unpaid_commition']);
                                            $_t_request_comm = (float)($totals['wallet_request_sent_amount']);
                                            $_t_total_commission = (float)($totals['all_clicks_comm'] + $totals['all_sale_comm'] + $totals['integration']['action_amount']);
                                            ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <i class="fa fa-circle"> <?= __( 'admin.total_paid_commission' ) ?> </i>
                                            <span class="badge badge-primary cursor-pointer badge-pill ajax-_t_paid_comm"><?php echo c_format($_t_paid_comm) ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <i class="fa fa-circle"> <?= __('admin.total_unpaid_commition') ?> </i>
                                            <span class="badge badge-primary cursor-pointer badge-pill ajax-_t_unpaid_comm"><?php echo c_format($_t_unpaid_comm) ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <i class="fa fa-circle"> <?= __('admin.total_in_request_commition') ?></i>
                                            <span class="badge badge-primary cursor-pointer badge-pill ajax-_t_request_comm"><?php echo c_format($_t_request_comm) ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <i class="fa fa-circle"> <?= __('admin.total_affiliates_comm') ?> </i>
                                            <span class="badge badge-primary cursor-pointer badge-pill ajax-_t_total_commission"><?php echo c_format($_t_total_commission) ?></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
</div>
                        
                        
</br>
                    
                    
<div class="row">
<div class="col-xl-7">
    <div class="card h-100">
        <li class="list-group-item list-group-item-white font-weight-bold" style="text-align:center;"><?= __('admin.affiliates_map') ?></li>
        <div class="card-title m-0 p-3">
        </div>
        <div class="card-body new-user">
            <div class="world-map-users"></div>
            <script type="text/javascript">
                function load_userworldmap(data) {
                    $('.world-map-users').html('<div id="world-map-users" style="height: 450px;"></div>');
                    $('.world-map-users #world-map-users').vectorMap({
                        map: 'world_mill_en',
                        scaleColors: ['#4ac18e', '#4ac18e'],
                        normalizeFunction: 'polynomial',
                        hoverOpacity: 0.7,
                        hoverColor: true,
                        regionStyle: {
                            initial: {
                                fill: '#6362bb'
                            }
                        },
                        markerStyle: {
                            initial: {
                                r: 9,
                                'fill': '#ff4000',
                                'fill-opacity': 0.9,
                                'stroke': '#fff',
                                'stroke-width': 7,
                                'stroke-opacity': 0.4
                            },
                            hover: {
                                'stroke': '#fff',
                                'fill-opacity': 1,
                                'stroke-width': 1.5
                            }
                        },
                        backgroundColor: 'transparent',
                        markers:data,
                    });
                }
                load_userworldmap(<?= json_encode($userworldmap) ?>)
            </script>
        </div>
    </div>
</div>
<div class="col-xl-5">
    <div class="card h-100">
      
            <li class="list-group-item list-group-item-white font-weight-bold pull-left"><?php echo __('admin.live_logs') ?>
            <div class="pull-right">
                <button class="btn btn-outline-warning btn-sm btn-count-notification" data-toggle="tooltip" title='All Notifications' data-key='live_log' data-type='admin'>
                    <span class="count-notifications">0</span>
                    <i class="fa fa-bell"></i>
                </button>
                <button class="btn btn-blue-grey btn-sm btn-setting" data-toggle="tooltip" title='Live Log Settings' data-key='live_log' data-type='admin'>
                    <i class="fa fa-gear"></i>
                </button>
            </div>
            </li>

         <div class="card-body p-0" id="ajax-live_window">
                <div class="text-center m-4 empty-data" style="display: <?= !empty($live_window) ? 'none' : 'block'; ?>">
                    <img class="img-responsive" src="<?php echo base_url("assets/vertical/assets/images/no-data-2.png"); ?>" style="margin-top:10px;">
                    <h3 class="m-t-40 text-center"><?= __('admin.not_activity_yet') ?></h3>
                </div>

                <ul class="ajax-live_window h-100" style="display: <?= empty($live_window) ? 'none' : 'block'; ?>">
                    <?php foreach ($live_window as $key => $value) { ?>
                        <li><?= $value['title'] ?></li>
                    <?php } ?>
                </ul>
            </div>
    </div>
</div>
</div>
                    
</br>
                
<div class="row">
<div class="col-xl-7">
<div class="card h-100">
            <li class="list-group-item list-group-item-white font-weight-bold pull-left"><?= __('admin.website_integration_store') ?>
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
            </div>
            </li>
        
        <div role="tabpanel" id="integration-data">
            <div class="data-here"></div>
            <div id="integration-chart" class="ct-chart ct-golden-section"></div>
            <script type="text/javascript">
                $("#integration-data").delegate(".show-tabs","change",function(){
                    $("#integration-data .tab-pane").hide();
                    $("#site-" + $("#integration-data option:selected").attr("data-id") ).show();
                })
            </script>
        </div>
    </div>
</div>

<div class="col-xl-5">
    <div class="card h-100">
            <li class="list-group-item list-group-item-white font-weight-bold" style="text-align:center;"><?php echo __('admin.popular_affiliates') ?></li>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <?php foreach ($populer_users as $key => $users) { ?>
                                <tr class="<?= $key == 0 ? 'bg-primary text-white' : '' ?>">
                                    <!--<td><?php echo $users['id']; ?></td>-->
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
                 <div class="text-center m-4 empty-data" style="display: <?= !empty($populer_users) ? 'none' : 'block'; ?>">
                     <img class="img-responsive" src="<?php echo base_url("assets/vertical/assets/images/no-data-2.png"); ?>" style="margin-top:100px;">
                    <h3 class="m-t-40 text-center"><?= __('admin.not_activity_yet') ?></h3>
                 </div>
                                    
                   
            </div>
      
        <li class="list-group-item list-group-item-white font-weight-bold" style="text-align:center;"><?= __('admin.admin_invitation_link') ?></li>
        <div class="card body">
            <?php $share_url = base_url('register/' . base64_encode($userdetails['id'])); ?>
            <div class="card-body new-user">
                <div class="row">
                    <h6 class="col-sm-12"><?= __('admin.register_new_affiliate_account_link') ?></h6>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="input-group m-1">
                                <input type="text" readonly="readonly" value="<?= $share_url ?>" class="form-control" id="unique_re_link">
                                <button copyToClipboard="<?= $share_url ?>" class="input-group-addon">
                                    <img src="<?php echo base_url();?>assets/images/clippy.svg" class="tooltiptext" width="15px" height="15px" >
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="store-link"></div>
                    </div>
                </div>
                <script type="text/javascript">
                    $(document).on('ready',function(){
                        $(".store-link").jsSocials({
                            url: "<?= $share_url ?>",
                            showCount: false,
                            showLabel: false,
                            shareIn: "popup",
                            shares: ["email", "twitter", "facebook", "googleplus", "linkedin", "whatsapp"]
                        });
                    })
                </script>
            </div>
        </div>
    </div>
    </div>
</div>
                
                
          
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">
                

                $(document).ready(function () {
                    $('body').trigger("click");
                    $(".height-fix").niceScroll()
                    google.charts.load('current', {packages: ['corechart', 'bar','gauge']});
                    google.charts.setOnLoadCallback(charloadfirsttime);
                })

                function renderStackedBarChart(group) {
                    var group = group ? group : 'month';
                    var selectedyear = $('.yearSelection').val();
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        data: {renderChart: group,selectedyear:selectedyear},
                        success: function (json) {
                            loadChartData(json);
                        },
                    })
                }
                if ($(".renderChart").val() !='month') {
                    $('.yearSelection').css('opacity',0);
                }else{
                    $('.yearSelection').css('opacity',1);
                }
                $(".renderChart").change(function () {
                    if ($(this).val()!='month') {
                        $('.yearSelection').css('opacity',0);
                    }else{
                        $('.yearSelection').css('opacity',1);
                    }
                    renderStackedBarChart($(this).val());
                })
                $('.yearSelection').change(function () {
                    $(".renderChart").trigger('change');
                })
                function charloadfirsttime() {
                    renderStackedBarChart('month');
//loadpaichart();
setTimeout2(undefined,true)
getTotalIntegration();
//getIntegrationChart();
}
function toArray(myObj) {
    return $.map(myObj, function(value, index) {
        return [value];
    });
}
function loadChartData(json) {
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
        height: '100%',
        legend: { position: 'bottom', maxLines: 3 },
        bar: { groupWidth: '40' },
        isStacked: true,
    };
    var chart = new google.visualization.ColumnChart(document.getElementById('stacked-bar-chart2'));
    chart.draw(data, options);
}
</script>
<?php
    $last_id_integration_logs = 0;
    $last_id_integration_orders = 0;
    $last_id_newuser = 0;
    $last_id_notifications = 0;
    foreach ($integration_logs as $key => $log){
        if($last_id_integration_logs <= $log['id']){ $last_id_integration_logs = $log['id']; }
    }
    /*foreach ($integration_orders as $key => $order) {
        if($last_id_integration_orders <= $order['id']){ $last_id_integration_orders = $order['id']; }
    }*/
    foreach ($newuser as $users) {
        if($last_id_newuser <= $users['id']){ $last_id_newuser = $users['id']; }
    }
    foreach ($notifications as $key => $notification) {
        if($last_id_notifications <= $notification['notification_id']){ $last_id_notifications = $notification['notification_id']; }
    }
?>
<script type="text/javascript">
var ajax_interval = 2000; // in time mili second
<?php  if((float)$live_dashboard['admin_data_load_interval'] >= 2){ ?>
    ajax_interval  = <?= (float)$live_dashboard['admin_data_load_interval'] * 1000 ?>;
<?php } ?>
var dashboard_xhr;
var last_id_integration_logs = <?= (int)$last_id_integration_logs ?>;
var last_id_integration_orders = <?= (int)$last_id_integration_orders ?>;
var last_id_newuser = <?= (int)$last_id_newuser ?>;
var last_id_notifications = <?= (int)$last_id_notifications ?>;
var total_commision_filter_year = '<?= date('Y') ?>';
var total_commision_filter_month = '<?= date('m') ?>';
var settings_clear = false;
function playSound(){
    $("body").append('<iframe id="noti-sound-iframe" src="<?= base_url('/assets/notify/notification.mp3') ?>"></iframe>')
    $("#noti-sound-iframe").on('load',function(){
        setTimeout(function(){
            $("#noti-sound-iframe").remove();
        },1000)
    });
}
function setTimeout2(callnexttime,show_popup) {
    $("<div />").css("height","0px").animate({height:'100px'},{
        duration: ajax_interval,
        step: function(now){
            $("#dashboard-progress").css('width',now+"%");
        },
        complete: function(){
            getDashboard(callnexttime,show_popup);
        }
    });
}
function getDashboard(callnexttime,show_popup){
    if(dashboard_xhr && dashboard_xhr.readyState != 4) dashboard_xhr.abort();
    var selectedyear = $('.yearSelection').val();
    $(".ajax-total_sale_balance").parents('.col-xl-3').find('.mini-stat-icon i').removeClass('blink_me');
    $(".ajax-total_balance").parents('.col-xl-3').find('.mini-stat-icon i').removeClass('blink_me');
    $(".ajax-all_clicks_comm").parents('.col-xl-3').find('.mini-stat-icon i').removeClass('blink_me');
    $(".ajax-action_count_action_amount").parents('.col-xl-3').find('.mini-stat-icon i').removeClass('blink_me');
    $(".ajax-user_count").parents('.col-xl-3').find('.mini-stat-icon i').removeClass('blink_me');
    var integration_data_year = $('select[name="filter_integration[year]"]').val();
    var integration_data_month = $('select[name="filter_integration[month]"]').val();
    var tab_type = $('#integration-data select[name="integration-chart-type"] option:selected').attr('data-website');
    dashboard_xhr = $.ajax({
        url:'<?= base_url('admincontrol/ajax_dashboard') ?>',
        type:'POST',
        dataType:'json',
        data:{
            renderChart  : $(".renderChart").val(),
            selectedyear :selectedyear,
            last_id_integration_logs :last_id_integration_logs,
            last_id_integration_orders :last_id_integration_orders,
            last_id_newuser :last_id_newuser,
            last_id_notifications :last_id_notifications,
            last_id_top_notifications :$("#last_id_notifications").val(),
            total_commision_filter_year : $('select[name="filter_commission[year]"]').val(),
            total_commision_filter_month : $('select[name="filter_commission[month]"]').val(),
            integration_data_year : integration_data_year,
            integration_data_month : integration_data_month,
            integration_data_website_selected: tab_type,
            integration_data_selected : $("#integration-chart-type").val(),
        },
        beforeSend:function(){},
        complete:function(){
            if(!callnexttime){
                setTimeout2(undefined,true);
            }
        },
        success:function(json){
            setTimeout(function(){
                $('.ajax-live_window .fa-bell').removeClass('blink-icon');
                $(".mini-stat-icon i").removeClass("blink-icon");
            }, 5000);
            var play_sound = false;
            if(json['totals']){
                if($.trim($(".ajax-total_balance").html()) != json['totals']['full_total_balance']){
                    play_sound = true;
                    $(".ajax-total_balance").parents('.col-xl-3').find('.mini-stat-icon i').addClass('blink-icon');
                }

                if($.trim($(".ajax-total_sale_balance").html()) != json['totals']['total_sale_balance']){
                    play_sound = true;
                    $(".ajax-total_sale_balance").parents('.col-xl-3').find('.mini-stat-icon i').addClass('blink-icon');
                }

                if($.trim($(".ajax-external_inte_order").html()) != json['totals']['external_inte_order']){
                    play_sound = true;
                }
                if($.trim($(".ajax-full_hold_action_count").html()) != json['totals']['full_hold_action_count']){
                    play_sound = true;
                }
                if($.trim($(".ajax-full_digital_orders").html()) != json['totals']['full_digital_orders']){
                    play_sound = true;
                }

                if($.trim($(".ajax-all_clicks_comm").html()) != json['totals']['full_all_clicks_comm']){
                    play_sound = true;
                    $(".ajax-all_clicks_comm").parents('.col-xl-3').find('.mini-stat-icon i').addClass('blink-icon');
                }
                if($.trim($(".ajax-action_count_action_amount").html()) != json['totals']['full_action_count_action_amount']){
                    play_sound = true;
                    $(".ajax-action_count_action_amount").parents('.col-xl-3').find('.mini-stat-icon i').addClass('blink-icon');
                }
                if($.trim($(".ajax-hold_action_count").html()) != json['totals']['hold_action_count']){
                    play_sound = true;
                }
                if($.trim($(".ajax-weekly_balance").html()) != json['totals']['full_weekly_balance']){
                    play_sound = true;
                }
                if($.trim($(".ajax-monthly_balance").html()) != json['totals']['full_monthly_balance']){
                    play_sound = true;
                }
                if($.trim($(".ajax-yearly_balance").html()) != json['totals']['full_yearly_balance']){
                    play_sound = true;
                }
                

                $(".ajax-total_balance").html(json['totals']['full_total_balance']);
                $(".ajax-all_clicks_comm").html(json['totals']['full_all_clicks_comm']);
                $(".ajax-action_count_action_amount").html(json['totals']['full_action_count_action_amount']);
                $(".ajax-user_count").html(json['totals']['user_count']);
                $(".ajax-hold_action_count").html(json['totals']['hold_action_count']);
                $(".ajax-hold_orders").html(json['totals']['full_hold_orders']);
                $(".ajax-weekly_balance").html(json['totals']['full_weekly_balance']);
                $(".ajax-monthly_balance").html(json['totals']['full_monthly_balance']);
                $(".ajax-yearly_balance").html(json['totals']['full_yearly_balance']);
                $(".ajax-_t_total_commission").html(json['totals']['_t_total_commission']);
                $(".ajax-_t_paid_comm").html(json['totals']['_t_paid_comm']);
                $(".ajax-_t_unpaid_comm").html(json['totals']['_t_unpaid_comm']);
                $(".ajax-_t_request_comm").html(json['totals']['_t_request_comm']);
                $(".ajax-external_inte_order").html(json['totals']['external_inte_order']);
                $(".ajax-full_digital_orders").html(json['totals']['full_digital_orders']);
                $(".ajax-full_hold_action_count").html(json['totals']['full_hold_action_count']);
                $(".ajax-all_commition").html(json['totals']['all_commition']);
                $(".ajax-total_sale_balance").html(json['totals']['total_sale_balance']);

                $(".ajax-vendor_order_count").html(json['totals']['vendor_order_count']);
                $(".ajax-vendor_wallet_unpaid_amount").html(json['totals']['vendor_wallet_unpaid_amount']);
                $(".ajax-vendor_wallet_on_hold_amount").html(json['totals']['vendor_wallet_on_hold_amount']);
                $(".ajax-vendor_wallet_accept_amount").html(json['totals']['vendor_wallet_accept_amount']);
                $(".ajax-vendor_wallet_request_sent_amount").html(json['totals']['vendor_wallet_request_sent_amount']);
                $(".ajax-total_sale_count").html(json['totals']['total_sale_count']);
                $(".ajax-vendor_order_count").html(json['totals']['vendor_order_count']);

            }
            $("#online_admin .count").html(json['online_count']['admin']['online']);
            $("#online_user .count").html(json['online_count']['user']['online']);
            if($.trim($(".ajax-notifications_count").html()) != json['notifications_count']){
                play_sound = true;
            }
            $(".ajax-notifications_count").html(json['notifications_count']);
            $(".server-last-update span").html(json['time'])
            loadChartData(json['chart'])
            load_userworldmap(json['userworldmap'])
            if(json['live_dashboard']['admin_data_load_interval'] > 2){
                ajax_interval = (json['live_dashboard']['admin_data_load_interval'] * 1000)
            }
            $('#integration-data .data-here').html(json['integration_data']['html']);
            renderIntegrationChart(json['integration_data']['chart'])
            $(".show-tabs").trigger('change');
            if(json['serverReq']){
                var e = "";
                var ii= 0;
                $.each(json['serverReq'],function(i,j){
                    e += '<p>'+ j +'</p>';
                    ii++;
                })
                if(e != ""){
                    if($(".server-errors p").length != ii){
                        play_sound = true;
                    }
                    e = "<div class='requirement-error'>" + e +"</div>";
                    $(".server-errors").html(e);
                    $(".server-status").html('<div class="server-error">Server Error <i class="fa fa-times-circle-o"></i></div>');
                }
                else {
                    $(".server-errors").html('');
                    $(".server-status").html('<div class="server-ok">Server Run OK <i class="fa fa-check-circle-o"></i></div>');
                }
            }
            var count = 0;
            if(json['live_window']){
                var notifications='';
                $.each(json['live_window'], function(i,j){
                    play_sound = true;
                    count++;
                    notifications += '<li>'+ j['title'] + '</li>';
                })
                $(".ajax-live_window").prepend(notifications)
                if($(".ajax-live_window li").length == 0){
                    $("#ajax-live_window .empty-data").show();
                    $("#ajax-live_window .ajax-live_window").hide();
                } else {
                    $("#ajax-live_window .empty-data").hide();
                    $("#ajax-live_window .ajax-live_window").show();
                }
            }
            $('.btn-count-notification .count-notifications').text(count);
            if(json['integration_logs']){
                $.each(json['integration_logs'], function(i,j){
                    last_id_integration_logs = last_id_integration_logs <= parseInt(j['id']) ? parseInt(j['id']) : last_id_integration_logs;
                    if(j['click_type'] == 'Action'){
                        if(show_popup && json['live_dashboard']['admin_action_status']){
                            show_tost("success","New Action", "New Action Click Done Just Now");
                        }
                    }
                })
            }
            if(json['integration_orders']){
                $.each(json['integration_orders'], function(i,j){
                    last_id_integration_orders = last_id_integration_orders <= parseInt(j['id']) ? parseInt(j['id']) : last_id_integration_orders;
                    if(show_popup && json['live_dashboard']['admin_integration_order_status']){
                        show_tost("success","New Integration Order","New Integration Order Place Just Now");
                    }
                })
            }
            if(json['newuser']){
                $.each(json['newuser'], function(i,j){
                    last_id_newuser = last_id_newuser <= parseInt(j['id']) ? parseInt(j['id']) : last_id_newuser;
                    if(show_popup && json['live_dashboard']['admin_affiliate_register_status']){
                        show_tost("success","New Affiliate Register","New Affiliate '"+ j['firstname'] +" "+ j['lastname'] +"' Register Just Now");
                    }
                })
            }
            var top_notifications = '';
            if(json['notifications']){
                $.each(json['notifications'], function(i,j){
                    top_notifications += '<a href="javascript:void(0)" onclick="shownofication('+ j['notification_id'] +',\'<?= base_url('admincontrol') ?>'+ j['notification_url'] + '\')" class="dropdown-item notify-item">\
                    <div class="notify-icon bg-primary"><i class="mdi mdi-cart-outline"></i></div>\
                    <p class="notify-details"><b>'+ j['notification_title'] +'</b><small class="text-muted">'+ j['notification_description'] +'</small></p>\
                    </a>';
                    if(j['notification_type'] == 'order'){
                        if(show_popup && json['live_dashboard']['admin_local_store_order_status']){
                            show_tost("success","New Local Store Order", j['notification_title'] + " Just Now");
                        }
                    }
                    last_id_notifications = last_id_notifications <= parseInt(j['notification_id']) ? parseInt(j['notification_id']) : last_id_notifications;
                })
            }
            $("#last_id_top_notifications").val(last_id_notifications);
            $('#allnotification').prepend(top_notifications);
            if(play_sound && json['sound_status'] == "1" && show_popup){
                playSound();
            }
        },
    })
}
$('select[name="filter_commission[year]"], select[name="filter_commission[month]"]').on('change', function(){
    getTotalCommision();
});
$('select[name="filter_integration[year]"], select[name="filter_integration[month]"]').on('change', function(){
    getTotalIntegration();
});
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
function getTotalCommision(){
    var year = $('select[name="filter_commission[year]"]').val();
    var month = $('select[name="filter_commission[month]"]').val();
    total_commision_filter_year = year;
    total_commision_filter_month = month;
    getDashboard(true , true);
}
function getTotalIntegration(){
    var integration_data_year = $('select[name="filter_integration[year]"]').val();
    var integration_data_month = $('select[name="filter_integration[month]"]').val();
    $.ajax({
        url: '<?= base_url('admincontrol/get_integartion_data') ?>',
        type: 'post',
        dataType:'json',
        data: {
            integration_data_year: integration_data_year,
            integration_data_month: integration_data_month,
            integration_data_selected : $("#integration-chart-type").val(),
        },
        success: function (json) {
            if(json){
                $('#integration-data .data-here').html(json['html']);
                renderIntegrationChart(json['chart']);
                $(".show-tabs").trigger('change');
            }
        }
    });
}
$(document).on('ready',function(){
});
</script>
<script>
    $(function() {
        $(".progress").on('each',function() {
            var value = $(this).attr('data-value');
            var left = $(this).find('.progress-left .progress-bar');
            var right = $(this).find('.progress-right .progress-bar');
            if (value > 0) {
                if (value <= 50) {
                    right.css('transform', 'rotate(' + percentageToDegrees(value) + 'deg)')
                } else {
                    right.css('transform', 'rotate(180deg)')
                    left.css('transform', 'rotate(180deg)')
                }
            }
        })
        function percentageToDegrees(percentage) {
            return percentage / 100 * 360
        }
    });
</script>