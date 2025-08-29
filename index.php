<?php

ini_set('display_errors', 0);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles/login.css">
    <script src="scripts/login.js"></script>
</head>

<body>
    <div class="login-header">
        <h1 class="login-header-text">Sign In</h1>
        <div class="horizontal-line"></div>
    </div>
    <form action="" method="POST" class="login-box">
        <label class="input-label" for="admin-id">Admin ID</label>
        <input type="text" class="input-field" name="admin_id" id="admin-id" required autocomplete="off">
        <label class="input-label" for="password">Admin Password</label>
        <input type="password" class="input-field" name="password" id="password" required>
        <input type="submit" class="button" name="login_button" value="Login">
    </form>
</body>

</html>
<?php

function generateAuthToken($adminId, $adminPassword)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://localhost/accounting_deal/api/generateAuthToken.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            "admin_id: $adminId",
            "admin_password: $adminPassword"
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $decodedResponse = json_decode($response);
    return $decodedResponse->{"auth_token"};
}

if (isset($_POST) && isset($_POST["login_button"])) {
    if (isset($_POST["admin_id"]) && isset($_POST["password"]) && trim($_POST["admin_id"]) != "") {
        $adminId = $_POST["admin_id"];
        $password = $_POST["password"];
        require_once("DB.php");
        $query = "SELECT admin_id, admin_name, is_super_admin, has_insert_privilege, has_view_edit_privilege FROM admins WHERE BINARY admin_id='" . $adminId . "' AND BINARY admin_password='" . $password . "'";
        $result = mysqli_query($conn, $query);
        mysqli_close($conn);
        if ($result) {
            if (mysqli_num_rows($result)) {
                $row = mysqli_fetch_row($result);
                session_start();
                $_SESSION["admin_id"] = $row[0];
                $_SESSION["admin_name"] = $row[1];
                $_SESSION["is_super_admin"] = $row[2];
                $_SESSION["has_insert_prev"] = $row[3];
                $_SESSION["has_view_edit_prev"] = $row[4];
                $_SESSION["auth_token"] = generateAuthToken($adminId, $password);
                header("Location:main.php");
            } else echo "<div id='login-error' class='login-error-box'>Incorrect Admin Data!</div>";
        } else echo "<div id='login-error' class='login-error-box'>Connection Error!</div>";
    } else echo "<div id='login-error' class='login-error-box'>Fields Are Empty!</div>";
    echo "<script>hideLoginErrorBox();</script>";
}
?>