$(function () {
  let selectedItem = null;

  $("#equipmentName").autocomplete({
    source: function (request, response) {
      $.getJSON(
        "/CustodianManagement/php/auto_suggest/assign_trans/consumable/search_asset.php",
        { term: request.term },
        function (data) {
          response(data);
        }
      );
    },
    minLength: 1,
    focus: function (event, ui) {
      event.preventDefault(); // don’t auto-replace while navigating
    },
    select: function (event, ui) {
      selectedItem = ui.item.value;

      // Autofill all fields
      $("#equipmentName").val(ui.item.value); // Item Name
      $("#equipmentId").val(ui.item.asset_tag).prop("readonly", true);
      $("#equipmentId").css("background-color", "#d4edda");

      $("#equipmentCategory").val(ui.item.category).prop("readonly", true);
      $("#equipmentCategory").css("background-color", "#d4edda");

      $("#expiration").val(ui.item.expiration).prop("readonly", true);
      $("#expiration").css("background-color", "#d4edda");

      return false;
    }
  });

  // Reset if cleared
  $("#equipmentName").on("input", function () {
    var currentVal = $(this).val().trim();

    if (currentVal === "") {
      $("#equipmentId, #equipmentCategory, #expiration")
        .val("")
        .prop("readonly", false)
        .css("background-color", "");
    }
  });

  // If user edits and it doesn’t match the selected autocomplete value
  $("#equipmentName").on("input", function () {
    const cur = $(this).val();

    if (!selectedItem) {
      $("#equipmentId, #equipmentCategory, #expiration").prop("readonly", false);
      return;
    }

    if (cur !== selectedItem) {
      selectedItem = null;
      $("#equipmentId, #equipmentCategory, #expiration")
        .val("")
        .prop("readonly", false)
        .css("background-color", "");
    }
  });
});
