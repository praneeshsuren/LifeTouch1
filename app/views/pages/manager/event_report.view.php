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
            <h1>Event Report</h1>
            <div class="greeting">
                <?php require APPROOT . '/views/components/user-greeting.view.php' ?>
            </div>
        </div>

        <div class="table-container">
            <a href="<?php echo URLROOT; ?>/manager/report" class="btn" style="float: right; margin-top: -10px;margin-bottom:3px;">Back</a>
            <br><br><br>
            <div class="user-table-wrapper">
                <table class='user-table'>
                    <thead>
                        <tr>

                            <th>Event Name</th>
                            <th>No of Participants</th>
                            <th>Total Revenue</th>
                            <th>Participant Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['event_participants'])): ?>
                            <?php foreach ($data['event_participants'] as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row->name); ?></td>
                                    <td><?php echo htmlspecialchars($row->participant_count); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($row->total_revenue, 2)); ?></td>
                                    <td>
                                        <a href="<?php echo URLROOT; ?>/report/participant_details/<?php echo $row->event_id; ?>" class="view-link">View</a>
                                    </td>
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
    </main>

    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time(); ?>"></script>


</body>


</html>