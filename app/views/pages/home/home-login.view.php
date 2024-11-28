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
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger" id="error-message" style="display:none;">
            <?= htmlspecialchars($_SESSION['error']); ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <header>
        <a href="#" class="logo">Life <span>Touch</span></a>

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
            <a href="<?php echo URLROOT; ?>/login" class="nav-btn">Sign in</a>
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
            
            <br><a href="#">Forgot password?</a><br>
            <button type="submit" name="submit" class="btn">Login</button>
        </form>
    </div>

    <script src="<?php echo URLROOT; ?>/assets/js/login-script.js"></script>
</body>
</html>
