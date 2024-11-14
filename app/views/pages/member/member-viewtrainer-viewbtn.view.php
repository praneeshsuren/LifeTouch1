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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title><?php echo APP_NAME; ?></title>
</head>

<body>

    <section class="sidebar">
    <?php require APPROOT.'/views/components/member-sidebar.view.php' ?>
    </section>

    <main>

        <div class="top">
            <h1 class="title">John's Profile</h1>
            <div class="bell">
                <i class="ph ph-bell"></i>
                <p>Hi, John!</p>
            </div>
        </div>

        <div class="box">
            <div class="member-card">
                <div>
                    <div class="profile-img-container"><img class="profile-img" src="<?php echo URLROOT; ?>/public/images/image.png" alt=""></div>
                    <a href="member_edit"><button class="edit-button">Edit</button></a>
                    <button class="delete-button">Delete</button>
                </div>
                <div>
                    <table class="profile-table">
                        <tr>
                            <th colspan="2">Trainer Details : </th>
                        </tr>
                        <tr>
                            <th><i class="ph ph-user-circle"></i>First Name</th>
                            <td>John</td>
                        </tr>
                        <tr>
                            <th><i class="ph ph-user-square"></i>Last Name</th>
                            <td>Doe</td>
                        </tr>
                        <tr>
                            <th><i class="ph ph-gender-intersex"></i>Gender</th>
                            <td>Mail</td>
                        </tr>
                        <tr>
                            <th><i class="ph ph-envelope"></i>Email</th>
                            <td>john@gmail.com</td>
                        </tr>
                        <tr>
                            <th><i class="ph ph-phone"></i>Contact</th>
                            <td>0774399256</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script src="<?php echo URLROOT; ?>/assets/js/member-script.js?v=<?php echo time();?>"></script>
</body>

</html>