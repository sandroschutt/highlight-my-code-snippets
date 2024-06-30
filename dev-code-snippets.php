<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://github.com/sandroschutt
 * @since             1.0.0
 * @package           Dev_Code_Snippets
 *
 * @wordpress-plugin
 * Plugin Name:       Highlight My Code Snippets
 * Plugin URI:        https://sandroschutt.com.br/projetos/highlight-my-code-snippets
 * Description:       Highlight My Code Snippets helps you to create beautiful code snipets' shortcodes with highlightjs.
 * Version:           1.0.0
 * Author:            Sandro Schutt
 * Author URI:        https://https://github.com/sandroschutt/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dev-code-snippets
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DEV_CODE_SNIPPETS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dev-code-snippets-activator.php
 */
function activate_dev_code_snippets() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dev-code-snippets-activator.php';
	Dev_Code_Snippets_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dev-code-snippets-deactivator.php
 */
function deactivate_dev_code_snippets() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dev-code-snippets-deactivator.php';
	Dev_Code_Snippets_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dev_code_snippets' );
register_deactivation_hook( __FILE__, 'deactivate_dev_code_snippets' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dev-code-snippets.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dev_code_snippets() {

	$plugin = new Dev_Code_Snippets();
	$plugin->run();

}
run_dev_code_snippets();
