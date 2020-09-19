<?php
$pageSetting = array();


$pageSetting['admincontrol_dashboard'] = array(
	//'title' => __('admin.page_title_dashboard'),
	'title' => __('admin.welcome_user') ."". $userdetails['username'] 

);

/*$pageSetting['admincontrol_dashboard'] = array(
	'title' => __('admin.page_title_dashboard'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
	),
);*/



$pageSetting['admincontrol_notification'] = array(
	'title' => __('admin.page_title_notification'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		
		array(
			'title' =>  __('admin.page_title_notification'),
			'link' =>  base_url('admincontrol/notification'),
		),
	),
);

$pageSetting['admincontrol_withdrawal_payment_gateways'] = array(
	'title' => __('admin.page_title_withdrawal_payment_gateways'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		
		array(
			'title' =>  __('admin.page_title_withdrawal_payment_gateways'),
			'link' =>  base_url('admincontrol/withdrawal_payment_gateways'),
		),
	),
);


$pageSetting['admincontrol_withdrawal_payment_gateways_edit'] = array(
	'title' => __('admin.page_title_withdrawal_payment_gateways_edit'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_withdrawal_payment_gateways'),
			'link' =>  base_url('admincontrol/withdrawal_payment_gateways'),
		),
		array(
			'title' =>  __('admin.page_title_withdrawal_payment_gateways_edit'),
			'link' =>  base_url('admincontrol/withdrawal_payment_gateways_edit'),
		),
	),
);

$pageSetting['admincontrol_script_details'] = array(
	'title' => __('admin.page_title_script_details'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		
		array(
			'title' =>  __('admin.page_title_script_details'),
			'link' =>  base_url('admincontrol/script_details'),
		),
	),
);

$pageSetting['admincontrol_store_category'] = array(
	'title' => __('admin.page_category'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_category'),
			'link' =>  base_url('admincontrol/store_category'),
		),
	),
);


$pageSetting['admincontrol_store_category_add'] = array(
	'title' => __('admin.page_title_admincontrol_store_category_add'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_category'),
			'link' =>  base_url('admincontrol/store_category'),
		),
		array(
			'title' =>  __('admin.page_title_admincontrol_store_category_add'),
			'link' =>  base_url('admincontrol/notification'),
		),
	),
);


$pageSetting['ReportController_admin_transaction'] = array(
	'title' => __('admin.page_title_transaction'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_transaction'),
			'link' =>  base_url('ReportController/admin_transaction'),
		),
	),
);

$pageSetting['admincontrol_userslist'] = array(
	'title' => __('admin.page_title_userslist'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_userslist'),
			'link' =>  base_url('admincontrol/userslist'),
		),
	),
);

$pageSetting['admincontrol_admin_user'] = array(
	'title' => __('admin.page_title_admin_user'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_admin_user'),
			'link' =>  base_url('admincontrol/admin_user'),
		),
	),
);


$pageSetting['admincontrol_addusers'] = array(
	'title' => __('admin.page_title_addusers'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_addusers'),
			'link' =>  base_url('admincontrol/addusers'),
		),
	),
);


$pageSetting['firstsetting_index'] = array(
	'title' => __('admin.page_title_firstsetting'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_firstsetting'),
			'link' =>  base_url('admincontrol/firstsetting'),
		),
	),
);

$pageSetting['admincontrol_userslisttree'] = array(
	'title' => __('admin.page_title_treelist'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_treelist'),
			'link' =>  base_url('admincontrol/userslisttree'),
		),
	),
);


$pageSetting['admincontrol_userslistmail'] = array(
	'title' => __('admin.page_title_userslistmail'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_userslistmail'),
			'link' =>  base_url('admincontrol/userslistmail'),
		),
	),
);

$pageSetting['integration_programs'] = array(
	'title' => __('admin.page_title_programs'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_programs'),
			'link' =>  base_url('integration/programs'),
		),
	),
);


