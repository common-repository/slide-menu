<?php if ( ! defined( 'ABSPATH' ) ) exit; 
	
	//* Add page in admin menu
	add_action('admin_menu', 'wow_admin_menu_sbmp', 999);
	function wow_admin_menu_sbmp() {
		add_menu_page(WOW_SBMP_NAME, __( WOW_SBMP_NAME, "wow-sbmp-lang"), 'manage_options', WOW_SBMP_BASENAME, 'wow_admin_sbmp', 'dashicons-menu', null);	
	}
	
	function wow_admin_sbmp() {
		global $wow_plugin;	
		$wow_plugin = true;				 
		include_once( 'partials/main.php' );	
		wp_enqueue_style(WOW_SBMP_BASENAME.'-style', plugin_dir_url(__FILE__) . 'css/style.css',false, WOW_SBMP_VERSION);
		wp_enqueue_script(WOW_SBMP_BASENAME, plugin_dir_url(__FILE__) . 'js/script.js', array('jquery'), WOW_SBMP_VERSION);
		wp_enqueue_script('fonticonpicker', plugin_dir_url(__FILE__) . 'fonticonpicker/jquery.fonticonpicker.min.js', array('jquery'),WOW_SBMP_VERSION);
		wp_enqueue_style('fonticonpicker', plugin_dir_url(__FILE__) . 'fonticonpicker/css/jquery.fonticonpicker.min.css',false, WOW_SBMP_VERSION);
		wp_enqueue_style('fonticonpicker-darkgrey', plugin_dir_url(__FILE__) . 'fonticonpicker/jquery.fonticonpicker.darkgrey.min.css',false, WOW_SBMP_VERSION);	
		wp_enqueue_style( WOW_SBMP_BASENAME.'-fontawesome', WOW_SBMP_URL . 'asset/font-awesome/css/font-awesome.min.css', false, '4.7.0' );		
		
	}
	
	//* Save all parametrs in the database
	if ( ! function_exists ( 'wow_plugin_nonce_check' ) ) {
		
		add_action( 'plugins_loaded', 'wow_plugin_nonce_check' );	
		function wow_plugin_nonce_check() {
			if (isset($_POST['wow_plugin_nonce_field'])) {
				if ( !empty($_POST) && wp_verify_nonce($_POST['wow_plugin_nonce_field'],'wow_plugin_action') && current_user_can('manage_options')){
					wow_plugin_run_data();
				}
			}
		}
		function wow_plugin_run_data(){
			global $wpdb;
			$objItem = new WOW_DATA();
			$add = (isset($_REQUEST["add"])) ? sanitize_text_field($_REQUEST["add"]) : '';
			$data = (isset($_REQUEST["data"])) ? sanitize_text_field($_REQUEST["data"]) : '';
			$page = sanitize_text_field($_REQUEST["page"]);
			$id = sanitize_text_field($_POST['id']);
			$post = array();
			foreach ($_POST as $key => $value){
				if (is_array($value) == true){
					$post[$key] = serialize($value);
				}
				else {
					$post[$key] = $value;
				}
			}
			if (isset($_POST["submit"])) {
				if (sanitize_text_field($_POST["add"]) == "1") {
					$objItem->addNewItem($data, $post);
					header("Location:admin.php?page=".$page."&info=saved");
					exit;
				} 
				else if (sanitize_text_field($_POST["add"]) == "2") {
					$objItem->updItem($data, $post);
					header("Location:admin.php?page=".$page."&tool=add&act=update&id=".$id."&info=update");
					exit;
				}
			}
		}
	}
	//* Footer text
	
	
		add_filter( 'admin_footer_text', 'wow_plugins_admin_footer_text_wsm' );
		function wow_plugins_admin_footer_text_wsm( $footer_text ) {
			global $wow_plugin;
			if ( $wow_plugin == true ) {
				$rate_text = sprintf( '<span id="footer-thankyou">Developed by <a href="http://wow-company.com/" target="_blank">Wow-Company</a> | <a href="https://wordpress.org/support/plugin/slide-menu" target="_blank">Support </a> | <a href="https://www.facebook.com/wowaffect/" target="_blank">Join us on Facebook</a></span>');
				return str_replace( '</span>', '', $footer_text ) . ' | ' . $rate_text . '</span>';
			}
			else {
				return $footer_text;
			}
		}
	
