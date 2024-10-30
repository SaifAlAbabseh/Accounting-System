//---------------- Variables Section ----------------//

let currentPosition = 0;
let totalRows = 0;
let currentPageNumOfRows = 0;
let numberOfRowsToShow = 10;
let numberOfPagesAllowedToShow = 4;
let numberOfPages = 1;
let searchCriteriaHeader = document.getElementById("search-criteria-header-menu").value;
let searchCriteriaValue = document.getElementById("search-criteria-value-field").value;
let searchCriteriaHeaderMenu = document.getElementById("search-criteria-header-menu");
let searchCriteriaValueField = document.getElementById("search-criteria-value-field");
let adminResultsBody = document.getElementById("admins-results");
let searchSubmitButton = document.getElementById("filter-submit-button");
let addNewAdminButtonLink = document.getElementById("add-new-admin-button-link");
let addNewAdminOuterBox = document.getElementById("add-new-admin-outer");
let addNewAdminHideButton = document.getElementById("hide-button");
let wrapper = document.getElementById("wrapper");
let leftArrow = document.getElementById("left-arrow");
let rightArrow = document.getElementById("right-arrow");
let clearFilterButton = document.getElementById("filter-clear-button");
let paginationPageNumbersParentBox = document.getElementById("pagination-page-numbers");
let exportButton = document.getElementById("export-button");
let adminInfoArr = Array("admin_id", "admin_name", "admin_password", "is_super_admin", "has_insert_privilege", "has_view_edit_privilege");
let adminsTotalLabel = document.getElementById("admins-total");
let currentPageAdminsTotal = document.getElementById("current-page-admins-total");

//---------------- Functions Section ----------------//

function getAdmins(searchCriteriaHeader, searchCriteriaValue, numOfRows, startPosition) {
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "api/getAdmins.php?searchCriteriaHeader=" + searchCriteriaHeader + "&searchCriteriaValue=" + searchCriteriaValue + "&numOfRows=" + numOfRows + "&startPosition=" + startPosition, false);
    xmlhttp.setRequestHeader("auth_token", authToken);
    xmlhttp.setRequestHeader("admin_id", adminId);
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == XMLHttpRequest.DONE) {
            if (this.status == 200) {
                let response = JSON.parse(this.responseText);
                totalRows = response["meta_data"]["total"];
                numberOfPages = Math.ceil(totalRows / numberOfRowsToShow);
                clearPaginationNumbers();
                buildPaginationPageNumbers(numberOfPages);
                currentPageNumOfRows = response["meta_data"]["available"];
                for (let i = 0; i < response["admins"].length; i++) {
                    let row = document.createElement("tr");
                    row.classList.add("admin-result-row");
                    if ((i + 1) % 2 == 0) row.classList.add("admin-result-row-even");
                    else row.classList.add("admin-result-row-odd");
                    for (let j = 0; j < adminInfoArr.length; j++) {
                        let removeAdminButton = "";
                        if (j == 0) removeAdminButton = "<button class='remove-admin-button' onclick=removeAdmin('" + response["admins"][i]["admin_id"] + "')>X</button> ";
                        if (adminInfoArr[j] == "is_super_admin" || adminInfoArr[j] == "has_insert_privilege" || adminInfoArr[j] == "has_view_edit_privilege") {
                            let showedValue = response["admins"][i][adminInfoArr[j]];
                            let otherValue = (showedValue == "1") ? "0" : "1";
                            row.innerHTML += "<td style='width: 100px'><div class='admin-result-column'><select style='width: 100px; text-align: center' onchange=adminValueChanged('" + response["admins"][i]["admin_id"] + "','" + adminInfoArr[j] + "',this.value) class='filters'><option value='" + showedValue + "'>" + showedValue + "</option><option value='" + otherValue + "'>" + otherValue + "</option>" + response["admins"][i][adminInfoArr[j]] + "</select></div></td>";
                        }
                        else row.innerHTML += "<td><div class='admin-result-column'>" + removeAdminButton + response["admins"][i][adminInfoArr[j]] + "</div></td>";
                    }
                    adminResultsBody.appendChild(row);
                }
                adminsTotalLabel.innerHTML = "Admins Total: " + totalRows;
                currentPageAdminsTotal.innerHTML = "Current Page Admins Total: " + currentPageNumOfRows;
            }
            else {
                if (this.status == 401) {

                }
                else {
                    document.write("Server Error, try again later please.");
                }
            }
        }
    };
    xmlhttp.send();
}

function clearResultBody() {
    adminResultsBody.innerHTML = "";
}

function adminValueChanged(adminId, privName, privValue) {
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.open("PUT", "api/updateAdmin.php?adminId=" + adminId + "&stateName=" + privName + "&stateValue=" + privValue, true);
    xmlhttp.setRequestHeader("auth_token", authToken);
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == XMLHttpRequest.DONE)
            if (this.status != 204) document.write("Server Error, try again later please.");
    };
    xmlhttp.send();
}

function hideInsertErrorBox() {
    setTimeout(function () {
        document.getElementById('insert-error').style.display = 'none';
    }, 3000);
}

function showAddNewAdminBox() {
    wrapper.classList.add("wrapper-disabled");
    addNewAdminOuterBox.style.display = "block";
}

function hideAddNewAdminBox() {
    wrapper.classList.remove("wrapper-disabled");
    addNewAdminOuterBox.style.display = "none";
}

function removeAdmin(adminId) {
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.open("DELETE", "api/removeAdmin.php?adminId=" + adminId, true);
    xmlhttp.setRequestHeader("auth_token", authToken);
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == XMLHttpRequest.DONE) {
            if (this.status == 204) {
                clearResultBody();
                getAdmins(searchCriteriaHeader, searchCriteriaValue, numberOfRowsToShow, currentPosition);
            }
            else document.write("Server Error, try again later please.");
        }
    };
    xmlhttp.send();
}

