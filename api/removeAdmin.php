<?php

header('Content-Type: application/json');

error_reporting(0);

$headers = getallheaders();

if (!isset($headers["auth_token"])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Auth token is not valid'));
} else if (isset($_REQUEST) && isset($_REQUEST["adminId"])) {
    require_once("../DB.php");
    $authQuery = "SELECT * FROM auth_tokens WHERE auth_token='" . $headers["auth_token"] . "' AND DATEDIFF(date, CURDATE()) BETWEEN 0 AND 1";
    $authResult = mysqli_query($conn, $authQuery);
    if ($authResult) {
        if (mysqli_num_rows($authResult)) {
            $query = "DELETE FROM  admins WHERE admin_id='" . $_REQUEST["adminId"] . "'";
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
    echo json_encode(array('error' => 'adminId parameter is not valid'));
}
