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

        <h1>Admin Details</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>

      </div>

      <div class="view-user-container">

        <div class="navbar-container">

          <div class="navbar">

            <ul class="nav-links">

              <li class="active"><a href="" id="userDetailsLink"><i class="ph ph-user"></i>User Details</a></li>
              <li><a href="" id="salaryHistoryLink"><i class="ph ph-money"></i>Salary History</a></li>

            </ul>

          </div>

        </div>

        <div class="user-container">

          <form id="userForm" method="POST" enctype="multipart/form-data" action="<?php echo URLROOT; ?>/user/admin/updateAdmin">

            <div class="details">

              <div class="profile-picture">

                <img src="<?php echo URLROOT; ?>/assets/images/Admin/<?php echo !empty($data['admin']->image) ? $data['admin']->image : 'default-placeholder.jpg'; ?>" 
                    alt="Admin Picture" 
                    id="profilePicture">
                <input 
                  type="file" 
                  name="image" 
                  id="profilePictureInput" 
                  accept="image/*" 
                  style="display: none;"
                >
                <button type="button" id="changePictureBtn" class="change-picture-btn" style="display: none;">Change Picture</button>

              </div>

              <div class="user-details">

                  <p>
                    <strong>Admin ID:</strong>
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

                  <div class="row">
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
                  </div>

                  <div class="row">
                    <p>
                      <strong>Date of Birth:</strong>
                      <input type="date" name="date_of_birth" value="<?php echo $data['admin']->date_of_birth; ?>" disabled>
                    </p>
                    <p>
                      <strong>Contact Number:</strong>
                      <input type="text" name="contact_number" value="<?php echo $data['admin']->contact_number; ?>" disabled>
                    </p>
                  </div>

                  <p>
                    <strong>Home Address:</strong>
                    <input type="text" name="home_address" value="<?php echo $data['admin']->home_address; ?>" disabled>
                  </p>
                  <p>
                    <strong>Email Address:</strong>
                    <input type="email" name="email_address" value="<?php echo $data['admin']->email_address; ?>" disabled>
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

      </div>

    </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/admin-script.js?v=<?php echo time();?>"></script>

    <script>
  // Edit button functionality
  document.getElementById('editBtn').addEventListener('click', () => {
    // Enable all input fields and the membership plan select
    const formElements = document.querySelectorAll('#userForm input');
    formElements.forEach(element => {
      element.disabled = false;
    });

    document.getElementById('user_id').disabled = true;
    // Hide the Edit button, show the Save and Cancel buttons
    
    document.getElementById('editBtn').style.display = 'none';
    document.getElementById('deleteBtn').style.display = 'none';
    document.getElementById('saveBtn').style.display = 'inline-block';
    document.getElementById('cancelBtn').style.display = 'inline-block';
    document.getElementById('changePictureBtn').style.display = 'inline-block';
  });

  // Cancel button functionality (reset to disabled)
  document.getElementById('cancelBtn').addEventListener('click', () => {
    // Disable all input fields and the membership plan select
    const formElements = document.querySelectorAll('#userForm input, #userForm select');
    formElements.forEach(element => {
      element.disabled = true;
    });

    // Hide the Save and Cancel buttons, show the Edit button
    document.getElementById('saveBtn').style.display = 'none';
    document.getElementById('cancelBtn').style.display = 'none';
    document.getElementById('changePictureBtn').style.display = 'none';
    document.getElementById('editBtn').style.display = 'inline-block';
    document.getElementById('deleteBtn').style.display = 'inline-block';
  });

  // Delete button confirmation functionality
  document.getElementById('deleteBtn').addEventListener('click', function(e) {
    e.preventDefault(); // Prevent immediate redirect or form submission

    const confirmation = confirm("Are you sure you want to delete this admin?");
    if (confirmation) {
      // If user clicks "OK", redirect to the delete URL
      window.location.href = '<?php echo URLROOT; ?>/user/admin/deleteAdmin?id=<?php echo $data['admin']->admin_id; ?>';
    }
  });

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


  document.addEventListener('DOMContentLoaded', () => {
    // Function to get URL parameter by name
    function getUrlParameter(name) {
      const urlParams = new URLSearchParams(window.location.search);
      return urlParams.get(name);
    }

    const adminId = getUrlParameter('id');

    if (adminId) {
      document.getElementById('userDetailsLink').href = `<?php echo URLROOT; ?>/admin/admins/viewAdmin?id=${adminId}`;
      document.getElementById('salaryHistoryLink').href = `<?php echo URLROOT; ?>/admin/admins/salaryHistory?id=${adminId}`;
    } else {
      alert('No admin selected.');
    }
  });
</script>

  </body>
</html>
