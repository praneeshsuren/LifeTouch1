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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/admin-style.css?v=<?php echo time();?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>

    <!-- PHP Alerts for Success/Error Messages -->
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
        <?php require APPROOT.'/views/components/admin-sidebar.view.php' ?>
    </section>

    <main>
      <div class="title">
        
        <h1>Settings</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>

      </div>

      <div class="settings-container">

        <form id="settings-form" method="POST" action="<?php echo URLROOT; ?>/admin/updateSettings" enctype="multipart/form-data">
          <input type="hidden" name="user_id" value="<?php echo $data['admin']->admin_id; ?>">

          <div class="details">
              <!-- Profile Picture -->
              <div class="profile-picture">
                  <img src="<?php echo URLROOT; ?>/assets/images/Admin/<?php echo !empty($data['admin']->image) ? $data['admin']->image : 'default-placeholder.jpg'; ?>" alt="Profile Picture" id="profilePicture">

                  <input 
                    type="file" 
                    name="image" 
                    id="profilePictureInput" 
                    accept="image/*" 
                    style="display: none;"
                  >

                  <button type="button" id="changePictureBtn" class="change-picture-btn" style="display: none;">Change Picture</button>
              </div>

              <!-- User Details -->
              <div class="user-details">
                  <p>
                      <strong>Admin ID:</strong>
                      <input type="text" id="admin-id" name="admin_id" value="<?php echo $data['admin']->admin_id; ?>" disabled>
                  </p>
                  <p>
                      <strong>Username:</strong>
                      <input type="text" id="username" name="username" value="<?php echo $data['user']->username; ?>" disabled>
                  </p>
                  <p>
                      <strong>Change Password:</strong>
                      <a href="#" id="changePasswordLink" class="change-password-link">Click here to change password</a>
                  </p>
                  <p>
                      <strong>First Name:</strong>
                      <input type="text" id="first-name" name="first_name" value="<?php echo $data['admin']->first_name; ?>" disabled>
                      <?php if (isset($data['errors']['first_name'])): ?>
                          <small class="error"><?php echo $data['errors']['first_name']; ?></small>
                      <?php endif; ?>
                  </p>
                  <p>
                      <strong>Last Name:</strong>
                      <input type="text" id="last-name" name="last_name" value="<?php echo $data['admin']->last_name; ?>" disabled>
                      <?php if (isset($data['errors']['last_name'])): ?>
                          <small class="error"><?php echo $data['errors']['last_name']; ?></small>
                      <?php endif; ?>
                  </p>
                  <p>
                      <strong>Email Address:</strong>
                      <input type="email" id="email" name="email_address" value="<?php echo $data['admin']->email_address; ?>" disabled>
                      <?php if (isset($data['errors']['email_address'])): ?>
                          <small class="error"><?php echo $data['errors']['email_address']; ?></small>
                      <?php endif; ?>
                  </p>
                  <div class="row">
                      <p>
                          <strong>Date of Birth:</strong>
                          <input type="date" id="dob" name="date_of_birth" value="<?php echo $data['admin']->date_of_birth; ?>" disabled>
                          <?php if (isset($data['errors']['date_of_birth'])): ?>
                              <small class="error"><?php echo $data['errors']['date_of_birth']; ?></small>
                          <?php endif; ?>
                      </p>
                      <p>
                          <strong>Contact Number:</strong>
                          <input type="number" id="contact" name="contact_number" value="<?php echo $data['admin']->contact_number; ?>" disabled>
                          <?php if (isset($data['errors']['contact_number'])): ?>
                              <small class="error"><?php echo $data['errors']['contact_number']; ?></small>
                          <?php endif; ?>
                      </p>
                  </div>
                  <p>
                      <strong>Home Address:</strong>
                      <input type="text" id="address" name="home_address" value="<?php echo $data['admin']->home_address; ?>" disabled>
                      <?php if (isset($data['errors']['home_address'])): ?>
                          <small class="error"><?php echo $data['errors']['home_address']; ?></small>
                      <?php endif; ?>
                  </p>
                  <p>
                      <strong>NIC Number:</strong>
                      <input type="number" id="NIC" name="NIC_no" value="<?php echo $data['admin']->NIC_no; ?>" disabled>
                      <?php if (isset($data['errors']['NIC_no'])): ?>
                          <small class="error"><?php echo $data['errors']['NIC_no']; ?></small>
                      <?php endif; ?>
                  </p>
              </div>
          </div>

          <!-- Action Buttons -->
          <div class="action-buttons">
              <button type="button" id="editBtn" class="edit-btn">Edit</button>
              <button type="submit" id="saveBtn" class="save-btn" style="display: none;">Save</button>
              <button type="button" id="cancelBtn" class="cancel-btn" style="display: none;">Cancel</button>
          </div>
        </form>

      </div>    
    </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/admin-script.js?v=<?php echo time();?>"></script>

    <script>
        // Image change functionality
        document.getElementById('changePictureBtn').addEventListener('click', () => {
          document.getElementById('profilePictureInput').click();
        });

        document.getElementById('profilePictureInput').addEventListener('change', function() {
          const file = this.files[0];
          if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
              document.getElementById('profilePicture').src = e.target.result;
            };
            reader.readAsDataURL(file);
          }
        });

        const editBtn = document.getElementById('editBtn');
        const changePictureBtn = document.getElementById('changePictureBtn');
        const saveBtn = document.getElementById('saveBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const form = document.getElementById('settings-form');
        const inputs = form.querySelectorAll('input');
        const fileInput = document.getElementById('profilePictureInput');

        // Store original values for cancel
        let originalValues = {};
        function cacheOriginalValues() {
          inputs.forEach(input => {
            if (input.type !== 'file' && input.name !== 'admin_id') {
              originalValues[input.name] = input.value;
            }
          });
        }

        function restoreOriginalValues() {
          inputs.forEach(input => {
            if (input.name in originalValues) {
              input.value = originalValues[input.name];
            }
            if (input.name !== 'admin_id') {
              input.disabled = true;
            }
          });
          fileInput.disabled = true;
          saveBtn.style.display = 'none';
          cancelBtn.style.display = 'none';
          changePictureBtn.style.display = 'none';
          editBtn.style.display = 'inline-block';
        }

        function enableEditing() {
          cacheOriginalValues();
          inputs.forEach(input => {
            if (input.name !== 'admin_id') {
              input.disabled = false;
            }
          });
          fileInput.disabled = false;
          changePictureBtn.style.display = 'inline-block';
          saveBtn.style.display = 'inline-block';
          cancelBtn.style.display = 'inline-block';
          editBtn.style.display = 'none';
        }

        editBtn.addEventListener('click', enableEditing);
        cancelBtn.addEventListener('click', restoreOriginalValues);

        document.getElementById('changePasswordLink').addEventListener('click', function (e) {
            e.preventDefault();
            window.location.href = "<?php echo URLROOT; ?>/changePassword";
        });
    </script>

  </body>
</html>
