<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <!-- STYLESHEET -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/trainer-style.css?v=<?php echo time();?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/sidebar-greeting.css?v=<?php echo time();?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- CHART.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>

    <?php
      if (isset($_SESSION['success'])) {
          echo "<script>alert('" . $_SESSION['success'] . "');</script>";
          unset($_SESSION['success']); // Clear the message after showing it
      }

      if (isset($_SESSION['error'])) {
          echo "<script>alert('" . $_SESSION['error'] . "');</script>";
          unset($_SESSION['error']); // Clear the message after showing it
      }
    ?>

    <section class="sidebar">
      <?php require APPROOT.'/views/components/trainer-sidebar.view.php' ?>
    </section>

    <main>
      <div class="title">
        <h1>Member Workouts</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>
      </div>

      <div class="view-user-container">
        <div class="navbar-container">
          <div class="navbar">
            <ul class="nav-links">
              <li><a href="#user-details" id="userDetailsLink"><i class="ph ph-user"></i>User Details</a></li>
              <li><a href="#membership-details"><i class="ph ph-calendar-dots"></i>Member Attendance</a></li>
              <li><a href="#workout-schedules" id="workoutSchedulesLink"><i class="ph ph-notebook"></i>Workout Schedules</a></li>
              <li><a href="#supplement-records"><i class="ph ph-barbell"></i>Supplement Records</a></li>
            </ul>
          </div>
        </div>

        <div class="user-container">
          <!-- Create Workout Schedule Button -->
          <div class="workout-schedule-header">
            <button id="createWorkoutBtn" class="add-workout-btn">+ Create Workout Schedule</button>
          </div>

          <!-- Display Workout Schedules as Cards -->
          <div id="schedule-cards-container" class="schedule-cards">
            <?php if (!empty($schedules)) : ?>
              <?php foreach ($schedules as $schedule) : ?>
                <?php
                  // Check if workout is completed
                  $status = !empty($schedule->completed_date) ? 'Completed' : 'Ongoing';
                  $startDate = date('d-m-Y', strtotime($schedule->created_at));
                  $completeDate = !empty($schedule->completed_date) ? date('d-m-Y', strtotime($schedule->completed_date)) : 'N/A';
                ?>
                <div class="schedule-card" onclick="window.location.href='<?php echo URLROOT; ?>/trainer/members/workoutDetails?id=<?php echo $schedule->schedule_id; ?>'">
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
        </div>
      </div>
    </main>

    <script src="<?php echo URLROOT; ?>/assets/js/trainer-sidebar.js?v=<?php echo time();?>"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Function to get URL parameter by name
        function getUrlParameter(name) {
          const urlParams = new URLSearchParams(window.location.search);
          return urlParams.get(name);
        }

        // Get the 'id' parameter (member_id) from the URL
        const memberId = getUrlParameter('id');

        if (memberId) {
          // Update the Create Workout Schedule button with the member ID dynamically
          const createWorkoutBtn = document.getElementById('createWorkoutBtn');
          createWorkoutBtn.addEventListener('click', function() {
            window.location.href = `<?php echo URLROOT; ?>/trainer/members/createWorkoutSchedule?id=${memberId}`;
          });

          // Also update the navigation links with the member ID
          const userDetailsLink = document.getElementById('userDetailsLink');
          userDetailsLink.href = `<?php echo URLROOT; ?>/trainer/members/userDetails?id=${memberId}`;

          const workoutSchedulesLink = document.getElementById('workoutSchedulesLink');
          workoutSchedulesLink.href = `<?php echo URLROOT; ?>/trainer/members/workoutSchedules?id=${memberId}`;
        } else {
          alert('No member selected.');
        }
      });
    </script>

  </body>
</html>
