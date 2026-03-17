let deleteTarget = {
  id: null,
  element: null
};

function model_cancle(ids, element, number) {
  deleteTarget.id = ids;
  deleteTarget.element = element;

  document.getElementById("deletePhoneNumber").innerText = "+" + number;

  const deleteModal = new bootstrap.Modal(document.getElementById('deleteNumberModal'));
  deleteModal.show();
}

// function close_btn() {
//   var modals = document.getElementById("my_model");
//   modals.classList.add("hidden");
// }

document.getElementById("confirmDeleteBtn").addEventListener("click", function () {
  const order_id = deleteTarget.id;
  const element = deleteTarget.element;

  const token = $("#token").val();
  const params = { token: token, order_id: order_id };

  $("#" + element)
  .prop("disabled", true)
  .html(
    '<span class="spinner-border spinner-border-sm me-2"></span>Cancel'
  );

  $.ajax({
    type: "GET",
    url: "api/service/cancelNumber",
    data: params,
    error: function (e) {
        console.log(e);
        toast.error("An error occurred");

        $("#" + element).html(
            "<span class='ri-delete-bin-line me-2'></span>"
        ).prop("disabled", false);
    },
    success: function (data) {
      $("#" + element).html(
        "<span class='ri-delete-bin-line me-2'></span>"
      ).prop("disabled", false);

      const json = JSON.parse(data);

      toast.success(json.message);

      if (json.status === "200") {
        checkOrder(); // Refresh active numbers
      }

      user_balance(token);

      // Close the modal after the request
      const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteNumberModal'));
      deleteModal.hide();
    }
  });
});

