<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WPAU_ADMIN_SETTINGS' ) ) :

/**
 * @class WPAU_ADMIN_SETTINGS
 * @version	1.0.0
 */
class WPAU_ADMIN_SETTINGS {
	
	/**
	 * The single instance of the class.
	 *
	 * @var WPAU_ADMIN_SETTINGS
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	protected static $_wpau_setting_slug = 'wpau_admin_settings';

	
	/**
	 * Main WPAU_ADMIN_SETTINGS Instance.
	 *
	 * Ensures only one instance of WPAU_ADMIN_SETTINGS is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see WPAU_ADMIN_SETTINGS()
	 * @return WPAU_ADMIN_SETTINGS - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	
	/**
	 * WPAU_ADMIN_SETTINGS Constructor.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Hook into actions and filters.
	 * @since  1.0.0
	 */
	private function init_hooks() {
		
		add_action( 'admin_menu', array( $this, 'wpau_plugin_menu' ), 10 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10 );
		add_action( 'wp_ajax_wpau_update_plugin_list', array( $this, 'wpau_update_plugin_list' ) );
	}

	function wpau_update_plugin_list() {
		$plugin_name = isset( $_POST['plugin_name'] ) ? $_POST['plugin_name'] : false;
		$action_to_do = isset( $_POST['action_to_do'] ) ? $_POST['action_to_do'] : 'delete';
		
		if( $plugin_name ) {
			$saved_plugins = get_option( 'wpau_avoid_update_plugins', array() );
			switch ( $action_to_do ) {
				case 'add':
					$saved_plugins[$plugin_name] = $plugin_name;
					break;

				case 'delete':
					unset( $saved_plugins[$plugin_name] );
					break;	
				
				default:
					break;
			}
			update_option( 'wpau_avoid_update_plugins', $saved_plugins );
		}
		wp_die();
	}

	function wpau_plugin_menu() {
		$slug_to_use = self::$_wpau_setting_slug;
	    add_plugins_page(
	        __( 'WP Avoid Update Plugin Page', WP_AVOID_UPDATE_TEXT_DOMAIN ),
	        __( 'WP Avoid Update', WP_AVOID_UPDATE_TEXT_DOMAIN ),
	        'manage_options',
	        $slug_to_use,
	        array( $this, 'wpau_render_plugin_submenu_page' )
	    );
	}

	function admin_enqueue_scripts() {
		$screen = get_current_screen();
		$screen_id = $screen ? $screen->id : '';
		if( $screen_id == 'plugins_page_wpau_admin_settings' ) {
			wp_register_script( 'wpau_admin_script', WP_AVOID_UPDATE_PLUGIN_DIR_URL.'/assets/wpau-admin.js', array( 'jquery', 'wpau_admin_datatable_script' ), WP_AVOID_UPDATE_VERSION, true );
			wp_localize_script( 'wpau_admin_script', 'wpau_admin_script_params', array(
				'ajax_url' => admin_url( 'admin-ajax.php' )
			) );
			wp_register_script( 'wpau_admin_datatable_script', WP_AVOID_UPDATE_PLUGIN_DIR_URL.'/assets/jquery.dataTables.min.js', array( 'jquery' ), WP_AVOID_UPDATE_VERSION, true );
			wp_enqueue_script( 'wpau_admin_script' );
			wp_enqueue_script( 'wpau_admin_datatable_script' );

			wp_register_style( 'wpau_admin_css', WP_AVOID_UPDATE_PLUGIN_DIR_URL.'/assets/wpau-admin.css', array(), WP_AVOID_UPDATE_VERSION );
			wp_register_style( 'wpau_admin_datatable_css', WP_AVOID_UPDATE_PLUGIN_DIR_URL.'/assets/jquery.dataTables.min.css', array(), WP_AVOID_UPDATE_VERSION );
			wp_enqueue_style( 'wpau_admin_css' );
			wp_enqueue_style( 'wpau_admin_datatable_css' );
		}
	}

	function wpau_render_plugin_submenu_page() {
		include_once( 'wpau-plugin-listing.php' );
	}

}

endif;

/**
 * Main instance of WPAU_ADMIN_SETTINGS.
 * @since  1.0.0
 * @return WPAU_ADMIN_SETTINGS
 */
WPAU_ADMIN_SETTINGS::instance();
?>