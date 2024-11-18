<div class="menu-btn">
    <i class="ph-bold ph-caret-left"></i>
</div>
<div class="head">
    <div class="user-img">
        <img src="<?php echo URLROOT; ?>/assets/images/image.png" alt="">
    </div>
    <div class="user-details">
        <p class="name">Manager</p>
        <p class="post">John Doe</p>
    </div>
</div>
<div class="nav">
    <div class="menu">
        <p class="title">Main</p>
        <ul>
            <li>
                <a href="<?php echo URLROOT; ?>/manager">
                    <i class='icon ph-bold ph-house-simple'></i>
                    <span class=" text">Dashboard</span>
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
                        <a href="<?php echo URLROOT; ?>/manager/member">
                            <span class="text">Members</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/manager/trainer">
                            <span class="text">Trainers</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/manager/admin">
                            <span class="text">Admin</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/manager/equipment">
                    <i class='icon ph-bold ph-barbell'></i>
                    <span class="text">Equipment</span>
                </a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/manager/announcement_main">
                    <i class="ph ph-megaphone"></i>
                    <span class="text">Post Announcements</span>
                </a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/manager/report">
                    <i class="ph ph-files"></i>
                    <span class="text">View Reports</span>
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
        <li>
            <a href="#">
                <i class='icon ph-bold ph-info'></i>
                <span class="text">Help</span>
            </a>
        </li>
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
            <a href="#">
                <i class='icon ph-bold ph-sign-out'></i>
                <span class="text">Logout</span>
            </a>
        </li>
    </ul>
</div>