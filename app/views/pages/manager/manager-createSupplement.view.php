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
        <a href="supplements" class="btn" style="float: right; margin-top: -10px; margin-bottom: 3px;">Back</a>
        <div class="supplements-container">
            <form method="post" enctype="multipart/form-data" action="<?php echo URLROOT; ?>/supplement/create_supplement">


                <div>

                    <div class="supplement-image">
                        <img class="supplement-picture" src="<?php echo URLROOT; ?>/assets/images/dumbell_add.png" alt="Supplement Image">
                    </div>
                    <p class="file-upload-text">Click below to select an image</p>
                    <input 
                        onchange="display_image(this.files[0])" 
                        type="file" 
                        class="file-upload-input" 
                        name="image" 
                        accept="image/*"
                    >
                </div>

                <div class="input-container1">
                    <p class="label"><i class="ph ph-barbell"></i> Name</p>
                    <input type="text" id="name" name="name" >
                    <?php if (!empty($errors['name'])): ?>
                        <small class="error"><?php echo $errors['name']; ?></small>
                    <?php endif; ?>
                </div>

                <div class="input-container1">
                    <p class="label"><i class="ph ph-calendar"></i> Purchase Date</p>
                    <input type="date" id="purchase_date" name="purchase_date" >
                    <?php if (!empty($errors['purchase_date'])): ?>
                        <small class="error"><?php echo $errors['purchase_date']; ?></small>
                    <?php endif; ?>
                </div>

                <div class="input-container1">
                    <p class="label"><i class="ph ph-money"></i> Purchase Price of a Supplement</p>
                    <input type="text" id="purchase_price" name="purchase_price" >
                    <?php if (!empty($errors['purchase_price'])): ?>
                        <small class="error"><?php echo $errors['purchase_price']; ?></small>
                    <?php endif; ?>
                </div>

                <div class="input-container1">
                    <p class="label"><i class="ph ph-stack"></i> Quantity</p>
                    <input type="number" id="quantity" name="quantity" >
                    <?php if (!empty($errors['quantity'])): ?>
                        <small class="error"><?php echo $errors['quantity']; ?></small>
                    <?php endif; ?>
                </div>

                <div class="input-container1">
                    <p class="label"><i class="ph ph-money"></i> Purchase Shop</p>
                    <input type="text" id="purchase_shop" name="purchase_shop" >
                    <?php if (!empty($errors['purchase_shop'])): ?>
                        <small class="error"><?php echo $errors['purchase_shop']; ?></small>
                    <?php endif; ?>
                </div>


                <div class="create-supplement">
                    <button type="submit" class="save-btn">Save</button>

                </div>
            </form>
        </div>

    </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time();?>"></script>
    <script>
        function display_image(file) {
            if (file) {
                var img = document.querySelector(".supplement-picture");
                img.src = URL.createObjectURL(file);
            }
        }
    </script>

</body>