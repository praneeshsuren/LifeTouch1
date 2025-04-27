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
        <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/manager-style.css?v=<?php echo time();?>" />
        <!-- ICONS -->
        <script src="https://unpkg.com/@phosphor-icons/web"></script>
        <title><?php echo APP_NAME; ?></title>
    </head>
    <body>

        <section class="sidebar">
        <?php require APPROOT.'/views/components/manager_sidebar.view.php' ?>
        </section>
        
        <main>
            <div class="title">
                <h1>Announcements</h1>
                <div class="greeting">
                    <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
                </div>
            </div>

            <div class="announcementsContainer">
                <?php if (!empty($data['announcements'])): ?>
                <?php foreach ($data['announcements'] as $announcement): ?>
                    <div class="announcement-card" id="announcement-<?php echo $announcement->id; ?>">
                        <div class="announcementCard-Header">
                            <div class="details">
                                <div class="profile-img">
                                    <img class="preview-image" src="<?php echo URLROOT; ?>/assets/images/Admin/<?php echo !empty($announcement->image) ? $announcement->image : 'default-placeholder.jpg'; ?>" alt="Admin Profile Picture">
                                </div>
                                <div class="name-and-title">
                                    <h3><?php echo $announcement->first_name; ?> <?php echo $announcement->last_name; ?></h3>
                                    <h4><?php echo $announcement->subject; ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="description">
                        <?php echo $announcement->description; ?>
                        </div>
                        <div class="announcementCard-Footer">
                            <div class="announcement-time">
                                <i class="ph ph-clock"></i>
                                <span><?php echo $announcement->created_time; ?></span>
                            </div>
                            <div class="announcement-date">
                                <i class="ph ph-calendar"></i>
                                <span><?php echo $announcement->created_date; ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php else: ?>
                    <p>No announcements available.</p>
                <?php endif; ?>

            </div>
        </main>

        <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time();?>"></script>
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                const params = new URLSearchParams(window.location.search);
                const targetId = params.get('announcement');
                if (targetId) {
                const targetElement = document.getElementById('announcement-' + targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    targetElement.classList.add('highlight-announcement');
                }
                }
            });
        </script>

    </body>
</html>