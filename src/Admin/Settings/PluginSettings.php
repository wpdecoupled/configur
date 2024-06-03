<?php
/**
 * Registers the Plugin Settings
 *
 * @package WPD\Configur\Admin\Settings
 * @since 0.0.6
 */

declare( strict_types = 1 );

namespace WPD\Configur\Admin\Settings;

/**
 * Class PluginSettings
 */
class PluginSettings {
	/**
	 * The settings key used to store the Clients config.
	 *
	 * @var string
	 */
	public static string $settings_prefix = 'wpd_configur_settings_';

	/**
	 * The setting configuration.
	 *
	 * @var array<string,array<string,mixed>>
	 */
	private static array $config = [];

	/**
	 * The args used to register the settings.
	 *
	 * @var array<string,mixed>
	 */
	private static array $args = [];

	/**
	 * Gets the setting configuration.
	 *
	 * @return array<string,array<string,mixed>>
	 */
	public static function get_config(): array {
		if ( empty( self::$config ) ) {
			self::$config = [
				// WP Tempaltes in WPGraphQL Settings.
				self::$settings_prefix . 'show_advanced_settings' => [
					'default'           => false,
					'description'       => __( 'Add Templates to WPGraphQL Schema.', 'wp-decoupled-configur' ),
					'hidden'            => true,
					'label'             => __( 'Add Templates', 'wp-decoupled-configur' ),
					'sanitize_callback' => 'rest_sanitize_boolean',
					'show_in_graphql'   => false,
					'show_in_rest'      => true,
					'type'              => 'boolean',
				],
				// Delete Data on Deactivate.
				self::$settings_prefix . 'delete_data_on_deactivate' => [
					'advanced'          => false,
					'default'           => false,
					'description'       => __( 'Delete all data on plugin deactivation.', 'wp-decoupled-configur' ),
					'help'              => __( 'When selected, all plugin data will be deleted when the plugin is deactivated, including client configurations. Mote: No user meta will be deleted.', 'wp-decoupled-configur' ),
					'label'             => __( 'Delete plugin data on deactivate.', 'wp-decoupled-configur' ),
					'order'             => 1,
					'required'          => false,
					'sanitize_callback' => 'rest_sanitize_boolean',
					'show_in_graphql'   => false,
					'show_in_rest'      => true,
					'type'              => 'boolean',
				],
				// The JWT Secret.
				// self::$settings_prefix . 'jwt_secret_key' => [
				// 'default'         => false,
				// 'hidden'          => true,
				// 'type'            => 'string',
				// 'show_in_rest'    => true,
				// 'show_in_graphql' => false,
				// ],
			];
		}

		return self::$config;
	}

	/**
	 * Returns the args used to register the settings.
	 *
	 * @return array<string,mixed>
	 */
	public static function get_settings_args(): array {
		if ( empty( self::$args ) ) {
			$config = self::get_config();

			$excluded_keys = [
				'advanced',
				'help',
				'label',
				'order',
				'hidden',
			];

			foreach ( $config as $settings_key => $args ) {
				// Remove excluded keys from args.
				$config[ $settings_key ] = array_diff_key( $args, array_flip( $excluded_keys ) );
			}

			self::$args = $config;
		}

		return self::$args;
	}
}
