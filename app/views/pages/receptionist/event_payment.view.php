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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/receptionist-style.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/payment-style.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/manager-style.css?v=<?php echo time(); ?>" />


    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        (function() {
            var savedMode = localStorage.getItem('mode');
            if (savedMode === 'dark') {
                document.body.classList.add('dark');
            }
        })();
    </script>
    <title><?php echo APP_NAME; ?></title>
</head>

<body>

    <section class="sidebar">
        <?php require APPROOT . '/views/components/receptionist-sidebar.view.php' ?>
    </section>

    <main>
        <div class="title">
            <h1>Event Payment</h1>
            <div class="greeting">
                <?php require APPROOT . '/views/components/user-greeting.view.php' ?>
            </div>
        </div>

        <div class="table-container" style="width: 800px;text-align: center;">
            <?php if (!empty($_SESSION['success'])): ?>
                <div class="success-container" style="color: green; font-size: 16px; margin-bottom: 10px;">
                    <?php echo htmlspecialchars($_SESSION['success']); ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['join_errors']) && is_array($_SESSION['join_errors'])): ?>
                <div class="error-container" style="color: red; font-size: 15px; margin-bottom: 10px;">
                    <?php foreach ($_SESSION['join_errors'] as $error): ?>
                        <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endforeach; ?>
                </div>
                <?php unset($_SESSION['join_errors']); ?>
            <?php endif; ?>
            
            <div class="user-table-wrapper">


                <table class='user-table'>
                    <thead>
                        <tr>

                            <th>Event Name</th>
                            <th>Free or Not (For Members)</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['event'])): ?>
                            <?php foreach ($data['event'] as $row): ?>
                                <tr class="clickable-row" data-event-id="<?php echo $row->event_id; ?>">
                                    <td><?php echo htmlspecialchars($row->name); ?></td>
                                    <td><?php echo $row->free_for_members ? 'Free' : 'Not Free'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center;">No event participant records available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>

            </div>
        </div>
        <div id="popup-form" class="popup-overlay" style="display: none;">
            <div class="popup-box">
                <span id="close-popup" class="close-btn">&times;</span>
                <h2>Join Event</h2>
                <form method="POST" action="<?php echo URLROOT; ?>/receptionist/joinEvent">
                    <input type="hidden" name="event_id" id="join-event-id">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="full_name" required>
                    </div>
                    <div class="form-group">
                        <label>NIC</label>
                        <input type="text" name="nic" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Are you a member?</label>
                        <select name="is_member" id="is_member">
                            <option value="1">Yes</option>
                            <option value="0" selected>No</option>
                        </select>
                    </div>
                    <div class="form-group" id="membership-number-group" style="display: none;">
                        <label>Membership Number (if member)</label>
                        <input type="text" name="membership_number">
                    </div>

                    <button type="submit" class="btn">Submit</button>
                </form>
            </div>
        </div>

    </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/receptionist-script.js?v=<?php echo time(); ?>"></script>
    <script>

        document.addEventListener('DOMContentLoaded', function() {
            const memberSelect = document.getElementById('is_member');
            const membershipNumberGroup = document.getElementById('membership-number-group');

            // Check the initial value and show/hide membership number input
            toggleMembershipNumberField();

            // Add event listener to toggle visibility when the dropdown value changes
            memberSelect.addEventListener('change', function() {
                toggleMembershipNumberField();
            });

            function toggleMembershipNumberField() {
                if (memberSelect.value === '1') {
                    membershipNumberGroup.style.display = 'block'; // Show membership number field
                } else {
                    membershipNumberGroup.style.display = 'none'; // Hide membership number field
                }
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.clickable-row').forEach(function(row) {
                row.addEventListener('click', function() {
                    const eventId = this.getAttribute('data-event-id');
                    document.getElementById('join-event-id').value = eventId;
                    document.getElementById('popup-form').style.display = 'flex';
                });
            });

            document.getElementById('close-popup').addEventListener('click', function() {
                document.getElementById('popup-form').style.display = 'none';
            });
        });
    </script>


</body>

</html>