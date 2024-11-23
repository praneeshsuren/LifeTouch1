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

    <section class="sidebar">
        <?php require APPROOT.'/views/components/receptionist-sidebar.view.php' ?>
    </section>

    <main>

      <div class="title">
        
        <h1>Members</h1>
        <div class="greeting">
          <span class="bell-container"><i class="ph ph-bell notification"></i></span>
          <h2>Hi, John!</h2>
        </div>

      </div>

      <div class="trainer-form">
    <h2>Member Registration</h2>
    <form action="<?php echo URLROOT; ?>/receptionist/members/registerMember" method="post">
    <div class="form-container">

        <!-- Left Column -->
        <div class="left-column">
            <div class="input-container">
                <div class="input-box">
                    <input type="text" id="first-name" name="first_name" placeholder="First Name" value="<?php echo $_POST['first_name'] ?? ''; ?>" required>
                    <?php if (!empty($data['errors']['first_name'])): ?>
                        <span class="invalid-feedback"><?php echo $data['errors']['first_name']; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="input-container">
                <div class="input-box">
                    <input type="text" id="last-name" name="last_name" placeholder="Last Name" value="<?php echo $_POST['last_name'] ?? ''; ?>" required>
                    <?php if (!empty($data['errors']['last_name'])): ?>
                        <span class="invalid-feedback"><?php echo $data['errors']['last_name']; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="input-container">
                <div class="input-box">
                    <input type="email" id="email-address" name="email_address" placeholder="Email Address" value="<?php echo $_POST['email_address'] ?? ''; ?>" required>
                    <?php if (!empty($data['errors']['email_address'])): ?>
                        <span class="invalid-feedback"><?php echo $data['errors']['email_address']; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="input-container">
                <div class="input-box">
                    <input type="date" id="dob" name="date_of_birth" placeholder="Date of Birth" value="<?php echo $_POST['date_of_birth'] ?? ''; ?>" required>
                    <?php if (!empty($data['errors']['date_of_birth'])): ?>
                        <span class="invalid-feedback"><?php echo $data['errors']['date_of_birth']; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="input-container">
                <div class="input-box">
                    <input type="text" id="home-address" name="home_address" placeholder="Home Address" value="<?php echo $_POST['home_address'] ?? ''; ?>" required>
                    <?php if (!empty($data['errors']['home_address'])): ?>
                        <span class="invalid-feedback"><?php echo $data['errors']['home_address']; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="input-container">
                <div class="input-box">
                    <input type="tel" id="contact-number" name="contact_number" placeholder="Contact Number" value="<?php echo $_POST['contact_number'] ?? ''; ?>" required>
                    <?php if (!empty($data['errors']['contact_number'])): ?>
                        <span class="invalid-feedback"><?php echo $data['errors']['contact_number']; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="input-container">
                <div class="input-box">
                    <select id="gender" name="gender" required>
                        <option value="" disabled selected>Gender</option>
                        <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                    <?php if (!empty($data['errors']['gender'])): ?>
                        <span class="invalid-feedback"><?php echo $data['errors']['gender']; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- New Input for Weight -->
            <div class="input-container">
                <div class="input-box">
                    <input type="number" id="weight" name="weight" placeholder="Weight (kg)" value="<?php echo $_POST['weight'] ?? ''; ?>" step="0.001" required>
                    <?php if (!empty($data['errors']['weight'])): ?>
                        <span class="invalid-feedback"><?php echo $data['errors']['weight']; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- New Input for Height -->
            <div class="input-container">
                <div class="input-box">
                    <input type="number" id="height" name="height" placeholder="Height (m)" value="<?php echo $_POST['height'] ?? ''; ?>" step="0.001" required>
                    <?php if (!empty($data['errors']['height'])): ?>
                        <span class="invalid-feedback"><?php echo $data['errors']['height']; ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="right-column">
            <div class="input-container">
                <div class="input-image-box">
                    <label for="member-image">Upload Member Image</label>
                    <div class="image-container">
                        <img id="member-image" src="path/to/your/image.jpg" alt="Member Image">
                    </div>
                    <input type="file" id="image-upload" name="image" accept="image/*" />
                </div>
            </div>

            <div class="input-container">
                <div class="input-box">
                    <input type="text" id="username" name="username" placeholder="Username" value="<?php echo $_POST['username'] ?? ''; ?>" required>
                    <?php if (!empty($data['errors']['username'])): ?>
                        <span class="invalid-feedback"><?php echo $data['errors']['username']; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="input-container">
                <div class="input-box">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <?php if (!empty($data['errors']['password'])): ?>
                        <span class="invalid-feedback"><?php echo $data['errors']['password']; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="input-container">
                <div class="input-box">
                    <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm Password" required>
                    <?php if (!empty($data['errors']['confirm_password'])): ?>
                        <span class="invalid-feedback"><?php echo $data['errors']['confirm_password']; ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="btn-container">
        <button type="submit" class="trainer-submit-btn">Create Member</button>
    </div>
</form>

</div>

      </div>

        <!-- SCRIPT -->
        <script src="<?php echo URLROOT; ?>/assets/js/receptionist-script.js?v=<?php echo time();?>"></script>

    </main>

</body>
</html>
