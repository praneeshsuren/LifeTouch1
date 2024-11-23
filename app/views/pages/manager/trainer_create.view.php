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
            <h1 class="title">Add Trainer</h1>
            <div class="bell">
                <i class="ph ph-bell"></i>
                <p>Hi, John!</p>
            </div>
        </div>

        <div class="box">
            <div>
                <a href="trainer" class="btn" style="float: right; margin-top: -10px;margin-bottom:3px;">Back</a>
                <div class="profile-img-container">
                    <img class="profile-img" src="<?php echo URLROOT; ?>/assets/images/no_img.jpg">
                </div>
                <p class="file-upload-text">Click below to select an image</p>
                <input onchange="display_image(this.files[0])" type="file" class="file-upload-input" name="image">
            </div>

            <div class="input-container">
                <input type="text" id="input" required="">
                <label for="input" class="label"><i class="ph ph-user-circle"></i>First Name</label>
                <div class="underline"></div>
            </div>
            <div class="input-container">
                <input type="text" id="input" required="">
                <label for="input" class="label"><i class="ph ph-user-square"></i>Last Name</label>
                <div class="underline"></div>
            </div>
            <div class="input-container">
                <input type="text" id="input" required="">
                <label for="input" class="label"><i class="ph ph-hourglass-medium"></i></i>Age</label>
                <div class="underline"></div>
            </div>
            <div class="input-container">
                <div class="gender-radio">
                    <label> <i class="ph ph-gender-intersex"></i>Gender</label>
                </div>
            </div>
            <div class="radio-buttons">
                <form>
                    <label>
                        <input type="radio" name="radio" checked="">
                        <span>Male</span>
                    </label>
                    <label>
                        <input type="radio" name="radio">
                        <span>Female</span>
                    </label>
                    <label>
                        <input type="radio" name="radio">
                        <span>Other</span>
                    </label>
                </form>
            </div>
            <div class="input-container">
                <input type="text" id="input" required="">
                <label for="input" class="label"><i class="ph ph-certificate"></i>Qualifications</label>
                <div class="underline"></div>
            </div>
            <div class="input-container">
                <input type="text" id="input" required="">
                <label for="input" class="label"><i class="ph ph-envelope"></i>Email</label>
                <div class="underline"></div>
            </div>
            <div class="input-container">
                <input type="text" id="input" required="">
                <label for="input" class="label"><i class="ph ph-phone"></i>Contact</label>
                <div class="underline"></div>
            </div>
            <div class="input-container">
                <input type="text" id="input" required="">
                <label for="input" class="label"><i class="ph ph-house-line"></i>Address</label>
                <div class="underline"></div>
            </div>
            <div class="member-buttons">
                <a href="#"><button class="edit-button">Save</button></a>
                <a href="member"><button class="delete-button">Back</button></a>
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