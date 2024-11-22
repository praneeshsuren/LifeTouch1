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
        <?php require APPROOT . '/views/components/manager_sidebar.view.php'; ?>
    </section>

    <main>
        <div class="top">
            <h1 class="title">Edit Equipment</h1>
        </div>

        <div class="box">
            <a href="<?php echo URLROOT; ?>/manager/equipment_view/<?php echo $equipment->equipment_id; ?>" class="btn" style="float: right; margin-top: -10px;margin-bottom:3px;">Back</a>

            <form method="post" enctype="multipart/form-data">
                <?php if (!empty($errors)): ?>
                    <div class="alert">
                        <?= implode("<br>", $errors); ?>
                    </div>
                <?php endif; ?>

                <!-- Display current equipment image -->
                <div>

                    <div class="profile-img-container">
                        <img class="profile-img" src="<?php echo URLROOT; ?>/assets/images/Equipment/<?php echo htmlspecialchars($equipment->file); ?>" alt="Equipment Image">
                    </div>
                    <p class="file-upload-text">Click below to select a new image (if needed)</p>
                    <input onchange="display_image(this.files[0])" type="file" class="file-upload-input" name="file">
                </div>

                <!-- Edit Name -->
                <div class="input-container">
                    <input type="text" id="name" name="name" value="<?php echo $equipment->name; ?>" required>
                    <label for="name" class="label"><i class="ph ph-barbell"></i>Name</label>
                    <div class="underline"></div>
                </div>

                <!-- Edit Description -->
                <div class="input-container">
                    <input type="text" id="description" name="description" value="<?php echo $equipment->description; ?>" required>
                    <label for="description" class="label"><i class="ph ph-clipboard-text"></i>Description</label>
                    <div class="underline"></div>
                </div>


                <!-- Submit Button -->
                <div class="member-buttons">
                    <button type="submit" class="edit-button">Save</button>
                    <a href="<?php echo URLROOT; ?>/manager/equipment" class="delete-button">Back</a>
                </div>
            </form>
        </div>
    </main>

    <script>
        function display_image(file) {
            if (file) {
                var img = document.querySelector(".profile-img");
                img.src = URL.createObjectURL(file);
            }
        }
    </script>
    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time(); ?>"></script>
</body>

</html>