//---------------- Imports Section ----------------//

import * as fileFunctions from '../scripts/viewProductsFiles.js';

//---------------- Variables Section ----------------//

let currentPosition = 0;
let currentRowsNumber = 0;
let totalRows = 0;
let currentPageNumOfRows = 0;
let currentFilterHeader = "product_id";
let currentFilterValue = "DESC";
let headersArray = Array("product_id", "admin_id", "product_name", "product_buy_price", "product_quantity", "product_tax", "product_discount", "product_selling_price", "product_sell_date");
let resultsBody = document.getElementById("results-body");
let leftArrow = document.getElementById("left-arrow");
let rightArrow = document.getElementById("right-arrow");
let submitFilterButton = document.getElementById("submit-filter-button");
let clearFilterButton = document.getElementById("clear-filter-button");
let filterMethodBar = document.getElementById("filter-method-bar");
let isFilter = false;
let filterMethod = false;
let isFilterExact = false;
let filterMethodCircle = document.getElementById("filter-method-circle");
let filterLeftMenu = document.getElementById("header-filters");
let filterRightMenu = document.getElementById("value-filters");
let filterByTextBox = document.getElementById("filter-by-text-box");
let filterByTextInputField = document.getElementById("filter-by-text-field");
let filterByTextExactCheckBox = document.getElementById("filter-by-text-checkbox");
let exportButton = document.getElementById("export-button");
let penSVG = "<svg id='pen-svg' class='pen-svg' width='15' height='15' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'><rect width='24' height='24' stroke='none' fill='#000000' opacity='0' /><g transform='matrix(1.05 0 0 1.05 12 12)'><path style='stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;' transform=' translate(-12.5, -11.5)' d='M 19.171875 2 C 18.448125 2 17.724375 2.275625 17.171875 2.828125 L 16 4 L 20 8 L 21.171875 6.828125 C 22.275875 5.724125 22.275875 3.933125 21.171875 2.828125 C 20.619375 2.275625 19.895625 2 19.171875 2 z M 14.5 5.5 L 3 17 L 3 21 L 7 21 L 18.5 9.5 L 14.5 5.5 z' stroke-linecap='round' /></g></svg>";

//---------------- Functions Section ----------------//

function getAllProductsAndExport() {
    let filters = "?filterMethod=" + filterMethod + "&filterBy=" + currentFilterHeader + "&filterCriteria=" + currentFilterValue + "&isFilterExact=" + isFilterExact;
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "api/getAllProducts.php" + filters, true);
    xmlhttp.setRequestHeader("auth_token", authToken);
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == XMLHttpRequest.DONE) {
            if (this.status == 200 || this.status == 204) {
                let response = JSON.parse(this.responseText);
                if (response.length > 0) fileFunctions.downloadCSV(response, "data.csv");
            }
            else
                document.write("Server Error, try again later please.");
        }
    };
    xmlhttp.send();
}

function getProducts(isCurrentNumOfRows, numOfRows, startPosition, subtract) {
    let filters = "";
    if (isFilter) filters = "&filterMethod=" + filterMethod + "&filterBy=" + currentFilterHeader + "&filterCriteria=" + currentFilterValue + "&isFilterExact=" + isFilterExact;
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "api/getProducts.php?numOfRows=" + numOfRows + "&startPosition=" + startPosition + filters, false);
    xmlhttp.setRequestHeader("auth_token", authToken);
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == XMLHttpRequest.DONE) {
            if (this.status == 200 || this.status == 204) {
                let wholeResponse = JSON.parse(this.responseText);
                let responseJSON = wholeResponse["products"];
                totalRows = wholeResponse["meta_data"]["total"];
                if (isCurrentNumOfRows) {
                    if (subtract) currentRowsNumber -= currentPageNumOfRows;
                    else currentRowsNumber += wholeResponse["meta_data"]["available"];
                }
                currentPageNumOfRows = wholeResponse["meta_data"]["available"];
                for (let i = 0; i < responseJSON.length; i++) {
                    let resultEditButton = document.createElement("a");
                    resultEditButton.href = "editProduct.php?product_id=" + responseJSON[i][headersArray[0]];
                    resultEditButton.classList.add("result-edit-button");
                    resultEditButton.innerHTML += penSVG;
                    let resultRow = document.createElement("tr");
                    resultRow.classList.add("result");
                    if ((i + 1) % 2 == 0) resultRow.classList.add("even-result");
                    else resultRow.classList.add("odd-result");
                    for (let j = 0; j < headersArray.length; j++)
                        resultRow.innerHTML += "<td class='results-body-element'>" + responseJSON[i][headersArray[j]] + "</td>";
                    resultRow.firstChild.insertBefore(resultEditButton, resultRow.firstChild.firstChild);
                    resultsBody.appendChild(resultRow);
                }
            }
            else
                document.write("Server Error, try again later please.");
        }
    };
    xmlhttp.send();
}

