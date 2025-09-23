$(function () {
  // Holds the name that was last accepted via autocomplete select()
  let selectedName = null;

  $("#userName").autocomplete({
    source: function (request, response) {
      $.getJSON(
        "/CustodianManagement/php/auto_suggest/assign_trans/non_consumable/search_user.php",
        { term: request.term },
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
      // mark this exact name as the "selected" one
      selectedName = ui.item.value;

      // Autofill and lock the fields
      $("#userName").val(ui.item.value);                  // name
      $("#userId").val(ui.item.user_id).prop("readonly", true);     // id locked
      $("#userDept").val(ui.item.department).prop("readonly", true); // dept locked
       $("#userId, #userDept").css("background-color", "#d4edda"); // light green

      // prevent default replacement behavior
      return false;
    }
  });
  // Detect manual changes (typing/erasing in name field)
  $("#userName").on("input", function () {
    var currentVal = $(this).val().trim();
    if (currentVal === "") {
      // Reset everything if cleared
      $("#userId, #userDept")
        .val("")
        .prop("readonly", false)
        .css("background-color", ""); // back to default
    } else {
      // Optional: remove highlight if typing something not from DB
      $("#userId, #userDept").css("background-color", "");
    }
});

  // If user edits the name input, clear autofill when it no longer matches selectedName
  $("#userName").on("input", function () {
    const cur = $(this).val();
    // If there's no selectedName (user didn't select from list) do nothing
    if (!selectedName) {
      // ensure ID/Dept are editable for manual entry
      $("#userId, #userDept").prop("readonly", false);
      return;
    }

    // If the current typed value no longer equals the previously selected name:
    if (cur !== selectedName) {
      // clear the selection and the filled fields, and unlock them
      selectedName = null;
      $("#userId").val("").prop("readonly", false);
      $("#userDept").val("").prop("readonly", false);
    }
    // If cur === selectedName, keep fields as-is (user restored it exactly)
  });

  // Optional: when the page loses focus or form is submitted,
  // you could clear selectedName or validate it as needed
});
