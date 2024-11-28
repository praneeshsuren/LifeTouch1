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
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/admin-style.css?v=<?php echo time(); ?>" />
  <!-- ICONS -->
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
  <!-- CHART.JS -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <title><?php echo APP_NAME; ?></title>

  <style>
    .chart-box {
      background: #ffffff;
      border: 1px solid #ddd;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      padding: 20px;
      width: 90%;
      min-width: 300px;
      flex: 1;
      text-align: center;
    }

    .charts-container {

      gap: 20px;
      justify-content: space-around;
      padding: 20px;
      flex-wrap: wrap;
      text-align: center;
    }
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


    <div class="charts-container">
      <!-- Chart 1: Revenue by Membership Type -->
      <div class="chart-box">
        <div class="chart-header">
          <h2>Revenue by Membership Type</h2>
        </div>
        <div class="chart-container">
          <canvas id="chart1" style="width:100px;height:50px"></canvas>
        </div>
      </div>
      <br>
      <!-- Chart 2: Members Age Category -->
      <div class="chart-box">
        <div class="chart-header">
          <h2>Members Age Category</h2>
        </div>
        <div class="chart-container" style="width: 300px;height:300px;">
          <canvas id="chart2"></canvas>
        </div>
      </div>
      <br>
      <div class="chart-box">
        <div class="chart-header">
          <h2>Inventory</h2>
        </div>
        <div class="chart-container">
          <canvas id="chart3"></canvas>
        </div>
      </div>
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




  </main>

  <!-- SCRIPT -->
  <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time(); ?>"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const ctx1 = document.getElementById('chart1').getContext('2d');

      const data = {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        datasets: [{
            label: 'Monthly',
            data: [10, 20, 30, 40, 50, 60, 70, 45, 50, 30, 55, 20],
            backgroundColor: 'rgba(255, 99, 132, 0.5)',
          },
          {
            label: 'Drop-in',
            data: [15, 25, 35, 45, 55, 65, 75, 25, 35, 45, 55, 65],
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
          },
          {
            label: 'Annual',
            data: [5, 15, 25, 35, 45, 55, 65, 30, 40, 50, 60, 70],
            backgroundColor: 'rgba(75, 192, 192, 0.5)',
          },
        ],
      };

      const config = {
        type: 'bar',
        data: data,
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top',
            },
          },
          scales: {
            x: {
              stacked: true,
            },
            y: {
              stacked: true,
            },
          },
        },
      };

      const myChart = new Chart(ctx1, config);
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

  <script>
    // Replace with actual years and equipment data
    const labels = ['2019', '2020', '2021', '2022', '2023'];
    const data = {
      labels: labels,
      datasets: [{
          label: 'Treadmills',
          data: [50, 55, 60, 65, 70], // Example counts
          backgroundColor: 'rgba(255, 99, 132, 0.5)',
          stack: 'Stack 0',
        },
        {
          label: 'Dumbbells',
          data: [100, 110, 120, 130, 140], // Example counts
          backgroundColor: 'rgba(54, 162, 235, 0.5)',
          stack: 'Stack 0',
        },
        {
          label: 'Bikes',
          data: [30, 35, 40, 45, 50], // Example counts
          backgroundColor: 'rgba(75, 192, 192, 0.5)',
          stack: 'Stack 1',
        },
        {
          label: 'Rowing Machines',
          data: [20, 25, 30, 35, 40], // Example counts
          backgroundColor: 'rgba(153, 102, 255, 0.5)',
          stack: 'Stack 1',
        },
      ],
    };

    // Configuration for a stacked bar chart displaying gym equipment counts year by year
    const inconfig = {
      type: 'bar',
      data: data,
      options: {
        plugins: {
          title: {
            display: true,
            text: 'Gym Equipment Counts by Year',
          },
        },
        responsive: true,
        interaction: {
          intersect: false,
        },
        scales: {
          x: {
            stacked: true,
            title: {
              display: true,
              text: 'Year',
            },
          },
          y: {
            stacked: true,
            title: {
              display: true,
              text: 'Equipment Count',
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
</body>

</html>