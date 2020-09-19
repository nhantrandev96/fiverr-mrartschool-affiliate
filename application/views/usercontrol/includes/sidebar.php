<?php	
	$db =& get_instance();
	$userdetails=$db->Product_model->userdetails('user',1);
	$store_setting =$db->Product_model->getSettings('store');
	$SiteSetting =$db->Product_model->getSettings('site');
	$refer_status =$db->Product_model->my_refer_status($userdetails['id']);

	$db->Product_model->ping($userdetails['id']);

    $vendor_setting = $db->Product_model->getSettings('vendor');
    $market_vendor = $db->Product_model->getSettings('market_vendor');

$csss = array(
	'sidebar_background_color'                   =>  array('type' => 'background', 'selectotr' => '.left.side-menu, .left.side-menu, #sidebar-menu, .left.side-menu, #sidebar-menu .custom-menu-link a'),
	'sidebar_menu_background_color'              =>  array('type' => 'background', 'selectotr' => '.left.side-menu #sidebar-menu li:not(.custom-menu-link) a'),
	'sidebar_menu_text_color'                    =>  array('type' => 'color', 'selectotr' => '.left.side-menu #sidebar-menu li:not(.custom-menu-link) a'),
	'sidebar_menu_bottom_links_background_color' =>  array('type' => 'background', 'selectotr' => '.left.side-menu #sidebar-menu .custom-menu-link a span'),
	'sidebar_menu_bottom_links_text_color'       =>  array('type' => 'color', 'selectotr' => '.left.side-menu #sidebar-menu .custom-menu-link a span'),
);
?>
<style type="text/css">
<?php 
$setting = $db->Product_model->getSettings('affiliateside');
foreach ($csss as $key => $d) {
    if(isset($setting[$key]) && $setting[$key] != ''){
        echo "\n{$d['selectotr']}{";
        echo "\t {$d['type']} : ".$setting[$key]. "!important;" ;
        echo "}";
    }
} 
?>
.reload-btn{
    border-radius: 50%;
    width: 25px;
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
 <!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu">
                <div class="sidebar-inner slimscrollleft">
                    <div id="sidebar-menu">
                        
		<?php 
            $logo = base_url($SiteSetting['logo'] ? 'assets/images/site/'.$SiteSetting['logo'] : 'assets/vertical/assets/images/no-logo-coming-soon.png');
        ?>
        <center><img style="max-width: 125px;margin-top:10px;" src="<?= $logo ?>" id="logo" class="img-fluid"></center>
        
			<ul>
				<li>
					<a href="<?php echo base_url(); ?>usercontrol/dashboard" class="waves-effect">
						<i class="mdi mdi-view-dashboard"></i>
						<span> <?= __('user.dashboard') ?></span>
					</a>
				</li>
			    <li><a href="<?php echo base_url();?>usercontrol/store_markettools/"><i class="mdi mdi-archive"></i><?= __('user.all_markettools') ?></a></li>
			    <?php if((int)$store_setting['status'] == 1){ ?>
			    	<li><a href="<?php echo base_url();?>usercontrol/listproduct/"><i class="mdi mdi-store"></i><?= __('user.products_list') ?></a></li>
			    <?php } ?>
		     	<li><a href="<?php echo base_url();?>usercontrol/store_orders/"><i class="mdi mdi-cart-outline"></i><?= __('user.my_all_orders') ?></a></li>
		     	<li><a href="<?php echo base_url();?>usercontrol/store_logs/"><i class="mdi mdi-trending-up"></i><?= __('user.my_all_logs') ?></a></li>


		     	<li class="has_sub">
					<a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-wallet"></i> <span> My Wallet </span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
					<ul class="list-unstyled">
	     				<li><a href="<?php echo base_url('usercontrol/mywallet/');?>"><?= __('user.transactions') ?></a></li>
	     				<li><a href="<?php echo base_url('usercontrol/wallet_requests_list/');?>"><?= __('user.usercontrol_wallet_requests_list') ?></a></li>
					</ul>
				</li>

		     	<li><a href="<?php echo base_url('/usercontrol/my_network'); ?>" class="waves-effect"><i class="mdi mdi-view-dashboard"></i><span> <?= __('user.page_title_my_network') ?></span></a></li>
		     	<li><a href="<?php echo base_url('/ReportController/user_reports'); ?>" class="waves-effect"><i class="mdi mdi-account-settings-variant"></i><span> <?= __('user.my_page_title_user_reports') ?></span></a></li>
				<li><a href="<?php echo base_url('usercontrol/contact-us');?>"><i class="mdi mdi-store"></i> <span>Contact Admin</span></a></li>

				<?php if((isset($userdetails['is_vendor']) && $userdetails['is_vendor']) && (int)$vendor_setting['storestatus'] == 1 && (int)$store_setting['status'] == 1){ ?>
					<li class="has_sub">
						<a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-store"></i> <span> Vendor Market Place </span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
						<ul class="list-unstyled">
							<li><a href="<?php echo base_url('usercontrol/store_products/');?>">My Products</a></li>
							<li><a href="<?php echo base_url('usercontrol/store_coupon/');?>">Product Coupon</a></li>
							<li><a href="<?php echo base_url('usercontrol/store_setting/');?>">Store Setting</a></li>
						</ul>
					</li>
				<?php } ?>

				<?php if((isset($userdetails['is_vendor']) && $userdetails['is_vendor']) && (int)$market_vendor['marketvendorstatus'] == 1){ ?>
					<li class="has_sub">
						<a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-store"></i> <span> Vendor Market Tools </span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
						<ul class="list-unstyled">
							<li><a href="<?php echo base_url('usercontrol/programs/');?>">My Marketing Programs</a></li>
							<li><a href="<?php echo base_url('usercontrol/integration_tools/');?>">My Marketing Ads</a></li>
							<li><a href="<?php echo base_url('usercontrol/integration/');?>">Website Integrations</a></li>
							<!-- <li><a href="<?php echo base_url('usercontrol/integration_mywallet/');?>"><?= __('user.usercontrol_integration_mywallet') ?></a></li>
							<li><a href="<?php echo base_url('usercontrol/integration_orders/');?>"><?= __('user.usercontrol_integration_orders') ?></a></li> -->
						</ul>
					</li>
				<?php } ?>

			</ul>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
    <!-- Start right Content here -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <!-- ==================
                         PAGE CONTENT START
                         ================== -->
                    <div class="page-content-wrapper">
                        <div class="container-fluid">
                        	<?php 
                        		require APPPATH."config/breadcrumb.php";
                        		$pageKey = $db->Product_model->page_id();
                        	?>
                        	<script type="text/javascript">console.log('Page ID : <?= $pageKey ?>')</script>
                        	<?php if(isset($pageSetting[$pageKey])){ ?>
	                            <div class="row">
	                                <div class="col-sm-12">
	                                    <div class="page-title-box shadow-sm">
	                                        <div class="float-right">
	                                            <ol class="breadcrumb hide-phone p-0 m-0">
	                                            	<?php 
	                                            		$count = count($pageSetting[$pageKey]['breadcrumb']); 
	                                            		foreach ($pageSetting[$pageKey]['breadcrumb'] as $key => $value) { 
                                            		?>
	                                                	<li class="breadcrumb-item <?= $count == $key ? 'active' : '' ?>">
	                                                		<a href="<?= $value['link'] ?>"><?= $value['title'] ?></a>
	                                                	</li>
	                                            	<?php } ?>
	                                            </ol>
	                                        </div>
	                                        <div class="iconify float-left">
                                          		<a class="btn btn-primary btn-sm reload-btn" title="Refresh Page" data-toggle='tooltip' href="JavaScript: location.reload(true);"><i class="mdi mdi-refresh" data-inline="false" style="font-size: 1.1rem"></i></a>
	                                        </div>
	                                        <h4 class="page-title pl-2"><?= $pageSetting[$pageKey]['title'] ?></h4>
	                                    </div>
	                                </div>
	                            </div>
                        	<?php } ?>	
                        </div>
                    </div>