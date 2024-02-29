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
    } else if (isset($_REQUEST["filterBy"]) && isset($_REQUEST["filterCriteria"])) {
        require_once("../DB.php");
        $authQuery = "SELECT * FROM auth_tokens WHERE auth_token='" . $headers["auth_token"] . "' AND DATEDIFF(date, CURDATE()) BETWEEN 0 AND 1";
        $authResult = mysqli_query($conn, $authQuery);
        if ($authResult) {
            if (mysqli_num_rows($authResult)) {
                $filterQuery = "ORDER BY product_id DESC";
                if (isset($_REQUEST["filterMethod"]) && $_REQUEST["isFilterExact"]) {
                    $filterMethod = strpos($_REQUEST["filterMethod"], "true") !== false;
                    if ($filterMethod) {
                        $checkStatement = " LIKE '%" . $_REQUEST["filterCriteria"] . "%'";
                        $isFilterExact = strpos($_REQUEST["isFilterExact"], "true") !== false;
                        if ($isFilterExact) $checkStatement = "='" . $_REQUEST["filterCriteria"] . "'";
                        $filterQuery = "WHERE " . $_REQUEST["filterBy"] . $checkStatement;
                    } else $filterQuery = "ORDER BY " . $_REQUEST["filterBy"] . " " . $_REQUEST["filterCriteria"];
                }
                $getQuery = "SELECT * FROM products " . $filterQuery;
                $getResult = mysqli_query($conn, $getQuery);
                mysqli_close($conn);
                if ($getResult) {
                    $response = array();
                    $requestedNumberOfRows = mysqli_num_rows($getResult);
                    if ($requestedNumberOfRows) {
                        while ($row = mysqli_fetch_row($getResult)) {
                            $response[] =
                                array(
                                    "product_id" => (int)$row[0],
                                    "admin_id" => (int)$row[1],
                                    "product_name" => $row[2],
                                    "product_buy_price" => (float)$row[3],
                                    "product_quantity" => (int)$row[4],
                                    "product_tax" => (float)$row[5],
                                    "product_discount" => (float)$row[6],
                                    "product_selling_price" => (float)$row[7],
                                    "product_sell_date" => $row[8]
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
                echo json_encode($response);
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
        echo json_encode(array('error' => 'filterBy and/or filterCriteria parameters is not valid'));
    }
}
