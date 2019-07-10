<?php
/*
Plugin Name: AppPresser Include Plugins Only in App
Plugin URI: http://apppresser.com
Description: Include plugins only while viewing in the app, not in web browser.
Author: AppPresser Team
Version: 0.1
Author URI: http://apppresser.com
*/

class AppPresserIncludePlugins {
	// A single instance of this class.
	public static $instance = null;
	public static $this_plugin = null;
	const PLUGIN = 'AppPresser Include Plugins Only in App';
	const VERSION = '0.1';
	public static $dir_path;
	public static $dir_url;
	public static $is_apppv;

	/**
	 * run function.
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	public static function run() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * __construct function.
	 *
	 * @access public
	 */
	public function __construct() {

		self::$dir_path = trailingslashit( plugin_dir_path( __FILE__ ) );
		self::$dir_url  = trailingslashit( plugins_url( null, __FILE__ ) );
		add_filter( 'option_active_plugins', array( $this, 'appp_filter_plugins' ), 5 );

	}

	function appp_filter_plugins( $active ) {
		if( is_admin() ) {
			return $active;
		}
		if( self::read_app_version() && ( !function_exists('wp_is_mobile') || function_exists('wp_is_mobile') && wp_is_mobile() ) ) {
			$active[] = 'divi-builder/divi-builder.php';
		}

		return $active;
	}

	public static function read_app_version() {
		if ( self::$is_apppv !== null )
			return self::$is_apppv;
		if( isset( $_GET['appp'] ) && $_GET['appp'] == 3 || isset( $_COOKIE['AppPresser_Appp3'] ) && $_COOKIE['AppPresser_Appp3'] === 'true' ) {
			self::$is_apppv = 3;
		} else if( isset( $_GET['appp'] ) && $_GET['appp'] == 2 || isset( $_COOKIE['AppPresser_Appp2'] ) && $_COOKIE['AppPresser_Appp2'] === 'true' ) {
			self::$is_apppv = 2;
		} else if( ( isset( $_GET['appp'] ) && $_GET['appp'] == 1 ) || isset( $_COOKIE['AppPresser_Appp'] ) && $_COOKIE['AppPresser_Appp'] === 'true' ) {
			self::$is_apppv = 1;
		} else {
			self::$is_apppv = 0;
		}
		return self::$is_apppv;
	}

}
AppPresserIncludePlugins::run();
