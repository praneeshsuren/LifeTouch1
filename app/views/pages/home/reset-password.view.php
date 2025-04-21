<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/login-style.css">
</head>
<body>
    <div id="container">
        <h1>Reset Your Password</h1>
        
        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo URLROOT; ?>/login/processReset" method="POST">
            <input type="hidden" name="token" value="<?php echo $token ?? ''; ?>">
            <input type="hidden" name="userType" value="<?php echo $userType ?? ''; ?>">
            
            <div class="input-box">
                <input type="password" name="password" placeholder="New Password" required>
            </div>
            
            <div class="input-box">
                <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
            </div>
            
            <button type="submit" class="btn">Reset Password</button>
        </form>
    </div>
</body>
</html>