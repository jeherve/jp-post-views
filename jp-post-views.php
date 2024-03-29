<?php
/**
 * Plugin Name: Post Views for Jetpack
 * Plugin URI: https://jeremy.hu/jetpack-post-views/
 * Description: Display the number of views for each one of your posts, as recorded by Jetpack Stats.
 * Author: Jeremy Herve
 * Version: 1.5.0
 * Author URI: https://jeremy.hu
 * License: GPL2+
 *
 * Requires at least: 6.0
 * Requires PHP: 5.6
 *
 * @package Post Views for Jetpack
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define( 'JPPOSTVIEWS__VERSION', '1.5.0' );
define( 'JPPOSTVIEWS__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Main plugin class.
 */
class Jeherve_Jp_Post_Views {
	private static $instance;

	static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new Jeherve_Jp_Post_Views;
		}
		return self::$instance;
	}

	/**
	 * Let's get things started!
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		// Load plugin.
		add_action( 'plugins_loaded', array( $this, 'load_plugin' ) );
	}

	/**
	 * Load our plugin. If Jetpack isn't installed, let the user know.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin() {
		// Check if Jetpack is active, and if the Stats module is used.
		if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'stats' ) ) {
			if ( defined( 'JETPACK__VERSION' ) && version_compare( JETPACK__VERSION, '11.5', '<' ) ) {
				// Prompt the user to update Jetpack.
				add_action( 'admin_notices',  array( $this, 'update_jetpack' ) );
				return;
			}

			// Load our functions.
			require_once( JPPOSTVIEWS__PLUGIN_DIR . 'functions.jp-post-views.php' );
			require_once( JPPOSTVIEWS__PLUGIN_DIR . 'widgets.jp-post-views.php' );

			// Add Stats to REST API Post response.
			if ( function_exists( 'register_rest_field' ) ) {
				add_action( 'rest_api_init',  array( $this, 'rest_register_post_views' ) );
			}

			// Create shortcode.
			add_shortcode( 'jp_post_view', 'jp_post_views_display' );
		} else {
			// Prompt the user to install Jetpack.
			add_action( 'admin_notices',  array( $this, 'install_jetpack' ) );
		}
	}

	/**
	 * Prompt to update Jetpack.
	 *
	 * @since 1.4.0
	 *
	 * @echo Notice that you need to update the Jetpack plugin.
	 */
	public function update_jetpack() {
		echo '<div class="error"><p>';
		esc_html_e( 'You are using an old version of the Jetpack plugin. Please update to the latest version so that the Post Views for Jetpack plugin can work properly.', 'post-views-for-jetpack' );
		echo '</p></div>';
	}

	/**
	 * Prompt to install Jetpack.
	 *
	 * @since 1.0.0
	 *
	 * @echo Notice that you need to install the Jetpack plugin.
	 */
	public function install_jetpack() {
		echo '<div class="error"><p>';
		printf(
			__( 'To use the Post View for Jetpack plugin, you\'ll need to install and activate <a href="%s">Jetpack</a> first, and then activate the Stats module.', 'post-views-for-jetpack' ),
			'plugin-install.php?tab=search&s=jetpack&plugin-search-input=Search+Plugins'
		);
		echo '</p></div>';
	}

	/**
	 * Add Colors to REST API Post responses.
	 *
	 * @since 1.0.0
	 */
	public function rest_register_post_views() {
		register_rest_field( 'post',
			'views',
			array(
				'get_callback'    => array( $this, 'rest_get_views' ),
				'update_callback' => array( $this, 'rest_update_views' ),
				'schema'          => null,
			)
		);
	}

	/**
	 * Get the Post views for the API.
	 *
	 * @since 1.0.0
	 *
	 * @param array           $object     Details of current post.
	 * @param string          $field_name Name of field.
	 * @param WP_REST_Request $request    Current request.
	 *
	 * @return array $views Array of views stored for that Post ID.
	 */
	public function rest_get_views( $object, $field_name, $request ) {
		return get_post_meta( $object['id'], '_post_views', true );
	}

	/**
	 * Update post views from the API.
	 *
	 * Only accepts a string.
	 *
	 * @since 1.0.0
	 *
	 * @param string $view       New post view value.
	 * @param object $object     The object from the response.
	 * @param string $field_name Name of field.
	 *
	 * @return bool|int
	 */
	public function rest_update_views( $view, $object, $field_name ) {

		if ( ! isset( $view ) || empty( $view ) ) {
			return new WP_Error( 'bad-post-view', __( 'The specified view is in an invalid format.', 'post-views-for-jetpack' ) );
		}

		$views = array(
			'total'    => $view,
		);

		return update_post_meta( $object->ID, '_post_views', $views );
	}
}
// And boom.
Jeherve_Jp_Post_Views::get_instance();