$pageSetting['integration_programs_form'] = array(
	'title' => __('admin.page_title_edit_programs'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_edit_programs'),
			'link' =>  base_url('integration/programs_form'),
		),
	),
);


$pageSetting['admincontrol_downline'] = array(
	'title' => __('admin.page_title_downline'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_downline'),
			'link' =>  base_url('admincontrol/downline'),
		),
	),
);



$pageSetting['integration_integration_tools'] = array(
	'title' => __('admin.page_title_integration_tools'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_integration_tools'),
			'link' =>  base_url('integration/integration_tools'),
		),
	),
);



$pageSetting['integration_integration_tools_form'] = array(
	'title' => __('admin.page_title_edit_ads'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_edit_ads'),
			'link' =>  base_url('integration/integration_tools_form/link_ads'),
		),
	),
);


$pageSetting['integration_orders'] = array(
	'title' => __('admin.page_title_integration_orders'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_integration_orders'),
			'link' =>  base_url('integration/orders'),
		),
	),
);

$pageSetting['integration_logs'] = array(
	'title' => __('admin.page_title_integration_logs'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_integration_logs'),
			'link' =>  base_url('integration/logs'),
		),
	),
);


$pageSetting['integration_index'] = array(
	'title' => __('admin.page_title_integration_plugins'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_integration_plugins'),
			'link' =>  base_url('integration/index'),
		),
	),
);


$pageSetting['integration_instructions'] = array(
	'title' => __('admin.page_title_integration_instructions'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_integration_instructions'),
			'link' =>  base_url('integration'),
		),
	),
);

if($this->uri->segment(3) == 'reviews'){
	$pageSetting['admincontrol_listproduct'] = array(
		'title' => __('admin.page_title_listproduct_review'),
		'breadcrumb' => array(
			array(
				'title' =>  __('admin.page_title_dashboard'),
				'link' =>  base_url('admincontrol/dashboard'),
			),
			array(
				'title' =>  __('admin.page_title_listproduct'),
				'link' =>  base_url('admincontrol/listproduct'),
			),
			array(
				'title' =>  __('admin.page_title_listproduct_review'),
				'link' =>  base_url('admincontrol/listproduct/reviews'),
			),
		),
	);
} else {
	$pageSetting['admincontrol_listproduct'] = array(
		'title' => __('admin.page_title_listproduct'),
		'breadcrumb' => array(
			array(
				'title' =>  __('admin.page_title_dashboard'),
				'link' =>  base_url('admincontrol/dashboard'),
			),
			array(
				'title' =>  __('admin.page_title_listproduct'),
				'link' =>  base_url('admincontrol/listproduct'),
			),
		),
	);
}


$pageSetting['admincontrol_addproduct'] = array(
	'title' => __('admin.page_title_addproduct'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_listproduct'),
			'link' =>  base_url('admincontrol/listproduct'),
		),
		array(
			'title' =>  __('admin.page_title_addproduct'),
			'link' =>  base_url('admincontrol/addproduct'),
		),
	),
);



$pageSetting['admincontrol_productupload'] = array(
	'title' => __('admin.page_title_productupload'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_productupload'),
			'link' =>  base_url('admincontrol/productupload'),
		),
	),
);


$pageSetting['admincontrol_store_dashboard'] = array(
	'title' => __('admin.page_title_store_dashboard'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_store_dashboard'),
			'link' =>  base_url('admincontrol/store_dashboard'),
		),
	),
);

$pageSetting['admincontrol_storepayments'] = array(
	'title' => __('admin.page_title_storepayments'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_storepayments'),
			'link' =>  base_url('admincontrol/storepayments'),
		),
	),
);


$pageSetting['admincontrol_storepayments_doc'] = array(
	'title' => __('admin.page_title_store_paymentdocs'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_store_paymentdocs'),
			'link' =>  base_url('admincontrol/storepayments_doc'),
		),
	),
);

