<?php
ini_set('display_errors', 0);

session_start();
if (!(isset($_SESSION) && $_SESSION["admin_id"])) header("Location:./");
if (!(isset($_SESSION["is_super_admin"]) && $_SESSION["is_super_admin"] == "1")) header("Location:main.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Adminstration</title>
    <link rel="stylesheet" href="styles/administration.css">
    <script>
        var authToken = "<?php echo $_SESSION['auth_token']; ?>";
        var adminId = "<?php echo $_SESSION['admin_id']; ?>";
    </script>
</head>

<body>
    <div class="wrapper" id="wrapper">
        <div class="go-back-outer">
            <a class="go-back" href="main.php">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" width="100" height="100" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                    <path d="M310.968,118.356H171.661V58.575L33.498,138.357l138.164,79.781v-59.781h139.307  c70.323,0,127.534,57.211,127.534,127.534c0,70.322-57.211,127.534-127.534,127.534H148.333v40h162.635  c92.378,0,167.534-75.155,167.534-167.534C478.502,193.512,403.346,118.356,310.968,118.356z" />
                </svg>
            </a>
        </div>

        <div class="insert-new-admin-box">
            <button class="add-new-admin-button-link" id="add-new-admin-button-link">Add New Admin</button>
        </div>

        <div class="filter-box">
            <h2>Filter:</h2>
            <select class="filters" id="search-criteria-header-menu">
                <option value="admin_id"></option>
                <option value="admin_id">Admin ID</option>
                <option value="admin_name">Admin Name</option>
            </select>
            <input type="text" id="search-criteria-value-field" class="input-field">
            <button class="button" id="filter-submit-button">GO</button>
        </div>

        <table border="1" class="results-box">
            <thead class="results-head">
                <tr>
                    <th>Admin ID</th>
                    <th>Admin Name</th>
                    <th>Admin Password</th>
                    <th>Is Super Admin</th>
                    <th>Has Insert Privilege</th>
                    <th>Has View/Edit Privilege</th>
                </tr>
            </thead>
            <tbody id="admins-results"></tbody>
        </table>
    </div>

    <div class="add-new-admin-outer" id="add-new-admin-outer">
        <div class="x-button-outer">
            <button class="button x-button" id="hide-button">X</button>
        </div>
        <form action="" method="POST" class="add-new-admin-box">
            <div class="add-admin-text-fields-box">
                <input type="text" name="admin_name" class="input-field" placeholder="Admin Name" required>
                <input type="password" name="admin_password" class="input-field" placeholder="Admin Password" required>
            </div>
            <div class="add-admin-menus-box">
                <label>Super Admin</label>
                <select name="is_super_admin" class="filters" required>
                    <option value="0">no</option>
                    <option value="1">yes</option>
                </select>
                <label>Insertion Privilege</label>
                <select name="has_insert_priv" class="filters" required>
                    <option value="0">no</option>
                    <option value="1">yes</option>
                </select>
                <label>View/Edit Privilege</label>
                <select name="has_view_edit_priv" class="filters" required>
                    <option value="0">no</option>
                    <option value="1">yes</option>
                </select>
                <input type="submit" name="add_new_admin_button" class="button" value="Add New Admin">
            </div>
        </form>
    </div>

    <script src="scripts/administration.js"></script>
</body>

</html>

<?php

if (isset($_POST) && isset($_POST["add_new_admin_button"])) {
    if (isset($_POST["admin_name"]) && isset($_POST["admin_password"]) && isset($_POST["is_super_admin"]) && isset($_POST["has_insert_priv"]) && isset($_POST["has_view_edit_priv"])) {
        if (trim($_POST["admin_name"]) != "" && trim($_POST["admin_password"])) {
            require_once("DB.php");
            $query = "INSERT INTO admins(`admin_name`,`admin_password`,`is_super_admin`,`has_insert_privilege`,`has_view_edit_privilege`) VALUES('" . $_POST["admin_name"] . "', '" . $_POST["admin_password"] . "', '" . $_POST["is_super_admin"] . "', '" . $_POST["has_insert_priv"] . "', '" . $_POST["has_view_edit_priv"] . "')";
            $result = mysqli_query($conn, $query);
            mysqli_close($conn);
            if ($result) echo "<div id='insert-error' class='insert-success-box'>Success!</div><script>hideInsertErrorBox();</script>";
            else echo "<div id='insert-error' class='insert-error-box'>Connection Error</div><script>hideInsertErrorBox();</script>";
        } else echo "<div id='insert-error' class='insert-error-box'>Check Fields</div><script>hideInsertErrorBox();</script>";
    } else echo "<div id='insert-error' class='insert-error-box'>Fields Are Empty!</div><script>hideInsertErrorBox();</script>";
}

?>