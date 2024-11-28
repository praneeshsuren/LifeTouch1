<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- STYLESHEET -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/admin-style.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/admin-event.css?v=<?php echo time(); ?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>
    <section class="sidebar">
        <?php require APPROOT . '/views/components/admin-sidebar.view.php'; ?>
    </section>
    
    <main>
      <div class="title">
        <h1>Events</h1>
        <div class="greeting">
          <span class="bell-container">
            <i class="ph ph-bell notification"></i>
          </span>
          <h2>Hi, John!</h2>
        </div>
      </div>


      <!-- PHP to generate event cards -->
      <?php
      $events = [
          [
              'title' => 'Yoga for Beginners',
              'date' => '2024-12-01',
              'time' => '8:00 AM - 9:30 AM',
              'location' => 'LifeTouch Studio A',
              'description' => 'Join our yoga expert for a relaxing and rejuvenating session designed for beginners.'
          ],
          [
              'title' => 'HIIT Cardio Blast',
              'date' => '2024-12-03',
              'time' => '6:00 PM - 7:00 PM',
              'location' => 'LifeTouch Main Hall',
              'description' => 'Push your limits with a high-intensity interval training session that burns calories fast!'
          ],
          [
              'title' => 'Weightlifting Basics',
              'date' => '2024-12-05',
              'time' => '5:00 PM - 6:30 PM',
              'location' => 'LifeTouch Gym',
              'description' => 'Learn the fundamentals of weightlifting with our certified trainers.'
          ],
          [
              'title' => 'Spin Class Marathon',
              'date' => '2024-12-07',
              'time' => '7:00 AM - 10:00 AM',
              'location' => 'LifeTouch Spin Studio',
              'description' => 'An energizing spin session to kickstart your weekend. All fitness levels are welcome!'
          ],
          [
              'title' => 'Zumba Dance Party',
              'date' => '2024-12-10',
              'time' => '6:30 PM - 7:30 PM',
              'location' => 'LifeTouch Main Hall',
              'description' => 'Dance your way to fitness with a fun and energetic Zumba class!'
          ]
      ];
      ?>

      <!-- Events Container -->
    <div class="events-container">
      <div class="cards-grid">
        <?php foreach ($events as $event): ?>
          <div class="card">
              <div class="card-header">
                  <h2><?php echo $event['title']; ?></h2>
              </div>
              <div class="card-body">
                  <p><strong>Date:</strong> <?php echo $event['date']; ?></p>
                  <p><strong>Time:</strong> <?php echo $event['time']; ?></p>
                  <p><strong>Location:</strong> <?php echo $event['location']; ?></p>
                  <p><?php echo $event['description']; ?></p>
                  <button class="btn view-btn">View</button>
              </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div> 
      
    </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/admin-script.js?v=<?php echo time(); ?>"></script>
  </body>
</html>