<?php
/**
 * Registers plugin settings to the backend.
 *
 * @package WPD\Configur\Admin
 * @since 0.0.1
 */

declare( strict_types = 1 );

namespace WPD\Configur\Admin;

use WPD\Configur\Admin\Settings\PluginSettings;



/**
 * Class - Settings
 */
class Settings {
	/**
	 * Menu slug
	 *
	 * @var string
	 */
	public static string $slug = 'wpd-configur-settings';

	/**
	 * Initialize the class
	 *
	 * @since 0.0.1
	 *
	 * @uses register_settings_page
	 * @uses register_admin_scripts
	 */
	public static function init(): void {
		add_action( 'init', [ self::class, 'register_settings' ] );
		add_action( 'admin_menu', [ self::class, 'register_settings_page' ] );
		// add_action( 'admin_enqueue_scripts', [ self::class, 'register_admin_scripts' ] );
		// add_filter( 'rest_pre_get_setting', [ self::class, 'hide_sensitive_data_from_rest' ], 10, 2 );
	}

	/**
	 * Store and get the registered settings.
	 *
	 * @return array<string,array<string,mixed>>
	 */
	public static function get_all_settings(): array {
		return [
			'plugin' => PluginSettings::get_settings_args(),
			// 'providers'      => ProviderSettings::get_settings_args(),
			// 'access_control' => AccessControlSettings::get_settings_args(),
		];
	}

	/**
	 * Register the settings to WordPress.
	 */
	public static function register_settings(): void {
		$all_settings = self::get_all_settings();

		foreach ( $all_settings as $settings ) {
			foreach ( $settings as $setting_name => $args ) {
				register_setting(
					self::$slug,
					$setting_name,
					$args
				);
			}
		}
	}

	/**
	 * Add submenu page
	 *
	 * @since 0.0.1
	 *
	 * @uses "render_settings"
	 */
	public static function register_settings_page(): void {
		add_menu_page(
			__( 'WPD Configur', 'wp-decoupled-configur' ),
			__( 'WPD Configur', 'wp-decoupled-configur' ),
			'manage_options',
			self::$slug,
			[ self::class, 'render_settings' ]
		);
	}

	/**
	 * Register CSS and JS for page
	 *
	 * @uses "admin_enqueue_scripts" action
	 */
	// public function register_assets(): void {
	// wp_register_script( $this->slug, $this->assets_url . '/js/admin.js', [ 'jquery' ] );
	// wp_register_style( $this->slug, $this->assets_url . '/css/admin.css' );
	// wp_localize_script(
	// $this->slug,
	// 'APEX',
	// [
	// 'strings' => [
	// 'saved' => __( 'Settings Saved', 'wp-decoupled-configur' ),
	// 'error' => __( 'Error', 'wp-decoupled-configur' ),
	// ],
	// 'api'     => [
	// 'url'   => esc_url_raw( rest_url( 'apex-api/v1/settings' ) ),
	// 'nonce' => wp_create_nonce( 'wp_rest' ),
	// ],
	// ]
	// );
	// }

	/**
	 * Enqueue CSS and JS for page
	 */
	// public function enqueue_assets(): void {
	// if ( ! wp_script_is( $this->slug, 'registered' ) ) {
	// $this->register_assets();
	// }
	// wp_enqueue_script( $this->slug );
	// wp_enqueue_style( $this->slug );
	// }

	/**
	 * Render plugin admin page
	 */
	public static function render_settings(): void {
		// $this->enqueue_assets();
		echo 'CONFIGUR IS ALIVE!';
		echo wp_kses_post( '<div id="wp-decoupled-configur-settings"></div>' );
	}
}
