<?php
ini_set('display_errors', 0);

session_start();
if (!(isset($_SESSION) && $_SESSION["admin_id"])) header("Location:./");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="styles/main.css">
    <script src="scripts/main.js"></script>
</head>

<body>
    <div class="header">
        <div class="admin-info-box">
            <label>Admin ID: <br><?php echo $_SESSION["admin_id"]; ?></label>
            <label>Admin Name: <br><?php echo $_SESSION["admin_name"]; ?></label>
            <form action="" method="POST" style="width:100%">
                <button type="submit" class="button logout-button" name="logout_button">
                    <svg id='Logout_Rounded_Left_24' width='40' height='40' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'>
                        <rect width='24' height='24' stroke='none' opacity='0' />
                        <g transform="matrix(0.43 0 0 0.43 12 12)">
                            <path style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill-rule: nonzero; opacity: 1;" transform=" translate(-24.99, -25)" d="M 25 2 C 17.389725 2 10.633395 5.7052643 6.4492188 11.408203 C 6.2248792666953765 11.695340111627546 6.17446280504503 12.081768024907047 6.317622422162685 12.41685200110387 C 6.460782039280341 12.75193597730069 6.7748587365821855 12.982639639991287 7.13742124133953 13.01903163390789 C 7.499983746096875 13.05542362782449 7.853646439046091 12.891743905858696 8.0605469 12.591797 C 11.882371 7.3827357 18.038275 4 25 4 C 36.609534 4 46 13.390466 46 25 C 46 36.609534 36.609534 46 25 46 C 18.038275 46 11.883451 42.617435 8.0605469 37.408203 C 7.853646439046091 37.1082560941413 7.499983746096875 36.94457637217551 7.13742124133953 36.980968366092114 C 6.7748587365821855 37.017360360008716 6.460782039280341 37.24806402269931 6.317622422162685 37.58314799889613 C 6.17446280504503 37.91823197509295 6.2248792666953765 38.304659888372456 6.4492188 38.591797 C 10.634315 44.294565 17.389725 48 25 48 C 37.690466 48 48 37.690466 48 25 C 48 12.309534 37.690466 2 25 2 z M 10.980469 15.990234 C 10.7206702905992 15.997975294585906 10.474090506709873 16.106554684950613 10.292969 16.292969 L 2.3808594 24.205078 C 2.132518321721257 24.394520683571358 1.9869474488697152 24.68911190652127 1.987330693802456 25.001460549295157 C 1.9877139387351965 25.31380919206904 2.134007286128719 25.608042304023293 2.3828125 25.796875 L 10.292969 33.707031 C 10.543785573137152 33.96827179479288 10.916235992218144 34.07350663500295 11.266678051522469 33.98214981098403 C 11.617120110826793 33.89079298696512 11.890792986965119 33.6171201108268 11.982149810984035 33.26667805152247 C 12.07350663500295 32.91623599221815 11.968271794792878 32.54378557313715 11.707031 32.292969 L 5.4140625 26 L 27 26 C 27.360635916577568 26.005100289545485 27.696081364571608 25.815624703830668 27.877887721486516 25.50412715028567 C 28.059694078401428 25.19262959674067 28.059694078401428 24.80737040325933 27.877887721486516 24.49587284971433 C 27.696081364571608 24.184375296169332 27.360635916577568 23.994899710454515 27 24 L 5.4140625 24 L 11.707031 17.707031 C 12.00279153364512 17.41953966716823 12.091720003978844 16.979965023872836 11.930965816124152 16.60011814577245 C 11.770211628269458 16.22027126767206 11.392752556125291 15.978075502390679 10.980469 15.990234 z" stroke-linecap="round" />
                        </g>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
        <h1 class="welcome-label">
            WELCOME
        </h1>
    </div>

    <div class="features-box">
        <a href="insertProducts.php" class="feature insert-products" style="background-color:blue;" id="insert-products">
            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0,0,256,256">
                <g fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal">
                    <g transform="scale(5.12,5.12)">
                        <path d="M25,2c-12.6907,0 -23,10.3093 -23,23c0,12.69071 10.3093,23 23,23c12.69071,0 23,-10.30929 23,-23c0,-12.6907 -10.30929,-23 -23,-23zM25,4c11.60982,0 21,9.39018 21,21c0,11.60982 -9.39018,21 -21,21c-11.60982,0 -21,-9.39018 -21,-21c0,-11.60982 9.39018,-21 21,-21zM24,13v11h-11v2h11v11h2v-11h11v-2h-11v-11z"></path>
                    </g>
                </g>
            </svg>
            <br>
            Insert Products
        </a>
        <a href="insertProductsByImport.php" class="feature insert-products-import" style="background-color:brown;" id="insert-products-import">
            <svg id='Import_File_24' width='100' height='100' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'>
                <rect width='24' height='24' stroke='none' opacity='0' />
                <g transform="matrix(0.91 0 0 0.91 12 12)">
                    <path style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill-rule: nonzero; opacity: 1;" transform=" translate(-14, -13)" d="M 6 2 C 4.897 2 4 2.897 4 4 L 4 20 C 4 21.103 4.897 22 6 22 L 12.683594 22 C 12.387594 21.378 12.181078 20.707 12.080078 20 L 6 20 L 6 4 L 13 4 L 13 9 L 18 9 L 18 12.078125 C 18.327 12.031125 18.66 11.998047 19 11.998047 C 19.34 11.998047 19.673 12.031125 20 12.078125 L 20 8 L 14 2 L 6 2 z M 19 14 C 16.25 14 14 16.25 14 19 C 14 21.75 16.25 24 19 24 C 21.75 24 24 21.75 24 19 C 24 16.25 21.75 14 19 14 z M 19 17 L 22 20 L 16 20 L 19 17 z" stroke-linecap="round" />
                </g>
            </svg>
            <br>
            Insert Products By Importing File
        </a>
        <a href="viewProducts.php" class="feature view-products" style="background-color:black;" id="view-products">
            <svg height="100" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" version="1.1" viewBox="0 0 846.66 846.66" width="100" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <g id="Layer_x0020_1">
                    <path class="fil0" d="M142.28 147.78l196.01 0c11.44,0 20.73,9.29 20.73,20.74l0 129.85c0,11.45 -9.29,20.74 -20.73,20.74l-196.01 0c-11.45,0 -20.74,-9.29 -20.74,-20.74l0 -129.85c0,-11.45 9.29,-20.74 20.74,-20.74zm587.76 223.18l100.7 90.07c20.22,18.08 -7.32,48.86 -27.53,30.77l-100.7 -90.06c-18.93,16.14 -41.02,26.94 -64.17,32.27l0 356.84c0,11.45 -9.28,20.73 -20.73,20.73l-588.03 0c-11.45,0 -20.73,-9.28 -20.73,-20.73l0 -735.04c0,-11.45 9.28,-20.73 20.73,-20.73l588.03 0c11.45,0 20.73,9.28 20.73,20.73l0 82.42c65.63,15.1 113.66,71.98 117.41,139.46 1.8,32.27 -6.67,65.13 -25.71,93.27zm-134.24 -236.37l1.07 -0.05 0 -58 -546.56 0 0 693.57 546.56 0 0 -332.4c-77.4,-3.71 -139.85,-65.45 -144.18,-143.16 -4.66,-83.61 59.5,-155.31 143.11,-159.96zm2.27 41.31c-60.94,3.39 -107.46,55.46 -104.07,116.38 3.39,60.9 55.53,107.46 116.38,104.07 60.47,-3.39 107.45,-55.72 104.07,-116.39 -3.43,-61.59 -56.16,-107.41 -116.38,-104.06zm-475.39 522.97c-27.27,0 -27.27,-41.47 0,-41.47l269.51 0c27.27,0 27.27,41.47 0,41.47l-269.51 0zm0 -284.82c-27.27,0 -27.27,-41.47 0,-41.47l269.51 0c27.27,0 27.27,41.47 0,41.47l-269.51 0zm0 94.94c-27.27,0 -27.27,-41.47 0,-41.47l269.51 0c27.27,0 27.27,41.47 0,41.47l-269.51 0zm0 94.94c-27.27,0 -27.27,-41.47 0,-41.47l269.51 0c27.27,0 27.27,41.47 0,41.47l-269.51 0zm194.87 -414.68l-154.54 0 0 88.39 154.54 0 0 -88.39z" />
                </g>
            </svg>
            <br>
            View Inserted Products
        </a>
        <a href="adminstration.php" class="feature view-products" style="background-color:green;" id="adminstration">
            <svg id='System_administrator_male_24' width='100' height='100' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'>
                <rect width='24' height='24' stroke='none' fill='#000000' opacity='0' />
                <g transform="matrix(0.74 0 0 0.74 12 12)">
                    <path style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill-rule: nonzero; opacity: 1;" transform=" translate(-19, -18.5)" d="M 16 5 C 12.145666 5 9 8.1456661 9 12 C 9 14.448126 10.344163 16.505949 12.257812 17.757812 C 8.5993675 19.24608 6 22.815262 6 27 L 8 27 C 8 22.569334 11.569334 19 16 19 C 19.854334 19 23 15.854334 23 12 C 23 8.1456661 19.854334 5 16 5 z M 16 7 C 18.773666 7 21 9.2263339 21 12 C 21 14.773666 18.773666 17 16 17 C 13.226334 17 11 14.773666 11 12 C 11 9.2263339 13.226334 7 16 7 z M 24 18 L 24 20.203125 C 23.381725 20.334451 22.824548 20.560446 22.3125 20.898438 L 20.755859 19.34375 L 19.34375 20.757812 L 20.898438 22.3125 C 20.560446 22.824548 20.334451 23.381725 20.203125 24 L 18 24 L 18 26 L 20.203125 26 C 20.334451 26.618275 20.560446 27.175452 20.898438 27.6875 L 19.34375 29.242188 L 20.755859 30.65625 L 22.3125 29.101562 C 22.824548 29.439554 23.381725 29.665549 24 29.796875 L 24 32 L 26 32 L 26 29.796875 C 26.618275 29.665549 27.175452 29.439554 27.6875 29.101562 L 29.244141 30.65625 L 30.65625 29.242188 L 29.101562 27.6875 C 29.439554 27.175452 29.665549 26.618275 29.796875 26 L 32 26 L 32 24 L 29.796875 24 C 29.665549 23.381725 29.439554 22.824548 29.101562 22.3125 L 30.65625 20.757812 L 29.244141 19.34375 L 27.6875 20.898438 C 27.175452 20.560446 26.618275 20.334451 26 20.203125 L 26 18 L 24 18 z M 25 22 C 26.668484 22 28 23.331516 28 25 C 28 26.668484 26.668484 28 25 28 C 23.331516 28 22 26.668484 22 25 C 22 23.331516 23.331516 22 25 22 z M 25 24 C 24.448 24 24 24.448 24 25 C 24 25.552 24.448 26 25 26 C 25.552 26 26 25.552 26 25 C 26 24.448 25.552 24 25 24 z" stroke-linecap="round" />
                </g>
            </svg>
            <br>
            Adminstration
        </a>
    </div>
</body>

</html>

<?php

if ($_SESSION["has_insert_prev"] == "0") echo "<script>hideFeature('insert-products');hideFeature('insert-products-import');</script>";
if ($_SESSION["has_view_edit_prev"] == "0") echo "<script>hideFeature('view-products');</script>";
if ($_SESSION["is_super_admin"] == "0") echo "<script>hideFeature('adminstration');</script>";

if (isset($_POST) && isset($_POST["logout_button"])) {
    session_destroy();
    echo "<script>location.replace('./');</script>";
}

?>