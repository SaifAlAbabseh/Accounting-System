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
    <title>Insert Products</title>
    <link rel="stylesheet" href="styles/insertProducts.css">
    <script src="scripts/insertProducts.js"></script>
</head>

<body>
    <div class="go-back-outer">
        <a class="go-back" href="main.php">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" width="100" height="100" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                <path d="M310.968,118.356H171.661V58.575L33.498,138.357l138.164,79.781v-59.781h139.307  c70.323,0,127.534,57.211,127.534,127.534c0,70.322-57.211,127.534-127.534,127.534H148.333v40h162.635  c92.378,0,167.534-75.155,167.534-167.534C478.502,193.512,403.346,118.356,310.968,118.356z" />
            </svg>
        </a>
    </div>

    <form class="insert-product-row-box" action="" method="POST" autocomplete="off">
        <div class="product-input-fields">
            <div class="product-input-field">
                <label for="name">Product Name</label>
                <input type="text" class="input-field name" name="name" id="name" required>
            </div>
            <div class="product-input-field">
                <label for="buy-price">Product Price</label>
                <input type="number" step="any" min="0" class="input-field buy-price" name="buy_price" id="buy-price" required>
            </div>
            <div class="product-input-field">
                <label for="quantity">Product Quantity</label>
                <input type="number" min="1" class="input-field quantity" name="quantity" id="quantity" required>
            </div>
            <div class="product-input-field">
                <label for="tax">Product Tax</label>
                <input type="number" step="any" min="0" class="input-field tax" name="tax" id="tax" required>
            </div>
            <div class="product-input-field">
                <label for="discount">Product Discount</label>
                <input type="number" step="any" min="0" class="input-field discount" name="discount" id="discount" required>
            </div>
            <div class="product-input-field">
                <label for="selling-price">Product Selling Price</label>
                <input type="number" step="any" min="0" class="input-field selling-price" name="selling_price" id="selling-price" required>
            </div>
        </div>
        <input type="submit" name="insert_product" value="Insert" class="button">
    </form>

    <div class="last-inserted-product-box" id="last-inserted-product-box">
        <form class="insert-product-row-box" action="" method="POST" autocomplete="off">
            <h2 class="last-inserted-product-label">Last Inserted Product</h2>
            <div class="product-input-fields">
                <div class="product-input-field">
                    <label for="last-product-id-field">Product ID</label>
                    <input type="text" class="input-field id" id="last-product-id-field" value="" disabled>
                </div>
                <div class="product-input-field">
                    <label for="last-name">Product Name</label>
                    <input type="text" class="input-field name" name="name" id="last-name" required>
                </div>
                <div class="product-input-field">
                    <label for="last-buy-price">Product Price</label>
                    <input type="number" step="any" min="0" class="input-field buy-price" name="buy_price" id="last-buy-price" required>
                </div>
                <div class="product-input-field">
                    <label for="last-quantity">Product Quantity</label>
                    <input type="number" min="1" class="input-field quantity" name="quantity" id="last-quantity" required>
                </div>
                <div class="product-input-field">
                    <label for="last-tax">Product Tax</label>
                    <input type="number" step="any" min="0" class="input-field tax" name="tax" id="last-tax" required>
                </div>
                <div class="product-input-field">
                    <label for="last-discount">Product Discount</label>
                    <input type="number" step="any" min="0" class="input-field discount" name="discount" id="last-discount" required>
                </div>
                <div class="product-input-field">
                    <label for="last-selling-price">Product Selling Price</label>
                    <input type="number" step="any" min="0" class="input-field selling-price" name="selling_price" id="last-selling-price" required>
                </div>
            </div>
            <div class="last-inserted-product-buttons">
                <input type="submit" name="update_product" value="Update" class="button">
                <button type="button" id="done-button" class="done-button" onclick="hideLastProductBox()">Done?</button>
                <form action="" method="POST">
                    <input type="submit" name="delete_product" value="Delete" class="delete-button">
                </form>
            </div>
        </form>
    </div>


</body>

</html>

<?php

function checkInputValues()
{
    if (
        isset($_POST["name"]) && isset($_POST["buy_price"])
        && isset($_POST["quantity"]) && isset($_POST["tax"])
        && isset($_POST["discount"]) && isset($_POST["selling_price"])
        && trim($_POST["name"]) != "" && trim($_POST["buy_price"]) != ""
        && trim($_POST["quantity"]) != "" && trim($_POST["tax"]) != ""
        && trim($_POST["discount"]) != "" && trim($_POST["selling_price"]) != ""
    ) return true;

    return false;
}

