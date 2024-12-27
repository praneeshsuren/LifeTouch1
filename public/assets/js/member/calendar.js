// Helper to get query params
function updateCalendarParams(month, year) {
    const url = new URL(window.location.href);
    url.searchParams.set('month', month);
    url.searchParams.set('year', year);
    window.location.href = url.toString();
}

// Handle Today button
document.querySelector('.todayBtn').addEventListener('click', () => {
    const today = new Date();
    const currentMonth = today.getMonth() + 1; // Months are 0-based
    const currentYear = today.getFullYear();
    updateCalendarParams(currentMonth, currentYear);
});

// Handle Go button
document.querySelector('.gotoBtn').addEventListener('click', () => {
    const dateInput = document.querySelector('.date-input').value.trim();
    const [month, year] = dateInput.split('/').map(Number);

    if (
        !isNaN(month) &&
        !isNaN(year) &&
        month >= 1 &&
        month <= 12 &&
        year >= 1000 &&
        year <= 9999
    ) {
        updateCalendarParams(month, year);
    } else {
        alert('Invalid date format. Please enter in mm/yyyy format.');
    }
});
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('bookingModal');
    const modalDate = document.getElementById('modalDate');
    const closeBtn = document.querySelector('.close');
    const selectedDateInput = document.getElementById('selectedDate');
    const selectedTimeslotInput = document.getElementById('selectedTimeslot');
        
    // Add event listener to all clickable calendar cells
    document.querySelectorAll('.calendar .clickable').forEach(cell => {cell.addEventListener('click', function () {
            const selectedDate = this.getAttribute('data-date');
            modalDate.textContent = selectedDate;
            selectedDateInput.value = selectedDate;
            selectedTimeslotInput.value = ''; 
            selectedTimeslotInput.placeholder = 'Select the Timeslot';

            modal.style.display = 'block';
        });
    });

    // Add event listener to all timeslot buttons
    document.querySelectorAll('.timeslot').forEach(button => {
        button.addEventListener('click', function () {
            const timeslot = this.getAttribute('data-timeslot'); // Get timeslot from clicked button
            selectedTimeslotInput.value = timeslot; // Update input field with selected timeslot

            document.querySelectorAll('.timeslot').forEach(btn => btn.classList.remove('selectedTimeslot'));
        
            // Add 'selected' class to the clicked button
            this.classList.add('selectedTimeslot');
        });
    });


    // Close the modal when clicking on the 'x' button
    closeBtn.addEventListener('click', function () {
        modal.style.display = 'none';
    });

});

