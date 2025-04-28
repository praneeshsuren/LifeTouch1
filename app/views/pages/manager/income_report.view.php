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
    <link rel="stylesheet" href="<?php echo defined('URLROOT') ? URLROOT : '/default-url'; ?>/assets/css/manager-style.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/report-style.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/income-style.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/manager-style.css?v=<?php echo time(); ?>" />


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

            <h1>Income and Expense</h1>
            <div class="greeting">
                <?php require APPROOT . '/views/components/user-greeting.view.php' ?>
            </div>

        </div>

        <div class="create-announcement">
        <a href="<?php echo URLROOT; ?>/manager/report" class="btn" style="float: right; margin-top: -30px;margin-bottom:3px;">Back</a>

        <div class="date-filter-container">
                <div class="left">
                    <label for="startDate">Start Date: </label>
                    <input type="date" class="date-input" id="startDate" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>" placeholder="Start Date">
                </div>
                <div class="right">
                    <label for="endDate">End Date: </label>
                    <input type="date" class="date-input" id="endDate" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>" placeholder="End Date">
                </div>
                <button id="clearDateFilter" class="filter">Clear Date Filter</button>
            </div><br>
            <div class="wrap">
                <div class="card green">
                    <div class="icon-container">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 4.69 2 8v8c0 3.31 4.48 6 10 6s10-2.69 10-6V8c0-3.31-4.48-6-10-6zm0 2c4.42 0 8 2.01 8 4s-3.58 4-8 4-8-2.01-8-4 3.58-4 8-4zm0 16c-4.42 0-8-2.01-8-4v-1.26c1.61 1.17 4.22 1.9 8 1.9s6.39-.73 8-1.9V16c0 1.99-3.58 4-8 4z"></path>
                        </svg>

                    </div>
                    <div class="content">
                        <h2 style="color:black">Total Income</h2>
                        <h3>RS. <?= number_format($totalIncome, 2) ?></h3>
                        <p>Membership Income , Event Income , Supplement Sales</p>
                    </div>
                </div>

                <div class="card">
                    <div class="icon-container">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-2 .9-2 2v2h20V6c0-1.1-.9-2-2-2zm0 4H2v10c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8zm-2 6H6v-2h12v2z"></path>
                        </svg>

                    </div>
                    <div class="content">
                        <h2 style="color:black">Total Expense</h2>
                        <h3>RS. <?= number_format($totalExpense, 2) ?></h3>
                        <p>Supplement Purchase , Salary Payment , Equipment Purchase , Equipment Service</p>
                    </div>
                </div>
            </div>
            <br><br>
            <div class="up">
                <div class="notificatio">
                    <div class="notiglow"></div>
                    <div class="notiborderglow"></div>
                    <div class="notititle">Membership Income</div>
                    <div class="notibody">RS. <?= number_format($membershipPaymentSum, 2) ?> </div>
                </div>
                <div class="notificatio">
                    <div class="notiglow"></div>
                    <div class="notiborderglow"></div>
                    <div class="notititle">Event Income</div>
                    <div class="notibody">RS. <?= number_format($eventPaymentSum, 2) ?></div>
                </div>
                <div class="notificatio">
                    <div class="notiglow"></div>
                    <div class="notiborderglow"></div>
                    <div class="notititle">Supplement Sales</div>
                    <div class="notibody">RS. <?= number_format($supplementSaleseSum, 2) ?> </div>
                </div>
            </div>
            <div class="down">
                <div class="notificatio">
                    <div class="notiglow"></div>
                    <div class="notiborderglow"></div>
                    <div class="notititle">Supplement Purchase</div>
                    <div class="notibody">RS. <?= number_format($supplementPurchaseSum, 2) ?> </div>
                </div>
                
                <div class="notificatio">
                    <div class="notiglow"></div>
                    <div class="notiborderglow"></div>
                    <div class="notititle">Equipment Purchase</div>
                    <div class="notibody">RS. <?= number_format($equipmentPurchaseSum, 2) ?> </div>
                </div>
                <div class="notificatio">
                    <div class="notiglow"></div>
                    <div class="notiborderglow"></div>
                    <div class="notititle">Equipment Service</div>
                    <div class="notibody">RS. <?= number_format($servicePurchaseSum, 2) ?></div>
                </div>
                <div class="notificatio">
                    <div class="notiglow"></div>
                    <div class="notiborderglow"></div>
                    <div class="notititle">Admin Salary Payment</div>
                    <div class="notibody">RS. <?= number_format($adminSalarySum, 2) ?></div>
                </div>
                <div class="notificatio">
                    <div class="notiglow"></div>
                    <div class="notiborderglow"></div>
                    <div class="notititle">Trainer Salary Payment</div>
                    <div class="notibody">RS. <?= number_format($trainerSalarySum, 2) ?></div>
                </div>
                <div class="notificatio">
                    <div class="notiglow"></div>
                    <div class="notiborderglow"></div>
                    <div class="notititle">Receptionist Salary Payment</div>
                    <div class="notibody">RS. <?= number_format($receptionistSalarySum, 2) ?></div>
                </div>
            </div>
        </div>
    </main>
    <script>
    document.getElementById('startDate').addEventListener('change', applyDateFilter);
    document.getElementById('endDate').addEventListener('change', applyDateFilter);
    document.getElementById('clearDateFilter').addEventListener('click', function () {
        const url = new URL(window.location.href);
        url.searchParams.delete('start_date');
        url.searchParams.delete('end_date');
        window.location.href = url.toString();
    });

    function applyDateFilter() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const today = new Date().toISOString().split('T')[0]; // Format YYYY-MM-DD

        // Validation: No future dates
        if ((startDate && startDate > today) || (endDate && endDate > today)) {
            alert('Dates cannot be in the future!');
            return;
        }

        // Validation: Start date must be <= End date
        if (startDate && endDate && startDate > endDate) {
            alert('Start Date cannot be after End Date!');
            return;
        }

        const url = new URL(window.location.href);
        if (startDate) {
            url.searchParams.set('start_date', startDate);
        }
        if (endDate) {
            url.searchParams.set('end_date', endDate);
        }
        window.location.href = url.toString();
    }
</script>

    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time(); ?>"></script>

</body>

</html>
</body>

</html>