function resetResults() {
    currentPosition = 0;
    totalRows = 0;
    currentPageNumOfRows = 0;
    leftArrow.classList.add("arrow-disabled");
}

function clearPaginationNumbers() {
    paginationPageNumbersParentBox.innerHTML = "";
}

function buildPaginationPageNumbers(numberOfPages) {
    for (let i = 1; i <= numberOfPages; i++) {
        let newNumberElement = document.createElement("button");
        newNumberElement.innerHTML = "" + i;
        newNumberElement.id = "number_" + i;
        newNumberElement.classList.add("pagination-page-number");
        newNumberElement.addEventListener("click", function () {
            clearResultBody();
            if (i - 1 > currentPosition) currentPosition += ((i - 1) - currentPosition);
            else currentPosition -= (currentPosition - (i - 1));
            getAdmins(searchCriteriaHeader, searchCriteriaValue, numberOfRowsToShow, currentPosition);
            if (currentPosition == numberOfPages - 1) {
                rightArrow.classList.add("arrow-disabled");
                leftArrow.classList.remove("arrow-disabled");
            }
            else if (currentPosition == 0) {
                rightArrow.classList.remove("arrow-disabled");
                leftArrow.classList.add("arrow-disabled");
            }
            else {
                rightArrow.classList.remove("arrow-disabled");
                leftArrow.classList.remove("arrow-disabled");
            }
            for (let j = 1; j <= numberOfPages; j++) {
                if (i == j) document.getElementById("number_" + j).classList.add("selected-page-number");
                else document.getElementById("number_" + j).classList.remove("selected-page-number");
            }
        });
        if (i - 1 == currentPosition) newNumberElement.classList.add("selected-page-number");
        paginationPageNumbersParentBox.appendChild(newNumberElement);
        if ((numberOfPages - numberOfPagesAllowedToShow >= 2) && i == numberOfPagesAllowedToShow) {
            let dotsElement = document.createElement("label");
            dotsElement.innerHTML = "...";
            dotsElement.classList.add("pagination-page-numbers-compliment");
            paginationPageNumbersParentBox.appendChild(dotsElement);
        }
        else if ((numberOfPages - numberOfPagesAllowedToShow >= 2) && (i > numberOfPagesAllowedToShow && i < numberOfPages)) {
            newNumberElement.style.display = "none";
        }
    }
}

function getAllAdminsAndExport() {
    let filters = "?searchCriteriaHeader=" + searchCriteriaHeader + "&searchCriteriaValue=" + searchCriteriaValue;
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "api/getAllAdmins.php" + filters, true);
    xmlhttp.setRequestHeader("auth_token", authToken);
    xmlhttp.setRequestHeader("admin_id", adminId);
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == XMLHttpRequest.DONE) {
            if (this.status == 200) {
                let response = JSON.parse(this.responseText);
                if (response.length > 0) downloadCSV(response, "data.csv");
            }
            else
                document.write("Server Error, try again later please.");
        }
    };
    xmlhttp.send();
}

//---------------- Listeners Section ----------------//

window.addEventListener("load", function () {
    getAdmins(searchCriteriaHeader, searchCriteriaValue, numberOfRowsToShow, currentPosition);
    leftArrow.classList.add("arrow-disabled");
    if (currentPageNumOfRows == totalRows) rightArrow.classList.add("arrow-disabled");
});

searchSubmitButton.addEventListener("click", function () {
    searchCriteriaHeader = document.getElementById("search-criteria-header-menu").value;
    searchCriteriaValue = document.getElementById("search-criteria-value-field").value;
    clearResultBody();
    resetResults();
    getAdmins(searchCriteriaHeader, searchCriteriaValue, numberOfRowsToShow, currentPosition);
    if (currentPageNumOfRows == totalRows) rightArrow.classList.add("arrow-disabled");
    else rightArrow.classList.remove("arrow-disabled");
});

clearFilterButton.addEventListener("click", function () {
    clearResultBody();
    resetResults();
    searchCriteriaHeaderMenu.selectedIndex = 0;
    searchCriteriaValueField.value = "";
    searchCriteriaHeader = document.getElementById("search-criteria-header-menu").value;
    searchCriteriaValue = document.getElementById("search-criteria-value-field").value;
    getAdmins(searchCriteriaHeader, searchCriteriaValue, numberOfRowsToShow, currentPosition);
    if (currentPageNumOfRows == totalRows) rightArrow.classList.add("arrow-disabled");
    else rightArrow.classList.remove("arrow-disabled");
});

leftArrow.addEventListener("click", function () {
    clearResultBody();
    getAdmins(searchCriteriaHeader, searchCriteriaValue, numberOfRowsToShow, --currentPosition);
    if (currentPosition == 0) leftArrow.classList.add("arrow-disabled");
    rightArrow.classList.remove("arrow-disabled");
});

rightArrow.addEventListener("click", function () {
    clearResultBody();
    getAdmins(searchCriteriaHeader, searchCriteriaValue, numberOfRowsToShow, ++currentPosition);
    if (currentPosition + 1 == numberOfPages) rightArrow.classList.add("arrow-disabled");
    leftArrow.classList.remove("arrow-disabled");
});

addNewAdminButtonLink.addEventListener("click", showAddNewAdminBox);

addNewAdminHideButton.addEventListener("click", hideAddNewAdminBox);

exportButton.addEventListener("click", getAllAdminsAndExport);