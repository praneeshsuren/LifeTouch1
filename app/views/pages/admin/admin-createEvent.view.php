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

    <section class="sidebar">
        <?php require APPROOT . '/views/components/admin-sidebar.view.php'; ?>
    </section>
    
    <main>
      <div class="title">
        <h1>Create Event</h1>
        <div class="greeting">
        <?php require APPROOT . '/views/components/user-greeting.view.php'; ?>
        </div>
      </div>

      <!-- Events Container -->
      <div class="events-container">
        
      </div>
      
    </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/admin-script.js?v=<?php echo time(); ?>"></script>
  </body>
</html>