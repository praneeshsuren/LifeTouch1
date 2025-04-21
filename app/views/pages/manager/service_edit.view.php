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
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .service-form {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .input-container {
            margin-bottom: 15px;
        }

        .input-container label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .input-container input[type="date"],
        .input-container input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .button-container {
            text-align: right;
        }

        .edit-button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .edit-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <section class="sidebar">
        <?php require APPROOT . '/views/components/manager_sidebar.view.php'; ?>
    </section>

    <main>
        <div class="title">

            <h1>Edit Service history</h1>
            <div class="greeting">
                <?php require APPROOT . '/views/components/user-greeting.view.php' ?>
            </div>

        </div>


        <div class="service-form" style="margin-top: 60px; height: 99%; width:1000px;">

            <a href="<?php echo URLROOT; ?>/manager/equipment_view/<?php echo isset($equipment->equipment_id); ?>" class="btn" style="float: right; margin-top: 10px;margin-bottom:3px;">Back</a>


            <form method="post" action="<?php echo URLROOT; ?>/service/updateService/<?php echo htmlspecialchars($service->service_id); ?>">
                
                <input type="hidden" name="service_id" value="<?php echo htmlspecialchars(isset($service->service_id)); ?>">

                <div class="input-container">
                    <label for="service_date">Service Date:</label>
                    <input type="date" id="service_date" name="service_date" value="<?php echo $service->service_date ?>" required>
                </div>
                <div class="input-container">
                    <label for="service_date">Next Service Date:</label>
                    <input type="date" id="next_service_date" name="next_service_date" value="<?php echo $service->next_service_date ?>" required>
                </div>
                <div class="input-container">
                    <label for="service_cost">Service Cost:</label>
                    <input type="text" id="service_cost" name="service_cost" value="<?php echo $service->service_cost ?>" required>
                </div>
                <div class="button-container">
                    <button type="submit" class="edit-button" style="display: block; margin: 0 auto; border-radius: 20px;width:200px;margin-top:20px;">Update</button>
                </div>
            </form>

        </div>

    </main>
    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time(); ?>"></script>
</body>

</html>