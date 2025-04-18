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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/report-style.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/popup-style.css?v=<?php echo time(); ?>" />


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title><?php echo APP_NAME; ?></title>
    <style>
        .plain-x {
            background: none;
            border: none;
            color: inherit;
            font: inherit;
            cursor: pointer;
            padding: 0;
            margin: 0;
            outline: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
    </style>
</head>

<body>
    <div id="mainContainer">
        <section class="sidebar">
            <?php require APPROOT . '/views/components/manager_sidebar.view.php' ?>
        </section>

        <main>
            <div class="title">
                <h1>Membership Plans</h1>
                <div class="greeting">
                    <?php require APPROOT . '/views/components/user-greeting.view.php' ?>
                </div>
            </div>


            <div class="table-container">
                <div class="heading" style="margin-left: 1040px;">
                    <a href="#" class="newMember-btn" onclick="openForm()"><i class="ph ph-plus"></i> Add Plan</a>
                </div>
                <div class="user-table-wrapper">
                    <table class='user-table'>
                        <thead>
                            <tr>
                                <th>Plan Name</th>
                                <th>Amount</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data['membership_plan'])): ?>
                                <?php foreach ($data['membership_plan'] as $plan) : ?>
                                    <tr onclick="openEditForm(
                    '<?php echo $plan->membershipPlan_id; ?>',
                    '<?php echo htmlspecialchars($plan->plan); ?>',
                    '<?php echo htmlspecialchars($plan->amount); ?>'
                )">
                                        <td><?php echo htmlspecialchars($plan->plan); ?></td>
                                        <td><?php echo htmlspecialchars($plan->amount); ?></td>
                                        <td>
                                            <form action="<?php echo URLROOT; ?>/manager/delete_plan" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this plan?'); event.stopPropagation();">
                                                <input type="hidden" name="membershipPlan_id" value="<?php echo $plan->membershipPlan_id; ?>">
                                                <button type="submit" class="plain-x">X</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" style="text-align: center;">No plans available</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <!-- Edit Form Popup -->
                    <div id="editForm" class="popup-form">
                        <div class="popup-content">
                            <span class="close-btn" onclick="closeEditForm()">&times;</span>
                            <h2>Edit Membership Plan</h2>
                            <form method="POST" action="<?php echo URLROOT; ?>/manager/update_plan">
                                <input type="hidden" id="edit_membershipPlan_id" name="membershipPlan_id">
                                <div class="input-group">
                                    <label for="edit_plan_name">Plan Name</label>
                                    <input type="text" id="edit_plan_name" name="plan_name" required>
                                </div>
                                <div class="input-group">
                                    <label for="edit_amount">Amount</label>
                                    <input type="text" id="edit_amount" name="amount" required>
                                </div>
                                <div class="button-group">
                                    <button type="submit" class="btn">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="joinForm" class="popup-form">
                        <div class="popup-content">
                            <span class="close-btn" onclick="closeForm()">&times;</span>
                            <h2>New membership plan</h2>

                            <form method="POST" action="<?php echo URLROOT; ?>/manager/create_plan">
                                <input type="hidden" id="eventIdInput" name="event_id" />
                                <div class="input-group">
                                    <label for="plan_name">Plan Name</label>
                                    <input type="text" id="plan_name" name="plan_name" required />
                                </div>
                                <div class="input-group">
                                    <label for="amount">Amount</label>
                                    <input type="text" id="amount" name="amount" required />
                                </div>

                                <div class="button-group">
                                    <button type="submit" class="btn">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </main>

    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time(); ?>"></script>
    <script>
        function openForm() {
            document.getElementById("joinForm").style.display = "flex";
        }

        function closeForm() {
            document.getElementById("joinForm").style.display = "none";
        }

        // Optional: Close when clicking outside modal
        window.onclick = function(event) {
            const modal = document.getElementById("joinForm");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };
    </script>
    <script>
        window.onload = function() {
            // Ensure both modals are hidden on page load
            document.getElementById("editForm").style.display = "none";
        };

        // Open the Edit Form
        function openEditForm(id, planName, amount) {
            document.getElementById("edit_membershipPlan_id").value = id;
            document.getElementById("edit_plan_name").value = planName;
            document.getElementById("edit_amount").value = amount;
            document.getElementById("editForm").style.display = "flex";
        }

        // Close the Edit Form
        function closeEditForm() {
            document.getElementById("editForm").style.display = "none";
        }

        // Close when clicking outside the modal
        window.onclick = function(event) {
            const modal = document.getElementById("editForm");
            if (event.target == modal) {
                closeEditForm();
            }
        };

        // Prevent row click when clicking on the delete button
        document.querySelectorAll('.plain-x').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>

</body>


</html>