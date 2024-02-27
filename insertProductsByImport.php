<?php
ini_set('display_errors', 0);

session_start();
if (!(isset($_SESSION) && isset($_SESSION["admin_id"]))) header("Location:./");
if(!(isset($_SESSION["has_insert_prev"]) && $_SESSION["has_insert_prev"] == "1")) header("Location:main.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Products By Import</title>
    <link rel="stylesheet" href="styles/insertProductsByImport.css">
    <script src="scripts/insertProductsByImport.js"></script>
</head>

<body>
    <div class="go-back-outer">
        <a class="go-back" href="main.php">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" width="100" height="100" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                <path d="M310.968,118.356H171.661V58.575L33.498,138.357l138.164,79.781v-59.781h139.307  c70.323,0,127.534,57.211,127.534,127.534c0,70.322-57.211,127.534-127.534,127.534H148.333v40h162.635  c92.378,0,167.534-75.155,167.534-167.534C478.502,193.512,403.346,118.356,310.968,118.356z" />
            </svg>
        </a>
    </div>
    <div class="if-dont-have-template-file">
        <h2>Don't have the template file? download it.</h2>
        <a href="templates/insert_products_template.csv" download class="link-button">Download Template File</a>
    </div>
    <form action="" method="POST" enctype="multipart/form-data" class="file-input-box">
        <input type="file" name="products_file" class="input-field" accept="text/csv" required>
        <input type="submit" name="submit_file_button" class="button" value="Import">
    </form>
</body>

</html>

<?php 

function showMessage($message, $type) {
    echo "<div id='import-".$type."' class='import-".$type."-box'>" . $message . "</div>";
    echo "<script>hideMessageBox('".$type."');</script>"; 
}

function insertProduct($conn, $prodName, $prodBuyPrice, $prodQuantity, $prodTax, $prodDiscount, $prodSellingPrice) {
    $adminId = $_SESSION["admin_id"];
    $query = "INSERT INTO products(`admin_id`, `name`, `buy_price`, `quantity`, `tax`, `discount`, `selling_price`) VALUES('" . $adminId . "', '" . $prodName . "', '" . $prodBuyPrice . "', '" . $prodQuantity . "', '" . $prodTax . "', '" . $prodDiscount . "', '" . $prodSellingPrice . "')";
    mysqli_query($conn, $query);
}

if(isset($_POST) && isset($_POST["submit_file_button"])) {
    if($_FILES['products_file']['error'] === UPLOAD_ERR_OK && is_uploaded_file($_FILES['products_file']['tmp_name'])) {
        $fileName = $_FILES["products_file"]["name"];
        $type = substr($fileName, strlen($fileName) - 3, strlen($fileName) - 1);
        if($type == "csv") {
            $csvFilePath = $_FILES["products_file"]["tmp_name"];
            $fileHandle = fopen($csvFilePath, "r");
            if($fileHandle !== false) {
                $index = 0;
                $howManyIsOk = 0;
                $howManyIsNotOk = 0;
                require_once("DB.php");
                while (($line = fgets($fileHandle)) !== false) {
                    if($index == 0) {
                        if(trim($line) != "product_name,product_buy_price,product_quantity,product_tax,product_discount,product_selling_price") {
                            showMessage("File has invalid inputs, please check the file.", "error");
                            break;
                        }
                    }
                    else {
                        $lineArr = str_getcsv($line, ",", '"');

                        $prodName = trim($lineArr[0]);
                        $prodBuyPrice = trim($lineArr[1]);
                        $prodQuantity = trim($lineArr[2]);
                        $prodTax = trim($lineArr[3]);
                        $prodDiscount = trim($lineArr[4]);
                        $prodSellingPrice = trim($lineArr[5]);

                        if($prodName != "" && $prodBuyPrice != "" && $prodQuantity != "" && $prodTax != "" && $prodDiscount != "" && $prodSellingPrice != "") {
                            if(is_numeric($prodBuyPrice) && (is_numeric($prodQuantity) && strpos($prodQuantity, '.') === false) && is_numeric($prodTax) && is_numeric($prodDiscount) && is_numeric($prodSellingPrice)) {
                                insertProduct($conn, $prodName, $prodBuyPrice, $prodQuantity, $prodTax, $prodDiscount, $prodSellingPrice);
                                $howManyIsOk++;
                            }
                            else {
                                showMessage("Some of the values in the file are invalid, please check it and try again", "error");
                                $howManyIsNotOk++;
                            }
                        }
                        else {
                            showMessage("Some of the values in the file are empty", "error");
                            $howManyIsNotOk++;
                        }
                    }
                    $index++;
                }
                mysqli_close($conn);
                fclose($fileHandle);

                showMessage("Successfully Imported Products: " . $howManyIsOk . "<br> Failed Imported Products: " . $howManyIsNotOk, "success");
            }
            else showMessage("Couldnt read from the file", "error");
        }
        else showMessage("File type is not .csv", "error");
    }
    else showMessage("Invalid", "error");
}

?>