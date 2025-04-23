// JavaScript for handling month navigation and dynamic generation of the calendar
document.addEventListener('DOMContentLoaded', () => {
    const currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();

    // Function to generate the calendar grid
    function generateCalendar(month, year) {
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const firstDayOfMonth = new Date(year, month, 1).getDay();
        const lastDayOfMonth = new Date(year, month, daysInMonth).getDay();
        const totalCells = Math.ceil((daysInMonth + firstDayOfMonth) / 7) * 7;

        let calendarGrid = document.getElementById("calendarGrid");
        calendarGrid.innerHTML = "";

        // Add empty cells before the first day of the month
        for (let i = 0; i < firstDayOfMonth; i++) {
            let emptyCell = document.createElement("div");
            emptyCell.classList.add("calendar-cell", "empty-cell");
            calendarGrid.appendChild(emptyCell);
        }

        // Add actual day cells
        for (let day = 1; day <= daysInMonth; day++) {
            let dayCell = document.createElement("div");
            dayCell.classList.add("calendar-cell");
            dayCell.innerHTML = `
                <div class="date">${day}</div>
            `;
            calendarGrid.appendChild(dayCell);
        }

        // Add empty cells after the last day of the month
        for (let i = lastDayOfMonth; i < 6; i++) {
            let emptyCell = document.createElement("div");
            emptyCell.classList.add("calendar-cell", "empty-cell");
            calendarGrid.appendChild(emptyCell);
        }

        // Update the month label
        document.getElementById("monthLabel").textContent = `${new Date(year, month).toLocaleString('default', { month: 'long' })} ${year}`;
    }

    // Initial calendar render
    generateCalendar(currentMonth, currentYear);
});
