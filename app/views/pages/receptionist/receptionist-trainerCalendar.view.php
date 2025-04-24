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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/receptionist-style.css?v=<?php echo time();?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
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
      <?php require APPROOT.'/views/components/receptionist-sidebar.view.php' ?>
    </section>

    <main>

      <div class="title">

        <h1>Trainer Calendar</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>
        
      </div>

      <div class="view-user-container">

        <div class="navbar-container">

          <div class="navbar">

            <ul class="nav-links">

              <li><a href="" id="userDetailsLink"><i class="ph ph-user"></i>User Details</a></li>
              <li><a href="" id="salaryHistoryLink"><i class="ph ph-money"></i>Salary History</a></li>
              <li class="active"><a href="" id="trainerCalendarLink"><i class="ph ph-barbell"></i>Trainer Calendar</a></li>

            </ul>

          </div>

        </div>

        <div class="user-container">

        </div>

      </div>

    </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/receptionist-script.js?v=<?php echo time();?>"></script>

    <script>    
        document.addEventListener('DOMContentLoaded', () => {
            // Function to get URL parameter by name
            function getUrlParameter(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
            }

            // Get the 'id' parameter (member_id) from the URL
            const trainerId = getUrlParameter('id');

            if (trainerId) {
            // Member ID is available, use it in the navigation link
            document.getElementById('userDetailsLink').href = `<?php echo URLROOT; ?>/receptionist/trainers/viewTrainer?id=${trainerId}`;
            document.getElementById('salaryHistoryLink').href = `<?php echo URLROOT; ?>/receptionist/trainers/salaryHistory?id=${trainerId}`;
            document.getElementById('trainerCalendarLink').href = `<?php echo URLROOT; ?>/receptionist/trainers/trainerCalendar?id=${trainerId}`;
            } else {
            // No member_id in the URL, show a message or handle accordingly
            alert('No member selected.');
            }
        });
    </script>


  </body>
</html>
