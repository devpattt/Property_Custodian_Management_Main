$(function () {
  // Holds the last accepted item name from autocomplete
  let selectedItem = null;

  $("#equipmentName").autocomplete({
    source: function (request, response) {
      $.getJSON(
        "/CustodianManagement/php/auto_suggest/assign_trans/non_consumable/search_asset.php",
        { term: request.term }, // send input term to backend
        function (data) {
          response(data);
        }
      );
    },
    minLength: 1,
    focus: function (event, ui) {
      // prevent replacing the input while navigating suggestions
      event.preventDefault();
    },
    select: function (event, ui) {
      // Mark this exact name as the "selected" one
      selectedItem = ui.item.value;

      // Autofill and lock the fields
      $("#equipmentName").val(ui.item.value); // item name
      $("#equipmentId").val(ui.item.asset_tag).prop("readonly", true); // lock asset tag
      $("#equipmentId").css("background-color", "#d4edda"); // light green highlight

      return false;
    }
  });

  // Detect manual changes in Item Name field
  $("#equipmentName").on("input", function () {
    var currentVal = $(this).val().trim();

    if (currentVal === "") {
      // Reset everything if cleared
      $("#equipmentId")
        .val("")
        .prop("readonly", false)
        .css("background-color", "");
    } else {
      // Remove highlight if typing something not from DB
      $("#equipmentId").css("background-color", "");
    }
  });

  // If user edits the name input, clear autofill when no longer matches selectedItem
  $("#equipmentName").on("input", function () {
    const cur = $(this).val();

    if (!selectedItem) {
      $("#equipmentId").prop("readonly", false);
      return;
    }

    if (cur !== selectedItem) {
      selectedItem = null;
      $("#equipmentId").val("").prop("readonly", false);
    }
  });
});
