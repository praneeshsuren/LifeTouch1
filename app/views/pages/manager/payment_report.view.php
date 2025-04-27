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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/downloadButton-style.css?v=<?php echo time(); ?>" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title><?php echo APP_NAME; ?></title>
    <style>
        .active-row {
            background-color: #e8f5e9;
        }

        .inactive-row {
            background-color: #ffebee;
        }

        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: capitalize;
        }

        .status.active {
            background-color: #c8e6c9;
            color: #256029;
        }

        .status.inactive {
            background-color: #ffcdd2;
            color: #c63737;
        }
    </style>

</head>

<body>

    <section class="sidebar">
        <?php require APPROOT . '/views/components/manager_sidebar.view.php' ?>
    </section>

    <main>
        <div class="title">
            <h1>Membership Plan Report</h1>
            <div class="greeting">
                <?php require APPROOT . '/views/components/user-greeting.view.php' ?>
            </div>
        </div>

        <div class="table-container">

        <div class="filters">
                <a href="#"><button style="background-color:#007bff;color:white;" class="filter">Online Payment</button></a>
                <a href="physicalPayment_report"> <button class="filter" >Physical Payment</button></a>
            </div>
            <div class="date-filter-container">
                <div class="left">
                    <label for="startDate">Start Date: </label>
                    <input type="date" class="date-input" id="startDate" placeholder="Start Date">
                </div>
                <div class="right">
                    <label for="endDate">End Date: </label>
                    <input type="date" class="date-input" id="endDate" placeholder="End Date">
                </div>
                <button id="clearDateFilter" class="filter">Clear Date Filter</button>
                <a href="<?php echo URLROOT; ?>/manager/report" class="btn" style="position: absolute; top: 90px; right: 60px;">Back</a>

            </div>

            <div class="table-container">


                <div class="user-table-wrapper">
                    <div class="table-scroll-container">
                        <table class='user-table'>
                            <thead>
                                <tr>
                                    <th>Member ID</th>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Email</th>
                                    <th>Membership Plan</th>
                                    <th>Start Date</th>
                                    <th>Amount</th>
                                    <th>Valid Until</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($reportData)): ?>
                                    <?php foreach ($reportData as $member) : ?>
                                        <tr class="<?php echo ($member->subscription_status == 'active') ? 'active-row' : 'inactive-row'; ?>">
                                            <td><?php echo htmlspecialchars($member->member_id); ?></td>
                                            <td><?php echo htmlspecialchars($member->member_name); ?></td>
                                            <td><?php echo htmlspecialchars($member->contact_number); ?></td>
                                            <td><?php echo htmlspecialchars($member->email_address); ?></td>
                                            <td><?php echo htmlspecialchars($member->membership_plan); ?></td>
                                            <td><?php echo date('Y-m-d', strtotime($member->membership_start_date)); ?></td>
                                            <td><?php echo 'Rs. ' . number_format($member->expected_amount, 2); ?></td>
                                            <td><?php echo date('Y-m-d', strtotime($member->last_valid_date)); ?></td>
                                            <td>
                                                <?php if ($member->subscription_status == 'active'): ?>
                                                    <span class="status active">Active</span>
                                                <?php else: ?>
                                                    <span class="status inactive">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" style="text-align: center;">No member records available</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time(); ?>"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const startDateInput = document.getElementById("startDate");
        const endDateInput = document.getElementById("endDate");
        const tableRows = document.querySelectorAll(".user-table tbody tr");

        // Function to validate dates
        function validateDates() {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            const today = new Date();

            let isValid = true;

            // Check if start date is in the future
            if (startDate > today) {
                alert("Start date cannot be in the future.");
                startDateInput.value = ''; // Clear the start date field
                isValid = false;
            }

            // Check if end date is in the future
            if (endDate > today) {
                alert("End date cannot be in the future.");
                endDateInput.value = ''; // Clear the end date field
                isValid = false;
            }

            // Ensure that start date is earlier than end date
            if (startDate && endDate && startDate >= endDate) {
                alert("Start date must be before the end date.");
                endDateInput.value = ''; // Clear the end date field
                isValid = false;
            }

            return isValid;
        }

        // Function to filter the rows based on the selected dates
        function filterByDate() {
            // Ensure the dates are valid before filtering
            if (!validateDates()) {
                return; // Stop filtering if dates are invalid
            }

            const startDate = startDateInput.value ? new Date(startDateInput.value) : null;
            const endDate = endDateInput.value ? new Date(endDateInput.value) : null;

            tableRows.forEach(row => {
                if (row.cells.length < 9) return; // Skip header and empty rows

                const validDateText = row.cells[7].textContent.trim(); // Valid Until is 8th column (0-indexed 7)
                const validDate = new Date(validDateText);

                let showRow = true;

                if (startDate && validDate < startDate) {
                    showRow = false;
                }

                if (endDate && validDate > endDate) {
                    showRow = false;
                }

                row.style.display = showRow ? "" : "none";
            });
        }

        // Attach event listeners
        startDateInput.addEventListener("change", filterByDate);
        endDateInput.addEventListener("change", filterByDate);

        document.getElementById("clearDateFilter").addEventListener("click", function() {
            startDateInput.value = '';
            endDateInput.value = '';
            tableRows.forEach(row => row.style.display = "");
        });
    });
</script>

    <script>
        document.getElementById("clearDateFilter").addEventListener("click", function() {
            document.getElementById("startDate").value = '';
            document.getElementById("endDate").value = '';
            document.querySelectorAll(".user-table tbody tr").forEach(row => {
                row.style.display = "";
            });
        });
    </script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tableRows = document.querySelectorAll(".user-table tbody tr");

        // Function to filter rows by inactive status
        function filterInactiveRows() {
            tableRows.forEach(row => {
                // Check the status column (the last column, index 8)
                const statusCell = row.cells[8]; // Status is the 9th column (0-indexed 8)
                if (statusCell) {
                    const statusText = statusCell.textContent.trim().toLowerCase();
                    
                    // Hide the row if the status is not 'inactive'
                    if (statusText !== 'inactive') {
                        row.style.display = 'none';
                    } else {
                        row.style.display = ''; // Show the row if status is inactive
                    }
                }
            });
        }

        // Call the function to filter rows when the page loads
        filterInactiveRows();
    });
</script>


</body>


</html>