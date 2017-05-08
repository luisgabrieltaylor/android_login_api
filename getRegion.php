<?php
include_once 'include/DbConnect.php';
 
function getRegion(){
     $db = new DbCo	nnect();
    // array for json response
    $response = array();
    $response["parts"] = array();
     
    // Mysql select query
    $result = mysql_query("SELECT * FROM region");
     
    while($row = mysql_fetch_array($result)){
        // temporary array to create single category
        $tmp = array();
        $tmp["id"] = $row["id"];
        $tmp["region"] = $row["region"];
         
        // push category to final json array
        array_push($response["parts"], $tmp);
    }
     
    // keeping response header to json
    header('Content-Type: application/json');
     
    // echoing json result
    echo json_encode($response);
}
 
getRegion();
?>