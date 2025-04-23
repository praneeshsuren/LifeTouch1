<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
  <!-- STYLESHEET -->
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/admin-style.css?v=<?php echo time();?>" />
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/calendar-style.css?v=<?php echo time();?>" />
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
  <title><?php echo APP_NAME; ?></title>
</head>

<body>
  <?php
  if (isset($_SESSION['success'])) {
    echo "<script>alert('" . $_SESSION['success'] . "');</script>";
    unset($_SESSION['success']);
  }

  if (isset($_SESSION['error'])) {
    echo "<script>alert('" . $_SESSION['error'] . "');</script>";
    unset($_SESSION['error']);
  }
  ?>

  <section class="sidebar">
    <?php require APPROOT . '/views/components/admin-sidebar.view.php' ?>
  </section>

  <main>
    <div class="title">
      <h1>Member Attendance</h1>
      <div class="greeting">
        <?php require APPROOT . '/views/components/user-greeting.view.php' ?>
      </div>
    </div>

    <div class="view-user-container">
      <div class="navbar-container">
        <div class="navbar">
          <ul class="nav-links">
            <li><a href="" id="userDetailsLink"><i class="ph ph-user"></i>User Details</a></li>
            <li><a href="" id="attendanceLink"><i class="ph ph-calendar-dots"></i>Member Attendance</a></li>
            <li><a href="" id="paymentHistoryLink"><i class="ph ph-money"></i>Payment History</a></li>
            <li><a href="" id="supplementRecordsLink"><i class="ph ph-barbell"></i>Supplement Records</a></li>
          </ul>
        </div>
      </div>

        <div class="user-container">
            <div class="calendar-container">
                <div class="calendar">
                    <div class="member-calendar-header">
                        <button class="nav-button" id="prevMonth">Previous</button>
                        <span id="monthLabel"></span>
                        <button class="nav-button" id="nextMonth">Next</button>
                    </div>

                    <div class="calendar-weekdays">
                        <div class="weekday">Sun</div>
                        <div class="weekday">Mon</div>
                        <div class="weekday">Tue</div>
                        <div class="weekday">Wed</div>
                        <div class="weekday">Thu</div>
                        <div class="weekday">Fri</div>
                        <div class="weekday">Sat</div>
                    </div>

                    <div class="calendar-grid" id="calendarGrid">
                        <!-- Calendar cells will be populated here -->
                    </div>
                </div>
            </div>
        </div>
        
    </div>

  </main>

  <!-- SCRIPT -->
  <script src="<?php echo URLROOT; ?>/assets/js/admin-script.js?v=<?php echo time(); ?>"></script>
  <script>
        document.addEventListener('DOMContentLoaded', () => {
            const currentDate = new Date();
            let currentMonth = currentDate.getMonth(); // 0 for January, 11 for December
            let currentYear = currentDate.getFullYear();

            let attendanceData = {}; // This will hold the attendance data for each day

            function getUrlParameter(name) {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(name);
            }

            const memberId = getUrlParameter('id');
            if (memberId) {
                document.getElementById('userDetailsLink').href = `<?php echo URLROOT; ?>/admin/members/viewMember?id=${memberId}`;
                document.getElementById('attendanceLink').href = `<?php echo URLROOT; ?>/admin/members/memberAttendance?id=${memberId}`;
            } else {
                alert('No member selected.');
            }

            function fetchAttendance(month, year) {
                fetch(`<?php echo URLROOT; ?>/Attendance/getAttendance?member_id=${memberId}&month=${month + 1}&year=${year}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.attendanceByDate === undefined) {
                            console.error("Invalid JSON response");
                            alert("Error: No data available for this month.");
                        } else {
                            attendanceData = data.attendanceByDate;
                            generateCalendar(month, year);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching attendance data:', error);
                        alert('Error fetching attendance data');
                    });
            }


            // Generate calendar for the selected month
            function generateCalendar(month, year) {
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const firstDayOfMonth = new Date(year, month, 1).getDay();
                const lastDayOfMonth = new Date(year, month, daysInMonth).getDay();
                const calendarGrid = document.getElementById('calendarGrid');
                calendarGrid.innerHTML = '';

                // Add empty cells before the first day of the month
                for (let i = 0; i < firstDayOfMonth; i++) {
                    const emptyCell = document.createElement('div');
                    emptyCell.className = 'calendar-cell empty-cell';
                    calendarGrid.appendChild(emptyCell);
                }

                // Add actual day cells
                for (let day = 1; day <= daysInMonth; day++) {
                    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    const cell = document.createElement('div');
                    cell.className = 'calendar-cell';

                    const header = `<div class="date">${day}</div>`;
                    const logs = attendanceData[dateStr] || [];

                    let summary = '';
                    if (logs.length > 0) {
                        const lastLog = logs[logs.length - 1];
                        summary = `<div class="attendance"><span>In: ${lastLog.time_in}</span><span>Out: ${lastLog.time_out}</span></div>`;
                    }

                    cell.innerHTML = header + summary;

                    // Add event listener to show modal for full attendance logs
                    if (logs.length > 0) {
                        cell.addEventListener('click', () => showAttendanceModal(logs, dateStr));
                    }

                    calendarGrid.appendChild(cell);
                }

                // Add empty cells after the last day of the month
                for (let i = lastDayOfMonth; i < 6; i++) {
                    const emptyCell = document.createElement('div');
                    emptyCell.className = 'calendar-cell empty-cell';
                    calendarGrid.appendChild(emptyCell);
                }

                // Update the month label
                document.getElementById('monthLabel').textContent = `${new Date(year, month).toLocaleString('default', { month: 'long' })} ${year}`;
            }

            function showAttendanceModal(logs, dateStr) {
    const modal = document.createElement('div');
    modal.className = 'attendance-modal';

    // Generate the table rows
    const tableRows = logs.map((r, index) => `
        <tr>
            <td>${index + 1}</td>
            <td>${r.time_in}</td>
            <td>${r.time_out}</td>
        </tr>
    `).join('');

    // Create modal content with table
    modal.innerHTML = `
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h3>Attendance Details for ${dateStr}</h3>
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Time-in</th>
                        <th>Time-out</th>
                    </tr>
                </thead>
                <tbody>
                    ${tableRows}
                </tbody>
            </table>
        </div>
    `;

    // Append the modal to the body
    document.body.appendChild(modal);

    // Add 'active' class to show the modal
    modal.classList.add('active');

    // Close the modal when the close button is clicked
    modal.querySelector('.close-btn').addEventListener('click', () => {
        modal.classList.remove('active');
        setTimeout(() => document.body.removeChild(modal), 300); // Remove the modal after the transition
    });

    // Close modal by clicking outside the modal content
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('active');
            setTimeout(() => document.body.removeChild(modal), 300);
        }
    });
}



            // Navigate to the previous month
            document.getElementById('prevMonth').addEventListener('click', () => {
                if (currentMonth === 0) {
                    currentMonth = 11;
                    currentYear--;
                } else {
                    currentMonth--;
                }
                fetchAttendance(currentMonth, currentYear);
            });

            // Navigate to the next month
            document.getElementById('nextMonth').addEventListener('click', () => {
                if (currentMonth === 11) {
                    currentMonth = 0;
                    currentYear++;
                } else {
                    currentMonth++;
                }
                fetchAttendance(currentMonth, currentYear);
            });

            // Initial fetch for the current month
            fetchAttendance(currentMonth, currentYear);
        });
  </script>
</body>
</html>
