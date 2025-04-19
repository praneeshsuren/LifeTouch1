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
            <h1 class="title">Supplement Details</h1>
            <div class="bell">
                <i class="ph ph-bell"></i>
                <p>Hi, John!</p>
            </div>
        </div>

        <div class="box">
            <a href="<?php echo URLROOT; ?>/manager/supplements" class="btn" style="float: right; margin-top: -10px;margin-bottom:3px;">Back</a>

            <div class="member-card">
                <div>

                    <div class="profile-img-container">
                        <img class="profile-img" src="<?php echo URLROOT; ?>/assets/images/Supplement/<?php echo htmlspecialchars($supplement->file); ?>" alt="Supplement Image">
                    </div>
                    <a href="<?php echo URLROOT; ?>/manager/supplement_edit/<?php echo $supplement->supplement_id; ?>">
                        <button class="edit-button">Edit</button>
                    </a>
                    <a href="<?php echo URLROOT; ?>/supplement/deleteSupplement/<?php echo $supplement->supplement_id; ?>" onclick="return confirm('Are you sure you want to delete this supplement?');">
                        <button class="delete-button">Delete</button>
                    </a>

                </div>
                <div>
                    <h2 class="announcement-title"><?php echo htmlspecialchars($supplement->name); ?></h2>
                    <p class="announcement-description">Quantity Available: <?php echo htmlspecialchars($supplement->quantity_available); ?></p>
                    <p class="announcement-description">Quantity Sold Till Date: <?php echo htmlspecialchars($supplement->quantity_sold); ?></p>
                </div>


            </div>
            <!-- Table Section -->
            <div class="purchase-table-container">
                <h3>Purchase History</h3>
                <div class="user-table-wrapper">
                    <table class='user-table'>
                        <thead>
                            <tr>
                                <th>Purchase Date</th>
                                <th>Purchase Place</th>
                                <th>Quantity</th>
                                <th>Total Cost</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php if (!empty($purchases)): ?>
                                <?php foreach ($purchases as $purchase): ?>
                                    <tr style="cursor: pointer;" onclick="window.location='<?php echo URLROOT; ?>/manager/purchase_edit/<?php echo $purchase->s_purchaseID; ?>'">
                                        <td><?php echo htmlspecialchars($purchase->purchase_date); ?></td>
                                        <td><?php echo htmlspecialchars($purchase->purchase_shop); ?></td>
                                        <td><?php echo htmlspecialchars($purchase->quantity); ?></td>
                                        <td><?php echo htmlspecialchars($purchase->purchase_price); ?></td>
                                        <td>

                                            </a><a href="<?php echo URLROOT; ?>/supplement/deletePurchase/<?php echo $purchase->s_purchaseID; ?>" onclick="return confirm('Are you sure you want to delete this supplement?');">
                                                <button class="delete-row-btn">Delete</button>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2">No Purchase history available</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="service-form">
                <h2>Add Purchase</h2>
                <form method="post" enctype="multipart/form-data" action="<?php echo URLROOT; ?>/supplement/createPurchase">
                    <input type="hidden" name="supplement_id" value="<?php echo htmlspecialchars($supplement->supplement_id); ?>">

                    <div class="input-container">
                        <label for="service_date">Purchase Date:</label>
                        <input type="date" id="service_date" name="purchase_date" required>
                    </div>
                    <div class="input-container">
                        <label for="service_cost">Purchase Price:</label>
                        <input type="number" id="service_cost" name="purchase_price" required>
                    </div>
                    <div class="input-container">
                        <label for="service_cost">Quantity:</label>
                        <input type="number" id="service_cost" name="quantity" required>
                    </div>
                    <div class="input-container">
                        <label for="service_cost">Purchase Place:</label>
                        <input type="text" id="service_cost" name="purchase_shop">
                    </div>
                    <div class="button-container">
                        <button class="edit-button">Add Purchase</button>
                    </div>
                </form>
            </div>

            </div>
        </div>
    </main>

    <script>

    </script>
    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time(); ?>"></script>
</body>

</html>