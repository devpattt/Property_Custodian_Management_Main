function openEditModal(button) {
  document.getElementById("editAssetTag").value = button.getAttribute("data-asset_tag");
  document.getElementById("editBox").value = button.getAttribute("data-box");
  document.getElementById("editQuantity").value = button.getAttribute("data-quantity");
  document.getElementById("editExpiration").value = button.getAttribute("data-expiration");

  document.getElementById("editModal").style.display = "block";
}

function closeModal() {
  document.getElementById("editModal").style.display = "none";
}