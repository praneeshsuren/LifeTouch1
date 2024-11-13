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
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/manager-style.css?v=<?php echo time(); ?>" />
  <!-- ICONS -->
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
  <title><?php echo APP_NAME; ?></title>
</head>

<body>

  <section class="sidebar">
    <?php require APPROOT . '/views/components/manager_sidebar.view.php' ?>
  </section>

  <main>
    <h1 class="title">Create new annoucement</h1>
    <div class="announcement">
      <div class="ann">
        <section class="container">
          <form action="#" class="form">


            <a href="announcement_main" class="btn" style="float: right; margin-top: -10px;margin-bottom:3px;">Back</a>

            <div class="input-box">
              <label>Subject</label>
              <input type="text" placeholder="Announcement Subject" required />
            </div>

            <div class="input-box">
              <label>Announcement</label>
              <textarea placeholder="Write your announcement here..." required></textarea>
            </div>


            <div class="column">

              <div class="input-box">
                <label>Date</label>
                <input type="date" placeholder="MM/DD/YY" required />
              </div>

              <div class="input-box">
                <label>Time</label>
                <input type="time" placeholder="12.00 PM" required />
              </div>

            </div>

            <div class="schedule-box">
              <div class="schedule-option">

                <div class="send">
                  <div class="radio-container">
                    <input type="radio" id="check" name="send" checked />
                    <label for="now">Send now</label>
                  </div>
                </div>

                <div class="send">
                  <div class="radio-container">
                    <input type="radio" id="check" name="send" checked />
                    <label for="later">schedule later</label>
                  </div>
                </div>

              </div>
            </div>

            <button>Publish Now</button>

          </form>
        </section>
      </div>
    </div>

  </main>
  <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time(); ?>"></script>
</body>

</html>