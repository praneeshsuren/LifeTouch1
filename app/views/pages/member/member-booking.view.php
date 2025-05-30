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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/sidebar-greeting.css?v=<?php echo time();?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
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
        <div class="mainContainer">
            <div class="calendarContainer">
                <div class="calendar-header"></div>
                <table class="calendar">
                    <thead>
                        <tr>
                            <th>Sun</th>
                            <th>Mon</th>
                            <th>Tues</th>
                            <th>Wed</th>
                            <th>Thur</th>
                            <th>Fri</th>
                            <th>Sat</th>
                        </tr>
                    </thead>
                    <tbody class="calendarBody"></tbody>
                </table>
                <div class="gotoToday">
                    <button class="todayBtn">Today</button>
                </div>
            </div> 
            <div class="booking-details">
                <div class="details">
                    <h2 style="font-size:1.5rem; font-weight:500; padding-top:1rem;text-align:center">Details</h2>
                    <div class="detail-list"></div>
                </div>
            </div>
        </div>
        <div id="bookingModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="title">
                    <h3>Booking : <span id="modalDate"></span></h3>
                </div>
                <div class="modal-body" style = "color:black"></div>
            </div>
        </div>  
    </main>
    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/member/member-script.js?v=<?php echo time();?>"></script>
    <!-- <script src="<?php echo URLROOT; ?>/assets/js/member/calendar.js?v=<?php echo time();?>"></script> -->
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const trainerId = urlParams.get('id'); 
        let currentMonth = parseInt(urlParams.get('month')) || new Date().getMonth() + 1; 
        let currentYear = parseInt(urlParams.get('year')) || new Date().getFullYear(); 
        const dateToday = new Date().toISOString().split('T')[0];

        const bookDiv = document.querySelector('.detail-list');
        const calendarBody = document.querySelector('.calendarBody');
        let holidays = [];
        let timeSlots = [];
        let bookings = [];

        const bookingModal = document.getElementById('bookingModal');
        const closeModal = document.querySelector('.modal .close');
        const modalDate = document.getElementById('modalDate');
        const modalBody = bookingModal.querySelector('.modal-body');

        const bookingTemplate = `
            <h4>Available Time Slots:</h4>
                <div class="timeslots"></div>
            
                <div class="bookingForm">
                    <form id="bookingForm">
                        <div class="input">
                            <input type="hidden" value="<?php echo htmlspecialchars($member_id); ?>" name="memberId" required>
                            <input type="hidden" id="selectedTrainerId"  name="trainerId" required>
                            <input type="hidden" id="selectedTimeslotId" name="timeslotId" required> 
                            <input type="hidden" id="date" name="date" required> 
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
        `;

        document.addEventListener("DOMContentLoaded", () =>{ 
            if (trainerId) {
                fetch(`<?php echo URLROOT; ?>/member/Booking/api?id=${trainerId}&month=${currentMonth}&year=${currentYear}`)
                    .then(response => {
                        console.log('Response Status:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        bookings = data.bookings;
                        timeSlots = data.timeSlots;
                        // console.log('Bookings:', bookings);
                        if (currentYear >= new Date().getFullYear() &&
                            currentMonth >= (new Date().getMonth() + 1) && Array.isArray(bookings) && bookings.length > 0) {
                            markBookings(bookings);
                        } else if (currentYear < new Date().getFullYear() || currentMonth < (new Date().getMonth() + 1)) {
                            bookDiv.innerHTML = `<div style="text-align: center; color: gray;">Bookings are not available for past months.</div>`;
                        } else {
                            bookDiv.innerHTML = `<div style="text-align: center; color: gray;">No bookings available.</div>`;
                        }

                        // console.log('isbooked:', data.isBooked);
                        // console.log('Time Slots:', timeSlots);

                        if (Array.isArray(data.holidays)) {
                            holidays = data.holidays.reduce((acc, holiday) => {
                                if(acc[holiday.date]) {
                                    acc[holiday.date].push(holiday.slot);
                                } else {
                                    acc[holiday.date] = [holiday.slot];
                                }
                                return acc;
                            }, {});
                            console.log("holidays:", holidays);
                        } else {
                            console.warn("No holidays data found or holidays is not an array.");
                        }

                        buildCalendar(holidays); // Always build calendar
                    })
                    .catch(error => console.error('Error fetching bookings details:', error));
            }

            buttons(); 

        });

        function parseTimeToMinutes(timeStr) {
            if (!timeStr) return 0;
            const [timePart, period] = timeStr.trim().split(' ');
            let [hours, minutes] = timePart.split(':').map(Number);
            if (period === 'PM' && hours !== 12) hours += 12;
            if (period === 'AM' && hours === 12) hours = 0;
            return hours * 60 + minutes;
        }

        function sortTimeslots(timeslots) {
            return [...timeslots].sort((a, b) => {
                const startTimeA = a.slot.split(' - ')[0];
                const startTimeB = b.slot.split(' - ')[0];
                return parseTimeToMinutes(startTimeA) - parseTimeToMinutes(startTimeB);
            });
        }

        //timeslots
        function normalizeSlot(slot) {
            if (!slot) return slot;
            return slot.trim().toLowerCase().replace(/\s+/g, ' ');
        }

        function displayTimeSlots(timeSlots, bookings = [], selectedDate, selectedTimeslotId = null, holidays = {}) {
            const timeSlotsContainer = document.querySelector('.timeslots');
            if (!timeSlotsContainer) {
                console.error('Timeslots container not found in the DOM');
                return 0; 
            }
            timeSlotsContainer.innerHTML = '';

            const sortedTimeSlots = sortTimeslots(timeSlots);

            const bookedForDate = (bookings || []).filter(b => 
                b.booking_date === selectedDate && b.status === 'booked'
            );

            let availableSlotCount = 0;
            const isToday = new Date(selectedDate).toDateString() === new Date().toDateString();
            const holidaySlots = holidays[selectedDate] || [];

            sortedTimeSlots.forEach(timeSlot => {
                let timeSlotBtn = document.createElement('div');
                timeSlotBtn.classList.add('time-slot');
                timeSlotBtn.dataset.slot = timeSlot.slot;
                timeSlotBtn.dataset.timeslotId = timeSlot.id;
                timeSlotBtn.textContent = timeSlot.slot;

                const isBooked = bookedForDate.some(b => parseInt(b.timeslot_id) === parseInt(timeSlot.id));
                const isSelected = selectedTimeslotId && parseInt(timeSlot.id) === parseInt(selectedTimeslotId);
                const isPast = isToday && isTimeSlotInPast(timeSlot.slot.split(' - ')[0]);

                let isHolidayBlocked = false;
                if(holidaySlots) {
                    isHolidayBlocked = holidaySlots.some(holidaySlot => 
                        normalizeSlot(holidaySlot) === normalizeSlot(timeSlot.slot)
                    );
                    console.log("ts",timeSlot.slot);
                    console.log("bl", isHolidayBlocked);
                }

                if ((isBooked && !isSelected)  || isPast || isHolidayBlocked )  {
                    timeSlotBtn.classList.add("booked");
                } else {
                    availableSlotCount++;

                    if (isSelected) {
                        timeSlotBtn.classList.add("selected")
                    }

                    timeSlotBtn.addEventListener('click', () => {
                        const selectedTimeslotInput = document.getElementById('selectedTimeslot');
                        const selectedTimeslotIdInput = document.getElementById('selectedTimeslotId');
                        
                        document.querySelectorAll('.time-slot').forEach(btn => {
                            btn.classList.remove("selected");
                        });

                        timeSlotBtn.classList.add("selected");

                        selectedTimeslotInput.textContent = timeSlot.slot;
                        selectedTimeslotIdInput.value = timeSlot.id;
                    });
                }
                timeSlotsContainer.appendChild(timeSlotBtn);
            });
            console.log(`Available slots count: ${availableSlotCount}`);

            return availableSlotCount; 
        }

        function markBookings(bookings) {
            bookDiv.innerHTML = ''; 
           
            const filteredBookings = bookings.filter(
            booking => (booking.status === 'booked' || booking.status === 'pending') && 
                        new Date(booking.booking_date).getTime() >= new Date(dateToday).getTime()
            );

            const groupedBookings = filteredBookings.reduce((acc, {id, booking_date, slot, status, timeslot_id, trainer_id}) => {
                if (!acc[booking_date]) acc[booking_date] = [];
                acc[booking_date].push({ id, slot, status, timeslot_id, trainer_id});
                return acc;
            }, {});

            const filteredFutureBookings = {};

            Object.keys(groupedBookings)
                .sort((a, b) => new Date(a) - new Date(b))
                .forEach(date => {
                    const isToday = new Date(date).toDateString() === new Date().toDateString();

                    const futureSlots = groupedBookings[date].filter(booking => {
                        if (!isToday) return true;
                        return !isTimeSlotInPast(booking.slot.split(' - ')[0]);
                    });

                    if (futureSlots.length > 0) {
                        filteredFutureBookings[date] = futureSlots;
                    }
                });

            const futureBookings = Object.keys(filteredFutureBookings).sort((a, b) => new Date(a) - new Date(b));

            futureBookings.forEach(date => {
                const dateHeading = document.createElement('div');
                dateHeading.classList.add('date-heading');
                dateHeading.innerText = date;
                bookDiv.appendChild(dateHeading);

                groupedBookings[date]
                    .sort((a, b) => convertTo24hrs(a.slot.split(' - ')[0]) - convertTo24hrs(b.slot.split(' - ')[0]))
                    .forEach(({ id, slot, status, timeslot_id, trainer_id}) => {
                        const isPast = (new Date(date).toDateString() === new Date().toDateString()) && isTimeSlotInPast(slot.split(' - ')[0]);

                        if (isPast) return;

                        const timeslotItem = document.createElement('div');
                        timeslotItem.classList.add('detail');

                        let statusClass;
                        switch (status?.toLowerCase()) {
                            case 'booked':
                                statusClass = 'status-booked';
                                break;
                            case 'pending':
                            default:
                                statusClass = 'status-pending';
                        }

                        const statusCircle = document.createElement('div');
                        statusCircle.classList.add('booking-status', statusClass);
                        statusCircle.textContent = status.charAt(0).toUpperCase() + status.slice(1).toLowerCase();

                        const slotText = document.createElement('span');
                        slotText.style.color = '#757575';
                        slotText.innerText = slot;

                        const editDtl = document.createElement('div');
                        const editButton = status?.toLowerCase() !== 'booked' 
                            ? `<div class="booking-action action-secondary" onclick="editBooking('${id}', '${date}', '${timeslot_id}', '${slot}', '${trainer_id}')">
                                <i class="ph ph-eraser"></i>
                            </div>`
                            : '';
                        editDtl.innerHTML = `
                            <div class="edit-dlt">
                                ${editButton}
                                <div class="booking-action action-danger" onclick="dltBooking('${id}')">
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
            const [hrMin, period] = time.trim().split(' '); 
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

        function isTimeSlotInPast(timeSlot) {
            const now = new Date();

            const slotTime = convertTo24hrs(timeSlot);
            const slotDateTime = new Date(now.getFullYear(), now.getMonth(), now.getDate(), slotTime.getHours(), slotTime.getMinutes());

            return slotDateTime < now;
        }

        function formatDateToLong(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
        }

        function buildCalendar(holidayData = {}) {
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

                if(date in holidays && (holidays[date]  && holidays[date] == 'Fullday')|| date < dateToday){
                    dayCell.classList.add("holiday");
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
            const todayBtn = document.querySelector(".todayBtn");

            if(todayBtn){
                todayBtn.addEventListener("click", ()=>{
                    const today = new Date();
                    currentMonth = today.getMonth() + 1;
                    currentYear = today.getFullYear();
                    window.location.href =`<?php echo URLROOT; ?>/member/Booking?id=${trainerId}&month=${currentMonth}&year=${currentYear}`;
                    buildCalendar();
                });
            }
        }
            
        calendarBody.addEventListener('click', function (event) {
            const clickedElement = event.target;

            if (clickedElement.classList.contains('day') && !clickedElement.classList.contains('plain')) {
                const selectedDate = clickedElement.getAttribute('data-date');
                let availableSlotCount = 0;
            
                if (selectedDate < dateToday) {
                    return; 
                }

                const formattedDate = formatDateToLong(selectedDate);
                modalDate.innerText = formattedDate;
    
                if(!holidays[selectedDate]){
                    modalBody.innerHTML = bookingTemplate;
                    availableSlotCount = displayTimeSlots(timeSlots, bookings, selectedDate);
                }
                    
                if (selectedDate in holidays && (holidays[selectedDate]  && holidays[selectedDate] == 'Fullday')) {
                    console.log("holiday");
                    modalBody.innerHTML = `
                        <div style="padding: 80px 0; text-align: center;">
                            <strong>Holiday</strong>
                        </div>`;
                } else {
                    console.log(holidays[selectedDate]);
                    modalBody.innerHTML = bookingTemplate;
                    availableSlotCount = displayTimeSlots(timeSlots, bookings, selectedDate, null, holidays);

                    if( availableSlotCount === 0){
                        modalBody.innerHTML = `
                            <div style="padding: 80px 0; text-align: center;">
                                <strong>No available slots</strong>
                            </div>`;
                    } else {
                        const selectedDateInput = document.getElementById('selectedDate');
                        const trainerIdInput = document.getElementById('selectedTrainerId');
                        const dateInput = document.getElementById("date");
                        const selectedTimeslotInput = document.getElementById('selectedTimeslot');
                        const selectedTimeslotIdInput = document.getElementById('selectedTimeslotId');

                        trainerIdInput.value = trainerId;
                        selectedDateInput.textContent = formattedDate;
                        dateInput.value = selectedDate;
                        selectedTimeslotInput.textContent = "Select timeslot";
                        selectedTimeslotIdInput.value = "";

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

                            const isSlotTaken = Array.isArray(bookings) && bookings.some(b =>
                                b.booking_date === date &&
                                b.timeslot_id == timeslotId &&
                                (b.status === "booked" || b.status === "pending")
                            );

                            if (isSlotTaken) {
                                alert("This timeslot is already taken (booked or pending). Please choose another one.");
                                return;
                            }

                            const formData = new FormData(this);
                            if (!confirm("Are you sure you want to book this timeslot?")) return;
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
                    }
                }


                bookingModal.style.display = 'block';
            }
        });

        closeModal.addEventListener('click', function () {
            bookingModal.style.display = 'none';
        });

        window.addEventListener('click', function (event) {
            if (event.target === bookingModal) {
                bookingModal.style.display = 'none';
            }
        });
        

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
                if (result.success) {
                    alert("Booking deleted successfully!");
                    location.reload();
                } else {
                    alert("Error: " + result.message);
                }
            })
            .catch(error => console.error("Error deleting Booking:", error));

        }

        function editBooking(id, date, timeslotid, timeslot ,trainerid){
            const formattedDate = formatDateToLong(date);
            modalDate.innerText = formattedDate;

            modalBody.innerHTML = bookingTemplate;
            
            const trainerIdInput = document.getElementById('selectedTrainerId');
            const selectedDateInput = document.getElementById('selectedDate');
            const dateInput = document.getElementById('date');
            const selectedTimeslotInput = document.getElementById('selectedTimeslot');
            const selectedTimeslotIdInput = document.getElementById('selectedTimeslotId');
            const editBtn = document.getElementById('btnBook');

            trainerIdInput.value= trainerid;
            selectedDateInput.textContent = formattedDate;
            dateInput.value = date;
            selectedTimeslotInput.textContent = timeslot;
            selectedTimeslotIdInput.value = timeslotid;

            const editbtn = document.getElementById('btnBook');
            editbtn.innerText = "Update";  

            displayTimeSlots(timeSlots, bookings, date, timeslotid, holidays);
            
            editbtn.onclick = function(event) {
                event.preventDefault(); 
                const newTimeslot = selectedTimeslotIdInput.value;

                const isSlotTaken = bookings.some(b =>
                    b.booking_date === date &&
                    b.timeslot_id == timeslotid &&
                    b.timeslot_id == newTimeslot &&
                    (b.status === "booked" || b.status === "pending")
                );
                console.log(isSlotTaken);

                if (isSlotTaken) {
                    alert("This timeslot is already taken (booked or pending). Please choose another one.");
                    return;
                }

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
                    if (result.success) {
                        alert("Timeslot updated successfully!");
                        bookingModal.style.display = "none";
                        location.reload();
                    } else {
                        alert("Error: " + result.message);
                    }
                })
                .catch(error => console.error("Error updating timeslot:", error));
            };
            bookingModal.style.display = "block";
        }

    </script>
    </body>
</html>

