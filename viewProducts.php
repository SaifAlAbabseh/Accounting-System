<?php
ini_set('display_errors', 0);

session_start();
if (!(isset($_SESSION) && $_SESSION["admin_id"])) header("Location:./");
else if(!(isset($_SESSION["has_view_edit_prev"]) && $_SESSION["has_view_edit_prev"] == "1")) header("Location:main.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Products</title>
    <link rel="stylesheet" href="styles/viewProducts.css">
    <script>
        var authToken = "<?php echo $_SESSION['auth_token']; ?>";
    </script>
</head>

<body>
    <div class="go-back-outer">
        <a class="go-back" href="main.php">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" width="100" height="100" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                <path d="M310.968,118.356H171.661V58.575L33.498,138.357l138.164,79.781v-59.781h139.307  c70.323,0,127.534,57.211,127.534,127.534c0,70.322-57.211,127.534-127.534,127.534H148.333v40h162.635  c92.378,0,167.534-75.155,167.534-167.534C478.502,193.512,403.346,118.356,310.968,118.356z" />
            </svg>
        </a>
    </div>
    <div class="filter-main-box">
        <div class="filter-method-box">
            <b>Filter By ASC/DESC</b>
            <div class="filter-method-inner-box" id="filter-method-bar">
                <div class="filter-method-inner-circle" id="filter-method-circle"></div>
            </div>
            <b>Filter By Text</b>
        </div>
        <div class="filters-box">
            <h2>Filter:</h2>
            <div id="filter-inner-box">
                <select id="header-filters" class="filters">
                    <option value="product_id"></option>
                </select>
                <select id="value-filters" class="filters">
                    <option value="DESC"></option>
                    <option value="ASC">Low to High</option>
                    <option value="DESC">High to Low</option>
                </select>
                <div id="filter-by-text-box">
                    <input type="text" id="filter-by-text-field" class="input-field" autocomplete="off">
                    <div class="filter-exact-box">
                        <label for="filter-by-text-checkbox">Exact?</label>
                        <input type="checkbox" id="filter-by-text-checkbox" class="checkbox">
                    </div>
                </div>
            </div>
            <button id="submit-filter-button" class="button">GO</button>
            <button id="clear-filter-button" class="button">Clear</button>
        </div>
    </div>
    <div class="export-box">
        <button class="button export-button" id="export-button">
            <svg class="export-svg" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="50" height="50" viewBox="0 0 50 50">
                <path d="M 28.875 0 C 28.855469 0.0078125 28.832031 0.0195313 28.8125 0.03125 L 0.8125 5.34375 C 0.335938 5.433594 -0.0078125 5.855469 0 6.34375 L 0 43.65625 C -0.0078125 44.144531 0.335938 44.566406 0.8125 44.65625 L 28.8125 49.96875 C 29.101563 50.023438 29.402344 49.949219 29.632813 49.761719 C 29.859375 49.574219 29.996094 49.296875 30 49 L 30 44 L 47 44 C 48.09375 44 49 43.09375 49 42 L 49 8 C 49 6.90625 48.09375 6 47 6 L 30 6 L 30 1 C 30.003906 0.710938 29.878906 0.4375 29.664063 0.246094 C 29.449219 0.0546875 29.160156 -0.0351563 28.875 0 Z M 28 2.1875 L 28 6.53125 C 27.867188 6.808594 27.867188 7.128906 28 7.40625 L 28 42.8125 C 27.972656 42.945313 27.972656 43.085938 28 43.21875 L 28 47.8125 L 2 42.84375 L 2 7.15625 Z M 30 8 L 47 8 L 47 42 L 30 42 L 30 37 L 34 37 L 34 35 L 30 35 L 30 29 L 34 29 L 34 27 L 30 27 L 30 22 L 34 22 L 34 20 L 30 20 L 30 15 L 34 15 L 34 13 L 30 13 Z M 36 13 L 36 15 L 44 15 L 44 13 Z M 6.6875 15.6875 L 12.15625 25.03125 L 6.1875 34.375 L 11.1875 34.375 L 14.4375 28.34375 C 14.664063 27.761719 14.8125 27.316406 14.875 27.03125 L 14.90625 27.03125 C 15.035156 27.640625 15.160156 28.054688 15.28125 28.28125 L 18.53125 34.375 L 23.5 34.375 L 17.75 24.9375 L 23.34375 15.6875 L 18.65625 15.6875 L 15.6875 21.21875 C 15.402344 21.941406 15.199219 22.511719 15.09375 22.875 L 15.0625 22.875 C 14.898438 22.265625 14.710938 21.722656 14.5 21.28125 L 11.8125 15.6875 Z M 36 20 L 36 22 L 44 22 L 44 20 Z M 36 27 L 36 29 L 44 29 L 44 27 Z M 36 35 L 36 37 L 44 37 L 44 35 Z"></path>
            </svg>
            Export
        </button>
    </div>
    <table border="1" class="results-box">
        <caption>
            <label id="products-total" class="table-caption"></label>
            &nbsp;&nbsp;
            <label id="current-page-products-total" class="table-caption"></label>
        </caption>
        <thead class="results-head">
            <tr>
                <th>
                    <h3>Product_ID</h3>
                </th>
                <th>
                    <h3>Admin_ID</h3>
                </th>
                <th>
                    <h3>Name</h3>
                </th>
                <th>
                    <h3>Buy_Price</h3>
                </th>
                <th>
                    <h3>Quantity</h3>
                </th>
                <th>
                    <h3>Tax</h3>
                </th>
                <th>
                    <h3>Discount</h3>
                </th>
                <th>
                    <h3>Selling_Price</h3>
                </th>
                <th>
                    <h3>Date</h3>
                </th>
            </tr>
        </thead>
        <tbody class="results-body" id="results-body">
        </tbody>
    </table>
    <div class="results-footer">
        <button id="left-arrow" class="shift-arrow">&lt;</button>
        <label class="page-number-label">Page:</label>
        <div class="pagination-page-numbers" id="pagination-page-numbers">
        </div>
        <button id="right-arrow" class="shift-arrow">&gt;</button>
    </div>


    <script src="scripts/viewProducts.js"></script>
    <script src="scripts/convertJSONToCSV.js"></script>
</body>

</html>