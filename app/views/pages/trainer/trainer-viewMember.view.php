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
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- CHART.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>

    <section class="sidebar">
      <?php require APPROOT.'/views/components/trainer-sidebar.view.php' ?>
    </section>

    <main>
      <div class="title">
        <h1>Member Details</h1>
        <div class="greeting">
            <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>
      </div>

      <div class="navbar-container">
        <div class="navbar">
          <ul class="nav-links">
            <li><a href="#user-details">User Details</a></li>
            <div class="separator"></div>
            <li><a href="#membership-details">Member Attendance</a></li>
            <div class="separator"></div>
            <li><a href="#supplement-records">Supplement Records</a></li>
            <div class="separator"></div>
            <li><a href="#workout-schedules">Workout Schedules</a></li>
          </ul>
        </div>
      </div>
      <div class="user-details">
          <div class="details">
            <div class="profile-picture">
              <img src="<?php echo URLROOT; ?>/assets/images/Member/<?php echo !empty($data['member']->image) ? $data['member']->image : 'default-placeholder.jpg'; ?>" 
                  alt="Member Picture" 
                  id="userImage">
              <input type="file" name="profile_picture" id="profilePictureInput" accept="image/*" style="display: none;">
            </div>
            <div class="left-column">
              <p>
                <strong>Member ID:</strong>
                <input type="text" id="user_id" value="<?php echo $data['member']->member_id; ?>" disabled>
                <input type="hidden" name="member_id" value="<?php echo $data['member']->member_id; ?>">
              </p>
              <p>
                <strong>First Name:</strong>
                <input type="text" name="first_name" value="<?php echo $data['member']->first_name; ?>" disabled>
              </p>
              <p>
                <strong>Last Name:</strong>
                <input type="text" name="last_name" value="<?php echo $data['member']->last_name; ?>" disabled>
              </p>
              <p>
                <strong>NIC Number:</strong>
                <input type="text" name="NIC_no" value="<?php echo $data['member']->NIC_no; ?>" disabled>
              </p>
              <p>
                <strong>Gender:</strong>
                <select name="gender" id="gender" disabled>
                  <option value="Male" <?php echo $data['member']->gender == 'Male' ? 'selected' : ''; ?>>Male</option>
                  <option value="Female" <?php echo $data['member']->gender == 'Female' ? 'selected' : ''; ?>>Female</option>
                  <option value="Other" <?php echo $data['member']->gender == 'Other' ? 'selected' : ''; ?>>Other</option>
                </select>
              </p>
              <p>
                <strong>Date of Birth:</strong>
                <input type="date" name="date_of_birth" value="<?php echo $data['member']->date_of_birth; ?>" disabled>
              </p>
            </div>
            <div class="right-column">
              <p>
                <strong>Height (m):</strong>
                <input type="number" name="height" value="<?php echo $data['member']->height; ?>" step="0.001" disabled>
              </p>
              <p>
                <strong>Weight (kg):</strong>
                <input type="number" name="weight" value="<?php echo $data['member']->weight; ?>" step="0.001" disabled>
              </p>
              <p>
                <strong>BMI (kg/m²):</strong>
                <input type="number" name="bmi" 
                    value="<?php echo ($data['member']->height > 0) 
                            ? round($data['member']->weight / ($data['member']->height ** 2), 2) 
                            : 'N/A'; ?>" 
                    step="0.01" disabled>
            </p>
              <p>
                <strong>Home Address:</strong>
                <input type="text" name="home_address" value="<?php echo $data['member']->home_address; ?>" disabled>
              </p>
              <p>
                <strong>Email Address:</strong>
                <input type="email" name="email_address" value="<?php echo $data['member']->email_address; ?>" disabled>
              </p>
              <p>
                <strong>Contact Number:</strong>
                <input type="text" name="contact_number" value="<?php echo $data['member']->contact_number; ?>" disabled>
              </p>
            </div>
          </div>
      </div>
    </main>

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

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/trainer-script.js?v=<?php echo time();?>"></script>

  </body>
</html>
