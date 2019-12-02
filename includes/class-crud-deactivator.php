<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://github.com/coderkoala
 * @since      1.0.0
 *
 * @package    Crud
 * @subpackage Crud/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Crud
 * @subpackage Crud/includes
 * @author     Nobel Dahal <iamtribulation@outlook.com>
 */
class Crud_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

    /*
 * @@ returns boolean
 */
    private static function destroyTable(){
        global $wpdb;

        $tableName = $wpdb->prefix. 'crud_students';

        $sql = "Drop TABLE {$tableName};";

        try{
            $attempt = $wpdb->query($sql);
            return $attempt;
        }
        catch(Exception $exception){
            return ($exception->getMessage());
        }
    }

	public static function deactivate() {
        self::destroyTable();
	}

}
