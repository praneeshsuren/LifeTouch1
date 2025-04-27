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
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/manager-style.css?v=<?php echo time(); ?>" />
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/dashboard.css?v=<?php echo time(); ?>" />

  <!-- ICONS -->
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
  <!-- CHART.JS -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <title><?php echo APP_NAME; ?></title>
  <style>
    
  </style>

</head>

<body>

  <section class="sidebar">
    <?php require APPROOT . '/views/components/manager_sidebar.view.php' ?>
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
                    <h1><?php echo $data['members'] ?></h1>
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
                    <h1><?php echo $data['recentMembers'] ?></h1>
                    <div class="text-muted">
                      <small>Last 30 days</small>
                    </div>
                  </div>
              </div>
            </div>

            <div class="insight-card card-3">
              <div class="upper">
                <i class="ph ph-barbell"></i>
                <div class="status-badge">
                  <span>+9.4%</span>
                </div>
              </div>
              <div class="lower">
                  <p>Total Equipments</p>
                  <div class="progress">
                    <h1><?php echo $data['equipmentCount'] ?></h1>
                    <div class="text-muted">
                      <small>Last 30 days</small>
                    </div>
                  </div>
              </div>
            </div>

            <div class="insight-card card-4">
              <div class="upper">
                <i class="ph ph-pint-glass"></i>
                <div class="status-badge">
                  <span>+9.4%</span>
                </div>
              </div>
              <div class="lower">
                  <p>Total Supplements</p>
                  <div class="progress">
                    <h1><?php echo $data['supplementCount'] ?></h1>
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
                <p>Track the total number of member who attended</p>
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

        <div class="chart-row">

          <div class="bar-chart">
            
            <div class="upper">
              <div class="upper-text">
                <h2>Inventory</h2>
                <p>Inventory distribution</p>
              </div>
            </div>

            <div class="lower">
              <canvas id="InventoryChart"></canvas>
            </div>

          </div>

          <div class="line-chart">
            
            <div class="upper">
              <div class="upper-text">
                <h2>Income</h2>
                <p>Income distribution</p>
              </div>
            </div>

            <div class="lower">
              <canvas id="IncomeChart"></canvas>
            </div>

          </div>

        </div>

      </div>

    </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time();?>"></script>
    <script>
      
      let barChartInstance;  // Declare globally
      let doughnutChartInstance;  // Declare globally
      let delayed;  // Declare globally
      let selectedPeriod = 'today';  // Default period

      document.addEventListener("DOMContentLoaded", function() {
          const timePeriodSelect = document.getElementById('time-period');

          fetchAndUpdateDoughnutChart();

          if (timePeriodSelect) {
              // Set the default period to 'today'
              fetchAndUpdateBarChart('today');

              // Add event listener to handle changes in the dropdown
              timePeriodSelect.addEventListener('change', function() {
                  selectedPeriod = this.value;  // Get the selected period (either "today" or "week")
                  console.log("Selected period:", selectedPeriod);  // Debugging: Check the selected period
                  fetchAndUpdateBarChart(selectedPeriod);  // Fetch and update the chart with the selected period
              });
          } else {
              console.error('The time-period select element was not found!');
          }
      });

      function fetchAndUpdateBarChart(period) {
        fetch(`<?php echo URLROOT; ?>/Attendance/updateAttendanceGraph?period=${period}`)
          .then(response => response.json())
          .then(data => {
            updateBarChart(data.attendance);  // Update the chart with attendance data
          })
          .catch(error => console.error('Error fetching data:', error));
      }

      function fetchAndUpdateDoughnutChart() {
        fetch(`<?php echo URLROOT; ?>/MembershipPlan/getMembershipPlanCount`)
          .then(response => response.json())
          .then(data => {
            updateDoughnutChart(data.membershipPlans);
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
            barThickness: 25
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

      function updateDoughnutChart(membershipPlansData){
        const ctxDoughnutChart = document.getElementById('DoughnutChart');

        if (!ctxDoughnutChart) {
          console.error('Canvas element not found');
          return;
        }

        // Destroy the existing chart before creating a new one
        if (doughnutChartInstance) {
          doughnutChartInstance.destroy();
        }

        // Create a new chart instance
        const ctx = ctxDoughnutChart.getContext('2d');

        const dataDoughnutChart = {
            labels: membershipPlansData.map(plan => plan.plan),
            datasets: [{
                label: 'Distribution of Membership Plans',
                data: membershipPlansData.map(plan => plan.count), // Use the count from the fetched data
                fill: true,
                backgroundColor: generateBackgroundColors(membershipPlansData.length),  // Dynamically generate colors based on the number of labels
                hoverOffset: 4
            }]
        };

        // Function to generate a background color array based on the number of labels
        function generateBackgroundColors(numLabels) {
            const colors = []; // This will hold the generated color values
            const baseColors = [
                'rgb(255, 99, 132)',  // Red
                'rgb(54, 162, 235)',  // Blue
                'rgb(255, 205, 86)',  // Yellow
                'rgb(75, 192, 192)',  // Teal
                'rgb(153, 102, 255)', // Purple
                'rgb(255, 159, 64)',  // Orange
                'rgb(0, 255, 0)',     // Green
                'rgb(255, 0, 255)',   // Magenta
                'rgb(255, 165, 0)',   // Orange
                'rgb(128, 0, 128)'    // Purple
            ];

            for (let i = 0; i < numLabels; i++) {
                colors.push(baseColors[i % baseColors.length]);  // Loop through the base colors if more labels exist
            }

            return colors;
        }


        const configDoughnutChart = {
            type: 'doughnut',
            data: dataDoughnutChart,
            options: {
                responsive: true,
                maintainAspectRatio: true,
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart',
                    onComplete: () => { delayed = true; },
                    delay: (context) => {
                        let delay = 0;
                        if (context.type === 'data' && context.mode === 'default' && !delayed) {
                            delay = context.dataIndex * 100 + context.datasetIndex * 50;
                        }
                        return delay;
                    },
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

        // Create the doughnut chart
        doughnutChartInstance = new Chart(ctx, configDoughnutChart);
      }

      function navigateToAnnouncement(id) {
        const role = "<?php echo $_SESSION['role']; ?>";
        const url = `<?php echo URLROOT; ?>/${role}/announcement_main?announcement=${id}#announcement-${id}`;
        window.location.href = url;
      }

    </script>
  </body>
</html>