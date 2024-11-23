<?php
$gender = ['Male', 'Female', 'Other'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
            <h1 class="title">Edit John's Profile</h1>
            <div class="bell">
                <i class="ph ph-bell"></i>
                <p>Hi, John!</p>
            </div>
        </div>

        <div class="box">
            <div class="member-card">
                <div>
                    <div class="profile-img-container">
                        <img class="profile-img" src="<?php echo URLROOT; ?>/assets/images/image.png" alt="Profile Image">
                    </div>
                    <p class="file-upload-text">Click below to select an image</p>
                    <input onchange="display_image(this.files[0])" type="file" class="file-upload-input" name="image">
                </div>
                <div>
                    <form method="post">
                        <table class="profile-table">
                            <tr>
                                <th colspan="2">User Details :</th>
                            </tr>
                            <tr>
                                <th><i class="ph ph-user-circle"></i>First Name</th>
                                <td>
                                    <input type="text" class="form-control" name="firstname" placeholder="First Name">
                                </td>
                            </tr>
                            <tr>
                                <th><i class="ph ph-user-square"></i>Last Name</th>
                                <td>
                                    <input type="text" class="form-control" name="lastname" placeholder="Last Name">
                                </td>
                            </tr>
                            <tr>
                                <th><i class="ph ph-gender-intersex"></i>Gender</th>
                                <td>
                                    <div class="row">
                                        <select name="type" id="type-list" class="gender-boxes">
                                            <option value disabled selected>--Select gender--</option>
                                            <?php foreach ($gender as $type): ?>
                                                <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><i class="ph ph-envelope"></i>Email</th>
                                <td>
                                    <input type="text" class="form-control" name="email" placeholder="Email">
                                </td>
                            </tr>
                            <tr>
                                <th><i class="ph ph-phone"></i>Contact</th>
                                <td>
                                    <input type="text" class="form-control" name="phone" placeholder="Contact">
                                </td>
                            </tr>

                        </table>
                        <a href="#"><button class="edit-button">Save</button></a>
                        <a href="member_view"><button class="delete-button">Back</button></a>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script>
        function display_image(file) {
            if (file) {
                var img = document.querySelector(".profile-img"); // Select the actual image element
                img.src = URL.createObjectURL(file); // Set the src attribute to the object URL
            }
        }
    </script>
    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time(); ?>"></script>
</body>

</html>