<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
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
            <h1>Availability</h1>
            <div class="greeting">
                <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
            </div>
        </div>
        <div class="container">
            <div class="availability-section">
                <div class="add-time-slot-form">
                    <h3>Set Default Availability</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="default-start-time">Start Time</label>
                            <div class="time-input-group">
                                <input type="text" id="start-time" name="start-time" placeholder="HH:MM" pattern="^(0?[1-9]|1[0-2]):[0-5][0-9]$" required>
                                <select id="start-period" name="start-period" required>
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="default-end-time">End Time</label>
                            <div class="time-input-group">
                                <input type="text" id="end-time" name="end-time" placeholder="HH:MM" pattern="^(0?[1-9]|1[0-2]):[0-5][0-9]$" required>
                                <select id="end-period" name="end-period" required>
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="action-buttons">
                        <button type="button" id="set-default-time-btn" class="btn-primary">Set Default Times</button>
                    </div>
                </div>
                <div class="add-time-slot-form">
                    <h3>Set Specific Availability</h3>
                    <div class="form-row">
                        <div class="form-group">
                             <label for="holidayDate"><i class="ph ph-calendar"></i>Date</label>
                            <input type="date" name="holidayDate" id="holidayDate">
                        </div>
                        <div class="form-group">
                            <label for="holidaySlot"><i class="ph ph-clock"></i></i>Time Slot</label>
                            <select id="blocktime"></select>
                        </div>
                    </div>
                    <div class="action-buttons">
                        <button type="button" id="set-specific-time-btn" class="btn-primary">Block Timeslot</button>
                    </div>
                </div>

                <div class="current-availability">
                    <h3>Current Default Availability</h3>
                    <div id="availability-list" class="availability-list"></div>
                </div>

                <div class="current-availability">
                    <h3>Blocked Details</h3>
                    <div id="specific-availability-list" class="availability-list"></div>
                </div>
            </div>
        </div>
      </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/trainer-script.js?v=<?php echo time();?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () =>{ 
            const defaultStartTimeSelect = document.getElementById('start-time');
            const defaultEndTimeSelect = document.getElementById('end-time');
            const startPeriodSelect = document.getElementById('start-period');
            const endPeriodSelect = document.getElementById('end-period');
            const setDefaultTimeBtn = document.getElementById('set-default-time-btn');

            const holidayDateInput = document.getElementById('holidayDate');
            const blockTimeSelect = document.getElementById('blocktime');
            const setSpecificTimeBtn = document.getElementById('set-specific-time-btn');

            const currentAvailabilityDisplay = document.getElementById('current-availability-display');

            let allTimeslots = [];
            let allHolidays = [];
            let allBookings = [];

            const trainerId = '<?php echo isset($_SESSION['user_id']) ? htmlspecialchars($_SESSION['user_id'], ENT_QUOTES, 'UTF-8') : ''; ?>';

            console.log('Trainer ID:', trainerId);
            fetch('<?php echo URLROOT; ?>/trainer/timeslot/api')
                .then(response => {
                    console.log('Response Status:', response.status); 
                    return response.json();
                })
                .then(data => {
                    console.log("Fetched timeslot data:", data);

                    if (Array.isArray(data.timeslot) && data.timeslot.length > 0){
                        allTimeslots = data.timeslot;
                        const sortedTimeslots = sortTimeslots(allTimeslots);
                        renderDefaultTable(sortedTimeslots);

                        const blockTimeSelect = document.getElementById('blocktime');
                        blockTimeSelect.innerHTML = '';
                        sortedTimeslots.forEach(timeslot => {
                            const option = document.createElement('option');
                            option.value = timeslot.id; 
                            option.textContent = timeslot.slot; 
                            blockTimeSelect.appendChild(option);
                        });
                    }
                    if (Array.isArray(data.holidays) && data.holidays.length > 0){
                        allHolidays = data.holidays;
                        const sortedHolidays = sortHolidays(allHolidays);
                        renderHolidayTable(sortedHolidays);;
                    }
                    if (Array.isArray(data.bookings) && data.bookings.length > 0){
                        allBookings = data.bookings;
                        console.log("bookings:",allBookings);
                    }

                })
                .catch(error => {
                    console.error('Error fetching timeslot:', error); 
                    const availabilityList = document.getElementById('availability-list');
                    availabilityList.innerHTML = '<p class="no-data-message">Error loading data</p>';
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
                    const startTimeB = b.slot.split(' -they - ')[0];
                    return parseTimeToMinutes(startTimeA) - parseTimeToMinutes(startTimeB);
                });
            }

            function sortHolidays(holidays) {
                return [...holidays].sort((a, b) => {

                    const dateA = new Date(a.date);
                    const dateB = new Date(b.date);
                    if (dateA < dateB) return -1;
                    if (dateA > dateB) return 1;

                    const startTimeA = a.slot.split(' - ')[0];
                    const startTimeB = b.slot.split(' - ')[0];
                    return parseTimeToMinutes(startTimeA) - parseTimeToMinutes(startTimeB);
                });
            }

            function normalizeTimeslot(timeslot) {
                if (!timeslot || typeof timeslot !== 'string') return '';
                const [start, end] = timeslot.split(' - ').map(time => {
                    if (!time.includes('AM') && !time.includes('PM')) {
  
                        const [hours, minutes] = time.split(':').map(Number);
                        const period = hours >= 12 ? 'PM' : 'AM';
                        const adjustedHours = hours % 12 || 12;
                        return `${adjustedHours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')} ${period}`;
                    }
 
                    const [timePart, period] = time.trim().split(' ');
                    const [hours, minutes] = timePart.split(':').map(Number);
                    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')} ${period}`;
                });
                return `${start} - ${end}`;
            }

            function doTimeslotsOverlap(slot1, slot2) {
                const normalizedSlot1 = normalizeTimeslot(slot1);
                const normalizedSlot2 = normalizeTimeslot(slot2);
                if (!normalizedSlot1 || !normalizedSlot2) return false;
                const [start1, end1] = normalizedSlot1.split(' - ').map(parseTimeToMinutes);
                const [start2, end2] = normalizedSlot2.split(' - ').map(parseTimeToMinutes);
                return start1 <  end2 && start2 < end1;
            }

            function renderDefaultTable(timeSlots) {
                const availabilityList = document.getElementById('availability-list');
                availabilityList.innerHTML = '';

                if (timeSlots.length === 0) {
                availabilityList.innerHTML = '<p class="no-data-message">No availability scheduled yet</p>';
                return;
                }

                const availabilityItem = document.createElement('div');
                availabilityItem.classList.add('availability-item');

                let timesHTML = '';
                timeSlots.forEach(slot => {
                    timesHTML += `<div class="availability-time">${slot.slot}
                        <button class="delete-timeslot-btn" data-timeslot-id="${slot.id}">
                            <i class="ph ph-x"></i>
                        </button>
                    </div>`;
                });

                availabilityItem.innerHTML = `
                    <div class="availability-times">
                        ${timesHTML}
                    </div>
                `;
                
                availabilityList.appendChild(availabilityItem);

                // Attach event listeners to delete buttons
                document.querySelectorAll('.delete-timeslot-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const timeslotId = this.getAttribute('data-timeslot-id');
                        deleteTimeslot(timeslotId);
                    });
                });
            }

            function renderHolidayTable(timeSlots) {
                const specificAvailabilityList = document.getElementById('specific-availability-list');
                specificAvailabilityList.innerHTML = '';

                if (timeSlots.length === 0) {
                    specificAvailabilityList.innerHTML = '<p class="no-data-message">No specific availability scheduled yet</p>';
                    return;
                }

                const groupedByDate = timeSlots.reduce((acc, slot) => {
                    acc[slot.date] = acc[slot.date] || [];
                    acc[slot.date].push(slot);
                    return acc;
                }, {});

                Object.keys(groupedByDate).forEach(date => {
                    const slots = groupedByDate[date];
                    const availabilityItem = document.createElement('div');
                    availabilityItem.classList.add('availability-item');

                    let timesHTML = slots.map(slot => `
                        <div class="availability-time">${slot.slot}
                            <button class="delete-timeslot-btn" data-timeslot-id="${slot.id}" data-type="specific">
                                <i class="ph ph-x"></i>
                            </button>
                        </div>
                    `).join('');

                    availabilityItem.innerHTML = `
                        <div class="availability-date">${date}</div>
                        <div class="availability-times">${timesHTML}</div>
                    `;
                    specificAvailabilityList.appendChild(availabilityItem);
                });

                document.querySelectorAll('.delete-timeslot-btn[data-type="specific"]').forEach(button => {
                    button.addEventListener('click', function() {
                        const timeslotId = this.getAttribute('data-timeslot-id');
                        deleteblkTimeslot(timeslotId);
                    });
                });
            }

            setDefaultTimeBtn.addEventListener("click", function(e){ 
                e.preventDefault();

                const startTime = defaultStartTimeSelect.value;
                const endTime = defaultEndTimeSelect.value;
                const startPeriod = startPeriodSelect.value;
                const endPeriod = endPeriodSelect.value;

                const timePattern = /^(0?[1-9]|1[0-2]):[0-5][0-9]$/;
                if (!startTime || !endTime || !startPeriod || !endPeriod) {
                    alert("Please select both start and end times and AM/PM periods.");
                    return;
                }
                if (!timePattern.test(startTime) || !timePattern.test(endTime)) {
                    alert("Please enter times in HH:MM format (e.g., 9:00 or 09:00).");
                    return;
                }

                const timeslot = `${startTime} ${startPeriod} - ${endTime} ${endPeriod}`;

                const startMinutes = parseTimeToMinutes(`${startTime} ${startPeriod}`);
                const endMinutes = parseTimeToMinutes(`${endTime} ${endPeriod}`);
                if (startMinutes >= endMinutes) {
                    alert("End time must be after start time.");
                    return;
                }

                const exist = allTimeslots.some(existingSlot => 
                    normalizeTimeslot(existingSlot.slot) === normalizeTimeslot(timeslot)
                );
                if (exist) {
                    alert("This timeslot already exists.");
                    return;
                }


                const hasOverlap = allTimeslots.some(existingSlot => 
                    doTimeslotsOverlap(timeslot, existingSlot.slot)
                );

                if (hasOverlap) {
                    alert("This timeslot overlaps with an existing one.");
                    return;
                }
                
                const formData = new FormData();
                formData.append("slot", timeslot);
                formData.append("trainer_id", trainerId);
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}: ${value}`);
                }
                    
                if (!confirm("Are you sure you want to add this timeslot?")) return;
                fetch('<?php echo URLROOT; ?>/trainer/timeslot/add', {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                    alert("Timeslot added successfully!");
                    location.reload();
                    } else {
                    alert("Error: " + result.message);
                    }
                })
                .catch(error => console.error("Error inserting holiday:", error));
            });

            function deleteTimeslot(timeslotId) {
                if (!confirm("Are you sure you want to delete this timeslot?")) return;

                const formData = new FormData();
                formData.append("id", timeslotId);
                for (let pair of formData.entries()) {
                    console.log(`${pair[0]}: ${pair[1]}`);
                }

                fetch('<?php echo URLROOT; ?>/trainer/timeslot/delete', {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert("Timeslot deleted successfully!");
                        location.reload();
                    } else {
                        alert("Error: " + result.message);
                    }
                })
                .catch(error => {
                    console.error("Error deleting timeslot:", error);
                    alert("Error deleting timeslot. Please try again.");
                });
            }

            function isBookingConflicting(allBookings, holidayDate, holidaySlot) {
                console.log('isBookingConflicting inputs:', { allBookings, holidayDate, holidaySlot });
                const conflictingIds = [];
                for (const booking of allBookings) {
                    if (booking.booking_date === holidayDate && booking.timeslot === holidaySlot) {
                        conflictingIds.push(booking.id);
                    }
                }

                console.log("ifds",conflictingIds);
                
                return conflictingIds;
            }

            function rejectConflictingBookings(allBookings, holidayDate, holidaySlot) {
                console.log('rejectConflictingBookings inputs:', { holidayDate, holidaySlot });

                if (!Array.isArray(allBookings)) {
                    console.error('allBookings is not an array:', allBookings);
                    return Promise.resolve(); // Resolve with no action if no valid bookings
                }

                const conflictingIds = isBookingConflicting(allBookings, holidayDate, holidaySlot);
                const conflictingBookings = allBookings.filter(booking =>
                    conflictingIds.includes(booking.id)
                );

                console.log("len",conflictingBookings);

                if (conflictingBookings.length === 0) return Promise.resolve();

                const promises = conflictingBookings.map(booking => {
                    const formData = new FormData();
                    formData.append('id', booking.id);
                    formData.append('status', 'rejected');

                    for (let pair of formData.entries()) {
                        console.log(pair[0]+ ': ' + pair[1]);
                    }


                    return fetch('<?php echo URLROOT; ?>/trainer/bookings/edit', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (!result.success) {
                            throw new Error(`Failed to reject booking ID ${booking.id}: ${result.message}`);
                        }
                    });
                });

                return Promise.all(promises);
            }

            setSpecificTimeBtn.addEventListener("click", function(e) {
                e.preventDefault();

                const date = holidayDateInput.value;
                const timeslotId = blockTimeSelect.value;
                const timeslotText = blockTimeSelect.options[blockTimeSelect.selectedIndex].text;

                if (!date) {
                    alert("Please select a date.");
                    return;
                }
                if (!timeslotId) {
                    alert("Please select a timeslot or Fullday.");
                    return;
                }

                const selectedDate = new Date(date);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                if (selectedDate < today) {
                    alert("Cannot select a past date.");
                    return;
                }

                const exist = allHolidays.some(holiday =>
                    holiday.date === date && (timeslotId === "fullday" ? holiday.slot === "Fullday" : holiday.slot === timeslotText)
                );
                if (exist) {
                    alert("This timeslot is already blocked for the selected date.");
                    return;
                }

                if (!confirm("Are you sure you want to block this timeslot? This will reject any existing bookings for the selected timeslot or day.")) return;

                rejectConflictingBookings(allBookings, date, timeslotText)
                    .then(()=> {
                        const formData = new FormData();
                        formData.append("date", date);
                        formData.append("slot", timeslotText);
                        formData.append("trainer_id", trainerId);

                        return fetch('<?php echo URLROOT; ?>/trainer/holiday/add', {
                            method: "POST",
                            body: formData
                        });
    
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            alert("Timeslot blocked successfully!");
                            location.reload();
                        } else {
                            alert("Error: " + result.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error blocking timeslot or reject bookings:", error);
                        alert("Error blocking timeslot. Please try again.");
                    });
            });

            // delete block time
            function deleteblkTimeslot(timeslotId) {
                if (!confirm("Are you sure you want to delete this timeslot?")) return;

                const formData = new FormData();
                formData.append("id", timeslotId);
                for (let pair of formData.entries()) {
                    console.log(`${pair[0]}: ${pair[1]}`);
                }

                fetch('<?php echo URLROOT; ?>/trainer/holiday/delete', {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert("Timeslot deleted successfully!");
                        location.reload();
                    } else {
                        alert("Error: " + result.message);
                    }
                })
                .catch(error => {
                    console.error("Error deleting timeslot:", error);
                    alert("Error deleting timeslot. Please try again.");
                });
            }

        });
         
    </script>
  </body>
</html>