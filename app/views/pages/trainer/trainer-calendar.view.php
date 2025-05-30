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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/trainer-style.css?v=<?php echo time();?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/sidebar-greeting.css?v=<?php echo time();?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>
    <section class="sidebar">
    <?php require APPROOT.'/views/components/trainer-sidebar.view.php' ?>
    </section>
    <main>
        <div class="title">
            <h1>View Calendar</h1>
            <div class="greeting">
                <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
            </div>
        </div>
        <div class="calendarContainer">
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
    <script src="<?php echo URLROOT; ?>/assets/js/admin-script.js?v=<?php echo time();?>"></script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const trainerId = urlParams.get('id'); 
        let currentMonth = parseInt(urlParams.get('month')) || new Date().getMonth() + 1; 
        let currentYear = parseInt(urlParams.get('year')) || new Date().getFullYear(); 
        const dateToday = new Date().toISOString().split('T')[0];
        let bookedBookings = [];
        let holidays = [];
        
        document.addEventListener("DOMContentLoaded", () =>{

            fetch('<?php echo URLROOT; ?>/trainer/bookings/api')
                .then(response => {
                    console.log('Response Status:', response.status); 
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
                    console.error('Error fetching bookings:', error); 
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="11" style="text-align: center;">Error loading data</td>
                        </tr>
                    `;
                });
            buttons(); 
        });

        // calender
        const calendarBody = document.querySelector('.calendarBody');
        function buildCalendar(bookedBookingsbyDate = {}, holidayData = {}) {
            const calendarHeader = document.querySelector('.calendar-header');
            const monthYear = document.querySelector(".monthYear");
            const weekDays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"]; 
            const firstDayOfMonth = new Date(currentYear, currentMonth - 1, 1);
            const dayInMonth = new Date(currentYear, currentMonth, 0).getDate(); 
            const emptyDays = firstDayOfMonth.getDay();
            const monthYearName = firstDayOfMonth.toLocaleDateString("en-us", { month: "long", year: "numeric" });

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


            calendarHeader.innerHTML = `
                <a class='prevMonth' href='?id=${trainerId}&month=${prevMonth}&year=${prevYear}' aria-label='Previous Month'><i class='ph ph-caret-circle-left'></i></a>
                <div class='monthYear'>${monthYearName}</div>
                <a class='nextMonth' href='?id=${trainerId}&month=${nextMonth}&year=${nextYear}' aria-label='Next Month'><i class='ph ph-caret-circle-right'></i></a>
            `;


            calendarBody.innerHTML = "";
            let row = document.createElement("tr");


            for (let i = 0; i < emptyDays; i++) {
                const emptyCell = document.createElement("td");
                emptyCell.classList.add("plain");
                row.appendChild(emptyCell);
            }


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
                    let bookingCount = document.createElement("div");
                    bookingCount.classList.add('booked-count');
                    bookingCount.innerHTML = `
                            <span class="booking-dot"></span>
                            <span>${bookedBookingsbyDate[date]} Booking${bookedBookingsbyDate[date] === 1 ? '' :'s'}</span>
                        `;
                    dayCell.appendChild(bookingCount);
                }

                row.appendChild(dayCell);
            }


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
                    window.location.href =`<?php echo URLROOT; ?>/trainer/calendar?month=${currentMonth}&year=${currentYear}`;
                    buildCalendar();
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

                if (clickedElement.classList.contains('day') && !clickedElement.classList.contains('plain')) {
                    const selectedDate = clickedElement.getAttribute('data-date');
                    const selectedDay = selectedDate.split('-')[2];
                    const currentMonthYear = document.querySelector('.monthYear').innerText;
    

                    modalDate.innerText = `${selectedDay} ${currentMonthYear}`;


                    const bookedBody = document.querySelector('.bookingModal-body');
                    const selectedBookings = bookedBookings.filter(booking => booking.booking_date === selectedDate);
                    let selectedHoliday = holidays[selectedDate] === null ? "N/A" : holidays[selectedDate];
                    
                    bookedBody.innerHTML = "";
                    if (selectedHoliday) {
                       bookedBody.innerHTML = `<div style="padding-top: 80px; padding-bottom:75px; text-align: center;">
                            Holiday: ${selectedHoliday}
                        </div>`;
                    } else if (selectedBookings.length > 0) {
                        selectedBookings.forEach(book => {
                        const bookingItem = document.createElement('div');
                        bookingItem.classList.add('booking-item');
                        bookingItem.innerHTML = `
                            <div class="booking-time">${book.timeslot}</div>
                            <div class="booking-title">${book.member_name}</div>
                        `;
                        bookedBody.appendChild(bookingItem);

                        })
                    } else {
                        bookedBody.innerHTML = `<div style="padding-top: 80px; padding-bottom:75px; text-align: center;">No bookings for this date.</div>`;
                    }
                    
         
                    modal.style.display = 'block';
                }
            });

            closeModal.addEventListener('click', function () {
                modal.style.display = 'none';
            });
        }
    </script>
    </body>
</html>

