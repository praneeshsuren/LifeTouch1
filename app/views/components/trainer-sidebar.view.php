<div class="menu-btn">
    <i class="ph-bold ph-caret-left"></i>
</div>
<div class="head">
    <div class="user-img">
            <img src="<?php echo URLROOT; ?>/assets/images/<?php echo $_SESSION['role'] ?>/<?php echo !empty($_SESSION['image']) ? $_SESSION['image'] : 'default-placeholder.jpg'; ?>"
            alt="User Picture"
            id="userImage">
    </div>
    <div class="user-details">
        <p class="post"><?php echo $_SESSION['role'] ?></p>
        <p class="name"><?php echo isset($_SESSION['first_name']) ? $_SESSION['first_name'] : 'User'; ?></p>
    </div>
</div>
<div class="nav">
    <div class="menu">
        <p class="title">Main</p>
        <ul>
            <li>
                <a href="<?php echo URLROOT; ?>/trainer">
                    <i class='icon ph-bold ph-house-simple'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/trainer/members">
                    <i class='icon ph-bold ph-user'></i>
                    <span class="text">View Member</span>
                </a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/trainer/bookings">
                    <i class="ph ph-notebook"></i>
                    <span class="text">Bookings</span>
                </a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/trainer/workouts">
                    <i class='icon ph-bold ph-barbell'></i>
                    <span class="text">Workouts</span>
                </a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/trainer/announcements">
                    <i class='icon ph-bold ph-newspaper'></i>
                    <span class="text">View Announcements</span>
                </a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/trainer/calendar">
                    <i class='icon ph-bold ph-calendar-dots'></i>
                    <span class="text">View Calendar</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="menu">
        <p class="title">Settings</p>
        <ul>
            <li>
                <a href="<?php echo URLROOT; ?>/trainer/settings">
                    <i class='icon ph-bold ph-gear'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="menu">
    <p class="title">Account</p>
    <ul>
        <li class="mode active">
            <div class="moon-sun">
                <i class='icon ph-bold ph-sun sun'></i>
                <i class='icon ph-bold ph-moon moon'></i>
            </div>
            <span class="mode-text text">Dark Mode</span>

            <div class="toggle-switch">
                <span class="switch"></span>
            </div>
        </li>
        <li>
            <a href="<?php echo URLROOT; ?>/login/logout">
                <i class='icon ph-bold ph-sign-out'></i>
                <span class="text">Logout</span>
            </a>
        </li>
    </ul>
</div>