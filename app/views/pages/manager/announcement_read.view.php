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
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title><?php echo APP_NAME; ?></title>
</head>

<body>

    <section class="sidebar">
        <?php require APPROOT . '/views/components/manager_sidebar.view.php' ?>
    </section>

    <main>
        <h1 class="title">Announcement Details</h1>
        <div class="announcement">
            <div class="announcement-header">
                <br>
                <a href="<?php echo URLROOT; ?>/manager/announcement_main" class="btn" style="float: right; margin-top: -10px;margin-bottom:3px:margin-left:-3px">Back</a>

                <div class="title-overlay">
                    <h2 class="announcement-title"> <img class="preview-image" src="<?php echo URLROOT; ?>/assets/images/image.png" alt="">
                        &nbsp;Gym Renovation</h2>
                    <p class="subtitle">
                    <div class="person-info">
                        <small class="email">john@gmail.com</small>
                    </div>Wednesday, 11 September 2024, 3:07 PM</p>
                </div>
            </div>
            <div class="announcement-content">
                <p>Dear customers,</p>
                <p>We will be undergoing a full renovation of our gym facilities. Please note that the gym will be closed during this period. We apologize for any inconvenience and look forward to unveiling the upgraded space soon!</p>
                <p>John Doe</p>
                <p>Manager</p>
                <p>2024.09.11</p>
            </div>
        </div>

    </main>
    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time(); ?>"></script>
</body>

</html>