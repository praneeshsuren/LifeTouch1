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
            <h1>Event Paricipant Details</h1>
            <div class="greeting">
                <?php require APPROOT . '/views/components/user-greeting.view.php' ?>
            </div>
        </div>

        <div class="table-container">
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
                            <th>Full Name</th>
                            <th>Is Member</th>
                            <th>Membership Number</th>
                            <th>NIC</th>
                            <th>Contact</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['event_participants'])): ?>
                            <?php foreach ($data['event_participants'] as $participant): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($participant->full_name); ?></td>
                                    <td><?php echo $participant->is_member ? 'Yes' : 'No'; ?></td>
                                    <td><?php echo htmlspecialchars($participant->membership_number ?? '—'); ?></td>
                                    <td><?php echo htmlspecialchars($participant->nic); ?></td>
                                    <td><?php echo htmlspecialchars($participant->contact_no); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center;">No participants found for this event.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                </table>
            </div>
        </div>
    </main>

    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time(); ?>"></script>



    <script>
    // Ensure this is set *before* the click handler
    const eventId = <?php echo json_encode($data['event_id'] ?? null); ?>;
</script>

<script>
    document.getElementById("downloadPDF").addEventListener("click", function(event) {
        event.preventDefault();
        const eventId = <?php echo json_encode($data['event_id']); ?>;
        
        // Open in new tab/window
        window.open(
            `<?php echo URLROOT; ?>/EventParticipant_pdf/index/${eventId}`,
            '_blank' // This makes it open in a new tab
        );
    });
</script>
</body>
</html>