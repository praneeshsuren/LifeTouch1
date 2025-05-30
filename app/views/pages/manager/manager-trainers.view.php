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
        <?php require APPROOT.'/views/components/manager_sidebar.view.php' ?>
    </section>

    <main>
      <div class="title">
        
        <h1>Trainers</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>

      </div>

      <div class="table-container">
          <div class="filters">
            <button class="filter active" name="all">All Users</button>
            <button class="filter" name="active">Active Users</button>
            <button class="filter" name="inactive">Inactive Users</button>
          </div>

          <div class="user-table-header">
            <input type="text" placeholder="Search" class="search-input">
            <button class="add-user-btn" onclick="window.location.href='<?php echo URLROOT; ?>/manager/trainers/createTrainer'">+ Add Trainer</button>
          </div>

          <div class="user-table-wrapper">
            <table class='user-table'>
              <thead>
                  <tr>
                      <th>Trainer Id</th>
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
                      <th>Status</th>
                  </tr>
              </thead>
              <tbody>
                <?php if (!empty($data['trainers'])): ?>
                  <?php foreach ($data['trainers'] as $trainer) : ?>
                    <tr onclick="window.location.href='<?php echo URLROOT; ?>/manager/trainers/viewTrainer?id=<?php echo $trainer->trainer_id; ?>';" style="cursor: pointer;">
                        <td><?php echo $trainer->trainer_id; ?></td>
                        <td>
                          <img src="<?php echo URLROOT; ?>/assets/images/Trainer/<?php echo !empty($trainer->image) ? $trainer->image : 'default-placeholder.jpg'; ?>" alt="Trainer Picture" class="user-image">
                        </td>
                        <td><?php echo $trainer->first_name; ?></td>
                        <td><?php echo $trainer->last_name; ?></td>
                        <td><?php echo $trainer->NIC_no; ?></td>
                        <td><?php echo $trainer->gender; ?></td>
                        <td><?php echo $trainer->date_of_birth; ?></td>
                        <td><?php echo calculateAge($trainer->date_of_birth); ?></td>
                        <td><?php echo $trainer->home_address; ?></td>
                        <td><?php echo $trainer->email_address; ?></td>
                        <td><?php echo $trainer->contact_number; ?></td>
                        <td><?php echo $trainer->status; ?></td>
                    </tr>
                  <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                        <td colspan="11" style="text-align: center;">No trainers available</td>
                    </tr>
                  <?php endif; ?>
              </tbody>
            </table>
          </div>

      </div>
      
      </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time();?>"></script>
    <script>

      document.addEventListener('DOMContentLoaded', () => {
        // Get references to the DOM elements
        const searchInput = document.querySelector('.search-input');
        const filterButtons = document.querySelectorAll('.filter');
        const userRows = document.querySelectorAll('.user-table tbody tr');
        
        // Function to filter rows based on active, inactive, or all
        function filterRows(status) {
          userRows.forEach(row => {
            const statusCell = row.cells[11]; // Assuming the status is in the 13th column (index 12)
            const rowStatus = statusCell.textContent.toLowerCase();

            // Show all rows if "All Users" is selected
            if (status === 'all') {
              row.style.display = '';
            } 
            // Show only active users
            else if (status === 'active' && rowStatus === 'active') {
              row.style.display = '';
            } 
            // Show only inactive users
            else if (status === 'inactive' && rowStatus === 'inactive') {
              row.style.display = '';
            } 
            // Hide the row if it doesn't match the filter
            else {
              row.style.display = 'none';
            }
          });
        }

        // Add event listeners to filter buttons
        filterButtons.forEach(button => {
          button.addEventListener('click', () => {
            // Set the active filter button
            filterButtons.forEach(b => b.classList.remove('active'));
            button.classList.add('active');

            // Get the filter type (All, Active, or Inactive)
            const filterType = button.getAttribute('name'); // Use the 'name' attribute
            filterRows(filterType);
          });
        });

        // Implement search functionality
        searchInput.addEventListener('input', () => {
          const searchText = searchInput.value.toLowerCase();
          
          userRows.forEach(row => {
            const rowText = Array.from(row.cells).map(cell => cell.textContent.toLowerCase()).join(' ');
            if (rowText.includes(searchText)) {
              row.style.display = '';
            } else {
              row.style.display = 'none';
            }
          });
        });

        // Initially filter to show all users
        filterRows('all');
      });

    </script>

  </body>
</html>
