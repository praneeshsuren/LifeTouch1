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

    <style>
        .alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}

.alert-success {
    color: #3c763d;
    background-color: #dff0d8;
    border-color: #d6e9c6;
}

.alert-danger {
    color: #a94442;
    background-color: #f2dede;
    border-color: #ebccd1;
}

.alert small {
    display: block;
    margin-top: 5px;
    color: #666;
}
    </style>
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
        <h2 style="color:white">Forgot Password</h2><br>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
                
            </div>
        <?php endif; ?>

        <form action="<?php echo URLROOT; ?>/login/forgotPassword" method="post">
            <div class="input-box">
                <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
            </div>
            
            <br><a href="<?php echo URLROOT; ?>/login" style="margin-top: -60px;">Back to Login</a><br>

            <button type="submit" class="btn" style="width: 150px;">Reset Password</button>
        </form>
    </div>


    <script src="<?php echo URLROOT; ?>/assets/js/login-script.js"></script>
    
</body>

</html>