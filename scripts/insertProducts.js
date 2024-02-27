//---------------- Functions Section ----------------//

function hideInsertErrorBox() {
    setTimeout(function() {
        document.getElementById('insert-error').style.display = 'none';
    }, 3000);
}

function hideInsertSuccessBox() {
    setTimeout(function() {
        document.getElementById('insert-success').style.display = 'none';
    }, 3000);
}

function setInputFieldsAfterError(lastProduct, isID, name, buyPrice, quantity, tax, discount, sellingPrice) {
    if(isID) document.getElementById("last-product-id-field").value = isID;
    document.getElementById(lastProduct + "name").value = name;
    document.getElementById(lastProduct + "buy-price").value = buyPrice;
    document.getElementById(lastProduct + "quantity").value = quantity;
    document.getElementById(lastProduct + "tax").value = tax;
    document.getElementById(lastProduct + "discount").value = discount;
    document.getElementById(lastProduct + "selling-price").value = sellingPrice;
}

function hideLastProductBox() {
    document.getElementById("last-inserted-product-box").style.display = "none";
}

function showLastProductBox() {
    document.getElementById("last-inserted-product-box").style.display = "block";
}

function goBackToViewProductsPage() {
    location.replace("./viewProducts.php");
}