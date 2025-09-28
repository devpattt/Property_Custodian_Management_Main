const rowsPerPage = 5;
let currentPage = 1;

// ❗ IMPORTANT: Update this path to your actual report script filename/location
const REPORT_SCRIPT_PATH = 'report.php'; 

// ---------------- Pagination ----------------
function paginateTable() {
    // ... (Your existing paginateTable function body)
    const rows = document.querySelectorAll("#deliveryTable tbody tr");
    const totalPages = Math.ceil(rows.length / rowsPerPage);
    rows.forEach((row, idx) => {
        row.style.display =
            idx >= (currentPage - 1) * rowsPerPage && idx < currentPage * rowsPerPage
                ? ""
                : "none";
    });
    renderPagination(totalPages, "pagination", (page) => {
        currentPage = page;
        paginateTable();
    });
}

function renderPagination(total, containerId, onClick) {
    // ... (Your existing renderPagination function body)
    const container = document.getElementById(containerId);
    if (!container) return; // Add a check in case pagination container doesn't exist
    container.innerHTML = "";
    for (let i = 1; i <= total; i++) {
        const btn = document.createElement("button");
        btn.innerText = i;
        btn.className = i === currentPage ? "active" : "";
        btn.onclick = () => onClick(i);
        container.appendChild(btn);
    }
}

// ---------------- Search ----------------
document.getElementById("search")?.addEventListener("keyup", function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#deliveryTable tbody tr");
    rows.forEach((r) => {
        r.style.display = r.innerText.toLowerCase().includes(filter) ? "" : "none";
    });
});

// ---------------- Sorting ----------------
document.querySelectorAll("#deliveryTable th").forEach((th) => {
    th.addEventListener("click", function () {
        let tbody = th.closest("table").querySelector("tbody");
        Array.from(tbody.querySelectorAll("tr"))
            .sort((a, b) => {
                let tdA = a.querySelector(`td:nth-child(${th.cellIndex + 1})`).innerText.trim();
                let tdB = b.querySelector(`td:nth-child(${th.cellIndex + 1})`).innerText.trim();
                return tdA.localeCompare(tdB, undefined, { numeric: true });
            })
            .forEach((tr) => tbody.appendChild(tr));
    });
});

// ---------------- View Toggle ----------------
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
    // ... (Your existing renderGrid function body)
    const grid = document.getElementById("gridContent");
    grid.innerHTML = "";
    const rows = document.querySelectorAll("#deliveryTable tbody tr");
    const totalPages = Math.ceil(rows.length / rowsPerPage);
    rows.forEach((row, idx) => {
        if (idx >= (currentPage - 1) * rowsPerPage && idx < currentPage * rowsPerPage) {
            let cells = row.querySelectorAll("td");
            grid.innerHTML += `
                <div class="card p-2 shadow-sm">
                    ${cells[0].innerHTML}
                    <h5>${cells[1].innerText}</h5>
                    <p><strong>Category:</strong> ${cells[2].innerText}</p>
                    <p>Qty: ${cells[3].innerText} ${cells[4].innerText}</p>
                    <p>${cells[5].innerText}</p>
                    <p>${cells[6].innerHTML}</p>
                </div>`;
        }
    });
    renderPagination(totalPages, "gridPagination", (page) => {
        currentPage = page;
        renderGrid();
    });
}

// ---------------- Report Modal Functions ----------------

// Function to generate the report content via AJAX and trigger printing
function generateReport(type) {
    let reportUrl = '';
    
    // Determine the URL and parameters (logic remains the same)
    if (type === 'month') {
        const monthInput = document.getElementById('reportMonthInput');
        const monthValue = monthInput ? monthInput.value : '';
        if (!monthValue) { alert('Please select a valid month.'); return; }
        reportUrl = `${REPORT_SCRIPT_PATH}?type=month&month_select=${monthValue}`;
    } else if (type === 'year') {
        const yearInput = document.getElementById('reportYearInput');
        const yearValue = yearInput ? yearInput.value : '';
        if (!yearValue) { alert('Please select a valid year.'); return; }
        reportUrl = `${REPORT_SCRIPT_PATH}?type=year&year_select=${yearValue}`;
    } else if (type === 'today' || type === 'week') {
        reportUrl = `${REPORT_SCRIPT_PATH}?type=${type}`;
    } else {
        return; 
    }

    // 1. Close the selection modals (if they are open)
    const allModals = document.querySelectorAll('.modal.show');
    allModals.forEach(modalEl => {
         const modalInstance = bootstrap.Modal.getInstance(modalEl);
         if (modalInstance) modalInstance.hide();
    });
    
    // 2. Fetch the report content
    fetch(reportUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(reportHtml => {
            // 3. Pass the content to the printing function
            printReport(reportHtml);
        })
        .catch(error => {
            console.error('Error fetching report:', error);
            alert('Failed to generate report. Check console for details.');
        });
}

/**
 * Creates a temporary, hidden iframe, injects the report HTML,
 * triggers printing, and then removes the iframe.
 * @param {string} htmlContent - The HTML fragment to print (the table).
 */
function printReport(htmlContent) {
    const iframe = document.createElement('iframe');
    iframe.style.display = 'none'; // Keep it hidden
    document.body.appendChild(iframe);
    
    const iframeDoc = iframe.contentWindow.document;

    // Use Bootstrap styles for formatting, and hide elements that shouldn't print
    const bootstrapCss = document.querySelector('link[href*="bootstrap.min.css"]')?.href || '';
    
    iframeDoc.open();
    iframeDoc.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Procurement Report</title>
            <link rel="stylesheet" href="${bootstrapCss}">
            <style>
                /* Style for a clean printout */
                body { font-family: sans-serif; padding: 20px; }
                h2 { text-align: center; }
                table { width: 100%; border-collapse: collapse; }
                /* Hide main page body content during print */
                @page { margin: 1cm; }
            </style>
        </head>
        <body>
            ${htmlContent}
        </body>
        </html>
    `);
    iframeDoc.close();

    // Wait for the iframe to load the content and CSS before printing
    iframe.onload = function() {
        try {
            // Trigger the native print dialog
            iframe.contentWindow.focus(); // Must focus before print
            iframe.contentWindow.print();
        } catch (e) {
            console.error("Print failed:", e);
        } finally {
            // Give the browser a moment to process the print dialog, then remove the iframe
            setTimeout(() => {
                document.body.removeChild(iframe);
            }, 1000); 
        }
    };
}


// ---------------- Initialization ----------------
document.addEventListener('DOMContentLoaded', function() {
    // Event listener for the simple buttons (Today/Week) in the main modal
    const simpleTriggers = document.querySelectorAll('#reportModal .report-trigger');
    simpleTriggers.forEach(button => {
        button.addEventListener('click', function() {
            const reportType = this.getAttribute('data-type'); 
            generateReport(reportType);
        });
    });
    
    // Initial call to set up the pagination/view
    paginateTable(); 
});