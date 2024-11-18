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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/receptionist-style.css?v=<?php echo time();?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>

    <section class="sidebar">
        <?php require APPROOT.'/views/components/receptionist-sidebar.view.php' ?>
    </section>

    <?php if (isset($_SESSION['success'])): ?>
      <script>
          alert("<?php echo $_SESSION['success']; ?>");
      </script>
      <?php unset($_SESSION['success']); // Clear success message after showing it ?>
    <?php endif; ?>

    <main>
      <div class="title">
        
        <h1>Members</h1>
        <div class="greeting">
          <span class="bell-container"><i class="ph ph-bell notification"></i></span>
          <h2>Hi, John!</h2>
        </div>

      </div>

      <div class="table-container">
        <table class='trainer-table'>
          <thead>
              <tr>
                  <th>Member Id</th>
                  <th>Profile Picture</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Gender</th>
                  <th>Date of Birth</th>
                  <th>Age</th>
                  <th>Home Address</th>
                  <th>Email Address</th>
                  <th>Contact Number</th>
              </tr>
          </thead>
          <tbody>
            <?php if (!empty($data['trainers'])): ?>
              <?php foreach ($data['trainers'] as $trainer) : ?>
                <tr>
                    <td><?php echo $trainer->trainer_id; ?></td>
                    <td><img src="<?php echo URLROOT; ?>/assets/images/image.png" alt="Picture"></td>
                    <td><?php echo $trainer->first_name; ?></td>
                    <td><?php echo $trainer->last_name; ?></td>
                    <td><?php echo $trainer->gender; ?></td>
                    <td><?php echo $trainer->date_of_birth; ?></td>
                    <td><?php echo calculateAge($trainer->date_of_birth); ?></td>
                    <td><?php echo $trainer->home_address; ?></td>
                    <td><?php echo $trainer->email_address; ?></td>
                    <td><?php echo $trainer->contact_number; ?></td>
                </tr>
              <?php endforeach; ?>
              <?php else: ?>
                <tr>
                    <td colspan="10" style="text-align: center;">No trainers available</td>
                </tr>
              <?php endif; ?>
          </tbody>
        </table>
      </div>

      <div class="add-trainer">
        <a href="<?php echo URLROOT; ?>/receptionist/trainers/createTrainer">
          <button class="add-trainer-btn">+ Add Member</button>
        </a>
      </div>

      </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/receptionist-script.js?v=<?php echo time();?>"></script>

  </body>
</html>
