function openEditModal(button) {
  document.getElementById("editReferenceNo").value = button.getAttribute("data-reference_no");
  document.getElementById("editBox").value = button.getAttribute("data-box");
  document.getElementById("editQuantity").value = button.getAttribute("data-quantity");
  document.getElementById("editExpiration").value = button.getAttribute("data-expiration");

  document.getElementById("editModal").style.display = "block";
}

function closeModal() {
  document.getElementById("editModal").style.display = "none";
}