let scheduleDates = [];
let scheduleData = {}; // Store complete schedule data
let pendingScheduleData = null; 

const dateObj = new Date();
let currentMonth = dateObj.getMonth();
let currentYear = dateObj.getFullYear();

const months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
const days = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];

function renderCalendar() {
    const calendar = document.getElementById("calendar");
    calendar.innerHTML = "";

    days.forEach(d => {
        const div = document.createElement("div");
        div.className = "day-name";
        div.innerText = d;
        calendar.appendChild(div);
    });

    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const lastDate = new Date(currentYear, currentMonth + 1, 0).getDate();

    for(let i = 0; i < firstDay; i++){
        const div = document.createElement("div");
        calendar.appendChild(div);
    }

    for(let d = 1; d <= lastDate; d++){
        const div = document.createElement("div");
        div.className = "date";
        const fullDate = `${currentYear}-${String(currentMonth+1).padStart(2,"0")}-${String(d).padStart(2,"0")}`;
        div.innerText = d;

        if(scheduleDates.includes(fullDate)){
            div.classList.add("scheduled");
            
            div.classList.add("date-tooltip");
            
            const tooltipDiv = document.createElement("div");
            tooltipDiv.className = "tooltip-text";
    
            const scheduleInfo = scheduleData[fullDate];
            if (scheduleInfo) {
                if (Array.isArray(scheduleInfo)) {
                    tooltipDiv.innerHTML = scheduleInfo.map(info => 
                        `<strong>Asset:</strong> ${info.asset}<br>
                         <strong>Type:</strong> ${info.type}<br>
                         <strong>Frequency:</strong> ${info.frequency}<br>
                         <strong>Personnel:</strong> ${info.personnel}`
                    ).join('<hr style="margin: 8px 0; border: 1px solid rgba(255,255,255,0.3);">');
                } else {
                    tooltipDiv.innerHTML = 
                        `<strong>Asset:</strong> ${scheduleInfo.asset}<br>
                         <strong>Type:</strong> ${scheduleInfo.type}<br>
                         <strong>Frequency:</strong> ${scheduleInfo.frequency}<br>
                         <strong>Personnel:</strong> ${scheduleInfo.personnel}`;
                }
            } else {
                tooltipDiv.innerHTML = '<strong>Scheduled Maintenance</strong><br>Details not available';
            }
            
            div.appendChild(tooltipDiv);
        }
        calendar.appendChild(div);
    }

    document.getElementById("monthYear").innerText = `${months[currentMonth]} ${currentYear}`;
}

function prevMonth(){
    currentMonth--;
    if(currentMonth < 0){
        currentMonth = 11;
        currentYear--;
    }
    renderCalendar();
}

function nextMonth(){
    currentMonth++;
    if(currentMonth > 11){
        currentMonth = 0;
        currentYear++;
    }
    renderCalendar();
}

function loadSchedules() {
    fetch('get_schedules.php')
        .then(res => res.json())
        .then(data => {
            scheduleDates = [];
            scheduleData = {};
            
            data.forEach(item => {
                let dateKey, scheduleInfo;
                
                if (typeof item === 'string') {
                    dateKey = item;
                    scheduleInfo = { asset: 'N/A', type: 'N/A', frequency: 'N/A', personnel: 'N/A' };
                } else {
                    dateKey = item.start_date || item.date;
                    scheduleInfo = {
                        asset: item.asset || 'N/A',
                        type: item.type || 'N/A', 
                        frequency: item.frequency || 'N/A',
                        personnel: item.personnel || 'N/A'
                    };
                }
                
                if (!scheduleDates.includes(dateKey)) {
                    scheduleDates.push(dateKey);
                }
                
                if (scheduleData[dateKey]) {
                    if (Array.isArray(scheduleData[dateKey])) {
                        scheduleData[dateKey].push(scheduleInfo);
                    } else {
                        scheduleData[dateKey] = [scheduleData[dateKey], scheduleInfo];
                    }
                } else {
                    scheduleData[dateKey] = scheduleInfo;
                }
            });
            
            renderCalendar();
        })
        .catch(err => {
            console.error("Error fetching schedules:", err);
            scheduleDates = ['2025-09-15', '2025-09-22', '2025-09-30'];
            scheduleData = {
                '2025-09-15': { asset: 'Generator A1', type: 'Preventive', frequency: 'Monthly', personnel: 'John Doe' },
                '2025-09-22': { asset: 'HVAC System', type: 'Corrective', frequency: 'As Needed', personnel: 'Jane Smith' },
                '2025-09-30': { asset: 'Elevator B', type: 'Inspection', frequency: 'Quarterly', personnel: 'Mike Johnson' }
            };
            renderCalendar();
        });
}

