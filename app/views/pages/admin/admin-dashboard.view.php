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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/admin-style.css?v=<?php echo time();?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/dashboard.css?v=<?php echo time();?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/sidebar.css?v=<?php echo time();?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>

    <section class="sidebar">
        <?php require APPROOT.'/views/components/admin-sidebar.view.php' ?>
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
                <p>Track the total number of member who attended</p>
              </div>

              <div class="period-select">
                <select id="time-period" name="time-period">
                  <option value="week">This Week</option>
                  <option value="month">This Month</option>
                  <option value="year">This Year</option>
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

    </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/admin-script.js?v=<?php echo time();?>"></script>
    <script>
      const ctxBarChart = document.getElementById('BarChart').getContext('2d');
      let delayed;

      const dataBarChart = {
        labels: [
          'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
        ],
        datasets: [{
          label: 'Number of Members',
          data: [15, 25, 35, 45, 30, 20], // Replace with your actual data
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

      // Chart configuration
      const configBarChart = {
        type: 'bar',
        data: dataBarChart,
        options: {
          responsive: true,
          maintainAspectRatio: true,
          animation: {
            duration: 2000, // Duration of the animation (2 seconds)
            easing: 'easeOutQuart', 
            onComplete: () => {
              delayed = true;
            },
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
              grid: {
                drawBorder: false,
                color: 'rgba(0, 0, 0, 0.1)'
              },
              ticks: {
                stepSize: 10
              },
              stacked: true
            },
            x: {
              grid: {
                drawBorder: false,
                display: false
              },
              ticks: {
                font: {
                  family: "'Poppins', sans-serif"
                }
              },
              stacked: true
            }
          },
          plugins: {
            legend: {
              position: 'top',
              labels: {
                boxWidth: 20,
                font: {
                  family: "'Poppins', sans-serif"
                }
              }
            },
            tooltip: {
              backgroundColor: 'rgba(0, 0, 0, 0.7)',
              padding: 10,
              titleFont: {
                family: "'Poppins', sans-serif"
              },
              bodyFont: {
                family: "'Poppins', sans-serif"
              }
            }
          }
        }
      };

      // Create the chart
      new Chart(ctxBarChart, configBarChart);

      const ctxDoughnutChart = document.getElementById('DoughnutChart').getContext('2d');

      const dataDoughnutChart = {
          labels: [
              'Monthly', 'Quarterly', 'Semi-Annually', 'Annually'
          ],
          datasets: [{
              label: 'Distribution of Membership Plans',
              data: [300, 150, 220, 80], // Replace with your actual data
              fill: true,
              backgroundColor: [
                  'rgb(255, 99, 132)',  // Red
                  'rgb(54, 162, 235)',  // Blue
                  'rgb(255, 205, 86)',  // Yellow
                  'rgb(75, 192, 192)'   // Teal
              ],
              hoverOffset: 4
          }]
      };

      const configDoughnutChart = {
          type: 'doughnut',
          data: dataDoughnutChart, // Correct reference to the data object
          options: {
              responsive: true,
              maintainAspectRatio: true,
              animation: {
                  duration: 1500,
                  easing: 'easeInOutQuart',
                  onComplete: () => {
                      delayed = true;
                  },
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
                      labels: {
                          boxWidth: 20,
                          font: {
                              family: "'Poppins', sans-serif"
                          }
                      }
                  },
                  tooltip: {
                      backgroundColor: 'rgba(0, 0, 0, 0.7)',
                      padding: 10,
                      titleFont: {
                          family: "'Poppins', sans-serif"
                      },
                      bodyFont: {
                          family: "'Poppins', sans-serif"
                      }
                  }
              }
          }
      };

      // Create the doughnut chart
      new Chart(ctxDoughnutChart, configDoughnutChart);

    </script>
  </body>
</html>

