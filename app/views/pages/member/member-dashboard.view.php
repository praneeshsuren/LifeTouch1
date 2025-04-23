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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/member-dashboard.css?v=<?php echo time();?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/sidebar-greeting.css?v=<?php echo time();?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>

    <section class="sidebar">
      <?php require APPROOT.'/views/components/member-sidebar.view.php' ?>
    </section>
    
    <main>
      <div class="title">
        <h1>Dashboard</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>
      </div>

      <div class="dashboard-container">
        <div class="left-column">
          <div class="insights">
            <div class="insight-card card-1">
              <div class="upper">
                <i class="ph ph-barbell"></i>
              </div>
              <div class="lower">
                <p>Total Workout Schedules Completed</p>
                <div class="progress">
                  <h1><?php echo $data['completedSchedules'] ?></h1>
                </div>
              </div>
            </div>
            <div class="insight-card card-2">
              <div class="upper">
                <i class="ph ph-pint-glass"></i>
              </div>
              <div class="lower">
                <p>Total Supplements Purchased</p>
                <div class="progress">
                  <h1><?php echo $data['supplementsPurchased'] ?></h1>
                </div>
              </div>
            </div>
          </div>

          <div class="bar-chart">

            <div class="upper">
              <div class="upper-text">
                <h2>Member Attendance</h2>
                <p>Track the total number of members who attended</p>
              </div>
              <div class="period-select">
                <select id="time-period" name="time-period">
                  <option value="today" selected>Today</option>
                  <option value="yesterday">Yesterday</option>
                  <option value="week">This Week</option>
                </select>
              </div>
            </div>

            <div class="lower">
              <canvas id="BarChart"></canvas>
            </div>

          </div>

          <div class="bookings">
            <div class="chart-header">
              <h2>Bookings</h2>
            </div>
              <table class="paymentHistoryTable">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Trainer's Detail</th>
                    <th>Time</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
          </div>

        </div>

        <div class="right-column">
          <div class="recent-announcements">
            <?php require APPROOT.'/views/components/recent-announcements.view.php' ?>
          </div>
        </div>

      </div>

    </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/member/member-script.js?v=<?php echo time();?>"></script>
    <script>

      let barChartInstance;  // Declare globally
      let delayed;  // Declare globally
      let selectedPeriod = 'today';  // Default period
      const dateToday = new Date().toISOString().split('T')[0];

      // Listen for the DOM content to be loaded
      document.addEventListener("DOMContentLoaded", function() {
          const timePeriodSelect = document.getElementById('time-period');
          if (timePeriodSelect) {
              // Set the default period to 'today'
              fetchAndUpdateChart('today');
              fetchAndMarkBookings();

              // Add event listener to handle changes in the dropdown
              timePeriodSelect.addEventListener('change', function() {
                  selectedPeriod = this.value;  // Get the selected period (either "today" or "week")
                  console.log("Selected period:", selectedPeriod);  // Debugging: Check the selected period
                  fetchAndUpdateChart(selectedPeriod);  // Fetch and update the chart with the selected period
              });
          } else {
              console.error('The time-period select element was not found!');
          }
      });
      // Function to fetch data and update the chart
      function fetchAndUpdateChart(period) {
        fetch(`<?php echo URLROOT; ?>/member/index/api?period=${period}`)
          .then(response => response.json())
          .then(data => {
            updateBarChart(data.attendance);  // Update the chart with attendance data
          })
          .catch(error => console.error('Error fetching data:', error));
      }

      // Update Bar Chart function
      function updateBarChart(attendanceData) {

        const ctxBarChart = document.getElementById('BarChart');
        if (!ctxBarChart) {
          console.error('Canvas element not found');
          return;
        }

        // Destroy the existing chart before creating a new one
        if (barChartInstance) {
          barChartInstance.destroy();
        }

        const ctx = ctxBarChart.getContext('2d');
        let labels = [];

        // Dynamic labels and data based on the selected period
        if (selectedPeriod === 'week') {
        // Calculate the last 7 days excluding today
        const currentDate = new Date();
        labels = [];
        attendanceCounts = new Array(7).fill(0);  // Initialize with 0 for each day
        
        // Calculate the last 7 days (excluding today)
        for (let i = 1; i <= 7; i++) {
            const date = new Date();
            date.setDate(currentDate.getDate() - i);  // Subtract i days from today
            const dayName = date.toLocaleString('en-us', { weekday: 'short' });  // Get day name (e.g., "Mon")
            labels.push(dayName);  // Push the day name to labels
        }

        // Reverse the order of labels so that yesterday is last
        labels.reverse();

        // Process the attendance data for the last 7 days (excluding today)
        if (attendanceData && attendanceData.attendance_by_day) {
            attendanceData.attendance_by_day.forEach(item => {
                const dayName = item.day_name;  // Get the full day name (e.g., "Tuesday")
                const attendanceCount = item.attendance_count;

                // Map the attendance count to the corresponding day (index)
                const dayIndex = labels.indexOf(dayName.substring(0, 3)); // Get first 3 characters (e.g., "Tue" for "Tuesday")
                if (dayIndex >= 0) {
                    attendanceCounts[dayIndex] = attendanceCount;
                }
            });
        }
    } else if (selectedPeriod === 'today' || selectedPeriod === 'yesterday') {
      labels = Array.from({ length: 19 }, (_, index) => `${index + 5}:00`);
    attendanceCounts = new Array(19).fill(0);

    // Handle object returned from backend
    if (attendanceData && typeof attendanceData.attendance_by_hour === 'object') {
        Object.entries(attendanceData.attendance_by_hour).forEach(([hour, count]) => {
            const hourIndex = parseInt(hour, 10) - 5;
            if (hourIndex >= 0 && hourIndex < 19) {
                attendanceCounts[hourIndex] = count;
            }
        });
    } else {
        console.error("attendance_by_hour is not an object or has no data.");
    }
    }

        // Ensure the attendance counts match the labels length (this should always be true now)
        if (attendanceCounts.length !== labels.length) {
          console.error('Mismatch between labels and attendance counts');
          return;
        }

        const dataBarChart = {
          labels: labels,
          datasets: [{
            label: 'Number of Members Attended',
            data: attendanceCounts,
            fill: true,
            borderColor: '#5f63f2',
            backgroundColor: 'rgba(95, 99, 242, 0.2)',
            tension: 0.4,
            pointBackgroundColor: '#5f63f2',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7,
            borderWidth: 2,
            borderRadius: 10,
            barThickness: 50
          }]
        };

        const configBarChart = {
          type: 'bar',
          data: dataBarChart,
          options: {
            responsive: true,
            maintainAspectRatio: true,
            animation: {
              duration: 2000,
              easing: 'easeOutQuart',
              onComplete: () => { delayed = true; },
              delay: (context) => {
                let delay = 0;
                if (context.type === 'data' && context.mode === 'default' && !delayed) {
                  delay = context.dataIndex * 150 + context.datasetIndex * 50;
                }
                return delay;
              }
            },
            scales: {
              y: {
                beginAtZero: true,
                grid: { drawBorder: false, color: 'rgba(0, 0, 0, 0.1)' },
                ticks: { stepSize: 10 },
                stacked: true
              },
              x: {
                grid: { drawBorder: false, display: false },
                ticks: { font: { family: "'Poppins', sans-serif" } },
                stacked: true
              }
            },
            plugins: {
              legend: {
                position: 'top',
                labels: { boxWidth: 20, font: { family: "'Poppins', sans-serif" } }
              },
              tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.7)',
                padding: 10,
                titleFont: { family: "'Poppins', sans-serif" },
                bodyFont: { family: "'Poppins', sans-serif" }
              }
            }
          }
        };

        // Initialize the chart with the new data
        barChartInstance = new Chart(ctx, configBarChart);
      }

      function fetchAndMarkBookings() {
        fetch(`<?php echo URLROOT; ?>/member/index/api`)
          .then(response => {
            console.log('Response Status:', response.status);
            return response.json();
          })
          .then(data =>{
            console.log('Bookings data:', data);
            markBookings(data.bookings);
          })
          .catch(error => console.error('Error fetching bookings details:', error));
      }

      function markBookings(bookings) {
        const tbody = document.querySelector(".paymentHistoryTable tbody");
        if (!tbody) {
            console.error('Table body not found');
            return;
        }
        tbody.innerHTML = ""; // Clear existing rows
        // Filter bookings for "booked" and future dates
        const filteredBookings = bookings.filter(
            booking => booking.status === 'booked' && 
            new Date(booking.booking_date).getTime() >= new Date(dateToday).getTime()
        )
        .sort((a, b) => {
            const dateA = new Date(a.booking_date);
            const dateB = new Date(b.booking_date);

            // Sort by booking date
            if (dateA.getTime() !== dateB.getTime()) {
                return dateA - dateB;
            }

            // If dates are the same, sort by timeslot
            const timeA = convertTo24hrs(a.timeslot.split(" - ")[0]); // "09:00 AM"
            const timeB = convertTo24hrs(b.timeslot.split(" - ")[0]); // "11:00 AM"

            return timeA.getTime() - timeB.getTime();
        });

        // Create table rows for each filtered and sorted booking
        filteredBookings.forEach(booking => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${booking.booking_date}</td>
                <td>${booking.trainer_name}</td>
                <td>${booking.timeslot}</td>
            `;
            tbody.appendChild(tr);
        });
    }

    // Function to convert time from AM/PM to 24-hour format
    function convertTo24hrs(time) {
        const [hrMin, period] = time.trim().split(' '); // Split into hour, minute, and period (AM/PM)
        let [hr, min] = hrMin.split(':');  // Split the hour and minute
        hr = parseInt(hr, 10);
        min = parseInt(min, 10);
        let hr24 = hr;

        if (period === 'PM' && hr24 < 12) {
            hr24 += 12;  // Convert PM hours to 24-hour format
        } else if (period === 'AM' && hr24 === 12) {
            hr24 = 0;    // Convert 12 AM to 0 hours (midnight)
        }

        return new Date(1970, 0, 1, hr24, min); // Create a new Date object (with fixed date) for comparison
    }
    </script>

  </body>
</html>
