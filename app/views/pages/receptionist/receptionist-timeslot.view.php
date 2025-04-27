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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/receptionist-style.css?v=<?php echo time();?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>
    <section class="sidebar">
        <?php require APPROOT.'/views/components/receptionist-sidebar.view.php' ?>
    </section>

    <main>
        <div class="title">
            <h1>Default availability</h1>
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

                <div class="current-availability">
                    <h3>Current Default Availability</h3>
                    <div id="availability-list" class="availability-list">
                        <!-- Saved availability will be displayed here -->
                        <p class="no-data-message">No availability scheduled yet</p>
                    </div>
                </div>
            </div>
        </div>
      </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/receptionist-script.js?v=<?php echo time();?>"></script>
    <script>
        const defaultStartTimeSelect = document.getElementById('start-time');
        const defaultEndTimeSelect = document.getElementById('end-time');
        const startPeriodSelect = document.getElementById('start-period');
        const endPeriodSelect = document.getElementById('end-period');
        const setDefaultTimeBtn = document.getElementById('set-default-time-btn');
        const currentAvailabilityDisplay = document.getElementById('current-availability-display');
        let allTimeslot = [];

        document.addEventListener('DOMContentLoaded', () =>{ 
            fetch('<?php echo URLROOT; ?>/receptionist/timeslot/api')
                .then(response => {
                    console.log('Response Status:', response.status); // Log response status
                    return response.json();
                })
                .then(data => {
                    console.log("Fetched timeslot data:", data.timeslot);

                    if (Array.isArray(data.timeslot) && data.timeslot.length > 0){
                        allTimeslot = data.timeslot;
                        renderTable(allTimeslot);
                    }
                })
                .catch(error => {
                    console.error('Error fetching timeslot:', error); // Log the error
                    const availabilityList = document.getElementById('availability-list');
                    availabilityList.innerHTML = '<p class="no-data-message">Error loading data</p>';
                });

            // Helper function to convert 12-hour AM/PM time to minutes
            function parseTimeToMinutes(timeStr) {
                if (!timeStr) return 0;
                const [timePart, period] = timeStr.trim().split(' ');
                let [hours, minutes] = timePart.split(':').map(Number);
                if (period === 'PM' && hours !== 12) hours += 12;
                if (period === 'AM' && hours === 12) hours = 0;
                return hours * 60 + minutes;
            }

            // Helper function to normalize timeslot to standardized AM/PM format
            function normalizeTimeslot(timeslot) {
                if (!timeslot || typeof timeslot !== 'string') return '';
                const [start, end] = timeslot.split(' - ').map(time => {
                    if (!time.includes('AM') && !time.includes('PM')) {
                        // Assume input is 24-hour format and convert to AM/PM
                        const [hours, minutes] = time.split(':').map(Number);
                        const period = hours >= 12 ? 'PM' : 'AM';
                        const adjustedHours = hours % 12 || 12;
                        return `${adjustedHours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')} ${period}`;
                    }
                    // Standardize existing AM/PM format
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

            function renderTable(timeSlots) {
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

            setDefaultTimeBtn.addEventListener("click", function(e){ 
                e.preventDefault();

                const startTime = defaultStartTimeSelect.value;
                const endTime = defaultEndTimeSelect.value;
                const startPeriod = startPeriodSelect.value;
                const endPeriod = endPeriodSelect.value;
                console.log(startTime);

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
                // Validate start and end times
                const startMinutes = parseTimeToMinutes(`${startTime} ${startPeriod}`);
                    const endMinutes = parseTimeToMinutes(`${endTime} ${endPeriod}`);
                    if (startMinutes >= endMinutes) {
                        alert("End time must be after start time.");
                        return;
                    }

                const exist = allTimeslot.some(existingSlot => 
                    normalizeTimeslot(existingSlot.slot) === normalizeTimeslot(timeslot)
                );
                if (exist) {
                    alert("This timeslot already exists.");
                    return;
                }

                // Check for overlaps
                const hasOverlap = allTimeslot.some(existingSlot => 
                    doTimeslotsOverlap(timeslot, existingSlot.slot)
                );

                if (hasOverlap) {
                    alert("This timeslot overlaps with an existing one.");
                    return;
                }
                
                const formData = new FormData();
                formData.append("slot", timeslot);
                    
                if (!confirm("Are you sure you want to add this timeslot?")) return;
                fetch('<?php echo URLROOT; ?>/receptionist/timeslot/add', {
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

            // Delete timeslot function
            function deleteTimeslot(timeslotId) {
                if (!confirm("Are you sure you want to delete this timeslot?")) return;

                const formData = new FormData();
                formData.append("timeslot_id", timeslotId);
                for (let pair of formData.entries()) {
                    console.log(`${pair[0]}: ${pair[1]}`);
                }

                fetch('<?php echo URLROOT; ?>/receptionist/timeslot/delete', {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    if (!result.success) {
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