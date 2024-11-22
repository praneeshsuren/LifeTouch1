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

                        <p>Description: <?php echo htmlspecialchars($equipment->description); ?></p>
                    </div>
                </div>


            </div>
            <!-- Table Section -->
            <div class="purchase-table-container">
                <h3>Purchase Details</h3>
                <table class="purchase-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Price</th>
                            <th>Buyer Name</th>
                            <th>Buyer Shop Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>2023-11-15</td>
                            <td>Rs.20000</td>
                            <td>John Doe</td>
                            <td>ABC Supplies</td>
                            <td>
                                <a href="#"><i class="ph ph-eye"></i></a>
                                <a href="#"><i class="ph ph-pen"></i></a>
                                <a href="#"><i class="ph ph-trash"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>2023-11-18</td>
                            <td>Rs.20000</td>
                            <td>Jane Smith</td>
                            <td>XYZ Traders</td>
                            <td>
                                <a href="#"><i class="ph ph-eye"></i></a>
                                <a href="#"><i class="ph ph-pen"></i></a>
                                <a href="#"><i class="ph ph-trash"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time(); ?>"></script>
</body>

</html>