<?php
$db = & get_instance();
$products = $db->Product_model;
$notifications = $products->getnotificationnew('admin', null, 5);
$notifications_count = $products->getnotificationnew_count('admin', null);
$userdetails = $db->userdetails();
$license = $products->getLicese();
$LanguageHtml = $products->getLanguageHtml();
$CurrencyHtml = $products->getCurrencyHtml();
$noti_order = $products->hold_noti();
$commonSetting = array(
    'site' => array('notify_email'),
    'store' => array('affiliate_cookie'),
    'email' => array('from_email'),
    'productsetting' => array('product_commission', 'product_ppc', 'product_noofpercommission'),
    'affiliateprogramsetting' => array('affiliate_commission', 'affiliate_ppc'),
    'paymentsetting' => array('api_username', 'api_password', 'api_signature'),
);
$allSettings = array();
foreach ($commonSetting as $key => $value) {
    $allSettings[$key] = $products->getSettings($key);
}
$required = '';
$validate = true;
foreach ($commonSetting as $key => $fields) {
    $data = $allSettings[$key];
    foreach ($fields as $field) {
        if (!isset($data[$field]) || $data[$field] == '') {
            $required .= "{$key} - {$field} \n";
            $validate = false;
        }
    }
}

$page_id = $products->page_id();
?>
<?php
$csss = array(
    'header_background_color' => array('type' => 'background', 'selectotr' => '.navbar-custom, .navbar-custom .button-menu-mobile'),
    'header_text_color' => array('type' => 'color', 'selectotr' => '.navbar-custom ul li > a, .navbar-custom ul li > a > i, .navbar-custom .button-menu-mobile'),
    'header_language_text_color' => array('type' => 'color', 'selectotr' => '.navbar-custom li.language > a'),
    'header_currency_text_color' => array('type' => 'color', 'selectotr' => '.navbar-custom li.currency > a'),
    'header_alert_background_color' => array('type' => 'background', 'selectotr' => '.alert-icon'),
    'header_alert_text_color' => array('type' => 'color', 'selectotr' => '.alert-icon > a > i'),
    'header_menu_dropdown_background_color' => array('type' => 'background', 'selectotr' => '.user-menu .profile-dropdown'),
    'header_menu_background_color' => array('type' => 'background', 'selectotr' => '.user-menu .profile-dropdown > a'),
    'header_menu_text_color' => array('type' => 'color', 'selectotr' => '.user-menu .profile-dropdown > a, .user-menu .profile-dropdown > a > i'),
    'top_left_background_color' => array('type' => 'background', 'selectotr' => '.topbar-left'),
    'top_left_text_color' => array('type' => 'color', 'selectotr' => '.topbar-left > a'),
);
?>
<style type="text/css">
<?php
$setting = $products->getSettings('adminside');
foreach ($csss as $key => $d) {
    if (isset($setting[$key]) && $setting[$key] != '') {
        echo "\n{$d['selectotr']}{";
        echo "\t {$d['type']} : " . $setting[$key] . "!important;";
        echo "}";
    }
}
?>
</style>


<!-- Top Bar Start -->
<div class="topbar">
    <!-- LOGO -->
    <div class="topbar-left">
        <div class="">
            <a href="<?php echo base_url(); ?>admincontrol/dashboard" class="logo text-center"><?php if ($setting['top_left_text']) {
    echo $setting['top_left_text'];
} else { ?><?= __('admin.menu_admin_panel') ?><?php } ?></a>
        </div>
    </div>

    <nav class="navbar-custom">
        <!-- Search input -->
        <div class="search-wrap" id="search-wrap">
            <div class="search-bar">
                <input class="search-input" type="search" placeholder="Search" />
                <a href="#" class="close-search toggle-search" data-target="#search-wrap">
                    <i class="mdi mdi-close-circle"></i>
                </a>
            </div>
        </div>

        <ul class="list-inline float-right mb-0">
            <!-- Search -->
            <!--<li class="list-inline-item dropdown notification-list">
                <a class="nav-link waves-effect toggle-search" href="#"  data-target="#search-wrap">
                    <i class="mdi mdi-magnify noti-icon"></i>
                </a>
            </li>-->
            <!-- Fullscreen -->
            <li class="list-inline-item dropdown notification-list hide-sm">
                <a class="nav-link waves-effect" href="#" id="btn-fullscreen">
                    <i class="mdi mdi-fullscreen noti-icon"></i>
                </a>
            </li>

            <!-- language-->
            <li class="list-inline-item dropdown notification-list hide-sm">
                <a class="nav-link dropdown-toggle arrow-none waves-effect text-muted" data-toggle="dropdown" href="#" role="button"
                   aria-haspopup="false" aria-expanded="false">
                    <li class="list-inline-item dropdown notification-list language"><?= $LanguageHtml ?></li>
                    <li class="list-inline-item dropdown notification-list currency"><?= $CurrencyHtml ?></li>
            </li>

            <li class="list-inline-item dropdown notification-list alert-icon">
