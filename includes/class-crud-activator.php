<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/coderkoala
 * @since      1.0.0
 *
 * @package    Crud
 * @subpackage Crud/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Crud
 * @subpackage Crud/includes
 * @author     Nobel Dahal <iamtribulation@outlook.com>
 */
class Crud_Activator {

    /*
     * @@ returns boolean
     */
    private static function createTable(){
        global $wpdb;

        $tableName = $wpdb->prefix. 'crud_students';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$tableName}(
        id int NOT NULL AUTO_INCREMENT,
        name text NOT NULL,
        age int NOT NULL,
        PRIMARY KEY  (id)
        ) {$charset_collate};";

        try{
            $wpdb->query($sql);
            return true;
        }
        catch(Exception $exception){
            return false;
        }
    }

    private static function migrateDB(){
        $is_migrated = get_option('crud_db_migrated');
        if(!$is_migrated){
            $dbCreated = self::createTable();
            if($dbCreated)
                {
                    $is_migrated = add_option('crud_db_migrated',$dbCreated);
                }
            else
                {
                    require_once plugin_dir_path( __FILE__ ) . 'includes/class-crud-deactivator.php';
                    Crud_Deactivator::wipe_db();
                }
        }
    }

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        self::migrateDB();
	}

}
