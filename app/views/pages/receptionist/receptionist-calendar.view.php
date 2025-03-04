<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- STYLESHEET -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/receptionist-style.css?v=<?php echo time();?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function(){
            (function() {
                var savedMode = localStorage.getItem('mode');
                if (savedMode === 'dark') {
                document.body.classList.add('dark');
                }
            })();
          });
    </script>
    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>
    <section class="sidebar">
    <?php require APPROOT.'/views/components/receptionist-sidebar.view.php' ?>
    </section>
    <main>
        <div class="title">
            <h1>View Calendar</h1>
            <div class="greeting">
                <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
            </div>
        </div>
        <div class="calendarContainer">
            <div class="holiday-header">
                <button class="add-holiday-btn" onclick="window.location.href='<?php echo URLROOT; ?>/receptionist/holiday'">
                    Holidays
                </button>
            </div>
            <div class="calendar-header"></div>
            <table class="calendar">
                <th>Sun</th>
                <th>Mon</th>
                <th>Tues</th>
                <th>Wed</th>
                <th>Thur</th>
                <th>Fri</th>
                <th>Sat</th>
                <tbody class="calendarBody"></tbody>
            </table>
            <div class="gotoToday">
                <div class="goto">
                    <input type="text" placeholder="mm/yyyy" class="date-input" />
                    <button class="gotoBtn">Go</button>
                </div>
                <button class="todayBtn">Today</button>
            </div>
        </div> 
        
        <div id="bookingModal" class="bookingModal">
            <div class="bookingModal-content">
                <span class="bookingModalClose">&times;</span>
                <div class="title">
                    <h3>Bookings : <span id="modalDate"></span></h3>
                </div>
                <div class="bookingModal-body" style = "color:black"></div>

            </div>
        </div>
    </main>
    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/receptionist-script.js?v=<?php echo time();?>"></script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const trainerId = urlParams.get('id'); 
        let currentMonth = parseInt(urlParams.get('month')) || new Date().getMonth() + 1; // Default to the current month
        let currentYear = parseInt(urlParams.get('year')) || new Date().getFullYear(); // Default to the current year
        const calendarBody = document.querySelector('.calendarBody');
        const dateToday = new Date().toISOString().split('T')[0];
        let bookedBookings = [];
        let holidays = [];
        
        document.addEventListener("DOMContentLoaded", () =>{

            fetch('<?php echo URLROOT; ?>/receptionist/bookings/api')
                .then(response => {
                    console.log('Response Status:', response.status); // Log response status
                    return response.json();
                })
                .then(data => {
                    console.log("Fetched booking data:", data.bookings);
                    console.log("Fetched holiday data:", data.holidays);
                     
                    holidays = data.holidays.reduce((acc, holiday) => {
                        acc[holiday.date] = holiday.reason;
                        return acc;
                    }, {});
        
                    if (Array.isArray(data.bookings) && data.bookings.length > 0){
                        bookedBookings = data.bookings.filter(booking => booking.status === 'booked');
                        let bookedBookingsbyDate = bookedBookings.reduce((acc, booking) => {
                            let date = booking.booking_date;
                            if(!acc[date]) acc[date] = 0;
                            acc[date]++;
                            return acc;
                        }, {});
                        buildCalendar(bookedBookingsbyDate, holidays);
                    } else{
                        buildCalendar({}, holidays);
                    }
                    bookingModal();
                })
                .catch(error => {
                    console.error('Error fetching bookings:', error); // Log the error
                    calendarBody.innerHTML = `
                        <tr>
                            <td colspan="11" style="text-align: center;">Error loading data</td>
                        </tr>
                    `;
                });
            buttons(); 
        });

        // calender
        function buildCalendar(bookedBookingsbyDate = {}, holidayData = {}) {
            const calendarHeader = document.querySelector('.calendar-header');
            const monthYear = document.querySelector(".monthYear");
            const weekDays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"]; 
            const firstDayOfMonth = new Date(currentYear, currentMonth - 1, 1);
            const dayInMonth = new Date(currentYear, currentMonth, 0).getDate(); // Number of days in the month
            const emptyDays = firstDayOfMonth.getDay(); // Day index of the first day (0-6)
            const monthYearName = firstDayOfMonth.toLocaleDateString("en-us", { month: "long", year: "numeric" });

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
                <div class='monthYear'>${monthYearName}</div>
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

                if(bookedBookingsbyDate[date]){
                    const bookingCount = document.createElement("div");
                    bookingCount.classList.add('booked-count');
                    bookingCount.innerText = `${bookedBookingsbyDate[date]}`;
                    dayCell.appendChild(bookingCount);
                }

                if(date in holidayData){
                    dayCell.classList.add("holiday");
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
            const gotoBtn = document.querySelector(".gotoBtn");
            const todayBtn = document.querySelector(".todayBtn");
            const dateInput = document.querySelector(".date-input");

            if(todayBtn){
                todayBtn.addEventListener("click", ()=>{
                    const today = new Date();
                    currentMonth = today.getMonth() + 1;
                    currentYear = today.getFullYear();
                    window.location.href =`<?php echo URLROOT; ?>/receptionist/calendar?month=${currentMonth}&year=${currentYear}`;
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
                        window.location.href =`<?php echo URLROOT; ?>/receptionist/calendar?month=${currentMonth}&year=${currentYear}`;
                        buildCalendar(); // Build calendar for the new date
                    } else {
                        alert("Please enter a valid date in MM/YYYY format.");
                    }
                });
            }
        }

        // modal
        function bookingModal(){
            const modal = document.getElementById('bookingModal');
            const closeModal = document.querySelector('.bookingModal .bookingModalClose');

            calendarBody.addEventListener('click', function (event) {
                const modalDate = document.getElementById('modalDate');
                const clickedElement = event.target;

                // Check if the clicked element is a date box
                if (clickedElement.classList.contains('day') && !clickedElement.classList.contains('plain')) {
                    const selectedDate = clickedElement.getAttribute('data-date');
                    const selectedDay = selectedDate.split('-')[2];
                    const currentMonthYear = document.querySelector('.monthYear').innerText;
    
                    // Set the modal's input
                    modalDate.innerText = `${selectedDay} ${currentMonthYear}`;

                    // booked booking details
                    const modalBody = document.querySelector('.bookingModal-body');

                    const selectedBookings = bookedBookings.filter(booking => booking.booking_date === selectedDate);
                    let selectedHoliday = holidays[selectedDate] === null ? "N/A" : holidays[selectedDate];
                    
                    modalBody.innerHTML = "";

                    if (selectedHoliday) {
                       modalBody.innerHTML = `<div style="padding-top: 80px; padding-bottom:75px; text-align: center;">
                            Holiday: ${selectedHoliday}
                        </div>`;
                    } else if (selectedBookings.length > 0) {
                        selectedBookings.forEach(book => {
                        modalBody.innerHTML += `
                            <div>
                                <table class="trainerviewbtn-profileTable-container">
                                    <tr>
                                        <td>${book.member_id}</td>
                                        <td>${book.trainer_id}</td>
                                        <td>${book.timeslot}</td>
                                    </tr>
                                </table>
                            </div>`;
                        })
                    } else {
                        modalBody.innerHTML = `<div style="padding-top: 80px; padding-bottom:75px; text-align: center;">No bookings for this date.</div>`;
                    }
                    
                    // Show the modal
                    modal.style.display = 'block';
                }
            });

            // Close modal when 'x' is clicked
            closeModal.addEventListener('click', function () {
                modal.style.display = 'none';
            });
        }
    </script>
    </body>
</html>

