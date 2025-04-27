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
        
        <h1>Bookings</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>

      </div>

      <div class="user-table-container">
          <div class="filters" >
            <button class="filter active">ALL</button>
            <button class="filter">Booked</button>
            <button class="filter">Pending</button>
            <button class="filter">Rejected</button>
          </div>

          <div class="user-table-header">
            <input type="text" placeholder="Search" class="search-input">
          </div>
          
          <div class="user-table-wrapper">
            <table class='user-table'>
              <thead>
                  <tr>
                      <th>Member Id</th>
                      <th>Member Profile</th>
                      <th>Member Name</th>
                      <th>Date</th>
                      <th>Time</th>
                      <th>Status</th>
                  </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
          <div id="bookingModal" class="bookingModal">
            <div class="bookingModal-content" >
                <div class="modal-header">
                    <h2 class="modal-title">Booking Request</h2>
                    <div class="bookingModalClose">&times;</div>
                </div>

                <div class="bookingModal-body" style = "color:black">
                    <form id="bookingForm">
                        <div class="booking-details">
                            <div class="detail-row" style="margin-top:5px;">
                                <div class="detail-label">Name:</div>
                                <div class="detail-value"  id="modalMemberName"></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Date:</div>
                                <div class="detail-value"  id="modalBookingDate" ></div>
                            </div>

    
                            <div class="detail-row">
                                <div class="detail-label">Time:</div>
                                <div class="detail-value"  id="modalTimeslot" ></div>
                            </div>

                            <div class="detail-row">
                                <div class="detail-label">Status</div>
                                <div class="detail-value">
                                    <select id="modalStatusSelect">
                                        <option value="pending">Pending</option>
                                        <option value="booked">Confirm</option>
                                        <option value="rejected">Reject</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="action-button" type="submit" id="submitBtn" name="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
          </div>

      </div>
      
      </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/trainer-script.js?v=<?php echo time();?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () =>{ 
            const tableBody = document.querySelector('.user-table tbody');
            const filterButtons = document.querySelectorAll('.filters .filter');
            const searchInput = document.querySelector('.search-input'); 
            let allBookings = [];
            let filterBookings = [];
            let holidays = [];

            fetch('<?php echo URLROOT; ?>/trainer/bookings/api')
                .then(response => {
                    console.log('Response Status:', response.status); // Log response status
                    return response.json();
                })
                .then(data => {
                    // console.log("Fetched booking data:", data.bookings);
                    // console.log("Fetched holiday data:", data.holidays);

                    holidays = data.holidays;

                    if (Array.isArray(data.bookings) && data.bookings.length > 0){
                        allBookings = data.bookings;
                        renderTable(allBookings);
                    }
                })
                .catch(error => {
                    console.error('Error fetching booking:', error); // Log the error
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="11" style="text-align: center;">Error loading data</td>
                        </tr>
                    `;
                });


                filterButtons.forEach(button => {
                    button.addEventListener('click', () =>{
                        filterButtons.forEach(btn => btn.classList.remove('active'));
                        button.classList.add('active');

                        let filterStatus = button.textContent.trim().toLowerCase();
                        filterBookings = allBookings;

                        if(filterStatus !== 'all'){
                            filterBookings = allBookings.filter(booking => booking.status.toLowerCase() === filterStatus);
                        }

                        applySearchFilter();
                    });
                });

                searchInput.addEventListener('input', applySearchFilter);

                function applySearchFilter() {
                    let searchQuery = searchInput.value.toLowerCase();

                    let filteredResults = filterBookings.filter(booking => {
                        return booking.member_name.toLowerCase().includes(searchQuery) ||
                            booking.member_id.toLowerCase().includes(searchQuery) ||
                            booking.booking_date.toLowerCase().includes(searchQuery) ||
                            booking.timeslot.toLowerCase().includes(searchQuery);
                    });

                    renderTable(filteredResults);
                }

                function renderTable(bookings){
                    tableBody.innerHTML = '';

                    filterButtons.forEach(button =>{
                        if(button.textContent.trim().toLowerCase() === 'new' && statusFilter === 'pending'){
                            button.classList.add('active');
                        }
                    });

                    const now = new Date();
                    const dateToday = new Date();
                    dateToday.setHours(0, 0, 0, 0);
                    
                    const futureBookings = [];
                    const pastBookings = [];

                    bookings.forEach(booking => {
                        const bookingDate = new Date(booking.booking_date);
                        bookingDate.setHours(0, 0, 0, 0);

                        if (bookingDate < dateToday) {
                            pastBookings.push(booking); // clearly past
                        } else if (bookingDate.getTime() === dateToday.getTime()) {
                            // Booking is for today, now check the end time of the slot
                            const endTimeStr = booking.timeslot.split(" - ")[1].trim(); // e.g., "11:00 AM"
                            const endTime = convertTo24hrs(endTimeStr);

                            const bookingEnd = new Date(booking.booking_date + ' ' + endTimeStr);
                            if (bookingEnd.getTime() < now.getTime()) {
                                pastBookings.push(booking); // today's timeslot has already passed
                            } else {
                                futureBookings.push(booking); // today, but not yet passed
                            }
                        } else {
                            futureBookings.push(booking); // future date
                        }
                    });

                    if(bookings.length > 0) {
                        const appendBookingRows = (bookingList) => {
                            bookingList.forEach(booking => {
                                const row = document.createElement('tr');

                                row. innerHTML = `
                                    <td>${booking.member_id}</td>
                                    <td>
                                        <img src="<?php echo URLROOT; ?>/assets/images/Member/${booking.image || 'default-placeholder.jpg'}" alt="member Picture" class="user-image">
                                    </td>
                                    <td>${booking.member_name}</td>
                                    <td>${booking.booking_date}</td>
                                    <td>${booking.timeslot}</td>
                                    <td> 
                                        <div class="status open-modal">
                                            ${booking.status.charAt(0).toUpperCase() + booking.status.slice(1).toLowerCase()}
                                        </div>
                                    </td>
                                `;

                                const statusDiv = row.querySelector('.open-modal');

                                const isFutureBooking = futureBookings.includes(booking);
                                if (isFutureBooking) {
                                    statusDiv.style.cursor = 'pointer';
                                    statusDiv.addEventListener('click', (e) => {
                                        e.stopPropagation();
                                        openModal(booking);
                                    });
                                } 

                                tableBody.appendChild(row);
                            });
                        };

                        appendBookingRows(futureBookings);

                        if (pastBookings.length > 0) {
                            const pastHeading = document.createElement('tr');
                            pastHeading.innerHTML = `
                                <td colspan="11" style="font-weight: bold; background: #f9f9f9;">Past Bookings</td>
                            `;
                            tableBody.appendChild(pastHeading);
                            appendBookingRows(pastBookings);
                        }
                    } else {
                        console.log('No Bookings found.');
                        tableBody.innerHTML = `
                        <tr>
                            <td colspan="11" style="text-align: center;">No Bookings available</td>
                        </tr>
                        `;
                    }
                }

                const modal = document.getElementById("bookingModal");
                const closeModal = document.querySelector(".bookingModalClose");
                let currentBooking = null;

                function openModal(booking){
                    modal.style.display = "block";

                    document.getElementById('modalMemberName').textContent = booking.member_name;
                    document.getElementById('modalBookingDate').textContent = booking.booking_date;
                    document.getElementById('modalTimeslot').textContent = booking.timeslot;

                    const statusSelect = document.getElementById('modalStatusSelect');
                    if (statusSelect) {
                        console.log("statusSelect exists", statusSelect);
                        // Set status value if it's a valid option
                        const statusValue = booking.status.toLowerCase();
                        if (["pending", "booked", "rejected"].includes(statusValue)) {
                            statusSelect.value = statusValue;
                        } else {
                            console.error("Invalid status value:", booking.status);
                        }
                        console.log(statusSelect.value);
                    } else {
                        console.error("modalStatusSelect element not found");
                    }
                    currentBooking = booking;
                }

                document.getElementById('modalStatusSelect').addEventListener('change', function () {
                    if (currentBooking) {
                        currentBooking.status = this.value;
                    }
                });

                document.getElementById("bookingForm").addEventListener("submit", function (event) {
                    event.preventDefault();

                    const statusSelect = document.getElementById("modalStatusSelect");
                    const selectedStatus = statusSelect.value;

                    const isHoliday = holidays.some(h => h.date === currentBooking.booking_date);
                    if (isHoliday && selectedStatus === "booked") {
                        alert("You cannot book on a holiday. Please choose 'pending' or 'rejected'.");
                        return;
                    }
                        
                    const formData = new FormData(this);
                    if (!confirm("Are you sure you want to update ?")) return;
                    fetch('<?php echo URLROOT; ?>/trainer/bookings/edit', {
                        method :'POST',
                        headers: {
                            "Content-type": "application/x-www-form-urlencoded",
                        },
                        body: `id=${encodeURIComponent(currentBooking.id)}&status=${encodeURIComponent(selectedStatus)}`,
                    })
                    .then(response => response.json())
                    .then(result =>{
                        console.log(result);
                        if (!result.success) {
                            alert("booking updated successfully!");
                            modal.style.display = "none";
                            location.reload();
                        } else {
                            alert("Error: " + result.message);
                        }
                    })
                    .catch(error => console.error("Error updating booking:", error));
                });

                closeModal.addEventListener('click',function() {
                    modal.style.display = 'none';
                });

                window.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        modal.style.display = 'none';
                    }
                });

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
        });
    </script>