function clearResultsBody() {
    resultsBody.innerHTML = "";
}

function initFilters() {
    let headers = document.querySelectorAll("th h3");
    let optionBox = document.getElementById("header-filters");

    for (let i = 0; i < headers.length; i++) {
        let option = document.createElement("option");
        option.classList.add("filter-option");
        option.innerHTML = headers[i].innerHTML;
        option.value = headers[i].innerHTML.toLowerCase();
        optionBox.appendChild(option);
    }
}

function resetResults() {
    currentPosition = 0;
    currentRowsNumber = 0;
    totalRows = 0;
    currentPageNumOfRows = 0;
    leftArrow.style.display = "none";
}

//---------------- Listeners Section ----------------//

window.addEventListener("load", function () {
    initFilters();
    getProducts(true, 10, currentPosition, false);
    if (currentRowsNumber == totalRows) rightArrow.style.display = "none";
});

leftArrow.addEventListener("click", function () {
    clearResultsBody();
    getProducts(true, 10, --currentPosition, true);
    if (currentPosition == 0) leftArrow.style.display = "none";
    rightArrow.style.display = "inline-block";
});

rightArrow.addEventListener("click", function () {
    clearResultsBody();
    getProducts(true, 10, ++currentPosition, false);
    if (currentRowsNumber == totalRows) rightArrow.style.display = "none";
    leftArrow.style.display = "inline-block";
});

submitFilterButton.addEventListener("click", function () {
    let filterBy = filterLeftMenu.value;
    let filterCriteria;
    if (!filterMethod) filterCriteria = filterRightMenu.value;
    else {
        filterCriteria = filterByTextInputField.value;
        isFilterExact = filterByTextExactCheckBox.checked;
    }
    currentFilterHeader = filterBy;
    currentFilterValue = filterCriteria;
    clearResultsBody();
    resetResults();
    isFilter = true;
    getProducts(true, 10, currentPosition, false);
    if (currentRowsNumber == totalRows) rightArrow.style.display = "none";
    else rightArrow.style.display = "inline-block";
});

clearFilterButton.addEventListener("click", function () {
    clearResultsBody();
    resetResults();
    currentFilterHeader = "product_id";
    currentFilterValue = "DESC";
    if (filterMethod) filterMethodBar.click();
    filterLeftMenu.selectedIndex = 0;
    filterRightMenu.selectedIndex = 0;
    isFilter = false;
    getProducts(true, 10, currentPosition, false);
    if (currentRowsNumber == totalRows) rightArrow.style.display = "none";
    else rightArrow.style.display = "inline-block";
});

filterMethodBar.addEventListener("click", function () {
    if (!filterMethod) { //Go to right hand side
        filterMethod = true;
        filterMethodCircle.style.transform = "translateX(60px)";
        filterRightMenu.style.display = "none";
        filterByTextBox.style.display = "block";
    }
    else { //Go to left hand side
        filterMethod = false;
        filterMethodCircle.style.transform = "translateX(0px)";
        filterRightMenu.style.display = "block";
        filterByTextBox.style.display = "none";
    }
});

exportButton.addEventListener("click", getAllProductsAndExport);