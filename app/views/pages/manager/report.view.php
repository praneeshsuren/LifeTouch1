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
    <link rel="stylesheet" href="<?php echo defined('URLROOT') ? URLROOT : '/default-url'; ?>/assets/css/manager-style.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/report-style.css?v=<?php echo time(); ?>" />

    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title><?php echo APP_NAME; ?></title>
</head>

<body>
    <section class="sidebar">
        <?php require APPROOT . '/views/components/manager_sidebar.view.php' ?>
    </section>
    <main>
        <div class="title">

            <h1>Reports</h1>
            <div class="greeting">
                <?php require APPROOT . '/views/components/user-greeting.view.php' ?>
            </div>

        </div>
        <div class="card-container" style="margin-top: 250px;">
            <div class="card">
                <div class="card-details">
                    <i class="ph ph-barbell" style="font-size: 50px;"></i>
                    <p class="text-title">Inventory Report</p>
                    <p class="text-body">Upcoming Services | Overdue Services</p>
                </div>
                <a href="<?php echo URLROOT; ?>/report/equipment_report">
                    <button class="card-button">More info</button>
                </a>

            </div>

            <div class="card">
                <div class="card-details">
                    <i class="ph ph-user" style="font-size: 50px;"></i>
                    <p class="text-title">Membership Plan Report</p>
                    <p class="text-body">Expire Membership Plans</p>
                </div>
                <a href="<?php echo URLROOT; ?>/report/payment_report">
                    <button class="card-button">More info</button>
                </a>
            </div>

            <div class="card">
                <div class="card-details">
                    <i class="ph ph-calendar-dots" style="font-size: 50px;"></i>
                    <p class="text-title">Event Report</p>
                    <p class="text-body">Total Revenue | Participants</p>
                </div>
                <a href="<?php echo URLROOT; ?>/report/event_report">
                    <button class="card-button">More info</button>
                </a>
            </div>
            
                </div>

                <div class="card" style="margin-top: 35px;">
                    <div class="card-details">
                        <i class="ph ph-money" style="font-size: 50px;"></i>
                        <p class="text-title">Financial Overview</p>
                        <p class="text-body">Income | Expense</p>
                    </div>
                    <a href="<?php echo URLROOT; ?>/report/income_report">
                        <button class="card-button">More info</button>
                    </a>
                </div>


    </main>

    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time(); ?>"></script>
</body>

</html>
</body>

</html>