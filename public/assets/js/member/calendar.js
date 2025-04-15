document.addEventListener("DOMContentLoaded", () => {
    buildCalendar();
    buttons();
});

// Extract query parameters from the URL
const urlParams = new URLSearchParams(window.location.search);
console.log(urlParams);
const trainerId = urlParams.get('id'); 
let currentMonth = parseInt(urlParams.get('month')) || new Date().getMonth() + 1; // Default to the current month
let currentYear = parseInt(urlParams.get('year')) || new Date().getFullYear(); // Default to the current year

const calendarBody = document.querySelector('.calendarBody');
const calendarHeader = document.querySelector('.calendar-header');
const monthYear = document.querySelector(".monthYear");
const weekDays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

const gotoBtn = document.querySelector(".gotoBtn");
const todayBtn = document.querySelector(".todayBtn");
const dateInput = document.querySelector(".date-input");

// Function to build the calendar body
function buildCalendar() {
    const firstDayOfMonth = new Date(currentYear, currentMonth - 1, 1);
    const dayInMonth = new Date(currentYear, currentMonth, 0).getDate(); // Number of days in the month
    const emptyDays = firstDayOfMonth.getDay(); // Day index of the first day (0-6)
    const dateToday = new Date().toISOString().split('T')[0];
    const monthYear = firstDayOfMonth.toLocaleDateString("en-us", { month: "long", year: "numeric" });

    // Calculate previous and next month/year
    let prevMonth = currentMonth - 1;
    let nextMonth = currentMonth + 1;
    let prevYear = currentYear;
    let nextYear = currentYear;

    if (prevMonth < 1) {
        prevMonth = 12;
        prevYear--;
    }
    if (nextMonth > 12) {
        nextMonth = 1;
        nextYear++;
    }

    // Update header
    calendarHeader.innerHTML = `
        <a class='prevMonth' href='?id=${trainerId}&month=${prevMonth}&year=${prevYear}' aria-label='Previous Month'><i class='ph ph-caret-circle-left'></i></a>
        <div class='monthYear'>${monthYear}</div>
        <a class='nextMonth' href='?id=${trainerId}&month=${nextMonth}&year=${nextYear}' aria-label='Next Month'><i class='ph ph-caret-circle-right'></i></a>
    `;

    // Build calendar table
    calendarBody.innerHTML = "";
    let row = document.createElement("tr");

    // Empty days before the first day of the month
    for (let i = 0; i < emptyDays; i++) {
        const emptyCell = document.createElement("td");
        emptyCell.classList.add("plain");
        row.appendChild(emptyCell);
    }

    // Calendar days
    for (let day = 1; day <= dayInMonth; day++) {
        if ((emptyDays + day - 1) % 7 === 0) {
            calendarBody.appendChild(row);
            row = document.createElement("tr");
        }

        const date = `${currentYear}-${String(currentMonth).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
        const dayCell = document.createElement("td");
        dayCell.classList.add("day");
        dayCell.dataset.date = date;
        dayCell.innerText = day;

        if (date === dateToday) {
            dayCell.classList.add("today");
        }

        row.appendChild(dayCell);
    }

    // Add remaining empty cells for the last week
    while (row.children.length < 7) {
        const emptyCell = document.createElement("td");
        emptyCell.classList.add("plain");
        row.appendChild(emptyCell);
    }
    calendarBody.appendChild(row);
}

function buttons(){
    if(todayBtn){
        todayBtn.addEventListener("click", ()=>{
            const today = new Date();
            currentMonth = today.getMonth() + 1;
            currentYear = today.getFullYear();

            window.location.href =`<?php echo URLROOT; ?>/member/Booking?id=${trainerId}&month=${currentMonth}&year=${currentYear}`;
            buildCalendar();
        });
    }

    if(dateInput){
        dateInput.addEventListener("input", (e) => {
            // Allow only numbers and slash
            dateInput.value = dateInput.value.replace(/[^0-9/]/g, "");
        
            // Limit the input to 7 characters (MM/YYYY)
            if (dateInput.value.length > 7) {
                dateInput.value = dateInput.value.slice(0, 7);
            }
        });
    }

    if(gotoBtn){
        gotoBtn.addEventListener("click", ()=>{
            const input = dateInput.value.trim();
            const [inputMonth, inputYear] = input.split("/").map(Number);// Split MM/YYYY and convert to numbers
             
            if (
                inputMonth >= 1 && 
                inputMonth <= 12 && 
                inputYear >= 1900 && 
                inputYear <= 2100
            ) {
                currentMonth = inputMonth;
                currentYear = inputYear;
                buildCalendar(); // Build calendar for the new date
            } else {
                alert("Please enter a valid date in MM/YYYY format.");
            }
        });
    }
}