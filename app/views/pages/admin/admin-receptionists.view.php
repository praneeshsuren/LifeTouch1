<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- STYLESHEET -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/admin-style.css?v=<?php echo time();?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>

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

    <section class="sidebar">
        <?php require APPROOT.'/views/components/admin-sidebar.view.php' ?>
    </section>

    <main>
      <div class="title">
        
        <h1>Receptionists</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>

      </div>

      <div class="table-container">
        <table class='user-table'>
          <thead>
              <tr>
                  <th>Receptionist Id</th>
                  <th>Profile Picture</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>NIC Number</th>
                  <th>Gender</th>
                  <th>Date of Birth</th>
                  <th>Age</th>
                  <th>Home Address</th>
                  <th>Email Address</th>
                  <th>Contact Number</th>
              </tr>
          </thead>
          <tbody>
            <?php if (!empty($data['receptionists'])): ?>
              <?php foreach ($data['receptionists'] as $receptionist) : ?>
                <tr onclick="window.location.href='<?php echo URLROOT; ?>/admin/receptionists/viewReceptionist?id=<?php echo $receptionist->receptionist_id; ?>';" style="cursor: pointer;">
                    <td><?php echo $receptionist->receptionist_id; ?></td>
                    <td>
                      <img src="<?php echo URLROOT; ?>/assets/images/receptionist/<?php echo !empty($receptionist->image) ? $receptionist->image : 'default-placeholder.jpg'; ?>" alt="receptionist Picture" class="user-image">
                    </td>
                    <td><?php echo $receptionist->first_name; ?></td>
                    <td><?php echo $receptionist->last_name; ?></td>
                    <td><?php echo $receptionist->NIC_no; ?></td>
                    <td><?php echo $receptionist->gender; ?></td>
                    <td><?php echo $receptionist->date_of_birth; ?></td>
                    <td><?php echo calculateAge($receptionist->date_of_birth); ?></td>
                    <td><?php echo $receptionist->home_address; ?></td>
                    <td><?php echo $receptionist->email_address; ?></td>
                    <td><?php echo $receptionist->contact_number; ?></td>
                </tr>
              <?php endforeach; ?>
              <?php else: ?>
                <tr>
                    <td colspan="11" style="text-align: center;">No Receptionists available</td>
                </tr>
              <?php endif; ?>
          </tbody>
        </table>
      </div>

        <div class="add-user">
          <a href="<?php echo URLROOT; ?>/admin/receptionists/createReceptionist">
            <button class="add-user-btn">+ Add Receptionist</button>
          </a>
        </div>
      
      </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/admin-script.js?v=<?php echo time();?>"></script>

  </body>
</html>
