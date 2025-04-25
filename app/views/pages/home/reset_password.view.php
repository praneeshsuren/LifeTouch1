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
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #ffffff;
            margin: 10% auto;
            padding: 30px 25px;
            border-radius: 12px;
            width: 100%;
            max-width: 400px;
            font-family: 'Poppins', sans-serif;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            animation: fadeIn 0.3s ease-in-out;
        }

        .modal h2 {
            font-size: 22px;
            margin-bottom: 15px;
            text-align: center;
            color: #333;
        }

        .modal .input-box input {
            width: 100%;
            color: #333;
            padding: 10px 14px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
            transition: border-color 0.3s;
        }


        .modal .input-box input:focus {
            border-color: #0066ff;
        }

        .modal-footer {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .modal .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            transition: background 0.3s;
        }

        .modal .btn-cancel {
            background: #ccc;
            color: #333;
        }

        .modal .btn-primary {
            background: #0066ff;
            color: white;
        }

        .modal .btn:hover {
            opacity: 0.9;
        }

        #resetMessage {
            font-size: 14px;
            text-align: center;
        }


        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .modal-footer {
            margin-top: 20px;
            text-align: right;
        }

        .btn {
            padding: 8px 16px;
            margin-left: 10px;
            cursor: pointer;
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
        <div class="login-container">
            <h2>Reset Password</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="<?php echo URLROOT; ?>/login/processReset" method="post">
                <input type="hidden" name="token" value="<?php echo $token; ?>">

                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" name="password" id="password" class="form-control" required minlength="8">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required minlength="8">
                </div>

                <button type="submit" class="btn btn-primary">Reset Password</button>
            </form>
        </div>
    </div>


    </div>

    <script src="<?php echo URLROOT; ?>/assets/js/login-script.js"></script>

</body>

</html>