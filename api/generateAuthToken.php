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
    if (isset($headers["admin_id"]) && isset($headers["admin_password"])) {
        require_once("../DB.php");
        $query = "SELECT * FROM admins WHERE admin_id='" . $headers["admin_id"] . "' AND admin_password='" . $headers["admin_password"] . "'";
        $result = mysqli_query($conn, $query);
        if ($result) {
            if (mysqli_num_rows($result)) {
                $clearQuery = "DELETE FROM auth_tokens WHERE admin_id='" . $headers["admin_id"] . "'";
                mysqli_query($conn, $clearQuery);
                $authToken = urlencode(generateAuthToken());
                $authQuery = "INSERT INTO auth_tokens(`auth_token`, `admin_id`) VALUES('" . $authToken . "', '" . $headers["admin_id"] . "')";
                $authResult = mysqli_query($conn, $authQuery);
                mysqli_close($conn);
                if ($authResult) {
                    http_response_code(200);
                    echo json_encode(array('auth_token' => $authToken));
                    exit;
                } else {
                    http_response_code(500);
                    exit;
                }
            } else {
                http_response_code(401);
                echo json_encode(array('error' => 'Credentials invalid'));
                exit;
            }
        } else {
            http_response_code(500);
            exit;
        }
    } else {
        http_response_code(400);
        echo json_encode(array('error' => 'admin_id or/and admin_password is not present'));
    }
}


function generateAuthToken()
{

    $numbers = "0123456789";
    $lowerLetters = "abcdefghijklmnopqrstuvwxyz";
    $upperLetters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $symbols = "@#$%^&*()!?/'][_-";

    $authToken = "login_session_";
    $length = rand(20, 30);

    for ($i = 1; $i <= $length; $i++) {
        $whichCharType = rand(1, 4);
        if ($whichCharType == 1) {
            $index = rand(0, (strlen($numbers) - 1));
            $authToken .= $numbers[$index];
        } else if ($whichCharType == 2) {
            $index = rand(0, (strlen($lowerLetters) - 1));
            $authToken .= $lowerLetters[$index];
        } else if ($whichCharType == 3) {
            $index = rand(0, (strlen($upperLetters) - 1));
            $authToken .= $upperLetters[$index];
        } else if ($whichCharType == 4) {
            $index = rand(0, (strlen($symbols) - 1));
            $authToken .= $symbols[$index];
        }
    }
    return $authToken;
}
