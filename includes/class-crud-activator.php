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
            return ($exception->getMessage());
        }
    }

    private static function migrateDB(){
        if(!isset($is_migrated)){
            $dbCreated = self::createTable();
            if($dbCreated === true)
            return true;
            else
                die('Something\'s wrong. Table couldn\'t be created.' );
        }
        else return true;
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
