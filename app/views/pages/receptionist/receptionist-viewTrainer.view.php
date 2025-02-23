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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/receptionist-style.css?v=<?php echo time();?>" />
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
      <?php require APPROOT.'/views/components/receptionist-sidebar.view.php' ?>
    </section>  

    <main>
      <div class="title">
        <h1>Trainer Details</h1>
        <div class="greeting">
            <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>
      </div>

      <div class="navbar-container">
        <div class="navbar">
          <ul class="nav-links">
            <li><a href="#user-details">User Details</a></li>
            <div class="separator"></div>
            <li><a href="#membership-details">Trainer Attendance</a></li>
            <div class="separator"></div>
            <li><a href="#supplement-records">Salary History</a></li>
            <div class="separator"></div>
            <li><a href="#workout-schedules">Trainer Calendar</a></li>
          </ul>
        </div>
      </div>
      
      <div class="user-details">
      <form id="userForm" method="POST" enctype="multipart/form-data" action="<?php echo URLROOT; ?>/receptionist/trainers/updateTrainer">
          <div class="details">
            <div class="profile-picture">
              <img 
                src="<?php echo URLROOT; ?>/assets/images/Trainer/<?php echo !empty($data['trainer']->image) ? $data['trainer']->image : 'default-placeholder.jpg'; ?>" 
                alt="Trainer Picture" 
                id="userImage" >
              
              <!-- File input for image upload -->
              <input type="file" name="profile_picture" id="profilePictureInput" style="display: none;" accept="image/*">
              
              <!-- Change picture button -->
              <button type="button" id="changePictureBtn" class="change-picture-btn">Change Picture</button>
            </div>

            <div class="left-column">
              <p>
                <strong>Trainer ID:</strong>
                <input type="text" id="user_id" value="<?php echo $data['trainer']->trainer_id; ?>" disabled>
                <input type="hidden" name="trainer_id" value="<?php echo $data['trainer']->trainer_id; ?>">
              </p>
              <p>
                <strong>First Name:</strong>
                <input type="text" name="first_name" value="<?php echo $data['trainer']->first_name; ?>" disabled>
              </p>
              <p>
                <strong>Last Name:</strong>
                <input type="text" name="last_name" value="<?php echo $data['trainer']->last_name; ?>" disabled>
              </p>
              <p>
                <strong>NIC Number:</strong>
                <input type="text" name="NIC_no" value="<?php echo $data['trainer']->NIC_no; ?>" disabled>
              </p>
              <p>
                <strong>Gender:</strong>
                <select name="gender" id="gender" disabled>
                  <option value="Male" <?php echo $data['trainer']->gender == 'Male' ? 'selected' : ''; ?>>Male</option>
                  <option value="Female" <?php echo $data['trainer']->gender == 'Female' ? 'selected' : ''; ?>>Female</option>
                  <option value="Other" <?php echo $data['trainer']->gender == 'Other' ? 'selected' : ''; ?>>Other</option>
                </select>
              </p>
              <p>
                <strong>Date of Birth:</strong>
                <input type="date" name="date_of_birth" value="<?php echo $data['trainer']->date_of_birth; ?>" disabled>
              </p>
            </div>
            <div class="right-column">
              <p>
                <strong>Home Address:</strong>
                <input type="text" name="home_address" value="<?php echo $data['trainer']->home_address; ?>" disabled>
              </p>
              <p>
                <strong>Email Address:</strong>
                <input type="email" name="email_address" value="<?php echo $data['trainer']->email_address; ?>" disabled>
              </p>
              <p>
                <strong>Contact Number:</strong>
                <input type="text" name="contact_number" value="<?php echo $data['trainer']->contact_number; ?>" disabled>
              </p>
            </div>
          </div>
          <div class="action-buttons">
            <button type="button" id="editBtn" class="edit-btn">Edit</button>
            <button type="button" id="deleteBtn" class="delete-btn" onclick="window.location.href='<?php echo URLROOT; ?>/receptionist/trainers/deleteTrainer?id=<?php echo $data['trainer']->trainer_id; ?>';">Delete</button>
            <button type="submit" id="saveBtn" class="save-btn" style="display: none;">Save</button>
            <button type="button" id="cancelBtn" class="cancel-btn" style="display: none;">Cancel</button>
          </div>
        </form>
      </div>
    </main>


    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/receptionist-script.js?v=<?php echo time();?>"></script>

    <script>
      document.getElementById('changePictureBtn').addEventListener('click', () => {
        document.getElementById('profilePictureInput').click(); // Trigger file input click
      });

      document.getElementById('profilePictureInput').addEventListener('change', (event) => {
        const file = event.target.files[0]; // Get the selected file
        if (file) {
          const reader = new FileReader(); // Create a FileReader object
          
          // Update the image source once the file is read
          reader.onload = function(e) {
            document.getElementById('userImage').src = e.target.result;
          };

          reader.readAsDataURL(file); // Read the file as a Data URL
        }
      });
    </script>

  </body>
</html>
