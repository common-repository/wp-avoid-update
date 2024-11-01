<?php
/**
 * Plugin Name: WP Avoid Update
 * Plugin URI: http://simplerthansimplest.com/
 * Description: Wordpress extension to remove plugin-update link from plugin listing page. Helpful to avoid comitting mistake in case you don't want to update any plugin.
 * Version: 1.0.0
 * Author: SimplerThanSimplest
 * Author URI: http://simplerthansimplest.com/
 * Requires at least: 4.0
 * Tested up to: 4.7
 *
 * Text Domain: wp-avoid-update
 * Domain Path: /i18n/languages/
 *
 * @package WP_AVOID_UPDATE
 * @category Core
 * @author SimplerThanSimplest
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WP_AVOID_UPDATE' ) ) :

/**
 * Main WP_AVOID_UPDATE Class.
 *
 * @class WP_AVOID_UPDATE
 * @version	1.0.0
 */
class WP_AVOID_UPDATE {

	/**
	 * WP_AVOID_UPDATE version.
	 *
	 * @var string
	 */
	public $version = '1.0.0';
	public $sts_site_url = 'http://simplerthansimplest.com/';

	/**
	 * The single instance of the class.
	 *
	 * @var WP_AVOID_UPDATE
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	
	/**
	 * Main WP_AVOID_UPDATE Instance.
	 *
	 * Ensures only one instance of WP_AVOID_UPDATE is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see INSTANTIATE_WP_AVOID_UPDATE()
	 * @return WP_AVOID_UPDATE - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	
	/**
	 * WP_AVOID_UPDATE Constructor.
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();

		do_action( 'wp_avoid_update_loaded' );
	}

	/**
	 * Hook into actions and filters.
	 * @since  1.0.0
	 */
	private function init_hooks() {
		add_filter( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_filter( 'plugin_action_links_'.WP_AVOID_UPDATE_PLUGIN_BASENAME, array( $this, 'alter_plugin_action_links' ) );
		add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 5 );
	}

	function admin_footer_text( $footer_text ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$screen = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		if( $screen_id == 'plugins_page_wpau_admin_settings' && isset($_GET['page']) && $_GET['page'] == 'wpau_admin_settings' ) {
			$footer_text = 'Thanks for using <b>WP Avoid Update</b>.';
			$footer_text .= '<br/>';
			$footer_text .= '<a href="'.$this->sts_site_url.'" target="_blank"><strong>Build And Customize More With Us</strong></a>';
		}
		return $footer_text;
	}

	function alter_plugin_action_links( $plugin_links ) {
		$settings_link = '<a href="admin.php?page=wpau_admin_settings">Settings</a>';
		array_unshift( $plugin_links, $settings_link );
		return $plugin_links;
	}

	/**
	 * Define WP_AVOID_UPDATE Constants.
	 */
	private function define_constants() {
		$this->define( 'WP_AVOID_UPDATE_PLUGIN_FILE', __FILE__ );
		$this->define( 'WP_AVOID_UPDATE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'WP_AVOID_UPDATE_VERSION', $this->version );
		$this->define( 'WP_AVOID_UPDATE_TEXT_DOMAIN', 'wp-avoid-update' );
		$this->define( 'WP_AVOID_UPDATE_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
		$this->define( 'WP_AVOID_UPDATE_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param  string $name
	 * @param  string|bool $value
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {
		include_once( 'admin/class-wpau-avoid-plugin-update.php' );
		include_once( 'admin/class-wpau-admin-settings.php' );
	}

	/**
	 * Load Localisation files.
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'wp_avoid_update_plugin_locale', get_locale(), WP_AVOID_UPDATE_TEXT_DOMAIN );
		load_textdomain( WP_AVOID_UPDATE_TEXT_DOMAIN, WP_AVOID_UPDATE_PLUGIN_DIR_PATH .'language/'.WP_AVOID_UPDATE_TEXT_DOMAIN.'-' . $locale . '.mo' );
		load_plugin_textdomain( WP_AVOID_UPDATE_TEXT_DOMAIN, false, plugin_basename( dirname( __FILE__ ) ) . '/language' );
	}

}

endif;

/**
 * Main instance of WP_AVOID_UPDATE.
 *
 * Returns the main instance of WP_AVOID_UPDATE to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return WP_AVOID_UPDATE
 */
function INSTANTIATE_WP_AVOID_UPDATE() {
	return WP_AVOID_UPDATE::instance();
}

// Global for backwards compatibility.
$GLOBALS['wp_avoid_update'] = INSTANTIATE_WP_AVOID_UPDATE();
?>