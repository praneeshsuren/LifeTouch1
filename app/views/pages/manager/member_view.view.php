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

        <div class="top">
            <h1 class="title">John's Profile</h1>
            <div class="bell">
                <i class="ph ph-bell"></i>
                <p>Hi, John!</p>
            </div>
        </div>

        <div class="box">
            <a href="member" class="btn" style="float: right; margin-top: -10px;margin-bottom:3px;">Back</a>
            <div class="member-card">
                <div>
                    <div class="profile-img-container"><img class="profile-img" src="<?php echo URLROOT; ?>/assets/images/image.png" alt=""></div>
                    <a href="member_edit"><button class="edit-button">Edit</button></a>
                    <button class="delete-button">Delete</button>
                </div>
                <div>
                    <div>
                        <h2 class="announcement-title">John's Details</h2>
                        <div class="para">
                            <p><i class="ph ph-user-circle"></i>&nbsp;First Name : John</p>
                            <p><i class="ph ph-user-square"></i>&nbsp;Last Name : Doe </p>
                            <p><i class="ph ph-hourglass-medium"></i>&nbsp;Age : 25</p>
                            <p><i class="ph ph-person"></i>&nbsp;Height : 200cm</p>
                            <p><i class="ph ph-person"></i>&nbsp;Weight : 60Kg</p>
                            <p><i class="ph ph-gender-intersex"></i>&nbsp;Gender : Male</p>
                            <p><i class="ph ph-envelope"></i>&nbsp;Email : johndoe@gmail.com</p>
                            <p><i class="ph ph-phone"></i>&nbsp;Contact : 0701455332</p>
                            <p><i class="ph ph-house-line"></i>&nbsp;Address : John Smith, 5480 7th Ave, San Francisco</p>
                        </div>
                    </div>
                </div>
            </div>
            <h2>Memberships</h2>
            <div class="memberships">
                <img class="membership-img" src="<?php echo URLROOT; ?>/assets/images/membership.jpg" alt="">
                <p>All access</p>
            </div>
            <div class="memberships">
                <img class="membership-img" src="<?php echo URLROOT; ?>/assets/images/membership.jpg" alt="">
                <p>Personal Training</p>
            </div>
            <br />
            <div class="payment-table">
                <section class="payment-header">
                    <h1>Payment Details</h1>
                </section>
                <section class="payment-body">
                    <table>
                        <thead>
                            <tr>
                                <td>Payment Type</td>
                                <td>Due Date</td>
                                <td>Amount</td>
                                <td>Status</td>
                            </tr>

                        <tbody>
                            <tr>
                                <td> Monthly Package</td>
                                <td>2024.11.11</td>
                                <td>Rs 5000</td>
                                <td>
                                    <p class="status paid">Paid</p>
                                </td>
                            </tr>
                            <tr>
                                <td> Monthly Package</td>
                                <td>2024.11.11</td>
                                <td>Rs 5000</td>
                                <td>
                                    <p class="status notpaid">Not Paid</p>
                                </td>
                            </tr>
                        </tbody>
                        </thead>
                    </table>
                </section>
            </div>
        </div>
    </main>

    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time(); ?>"></script>
</body>

</html>