function openEditModal(button) {
  document.getElementById("editReferenceNo").value = button.getAttribute("data-reference_no");
  document.getElementById("editQuantity").value = button.getAttribute("data-quantity");

  document.getElementById("editModal").style.display = "block";
}

function closeModal() {
  document.getElementById("editModal").style.display = "none";
}