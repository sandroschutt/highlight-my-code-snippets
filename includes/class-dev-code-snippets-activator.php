<?php

/**
 * Fired during plugin activation
 *
 * @link       https://https://github.com/sandroschutt
 * @since      1.0.0
 *
 * @package    Dev_Code_Snippets
 * @subpackage Dev_Code_Snippets/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Dev_Code_Snippets
 * @subpackage Dev_Code_Snippets/includes
 * @author     Sandro Schutt <sandro@email.com>
 */
class Dev_Code_Snippets_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'highlight_code_snippets';
		$charset_collate = $wpdb->get_charset_collate();

		if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name) {
			$sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            title tinytext NOT NULL,
            language tinytext NOT NULL,
            code text NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
	}
}
