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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/admin-style.css?v=<?php echo time(); ?>" />

    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title><?php echo APP_NAME; ?></title>
    <style>
        .member-phone-display {
            font-size: 0.9rem;
            margin-top: 4px;
        }

        select {
            font-family: 'Poppins', sans-serif;
            padding: 10px 12px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            color: #333;
            appearance: none;
            width: 100%;
            height: 45px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        select:focus {
            border-color: #4CAF50;
            outline: none;
        }

        select option {
            font-size: 14px;
            padding: 8px;
        }
    </style>
</head>

<body>

    <section class="sidebar">
        <?php require APPROOT . '/views/components/receptionist-sidebar.view.php' ?>
    </section>

    <main>
        <div class="title">
            <h1>Payment</h1>
            <div class="greeting">
                <?php require APPROOT . '/views/components/user-greeting.view.php' ?>
            </div>
        </div>

        <div class="create-event-container">
            <div class="form-container">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" style="color:red;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success" style="color:green;">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo URLROOT; ?>/receptionist/payment" method="POST" class="create-event-form">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="member_id">Member ID</label>
                            <input type="text" id="member_id" name="member_id" required placeholder="Enter member ID">
                            <div id="member-info" class="member-info-display">
                                <!-- Will display name and phone here -->
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="plan">Membership Plan</label>
                            <select name="plan" id="plan" required>
                                <option value="" disabled selected>Select a membership plan</option>
                                <?php foreach ($plans as $p): ?>
                                    <option value="<?php echo htmlspecialchars($p->plan); ?>"
                                        data-duration="<?php echo htmlspecialchars($p->duration); ?>">
                                        <?php echo htmlspecialchars($p->plan) . " ({$p->duration} months) - Rs {$p->amount}"; ?>
                                    </option>

                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" id="start_date" name="start_date" required readonly>
                        </div>

                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" id="end_date" name="end_date" required readonly>
                        </div>


                        <div class="form-actions">
                            <button type="submit" class="btn submit-btn">Pay</button>
                            <button type="reset" class="btn reset-btn">Cancel</button>
                        </div>
                </form>
            </div>
        </div>


    </main>

    <!-- SCRIPT -->
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const planSelect = document.getElementById('plan');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            planSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const durationMonths = parseInt(selectedOption.getAttribute('data-duration'));

                if (!isNaN(durationMonths)) {
                    const today = new Date();

                    // Format Start Date
                    const startDate = today.toISOString().split('T')[0];
                    startDateInput.value = startDate;

                    // Calculate End Date
                    const endDate = new Date(today);
                    endDate.setMonth(endDate.getMonth() + durationMonths);

                    // Fix for months like January 31 + 1 month => March 3
                    if (endDate.getDate() !== today.getDate()) {
                        endDate.setDate(0); // Move to last day of previous month
                    }

                    const formattedEndDate = endDate.toISOString().split('T')[0];
                    endDateInput.value = formattedEndDate;
                }
            });
        });
    </script>


    <script src="<?php echo URLROOT; ?>/assets/js/receptionist-script.js?v=<?php echo time(); ?>"></script>
    <script>
        phoneDisplay.textContent = `Phone: ${data.phone}`;
    </script>
</body>

</html>