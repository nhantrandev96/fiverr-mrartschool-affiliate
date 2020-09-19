<?php
	$db =& get_instance(); 
	$products = $db->Product_model; 
	$userdetails         = $products->userdetails('user'); 
	$notifications       = $products->getnotificationnew('user',$userdetails['id'],5);
	$notifications_count = $products->getnotificationnew_count('user',$userdetails['id']);
	$paymentlist         = $products->getPaymentWarning();
	$LanguageHtml        = $products->getLanguageHtml('usercontrol');
	$CurrencyHtml        = $products->getCurrencyHtml('usercontrol');	
	//$noti_order = $products->hold_noti(['user_id' => $userdetails['id']]);
?>
<?php
$csss = array(
    'header_background_color'       =>  array('type' => 'background', 'selectotr' => '.navbar-custom, .navbar-custom .button-menu-mobile'),
    'header_text_color'             =>  array('type' => 'color', 'selectotr' => '.navbar-custom ul li > a, .navbar-custom ul li > a > i, .navbar-custom .button-menu-mobile'),
    'header_language_text_color'    =>  array('type' => 'color', 'selectotr' => '.navbar-custom li.language > a'),
    'header_currency_text_color'    =>  array('type' => 'color', 'selectotr' => '.navbar-custom li.currency > a'),
    'header_alert_background_color' =>  array('type' => 'background', 'selectotr' => '.alert-icon'),
    'header_alert_text_color'       =>  array('type' => 'color', 'selectotr' => '.alert-icon > a > i'),
    'header_menu_dropdown_background_color'  =>  array('type' => 'background', 'selectotr' => '.user-menu .profile-dropdown'),
    'header_menu_background_color'  =>  array('type' => 'background', 'selectotr' => '.user-menu .profile-dropdown > a'),
    'header_menu_text_color'        =>  array('type' => 'color', 'selectotr' => '.user-menu .profile-dropdown > a, .user-menu .profile-dropdown > a > i'),
    'top_left_background_color'     =>  array('type' => 'background', 'selectotr' => '.topbar-left'),
    'top_left_text_color'           =>  array('type' => 'color', 'selectotr' => '.topbar-left > a'),
);
?>
<style type="text/css">
<?php 
$setting = $products->getSettings('affiliateside');
foreach ($csss as $key => $d) {
    if(isset($setting[$key]) && $setting[$key] != ''){
        echo "\n{$d['selectotr']}{";
        echo "\t {$d['type']} : ".$setting[$key]. "!important;" ;
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
                        <a href="<?php echo base_url(); ?>usercontrol/dashboard" class="logo text-center"><?php if($setting['top_left_text']) { echo $setting['top_left_text']; }else { ?><?= __('user.affiliate_panel') ?><?php } ?></a>
                    </div>
                </div>
                
                
<!--<div class="topbar">-->
	<nav class="navbar-custom">
		<ul class="list-inline float-right mb-0">
			<!-- language-->
			<li class="list-inline-item dropdown notification-list language"><?= $LanguageHtml ?></li>
			<li class="list-inline-item dropdown notification-list currency"><?= $CurrencyHtml ?></li>
		
			<!-- <li class="list-inline-item dropdown notification-list order-noti">
				<a class="nav-link text-white" href="<?= base_url('usercontrol/mywallet') ?>" role="button" data-toggle="tooltip" data-original-title="Hold Orders">
					<i class="ti-bell noti-icon"></i>
					<span class="badge badge-danger noti-icon-badge"><?= (int)($noti_order['store_hold_orders'] + $noti_order['integration_hold_orders']) ?></span>				
				</a>
			</li> -->

			<li class="list-inline-item dropdown notification-list alert-icon">
				<a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
				aria-haspopup="false" aria-expanded="false">
					<i class="ti-bell noti-icon"></i>
					<span class="badge badge-success noti-icon-badge userside" id="notynew"><?php echo $notifications_count; ?></span>
				</a>
				<div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg">
					
					<div class="dropdown-item noti-title">
						<h5><span class="badge badge-danger float-right" id="notyall"><?php echo $notifications_count; ?></span><?= __('user.notification') ?></h5>
					</div>
					
					<div id="allnotification">
                        <?php foreach ($notifications as $key => $notification) { ?>
                            <a href="javascript:void(0)" onclick="shownofication(<?php echo $notification['notification_id'] . ',\'' . base_url('usercontrol') . $notification['notification_url'] . '\''; ?>)" class="dropdown-item notify-item">
                                <div class="notify-icon bg-primary"><i class="mdi mdi-cart-outline"></i></div>
                                <p class="notify-details"><b><?php echo $notification['notification_title']; ?></b><small class="text-muted"><?php echo $notification['notification_description']; ?></small></p>
                            </a>
                        <?php } ?>
					</div>
					<a href="<?php echo base_url('usercontrol/notification') ?>" class="dropdown-item notify-item">
						<?= __('user.view_all') ?>
					</a>
				</div>
			</li>
			
			<li class="list-inline-item dropdown notification-list user-menu">
				<a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
				aria-haspopup="false" aria-expanded="false">
					<?php if(!empty($userdetails['avatar'])) { ?>
						<img class="rounded-circle" src="<?php echo base_url(); ?>assets/images/users/thumb/<?php echo $userdetails['avatar'];?>" alt="">
						<?php } else { ?>
						<img class="rounded-circle" src="<?php echo base_url(); ?>assets/vertical/assets/images/users/avatar-1.jpg" alt="user" width="40">
					<?php } ?>
				</a>
				<div class="dropdown-menu dropdown-menu-right profile-dropdown " style="width: 200px">
					<!-- item-->
					<a class="dropdown-item" href="<?php echo base_url();?>usercontrol/editProfile"><i class="mdi mdi-account-circle m-r-5 text-muted"></i> <?= __('user.profile') ?></a>
					<a class="dropdown-item" href="<?php echo base_url();?>usercontrol/changePassword"><i class="mdi mdi-wallet m-r-5 text-muted"></i> <?= __('user.change_password') ?></a>
					<a class="dropdown-item" href="<?php echo base_url();?>usercontrol/mywallet"><i class="mdi mdi-wallet m-r-5 text-muted"></i> <?= __('user.my_wallet') ?></a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="<?php echo base_url();?>usercontrol/logout"><i class="mdi mdi-logout m-r-5 text-muted"></i> <?= __('user.logout') ?></a>
				</div>
			</li>
			
		</ul>
		
		<ul class="list-inline menu-left mb-0">
			<li class="float-left">
				<button class="button-menu-mobile open-left waves-light waves-effect">
					<i class="mdi mdi-menu"></i>
				</button>
			</li>
		</ul>
		<div class="clearfix"></div>
	</nav>
</div>
