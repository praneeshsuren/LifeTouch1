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
        <h1>Workout Schedules</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>
      </div>
      
      <div class="workout-container">

        <div id="schedule-cards-container" class="schedule-cards">
            <?php if (!empty($schedules)) : ?>
              <?php foreach ($schedules as $schedule) : ?>
                <?php
                  // Check if workout is completed
                  $status = !empty($schedule->completed_date) ? 'Completed' : 'Ongoing';
                  $startDate = date('d-m-Y', strtotime($schedule->created_at));
                  $completeDate = !empty($schedule->completed_date) ? date('d-m-Y', strtotime($schedule->completed_date)) : 'N/A';
                ?>
                <div class="schedule-card" onclick="window.location.href='<?php echo URLROOT; ?>/member/workoutDetails?id=<?php echo $schedule->schedule_id; ?>'">
                  <h3>Workout Schedule #<?php echo $schedule->schedule_no; ?></h3>
                  <p><strong>Start Date:</strong> <?php echo $startDate; ?></p>
                  <p><strong>Complete Date:</strong> <?php echo $completeDate; ?></p>
                  <p><strong>Status:</strong> <span class="status <?php echo strtolower($status); ?>"><?php echo $status; ?></span></p>
                </div>
              <?php endforeach; ?>
            <?php else : ?>
              <p>No workout schedules found for this member.</p>
            <?php endif; ?>
        </div>

        <div id="measurement-charts">
          <h2>Measurements Statistics</h2>
          
          <div class="charts-grid">
            <div class="chart-wrapper">
              <canvas id="weightChart"></canvas>
              <p class="chart-label">Weight (kg)</p>
            </div>
            <div class="chart-wrapper">
              <canvas id="chestChart"></canvas>
              <p class="chart-label">Chest (cm)</p>
            </div>
            <div class="chart-wrapper">
              <canvas id="bicepChart"></canvas>
              <p class="chart-label">Bicep (cm)</p>
            </div>
            <div class="chart-wrapper">
              <canvas id="thighChart"></canvas>
              <p class="chart-label">Thigh (cm)</p>
            </div>
          </div>
        </div>


      </div>

    </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/member/member-script.js?v=<?php echo time();?>"></script>
    <script>
      async function fetchAndBuildMeasurementData() {
        try {
          const response = await fetch("<?= URLROOT ?>/member/workoutSchedulesApi");
          const schedules = await response.json();

          if (!schedules.length) return;

          const labels = [];
          const weight = [], chest = [], bicep = [], thigh = [];

          // First data point from beginning of first schedule
          labels.push("Start (Schedule #" + schedules[0].schedule_no + ")");
          weight.push(parseFloat(schedules[0].weight_beginning));
          chest.push(parseFloat(schedules[0].chest_measurement_beginning));
          bicep.push(parseFloat(schedules[0].bicep_measurement_beginning));
          thigh.push(parseFloat(schedules[0].thigh_measurement_beginning));

          // Following data points from ending of all schedules
          schedules.forEach((schedule, index) => {
            labels.push("Schedule #" + schedule.schedule_no);
            weight.push(parseFloat(schedule.weight_ending));
            chest.push(parseFloat(schedule.chest_measurement_ending));
            bicep.push(parseFloat(schedule.bicep_measurement_ending));
            thigh.push(parseFloat(schedule.thigh_measurement_ending));
          });

          renderChart("weightChart", "Weight (kg)", weight, labels, "#3498db");
          renderChart("chestChart", "Chest (cm)", chest, labels, "#e67e22");
          renderChart("bicepChart", "Bicep (cm)", bicep, labels, "#8e44ad");
          renderChart("thighChart", "Thigh (cm)", thigh, labels, "#2ecc71");

        } catch (error) {
          console.error("Failed to fetch schedule data:", error);
        }
      }

      function renderChart(canvasId, label, data, labels, color) {
        const ctx = document.getElementById(canvasId).getContext('2d');
        new Chart(ctx, {
          type: 'line',
          data: {
            labels: labels,
            datasets: [{
              label: label,
              data: data,
              borderColor: color,
              borderWidth: 2,
              fill: false,
              tension: 0.3,
              pointBackgroundColor: color
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: {
                beginAtZero: false
              }
            },
            plugins: {
              legend: {
                display: true,
                labels: {
                  usePointStyle: true,
                  padding: 20
                }
              }
            }
          }
        });
      }

      window.addEventListener('DOMContentLoaded', fetchAndBuildMeasurementData);
    </script>



  </body>
</html>

