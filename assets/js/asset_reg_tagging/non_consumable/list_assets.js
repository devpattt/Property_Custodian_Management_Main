
function openEditModal(button) {
  document.getElementById("editAssetTag").value = button.getAttribute("data-asset_tag");
  document.getElementById("editActive").value = button.getAttribute("data-active");
  document.getElementById("editInRepair").value = button.getAttribute("data-in_repair");
  document.getElementById("editDisposed").value = button.getAttribute("data-disposed");

  document.getElementById("editModal").style.display = "block";
}

function closeModal() {
  document.getElementById("editModal").style.display = "none";
}