$pageSetting['admincontrol_withdrawal_payment_gateways_doc'] = array(
	'title' => __('admin.page_title_withdrawal_payment_gateways_doc'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_withdrawal_payment_gateways_doc'),
			'link' =>  base_url('admincontrol/withdrawal_payment_gateways_doc'),
		),
	),
);

$pageSetting['admincontrol_storepayments_edit'] = array(
	'title' => __('admin.page_title_storepayments_edit'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_storepayments'),
			'link' =>  base_url('admincontrol/storepayments'),
		),
		array(
			'title' =>  __('admin.page_title_storepayments_edit'),
			'link' =>  '#',
		),
	),
);

$pageSetting['admincontrol_store_setting'] = array(
	'title' => __('admin.page_title_store_setting'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_store_setting'),
			'link' =>  base_url('admincontrol/store_setting'),
		),
	),
);


$pageSetting['admincontrol_updateproduct'] = array(
	'title' => __('admin.page_title_updateproduct'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_listproduct'),
			'link' =>  base_url('admincontrol/listproduct'),
		),
		array(
			'title' =>  __('admin.page_title_updateproduct'),
			'link' =>  base_url('admincontrol/updateproduct'),
		),
	),
);


$pageSetting['admincontrol_videoupload'] = array(
	'title' => __('admin.page_title_videoupload'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_videoupload'),
			'link' =>  base_url('admincontrol/videoupload'),
		),
	),
);


$pageSetting['admincontrol_form'] = array(
	'title' => __('admin.page_title_form'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_form'),
			'link' =>  base_url('admincontrol/form'),
		),
	),
);


$pageSetting['admincontrol_form_manage'] = array(
	'title' => __('admin.page_title_form_manage'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_form_manage'),
			'link' =>  base_url('admincontrol/form_manage'),
		),
	),
);


$pageSetting['admincontrol_listorders'] = array(
	'title' => __('admin.page_title_store_listorders'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_store_listorders'),
			'link' =>  base_url('admincontrol/listorders'),
		),
	),
);


$pageSetting['admincontrol_vieworder'] = array(
	'title' => __('admin.page_title_store_order_detail'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_store_listorders'),
			'link' =>  base_url('admincontrol/listorders'),
		),
		array(
			'title' =>  __('admin.page_title_store_order_detail'),
			'link' =>  '#',
		),
	),
);


$pageSetting['admincontrol_listclients'] = array(
	'title' => __('admin.page_title_listclients'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_listclients'),
			'link' =>  base_url('admincontrol/listclients'),
		),
	),
);


$pageSetting['admincontrol_addclients'] = array(
	'title' => __('admin.page_title_addclients'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_addclients'),
			'link' =>  base_url('admincontrol/addclients'),
		),
	),
);



$pageSetting['admincontrol_coupon_manage'] = array(
	'title' => __('admin.page_title_product_coupon_manage'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_product_coupon_manage'),
			'link' =>  base_url('admincontrol_coupon'),
		),
	),
);


$pageSetting['admincontrol_coupon'] = array(
	'title' => __('admin.page_title_product_coupon'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_product_coupon'),
			'link' =>  base_url('admincontrol/coupon'),
		),
	),
);


$pageSetting['admincontrol_form_coupon'] = array(
	'title' => __('admin.page_title_form_coupon'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_form_coupon'),
			'link' =>  base_url('admincontrol/form_coupon'),
		),
	),
);


$pageSetting['admincontrol_form_coupon_manage'] = array(
	'title' => __('admin.page_title_form_coupon_manage'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_form_coupon_manage'),
			'link' =>  base_url('admincontrol/form_coupon_manage'),
		),
	),
);


$pageSetting['admincontrol_mywallet'] = array(
	'title' => __('admin.page_title_mywallet'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_mywallet'),
			'link' =>  base_url('admincontrol/mywallet'),
		),
	),
);


