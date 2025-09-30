let currentForm = null;
const updateModalEl = document.getElementById("updateModal");
const confirmUpdateBtn = document.getElementById("confirmUpdate");
const toastEl = document.getElementById("updateToast");

const updateModal = new bootstrap.Modal(updateModalEl);
const updateToast = new bootstrap.Toast(toastEl, { delay: 3000, autohide: true });

// ---------------- Enable/disable update button ----------------
document.querySelectorAll('form select[name="status"]').forEach(select => {
  const form = select.closest("form");
  const button = form.querySelector(".update-btn");
  const original = select.value; // original status comes from DB
  button.dataset.original = original;

  if (select.value === original) {
    button.disabled = true;
  }

  select.addEventListener("change", () => {
    button.disabled = (select.value === button.dataset.original);
  });
});

// ---------------- Update confirmation modal ----------------
document.querySelectorAll('.update-btn').forEach(btn => {
  btn.addEventListener("click", function (e) {
    if (btn.disabled) return; 
    e.preventDefault();
    currentForm = this.closest("form");
    updateModal.show();
  });
});

confirmUpdateBtn.addEventListener("click", () => {
  if (currentForm) {
    const formData = new FormData(currentForm);

    fetch(currentForm.action, {
      method: "POST",
      body: formData
    })
      .then(response => response.text())
      .then(() => {
        updateModal.hide();
        updateToast.show();

        const select = currentForm.querySelector('select[name="status"]');
        const button = currentForm.querySelector(".update-btn");

        button.dataset.original = select.value;
        button.disabled = true;

        if (select.value === "Completed") {
          button.disabled = true;
        }
      })
      .catch(err => {
        console.error("Update failed:", err);
      });
  }
});

// ---------------- Status filter ----------------
document.addEventListener("DOMContentLoaded", () => {
  const statusFilter = document.getElementById("statusFilter");
  const rows = document.querySelectorAll("#deliveryTable tbody tr");

  statusFilter.addEventListener("change", () => {
    const filter = statusFilter.value;
    rows.forEach(row => {
      const select = row.querySelector("select[name='status']");
      if (!select) return;
      const dbStatus = select.value;

      row.style.display = (filter === "All" || dbStatus === filter) ? "" : "none";
    });
  });
});
