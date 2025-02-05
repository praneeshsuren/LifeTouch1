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

    <style>
        .member-buttons {
    display: flex;
    height: 100px; /* Adjust height if necessary */
}

.edit-button {
    border-radius: 20px;
    width: 150px;
    height: 50px;
    text-align: center;
    font-size: 16px;
    padding: 10px;
    border: none;
    cursor: pointer;
    background-color: #007bff; /* Change to your preferred color */
    color: white;
    transition: transform 0.3s ease, background-color 0.3s ease;
}

.edit-button:hover {
    transform: scale(1.1); /* Scales the button on hover */
    background-color: #0056b3; /* Darker shade on hover */
}
.input-container {
    width: 100%;
    max-width: 800px; /* Ensures the container doesn't exceed 100% of its parent */
    margin-bottom: 15px; /* Optional: Add some spacing between input fields */
}

.input-container input {
    width: 100%; /* Makes the input element take up the full width of its container */
}


    </style>
</head>

<body>
    <section class="sidebar">
        <?php require APPROOT . '/views/components/manager_sidebar.view.php'; ?>
    </section>

    <main>
        <div class="top">
            <h1 class="title">Add Equipment</h1>
            <div class="bell">
                <i class="ph ph-bell"></i>
                <p>Hi, John!</p>
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