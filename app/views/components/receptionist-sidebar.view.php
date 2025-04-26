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
                <a href="<?php echo URLROOT; ?>/receptionist">
                    <i class='icon ph-bold ph-house-simple'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class='icon ph-bold ph-user'></i>
                    <span class="text">Users</span>
                    <i class='arrow ph-bold ph-caret-down'></i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="<?php echo URLROOT; ?>/receptionist/members">
                            <span class="text">Members</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/receptionist/trainers">
                            <span class="text">Trainers</span>
                        </a>
                    </li> 
                </ul> 
            </li>
            <li>
                <a href="#">
                    <i class="ph ph-notebook"></i>
                    <span class="text">Reservations</span>
                    <i class='arrow ph-bold ph-caret-down'></i>
                </a>
                <ul class="sub-menu">
                    <a href="<?php echo URLROOT; ?>/receptionist/holiday">
                        <span class="text">Holidays</span>
                    </a>
                    <a href="<?php echo URLROOT; ?>/receptionist/timeslot">
                        <span class="text">Timeslot</span>
                    </a>
                </ul>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/receptionist/payment">
                    <i class='icon ph-bold ph-credit-card'></i>
                    <span class="text">Payment</span>
                </a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/receptionist/announcements">
                    <i class='icon ph-bold ph-newspaper'></i>
                    <span class="text">Announcements</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="menu">
        <p class="title">Settings</p>
        <ul>
            <li>
                <a href="#">
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