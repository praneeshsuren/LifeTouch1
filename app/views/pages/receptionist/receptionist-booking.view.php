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
        
        <h1>Bookings</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>

      </div>

      <div class="table-container">

          <div class="filters">
            <button class="filter active">ALL</button>
            <button class="filter">Booked</button>
            <button class="filter">Pending</button>
            <button class="filter">Rejected</button>
          </div>

          <div class="user-table-header">
            <input type="text" placeholder="Search" class="search-input">
            <button class="add-user-btn" onclick="window.location.href='<?php echo URLROOT; ?>/receptionist/calendar'">
                <i class="ph ph-calendar-dots"></i>
            </button>
          </div>
          
          <div class="user-table-wrapper">
            <table class='user-table'>
              <thead>
                  <tr>
                      <th>Member Id</th>
                      <th>Member Profile</th>
                      <th>Trainer Id</th>
                      <th>Trainer Profile</th>
                      <th>Date</th>
                      <th>Time</th>
                      <th>Status</th>
                  </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>

      </div>
      
      </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/receptionist-script.js?v=<?php echo time();?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () =>{ 
            const tableBody = document.querySelector('.user-table tbody');
            const filterButtons = document.querySelectorAll('.filters .filter');
            const searchInput = document.querySelector('.search-input'); 
            let allBookings = [];
            let filterBookings = [];

            fetch('<?php echo URLROOT; ?>/receptionist/bookings/api')
                .then(response => {
                    console.log('Response Status:', response.status); // Log response status
                    return response.json();
                })
                .then(data => {
                    console.log("Fetched booking data:", data.bookings);
                    console.log("Fetched holiday data:", data.holidays);

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
                            booking.trainer_name.toLowerCase().includes(searchQuery) ||
                            booking.member_id.toLowerCase().includes(searchQuery) ||
                            booking.trainer_id.toLowerCase().includes(searchQuery) ||
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

                    if(bookings.length > 0){
                        bookings.forEach(booking => {
                            const row = document.createElement('tr');
                            row.style.cursor = 'pointer';

                            let statusClass = "";
                            if (booking.status === "booked") {
                                statusClass = "booked";
                            } else if (booking.status === "pending") {
                                statusClass = "pending";
                            } else if (booking.status === "rejected") {
                                statusClass = "rejected";
                            }

                            row. innerHTML = `
                                <td>${booking.member_id}</td>
                                <td>
                                    <div>
                                        <img src="<?php echo URLROOT; ?>/assets/images/member/${booking.member_image || 'default-placeholder.jpg'}" alt="member Picture" class="user-image">
                                        <div>${booking.member_name}</div>
                                    </div>
                                </td>
                                <td>${booking.trainer_id}</td>
                                <td>
                                    <div>
                                        <img src="<?php echo URLROOT; ?>/assets/images/trainer/${booking.trainer_image || 'default-placeholder.jpg'}" alt="trainer Picture" class="user-image">
                                        <div>${booking.trainer_name}</div>
                                    </div>
                                </td>
                                <td>${booking.booking_date}</td>
                                <td>${booking.timeslot}</td>
                                <td> 
                                    <div class="status ${statusClass}">
                                        ${booking.status.charAt(0).toUpperCase() + booking.status.slice(1).toLowerCase()}
                                    </div>
                                </td>
                            `;

                            tableBody.appendChild(row);

                       });
                    } else {
                        console.log('No Bookings found.');
                        tableBody.innerHTML = `
                        <tr>
                            <td colspan="11" style="text-align: center;">No Bookings available</td>
                        </tr>
                        `;
                    }
                }
        });
    </script>
  </body>
</html>