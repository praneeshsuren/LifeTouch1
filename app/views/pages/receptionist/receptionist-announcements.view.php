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
        <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/receptionist-style.css?v=<?php echo time();?>" />
        <!-- ICONS -->
        <script src="https://unpkg.com/@phosphor-icons/web"></script>
        <script>
            (function() {
                var savedMode = localStorage.getItem('mode');
                if (savedMode === 'dark') {
                document.body.classList.add('dark');
                }
            })();
        </script>
        <title><?php echo APP_NAME; ?></title>
    </head>
    <body>

        <section class="sidebar">
            <?php require APPROOT.'/views/components/receptionist-sidebar.view.php' ?>
        </section>
        
        <main>
            <div class="title">
                <h1>View Announcements</h1>
                <div class="greeting">
                    <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
                </div>
            </div>

            <div class="announcementsContainer">
                <div class="announcementHeader">
                    <div class="aHeading">
                        <h2>Announcements</h2>
                        <span id="num-of-announcements"></span>
                    </div>
                    <p id="mark-as-read">Mark as All Read</p>
                </div>
                <div class="announcement-card">
                    <div class="announcementCard-Header">
                        <div class="details">
                            <div class="profile-img">
                                <img src="<?php echo URLROOT; ?>/assets/images/image.png" alt="">
                            </div>
                            <div class="name-and-title">
                                <h3>Mark Anderson</h3>
                                <h4>GYM Renovation Notice for all Members and Trainers</h4>
                            </div>
                        </div>
                        <div class="unread-marker unread">
                            <p>Unread</p>
                        </div>
                    </div>
                    <div class="description">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                        Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. 
                        It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. 
                        It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                    </div>
                    <div class="announcementCard-Footer">
                        <div class="announcement-time">
                            <i class="ph ph-clock"></i>
                            <span>10:00 AM</span>
                        </div>
                        <div class="announcement-date">
                            <i class="ph ph-calendar"></i>
                            <span>14th September 2024</span>
                        </div>
                    </div>
                </div>
                <div class="announcement-card">
                    <div class="announcementCard-Header">
                        <div class="details">
                            <div class="profile-img">
                                <img src="<?php echo URLROOT; ?>/assets/images/image.png" alt="">
                            </div>
                            <div class="name-and-title">
                                <h3>Mark Anderson</h3>
                                <h4>GYM Renovation Notice for all Members and Trainers</h4>
                            </div>
                        </div>
                        <div class="unread-marker unread">
                            <p>Unread</p>
                        </div>
                    </div>
                    <div class="description">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                        Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. 
                        It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. 
                        It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                    </div>
                    <div class="announcementCard-Footer">
                        <div class="announcement-time">
                            <i class="ph ph-clock"></i>
                            <span>10:00 AM</span>
                        </div>
                        <div class="announcement-date">
                            <i class="ph ph-calendar"></i>
                            <span>14th September 2024</span>
                        </div>
                    </div>
                </div>
                <div class="announcement-card">
                    <div class="announcementCard-Header">
                        <div class="details">
                            <div class="profile-img">
                                <img src="<?php echo URLROOT; ?>/assets/images/image.png" alt="">
                            </div>
                            <div class="name-and-title">
                                <h3>Mark Anderson</h3>
                                <h4>GYM Renovation Notice for all Members and Trainers</h4>
                            </div>
                        </div>
                        <div class="unread-marker">
                            <p>Unread</p>
                        </div>
                    </div>
                    <div class="description">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                        Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. 
                        It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. 
                        It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                    </div>
                    <div class="announcementCard-Footer">
                        <div class="announcement-time">
                            <i class="ph ph-clock"></i>
                            <span>10:00 AM</span>
                        </div>
                        <div class="announcement-date">
                            <i class="ph ph-calendar"></i>
                            <span>14th September 2024</span>
                        </div>
                    </div>
                </div>
                <div class="announcement-card">
                    <div class="announcementCard-Header">
                        <div class="details">
                            <div class="profile-img">
                                <img src="<?php echo URLROOT; ?>/assets/images/image.png" alt="">
                            </div>
                            <div class="name-and-title">
                                <h3>Mark Anderson</h3>
                                <h4>GYM Renovation Notice for all Members and Trainers</h4>
                            </div>
                        </div>
                        <div class="unread-marker">
                            <p>Unread</p>
                        </div>
                    </div>
                    <div class="description">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                        Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. 
                        It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. 
                        It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                    </div>
                    <div class="announcementCard-Footer">
                        <div class="announcement-time">
                            <i class="ph ph-clock"></i>
                            <span>10:00 AM</span>
                        </div>
                        <div class="announcement-date">
                            <i class="ph ph-calendar"></i>
                            <span>14th September 2024</span>
                        </div>
                    </div>
                </div>
                <div class="announcement-card">
                    <div class="announcementCard-Header">
                        <div class="details">
                            <div class="profile-img">
                                <img src="<?php echo URLROOT; ?>/assets/images/image.png" alt="">
                            </div>
                            <div class="name-and-title">
                                <h3>Mark Anderson</h3>
                                <h4>GYM Renovation Notice for all Members and Trainers</h4>
                            </div>
                        </div>
                        <div class="unread-marker unread">
                            <p>Unread</p>
                        </div>
                    </div>
                    <div class="description">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                        Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. 
                        It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. 
                        It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                    </div>
                    <div class="announcementCard-Footer">
                        <div class="announcement-time">
                            <i class="ph ph-clock"></i>
                            <span>10:00 AM</span>
                        </div>
                        <div class="announcement-date">
                            <i class="ph ph-calendar"></i>
                            <span>14th September 2024</span>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <!-- SCRIPT -->
        <script src="<?php echo URLROOT; ?>/assets/js/receptionist-script.js?v=<?php echo time();?>"></script>

        <script>
            const unReadAnnouncements = document.querySelectorAll('.unread');
            const unReadAnnouncementsCount = document.getElementById('num-of-announcements');
            const markAllAsReadButton = document.getElementById('mark-as-read');

            updateUnreadCount();

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.remove('unread');
                        updateUnreadCount();
                        // Once the announcement is marked as read, we don't need to observe it anymore
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                root: null, // Use the viewport as the root
                rootMargin: '0px',
                threshold: 0.5 // Trigger when 50% of the element is visible
            });

            // Start observing each unread announcement
            unReadAnnouncements.forEach((announcement) => {
                observer.observe(announcement);
            });

            markAllAsReadButton.addEventListener('click', function() {
                unReadAnnouncements.forEach((announcement) => {
                    announcement.classList.remove('unread');
                });
                updateUnreadCount();
            });

            function updateUnreadCount() {
                const currentUnreadCount = document.querySelectorAll('.unread').length;
                unReadAnnouncementsCount.textContent = currentUnreadCount + ' Unread!';
                unReadAnnouncementsCount.style.display = currentUnreadCount === 0 ? 'none' : 'flex';
            }
        </script>
    </body>
</html>