$pageSetting['admincontrol_wallet_withdraw'] = array(
	'title' => __('admin.page_title_wallet_withdraw'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_wallet_withdraw'),
			'link' =>  base_url('admincontrol/wallet_withdraw'),
		),
	),
);


$pageSetting['incomereport_index'] = array(
	'title' => __('admin.page_title_admin_statistics'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_admin_statistics'),
			'link' =>  base_url('incomereport'),
		),
	),
);



$pageSetting['ReportController_admin_statistics'] = array(
	'title' => __('admin.page_title_admin_statistics_graph'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_admin_statistics_graph'),
			'link' =>  base_url('ReportController/admin_statistics'),
		),
	),
);


$pageSetting['admincontrol_registration_builder'] = array(
	'title' => __('admin.page_title_registration_builder'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_registration_builder'),
			'link' =>  base_url('admincontrol/registration_builder'),
		),
	),
);


$pageSetting['pagebuilder_index'] = array(
	'title' => __('admin.page_title_pagebuilder'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_pagebuilder'),
			'link' =>  base_url('pagebuilder'),
		),
	),
);


$pageSetting['pagebuilder_addtheme'] = array(
	'title' => __('admin.page_title_addtheme'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_addtheme'),
			'link' =>  base_url('pagebuilder/addtheme'),
		),
	),
);



$pageSetting['pagebuilder_addpage'] = array(
	'title' => __('admin.page_title_addpage'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_addpage'),
			'link' =>  base_url('pagebuilder/addpage'),
		),
	),
);



$pageSetting['admincontrol_paymentsetting'] = array(
	'title' => __('admin.page_title_configuration'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_configuration'),
			'link' =>  base_url('admincontrol/paymentsetting'),
		),
	),
);


$pageSetting['admincontrol_install_new_version'] = array(
	'title' => __('admin.page_title_install_new_version'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_install_new_version'),
			'link' =>  base_url('admincontrol/install_new_version'),
		),
	),
);


$pageSetting['admincontrol_mails'] = array(
	'title' => __('admin.page_title_mails'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_mails'),
			'link' =>  base_url('admincontrol/mails'),
		),
	),
);


$pageSetting['admincontrol_mails_edit'] = array(
	'title' => __('admin.page_title_mails_edit'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_mails_edit'),
			'link' =>  base_url('admincontrol/mails'),
		),
	),
);


$pageSetting['admincontrol_backup'] = array(
	'title' => __('admin.page_title_backup'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_backup'),
			'link' =>  base_url('admincontrol/backup'),
		),
	),
);


$pageSetting['admincontrol_language'] = array(
	'title' => __('admin.page_title_language'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_language'),
			'link' =>  base_url('admincontrol/language'),
		),
	),
);


$pageSetting['admincontrol_translation_edit'] = array(
	'title' => __('admin.page_title_translation_edit'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_translation_edit'),
			'link' =>  base_url('admincontrol/translation_edit'),
		),
	),
);



$pageSetting['admincontrol_translation'] = array(
	'title' => __('admin.page_title_edit_translation'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_edit_translation'),
			'link' =>  base_url('admincontrol/translation'),
		),
	),
);



$pageSetting['admincontrol_currency_list'] = array(
	'title' => __('admin.page_title_currency_list'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_currency_list'),
			'link' =>  base_url('admincontrol/currency_list'),
		),
	),
);



$pageSetting['admincontrol_currency_edit'] = array(
	'title' => __('admin.page_title_currency_edit'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_currency_edit'),
			'link' =>  base_url('admincontrol/currency_edit'),
		),
	),
);


$pageSetting['admincontrol_front_template'] = array(
	'title' => __('admin.page_title_front_template'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_front_template'),
			'link' =>  base_url('admincontrol/front_template'),
		),
	),
);


$pageSetting['admincontrol_editProfile'] = array(
	'title' => __('admin.page_title_editProfile'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_editProfile'),
			'link' =>  base_url('admincontrol/editProfile'),
		),
	),
);


