<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_POST['erpco']) && isset($_POST['driver']) && isset($_POST['bus']) && isset($_POST['category']) && isset($_POST['region']) && isset($_POST['date']) && isset($_POST['site']) && isset($_POST['classification']) && isset($_POST['type']) && isset($_POST['suggestion']))  {
 
    // receiving the post params
	$erpco= $_POST['erpco'];
    $driver = $_POST['driver'];
	$bus = $_POST['bus'];        
	$category = $_POST['category'];
	$region = $_POST['region'];        	
	$date = $_POST['date'];        
	$site = $_POST['site'];        
	$classification = $_POST['classification'];
	$type = $_POST['type'];
	$suggestion = $_POST['suggestion'];		
	
    // check if user is already existed with the same MAC
    if ($db->isCodeExisted($erpco)) {
        // user already existed
        $response["error"] = TRUE;
        $response["error_msg"] = "El codigo ya existe " . $erpco;
        echo json_encode($response);
    } else {
        // create a new user
        $incident = $db->storeCode($erpco, $driver, $bus, $category, $region, $date, $site, $classification, $type, $suggestion);
        if ($incident) {
            // user stored successfully
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
    $response["error_msg"] = "Parametros requeridos!";
    echo json_encode($response);
}
?>