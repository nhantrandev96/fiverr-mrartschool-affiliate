<?php
/**
 * Plugin Name: Wordpress/Woocommerce Registration Bridge
 * Plugin URI: https://affiliatepro.org/
 * Description: This plugin is help to affiliate script to register new user..
 * Version: 1.0.0
 * Author: All4Bussiness
 * Author URI: https://affiliatepro.org/
 */

$plugin_name = 'affiliatepro-bridge';

add_action( 'user_register', 'affiliatepro_registration_save', 10, 1 );

if(!function_exists('call_affiliate_api')){
	function call_affiliate_api($endpoint, $data){
		$context_options = stream_context_create(array(
			'http'=>array(
				'method'=>"GET",
				'header'=> "User-Agent: ". $_SERVER['HTTP_USER_AGENT'] ."\r\n"."Referer: \r\n"
			)
		));
		
		file_get_contents("__baseurl__{$endpoint}?".http_build_query($data), false, $context_options);
	}
}

if(!function_exists('affiliatepro_registration_save')){
	function affiliatepro_registration_save( $user_id ) {
		$settings = (array) get_option( 'affiliatepro_bridge' );

		$user = get_userdata($user_id);
		if($user){
			if(is_checkout()){
				if(isset($settings['woocommerce_users'])  && $settings['woocommerce_users'] == "1"){
					call_affiliate_api('integration/addUser', (array)$user->data);
				}	
			}
			else if(isset($settings['wordpress_users'])  && $settings['wordpress_users'] == "1") {
				call_affiliate_api('integration/addUser', (array)$user->data);
			}
		}
	}
}

register_activation_hook(__FILE__, 'affiliatepro_bridge_add_option' );

function plugin_add_settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page={$plugin_name}">' . __( 'Settings' ) . '</a>';
    array_push( $links, $settings_link );
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'plugin_add_settings_link' );

function affiliatepro_bridge_add_option(){
	update_option('affiliatepro_bridge',array(
		'woocommerce_users' => "1",
		'wordpress_users' => "1",
	));
}

add_action( 'admin_menu', 'affiliatepro_bridge_admin_menu' );
function affiliatepro_bridge_admin_menu() {
    add_options_page( __('Wordpress/Woocommerce Registration Bridge', 'textdomain' ), __('Wordpress/Woocommerce Registration Bridge', 'textdomain' ), 'manage_options', $plugin_name, 'my_options_page' );
}
add_action( 'admin_init', 'my_admin_init' );

function my_admin_init() {
  	register_setting( 'my-settings-group', 'affiliatepro_bridge' );
  	add_settings_section( 'section-1', __( '', 'textdomain' ), 'section_1_callback', $plugin_name );
  	add_settings_field( 'field-1-1', __( 'Register Woocommerce Users', 'textdomain' ), 'woocommerce_users_callback', $plugin_name, 'section-1' );	
  	add_settings_field( 'field-1-2', __( 'Register Wordpress Users', 'textdomain' ), 'wordpress_users_callback', $plugin_name, 'section-1' );	
}

function my_options_page() {
?>
  <div class="wrap">
      <h2><?php _e('Wordpress/Woocommerce Registration Bridge Plugin Options', 'textdomain'); ?></h2>
      <form action="options.php" method="POST">
        <?php settings_fields('my-settings-group'); ?>
        <?php do_settings_sections($plugin_name); ?>
        <?php submit_button(); ?>
      </form>
  </div>
<?php }

function section_1_callback() {}

function woocommerce_users_callback() {
	$settings = (array) get_option( 'affiliatepro_bridge' );
	$field = "woocommerce_users";
	$value = esc_attr( $settings[$field] );
	
	echo "<input type='checkbox' name='affiliatepro_bridge[$field]' value='1' ". ($value == '1' ? 'checked' : '') ." />";
}

function wordpress_users_callback() {
	$settings = (array) get_option( 'affiliatepro_bridge' );
	$field = "wordpress_users";
	$value = esc_attr( $settings[$field] );
	echo "<input type='checkbox' name='affiliatepro_bridge[$field]' value='1' ". ($value == '1' ? 'checked' : '') ." />";
}

function my_settings_validate_and_sanitize( $input ) {
	$settings = (array) get_option( 'affiliatepro_bridge' );
	$output['woocommerce_users'] = $input['woocommerce_users'];
	$output['wordpress_users'] = $input['wordpress_users'];

	return $output;
}