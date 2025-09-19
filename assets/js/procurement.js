
  const rowsPerPage = 5;
  let currentPage = 1;

  function paginateTable() {
    const rows = document.querySelectorAll("#deliveryTable tbody tr");
    const totalPages = Math.ceil(rows.length / rowsPerPage);
    rows.forEach((row, idx) => {
      row.style.display = (idx >= (currentPage-1)*rowsPerPage && idx < currentPage*rowsPerPage) ? "" : "none";
    });
    renderPagination(totalPages, "pagination", (page)=>{ currentPage=page; paginateTable(); });
  }

  function renderPagination(total, containerId, onClick) {
    const container = document.getElementById(containerId);
    container.innerHTML = "";
    for (let i=1; i<=total; i++) {
      const btn = document.createElement("button");
      btn.innerText = i;
      btn.className = (i===currentPage ? "active":"");
      btn.onclick = ()=>onClick(i);
      container.appendChild(btn);
    }
  }

  // Search filter
  document.getElementById("search").addEventListener("keyup", function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#deliveryTable tbody tr");
    rows.forEach(r=>{
      r.style.display = r.innerText.toLowerCase().includes(filter) ? "" : "none";
    });
  });

  // Sorting
  document.querySelectorAll("#deliveryTable th").forEach(th=>{
    th.addEventListener("click", function(){
      let table = th.closest("table");
      let tbody = table.querySelector("tbody");
      Array.from(tbody.querySelectorAll("tr"))
        .sort((a,b)=>{
          let col = th.dataset.col;
          let tdA = a.querySelector(`td:nth-child(${th.cellIndex+1})`).innerText.trim();
          let tdB = b.querySelector(`td:nth-child(${th.cellIndex+1})`).innerText.trim();
          return tdA.localeCompare(tdB, undefined, {numeric:true});
        })
        .forEach(tr=>tbody.appendChild(tr));
    });
  });

  function toggleView() {
    let tableView = document.getElementById("tableView");
    let gridView = document.getElementById("gridView");
    if (tableView.style.display === "none") {
      tableView.style.display = "block";
      gridView.style.display = "none";
    } else {
      tableView.style.display = "none";
      gridView.style.display = "block";
      renderGrid();
    }
  }

  function renderGrid() {
    const grid = document.getElementById("gridContent");
    grid.innerHTML = "";
    const rows = document.querySelectorAll("#deliveryTable tbody tr");
    const totalPages = Math.ceil(rows.length / rowsPerPage);
    rows.forEach((row, idx)=>{
      if (idx >= (currentPage-1)*rowsPerPage && idx < currentPage*rowsPerPage) {
        let cells = row.querySelectorAll("td");
        grid.innerHTML += `
          <div class="card">
            ${cells[0].innerHTML}
            <h3>${cells[1].innerText}</h3>
            <p><strong>Category:</strong> ${cells[2].innerText}</p>
            <p>Qty: ${cells[3].innerText} ${cells[4].innerText}</p>
            <p>Supplier: ${cells[5].innerText}</p>
            <p>${cells[6].innerHTML}</p>
          </div>`;
      }
    });
    renderPagination(totalPages, "gridPagination", (page)=>{ currentPage=page; renderGrid(); });
  }

  paginateTable();

  function generateReport() {
  window.print();
}