function saveSchedule() {
    const asset = document.getElementById('asset').value.trim();
    const type = document.getElementById('type').value;
    const frequency = document.getElementById('frequency').value;
    const personnel = document.getElementById('personnel').value.trim();
    const date = document.getElementById('date').value;

    if (!asset || !type || !frequency || !personnel || !date) {
        showModal("Please fill out all fields before saving.");
        return;
    }

    pendingScheduleData = { asset, type, frequency, personnel, date };

    document.getElementById('confirmMessage').innerHTML = 
        `<strong>Asset:</strong> ${asset}<br>
         <strong>Type:</strong> ${type}<br>
         <strong>Frequency:</strong> ${frequency}<br>
         <strong>Personnel:</strong> ${personnel}<br>
         <strong>Date:</strong> ${date}`;
    
    document.getElementById('confirmModal').style.display = "block";
}

function confirmSchedule() {
    if (!pendingScheduleData) return;

    const { asset, type, frequency, personnel, date } = pendingScheduleData;

    fetch('save_schedule.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ asset, type, frequency, personnel, date })
    })
    .then(res => res.json())
    .then(data => {
        closeConfirmModal();
        
        if (data.status === "success") {
            // Update local data
            if (!scheduleDates.includes(date)) {
                scheduleDates.push(date);
            }
            
            const newScheduleInfo = { asset, type, frequency, personnel };
            if (scheduleData[date]) {
                if (Array.isArray(scheduleData[date])) {
                    scheduleData[date].push(newScheduleInfo);
                } else {
                    scheduleData[date] = [scheduleData[date], newScheduleInfo];
                }
            } else {
                scheduleData[date] = newScheduleInfo;
            }
            
            renderCalendar();

            document.getElementById('asset').value = '';
            document.getElementById('type').value = '';
            document.getElementById('frequency').value = '';
            document.getElementById('personnel').value = '';
            document.getElementById('date').value = '';

            showToast("✅ Maintenance schedule saved successfully!");
        } else {
            showToast("⚠️ " + data.message, true);
        }
    })
    .catch(err => {
        closeConfirmModal();
        showToast("⚠️ Error: " + err.message, true);
        console.error("Save error:", err);
    });

    pendingScheduleData = null;
}

function closeConfirmModal() {
    document.getElementById('confirmModal').style.display = "none";
    pendingScheduleData = null;
}

function showModal(message) {
    document.getElementById('modalMessage').innerText = message;
    document.getElementById('modalAlert').style.display = "block";
}

function closeModal() {
    document.getElementById('modalAlert').style.display = "none";
}

function showToast(msg, isError = false) {
    const toast = document.getElementById("toast");
    toast.innerText = msg;
    toast.className = isError ? "show error" : "show";
    setTimeout(() => { 
        toast.className = toast.className.replace("show", ""); 
    }, 3000);
}

window.onclick = function(event) {
    const alertModal = document.getElementById('modalAlert');
    const confirmModal = document.getElementById('confirmModal');
    
    if (event.target == alertModal) {
        closeModal();
    }
    if (event.target == confirmModal) {
        closeConfirmModal();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    renderCalendar();
    loadSchedules();
});