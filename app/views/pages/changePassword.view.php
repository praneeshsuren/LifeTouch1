<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- STYLESHEET -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/changePassword-style.css?v=<?php echo time();?>" />
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

    <main>
        <div class="change-password-container">
            <h2>Change Password</h2>
            <form id="changePasswordForm" method="POST" action="<?php echo URLROOT; ?>/ChangePassword/updatePassword">
                <div class="form-group">
                    <input type="password" name="current_password" id="current_password" required placeholder=" ">
                    <label for="current_password">Current Password</label>
                    <i class="ph ph-eye-slash toggle-password" data-toggle="current_password"></i>
                </div>

                <div class="form-group">
                    <input type="password" name="new_password" id="new_password" required placeholder=" ">
                    <label for="new_password">New Password</label>
                    <i class="ph ph-eye-slash toggle-password" data-toggle="new_password"></i>
                </div>

                <div class="form-group">
                    <input type="password" name="confirm_password" id="confirm_password" required placeholder=" ">
                    <label for="confirm_password">Confirm Password</label>
                    <i class="ph ph-eye-slash toggle-password" data-toggle="confirm_password"></i>
                </div>

                <button type="submit">Update Password</button>

                <p class="error-message" id="errorMessage">
                    <?php
                    if (isset($_SESSION['error'])) {
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                    }
                    if (isset($_SESSION['success'])) {
                        echo '<span style="color: green;">' . $_SESSION['success'] . '</span>';
                        unset($_SESSION['success']);
                    }
                    ?>
                </p>
            </form>
        </div>
    </main>


    <script>
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(icon => {
      icon.addEventListener('click', () => {
        const targetId = icon.getAttribute('data-toggle');
        const targetInput = document.getElementById(targetId);

        if (targetInput.type === 'password') {
          targetInput.type = 'text';
          icon.classList.remove('ph-eye-slash');
          icon.classList.add('ph-eye');
        } else {
          targetInput.type = 'password';
          icon.classList.remove('ph-eye');
          icon.classList.add('ph-eye-slash');
        }
      });
    });

    // Frontend validation
    const form = document.getElementById('changePasswordForm');
    const errorMessage = document.getElementById('errorMessage');

    form.addEventListener('submit', function (e) {
      const newPass = document.getElementById('new_password').value;
      const confirmPass = document.getElementById('confirm_password').value;

      if (newPass.length < 6) {
        e.preventDefault();
        errorMessage.textContent = 'New password must be at least 6 characters.';
      } else if (newPass !== confirmPass) {
        e.preventDefault();
        errorMessage.textContent = 'New password and confirmation do not match.';
      }
    });
  </script>

  </body>
</html>
