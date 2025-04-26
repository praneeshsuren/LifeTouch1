<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- STYLESHEET -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/login-style.css?v=<?php echo time(); ?>" />
</head>

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

    <body>
        <!-- Display session error message -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger" id="error-message" style="display:none;">
                <?= htmlspecialchars($_SESSION['error']); ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <header>
            <a href="<?php echo URLROOT; ?>" class="logo no-style">Life<span>Touch</span></a>

            <div class='bx bx-menu' id="menu-icon"></div>

            <ul class="navbar">
                <li><a href="<?php echo URLROOT; ?>">Home</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#plans">Events</a></li>
                <li><a href="#review">Trainers</a></li>
                <li><a href="#contact">Contact Us</a></li>
            </ul>
            <div class="top-btn">
                <a href="<?php echo URLROOT; ?>/login" class="nav-btn no-style">Sign in</a>
            </div>
        </header>

        <div id="container">
            <h1>Start a better shape of you!</h1>
            <h1>Come join us</h1>
            <p>Please complete the login process with correct information.</p>

            <!-- Display error messages if any -->
            <?php if (!empty($data['error'])): ?>
                <div class="error-message">
                    <?php echo $data['error']; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo URLROOT; ?>/login/user" method="POST">
                <div class="input-box">
                    <input type="text" name="username" placeholder="Username" required>
                </div>

                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <br><a href="#" id="forgot-password-link">Forgot password?</a><br>
                <button type="submit" name="submit" class="btn">Login</button>
            </form>
            <!-- Forgot Password Modal -->
            <div id="forgotPasswordModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Reset Password</h2>
                    <form id="forgot-password-form">
                        <div class="input-box">
                            <input type="text" name="username" placeholder="Enter your username" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-cancel">Cancel</button>
                            <button type="submit" class="btn btn-primary" style="width: 150px;">Send Reset Link</button>
                        </div>
                    </form>
                    <div id="resetMessage" style="margin-top: 15px;"></div>
                </div>
            </div>


        </div>

        <script src="<?php echo URLROOT; ?>/assets/js/login-script.js"></script>
        <script>
            // Forgot Password Modal
            const modal = document.getElementById("forgotPasswordModal");
            const btn = document.getElementById("forgot-password-link");
            const span = document.getElementsByClassName("close")[0];
            const cancelBtn = document.querySelector(".btn-cancel");

            btn.onclick = function(e) {
                e.preventDefault();
                modal.style.display = "block";
            }

            span.onclick = function() {
                modal.style.display = "none";
            }

            cancelBtn.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            document.getElementById("forgot-password-form").addEventListener("submit", function(e) {
                e.preventDefault();

                const username = this.querySelector('input[name="username"]').value.trim();
                const messageDiv = document.getElementById("resetMessage");

                if (!username) {
                    messageDiv.innerHTML = '<p style="color:red;">Please enter your username</p>';
                    return;
                }

                // Show loading state
                messageDiv.innerHTML = '<p style="color:blue;">Processing your request...</p>';

                // Send username to server
                fetch('<?php echo URLROOT; ?>/login/requestReset', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        username: username
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        messageDiv.innerHTML = '<p style="color:green;">' + data.message + '</p>';
                        setTimeout(() => {
                            modal.style.display = "none";
                            messageDiv.innerHTML = '';
                            this.reset(); // Reset the form
                        }, 3000);
                    } else {
                        messageDiv.innerHTML = '<p style="color:red;">' + data.message + '</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    messageDiv.innerHTML = '<p style="color:red;">An error occurred. Please try again.</p>';
                });
            });
        </script>
    </body>

</html>