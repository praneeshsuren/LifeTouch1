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
    
</head>

<body>

    <section class="sidebar">
        <?php require APPROOT . '/views/components/manager_sidebar.view.php' ?>
    </section>

    <main>
        <div class="title">
            <h1>Equipment Service Report</h1>
            <div class="greeting">
                <?php require APPROOT . '/views/components/user-greeting.view.php' ?>
            </div>
        </div>

        <div class="table-container">


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
                                    <th>Expected Amount</th>
                                    <th>Total Paid</th>
                                    <th>Last Payment</th>
                                    <th>Valid Until</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($reportData)): ?>
                                    <?php foreach ($reportData as $member) : ?>
                                        <tr class="<?php echo !$member->is_compliant ? 'non-compliant' : ''; ?>">
                                            <td><?php echo htmlspecialchars($member->member_id); ?></td>
                                            <td><?php echo htmlspecialchars($member->member_name); ?></td>
                                            <td><?php echo htmlspecialchars($member->contact_number); ?></td>
                                            <td><?php echo htmlspecialchars($member->email_address); ?></td>
                                            <td><?php echo htmlspecialchars($member->membership_plan); ?></td>
                                            <td><?php echo htmlspecialchars($member->membership_start_date); ?></td>
                                            <td><?php echo 'Rs. ' . number_format($member->expected_amount, 2); ?></td>
                                            <td><?php echo 'Rs. ' . number_format($member->total_paid, 2); ?></td>
                                            <td><?php echo htmlspecialchars($member->last_payment_date); ?></td>
                                            <td><?php echo htmlspecialchars($member->last_valid_date); ?></td>
                                            <td>
                                                <?php if ($member->is_compliant): ?>
                                                    <span class="status compliant">Active</span>
                                                <?php else: ?>
                                                    <span class="status non-compliant">Expired</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="11" style="text-align: center;">No member records available</td>
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

            function filterByDate() {
                const startDate = startDateInput.value ? new Date(startDateInput.value) : null;
                const endDate = endDateInput.value ? new Date(endDateInput.value) : null;

                tableRows.forEach(row => {
                    if (row.cells.length < 11) return; // Skip header and empty rows

                    const validDateText = row.cells[9].textContent.trim(); // Valid Until is 10th column
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


</body>


</html>