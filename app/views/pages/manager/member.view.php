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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/admin-style.css?v=<?php echo time(); ?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title><?php echo APP_NAME; ?></title>
</head>

<body>
    <section class="sidebar">
        <?php require APPROOT . '/views/components/manager_sidebar.view.php' ?>
    </section>
    <!-- PHP Alerts for Success/Error Messages -->
    <?php
      if (isset($_SESSION['success'])) {
          echo "<script>alert('" . $_SESSION['success'] . "');</script>";
          unset($_SESSION['success']); // Clear the message after showing it
      }

      if (isset($_SESSION['error'])) {
          echo "<script>alert('" . $_SESSION['error'] . "');</script>";
          unset($_SESSION['error']); // Clear the message after showing it
      }
    ?>

    

    <main>
      <div class="title">
        
        <h1>Members</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>

      </div>

      <div class="table-container">

          <div class="filters">
            <button class="filter active">All Users</button>
            <button class="filter">Active Users</button>
            <button class="filter">Inactive Users</button>
          </div>

          <div class="user-table-header">
            <input type="text" placeholder="Search" class="search-input">
            <button class="add-user-btn" onclick="window.location.href='<?php echo URLROOT; ?>/manager/member_create'">+ Add Member</button>
          </div>

          <div class="user-table-wrapper">
            <table class='user-table'>
              <thead>
                  <tr>
                      <th>Member Id</th>
                      <th>Profile Picture</th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>NIC Number</th>
                      <th>Gender</th>
                      <th>Date of Birth</th>
                      <th>Age</th>
                      <th>Height (m)</th>
                      <th>Weight (kg)</th>
                      <th>Home Address</th>
                      <th>Email Address</th>
                      <th>Contact Number</th>
                  </tr>
              </thead>
              <tbody>
                <?php if (!empty($data['members'])): ?>
                  <?php foreach ($data['members'] as $member) : ?>
                    <tr onclick="window.location.href='<?php echo URLROOT; ?>/admin/members/viewMember?id=<?php echo $member->member_id; ?>';" style="cursor: pointer;">
                        <td><?php echo $member->member_id; ?></td>
                        <td>
                          <img src="<?php echo URLROOT; ?>/assets/images/Member/<?php echo !empty($member->image) ? $member->image : 'default-placeholder.jpg'; ?>" alt="Member Picture" class="user-image">
                        </td>
                        <td><?php echo $member->first_name; ?></td>
                        <td><?php echo $member->last_name; ?></td>
                        <td><?php echo $member->NIC_no; ?></td>
                        <td><?php echo $member->gender; ?></td>
                        <td><?php echo $member->date_of_birth; ?></td>
                        <td><?php echo calculateAge($member->date_of_birth); ?></td>
                        <td><?php echo $member->height; ?></td>
                        <td><?php echo $member->weight; ?></td>
                        <td><?php echo $member->home_address; ?></td>
                        <td><?php echo $member->email_address; ?></td>
                        <td><?php echo $member->contact_number; ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="13" style="text-align: center;">No members available</td>
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
</body>

</html>