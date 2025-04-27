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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/member-style.css?v=<?php echo time();?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/sidebar-greeting.css?v=<?php echo time();?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/notifications-style.css?v=<?php echo time();?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>
    <section class="sidebar">
        <?php require APPROOT.'/views/components/member-sidebar.view.php' ?>
    </section>

    <main>

        <div class="title">
        <h1>Notifications</h1>
        <div class="greeting">
            <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>
        </div>

        <div class="notification-container">
            <?php if (!empty($data['notifications'])): ?>
                <div class="mark-all-container">
                    <button class="mark-all-read" onclick="markAllAsRead()">Mark All as Read</button>
                </div>
                <?php foreach ($data['notifications'] as $notification): ?>
                    <div class="notification">
                        <div class="notification-header">
                            <span class="notification-user"><?php echo $notification->user_type; ?></span>
                            <span class="notification-time"><?php echo date('F j, Y, g:i a', strtotime($notification->created_at)); ?></span>
                        </div>
                        <div class="notification-body">
                            <p><?php echo $notification->message; ?></p>
                        </div>
                        <div class="notification-footer">
                            <button class="mark-read" onclick="markAsRead(<?php echo $notification->id; ?>)">Mark as Read</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No notifications found.</p>
            <?php endif; ?>
        </div>


    </main>
      
    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/member-script.js?v=<?php echo time();?>"></script>
    <script>
        function markAsRead(notificationId) {
            // Make a request to mark the notification as read
            window.location.href = `index.php?action=markAsRead&notificationId=${notificationId}`;
        }
    </script>

  </body>
</html>
