<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_POST['name']) && isset($_POST['mac']) && isset($_POST['user']))  {
 
    // receiving the post params
	$name= $_POST['name'];
    $mac = $_POST['mac'];
	$user = $_POST['user'];        
	
    // check if user is already existed with the same MAC
    if ($db->isDeviceExisted($mac)) {
        // user already existed
        $response["error"] = TRUE;
        $response["error_msg"] = "El dispositivo ya existe " . $mac;
        echo json_encode($response);
    } else {
        // create a new user
        $device = $db->storeDevice($name, $mac, $user);
        if ($device) {
            // user stored successfully
            $response["error"] = FALSE;
            $response["device"]["name"] = $device["name"];
            $response["device"]["mac"] = $device["mac"];
            $response["device"]["user"] = $device["user"];
            $response["device"]["created_at"] = $device["created_at"];
            //$response["user"]["updated_at"] = $user["updated_at"];
            echo json_encode($response);
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Error en el registro!";
            echo json_encode($response);
        }
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Parametros requeridos (name, mac, user) no se encuentra!";
    echo json_encode($response);
}
?>