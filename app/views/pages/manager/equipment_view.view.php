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
            <h1 class="title">Equipment Details</h1>
            <div class="bell">
                <i class="ph ph-bell"></i>
                <p>Hi, John!</p>
            </div>
        </div>

        <div class="box">
            <a href="<?php echo URLROOT; ?>/manager/equipment" class="btn" style="float: right; margin-top: -10px;margin-bottom:3px;">Back</a>

            <div class="member-card">
                <div>

                    <div class="profile-img-container">
                        <img class="profile-img" src="<?php echo URLROOT; ?>/assets/images/Equipment/<?php echo htmlspecialchars($equipment->file); ?>" alt="Equipment Image">
                    </div>
                    <a href="<?php echo URLROOT; ?>/manager/equipment_edit/<?php echo $equipment->equipment_id; ?>">
                        <button class="edit-button">Edit</button>
                    </a>
                    <a href="<?php echo URLROOT; ?>/manager/equipment_delete/<?php echo $equipment->equipment_id; ?>" onclick="return confirm('Are you sure you want to delete this equipment?');">
                        <button class="delete-button">Delete</button>
                    </a>

                </div>
                <div>
                    <h2 class="announcement-title"><?php echo htmlspecialchars($equipment->name); ?></h2>
                    <div class="para">
                        <p>Purchase Date : <?php echo htmlspecialchars($equipment->purchase_date); ?></p>
                        <p>Purchase Price : <?php echo htmlspecialchars($equipment->purchase_price); ?></p>
                        <p>Purchase Shop : <?php echo htmlspecialchars($equipment->purchase_shop); ?></p>
                        <p>Description : <?php echo htmlspecialchars($equipment->description); ?></p>
                    </div>
                </div>


            </div>
            <!-- Table Section -->
            <div class="purchase-table-container">
                <h3>Service History</h3>
                <table class="purchase-table">
                    <thead>
                        <tr>
                            <th>Service Date</th>
                            <th>Service Cost</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2023-11-15</td>
                            <td>50000</td>
                        </tr>
                        <tr>
                            <td>2023-11-18</td>
                            <td>50000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="service-form">
                <h2>Add Service</h2>
                <form method="post" action="add_service.php">
                    <div class="input-container">
                        <label for="service_date">Service Date:</label>
                        <input type="date" id="service_date" name="service_date" required>
                    </div>
                    <div class="input-container">
                        <label for="service_cost">Service Cost:</label>
                        <input type="text" id="service_cost" name="service_cost" required>
                    </div>
                    <div class="button-container">
                        <button class="edit-button">Add Service</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time(); ?>"></script>
</body>

</html>