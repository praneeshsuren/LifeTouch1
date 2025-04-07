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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/member-style.css?v=<?php echo time();?>" />
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
        <?php require APPROOT.'/views/components/member-sidebar.view.php' ?>
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
                <div class="goto">
                    <input type="text" placeholder="mm/yyyy" class="date-input" />
                    <button class="gotoBtn">Go</button>
                </div>
                <button class="todayBtn">Today</button>
            </div>
        </div> 
        <div class="recent-announcements">
            <div class="announcements">
                <h2 style="font-size:1.5rem; font-weight:500; padding-top:1rem;text-align:center">Details</h2>
                <div class="announcement-list"></div>
            </div>
        </div>
        <div id="bookingModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="title">
                    <h3>Booking : <span id="modalDate"></span></h3>
                </div>
                <div class="timeslots"></div>
                <div class="bookingForm">
                    <form id="bookingForm">
                        <div class="input">
                            <input type="text" value="<?php echo htmlspecialchars($member_id); ?>" name="memberId" required>
                            <input type="text" id="selectedTrainerId"  name="trainerId" required>
                            <input type="text" id="selectedTimeslotId" name="timeslotId" required> 
                            <input type="text" id="date" name="date" required> 
                            <div class="input-container">
                                <div  class="label"><i class="ph ph-calendar"></i>Date</div>
                                <div id="selectedDate"></div>
                            </div>
                            <div class="input-container">
                                <div class="label"><i class="ph ph-clock-countdown"></i>Time</div>
                                <div id="selectedTimeslot"></div>
                            </div>
                        </div>
                        <div class="book-btn">
                            <button type="submit" id="btnBook" name="submit">Book</button>
                        </div>
                    </form>
                </div> 
            </div>
        </div>
    </main>
    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/member/member-script.js?v=<?php echo time();?>"></script>
    <!-- <script src="<?php echo URLROOT; ?>/assets/js/member/calendar.js?v=<?php echo time();?>"></script> -->
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const trainerId = urlParams.get('id'); 
        let currentMonth = parseInt(urlParams.get('month')) || new Date().getMonth() + 1; // Default to the current month
        let currentYear = parseInt(urlParams.get('year')) || new Date().getFullYear(); // Default to the current year
        const dateToday = new Date().toISOString().split('T')[0];
        const bookDiv = document.querySelector('.announcement-list');

        document.addEventListener("DOMContentLoaded", () =>{ 
            if (trainerId) {
                if(currentYear >= new Date().getFullYear() && currentMonth >= (new Date().getMonth() + 1)){
                    fetch(`<?php echo URLROOT; ?>/member/Booking/api?id=${trainerId}&month=${currentMonth}&year=${currentYear}`)
                        .then(response => {
                            console.log('Response Status:', response.status); // Log response status
                            return response.json();
                        })
                        .then(data => {
                            console.log('Bookings:', data.bookings);
                            // Mark bookings in the calendar
                            if (Array.isArray(data.bookings) && data.bookings.length > 0) {
                                markBookings(data.bookings);
                            } else {
                                bookDiv.innerHTML = `<div style="text-align: center; color: gray;">No bookings available.</div>`;
                                console.log('No bookings found.');
                            }
                            console.log('Time Slots:', data.timeSlots);
                            if(Array.isArray(data.timeSlots) && data.timeSlots.length > 0){
                                displayTimeSlots(data.timeSlots);
                            } else {
                                console.log('No timeslots found.');
                            }
                        })
                        .catch(error => console.error('Error fetching bookings details:', error));
                } else {
                    bookDiv.innerHTML = `<div style="text-align: center; color: gray;">Bookings are not available for past months.</div>`;
                }
            }

            //submit
            document.getElementById("bookingForm").addEventListener('submit', function(e) {
                e.preventDefault();

                const timeslotId = document.getElementById("selectedTimeslotId").value;
                const date = document.getElementById('date').value;

                if (!timeslotId) {
                    alert("timeslot is required");
                    return;
                }
                if(date < dateToday){
                    alert("You cannot add booking for a past date.");
                    return;
                }

                const formData = new FormData(this);
                fetch('<?php echo URLROOT; ?>/member/Booking/add',{
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                    alert("Booking added successfully!");
                    location.reload();
                    } else {
                    alert("Error: " + result.message);
                    }
                })
                .catch(error => console.error("Error inserting booking:", error));
            });

            buildCalendar();
            buttons(); 
            bookingModal();
        });

        //timeslots
        function displayTimeSlots(timeSlots){
            const timeSlotsContainer = document.querySelector('.timeslots');
            timeSlotsContainer.innerHTML = '';
            timeSlots.forEach(timeSlot => {
                let timeSlotBtn = document.createElement('button');
                timeSlotBtn.classList.add('timeslot');
                timeSlotBtn.dataset.slot = timeSlot.slot;
                timeSlotBtn.dataset.timeslotId = timeSlot.id;
                timeSlotBtn.textContent = timeSlot.slot;

                timeSlotBtn.addEventListener('click', () => {
                    const selectedTimeslotInput = document.getElementById('selectedTimeslot');
                    const selectedTimeslotIdInput = document.getElementById('selectedTimeslotId');
                    selectedTimeslotInput.textContent = timeSlot.slot;
                    selectedTimeslotIdInput.value = timeSlot.id;
                });
                timeSlotsContainer.appendChild(timeSlotBtn);
            });
        }

        function markBookings(bookings) {
            bookDiv.innerHTML = ''; // Clear existing content
           
            // Filter bookings for "booked" and "pending" statuses
            const filteredBookings = bookings.filter(
            booking => (booking.status === 'booked' || booking.status === 'pending') && 
                        new Date(booking.booking_date).getTime() >= new Date(dateToday).getTime()
            );

            // Group bookings by date
            const groupedBookings = filteredBookings.reduce((acc, {id, booking_date, slot, status, timeslot_id, trainer_id}) => {
                if (!acc[booking_date]) acc[booking_date] = [];
                acc[booking_date].push({ id, slot, status, timeslot_id, trainer_id});
                return acc;
            }, {});

            const todayFutureBookings = Object.keys(groupedBookings).sort((a, b) => new Date(a) - new Date(b));

            todayFutureBookings.forEach(date => {
                const dateHeading = document.createElement('div');
                dateHeading.classList.add('date-heading');
                dateHeading.innerText = date;
                bookDiv.appendChild(dateHeading);

                groupedBookings[date]
                    .sort((a, b) => convertTo24hrs(a.slot.split(' - ')[0]) - convertTo24hrs(b.slot.split(' - ')[0]))
                    .forEach(({ id, slot, status, timeslot_id, trainer_id}) => {
                        const timeslotItem = document.createElement('div');
                        timeslotItem.classList.add('announcement');

                        const statusCircle = document.createElement('div');
                        statusCircle.classList.add(status === 'booked' ? 'booked' : 'pending');

                        const slotText = document.createElement('span');
                        slotText.style.color = '#757575';
                        slotText.innerText = slot;

                        const editDtl = document.createElement('div');
                        editDtl.innerHTML = `
                            <div class="edit-dlt">
                                <div class="edit" onclick="editBooking('${id}', '${date}', '${timeslot_id}', '${slot}', '${trainer_id}')">
                                    <i class="ph ph-eraser"></i>
                                </div>
                                <div class="dlt" onclick="dltBooking('${id}')">
                                    <i class="ph ph-trash-simple"></i>
                                </div>
                            </div>
                        `;


                        timeslotItem.append(statusCircle, slotText, editDtl);
                        bookDiv.appendChild(timeslotItem);
                    });
            });
        }

        function convertTo24hrs(time){
            const [hrMin, period] = time.trim().split(' '); //AM,PM
            let [hr, min] =hrMin.split(':');
            hr = parseInt(hr, 10);
            min = parseInt(min, 10);
            let hr24 = hr;
            if(period === 'PM' && hr24 < 12) {
                hr24 +=12;
            } else if (period === 'AM' && hr24 ===12) {
                hr24 = 0;
            }
            return new Date(1970, 0, 1, hr24, min);
        }

        function formatDateToLong(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
        }
        // calender
        const calendarBody = document.querySelector('.calendarBody');
        function buildCalendar() {
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
                        window.location.href =`<?php echo URLROOT; ?>/member/Booking?id=${trainerId}&month=${currentMonth}&year=${currentYear}`;
                        buildCalendar(); // Build calendar for the new date
                    } else {
                        alert("Please enter a valid date in MM/YYYY format.");
                    }
                });
            }
        }

        const modal = document.getElementById('bookingModal');
        const closeModal = document.querySelector('.modal .close');

        const modalDate = document.getElementById('modalDate');
        const selectedDateInput = document.getElementById('selectedDate');
        const trainerIdInput = document.getElementById('selectedTrainerId');
        const dateInput = document.getElementById("date");
        const selectedTimeslotInput = document.getElementById('selectedTimeslot');
        const selectedTimeslotIdInput = document.getElementById('selectedTimeslotId');
              
        // modal
        function bookingModal(){
            calendarBody.addEventListener('click', function (event) {
                  const clickedElement = event.target;

                // Check if the clicked element is a date box
                if (clickedElement.classList.contains('day') && !clickedElement.classList.contains('plain')) {
                    const selectedDate = clickedElement.getAttribute('data-date');
                    // Only allow selecting present or future dates
                    if (selectedDate < dateToday) {
                        return; 
                    }

                    const formattedDate = formatDateToLong(selectedDate);

                    // Set the modal's input
                    trainerIdInput.value= `${trainerId}`;
                    modalDate.innerText = formattedDate;
                    selectedDateInput.textContent = formattedDate;
                    dateInput.value = selectedDate;
                    selectedTimeslotInput.textContent = `Select timeslot`;
                    selectedTimeslotIdInput.value = '';

                    // Show the modal
                    modal.style.display = 'block';
                }
            });

            // Close modal when 'x' is clicked
            closeModal.addEventListener('click', function () {
                modal.style.display = 'none';
            });
        }

        function dltBooking(id){
            if (!confirm("Are you sure you want to delete this booking?")) return;

            fetch('<?php echo URLROOT?>/member/Booking/delete',{
                method: "POST",
                headers: {
                    "Content-Type":  "application/x-www-form-urlencoded",
                },
                body: `id=${encodeURIComponent(id)}`,
            })
            .then(response => response.json())
            .then(result => { 
                if (!result.success) {
                    alert("Booking deleted successfully!");
                    location.reload();
                } else {
                    alert("Error: " + result.message);
                }
            })
            .catch(error => console.error("Error deleting Booking:", error));

        }

        function editBooking(id, date, timeslotid, timeslot ,trainerid){
            modal.style.display = "block";

            trainerIdInput.value= trainerid;
            const formattedDate = formatDateToLong(date);
            modalDate.innerText = formattedDate
            selectedDateInput.textContent = formattedDate;
            dateInput.value = date;
            selectedTimeslotInput.textContent = timeslot;
            selectedTimeslotIdInput.value = timeslotid;

            const editbtn = document.getElementById('btnBook');
            editbtn.innerText = "Update";  

            editbtn.onclick = function(event) {
                event.preventDefault(); 
                const newTimeslot = selectedTimeslotIdInput.value;
                
                if (!confirm("Are you sure you want to update this timeslot?")) return;
                fetch('<?php echo URLROOT; ?>/member/Booking/edit', {
                    method :'POST',
                    headers: {
                    "Content-type": "application/x-www-form-urlencoded",
                    },
                    body: `id=${encodeURIComponent(id)}&timeslot_id=${encodeURIComponent(newTimeslot)}`,
                })
                .then(response => response.json())
                .then(result =>{
                    console.log(result);
                    if (!result.success) {
                        alert("Timeslot updated successfully!");
                        modal.style.display = "none";
                        location.reload();
                    } else {
                        alert("Error: " + result.message);
                    }
                })
                .catch(error => console.error("Error updating timeslot:", error));

            };
        }

    </script>
    </body>
</html>

