<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
  <!-- STYLESHEET -->
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/trainer-style.css?v=<?php echo time();?>" />
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/sidebar-greeting.css?v=<?php echo time();?>" />
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
  <title><?php echo APP_NAME; ?></title>
</head>

<body>

  <section class="sidebar">
    <?php require APPROOT . '/views/components/trainer-sidebar.view.php' ?>
  </section>

  <main>
    
    <div class="title">
      <h1>Supplements Records</h1>
      <div class="greeting">
        <?php require APPROOT . '/views/components/user-greeting.view.php' ?>
      </div>
    </div>

    <div class="view-user-container">
      <div class="navbar-container">
        <div class="navbar">
          <ul class="nav-links">
              <li><a href="" id="userDetailsLink"><i class="ph ph-user"></i>User Details</a></li>
              <li><a href="" id="attendanceLink"><i class="ph ph-calendar-dots"></i>Attendance Records<span class=""></a></li>
              <li><a href="" id="workoutSchedulesLink"><i class="ph ph-notebook"></i>Workout Schedules</a></li>
              <li class="active"><a href="" id="supplementRecordsLink"><i class="ph ph-barbell"></i>Supplement Records</a></li>
          </ul>
        </div>
      </div>

        <div class="user-container">
            <!-- Display Supplement Records in a Table -->
            <div id="supplement-table-container" class="supplement-table">

                <?php if (!empty($supplements)) : ?>

                    <table>
                        
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Supplement Image</th>
                                <th>Supplement Name</th>
                                <th>Purchase Date</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $rowNumber = 1; ?>
                            <?php foreach ($supplements as $supplement) : ?>
                                <?php
                                $purchaseDate = date('d-m-Y', strtotime($supplement->sale_date));
                                ?>
                                <tr>
                                <td><?php echo $rowNumber++; ?></td>
                                <td><img src="<?php echo URLROOT . '/assets/images/Supplement/' . $supplement->file; ?>" alt="<?php echo $supplement->name; ?>" class="supplement-image"></td>
                                <td><?php echo $supplement->name; ?></td>
                                <td><?php echo $purchaseDate; ?></td>
                                <td><?php echo $supplement->quantity; ?></td>
                                <td><?php echo number_format($supplement->price_of_a_supplement, 2); ?> LKR</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        
                    </table>

                <?php else : ?>
                    <p>No supplement records found for this member.</p>
                <?php endif; ?>

            </div>

        </div>
        
    </div>

  </main>

  <!-- SCRIPT -->
  <script src="<?php echo URLROOT; ?>/assets/js/trainer-script.js?v=<?php echo time(); ?>"></script>
  <script>
        document.addEventListener('DOMContentLoaded', () => {

            function getUrlParameter(name) {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(name);
            }

            const memberId = getUrlParameter('id');

            if (memberId) {
                document.getElementById('userDetailsLink').href = `<?php echo URLROOT; ?>/trainer/members/viewMember?id=${memberId}`;
                document.getElementById('attendanceLink').href = `<?php echo URLROOT; ?>/trainer/members/memberAttendance?id=${memberId}`;
                document.getElementById('workoutSchedulesLink').href = `<?php echo URLROOT; ?>/trainer/members/workoutSchedules?id=${memberId}`;
                document.getElementById('supplementRecordsLink').href = `<?php echo URLROOT; ?>/trainer/members/memberSupplements?id=${memberId}`;
            } else {
                alert('No member selected.');
            }

        });
  </script>
</body>
</html>