$pageSetting['admincontrol_changePassword'] = array(
	'title' => __('admin.page_title_changePassword'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_changePassword'),
			'link' =>  base_url('admincontrol/changePassword'),
		),
	),
);


$pageSetting['admincontrol_update'] = array(
	'title' => __('admin.page_title_site_update'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_site_update'),
			'link' =>  base_url('admincontrol/update'),
		),
	),
);


$pageSetting[''] = array(
	'title' => __('admin.page_title_'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_'),
			'link' =>  base_url(''),
		),
	),
);



$pageSetting[''] = array(
	'title' => __('admin.page_title_'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_'),
			'link' =>  base_url(''),
		),
	),
);


$pageSetting[''] = array(
	'title' => __('admin.page_title_'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_'),
			'link' =>  base_url(''),
		),
	),
);


$pageSetting[''] = array(
	'title' => __('admin.page_title_'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_'),
			'link' =>  base_url(''),
		),
	),
);



$pageSetting[''] = array(
	'title' => __('admin.page_title_'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_'),
			'link' =>  base_url(''),
		),
	),
);


$pageSetting[''] = array(
	'title' => __('admin.page_title_'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_'),
			'link' =>  base_url(''),
		),
	),
);



$pageSetting[''] = array(
	'title' => __('admin.page_title_'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_'),
			'link' =>  base_url(''),
		),
	),
);


$pageSetting[''] = array(
	'title' => __('admin.page_title_'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_'),
			'link' =>  base_url(''),
		),
	),
);





/* USER CONTROLS */

$pageSetting['usercontrol_dashboard'] = array(
	'title' => __('user.welcome_user') ."". $userdetails['username']   ,
);


$pageSetting['integration_user_integration_tools'] = array(
	'title' => __('user.page_title_user_integration_tools'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_user_integration_tools'),
			'link' =>  base_url('integration/user_integration_tools'),
		),
	),
);

$pageSetting['integration_user_orders'] = array(
	'title' => __('user.page_title_orders'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_orders'),
			'link' =>  base_url('integration/user_orders'),
		),
	),
);


$pageSetting['integration_click_logs'] = array(
	'title' => __('user.page_title_logs'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_logs'),
			'link' =>  base_url('integration/click_logs'),
		),
	),
);


$pageSetting['usercontrol_listproduct'] = array(
	'title' => __('user.page_title_listproduct'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_listproduct'),
			'link' =>  base_url('usercontrol/listproduct'),
		),
	),
);

$pageSetting['usercontrol_form'] = array(
	'title' => __('user.page_title_form'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_form'),
			'link' =>  base_url('usercontrol/form'),
		),
	),
);


$pageSetting['usercontrol_listbuyaffiproduct'] = array(
	'title' => __('user.page_title_orders'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_orders'),
			'link' =>  base_url('usercontrol/listbuyaffiproduct'),
		),
	),
);


$pageSetting['usercontrol_mywallet'] = array(
	'title' => __('user.page_title_mywallet'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_mywallet'),
			'link' =>  base_url('usercontrol/mywallet'),
		),
	),
);


$pageSetting['usercontrol_wallet_withdraw'] = array(
	'title' => __('user.page_title_wallet_withdraw'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_wallet_withdraw'),
			'link' =>  base_url('usercontrol/wallet_withdraw'),
		),
	),
);


$pageSetting['usercontrol_addpayment'] = array(
	'title' => __('user.page_title_addpayment'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_addpayment'),
			'link' =>  base_url('usercontrol/addpayment'),
		),
	),
);


$pageSetting['ReportController_user_transaction'] = array(
	'title' => __('user.page_title_user_transaction'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_user_transaction'),
			'link' =>  base_url('ReportController/user_transaction'),
		),
	),
);


