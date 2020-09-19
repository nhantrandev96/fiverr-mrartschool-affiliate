<?php
	$db =& get_instance();
	$products = $db->Product_model;
	$store_setting =$db->Product_model->getSettings('store');
	$userdetails=$db->userdetails();
	$license = $products->getLicese();
 	$notifications = $products->getnotificationnew('admin',null,5);
    $notifications_count = $products->getnotificationnew_count('admin',null);
?>
<?php
$csss = array(
    'sidebar_background_color'                      =>  array('type' => 'background', 'selectotr' => '.left.side-menu, .left.side-menu, #sidebar-menu, .left.side-menu, #sidebar-menu .custom-menu-link a'),
    'sidebar_menu_background_color'                 =>  array('type' => 'background', 'selectotr' => '.left.side-menu #sidebar-menu li:not(.custom-menu-link) a'),
    'sidebar_menu_text_color'                       =>  array('type' => 'color', 'selectotr' => '.left.side-menu #sidebar-menu li:not(.custom-menu-link) a'),
    'sidebar_menu_bottom_links_background_color'    =>  array('type' => 'background', 'selectotr' => '.left.side-menu #sidebar-menu .custom-menu-link a span'),
    'sidebar_menu_bottom_links_text_color'          =>  array('type' => 'color', 'selectotr' => '.left.side-menu #sidebar-menu .custom-menu-link a span'),
);
?>
<style type="text/css">
<?php 
$setting = $products->getSettings('adminside');
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
.server-last-update span,
.dashboard-refresh span {
    background: rgba(164, 91, 207,0.2);
    border: solid 1px rgba(164, 91, 207,0.3);
    padding: 2px 7px;
}
</style>

            <div class="left side-menu">
                <div class="sidebar-inner slimscrollleft">
                    <div id="sidebar-menu">
                        <ul>
                             <li>
                                <a href="<?php echo base_url(); ?>admincontrol/dashboard" class="waves-effect"><i class="mdi mdi-view-dashboard"></i><span><?= __('admin.menu_dashboard') ?></a>
                            </li>
                           <li><a href="<?php echo base_url();?>" target="_blank" class="waves-effect"><i class="mdi mdi-web"></i><span> <?=__('admin.view_site') ?></a></li>
                            <li><a href="<?php echo base_url('firstsetting') ?>" class="waves-effect" >
                                <i class="mdi mdi-settings"></i>
                                <span><?= __('admin.menu_firstsetting') ?><span class="badge badge-danger float-right">i</span></a></li>

                            
                        <li class="has_sub">
					       <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-account"></i> <span><?= __('admin.activity') ?></span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
					       <ul class="list-unstyled">
					         <li><a href="<?php echo base_url();?>admincontrol/store_markettools/"><i class="mdi mdi-wallet"></i><?= __('admin.all_markettools') ?></a></li>
				     	    <li><a href="<?php echo base_url();?>admincontrol/store_orders/"><i class="mdi mdi-wallet"></i><?= __('admin.my_all_orders') ?></a></li>
				         	<li><a href="<?php echo base_url();?>admincontrol/store_logs/"><i class="mdi mdi-wallet"></i><?= __('admin.my_all_logs') ?></a></li>
					      </ul>
				        </li>

                        

                        <li class="has_sub">
					       <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-account"></i> <span><?= __('admin.menu_members') ?></span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
					       <ul class="list-unstyled">
					         <li><a href="<?php echo base_url();?>admincontrol/userslist/"><?= __('admin.menu_list_affiliates') ?></a></li>
					         <li><a href="<?php echo base_url();?>admincontrol/userslisttree/" class="waves-effect" ><span><?= __('admin.menu_referring_tree') ?></a></li>
					         <li><a href="<?php echo base_url();?>admincontrol/userslistmail/"><?= __('admin.menu_list_affiliates_email') ?></a></li>
					         <?php if($userdetails['id'] == 1){ ?>
                            	<li><a href="<?php echo base_url('admincontrol/admin_user');?>" class="waves-effect"></i><span> <?=__('admin.menu_manage_admin') ?></a></li>
                            <?php } ?>
					      </ul>
				        </li>
				        
				        
				<li class="has_sub">
					<a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-store"></i> <span><?= __('admin.menu_market_tools') ?></span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
					<ul class="list-unstyled">
					    <li><a href="<?php echo base_url();?>integration/programs"><?= __('admin.sub_menu_integration_programs') ?></a></li>
					    <li><a href="<?php echo base_url();?>integration/integration_category"><?= __('admin.integration_category') ?></a></li>
					    <li><a href="<?php echo base_url();?>integration/integration_tools"><?= __('admin.menu_affiliate_marketing') ?></a></li>
					    <li><a href="<?php echo base_url();?>integration/orders"><?= __('admin.sub_menu_integration_orders') ?></a></li>
					    <li><a href="<?php echo base_url();?>integration/logs"><?= __('admin.sub_menu_integration_logs') ?></a></li>
					    <li><a href="<?php echo base_url();?>admincontrol/market_tools_setting"><?= __('admin.admincontrol_market_tools_setting') ?></a></li>
					</ul>
				</li>
				
				<?php if($store_setting['status']) { ?>
				<li class="has_sub">
					<a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-store"></i> <span><?= __('admin.menu_my_store') ?></span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
					<ul class="list-unstyled">
					    <li><a href="<?php echo base_url();?>admincontrol/store_dashboard/"><?= __('admin.page_title_store_dashboard') ?></a></li>
					    <li><a href="<?php echo base_url();?>admincontrol/listproduct/"><?= __('admin.menu_list_products') ?></a></li>
					    <li><a href="<?php echo base_url();?>admincontrol/listproduct/reviews"><?= __('admin.page_title_listproduct_review') ?></a></li>
					    <li><a href="<?php echo base_url();?>admincontrol/store_category/"><?= __('admin.menu_store_category') ?></a></li>
					    <li><a href="<?php echo base_url();?>admincontrol/form/"><?= __('admin.menu_form_list') ?></a></li>
					    <li><a href="<?php echo base_url();?>admincontrol/listorders/"><?= __('admin.menu_list_orders') ?></a></li>
					    <li><a href="<?php echo base_url();?>admincontrol/listclients/"><?= __('admin.menu_list_clients') ?></a></li>
					    <li><a href="<?php echo base_url();?>admincontrol/coupon/"><?= __('admin.coupon') ?></a></li>
					    <li><a href="<?php echo base_url();?>admincontrol/form_coupon/"><?= __('admin.menu_form_coupon') ?></a></li>
					    <li><a href="<?php echo base_url();?>admincontrol/storepayments/"><?= __('admin.page_title_storepayments') ?></a></li>
					    <!-- <li><a href="<?php echo base_url();?>admincontrol/storepayments_doc/"><?= __('admin.page_title_store_paymentdocs') ?></a></li> -->
					    <li><a href="<?php echo base_url();?>admincontrol/store_setting/"><?= __('admin.page_title_store_setting') ?></a></li>
					</ul>
				</li>
			    <?php } ?>
				
				<li class="has_sub">
					<a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-wallet-travel"></i> <span><?= __('admin.menu_admin_wallet') ?></span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
					<ul class="list-unstyled">
						<li><a href="<?php echo base_url();?>admincontrol/mywallet/"><?= __('admin.menu_all_transactions') ?></a></li>
						<li><a href="<?php echo base_url();?>admincontrol/wallet/withdraw"><?= __('admin.menu_withdraw_request') ?></a></li>
						<li><a href="<?php echo base_url('admincontrol/wallet_requests_list');?>"><?= __('admin.menu_withdraw_request_v2') ?></a></li>
						<li><a href="<?php echo base_url();?>admincontrol/withdrawal_payment_gateways"><?= __('admin.withdrawal_payment_gateways') ?></a></li>
					</ul>
				</li>
				<!--<li class="menu-title">Reports&Wallet</li>-->
				<li class="has_sub">
					<a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-view-list"></i> <span><?= __('admin.menu_reports') ?></span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
					<ul class="list-unstyled">
						<li><a href="<?php echo base_url();?>ReportController/admin_transaction/" class="waves-effect"><i class="mdi mdi-settings"></i><span><?= __('admin.menu_report_all_transactions') ?></span></a></li>
						<li><a href="<?= base_url('incomereport') ?>" class="waves-effect" ><i class="mdi mdi-layers"></i> <span> <?= __('admin.menu_statistics') ?> </span> </a></li>
						<li><a href="<?php echo base_url();?>ReportController/admin_statistics/" class="waves-effect"><i class="mdi mdi-settings"></i><span><?= __('admin.menu_report_statistics') ?></span></a></li>
					</ul>
				</li>
				<li class="has_sub">
					<a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-script"></i> <span><?= __('admin.menu_script_builders') ?></span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
					<ul class="list-unstyled">
						<li><a href="<?= base_url('admincontrol/registration_builder') ?>" class="waves-effect" ><i class="mdi mdi-library-plus"></i> <span> <?= __('admin.menu_registration_builder') ?> </span> </a></li>
					</ul>
				</li>
				
				<li class="has_sub">
					<a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-account-settings-variant"></i> <span><?= __('admin.menu_global_settings') ?></span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
					<ul class="list-unstyled">
					    <li><a href="<?php echo base_url();?>admincontrol/paymentsetting/" class="waves-effect"><i class="mdi mdi-account-settings-variant"></i><span><?= __('admin.menu_configuration') ?></span></a></li>
						<li><a href="<?php echo base_url(); ?>admincontrol/mails" class="waves-effect"><i class="mdi mdi-email"></i><span> <?= __('admin.menu_mail_Templates') ?></span></a></li>
						<li><a href="<?php echo base_url();?>admincontrol/backup/" class="waves-effect"><i class="mdi mdi-backup-restore"></i><span><?= __('admin.menu_site_Backups') ?></span></a></li>
						<li><a href="<?= base_url('admincontrol/language') ?>" class="waves-effect" ><i class="mdi mdi-translate"></i> <span> <?= __('admin.menu_language') ?> </span> </a></li>
						<li><a href="<?= base_url('admincontrol/currency_list') ?>" class="waves-effect" ><i class="mdi mdi-translate"></i> <span> <?= __('admin.menu_currency') ?> </span> </a></li>
						<li><a href="<?= base_url('admincontrol/front_template') ?>" class="waves-effect" ><i class="mdi mdi-translate"></i> <span> <?= __('admin.front_side') ?> </span> </a></li>
						<li><a href="<?= base_url('admincontrol/theme_setting') ?>" class="waves-effect" ><i class="mdi mdi-translate"></i> <span> <?= __('admin.theme_setting') ?> </span> </a></li>
						<li><a href="<?= base_url('admincontrol/system_status') ?>" class="waves-effect" ><i class="mdi mdi-alert-outline"></i> <span> <?= __('admin.page_title_system_status') ?> </span>
						<li><a href="<?= base_url('admincontrol/install_new_version') ?>" class="waves-effect" ><i class="mdi mdi-alert-outline"></i> <span> <?= __('admin.page_title_install_new_version') ?> </span>
					</a></li>
					</ul>
				</li>
				


				<li>
					<a href="<?php echo base_url();?>integration/" class="waves-effect"><i class="mdi mdi-layers"></i> 
						<span> <?= __('admin.sub_menu_integration') ?> </span>
					<span class="badge badge-danger float-right">i</span></a> </a>
				</li>

		        <li>
					<a href="https://affiliatepro.org/knowledge-base/" class="waves-effect" target="_blank"><i class="mdi mdi-layers"></i> 
						<span> <?= __('DOCUMENT') ?> </span>
					<span class="badge badge-danger float-right">i</span></a> </a>
				</li>
				
				<li>
					<a href="<?php echo base_url();?>admincontrol/docs/" class="waves-effect"><i class="mdi mdi-layers"></i> 
						<span>HOW I CAN DO?</span>
					<span class="badge badge-danger float-right">i</span></a> </a>
				</li>
				
                <li class="mt-3">
		        	<a href="<?= base_url('admincontrol/script_details') ?>"> <i class="mdi mdi-layers"></i><span class="waves-effect" id="notynew"></span>
			        <span>SCRIPT VERSION:</span><span class="badge badge-primary float-right"><?php echo SCRIPT_VERSION ?></span></a>
		        </li>
                
		        <?php if ($license['is_lifetime']) { ?>
			        <li class="custom-menu-link"><a href="javascript:void(0)" class="waves-effect"><span class="badge badge-success">Lifetime License</span></a></li>
		    	<?php  } ?>
				</br>
				
                </div>
                    <div class="clearfix"></div>
                </div> <!-- end sidebarinner -->
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
                        		$serverReq = checkReq();
                        		require APPPATH."config/breadcrumb.php";
                        		$pageKey = $db->Product_model->page_id();
                        	?>
                        	<script type="text/javascript">console.log('Page ID : <?= $pageKey ?>')</script>
                        	<?php if(isset($pageSetting[$pageKey])){ ?>
	                            <div class="row">
	                                <div class="col-sm-12">
	                                    <div class="page-title-box shadow-sm">
	                                        <div class="iconify float-left">
                                          		<a class="btn btn-primary btn-sm reload-btn" title="Refresh Page" data-toggle='tooltip' href="JavaScript: location.reload(true);"><i class="mdi mdi-refresh" data-inline="false" style="font-size: 1.1rem"></i></a>
	                                        </div>
	                                        <h4 class="page-title pull-left pl-2 mb-lg-0 mb-3"><?= $pageSetting[$pageKey]['title'] ?></h4>
	                                        <div class="float-right dashboard-tool">
	                                        	<?php if($pageKey == 'admincontrol_dashboard'){ ?>
	                                        		<div class="server-last-update">Last Updated <span><?= date("h:i:s A") ?></span></div>
	                                        		<div class="d-inline-block dashboard-refresh mr-3">
		                                        		Auto Reload <span>01:00</span>

		                                        		<script type="text/javascript">
		                                        			function secondsTimeSpanToHMS(s) {
															    var h = Math.floor(s/3600);
															    s -= h*3600;
															    var m = Math.floor(s/60);
															    s -= m*60;
															    return h+":"+(m < 10 ? '0'+m : m)+":"+(s < 10 ? '0'+s : s);
															}
															var refresh_after = 3600;
															var d_index = 1;
		                                        			setInterval(function(){
		                                        				var remaining = refresh_after - d_index;
		                                        				d_index++;

		                                        				$(".dashboard-refresh span").html(secondsTimeSpanToHMS(remaining))
		                                        				if(d_index > refresh_after){
		                                        					d_index = 1;
		                                        					window.location.reload();
		                                        				}
		                                        			}, 1000);
		                                        		</script>
                                        			</div>
	                                        		<div class="server-status">
		                                        		<?php if(count($serverReq) == 0) { ?>
			                                        		<div class="server-ok">Server Run OK <i class="fa fa-check-circle-o"></i></div>
		                                        		<?php } else { ?>
		                                        			<div class="server-error">Server Error <i class="fa fa-times-circle-o"></i></div>
		                                        		<?php } ?>
                                        			</div>
                                        			
                                        			<div class="d-inline-block setting-tools">
                                        				<button class="btn btn-blue-grey btn-sm btn-setting ml-2" data-toggle="tooltip" title='Dashboard Settings' data-key='live_dashboard' data-type='admin'>
									                        <i class="fa fa-gear"></i>
									                    </button>
                                        			</div>
	                                        	<?php } ?>
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
	                                        
		                                    <?php if($pageKey == 'admincontrol_dashboard'){ ?>
			                                    <div id="dashboard-progress"></div>
											<?php } ?>
	                                        <div class="clearfix"></div>
	                                    </div>
	                                </div>
	                            </div>
                        	<?php } ?>
                        </div>
                    </div>
                    <div class="server-errors">
	                    <?php
	                    	if($serverReq){
	                    		echo "<div class='requirement-error'>";
	                    		foreach ($serverReq as $key => $e) {
	                    			echo "<p>{$e}</p>";
	                    		}
	                    		echo "</div>";
	                    	}
	                    ?>
                    </div>
<div class="modal fade" id="update-error">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<p>Dear Client,</p>
				<p>You are not allowed to get auto updates with your license type.
				To Enable Updates Please Contact To Script Admin at: <b><a href="mailto:support@affiliatepro.org">support@affiliatepro.org</a></b>
				</p>
				<p>Thank you</p>
				<b>Affiliate Pro Support</b>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

