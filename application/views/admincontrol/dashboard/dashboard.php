<?php
    $db =& get_instance();
    $userdetails = get_object_vars($db->user_info());
    //$store_setting =$db->Product_model->getSettings('store');
    $products = $db->Product_model;
    $notifications_count = $products->getnotificationnew_count('admin',null);
?>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/flag/css/main.min.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/plugins/table/datatables.min.css") ?>">
<script type="text/javascript" src="<?= base_url("assets/plugins/table/datatables.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/plugins/table/dataTables.responsive.min.js") ?>"></script>

<style>
    .counter{
        font-size: 1.7vw;
        font-weight: bold;
    }
    .counter-card {
        width: 100%;
        height: 100%;
    }
    .counter-card .card-body{
        display: flex;
        align-items: center;
    }
    @media(max-width: 1200px){
        .counter{
            font-size: 1.3rem;
        }
    }
</style>

<div class="row mb-4">
    <div class="col-xl-2 d-flex align-items-center mb-2 mb-lg-0">
        <div class="card border shadow-sm counter-card">
            <div class="card-body p-4">
                <div class="text-left">
                    <ul class="list-inline row mb-0 clearfix">
                        <li class="col-12">
                            <p class="mb-0 text-secondary">Admin Balance</p>
                            <p class="m-b-5 font-18 font-500 counter text-primary ajax-admin_balance"><?= c_format($admin_totals['admin_balance']) ?></p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 d-flex align-items-center mb-2 mb-lg-0">
        <div class="card border shadow-sm counter-card">
            <div class="card-body p-4">
                <div class="text-left">
                    <ul class="list-inline row mb-0 clearfix">
                        <li class="col-12">
                            <p class="mb-0 text-muted">Admin Actions</p>
                            <p class="m-b-5 font-18 font-500 counter text-primary">
                                <span class="ajax-click_action_total"><?= (int)$admin_totals['click_action_total'] ?></span> / 
                                <span class="ajax-click_action_commission"><?= c_format($admin_totals['click_action_commission']) ?></span>        
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 d-flex align-items-center mb-2 mb-lg-0">
        <div class="card border shadow-sm counter-card">
            <div class="card-body p-4">
                <div class="text-left">
                    <ul class="list-inline row mb-0 clearfix">
                        <li class="col-12">
                            <p class="mb-0 text-muted">All Clicks</p>
                            <p class="m-b-5 font-18 font-500 counter text-primary">
                                <span class="ajax-all_click_total">
                                    <?= (int)(
                                        $admin_totals['click_localstore_total'] +
                                        $admin_totals['click_integration_total'] +
                                        $admin_totals['click_form_total'] 
                                    ) ?>
                                </span> /
                                <span class="ajax-all_click_commission">
                                    <?= c_format(
                                        $admin_totals['click_localstore_commission'] +
                                        $admin_totals['click_integration_commission'] +
                                        $admin_totals['click_form_commission']
                                    ) ?>
                                </span>
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 mb-2 mb-lg-0">
        <div class="card border shadow-sm h-100">
            <div class="card-header bg-transparent border-0 pb-0">
                <span class="d-none bg-success m-0 mini-stat-icon pull-left"><i class="fa fa-bell"></i></span>
                <h6 class='card-title text-center text-uppercase text-dark m-0 font-weight-bold'>Total Sales</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <ul class="list-inline row mb-0 clearfix">
                        <li class="col-6">
                            <p class="m-b-5 font-18 font-500 counter text-primary ajax-sale_total_admin_store"><?= c_format($admin_totals['sale_localstore_total'] + $admin_totals['order_external_total']) ?></p>
                            <p class="mb-0 text-muted">Admin Store</p>
                        </li>
                        <li class="col-6 border-left">
                            <p class="m-b-5 font-18 font-500 counter text-primary ajax-sale_localstore_vendor_total"><?= c_format($admin_totals['sale_localstore_vendor_total']) ?></p>
                            <p class="mb-0 text-muted">Vendor Store</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 mb-2 mb-lg-0">
        <div class="card border shadow-sm h-100">
            <div class="card-header bg-transparent  border-0 pb-0">
                <span class="d-none bg-success m-0 mini-stat-icon pull-left"><i class="fa fa-bell"></i></span>
                <h6 class='card-title text-center text-uppercase text-dark m-0 font-weight-bold'>Total Online</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <ul class="list-inline row mb-0 clearfix">
                        <li class="col-4">
                            <p class="m-b-5 font-18 font-500 counter text-primary ajax-online-admin"><?= (int)$online_count['admin']['online'] ?></p>
                            <p class="mb-0 text-muted">Admin</p>
                        </li>
                        <li class="col-4 border-left">
                            <p class="m-b-5 font-18 font-500 counter text-primary ajax-online-affiliate"><?= (int)$online_count['user']['online'] ?></p>
                            <p class="mb-0 text-muted">Affiliate</p>
                        </li>
                        <li class="col-4 border-left">
                            <p class="m-b-5 font-18 font-500 counter text-primary ajax-online-client"><?= (int)$online_count['client']['online'] ?></p>
                            <p class="mb-0 text-muted">Client</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-7">
        <div class="new-card shadow-sm">
            <div class="card-header border-0">
                <h5 class="card-title"><?php echo __('admin.admin_overview') ?></h5>
                <div class="card-options">
                    <select onchange="loadDashboardChart()" class="renderChart chart-input form-control mr-1" name="group">
                        <option value="day" ><?= __('admin.day') ?></option>
                        <option value="week"><?= __('admin.week') ?></option>
                        <option value="month" selected=""><?= __('admin.month') ?></option>
                        <option value="year"><?= __('admin.year') ?></option>
                    </select>

                    <select onchange="loadDashboardChart()" class="yearSelection chart-input form-control" name='year'>
                        <?php for($i=2016; $i<= date("Y"); $i++){ ?>
                            <option value="<?= $i ?>" <?php echo $i==date("Y") ? "selected='selected'" : '' ?>><?= $i ?></option>
                        <?php  } ?>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <ul class="list-inline widget-chart m-t-20 m-b-15 text-center">
                    <li class="list-inline-item d-block d-sm-inline-block m-auto">
                        <i class="mdi mdi-arrow-up-bold-circle-outline text-success"></i>
                        <h5 class="mb-0 ajax-weekly_balance"><?= $admin_totals_week ?></h5>
                        <p class="font-14"><?php echo __('admin.weekly_earnings') ?></p>
                    </li>
                    <li class="list-inline-item d-block d-sm-inline-block m-auto">
                        <i class="mdi mdi-arrow-down-bold-circle-outline text-danger"></i>
                        <h5 class="mb-0 ajax-monthly_balance"><?= $admin_totals_month ?></h5>
                        <p class="font-14"><?php echo __('admin.monthly_earnings') ?></p>
                    </li>
                    <li class="list-inline-item d-block d-sm-inline-block m-auto">
                        <i class="mdi mdi-arrow-up-bold-circle-outline text-success"></i>
                        <h5 class="mb-0 ajax-yearly_balance"><?= $admin_totals_year ?></h5>
                        <p class="font-14"><?php echo __('admin.yearly_earnings') ?></p>
                    </li>
                </ul>

                <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
                <canvas height="340px" id="dashboard-chart" class="ct-chart ct-golden-section"></canvas>
                <div id="dashboard-chart-empty" class="ct-chart d-none ct-golden-section">
                    <div class="text-center">
                        <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:10px;">
                        <h3 class="m-t-40 text-center"><?= __('admin.not_activity_yet') ?></h3>
                    </div>
                </div>

                <script type="text/javascript">
                    var ctx = document.getElementById('dashboard-chart').getContext('2d');
                    var chartData = <?= json_encode($chart) ?>;

                    var chart = new Chart(ctx, {
                        type: 'bar',
                        data: {},
                        options: {
                            showLines: true,
                            tooltips: {
                                mode: 'index',
                                intersect: false
                            },
                            scales: {
                                /*xAxes: [{
                                    stacked: true,
                                }],
                                yAxes: [{
                                    stacked: true
                                }]*/
                            },
                            responsive: true,
                        }
                    });

                    function renderDashboardChart(chartData){
                        var t = ctx.createLinearGradient(0, 0, 0, 150);
                        t.addColorStop(0, Chart.helpers.color("#fd397a").alpha(.3).rgbString());
                        t.addColorStop(1, Chart.helpers.color("#fd397a").alpha(0).rgbString());

                        var g = ctx.createLinearGradient(0, 0, 0, 150);
                        g.addColorStop(0, Chart.helpers.color("#1dc9b7").alpha(.3).rgbString());
                        g.addColorStop(1, Chart.helpers.color("#1dc9b7").alpha(0).rgbString());
                        
                        chart.data = {
                            labels: Object.values(chartData['keys']),
                            datasets: [
                                {
                                    label: 'Action Count',
                                    backgroundColor: 'rgb(54, 162, 235)',
                                    borderColor: 'rgb(54, 162, 235)',
                                    data: Object.values(chartData['action_count']),
                                },
                                {

                                    label: 'Order Count',
                                    backgroundColor: 'rgb(255, 205, 86)',
                                    borderColor: 'rgb(255, 205, 86)',
                                    data: Object.values(chartData['order_count']),
                                },
                                {
                                    type: "line",
                                    borderWidth:2,
                                    label: 'Order Commission',
                                    backgroundColor: g,
                                    borderColor: "#1dc9b7",
                                    data: Object.values(chartData['order_commission']),
                                },
                                {
                                    label: 'Action Commission',
                                    backgroundColor: 'rgb(75, 192, 192)',
                                    borderColor: 'rgb(75, 192, 192)',
                                    data: Object.values(chartData['action_commission']),
                                },
                                
                                {
                                    type: "line",
                                    label: 'Order total',
                                    backgroundColor: t,
                                    borderColor: "#fd397a",
                                    borderWidth:2,
                                    data: Object.values(chartData['order_total']),
                                },
                            ]
                        }

                        chart.update();
                    }

                    function loadDashboardChart(){
                        $.ajax({
                            url:'<?= base_url("admincontrol/dashboard?getChartData=1") ?>',
                            type:'POST',
                            dataType:'json',
                            data:$(".chart-input"),
                            beforeSend:function(){},
                            complete:function(){},
                            success:function(json){
                               renderDashboardChart(json['chart']);
                            },
                        })
                    }

                    loadDashboardChart()
                </script>
            </div>
        </div>

        <div class="new-card mt-4 shadow-sm">
            <div class="card-header border-0">
                <h3 class='card-title'><?= __("admin.affiliates_map") ?></h3>
                <div class="card-options"></div>
            </div>
            <div class="card-body">
                <script type="text/javascript" src="<?= base_url('assets/plugins/jmap/jquery-jvectormap-2.0.3.min.js') ?>"></script>
                <script type="text/javascript" src="<?= base_url('assets/plugins/jmap/jquery-jvectormap-world-mill.js') ?>"></script>
                <link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/jmap/css.css') ?>">
                <div class="world-map-users"></div>
                <script type="text/javascript">
                    function load_userworldmap(_data) {
                        $('.world-map-users').html('<div class="map"><div id="world-map-users" class="map-content"></div></div>');
                        var data = {};
                        $.each(_data,function(i,j){
                            data[j['code']] = j['total']; 
                        })

                        $('.world-map-users #world-map-users').vectorMap({
                            map: 'world_mill',
                            zoomButtons : 1,
                            zoomOnScroll: false,
                            panOnDrag: 1,
                            backgroundColor: 'transparent',
                            markerStyle: {
                                initial: {
                                    fill: '#9E9E9E',
                                    stroke: '#fff',
                                    "stroke-width": 1,
                                    r: 5
                                },
                            },
                            onRegionTipShow: function(e, el, code, f){
                                el.html(el.html() + (data[code] ? ': <small>' + data[code]+'</small>' : ''));
                            },
                            series: {
                                regions: [{
                                    values: data,
                                    scale: ['#dfd0f5', '#563D7C'],
                                    normalizeFunction: 'polynomial'
                                }]
                            },
                            regionStyle: {
                                initial: {
                                    fill: '#dfd0f5'
                                }
                            },
                            markers:false,
                        });
                    }
                    load_userworldmap(<?= json_encode($userworldmap) ?>)
                </script>
            </div>
        </div>

        <div class="new-card mt-4 shadow-sm">
            <div class="card-header border-0">
                <h3 class="card-title"><?= __('admin.website_integration_store') ?></h3>
                <div class="card-options">
                    <select name="filter_integration[year]" class="form-control mr-1">
                        <?php foreach ($years as $key => $value) { ?>
                            <option value="<?= $value ?>" <?php if(date('Y') == $value) { ?>selected="selected"<?php } ?>><?= $value ?></option>
                        <?php } ?>
                    </select>
                    <select name="filter_integration[month]" class="form-control">
                        <?php foreach ($months as $key => $value) { ?>
                            <option value="<?= $value ?>"><?= $value ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="card-body p-0">
                <div role="tabpanel" id="integration-data">
                    <div class="data-here">
                        <table class="table-striped table responsive">
                            <thead>
                                <tr>
                                    <th data-priority='1'>Website</th>
                                    <th><?= __( 'admin.total_balance' ) ?></th>
                                    <th><?= __( 'admin.total_sales' ) ?></th>
                                    <th><?= __( 'admin.clicks' ) ?></th>
                                    <th><?= __('admin.actions') ?></th>
                                    <th><?= __( 'admin.total_commission' ) ?></th>
                                    <th><?= __( 'admin.total_orders' ) ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?= $integration_data['html'] ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="integration-chart" class="ct-chart d-none ct-golden-section"></div>
                    <script type="text/javascript">
                        $("#integration-data").delegate(".show-tabs","change",function(){
                            $("#integration-data .tab-pane").hide();
                            $("#site-" + $("#integration-data option:selected").attr("data-id") ).show();
                        })

                        $("#integration-data table").dataTable({
                            "paging":   false,
                            "ordering": false,
                            "searching": false,
                            "info":     false
                        })
                    </script>
                </div>
            </div>
        </div>
        
        <div class="new-card mt-4 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="card-title"><?php echo __('admin.live_logs') ?></h5>
                <div class="card-options">
                    <button class="btn btn-outline-warning btn-sm mr-1 btn-count-notification" data-toggle="tooltip" title='All Notifications' data-key='live_log' data-type='admin'>
                        <span class="count-notifications"><?= $notifications_count ?></span>
                        <i class="fa fa-bell"></i>
                    </button>
                    <button class="btn btn-blue-grey btn-sm btn-setting" data-toggle="tooltip" title='Live Log Settings' data-key='live_log' data-type='admin'>
                        <i class="fa fa-gear"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0" id="ajax-live_window">
                <div class="text-center m-4 empty-data" style="display: <?= !empty($live_window) ? 'none' : 'block'; ?>">
                    <img class="img-responsive" src="<?php echo base_url("assets/vertical/assets/images/no-data-2.png"); ?>" style="margin-top:10px;">
                    <h3 class="m-t-40 text-center"><?= __('admin.not_activity_yet') ?></h3>
                </div>
                <table class="ajax-live_window no-wrap responsive table table-striped" style="display: <?= empty($live_window) ? 'none' : 'table'; ?>;width: 100%">
                    <thead>
                        <th>DATE</th>
                        <th>TIME</th>
                        <th data-priority='-1' class="all">LOG</th>
                    </thead>
                    <tbody>
                        <?php foreach ($live_window as $key => $value) { ?>
                            <?= $value['title'] ?>
                        <?php } ?>
                    </tbody>
                </table>

                <script type="text/javascript">
                    function aplyDatatableOnLiveLog() {
                        return $(".ajax-live_window").dataTable({
                            "paging"         : false,
                            "ordering"       : false,
                            "searching"      : false,
                            "info"           : false,
                            "scrollY"        : "300px",
                            "scrollCollapse" : true,
                        })
                    }
                    var dataTableLiveLog = aplyDatatableOnLiveLog();
                    
                </script>
            </div>
        </div>
    </div>
    <div class="col-xl-5">
        <?php 
            $top_user = isset($populer_users[0]) ? $populer_users[0] : false;
            if(isset($top_user)){
        ?>
        <div class="profile mb-3">
            <div class="profile__picture"><img src="<?= $products->getAvatar($top_user['avatar']) ?>" alt=""/></div>
            <div class="profile__header">
                <div class="profile__account">
                    <h4 class="profile__username text-uppercase"><?= $top_user['firstname'] ?> <?= $top_user['lastname'] ?></h4>
                </div>
            </div>
            <div class="profile__stats">
                <div class="profile__stat">
                    <div class="profile__value">
                        <i class="flag-sm m-auto d-inline-flex flag-sm-<?= strtoupper($top_user['sortname']) ?>"></i>
                        <div class="profile__key text-uppercase">Country</div>
                    </div>
                </div>
                <div class="profile__stat">
                    <div class="profile__value"><?= c_format($top_user['amount']) ?>
                        <div class="profile__key text-uppercase">Balance</div>
                    </div>
                </div>
                <div class="profile__stat">
                    <div class="profile__value"><?= c_format($top_user['all_commition']) ?>
                        <div class="profile__key text-uppercase">Commission</div>
                    </div>
                </div>
            </div>
        </div>
   
        <?php } ?>
        <div class="new-card pb-3 shadow-sm">
            <?php $share_url = base_url('register/' . base64_encode($userdetails['id'])); ?>
            <div class="card-body">
                <h6><?= __('admin.register_new_affiliate_account_link') ?></h6>
                <div class="form-group">
                    <div class="copy-input input-group">
                        <input type="text" readonly="readonly" value="<?= $share_url ?>" class="form-control" id="unique_re_link">
                        <button copyToClipboard="<?= $share_url ?>" class="input-group-addon"></button>
                    </div>
                </div>

                <div class="store-link"></div>
                
                <script type="text/javascript">
                    $(document).on('ready',function(){
                        $(".store-link").jsSocials({
                            url: "<?= $share_url ?>",
                            showCount: false,
                            showLabel: false,
                            shareIn: "popup",
                            shares: ["email", "twitter", "facebook", "linkedin", "whatsapp"]
                        });
                    })
                </script>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-sm-6">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <span class="d-none bg-success m-0 mini-stat-icon pull-left"><i class="fa fa-bell"></i></span>
                        <h6 class='card-title m-0'>All Clicks</h6>
                    </div>
                    
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <span>LocalStore</span>
                            <span class="text-right pull-right text-primary font-weight-bold">
                                <span class="ajax-click_localstore_total"><?= (int)$admin_totals['click_localstore_total'] ?></span> / 
                                <span class="ajax-click_localstore_commission"><?= c_format($admin_totals['click_localstore_commission']) ?></span>
                            </span>  
                        </li>
                        <li class="list-group-item">
                            <span>External</span>
                            <span class="text-right pull-right text-primary font-weight-bold">
                                <span class="ajax-click_integration_total"><?= (int)$admin_totals['click_integration_total'] ?></span> / 
                                <span class="ajax-click_integration_commission"><?= c_format($admin_totals['click_integration_commission']) ?></span>        
                            </span>  
                        </li>
                        <li class="list-group-item">
                            <span>Forms</span>
                            <span class="text-right pull-right text-primary font-weight-bold">
                                <span class="ajax-click_form_total"><?= (int)$admin_totals['click_form_total'] ?></span> / 
                                <span class="ajax-click_form_commission"><?= c_format($admin_totals['click_form_commission']) ?></span>
                            </span>  
                        </li>
                    </ul>
                    <div class="card-body">
                        <footer class="blockquote-footer font-14">
                            All Clicks 
                            <cite title="">
                                <span class="ajax-click_all_total"><?= (int)(
                                    $admin_totals['click_localstore_total'] +
                                    $admin_totals['click_integration_total'] +
                                    $admin_totals['click_form_total'] 
                                ) ?></span> /
                                <span class="click_all_commission"><?= c_format(
                                    $admin_totals['click_localstore_commission'] +
                                    $admin_totals['click_integration_commission'] +
                                    $admin_totals['click_form_commission']
                                ) ?></span>
                            </cite>
                        </footer>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <span class="d-none bg-success m-0 mini-stat-icon pull-left"><i class="fa fa-bell"></i></span>
                        <h6 class='card-title m-0'>Order Commission</h6>
                    </div>
                    
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <span>LocalStore</span>
                            <span class="text-right pull-right text-primary font-weight-bold">
                                <span class="ajax-sale_localstore_count"><?= (int)$admin_totals['sale_localstore_count'] ?></span> / 
                                <span class="ajax-sale_localstore_commission"><?= c_format($admin_totals['sale_localstore_commission']) ?></span>
                            </span>  
                        </li>
                        <li class="list-group-item">
                            <span>Vendor</span>
                            <span class="text-right pull-right text-primary font-weight-bold">
                                <span class="ajax-sale_localstore_vendor_count"><?= (int)$admin_totals['sale_localstore_vendor_count'] ?></span> / 
                                <span class="ajax-sale_localstore_vendor_commission"><?= c_format($admin_totals['sale_localstore_vendor_commission']) ?></span>
                            </span>  
                        </li>

                        <li class="list-group-item">
                            <span>External</span>
                            <span class="text-right pull-right text-primary font-weight-bold">
                                <span class="ajax-order_external_count"><?= (int)$admin_totals['order_external_count'] ?></span> / 
                                <span class="ajax-order_external_commission"><?= c_format($admin_totals['order_external_commission']) ?></span>
                            </span>
                        </li>

                    </ul>
                    <div class="card-body">
                        <footer class="blockquote-footer font-14">
                            All Orders 
                            <cite title="">
                                <span class="ajax-all_sale_count"><?= (int)(
                                    $admin_totals['sale_localstore_count'] +
                                    $admin_totals['order_external_count'] +
                                    $admin_totals['sale_localstore_vendor_count']

                                ) ?></span> /
                                <span class="ajax-all_sale_commission"><?= c_format(
                                    $admin_totals['sale_localstore_commission'] +
                                    $admin_totals['order_external_commission'] +
                                    $admin_totals['sale_localstore_vendor_commission']

                                ) ?></span>
                            </cite>
                        </footer>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-0">
                        <span class="d-none bg-success m-0 mini-stat-icon pull-left"><i class="fa fa-bell"></i></span>
                        <h6 class='card-title m-0'>Wallet Statistics</h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <span>Hold</span>
                            <span class="text-right pull-right text-primary font-weight-bold">
                                <span class="ajax-wallet_unpaid_amounton_hold_count"><?= (int)$admin_totals['wallet_unpaid_amounton_hold_count'] ?></span> / 
                                <span class="ajax-wallet_on_hold_amount"><?= c_format($admin_totals['wallet_on_hold_amount']) ?></span>
                            </span>  
                        </li>
                        <li class="list-group-item">
                            <span>Unpaid</span>
                            <span class="text-right pull-right text-primary font-weight-bold">
                                <span class='ajax-wallet_unpaid_count'><?= (int)$admin_totals['wallet_unpaid_count'] ?></span> / 
                                <span class='ajax-wallet_unpaid_amount'><?= c_format($admin_totals['wallet_unpaid_amount']) ?></span>
                            </span>  
                        </li>
                        <li class="list-group-item">
                            <span>Request</span>
                            <span class="text-right pull-right text-primary font-weight-bold">
                                <span class="ajax-wallet_request_sent_count"><?= (int)$admin_totals['wallet_request_sent_count'] ?></span> / 
                                <span class="ajax-wallet_request_sent_amount"><?= c_format($admin_totals['wallet_request_sent_amount']) ?></span>
                            </span>  
                        </li>
                        <li class="list-group-item">
                            <span>Paid</span>
                            <span class="text-right pull-right text-primary font-weight-bold">
                                <span class="ajax-wallet_accept_count"><?= (int)$admin_totals['wallet_accept_count'] ?></span> / 
                                <span class="ajax-wallet_accept_amount"><?= c_format($admin_totals['wallet_accept_amount']) ?></span>
                            </span>  
                        </li>
                        <li class="list-group-item">
                            <span>Cancel</span>
                            <span class="text-right pull-right text-primary font-weight-bold">
                                <span class="ajax-wallet_cancel_count"><?= (int)$admin_totals['wallet_cancel_count'] ?></span> / 
                                <span class="ajax-wallet_cancel_amount"><?= c_format($admin_totals['wallet_cancel_amount']) ?></span>
                            </span>  
                        </li>
                    </ul>

                    <div class="card-body">
                        <footer class="blockquote-footer font-14">
                            Check All Transactions <cite title="Source Title"><a href="<?= base_url('/admincontrol/mywallet/') ?>">Click Here</a></cite>
                        </footer>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-0">
                        <span class="d-none bg-success m-0 mini-stat-icon pull-left"><i class="fa fa-bell"></i></span>
                        <h6 class='card-title m-0'>Vendor Order Statistics</h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <span>Paid</span>
                            <span class="text-right pull-right text-primary font-weight-bold">
                                <span class="ajax-vendor_wallet_accept_count"><?= (int)$admin_totals['vendor_wallet_accept_count'] ?></span> / 
                                <span class="ajax-vendor_wallet_accept_amount"><?= c_format($admin_totals['vendor_wallet_accept_amount']) ?></span>
                            </span>  
                        </li>
                        <li class="list-group-item">
                            <span>Request</span>
                            <span class="text-right pull-right text-primary font-weight-bold">
                                <span class="ajax-vendor_wallet_request_sent_count"><?= (int)$admin_totals['vendor_wallet_request_sent_count'] ?></span> / 
                                <span class="ajax-vendor_wallet_request_sent_amount"><?= c_format($admin_totals['vendor_wallet_request_sent_amount']) ?></span>
                            </span>  
                        </li>
                        <li class="list-group-item">
                            <span>Unpaid</span>
                            <span class="text-right pull-right text-primary font-weight-bold">
                                <span class="ajax-vendor_wallet_unpaid_count"><?= (int)$admin_totals['vendor_wallet_unpaid_count'] ?></span> / 
                                <span class="ajax-vendor_wallet_unpaid_amount"><?= c_format($admin_totals['vendor_wallet_unpaid_amount']) ?></span>
                            </span>  
                        </li>
                        
                        
                        <li class="list-group-item">
                            <span>Total Orders</span>
                            <span class="text-right pull-right text-primary font-weight-bold ajax-order_vendor_total"><?= (int)$admin_totals['order_vendor_total'] ?></span>  
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="new-card mt-4">
            <div class="card-header border-0">
                <h5 class="card-title"><?= __('admin.popular_affiliates') ?></h5>
            </div>
            <div class="card-body p-0">
                <div role="tabpanel" id="top-users-data">
                    <div class="data-here">
                        <table class="table-striped table responsive no-wrap">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Country</th>
                                    <th>Balance</th>
                                    <th>Commission</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($populer_users as $key => $users) { ?>
                                    <tr>
                                        <?php
                                            $flag = '';
                                            if ($users['sortname'] != '') {
                                                $flag = base_url('assets/vertical/assets/images/flags/' . strtolower($users['sortname']) . '.png');
                                            }
                                        ?>
                                        <td><span class="user-avatar" style="background-image:url('<?= $products->getAvatar($users['avatar']) ?>')"></span></td>
                                        <td><?php echo $users['firstname']; ?> <?php echo $users['lastname']; ?></td>
                                        <td><?= strtoupper($users['sortname']) ?> <img class="country-flag" src="<?php echo $flag; ?>"></td>
                                        <td><?php echo c_format($users['amount']); ?></td>
                                        <td><?php echo c_format($users['all_commition']); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <script type="text/javascript">
                        var dataTableUser = $("#top-users-data table").dataTable({
                            "paging":   false,
                            "ordering": false,
                            "searching": false,
                            "info":     false
                        })
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    $last_id_integration_logs = 0;
    $last_id_integration_orders = 0;
    $last_id_newuser = 0;
    $last_id_notifications = 0;
    foreach ($integration_logs as $key => $log){
        if($last_id_integration_logs <= $log['id']){ $last_id_integration_logs = $log['id']; }
    }
    foreach ($integration_orders as $key => $order) {
        if($last_id_integration_orders <= $order['id']){ $last_id_integration_orders = $order['id']; }
    }
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

    var checkdata = {
        '.ajax-admin_balance'                     : 'admin_balance',
        '.ajax-sale_total_admin_store'            : 'sale_total_admin_store',
        '.ajax-sale_localstore_vendor_total'      : 'sale_localstore_vendor_total',
        '.ajax-click_action_total'                : 'click_action_total',
        '.ajax-click_action_commission'           : 'click_action_commission',
        '.ajax-all_click_total'                   : 'all_click_total',
        '.ajax-all_click_commission'              : 'all_click_commission',
        '.ajax-click_localstore_total'            : 'click_localstore_total',
        '.ajax-click_localstore_commission'       : 'click_localstore_commission',
        '.ajax-click_integration_total'           : 'click_integration_total',
        '.ajax-click_integration_commission'      : 'click_integration_commission',
        '.ajax-click_form_total'                  : 'click_form_total',
        '.ajax-click_form_commission'             : 'click_form_commission',
        '.ajax-click_all_total'                   : 'click_all_total',
        '.ajax-click_all_commission'              : 'click_all_commission',
        '.ajax-sale_localstore_count'             : 'sale_localstore_count',
        '.ajax-sale_localstore_commission'        : 'sale_localstore_commission',
        '.ajax-sale_localstore_vendor_count'      : 'sale_localstore_vendor_count',
        '.ajax-sale_localstore_vendor_commission' : 'sale_localstore_vendor_commission',
        '.ajax-order_external_count'              : 'order_external_count',
        '.ajax-order_external_commission'         : 'order_external_commission',
        '.ajax-all_sale_count'                    : 'all_sale_count',
        '.ajax-all_sale_commission'               : 'all_sale_commission',
        '.ajax-wallet_unpaid_amounton_hold_count' : 'wallet_unpaid_amounton_hold_count',
        '.ajax-wallet_on_hold_amount'             : 'wallet_on_hold_amount',
        '.ajax-wallet_unpaid_count'               : 'wallet_unpaid_count',
        '.ajax-wallet_unpaid_amount'              : 'wallet_unpaid_amount',
        '.ajax-wallet_request_sent_count'         : 'wallet_request_sent_count',
        '.ajax-wallet_request_sent_amount'        : 'wallet_request_sent_amount',
        '.ajax-wallet_accept_count'               : 'wallet_accept_count',
        '.ajax-wallet_accept_amount'              : 'wallet_accept_amount',
        '.ajax-wallet_cancel_count'               : 'wallet_cancel_count',
        '.ajax-wallet_cancel_amount'              : 'wallet_cancel_amount',
        '.ajax-vendor_wallet_accept_count'        : 'vendor_wallet_accept_count',
        '.ajax-vendor_wallet_accept_amount'       : 'vendor_wallet_accept_amount',
        '.ajax-vendor_wallet_request_sent_count'  : 'vendor_wallet_request_sent_count',
        '.ajax-vendor_wallet_request_sent_amount' : 'vendor_wallet_request_sent_amount',
        '.ajax-vendor_wallet_unpaid_count'        : 'vendor_wallet_unpaid_count',
        '.ajax-vendor_wallet_unpaid_amount'       : 'vendor_wallet_unpaid_amount',
        '.ajax-order_vendor_total'                : 'order_vendor_total',
    }

    function setColors() {
        $.each(checkdata,function(ele,Key){
            if($(ele).length){
                var val =  parseInt($(ele).html().toString().replace(/[^0-9-.]/g, '') || 0);

                $(ele).removeClass("text-primary")
                $(ele).removeClass("text-danger")
                if(val >= 0){
                    $(ele).addClass("text-primary");
                } else{
                    $(ele).addClass("text-danger");
                }
            }
        })
    }

    setColors();

    function getDashboard(callnexttime,show_popup,actions){
        if(dashboard_xhr && dashboard_xhr.readyState != 4) dashboard_xhr.abort();

        if(actions == 'clearlog'){
            settings_clear = true;
            last_id_integration_logs = 0;
            last_id_integration_orders = 0;
            last_id_newuser = 0;
            last_id_notifications = 0;
            console.log(actions)
        }

        dashboard_xhr = $.ajax({
            url:'<?= base_url('admincontrol/ajax_dashboard') ?>',
            type:'POST',
            dataType:'json',
            data:{
                renderChart  : $(".renderChart").val(),
                selectedyear :$('.yearSelection').val(),
                last_id_integration_logs :last_id_integration_logs,
                last_id_integration_orders :last_id_integration_orders,
                last_id_newuser :last_id_newuser,
                last_id_notifications :last_id_notifications,
                last_id_top_notifications :$("#last_id_notifications").val(),
                total_commision_filter_year : $('select[name="filter_commission[year]"]').val(),
                total_commision_filter_month : $('select[name="filter_commission[month]"]').val(),
                integration_data_year : $('select[name="filter_integration[year]"]').val(),
                integration_data_month : $('select[name="filter_integration[month]"]').val(),
                integration_data_selected : $("#integration-chart-type").val(),
            },
            beforeSend:function(){},
            complete:function(){
                if(callnexttime){
                    setTimeout2(true,true);
                }
            },
            success:function(json){
                setTimeout(function(){
                    $('.ajax-live_window .fa-bell').removeClass('blink-icon');
                    $(".mini-stat-icon i").removeClass("blink-icon");
                }, 5000);

                var play_sound = false;
                

                $.each(checkdata,function(ele,Key){
                    if($.trim($(ele).html()) != json['admin_totals'][Key]){
                        play_sound = true;
                        $(ele).html(json['admin_totals'][Key]);
                    }
                })

                setColors();

                if(json['online_count']){
                    if (typeof json['online_count']['admin'] == 'object' && json['online_count']['admin']['online'] ) {
                        $(".ajax-online-admin").html( json['online_count']['admin']['online']);
                    }
                    if (typeof json['online_count']['user'] == 'object' && json['online_count']['user']['online'] ) {
                        $(".ajax-online-affiliate").html(json['online_count']['user']['online']);
                    }
                    if (typeof json['online_count']['client'] == 'object' && json['online_count']['client']['online'] ) {
                        $(".ajax-online-client").html(json['online_count']['client']['online']);
                    }
                }

                $(".ajax-weekly_balance").html(json['admin_totals_week']);
                $(".ajax-monthly_balance").html(json['admin_totals_month']);
                $(".ajax-yearly_balance").html(json['admin_totals_year']);

                renderDashboardChart(json['chart'])
                load_userworldmap(json['userworldmap'])
                
                if($.trim($(".ajax-notifications_count").html()) != json['notifications_count']){
                    play_sound = true;
                }

                if(json['newuser']){
                    $.each(json['newuser'], function(i,j){
                        last_id_newuser = last_id_newuser <= parseInt(j['id']) ? parseInt(j['id']) : last_id_newuser;
                        if(show_popup && json['live_dashboard']['admin_affiliate_register_status']){
                            show_tost("success","New Affiliate Register","New Affiliate '"+ j['firstname'] +" "+ j['lastname'] +"' Register Just Now");
                        }
                    })
                }

                var count = 0;
                if(json['live_window']){
                    var notifications='';
                    $.each(json['live_window'], function(i,j){
                        play_sound = true;
                        count++;
                        notifications += j['title'];
                    })
                    if (notifications) {
                        dataTableLiveLog.fnClearTable();
                        dataTableLiveLog.fnDestroy();

                        $(".ajax-live_window tbody").html(notifications)
                        dataTableLiveLog = aplyDatatableOnLiveLog()
                    }
                }
                
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

    setTimeout2(true,true)
</script>