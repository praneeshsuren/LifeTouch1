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
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/dashboard.css?v=<?php echo time();?>" />
  <!-- ICONS -->
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
  <!-- CHART.JS -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <title><?php echo APP_NAME; ?></title>
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
              <i class="ph ph-barbell"></i>
                <div class="status-badge">
                  <span>+9.4%</span>
                </div>
              </div>
              <div class="lower">
                  <p>Total Equipments</p>
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
              <i class="ph ph-notepad"></i>
                <div class="status-badge">
                  <span>+9.4%</span>
                </div>
              </div>
              <div class="lower">
                  <p>Total Workouts Created</p>
                  <div class="progress">
                    <h1>20000</h1>
                    <div class="text-muted">
                      <small>Last 30 days</small>
                    </div>
                  </div>
              </div>
            </div>

          </div>

          <div class="bar-chart" style="margin-left:30px">

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

          <div class="bar-chart" style="width: 700px; margin-top:30px; margin-left:30px">
          <div class="upper-text">
                <h2>Member Age Distribution</h2>
              </div>
            <div class="lower">
                <canvas id="chart2"></canvas>
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
        <div class="right-column" style="width: 700px; margin-left: -835px;">
        <div class="doughnut-chart">
            <div class="upper-text">
                  <h2>Invetory</h2>
                  <p>Invetory distribution</p>
            </div> 
            <div class="lower">
                <canvas id="chart3"></canvas>
            </div>
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

<script>
    // Data for gym equipment counts
    const data = {
      labels: ['Treadmills', 'Dumbbells', 'Bikes', 'Rowing Machines'], // Equipment names on x-axis
      datasets: [{
        label: 'Count of Equipment',
        data: [70, 140, 50, 40], // Equipment counts
        backgroundColor: 'rgba(75, 192, 192, 0.5)', // Color for the bars
      }, ],
    };

    // Configuration for the bar chart
    const inconfig = {
      type: 'bar',
      data: data,
      options: {
        plugins: {
          title: {
            display: true,
            text: 'Gym Equipment Counts', // Chart title
          },
        },
        responsive: true,
        interaction: {
          intersect: false,
        },
        scales: {
          x: {
            stacked: false, // No stacking on the x-axis
            title: {
              display: true,
              text: 'Equipment', // X-axis label
            },
          },
          y: {
            stacked: false, // No stacking on the y-axis
            title: {
              display: true,
              text: 'Count', // Y-axis label
            },
          },
        },
      },
    };
    // Render the chart
    document.addEventListener('DOMContentLoaded', () => {
      const ctx3 = document.getElementById('chart3').getContext('2d');
      const ageChart = new Chart(ctx3, inconfig); // Create a new Chart.js instance
    });
  </script>

<script>
    // Define age category data
    // Define age category data
    const ageData = {
      labels: ['18-25', '26-35', '36-45', '46-60', '60+'], // Age categories
      datasets: [{
        label: 'Age Distribution',
        data: [12, 19, 10, 7, 5], // Example data for each age category
        backgroundColor: [
          'rgba(255, 99, 132, 0.5)', // Red
          'rgba(54, 162, 235, 0.5)', // Blue
          'rgba(255, 206, 86, 0.5)', // Yellow
          'rgba(75, 192, 192, 0.5)', // Green
          'rgba(153, 102, 255, 0.5)' // Purple
        ],
        borderColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)'
        ],
        borderWidth: 1,
      }],
    };

    // Pie chart configuration
    const ageConfig = {
      type: 'pie',
      data: ageData,
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'right', // Positions the legend at the top
          },
          title: {
            display: true,
            text: 'Age Distribution of Members', // Chart title
          },
        },
      },
    };

    // Render the chart
    document.addEventListener('DOMContentLoaded', () => {
      const ctx2 = document.getElementById('chart2').getContext('2d');
      const ageChart = new Chart(ctx2, ageConfig); // Create a new Chart.js instance
    });
  </script>

  </body>