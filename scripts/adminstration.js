//---------------- Variables Section ----------------//

let searchCriteriaHeader = document.getElementById("search-criteria-header-menu").value;
let searchCriteriaValue = document.getElementById("search-criteria-value-field").value;
let adminResultsBody = document.getElementById("admins-results");
let searchSubmitButton = document.getElementById("filter-submit-button");
let addNewAdminButtonLink = document.getElementById("add-new-admin-button-link");
let addNewAdminOuterBox = document.getElementById("add-new-admin-outer");
let addNewAdminHideButton = document.getElementById("hide-button");
let wrapper = document.getElementById("wrapper");
let adminInfoArr = Array("admin_id", "admin_name", "admin_password", "is_super_admin", "has_insert_privilege", "has_view_edit_privilege");

//---------------- Functions Section ----------------//

function getAdmins() {
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "api/getAdmins.php?searchCriteriaHeader=" + searchCriteriaHeader + "&searchCriteriaValue=" + searchCriteriaValue, true);
    xmlhttp.setRequestHeader("auth_token", authToken);
    xmlhttp.setRequestHeader("admin_id", adminId);
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == XMLHttpRequest.DONE) {
            if (this.status == 200 || this.status == 204) {
                let response = JSON.parse(this.responseText);
                for(let i = 0; i < response["admins"].length; i++) {
                    let row = document.createElement("tr");
                    row.classList.add("admin-result-row");
                    if((i + 1) % 2 == 0) row.classList.add("admin-result-row-even");
                    else row.classList.add("admin-result-row-odd");
                    for(let j = 0; j < adminInfoArr.length; j++) {
                        let removeAdminButton = "";
                        if(j == 0) removeAdminButton = "<button class='remove-admin-button' onclick=removeAdmin('" + response["admins"][i]["admin_id"] + "')>X</button> ";
                        if(adminInfoArr[j] == "is_super_admin" || adminInfoArr[j] == "has_insert_privilege" || adminInfoArr[j] == "has_view_edit_privilege") {
                            let showedValue = response["admins"][i][adminInfoArr[j]];
                            let otherValue = (showedValue == "1")?"0":"1";
                            row.innerHTML += "<td style='width: 100px'><div class='admin-result-column'><select style='width: 100px; text-align: center' onchange=adminValueChanged('" + response["admins"][i]["admin_id"] + "','" + adminInfoArr[j] + "',this.value) class='filters'><option value='" + showedValue + "'>" + showedValue + "</option><option value='" + otherValue + "'>" + otherValue + "</option>" + response["admins"][i][adminInfoArr[j]] + "</select></div></td>";
                        }
                        else row.innerHTML += "<td><div class='admin-result-column'>" + removeAdminButton + response["admins"][i][adminInfoArr[j]] + "</div></td>";
                    }
                    adminResultsBody.appendChild(row);
                }
            }
            else {
                if(this.status == 401) {
                    
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
    xmlhttp.open("PUT", "api/updateAdmin.php?admin_id=" + adminId + "&state_name=" + privName + "&state_value=" + privValue, true);
    xmlhttp.setRequestHeader("auth_token", authToken);
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == XMLHttpRequest.DONE)
            if (this.status != 204) document.write("Server Error, try again later please.");
    };
    xmlhttp.send(); 
}

function hideInsertErrorBox() {
    setTimeout(function() {
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
    xmlhttp.onreadystatechange = function() {
        if(this.readyState == XMLHttpRequest.DONE) {
            if(this.status == 204) {
                clearResultBody();
                getAdmins();
            }
            else document.write("Server Error, try again later please.");
        }
    };
    xmlhttp.send();
}

//---------------- Listeners Section ----------------//

window.addEventListener("load", function() {
    getAdmins(searchCriteriaHeader, searchCriteriaValue);
});

searchSubmitButton.addEventListener("click", function() {
    searchCriteriaHeader = document.getElementById("search-criteria-header-menu").value;
    searchCriteriaValue = document.getElementById("search-criteria-value-field").value;
    clearResultBody();
    getAdmins(searchCriteriaHeader, searchCriteriaValue);
});

addNewAdminButtonLink.addEventListener("click", showAddNewAdminBox);

addNewAdminHideButton.addEventListener("click", hideAddNewAdminBox);