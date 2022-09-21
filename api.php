<?php

date_default_timezone_set('Asia/Kuala_Lumpur');
header('Access-Control-Allow-Origin:*');

require_once("dbcontroller.php");
$db_handle = new DBController();

$action = $_REQUEST['action'];
$data = array();

switch($action){

    case "delete_selected":
        try{
           $obj      = json_decode($_REQUEST['itemArray'], true);

           for($i = 0; $i < count($obj); $i++){
               try{
                    $query = "DELETE FROM user WHERE sysid='".$obj[$i]."'";
                    $result = $db_handle->executeQuery($query);
               }
               catch(Exception $e){
                    
               }
           }

           echo json_encode(array('message' => 'Successful'));
  
        }
        catch(Exception $e) {
            echo json_encode(array('message' => 'Failed'));
        }
     break;

}

?>