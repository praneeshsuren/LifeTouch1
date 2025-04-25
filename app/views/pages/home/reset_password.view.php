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

<body>
    <!-- Display session error message -->
    <?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])): ?>
        <div class="alert alert-danger" id="error-message" style="display:block;">
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
        <div class="login-container">
            <h2 style="color:white">Reset Password</h2>

            <!-- Display error message dynamically -->
            <?php if (isset($error) && !empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="<?php echo URLROOT; ?>/login/processReset" method="post">
                <input type="hidden" name="token" value="<?php echo $token; ?>">

                <div class="input-box">
                    <label for="password">New Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="New password" required minlength="8">
                </div>

                <div class="input-box">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm password" required minlength="8">
                </div>

                <button type="submit" class="btn">Reset Password</button>
            </form>
        </div>
    </div>

    <script src="<?php echo URLROOT; ?>/assets/js/login-script.js"></script>

</body>

</html>