<?php if ($notifications_count == null) { ?>
                    <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
                       aria-haspopup="false" aria-expanded="false">
                        <i class="d-block ion-ios7-bell noti-icon"></i>
                        <span class="badge badge-danger noti-icon-badge ajax-notifications_count" id="notynew"><?php echo $notifications_count; ?></span>
                    </a>

<?php } else { ?>
                    <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
                       aria-haspopup="false" aria-expanded="false">
                        <i class="d-block ion-ios7-bell noti-icon"></i>
                        <span class="badge badge-danger noti-icon-badge blink_me ajax-notifications_count" id="notynew"><?php echo $notifications_count; ?></span></a>
<?php } ?>

                <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg">
                    <!-- item-->
                    <div class="dropdown-item noti-title">
                        <h5>Notification<span class="badge badge-success float-right m-t-5"> (<?php echo $notifications_count; ?>)</span></h5>
                    </div>

                    <div id="allnotification">
                        <?php $last_id_notifications = 0; ?>
<?php foreach ($notifications as $key => $notification) { ?>
    <?php if ($last_id_notifications <= $notification['notification_id']) {
        $last_id_notifications = $notification['notification_id'];
    } ?>
                            <a href="javascript:void(0)" onclick="shownofication(<?php echo $notification['notification_id'] . ',\'' . base_url('admincontrol') . $notification['notification_url'] . '\''; ?>)" class="dropdown-item notify-item">
                                <div class="notify-icon bg-primary"><i class="mdi mdi-cart-outline"></i></div>
                                <p class="notify-details"><b><?php echo $notification['notification_title']; ?></b><small class="text-muted"><?php echo $notification['notification_description']; ?></small></p>
                            </a>
<?php } ?>

                        <input type="hidden" id="last_id_notifications" value="<?= $last_id_notifications ?>">
                    </div>
                    <a href="<?php echo base_url('admincontrol/notification') ?>" class="dropdown-item notify-item"><?= __('admin.common_view_all') ?></a>

                </div>
            </li>






            <!-- User-->
            <li class="list-inline-item dropdown notification-list user-menu">
                <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
                   aria-haspopup="false" aria-expanded="false">

                    <?php if (!empty($userdetails['avatar'])) { ?>
                        <img class="rounded-circle" src="<?php echo base_url(); ?>assets/images/users/thumb/<?php echo $userdetails['avatar']; ?>" alt="admin" width="65px">
<?php } else { ?>
                        <img class="rounded-circle" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-image.jpg" alt="admin" width="65px">
<?php } ?>
                </a>
                <div class="dropdown-menu profile-dropdown">
                    <a class="dropdown-item" href="<?php echo base_url(); ?>admincontrol/editProfile"><i class="dripicons-user text-muted"></i><?= __('admin.top_profile') ?></a>
                    <a class="dropdown-item" href="<?php echo base_url(); ?>admincontrol/changePassword"><i class="dripicons-wallet text-muted"></i><?= __('admin.top_change_password') ?></a>
                    <a class="dropdown-item" href="<?php echo base_url(); ?>admincontrol/mywallet"><i class="dripicons-wallet text-muted"></i><?= __('admin.top_my_wallet') ?></a>
                    <a class="dropdown-item" href="<?php echo base_url(); ?>admincontrol/paymentsetting"><i class="dripicons-lock text-muted"></i><?= __('admin.top_settings') ?></a>

                    <a class="dropdown-item" href="<?php echo base_url('/integration/programs/') ?>"><i class="dripicons-lock text-muted"></i>Create Programme</a>
                    <a class="dropdown-item" href="<?php echo base_url('/integration/integration_tools/') ?>"><i class="dripicons-lock text-muted"></i>Create Banner Ads</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo base_url(); ?>admincontrol/logout"><i class="dripicons-exit text-muted"></i> <?= __('admin.top_logout') ?></a>
                </div>
            </li>
        </ul>


        <!-- Menu Collapse Button -->

        <button type="button" class="button-menu-mobile open-left waves-effect">
            <i class="ion-navicon"></i>
        </button>
        <div class="clearfix"></div>
    </nav>

</div>
<!-- Top Bar End -->


















<!--<?php if (!$validate) { ?>
            <br><div class="alert alert-info <?php echo $required ?>">
    <?= __('admin.top_error') ?> <a href="<?php echo base_url('admincontrol/paymentsetting/') ?>"><?= __('admin.top_clickhere') ?></a>
            </div>
<?php } ?>-->


<?php
$status = $products->getSettingStatus();
?>

<?php if (isset($status['currency'])) { ?>

    <?php if (!in_array($page_id, array('admincontrol_currency_list', 'admincontrol_currency_edit', 'firstsetting_index'))) { ?>
        <script type="text/javascript">
            window.location = '<?= base_url('firstsetting') ?>';
        </script>
    <?php } ?>
    <br><div class="alert alert-danger">Set Default Currency</div>
<?php } ?>

<?php if (isset($status['language'])) { ?>
    <br><div class="alert alert-danger">Set Default Language</div>
<?php } ?>