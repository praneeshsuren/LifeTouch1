<span class="bell-container" onclick="window.location.href='<?php echo URLROOT ?>/<?php echo $_SESSION['role'] ?>/notifications';">
    <i class="ph ph-bell notification-icon"></i>
</span>
<h2>Hi, <?php echo isset($_SESSION['first_name']) ? $_SESSION['first_name'] : 'User'; ?>!</h2>