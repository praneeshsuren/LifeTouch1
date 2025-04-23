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

      
    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/member/member-script.js?v=<?php echo time();?>"></script>
</html>

