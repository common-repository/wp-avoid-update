<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WPAU_AVOID_PLUGIN_UPDATE' ) ) :

/**
 * @class WPAU_AVOID_PLUGIN_UPDATE
 * @version	1.0.0
 */
class WPAU_AVOID_PLUGIN_UPDATE {
	
	/**
	 * The single instance of the class.
	 *
	 * @var WPAU_AVOID_PLUGIN_UPDATE
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	
	/**
	 * Main WPAU_AVOID_PLUGIN_UPDATE Instance.
	 *
	 * Ensures only one instance of WPAU_AVOID_PLUGIN_UPDATE is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see WPAU_AVOID_PLUGIN_UPDATE()
	 * @return WPAU_AVOID_PLUGIN_UPDATE - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	
	/**
	 * WPAU_AVOID_PLUGIN_UPDATE Constructor.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Hook into actions and filters.
	 * @since  1.0.0
	 */
	private function init_hooks() {
		add_action( 'site_transient_update_plugins', array( $this, 'site_transient_update_plugins' ), 20, 2 );
	}

	function site_transient_update_plugins( $value, $transient ) {
		$alteredResponse = array();
		$extra_no_update = array();
		$saved_plugins = get_option( 'wpau_avoid_update_plugins', array() );
		
		$response = $value->response;
		foreach ($response as $key => $val) {
			if( in_array($key, $saved_plugins) ) {
				unset($val->tested);
				unset($val->compatibility);
				$extra_no_update[$key] = $val;
			}
			else {
				$alteredResponse[$key] = $val;
			}
		}
		$value->response = $alteredResponse;

		$no_update = $value->no_update;
		$no_update = array_merge( $no_update, $extra_no_update );
		$value->no_update = $no_update;
		
		return $value;
	}

}

endif;

/**
 * Main instance of WPAU_AVOID_PLUGIN_UPDATE.
 * @since  1.0.0
 * @return WPAU_AVOID_PLUGIN_UPDATE
 */
WPAU_AVOID_PLUGIN_UPDATE::instance();
?>