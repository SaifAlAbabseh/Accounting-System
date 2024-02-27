<?php

header('Content-Type: application/json');

error_reporting(0);

$headers = getallheaders();

if (!isset($headers["auth_token"])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Auth token is not valid'));
} else if (isset($_REQUEST) && isset($_REQUEST["admin_id"]) && isset($_REQUEST["state_name"]) && isset($_REQUEST["state_value"])) {
    require_once("../DB.php");
    $authQuery = "SELECT * FROM auth_tokens WHERE auth_token='" . $headers["auth_token"] . "' AND DATEDIFF(date, CURDATE()) BETWEEN 0 AND 1";
    $authResult = mysqli_query($conn, $authQuery);
    if ($authResult) {
        if (mysqli_num_rows($authResult)) {
            $query = "UPDATE admins SET " . $_REQUEST["state_name"] . "='" . $_REQUEST["state_value"] . "' WHERE admin_id='" . $_REQUEST["admin_id"] . "'";
            $result = mysqli_query($conn, $query);
            mysqli_close($conn);
            if ($result) http_response_code(204);
            else http_response_code(500);
            exit;
        } else {
            http_response_code(401);
            echo json_encode(array('error' => 'Auth token is expired'));
            exit;
        }
    } else {
        http_response_code(500);
        exit;
    }
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'admin_id and/or state_name and/or state_value parameters is not valid'));
}