if (isset($_POST) && isset($_POST["insert_product"])) {
    if (checkInputValues()) {

        $adminId = $_SESSION["admin_id"];
        $productName = $_POST["name"];
        $productBuyPrice = $_POST["buy_price"];
        $productQuantity = $_POST["quantity"];
        $productTax = $_POST["tax"];
        $productDiscount = $_POST["discount"];
        $productSellingPrice = $_POST["selling_price"];

        require_once("DB.php");
        $query = "INSERT INTO products(`admin_id`, `name`, `buy_price`, `quantity`, `tax`, `discount`, `selling_price`) VALUES('" . $adminId . "', '" . $productName . "', '" . $productBuyPrice . "', '" . $productQuantity . "', '" . $productTax . "', '" . $productDiscount . "', '" . $productSellingPrice . "')";
        $result = mysqli_query($conn, $query);
        $getQuery = "SELECT product_id FROM products WHERE admin_id='" . $adminId . "' ORDER BY product_id DESC LIMIT 1";
        $getResult = mysqli_query($conn, $getQuery);
        mysqli_close($conn);
        if ($result) {
            echo "<div id='insert-success' class='insert-success'>Success</div><script>hideInsertSuccessBox();</script>";
            $productId = mysqli_fetch_row($getResult)[0];
            $_SESSION["product_id"] = $productId;
            echo "<script>showLastProductBox(); setInputFieldsAfterError('last-', " . $productId . ", '" . $productName . "', '" . $productBuyPrice . "', '" . $productQuantity . "', '" . $productTax . "', '" . $productDiscount . "', '" . $productSellingPrice . "');</script>";
        } else echo "<div id='insert-error' class='insert-error'>Connection Error!</div><script>setInputFieldsAfterError('', false, '" . $_POST["name"] . "', '" . $_POST["buy_price"] . "', '" . $_POST["quantity"] . "', '" . $_POST["tax"] . "', '" . $_POST["discount"] . "', '" . $_POST["selling_price"] . "'); hideInsertErrorBox();</script>";
    } else echo "<div id='insert-error' class='insert-error'>Check Fields!</div><script>setInputFieldsAfterError('', false, '" . $_POST["name"] . "', '" . $_POST["buy_price"] . "', '" . $_POST["quantity"] . "', '" . $_POST["tax"] . "', '" . $_POST["discount"] . "', '" . $_POST["selling_price"] . "'); hideInsertErrorBox();</script>";
}

if (isset($_POST) && isset($_POST["delete_product"]) && isset($_SESSION["product_id"])) {
    require_once("DB.php");
    $query = "DELETE FROM products WHERE product_id='" . $_SESSION["product_id"] . "'";
    $result = mysqli_query($conn, $query);
    mysqli_close($conn);
    if ($result) echo "<div id='insert-success' class='insert-success'>Success</div><script>hideInsertSuccessBox();</script>";
    else echo "<div id='insert-error' class='insert-error'>Connection Error!</div><script>hideInsertErrorBox();</script>";
}

if (isset($_POST) && isset($_POST["update_product"]) && isset($_SESSION["product_id"])) {
    if (checkInputValues()) {
        $productName = $_POST["name"];
        $productBuyPrice = $_POST["buy_price"];
        $productQuantity = $_POST["quantity"];
        $productTax = $_POST["tax"];
        $productDiscount = $_POST["discount"];
        $productSellingPrice = $_POST["selling_price"];
        require_once("DB.php");
        $query = "UPDATE products SET `name`='".$productName."', `buy_price`='".$productBuyPrice."', `quantity`='$productQuantity', `tax`='".$productTax."', `discount`='$productDiscount', `selling_price`='".$productSellingPrice."' WHERE product_id='" . $_SESSION["product_id"] . "'";
        $result = mysqli_query($conn, $query);
        mysqli_close($conn);
        if ($result) {
            echo "<div id='insert-success' class='insert-success'>Success</div><script>hideInsertSuccessBox();</script>";
            echo "<script>showLastProductBox(); setInputFieldsAfterError('last-', " . $_SESSION["product_id"] . ", '" . $productName . "', '" . $productBuyPrice . "', '" . $productQuantity . "', '" . $productTax . "', '" . $productDiscount . "', '" . $productSellingPrice . "');</script>";
        } else echo "<div id='insert-error' class='insert-error'>Connection Error!</div><script>hideInsertErrorBox();</script>";
    }
    else echo "<div id='insert-error' class='insert-error'>Check Fields!</div><script>showLastProductBox(); setInputFieldsAfterError('last-', " . $_SESSION["product_id"] . ", '" . $_POST["name"] . "', '" . $_POST["buy_price"] . "', '" . $_POST["quantity"] . "', '" . $_POST["tax"] . "', '" . $_POST["discount"] . "', '" . $_POST["selling_price"] . "'); hideInsertErrorBox();</script>";
}

?>