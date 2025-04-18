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
            <div class="filters">
                <a href="equipment_report"><button class="filter" style="background-color:#007bff;color:white;">All Services</button></a>
                <a href="equipment_upcoming_services"> <button class="filter">Upcoming Services</button></a>
                <a href="equipment_overdue_services"> <button class="filter">Overdue Services</button></a>
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
            </div>


            <div class="download-button" id="downloadPDF">
                <div class="download-wrapper">
                    <div class="download-text">Download</div>
                    <span class="download-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="2em" height="2em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15V3m0 12l-4-4m4 4l4-4M2 17l.621 2.485A2 2 0 0 0 4.561 21h14.878a2 2 0 0 0 1.94-1.515L22 17"></path>
                        </svg>
                    </span>
                </div>
            </div>


            <div class="user-table-wrapper">
                <table class='user-table'>
                    <thead>
                        <tr>

                            <th>Equipment Name</th>
                            <th>Service Date</th>
                            <th>Next Service Date</th>
                            <th>Service Cost</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['services'])): ?>
                            <?php foreach ($data['services'] as $service) : ?>
                                <tr class="<?php echo (strtotime($service->next_service_date) < time()) ? 'overdue' : ''; ?>">

                                    <td><?php echo !empty($service->equipment_name) ? htmlspecialchars($service->equipment_name) : 'N/A'; ?></td> <!-- Correct Equipment Name -->
                                    <td><?php echo htmlspecialchars($service->service_date); ?></td>
                                    <td><?php echo htmlspecialchars($service->next_service_date); ?></td>
                                    <td><?php echo htmlspecialchars($service->service_cost); ?></td>
                                    <td class="status <?php echo (strtotime($service->next_service_date) < time()) ? 'overdue' : 'upcoming'; ?>">
                                        <?php echo (strtotime($service->next_service_date) < time()) ? 'Overdue' : 'Upcoming'; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">No service records available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </main>

    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time(); ?>"></script>
    <script>
        document.getElementById("downloadPDF").addEventListener("click", function(event) {
            event.preventDefault();

            // Open the PDF in a new window
            var pdfUrl = "/LifeTouch1/public/make_pdf";
            window.open(pdfUrl, "_blank");
        });
    </script>
    <script>
        document.getElementById("downloadPDF").addEventListener("click", function(event) {
            event.preventDefault();

            var memberId = this.getAttribute("data-member-id");

            // Create a new AJAX request
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "generate-pdf.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onload = function() {
                if (xhr.status === 200) {
                    // If successful, initiate download
                    var pdfPath = xhr.responseText; // Response should be the file path
                    window.location.href = pdfPath; // Trigger the file download
                } else {
                    alert("Failed to generate PDF");
                }
            };

            // Send the member_id to the server
            xhr.send("member_id=" + memberId);
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const startDateInput = document.getElementById("startDate");
            const endDateInput = document.getElementById("endDate");
            const tableRows = document.querySelectorAll(".user-table tbody tr");

            function filterByDate() {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);

                tableRows.forEach(row => {
                    const serviceDateText = row.children[1].textContent.trim(); // Service Date is 2nd column
                    const serviceDate = new Date(serviceDateText);

                    // If no date filters are set, show all
                    if (!startDateInput.value && !endDateInput.value) {
                        row.style.display = "";
                        return;
                    }

                    let showRow = true;

                    if (startDateInput.value && serviceDate < startDate) {
                        showRow = false;
                    }

                    if (endDateInput.value && serviceDate > endDate) {
                        showRow = false;
                    }

                    row.style.display = showRow ? "" : "none";
                });
            }

            // Attach event listeners
            startDateInput.addEventListener("change", filterByDate);
            endDateInput.addEventListener("change", filterByDate);
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