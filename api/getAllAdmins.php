<?php

header('Content-Type: application/json');

error_reporting(0);

$headers = getallheaders();

if ($_SERVER['REQUEST_METHOD'] != "GET") {
    http_response_code(405);
    header('Allow: GET');
    echo json_encode(array('error' => $_SERVER['REQUEST_METHOD'] . ' request method is not allowed'));
    exit;
} else {
    if (!isset($headers["auth_token"])) {
        http_response_code(401);
        echo json_encode(array('error' => 'Auth token is not valid'));
    } else if (isset($_REQUEST) && isset($_REQUEST["searchCriteriaHeader"]) && isset($_REQUEST["searchCriteriaValue"]) && isset($headers["admin_id"])) {
        require_once("../DB.php");
        $authQuery = "SELECT * FROM auth_tokens WHERE auth_token='" . $headers["auth_token"] . "' AND DATEDIFF(date, CURDATE()) BETWEEN 0 AND 1";
        $authResult = mysqli_query($conn, $authQuery);
        if ($authResult) {
            if (mysqli_num_rows($authResult)) {
                $filterCriteria = $_REQUEST["searchCriteriaHeader"] . " LIKE '%" . $_REQUEST["searchCriteriaValue"] . "%'";
                $query = "SELECT * FROM admins WHERE admin_id != '" . $headers["admin_id"] . "' AND " . $filterCriteria;
                $result = mysqli_query($conn, $query);
                if ($result) {
                    $response = array();
                    if (mysqli_num_rows($result)) {
                        while ($row = mysqli_fetch_row($result)) {
                            $response[] = array(
                                "admin_id" => $row[0],
                                "admin_name" => $row[1],
                                "admin_password" => $row[2],
                                "is_super_admin" => $row[3],
                                "has_insert_privilege" => $row[4],
                                "has_view_edit_privilege" => $row[5]
                            );
                        }
                    }
                    http_response_code(200);
                    echo json_encode($response);
                    exit;
                } else {
                    http_response_code(500);
                    exit;
                }
            } else {
                http_response_code(401);
                echo json_encode(array('error' => 'Auth token is expired'));
                exit;
            }
        } else {
            http_response_code(500);
            exit;
        }
        mysqli_close($conn);
    } else {
        http_response_code(400);
        echo json_encode(array('error' => 'searchCriteriaHeader and/or searchCriteriaValue and/or admin_id parameters/headers is not valid'));
    }
}
