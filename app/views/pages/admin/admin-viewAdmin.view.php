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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/admin-style.css?v=<?php echo time();?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- CHART.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>

    <section class="sidebar">
      <?php require APPROOT.'/views/components/admin-sidebar.view.php' ?>
    </section>

    <main>
      <div class="title">
        <h1>Admin Details</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>
      </div>

      <div class="navbar-container">
        <div class="navbar">
          <ul class="nav-links">
            <li><a href="#user-details">User Details</a></li>
            <div class="separator"></div>
            <li><a href="#membership-details">Admin Attendance</a></li>
            <div class="separator"></div>
            <li><a href="#supplement-records">Salary History</a></li>
            <div class="separator"></div>
            <li><a href="#workout-schedules">Admin Calendar</a></li>
          </ul>
        </div>
      </div>
      <div class="user-details">
      <form id="userForm" method="POST" enctype="multipart/form-data" action="<?php echo URLROOT; ?>/admin/admins/updateAdmin">
          <div class="details">
            <div class="profile-picture">
              <img src="<?php echo URLROOT; ?>/assets/images/Admin/<?php echo !empty($data['admin']->image) ? $data['admin']->image : 'default-placeholder.jpg'; ?>" 
                  alt="Admin Picture" 
                  id="userImage">
              <input type="file" name="profile_picture" id="profilePictureInput" accept="image/*" style="display: none;">
              <button type="button" id="changePictureBtn" class="change-picture-btn">Change Picture</button>
            </div>
            <div class="left-column">
              <p>
                <strong>admin ID:</strong>
                <input type="text" id="user_id" value="<?php echo $data['admin']->admin_id; ?>" disabled>
                <input type="hidden" name="admin_id" value="<?php echo $data['admin']->admin_id; ?>">
              </p>
              <p>
                <strong>First Name:</strong>
                <input type="text" name="first_name" value="<?php echo $data['admin']->first_name; ?>" disabled>
              </p>
              <p>
                <strong>Last Name:</strong>
                <input type="text" name="last_name" value="<?php echo $data['admin']->last_name; ?>" disabled>
              </p>
              <p>
                <strong>NIC Number:</strong>
                <input type="text" name="NIC_no" value="<?php echo $data['admin']->NIC_no; ?>" disabled>
              </p>
              <p>
                <strong>Gender:</strong>
                <select name="gender" id="gender" disabled>
                  <option value="Male" <?php echo $data['admin']->gender == 'Male' ? 'selected' : ''; ?>>Male</option>
                  <option value="Female" <?php echo $data['admin']->gender == 'Female' ? 'selected' : ''; ?>>Female</option>
                  <option value="Other" <?php echo $data['admin']->gender == 'Other' ? 'selected' : ''; ?>>Other</option>
                </select>
              </p>
              <p>
                <strong>Date of Birth:</strong>
                <input type="date" name="date_of_birth" value="<?php echo $data['admin']->date_of_birth; ?>" disabled>
              </p>
            </div>
            <div class="right-column">
              <p>
                <strong>Home Address:</strong>
                <input type="text" name="home_address" value="<?php echo $data['admin']->home_address; ?>" disabled>
              </p>
              <p>
                <strong>Email Address:</strong>
                <input type="email" name="email_address" value="<?php echo $data['admin']->email_address; ?>" disabled>
              </p>
              <p>
                <strong>Contact Number:</strong>
                <input type="text" name="contact_number" value="<?php echo $data['admin']->contact_number; ?>" disabled>
              </p>
            </div>
          </div>
          <div class="action-buttons">
            <button type="button" id="editBtn" class="edit-btn">Edit</button>
            <button type="button" id="deleteBtn" class="delete-btn" onclick="window.location.href='<?php echo URLROOT; ?>/admin/admins/deleteAdmin?id=<?php echo $data['admin']->admin_id; ?>';">Delete</button>
            <button type="submit" id="saveBtn" class="save-btn" style="display: none;">Save</button>
            <button type="button" id="cancelBtn" class="cancel-btn" style="display: none;">Cancel</button>
          </div>
        </form>
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

    <script>
      document.getElementById('changePictureBtn').addEventListener('click', () => {
        document.getElementById('profilePictureInput').click();
      });

      document.getElementById('profilePictureInput').addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function (e) {
            document.getElementById('adminImage').src = e.target.result;
          };
          reader.readAsDataURL(file);
        }
      });
    </script>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/admin-script.js?v=<?php echo time();?>"></script>

  </body>
</html>
