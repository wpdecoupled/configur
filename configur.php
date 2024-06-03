<?php
/**
 * Plugin Name: WP Decoupled Configur
 * Plugin URI: https://github.com/wpdecoupled/configur
 * GitHub Plugin URI: https://github.com/wpdecoupled/configur
 * Description: A WordPress plugin for adding basic features and config need for headless/decoupled WordPress.
 * Author: WP Decoupled
 * Author URI: https://wpdecoupled.dev
 * Update URI: https://github.com/wpdecoupled/configur
 * Version: 0.0.1
 * Text Domain: wp-decoupled-configur
 * Domain Path: /languages
 * Requires at least: 6.0
 * Tested up to: 6.6
 * Requires PHP: 7.4
 * Requires Plugins:
 * License: GPL-3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package WPDecoupled\Configur
 * @author WP Decoupled
 * @license GPL-3
 * @version 0.0.1
 */

declare(strict_types=1);


namespace WPD\Configur;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Autoload Dependencies.
require_once __DIR__ . '/src/Autoloader.php';
if ( ! \WPD\Configur\Autoloader::autoload() ) {
	return;
}

// Run this function when the plugin is activated.
if ( file_exists( __DIR__ . '/activation.php' ) ) {
	require_once __DIR__ . '/activation.php';
	register_activation_hook( __FILE__, 'WPD\Configur\activation_callback' );
}

// Run this function when the plugin is deactivated.
if ( file_exists( __DIR__ . '/deactivation.php' ) ) {
	require_once __DIR__ . '/deactivation.php';
	register_activation_hook( __FILE__, 'WPD\Configur\deactivation_callback' );
}


/**
 * Define plugin constants.
 *
 * @since 0.0.1
 */
function constants(): void {
	// Plugin version.
	if ( ! defined( 'WPD_CONFIGUR_VERSION' ) ) {
		define( 'WPD_CONFIGUR_VERSION', '0.0.1' );
	}

	// Plugin Folder Path.
	if ( ! defined( 'WPD_CONFIGUR_PLUGIN_DIR' ) ) {
		define( 'WPD_CONFIGUR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	}

	// Plugin Folder URL.
	if ( ! defined( 'WPD_CONFIGUR_PLUGIN_URL' ) ) {
		define( 'WPD_CONFIGUR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	}

	// Plugin Root File.
	if ( ! defined( 'WPD_CONFIGUR_PLUGIN_FILE' ) ) {
		define( 'WPD_CONFIGUR_PLUGIN_FILE', __FILE__ );
	}

	// Whether to autoload the files or not.
	if ( ! defined( 'WPD_CONFIGUR_AUTOLOAD' ) ) {
		define( 'WPD_CONFIGUR_AUTOLOAD', true );
	}
}


/**
 * Checks if any known plugin conflicts are present.
 *
 * @since 0.0.1
 *
 * @return string[]
 */
function plugin_conflicts(): array {
	$conflicts = [];

	if ( class_exists( 'WPE\Faust\GraphQL' ) && is_plugin_active( 'faustwp/faustwp.php' ) ) {
		$conflicts[] = 'Faust WP';
	}

	return $conflicts;
}



	/**
	 * Initializes plugin.
	 *
	 * @since 0.0.1
	 */
function init(): void {
	constants();

	// Get the dependencies that are not ready.
	// $not_ready = dependencies_not_ready();

	// Get the conflicting plugins.
	$conflicts = plugin_conflicts();

	// Load our plugin and initialize.
	// empty( $not_ready ) &&
	if ( empty( $conflicts ) && defined( 'WPD_CONFIGUR_PLUGIN_DIR' ) ) {
		require_once WPD_CONFIGUR_PLUGIN_DIR . 'src/Main.php';
		\WPD\Configur\Main::instance();
	}

	// Output an error notice for the conflicting plugins.
	foreach ( $conflicts as $conflict ) {
		add_action(
			'admin_notices',
			static function () use ( $conflict ) {
				?>
					<div class="error notice">
						<p>
					<?php
					printf(
						/* translators: dependency not ready error message */
						esc_html__( '%1$s is not compatible with WP Decoupled Configur. Please deactivate it.', 'wp-decoupled-configur' ),
						esc_attr( $conflict ),
					);
					?>
						</p>
					</div>
						<?php
			}
		);
	}
}

	// Initialize the plugin.
	add_action( 'init', 'WPD\Configur\init' );
