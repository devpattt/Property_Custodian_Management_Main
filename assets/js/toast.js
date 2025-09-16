function showToast(message, type = "success") {
  const container = document.getElementById("toast-container");
  if (!container) return;

  // create toast element
  let toast = document.createElement("div");
  toast.className = "toast " + type;
  toast.innerHTML = message;

  container.appendChild(toast);

  // auto-remove after 4s
  setTimeout(() => {
    toast.remove();
  }, 4000);
}
