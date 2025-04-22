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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/dashboard.css?v=<?php echo time();?>" />
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
                <i class="ph ph-users"></i>
                <div class="status-badge">
                  <span>+9.4%</span>
                </div>
              </div>
              <div class="lower">
                <p>Total Members</p>
                <div class="progress">
                  <h1>20000</h1>
                  <div class="text-muted">
                    <small>Last 30 days</small>
                  </div>
                </div>
              </div>
            </div>
            <div class="insight-card card-2">
              <div class="upper">
                <i class="ph ph-user-plus"></i>
                <div class="status-badge">
                  <span>+9.4%</span>
                </div>
              </div>
              <div class="lower">
                <p>New Members</p>
                <div class="progress">
                  <h1>20000</h1>
                  <div class="text-muted">
                    <small>Last 30 days</small>
                  </div>
                </div>
              </div>
            </div>
            <div class="insight-card card-3">
              <div class="upper">
                <i class="ph ph-chat-circle-text"></i>
                <div class="status-badge">
                  <span>+9.4%</span>
                </div>
              </div>
              <div class="lower">
                <p>Total Inquiries</p>
                <div class="progress">
                  <h1>20000</h1>
                  <div class="text-muted">
                    <small>Last 30 days</small>
                  </div>
                </div>
              </div>
            </div>
            <div class="insight-card card-4">
              <div class="upper">
                <i class="ph ph-calendar-check"></i>
                <div class="status-badge">
                  <span>+9.4%</span>
                </div>
              </div>
              <div class="lower">
                <p>Total Event Attendees</p>
                <div class="progress">
                  <h1>20000</h1>
                  <div class="text-muted">
                    <small>Last 30 days</small>
                  </div>
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
    <option value="week">This Week</option>
  </select>
</div>



            </div>
            <div class="lower">
              <canvas id="BarChart"></canvas>
            </div>
          </div>
        </div>

        <div class="right-column">
          <div class="doughnut-chart">
            <div class="upper-text">
              <h2>Membership Types</h2>
              <p>Membership plan distribution</p>
            </div> 
            <div class="lower">
              <canvas id="DoughnutChart"></canvas>
            </div>
          </div>
          <div class="recent-announcements">
            <?php require APPROOT.'/views/components/recent-announcements.view.php' ?>
          </div>
        </div>
      </div>

      <div class="chart">
        <div class="chart-header">
          <h2>Bookings</h2>
        </div>
        <div class="chart-container">
          <table class="paymentHistoryTable">
            <thead>
              <tr>
                <th>Date</th>
                <th>Trainer's Detail</th>
                <th>Time</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/member/member-script.js?v=<?php echo time();?>"></script>

    <script>
      let barChartInstance;  // Declare globally
      let delayed;  // Declare globally
       selectedPeriod = 'today';  // Default period
      // Listen for the DOM content to be loaded
      document.addEventListener("DOMContentLoaded", function() {
          const timePeriodSelect = document.getElementById('time-period');
          if (timePeriodSelect) {
              // Set the default period to 'today'
              fetchAndUpdateChart('today');

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
            console.log('Fetched Data:', data);  // Log the fetched data
            updateBarChart(data.attendance);  // Update the chart with attendance data
          })
          .catch(error => console.error('Error fetching data:', error));
      }

      // Update Bar Chart function
      function updateBarChart(attendanceData) {
        console.log('Attendance Data:', attendanceData); // Debugging: Check the data being passed

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
        if (selectedPeriod === 'week') {
        // Labels for days of the week (Tuesday to Monday)
        labels = ['Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', 'Mon'];
        attendanceCounts = new Array(7).fill(0);  // Initialize with 0 for each day

        // Process the attendance data for the week
        if (attendanceData && attendanceData.attendance_by_day) {
            attendanceData.attendance_by_day.forEach(item => {
                const dayName = item.day_name;  // Get the day name
                const attendanceCount = item.attendance_count;

                // Map the attendance count to the corresponding day (index)
                const dayIndex = labels.indexOf(dayName.substring(0, 3)); // Get first 3 characters (e.g., "Tue" for "Tuesday")
                if (dayIndex >= 0) {
                    attendanceCounts[dayIndex] = attendanceCount;
                }
            });
        }
    } else if (selectedPeriod === 'today') {
        // Labels for hours of the day (5 AM to 11 PM)
        labels = Array.from({ length: 19 }, (_, index) => `${index + 5} AM`);
        attendanceCounts = new Array(19).fill(0);

        // Process the attendance data for the day
        if (attendanceData && attendanceData.attendance_by_hour) {
            attendanceData.attendance_by_hour.forEach(item => {
                const hour = item.hour;
                if (hour >= 5 && hour <= 23) {
                    // Set the corresponding attendance count for the hour
                    attendanceCounts[hour - 5] = item.attendance_count;
                }
            });
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
    </script>

  </body>
</html>
