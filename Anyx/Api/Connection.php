<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @copyright  Copyright 2010-2015 SEConfig Team
 * @version    $Id: Core.php 480 2015-03-19 05:59 Reza $
 * @author     Reza
 */

class Anyx_Api_Connection extends Core_Api_Abstract {

    public function dbConnect(){
        $setting = Engine_Api::_()->getApi('settings', 'core');
        $params["host"]        = $setting->getSetting('anyx.server1.host');
        $params["dbname"]      = $setting->getSetting('anyx.server1.dbname');
        $params["username"]    = $setting->getSetting('anyx.server1.username');
        $params["password"]    = $setting->getSetting('anyx.server1.password');
        $params["port"]        = $setting->getSetting('anyx.server1.port');
        $params["socket"]      = $setting->getSetting('anyx.server1.socket');

        $mysqli = null;
        if ( !empty ( $params["port"] ) && !empty ( $params["socket"] ) ){
            $mysqli = new mysqli( trim( $params["host"] ), trim( $params["username"] ), trim( $params["password"] ), trim( $params["dbname"] ), (int)trim( $params["port"] ), trim( $params["socket"] ) );
        } elseif ( !empty ($params["port"] ) ){
            $mysqli = new mysqli( trim( $params["host"] ), trim( $params["username"] ), trim( $params["password"] ), trim( $params["dbname"] ), (int)trim( $params["port"] ) );
        } else {
            $mysqli = new mysqli( trim( $params["host"] ), trim( $params["username"] ), trim( $params["password"] ), trim( $params["dbname"] ) );
        }

        // Check connection
        if ( $mysqli -> connect_errno ) {
            return array ( "status" => false, "message" => "Failed to connect to MySQL: " . $mysqli -> connect_error );
        }

        return $mysqli;
    }

    public function getTables(){
        $connection = $this->dbConnect();
        $tableList = array();
        $res = mysqli_query( $connection,"SHOW TABLES" );

        while( $cRow = mysqli_fetch_array( $res ) ) {
          $tableList[] = $cRow[0];
        }
        return $tableList;
    }

    public function selectTable(){
        $connection = $this->dbConnect();
        $setting = Engine_Api::_()->getApi('settings', 'core');
	$table_name = "Contract";
//	$table_name = $setting->getSetting('anyx.server1.tbsel');
//		var_dump($table_name);exit;
	$query = "SELECT * FROM {$table_name}";
	$res = mysqli_query( $connection, $query );
	//var_dump($res);exit;
	//foreach($query as $key => $val)
	//{	
	//	$a = $Key;
	//	var_dump($a);
	//}
	//var_dump($res);exit;
	return $res;
    }
	//Samira
    public function selectListofTables(){

	$i = 0;
	foreach($table_name as $key => $val)
	{	
        	$query = "SELECT * FROM {$val}";
		$res = mysqli_query( $connection, $query );
		$reslist[$i] = $res;
		$i = $i+1;
        }
	//var_dump($table_name);
	//var_dump($reslist);exit;

    }

}