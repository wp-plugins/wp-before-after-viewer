<?php
/*
Plugin Name: WP Before After Viewer
plugin URI:http://www.wwgate.net
Description: WP Before After Viewer plugin let you display two images in very nice way to compare them. There is some options you can customise, please go to the setting page to configure it.
Version:1.0.0
Author: Fadi Ismail
Author URI: http://www.wwgate.net
*/
define('WPBA_path', plugins_url('',__FILE__) );
define('WPBA_version', '1.0.0' );
function WPBA_start(){
	//enqueue admin script
	add_action( 'admin_enqueue_scripts', 'WPBA_admin_script' );
	//enqueue scripts and styles
	add_action('wp_enqueue_scripts','enqueue_scripts');
	//add setting page menu
	add_action("admin_menu",'WPBA_setting_page_init');
	//register settings page
	add_action('admin_init', 'register_WPBA_settings');
}
WPBA_start();


//enqueue javascript file to the admin
function enqueue_scripts(){
	$WPBA_vars = array(
		'url'=> plugin_dir_url(__FILE__),
		'animated' =>(get_option( 'WPBA_animateIntro'))? true : false,
		'showlinks' => (get_option( 'WPBA_showFullLinks'))? true : false,
		'dividerposition' =>  get_option( 'WPBA_introPosition','0.50' ),
		'dividercolor' => get_option( 'WPBA_dividerColor','#888' ),
		'enablekeyboard' => (get_option( 'WPBA_enableKeyboard'))? true : false,
		'keyspace' => get_option( 'WPBA_keypressAmount','20' )
	);	
	wp_enqueue_script('touch-punch',WPBA_path.'/js/jquery.ui.touch-punch.min.js',array('jquery','jquery-ui-core','jquery-ui-widget','jquery-ui-draggable'),WPBA_version);
	wp_enqueue_script('beforeafter',WPBA_path.'/js/jquery.beforeafter-1.4.min.js',array('jquery','jquery-ui-core','jquery-ui-widget','jquery-ui-draggable'),WPBA_version);
	wp_localize_script( 'beforeafter', 'WPBA', $WPBA_vars );
	wp_enqueue_script('beforeafterinit',WPBA_path.'/js/wpba.js',array('jquery'),WPBA_version);
	wp_enqueue_style('wpba-style',WPBA_path.'/css/wpba.css',array(),WPBA_version);
}
//admin enqueue script
function WPBA_admin_script(){
	wp_enqueue_script('wpba-admin',WPBA_path.'/js/wpba-admin.js',array('jquery'),WPBA_version);		
}

//setting page function 
function WPBA_setting_page_init(){
	add_submenu_page('options-general.php', 'WP Before After viewer Settings', 'WP Before After Viewer Settings', 'manage_options', 'WPBA_settings', 'WPBA_setting_page_contnet');
}
//setting page content
function WPBA_setting_page_contnet(){
	require_once(plugin_dir_path(__FILE__).'views/settings.php');
}
//register setting fields
function register_WPBA_settings(){
	add_settings_section( 'default_settings', '', 'default_settings_callback', 'WPBA_settings' );
	
	register_setting( 'WPBA_settings_group', 'WPBA_animateIntro' );
	add_settings_field( 'WPBA_animateIntro', 'Animate intro', 'WPBA_animateIntro_callback', 'WPBA_settings', 'default_settings' );
	
	register_setting( 'WPBA_settings_group', 'WPBA_showFullLinks' );
	add_settings_field( 'WPBA_showFullLinks', 'Show full image links', 'WPBA_showFullLinks_callback', 'WPBA_settings', 'default_settings' );
	
	register_setting( 'WPBA_settings_group', 'WPBA_introPosition' );
	add_settings_field( 'WPBA_introPosition', 'Devider position', 'WPBA_introPosition_callback', 'WPBA_settings', 'default_settings' );
	
	register_setting( 'WPBA_settings_group', 'WPBA_dividerColor' );
	add_settings_field( 'WPBA_dividerColor', 'Devider color', 'WPBA_dividerColor_callback', 'WPBA_settings', 'default_settings' );
	
	register_setting( 'WPBA_settings_group', 'WPBA_enableKeyboard' );
	add_settings_field( 'WPBA_enableKeyboard', 'Enable Left/Right keyboard keys', 'WPBA_enableKeyboard_callback', 'WPBA_settings', 'default_settings' );
	
	register_setting( 'WPBA_settings_group', 'WPBA_keypressAmount' );
	add_settings_field( 'WPBA_keypressAmount', 'pixels moves when kayboard keys pressed', 'WPBA_keypressAmount_callback', 'WPBA_settings', 'default_settings' );
	
	register_setting( 'WPBA_settings_group', 'WPBA_keypressAmount' );
	add_settings_field( 'WPBA_keypressAmount', 'pixels moves when kayboard keys pressed', 'WPBA_keypressAmount_callback', 'WPBA_settings', 'default_settings' );
	
}
// call back function for add settings section
function default_settings_callback(){
	//echo 'some text';	
}
function WPBA_animateIntro_callback() {
	$check='';
	$setting = esc_attr( get_option( 'WPBA_animateIntro','false' ) );
	if($setting) $check = "checked";
	echo "<input type='checkbox' name='WPBA_animateIntro' value='true' $check />";
}
function WPBA_showFullLinks_callback() {
	$check='';
	$setting = esc_attr( get_option( 'WPBA_showFullLinks','false' ) );
	if($setting) $check = "checked";
	echo "<input type='checkbox' name='WPBA_showFullLinks' value='true' $check />";
}
function WPBA_introPosition_callback() {
	$setting = esc_attr( get_option( 'WPBA_introPosition','0.50' ) );
	echo "<input type='text' name='WPBA_introPosition' value='$setting' />";
}
function WPBA_dividerColor_callback() {
	$setting = esc_attr( get_option( 'WPBA_dividerColor','#888' ) );
	echo "<input type='text' name='WPBA_dividerColor' value='$setting' />";
}
function WPBA_enableKeyboard_callback() {
	$check='';
	$setting = esc_attr( get_option( 'WPBA_enableKeyboard','false' ) );
	if($setting) $check = "checked";
	echo "<input type='checkbox' name='WPBA_enableKeyboard' value='true' $check />";
}
function WPBA_keypressAmount_callback() {
	$setting = esc_attr( get_option( 'WPBA_keypressAmount','20' ) );
	echo "<input type='text' name='WPBA_keypressAmount' value='$setting' />";
}

/* short code */
add_shortcode('wpba','wpba_shortcode');
function wpba_shortcode($atts,$content){
	$atts = shortcode_atts( array(
		'before_label' =>'',
		'after_label' =>''
	),$atts);
	$lbls = ($atts['before_label'])?'<span class="before-lbl">'.$atts['before_label'].'</span>':'';
	$lbls .= ($atts['after_label'])?'<span class="after-lbl">'.$atts['after_label'].'</span>':'';
	return '<div class="ba-container">'.$lbls . $content.'</div>';
}