$pageSetting['ReportController_user_statistics'] = array(
	'title' => __('user.page_title_graph_user_statistics'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_graph_user_statistics'),
			'link' =>  base_url('ReportController/user_statistics'),
		),
	),
);

$pageSetting['incomereport_statistics'] = array(
	'title' => __('user.page_title_incomereport_statistics'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_incomereport_statistics'),
			'link' =>  base_url('incomereport/statistics'),
		),
	),
);

$pageSetting['usercontrol_managereferenceusers'] = array(
	'title' => __('user.page_title_managereferenceusers'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_managereferenceusers'),
			'link' =>  base_url('usercontrol/managereferenceusers'),
		),
	),
);

$pageSetting['usercontrol_myreferal'] = array(
	'title' => __('user.page_title_reffer_tree'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_reffer_tree'),
			'link' =>  base_url('usercontrol/myreferal'),
		),
	),
);

$pageSetting['usercontrol_userslisttree'] = array(
	'title' => __('user.page_title_userslisttree'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_userslisttree'),
			'link' =>  base_url('usercontrol/userslisttree'),
		),
	),
);


$pageSetting['usercontrol_editProfile'] = array(
	'title' => __('user.page_title_editProfile'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_editProfile'),
			'link' =>  base_url('usercontrol/editProfile'),
		),
	),
);



$pageSetting['usercontrol_changePassword'] = array(
	'title' => __('user.page_title_changePassword'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_changePassword'),
			'link' =>  base_url('usercontrol/changePassword'),
		),
	),
);



$pageSetting['admincontrol_system_status'] = array(
	'title' => __('admin.page_title_system_status'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_system_status'),
			'link' =>  base_url('admincontrol/system_status'),
		),
	),
);


$pageSetting['usercontrol_notification'] = array(
	'title' => __('user.page_title_notification'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_notification'),
			'link' =>  base_url('usercontrol/notification'),
		),
	),
);



$pageSetting['usercontrol_store_markettools'] = array(
	'title' => __('user.page_title_store_markettools'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_store_markettools'),
			'link' =>  base_url('usercontrol/store_markettools'),
		),
	),
);



$pageSetting['usercontrol_store_orders'] = array(
	'title' => __('user.page_title_store_orders'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_store_orders'),
			'link' =>  base_url('usercontrol/store_orders'),
		),
	),
);



$pageSetting['usercontrol_store_logs'] = array(
	'title' => __('user.page_title_store_logs'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_store_logs'),
			'link' =>  base_url('usercontrol/store_logs'),
		),
	),
);



$pageSetting['usercontrol_my_network'] = array(
	'title' => __('user.page_title_my_network'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_my_network'),
			'link' =>  base_url('usercontrol/my_network'),
		),
	),
);


$pageSetting['ReportController_user_reports'] = array(
	'title' => __('user.page_title_user_reports'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_user_reports'),
			'link' =>  base_url('ReportController/user_reports/'),
		),
	),
);

$pageSetting['admincontrol_store_markettools'] = array(
	'title' => __('user.all_markettools'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.all_markettools'),
			'link' =>  base_url('admincontrol/store_markettools/'),
		),
	),
);


$pageSetting['admincontrol_store_orders'] = array(
	'title' => __('user.my_all_orders'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.my_all_orders'),
			'link' =>  base_url('admincontrol/store_orders/'),
		),
	),
);



$pageSetting['admincontrol_store_logs'] = array(
	'title' => __('user.my_all_logs'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.my_all_logs'),
			'link' =>  base_url('admincontrol/store_logs/'),
		),
	),
);



$pageSetting['admincontrol_theme_setting'] = array(
	'title' => __('admin.page_title_theme_setting'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_theme_setting'),
			'link' =>  base_url('admincontrol/theme_setting'),
		),
	),
);


$pageSetting['usercontrol_store_products'] = array(
	'title' => __('user.page_title_store_products'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_store_products'),
			'link' =>  base_url('usercontrol/store_products'),
		),
	),
);

