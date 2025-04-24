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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/admin-style.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/add_member-style.css?v=<?php echo time(); ?>" />

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
        <div class="title">
            <h1>Add Member</h1>
            <div class="greeting">
                <?php require APPROOT . '/views/components/user-greeting.view.php' ?>
            </div>
        </div>

        <div class="table-container">
            <div class="filters">
                <a href="<?php echo URLROOT; ?>/report/participant_details/<?php echo isset($event->event_id) ? $event->event_id : ''; ?>"><button class="filter" onclick="history.back(); return false;">Summary</button></a>
                <a href="#"> <button class="filter" style="background-color:#007bff;color:white;">Add participant</button></a>
            </div>
            <div class="table-container">

                <form method="POST" action="<?php echo URLROOT; ?>/report/event_payment/<?php echo $event_id; ?>">
                    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                        <pre><?php print_r($_POST); ?></pre>
                    <?php endif; ?>


                    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                    <div class="form-group">
                        <label for="full_name">Full Name:</label>
                        <input type="text" id="full_name" name="full_name"
                            value="<?php echo htmlspecialchars($old['full_name'] ?? ''); ?>" required>
                        <?php if (!empty($errors['full_name'])): ?>
                            <span class="error"><?php echo $errors['full_name']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="nic">NIC:</label>
                        <input type="text" id="nic" name="nic"
                            value="<?php echo htmlspecialchars($old['nic'] ?? ''); ?>" required>
                        <?php if (!empty($errors['nic'])): ?>
                            <span class="error"><?php echo $errors['nic']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="is_member" name="is_member"
                            <?php echo ($old['is_member'] ?? false) ? 'checked' : ''; ?>>
                        <label for="is_member">Is Gym Member?</label>
                    </div>

                    <div class="form-group" id="membership-group"
                        style="display: <?php echo ($old['is_member'] ?? false) ? 'block' : 'none'; ?>">
                        <label for="membership_number">Membership Number:</label>
                        <input type="text" id="membership_number" name="membership_number"
                            value="<?php echo htmlspecialchars($old['membership_number'] ?? ''); ?>">
                        <?php if (!empty($errors['membership_number'])): ?>
                            <span class="error"><?php echo $errors['membership_number']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="text" id="email" name="email"
                            value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" required>
                        <?php if (!empty($errors['email'])): ?>
                            <span class="error"><?php echo $errors['email']; ?></span>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-primary">Add Participant</button>
                </form>
            </div>




        </div>
    </main>

    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time(); ?>"></script>
    <script>
        // Show/hide membership number field based on checkbox
        document.getElementById('is_member').addEventListener('change', function() {
            document.getElementById('membership-group').style.display =
                this.checked ? 'block' : 'none';
        });
    </script>

</body>


</html>