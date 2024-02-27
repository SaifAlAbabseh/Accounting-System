//---------------- Functions Section ----------------//

function hideMessageBox(type) {
    setTimeout(function() {
        document.getElementById('import-' + type).style.display = 'none';
    }, 3000);
}