$pageSetting['usercontrol_store_edit_product'] = array(
	'title' => __('user.page_title_store_edit_product'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_store_edit_product'),
			'link' =>  base_url('usercontrol/store_edit_product'),
		),
	),
);

$pageSetting['usercontrol_store_setting'] = array(
	'title' => __('user.page_title_store_setting'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_store_setting'),
			'link' =>  base_url('usercontrol/store_setting'),
		),
	),
);



























$pageSetting['usercontrol_updateproduct'] = array(
	'title' => __('user.page_title_updateproduct'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_listproduct'),
			'link' =>  base_url('usercontrol/listproduct'),
		),
		array(
			'title' =>  __('user.page_title_updateproduct'),
			'link' =>  base_url('usercontrol/updateproduct'),
		),
	),
);


$pageSetting['usercontrol_videoupload'] = array(
	'title' => __('user.page_title_videoupload'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_videoupload'),
			'link' =>  base_url('usercontrol/videoupload'),
		),
	),
);

$pageSetting['usercontrol_productupload'] = array(
	'title' => __('admin.page_title_productupload'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_productupload'),
			'link' =>  base_url('usercontrol/productupload'),
		),
	),
);



$pageSetting['usercontrol_store_coupon_manage'] = array(
	'title' => __('user.page_title_product_coupon_manage'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_product_coupon_manage'),
			'link' =>  base_url('usercontrol/store_coupon/'),
		),
	),
);


$pageSetting['usercontrol_store_coupon'] = array(
	'title' => __('user.page_title_product_coupon'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_product_coupon'),
			'link' =>  base_url('usercontrol/store_coupon'),
		),
	),
);



$pageSetting['usercontrol_store_form'] = array(
	'title' => __('user.page_title_form'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_form'),
			'link' =>  base_url('usercontrol/store_form'),
		),
	),
);


$pageSetting['usercontrol_store_form_manage'] = array(
	'title' => __('user.page_title_form_manage'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_form'),
			'link' =>  base_url('usercontrol/store_form'),
		),
		array(
			'title' =>  __('user.page_title_form_manage'),
			'link' =>  base_url('usercontrol/store_form_manage'),
		),
	),
);

$pageSetting['usercontrol_store_form_coupon'] = array(
	'title' => __('admin.page_title_form_coupon'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_form_coupon'),
			'link' =>  base_url('usercontrol/store_form_coupon'),
		),
	),
);


$pageSetting['usercontrol_store_form_coupon_manage'] = array(
	'title' => __('admin.page_title_form_coupon_manage'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_form_coupon'),
			'link' =>  base_url('usercontrol/store_form_coupon'),
		),
		array(
			'title' =>  __('admin.page_title_form_coupon_manage'),
			'link' =>  base_url('usercontrol/store_form_coupon_manage'),
		),
	),
);

$pageSetting['usercontrol_store_contact'] = array(
	'title' => __('admin.page_title_store_contact'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_store_contact'),
			'link' =>  base_url('usercontrol/store_contact'),
		),		
	),
);


$pageSetting['integration_integration_category'] = array(
	'title' => __('admin.integration_category'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.integration_category'),
			'link' =>  base_url('integration/integration_category'),
		),
	),
);


$pageSetting['integration_integration_category_add'] = array(
	'title' => __('admin.page_title_integration_category_add'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.integration_category'),
			'link' =>  base_url('integration/integration_category'),
		),
		array(
			'title' =>  __('admin.page_title_integration_category_add'),
			'link' =>  base_url('integration/integration_category_add'),
		),
	),
);



$pageSetting['usercontrol_programs'] = array(
	'title' => __('user.page_title_programs'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_programs'),
			'link' =>  base_url('usercontrol/programs'),
		),
	),
);


$pageSetting['usercontrol_programs_form'] = array(
	'title' => __('user.page_title_edit_programs'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_edit_programs'),
			'link' =>  base_url('usercontrol/programs_form'),
		),
	),
);

