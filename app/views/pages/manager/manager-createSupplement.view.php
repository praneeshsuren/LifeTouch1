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
  <!-- CHART.JS -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <title><?php echo APP_NAME; ?></title>
</head>

<body>

    <section class="sidebar">
        <?php require APPROOT . '/views/components/manager_sidebar.view.php' ?>
    </section>

    <main>
        <div class="title">
            <h1>Supplements</h1>
            <div class="greeting">
                <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
            </div>
        </div>

        <div class="box">
            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo esc($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <a href="equipment" class="btn" style="float: right; margin-top: -10px; margin-bottom: 3px;">Back</a>
            <form method="post" enctype="multipart/form-data">


                <div>

                    <div class="profile-img-container">
                        <img class="profile-img" src="<?php echo URLROOT; ?>/assets/images/dumbell_add.png" alt="Equipment Image">
                    </div>
                    <p class="file-upload-text">Click below to select an image</p>
                    <input onchange="display_image(this.files[0])" type="file" class="file-upload-input" name="image" required accept="image/jpg, image/jpeg, image/png">
                </div>

                <div class="input-container">
                    <input type="text" id="name" name="name" required>
                    <label for="name" class="label"><i class="ph ph-barbell"></i>Name</label>
                    <div class="underline"></div>
                </div>

                <div class="input-container">
                    <input type="text" id="description" name="description" required>
                    <label for="description" class="label"><i class="ph ph-clipboard-text"></i>Description</label>
                    <div class="underline"></div>
                </div>
                <div class="input-container">
                    <input type="date" id="purchase_date" name="purchase_date" required>
                    <label for="date" class="label"><i class="ph ph-calendar"></i>Purchase Date</label>
                    <div class="underline"></div>
                </div>

                <div class="input-container">
                    <input type="text" id="purchase_price" name="purchase_price" required>
                    <label for="price" class="label"><i class="ph ph-money"></i>Purchase Price</label>
                    <div class="underline"></div>
                </div>

                <div class="input-container">
                    <input type="text" id="purchase_shop" name="purchase_shop" required>
                    <label for="price" class="label"><i class="ph ph-money"></i>Purchase Shop</label>
                    <div class="underline"></div>
                </div>

                <div class="member-buttons">
                    <button type="submit" class="edit-button">Save</button>

                </div>
            </form>
        </div>

    </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time();?>"></script>
    <script>
        function display_image(file) {
            if (file) {
                var img = document.querySelector(".profile-img");
                img.src = URL.createObjectURL(file);
            }
        }
    </script>

</body>