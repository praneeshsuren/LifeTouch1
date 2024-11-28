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
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function(){
            (function() {
                var savedMode = localStorage.getItem('mode');
                if (savedMode === 'dark') {
                document.body.classList.add('dark');
                }
            })();
          });
    </script>
    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>

    <section class="sidebar">
    <?php require APPROOT.'/views/components/member-sidebar.view.php' ?>
    </section>
    
    <main>
      <div class="title">
        <h1>View Workout Schedules</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>
      </div>
      <div class="workoutContainer">
        <div class="workout-card">
          <div class="workoutCard-Header">
            <div class="workout-img">
              <img src="<?php echo URLROOT; ?>/assets/images/home/image1.jpg" alt="">
            </div>
            <div class="details">
              <div class="workout-title">
                <h3>Chest and Triceps</h3>
                <button class="completebtn">Completed</button>
              </div>
              <div class="workout-description">
                <table>
                  <tr>
                    <td>Bench press</td>
                    <td>4 X 6</td>
                  </tr>
                  <tr>
                    <td>Incline dumbell press</td>
                    <td>4 X 8</td>
                  </tr>
                  <tr>
                    <td>Dips</td>
                    <td>4 X 10</td>
                  </tr>
                  <tr>
                    <td>Rope triceps</td>
                    <td>4 X 10</td>
                  </tr>
                  <tr>
                    <td>Pull-ups</td>
                    <td>4 X 10</td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="workout-card">
          <div class="workoutCard-Header">
            <div class="workout-img">
              <img src="<?php echo URLROOT; ?>/assets/images/home/image1.jpg" alt="">
            </div>
            <div class="details">
              <div class="workout-title">
                <h3>Chest and Triceps</h3>
                <button class="notcompletebtn">Not Completed</button>
              </div>
              <div class="workout-description">
                <table>
                  <tr>
                    <td>Bench press</td>
                    <td>4 X 6</td>
                  </tr>
                  <tr>
                    <td>Incline dumbell press</td>
                    <td>4 X 8</td>
                  </tr>
                  <tr>
                    <td>Dips</td>
                    <td>4 X 10</td>
                  </tr>
                  <tr>
                    <td>Rope triceps</td>
                    <td>4 X 10</td>
                  </tr>
                  <tr>
                    <td>Pull-ups</td>
                    <td>4 X 10</td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="workout-card">
          <div class="workoutCard-Header">
            <div class="workout-img">
              <img src="<?php echo URLROOT; ?>/assets/images/home/image1.jpg" alt="">
            </div>
            <div class="details">
              <div class="workout-title">
                <h3>Chest and Triceps</h3>
                <button class="notcompletebtn">Not Completed</button>
              </div>
              <div class="workout-description">
                <table>
                  <tr>
                    <td>Bench press</td>
                    <td>4 X 6</td>
                  </tr>
                  <tr>
                    <td>Incline dumbell press</td>
                    <td>4 X 8</td>
                  </tr>
                  <tr>
                    <td>Dips</td>
                    <td>4 X 10</td>
                  </tr>
                  <tr>
                    <td>Rope triceps</td>
                    <td>4 X 10</td>
                  </tr>
                  <tr>
                    <td>Pull-ups</td>
                    <td>4 X 10</td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      
    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/member/member-script.js?v=<?php echo time();?>"></script>
</html>

