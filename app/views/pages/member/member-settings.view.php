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
        <div class="title">
            <h1>View Trainer Details</h1>
            <div class="greeting">
                <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
            </div>
        </div>
        </div>
        <div class="trainerviewbtnBox">
            <div class="trainerviewbtn-profile">
                <div class="trainerviewbtn-profile-img">
                    <img src="<?php echo URLROOT; ?>/assets/images/image.png" alt="">
                </div>
                <div class="trainerviewbtn-profile-detail">
                    <h2><?php echo isset($_SESSION['first_name']) ? $_SESSION['first_name'] : 'User'; ?></h3>
                </div>
            </div>   
             <div class="trainerviewbtn-profileTable-container">
                <table>
                    <tr>
                        <td class="first"><h4>Age</h4></td>
                        <td>30 years</td>
                    </tr>
                    <tr>
                        <td class="first"><h4>Email</h4></td>
                        <td>jamesmary@gmail.com</td>
                    </tr>
                    <tr>
                        <td class="first"><h4>Contact</h4></td>
                        <td>0711234567</td>
                    </tr>
                    <tr>
                        <td class="first"><h4>NIC No</h4></td>
                        <td>200244555667</td>
                    </tr>
                    <tr>
                        <td class="first"><h4>Gender</h4></td>
                        <td>Female</td>
                    </tr>
                    <tr>
                        <td class="first"><h4>Weight</h4></td>
                        <td>58 kg</td>
                    </tr>
                </table>
            </div>       
        </div>   
               
    </main>

    <script src="<?php echo URLROOT; ?>/assets/js/member/member-script.js?v=<?php echo time();?>"></script>
</body>

</html>