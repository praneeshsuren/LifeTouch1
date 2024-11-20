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

        <div class="top">
            <h1 class="title">Announcement Summary</h1>
            <div class="bell">
                <i class="ph ph-bell"></i>
                <p>Hi, John!</p>
            </div>
        </div>
        <form class="search">
            <button>
                <svg width="17" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="search">
                    <path d="M7.667 12.667A5.333 5.333 0 107.667 2a5.333 5.333 0 000 10.667zM14.334 14l-2.9-2.9" stroke="currentColor" stroke-width="1.333" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </button>
            <div class="search-input">
                <input class="input" placeholder="Search here..." required="" type="text">
            </div>
            <button class="reset" type="reset">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </form>

        <div class="ann">

            <div class="tables">
                <div class="last-announcement">
                    <div class="heading">
                        <h2>Announcements</h2>
                        <a href="announcement" class="btn"><i class="ph ph-plus"></i> New Announcement</a>
                    </div>
                    <br>

                    <table class="list">
                        <thead>
                            <tr>
                                <td>Person</td>
                                <td>Title</td>
                                <td>Date</td>
                                <td>Actions</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data)): ?>
                                <?php foreach ($data as $announcement): ?>
                                    <tr>
                                        <td>
                                            <div class="person">
                                                <img class="preview-image" src="<?php echo URLROOT; ?>/assets/images/image.png" alt="">
                                                <div class="person-info">
                                                    <h4>John Doe</h4>
                                                    <small class="email">john@gmail.com</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($announcement->subject); ?></td>
                                        <td><?php echo htmlspecialchars($announcement->date); ?></td>
                                        <td>

                                            <!-- Pass the subject, date, and time as parameters to the openModal() function -->
                                            <a href="announcement_read/<?php echo $announcement->announcement_id; ?>"><i class="ph ph-eye"></i></a>


                                            <a href="announcement_update"><i class="ph ph-pen"></i></a>
                                            <a href="<?php echo URLROOT; ?>/manager/delete_announcement/<?php echo $announcement->announcement_id; ?>"
                                                onclick="return confirm('Are you sure you want to delete this announcement?');">
                                                <i class="ph ph-trash"></i>
                                            </a>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">No announcements found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </main>

    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time(); ?>"></script>

</body>

</html>