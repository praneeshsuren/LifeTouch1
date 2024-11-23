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
          <span class="bell-container"><i class="ph ph-bell notification"></i></span>
          <h2>Hi, John!</h2>
        </div>

      </div>

      <div class="insights">

        <div class="members">
          <i class="ph ph-users"></i>
          <div class="middle">
            <div class="left">
              <h3>Total Members</h3>
              <h1>20</h1>
            </div>
            <div class="progress">
              <svg>
                <circle cx="38" cy="38" r="38"></circle>
              </svg>
              <div class="number">
                <p>75%</p>
              </div>
            </div>
          </div>
          <small class="text-muted">Last 30 days</small>
        </div>
        <!-- END OF MEMBERS -->

        <div class="bookings">
          <i class="ph ph-chart-bar"></i>
          <div class="middle">
            <div class="left">
              <h3>Total Bookings</h3>
              <h1>10</h1>
            </div>
            <div class="progress">
              <svg>
                <circle cx="38" cy="38" r="38"></circle>
              </svg>
              <div class="number">
                <p>75%</p>
              </div>
            </div>
          </div>
          <small class="text-muted">Last 30 days</small>
        </div>
        <!-- END OF BOOKINGS -->

        <div class="workouts">
          <i class="ph ph-trend-up"></i>
          <div class="middle">
            <div class="left">
              <h3>Workouts Created</h3>
              <h1>10</h1>
            </div>
            <div class="progress">
              <svg>
                <circle cx="38" cy="38" r="38"></circle>
              </svg>
              <div class="number">
                <p>75%</p>
              </div>
            </div>
          </div>
          <small class="text-muted">Last 30 days</small>
        </div>
        <!-- END OF WORKOUTS -->

      </div>

      <div class="recent-announcements">

        <h2>Recent Announcements</h2>
        <div class="announcements">

          <div class="announcement">
            <div class="profile-img">
            <img src="<?php echo URLROOT; ?>/assets/images/image.png" alt="">
            </div>
            <div class="message">
              <p><b>Mark Anderson</b></br>GYM Renovation Notice for all Members and Trainers</p>
              <small class="text-muted">2 hours ago</small>
            </div>
          </div>

          <div class="announcement">
            <div class="profile-img">
            <img src="<?php echo URLROOT; ?>/assets/images/image.png" alt="">
            </div>
            <div class="message">
              <p><b>Mark Anderson</b></br>GYM Renovation Notice for all Members and Trainers</p>
              <small class="text-muted">2 hours ago</small>
            </div>
          </div>

          <div class="announcement">
            <div class="profile-img">
            <img src="<?php echo URLROOT; ?>/assets/images/image.png" alt="">
            </div>
            <div class="message">
              <p><b>Mark Anderson</b></br>GYM Renovation Notice for all Members and Trainers</p>
              <small class="text-muted">2 hours ago</small>
            </div>
          </div>

        </div>  

      </div>

      <!-- CHARTS -->

      <div class="chart">
        <div class="chart-header">
          <h2>Busy Hours</h2>
          <i class="ph ph-dots-three-circle-vertical"></i>
        </div>
        <div class="chart-container">
          <canvas id="LineChart"></canvas>
        </div>
      </div>
      

    </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/admin-script.js?v=<?php echo time();?>"></script>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
      const ctx = document.getElementById('LineChart').getContext('2d');
      
      // Generate current time and past 6 hours
      const getCurrentTimeLabel = (hoursAgo) => {
          const date = new Date();
          date.setHours(date.getHours() - hoursAgo);
          return date.toLocaleTimeString('en-US', { 
              hour: 'numeric', 
              minute: '2-digit', 
              hour12: true 
          });
      };

      // Sample data - you can replace these with data from your PHP controller
      const data = {
          labels: [
              getCurrentTimeLabel(5),
              getCurrentTimeLabel(4),
              getCurrentTimeLabel(3),
              getCurrentTimeLabel(2),
              getCurrentTimeLabel(1),
              getCurrentTimeLabel(0)
          ],
          datasets: [{
              label: 'Number of Members',
              data: [15, 25, 35, 45, 30, 20], // Replace with your actual data
              fill: true,
              borderColor: '#7380ec',
              backgroundColor: 'rgba(115, 128, 236, 0.1)',
              tension: 0.4,
              pointBackgroundColor: '#7380ec',
              pointBorderColor: '#fff',
              pointBorderWidth: 2,
              pointRadius: 5,
              pointHoverRadius: 7,
          }]
      };

      // Chart configuration
      const config = {
          type: 'line',
          data: data,
          options: {
              responsive: true,
              maintainAspectRatio: false,
              scales: {
                  y: {
                      beginAtZero: true,
                      grid: {
                          drawBorder: false,
                          color: 'rgba(0, 0, 0, 0.1)'
                      },
                      ticks: {
                          stepSize: 10
                      }
                  },
                  x: {
                      grid: {
                          drawBorder: false,
                          display: false
                      }
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
      new Chart(ctx, config);
    });
  </script>
  </body>
</html>

