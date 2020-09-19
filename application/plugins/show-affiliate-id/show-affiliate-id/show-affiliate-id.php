<?php
/**
 * Plugin Name: Show Affiliate ID
 * Plugin URI: https://affiliatepro.org/
 * Description: description.
 * Version: 1.0.0
 * Author: All4Bussiness
 * Author URI: https://affiliatepro.org/
 */
 
// don't load directly
if( !defined('ABSPATH') ){
	exit;
}


$dir = dirname( __FILE__ );
define( 'WPB_PLUGIN_DIR', $dir );
define( 'WPB_PLUGIN_FILE', __FILE__ );

//require_once $dir . '/include/function.php';


/*
 * Add my new menu to the Admin Control Panel
 */
 

add_action('admin_menu', 'my_plugin_menu');
function my_plugin_menu() {
	add_menu_page('Show Affiliate ID', 'Show Affiliate ID', 'manage_options', 'affiliate-settings', 'my_plugin_settings_page', 'dashicons-admin-generic');
}

function my_plugin_settings_page() {
 require_once $dir . 'affiliate-settings.php';
}

function shapeSpace_add_settings_errors() {	
    settings_errors();    
}
add_action('admin_notices', 'shapeSpace_add_settings_errors');


add_action( 'wp_footer', 'footer_affiliate_cookie' );
function footer_affiliate_cookie() {
	$settings =  get_option( 'affiliate_id' );
	$status = $settings['affiliate_status'];
	$text = ($settings['affiliate_text'] == "") ? 'Affiliate ID is {id}' : $settings['affiliate_text'];
	$bgcolor = ($settings['affiliate_bgcolor'] == "") ? '#E10E49' : $settings['affiliate_bgcolor'];
	$textcolor = ($settings['affiliate_textcolor'] == "") ? '#fff' : $settings['affiliate_textcolor'];
	$position = ($settings['affiliate_position'] == "") ? 'bottom' : $settings['affiliate_position'];	
	if($status) : 
    ?>
    <script type="text/javascript">
    	var txt = '<?php echo $text ?>';
    	var bgcolor = '<?php echo $bgcolor ?>';
		var textcolor = '<?php echo $textcolor ?>';
		var position = '<?php echo $position ?>';		

		function getCookie(cname) {
		  var name = cname + "=";
		  var decodedCookie = decodeURIComponent(document.cookie);
		  var ca = decodedCookie.split(';');
		  for(var i = 0; i < ca.length; i++) {
		    var c = ca[i];
		    while (c.charAt(0) == ' ') {
		      c = c.substring(1);
		    }
		    if (c.indexOf(name) == 0) {
		      return c.substring(name.length, c.length);
		    }
		  }
		  return "";
		}
		var af_df_setting = {
		  position:position,
		  text:txt,
		}
		
		var cc = getCookie('af_id');
		var id = cc.toString().split("-");
		var iid =  (typeof id[1] == 'undefined' ? 0 : atob(id[1]));
		var tmp = iid.toString().split("-")
		var afID = typeof tmp[0] == 'string' ? tmp[0] : 0;

		if(parseInt(afID)){
		    var nFilter = document.createElement('div');
		    nFilter.className = 'affiliate-id-info';
		    nFilter.innerHTML = af_df_setting.text.replace("{id}",afID);

		    if( af_df_setting.position =='left'){
		        nFilter.setAttribute("style", "background: "+bgcolor+";position: fixed;left: 0;width: auto;z-index: 999;text-align: center;color: "+textcolor+";padding: 10px;font-family: inherit;top: 50%;");
		    } else if( af_df_setting.position =='right'){
		        nFilter.setAttribute("style", "background: "+bgcolor+";position: fixed;right: 0;width: auto;z-index: 999;text-align: center;color: "+textcolor+";padding: 10px;font-family: inherit;top: 50%;");
		    } else if( af_df_setting.position =='top'){
		        nFilter.setAttribute("style", "background: "+bgcolor+";position: fixed;top: 0;width: 100%;z-index: 999;text-align: center;color: "+textcolor+";padding: 10px;font-family: inherit;");
	        } else if( af_df_setting.position =='top-right'){
	        	nFilter.setAttribute("style", "background: "+bgcolor+";position: fixed;top: 0;width: auto; right:0; z-index: 999;text-align: center;color: "+textcolor+";padding: 10px;font-family: inherit;");
	        } else if( af_df_setting.position =='top-left'){
	        	nFilter.setAttribute("style", "background: "+bgcolor+";position: fixed;top: 0;width: auto; left:0; z-index: 999;text-align: center;color: "+textcolor+";padding: 10px;font-family: inherit;");
	        } else if( af_df_setting.position =='bottom-right'){
	        	nFilter.setAttribute("style", "background: "+bgcolor+";position: fixed;bottom: 0;width: auto; right:0; z-index: 999;text-align: center;color: "+textcolor+";padding: 10px;font-family: inherit;");
	        } else if( af_df_setting.position =='bottom-left'){
	        	nFilter.setAttribute("style", "background: "+bgcolor+";position: fixed;bottom: 0;width: auto; left:0; z-index: 999;text-align: center;color: "+textcolor+";padding: 10px;font-family: inherit;");
		    } else {
		        nFilter.setAttribute("style", "background: "+bgcolor+";position: fixed;bottom: 0;width: 100%;z-index: 999;text-align: center;color: "+textcolor+";padding: 10px;font-family: inherit;");
		    }
		    document.body.appendChild(nFilter)
		}
	</script>
    <?php
	endif;
}



register_activation_hook(__FILE__, 'plugin_activate_fun');
register_deactivation_hook(__FILE__, 'plugin_deactivate_fun');
register_uninstall_hook( __FILE__, 'plugin_uninstall_fun' );

function plugin_activate_fun() {
    
	//actions to perform once on plugin activation go here   
}

function plugin_deactivate_fun() {    
	// actions to perform once on plugin deactivation go here	    
}

function plugin_uninstall_fun() {    
	delete_option( 'affiliate_id' );
}