$pageSetting['usercontrol_integration_tools'] = array(
	'title' => __('user.page_title_integration_tools'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_integration_tools'),
			'link' =>  base_url('usercontrol/integration_tools'),
		),
	),
);



$pageSetting['usercontrol_integration_tools_form'] = array(
	'title' => __('user.page_title_edit_ads'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_edit_ads'),
			'link' =>  base_url('usercontrol/integration_tools_form/link_ads'),
		),
	),
);


$pageSetting['usercontrol_integration'] = array(
	'title' => __('user.page_title_usercontrol_plugins'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_usercontrol_plugins'),
			'link' =>  base_url('usercontrol/integration'),
		),
	),
);


$pageSetting['usercontrol_instructions'] = array(
	'title' => __('user.page_title_usercontrol_instructions'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_usercontrol_plugins'),
			'link' =>  base_url('usercontrol/integration'),
		),
		array(
			'title' =>  __('user.page_title_usercontrol_instructions'),
			'link' =>  '',
		),
	),
);

$pageSetting['admincontrol_market_tools_setting'] = array(
	'title' => __('admin.admincontrol_market_tools_setting'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.admincontrol_market_tools_setting'),
			'link' =>  base_url('admincontrol/market_tools_setting'),
		),
	),
);

$pageSetting['usercontrol_integration_mywallet'] = array(
	'title' => __('user.usercontrol_integration_mywallet'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.usercontrol_integration_mywallet'),
			'link' =>  base_url('usercontrol/integration_mywallet'),
		),
	),
);

$pageSetting['usercontrol_integration_orders'] = array(
	'title' => __('user.usercontrol_integration_orders'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.usercontrol_integration_orders'),
			'link' =>  base_url('usercontrol/integration_orders'),
		),
	),
);

$pageSetting['usercontrol_wallet_requests_list'] = array(
	'title' => __('user.usercontrol_wallet_requests_list'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.page_title_mywallet'),
			'link' =>  base_url('usercontrol/mywallet'),
		),
		array(
			'title' =>  __('user.usercontrol_wallet_requests_list'),
			'link' =>  base_url('usercontrol/wallet_requests_list'),
		),
	),
);

$pageSetting['usercontrol_wallet_requests_details'] = array(
	'title' => __('user.usercontrol_wallet_requests_details'),
	'breadcrumb' => array(
		array(
			'title' =>  __('user.page_title_dashboard'),
			'link' =>  base_url('usercontrol/dashboard'),
		),
		array(
			'title' =>  __('user.usercontrol_wallet_requests_list'),
			'link' =>  base_url('usercontrol/wallet_requests_list'),
		),
		array(
			'title' =>  __('user.usercontrol_wallet_requests_details'),
			'link' =>  base_url('usercontrol/wallet_requests_list'),
		),
		
	),
);


$pageSetting['admincontrol_wallet_requests_list'] = array(
	'title' => __('admin.admincontrol_wallet_requests_list'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.page_title_mywallet'),
			'link' =>  base_url('admincontrol/mywallet'),
		),
		array(
			'title' =>  __('admin.admincontrol_wallet_requests_list'),
			'link' =>  base_url('admincontrol/wallet_requests_list'),
		),
	),
);

$pageSetting['admincontrol_wallet_requests_details'] = array(
	'title' => __('admin.admincontrol_wallet_requests_details'),
	'breadcrumb' => array(
		array(
			'title' =>  __('admin.page_title_dashboard'),
			'link' =>  base_url('admincontrol/dashboard'),
		),
		array(
			'title' =>  __('admin.admincontrol_wallet_requests_list'),
			'link' =>  base_url('admincontrol/wallet_requests_list'),
		),
		array(
			'title' =>  __('admin.admincontrol_wallet_requests_details'),
			'link' =>  base_url('admincontrol/wallet_requests_list'),
		),
		
	),
);