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
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        <?php require APPROOT . '/views/components/admin-sidebar.view.php'; ?>
    </section>
    
    <main>
      <div class="title">
        <h1>Events</h1>
        <div class="greeting">
        <?php require APPROOT . '/views/components/user-greeting.view.php'; ?>
        </div>
      </div>

      <!-- Events Container -->
      <div class="events-container">
        <div class="events-header">
          <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search events..." />
            <i class="ph ph-magnifying-glass search-icon"></i>
          </div>
          <button class="btn add-event-btn" onclick="window.location.href='<?php echo URLROOT; ?>/admin/events/createEvent'">+ Add New Event</button>
        </div>

        <?php if (!empty($data['events'])): ?>
          <div class="cards-grid" id="eventsGrid">
            <?php foreach ($data['events'] as $event): ?>
              <div class="card">
                <div class="card-header">
                  <h2><?php echo $event->name; ?></h2>
                </div>
                <div class="card-body">
                  <p><strong>Date:</strong> <?php echo $event->event_date; ?></p>
                  <p><strong>Time:</strong> <?php echo $event->start_time; ?></p>
                  <p><strong>Duration:</strong> <?php echo $event->duration; ?> hours</p>
                  <p><strong>Location:</strong> <?php echo $event->location; ?></p>
                  <p class="description"><?php echo $event->description; ?></p>
                  <button class="btn view-btn" onclick="window.location.href='<?php echo URLROOT; ?>/admin/events/viewEvent?id=<?php echo $event->event_id; ?>';">View</button>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="no-events-text">No Events Available</p>
        <?php endif; ?>
      </div>
      
    </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/admin-script.js?v=<?php echo time(); ?>"></script>
    <script>
      // Live Search Functionality
      const searchInput = document.getElementById('searchInput');
      const eventCards = document.querySelectorAll('#eventsGrid .card');

      searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        eventCards.forEach(card => {
          const title = card.querySelector('h2').innerText.toLowerCase();
          if (title.includes(query)) {
            card.style.display = "block";
          } else {
            card.style.display = "none";
          }
        });
      });
    </script>

  </body>
</html>