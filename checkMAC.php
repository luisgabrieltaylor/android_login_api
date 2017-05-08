<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['mac'])) {

    // receiving the post params
    $mac = $_POST['mac'];
    
    // get the user by email and password
    $device = $db->getDeviceByMAC($mac);

    if ($device != false) {
        // use is found
        $response["error"] = FALSE;
        $response["device"]["mac"] = $device["mac"];        
        echo json_encode($response);
    } else {
        // user is not found with the credentials
        $response["error"] = TRUE;
        $response["error_msg"] = "MAC no encontrada. No cuenta con autorización!";
        echo json_encode($response);
    }
} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Falta el parametro MAC!";
    echo json_encode($response);
}
?>

