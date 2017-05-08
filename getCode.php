<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['erpco'])) {

    // receiving the post params
    $erpco = $_POST['erpco'];    

    // get the user by email and password
    $incident = $db->getCode($erpco);

    if ($incident != false) {
        // use is found
        $response["error"] = FALSE;
        $response["incident"]["erpco"] = $incident["erpco"];
        $response["incident"]["driver"] = $incident["driver"];
        $response["incident"]["bus"] = $incident["bus"];
        $response["incident"]["category"] = $incident["category"];
		$response["incident"]["region"] = $incident["region"];
		$response["incident"]["date"] = $incident["date"];
		$response["incident"]["site"] = $incident["site"];
		$response["incident"]["classification"] = $incident["classification"];
		$response["incident"]["type"] = $incident["type"];
		$response["incident"]["suggestion"] = $incident["suggestion"];
		$response["incident"]["createdat"] = $incident["createdat"];			
        echo json_encode($response);
    } else {
        // user is not found with the credentials
        $response["error"] = TRUE;
        $response["error_msg"] = "Codigo erroneo!";
        echo json_encode($response);
    }
} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters email or password is missing!";
    echo json_encode($response);
}
?>

