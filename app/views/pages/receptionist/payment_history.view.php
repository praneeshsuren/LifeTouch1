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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/manager-style.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/admin-style.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/report-style.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/downloadButton-style.css?v=<?php echo time(); ?>" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

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
            <h1>Payment History</h1>
            <div class="greeting">
                <?php require APPROOT . '/views/components/user-greeting.view.php' ?>
            </div>
        </div>

        <div class="view-user-container">
            <div class="navbar-container">
                <div class="navbar">
                    <ul class="nav-links">
                        <li><a href="" id="userDetailsLink"><i class="ph ph-user"></i>User Details</a></li>
                        <li><a href="" id="attendanceLink"><i class="ph ph-calendar-dots"></i>Attendance Records</a></li>
                        <li class="active"><a href="" id="paymentHistoryLink"><i class="ph ph-money"></i>Payment History</a></li>
                        <li><a href="" id="supplementRecordsLink"><i class="ph ph-barbell"></i>Supplement Records</a></li>
                    </ul>
                </div>
            </div>

            <div class="user-container">

            </div>

        </div>

    </main>

    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time(); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            function getUrlParameter(name) {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(name);
            }

            const memberId = getUrlParameter('id');

            if (memberId) {
                const userDetailsLink = document.getElementById('userDetailsLink');
                userDetailsLink.href = `<?php echo URLROOT; ?>/receptionist/members/userDetails?id=${memberId}`;

                const workoutSchedulesLink = document.getElementById('workoutSchedulesLink');
                workoutSchedulesLink.href = `<?php echo URLROOT; ?>/receptionist/members/workoutSchedules?id=${memberId}`;

                const supplementRecordsLink = document.getElementById('supplementRecordsLink');
                supplementRecordsLink.href = `<?php echo URLROOT; ?>/receptionist/members/supplementRecords?id=${memberId}`;
            } else {
                alert('No member selected.');
            }

        });
    </script>

</body>

</html>