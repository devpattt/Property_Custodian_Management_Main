function searchAssets() {
    let input = document.getElementById("search").value.toLowerCase();
    let rows = document.querySelectorAll("table tbody tr");
    rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(input) ? "" : "none";
    });
}