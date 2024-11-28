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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/trainer-style.css?v=<?php echo time();?>" />
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
        <?php require APPROOT.'/views/components/trainer-sidebar.view.php' ?>
    </section>

    <main>
      <div class="title">
        
        <h1>Members</h1>
        <div class="greeting">
            <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>

      </div>

      <div class="retrieve-users">

        <div class="searchBar">
          <input 
            type="text" 
            id="memberSearch" 
            placeholder="Search by Name, Member ID, or NIC..." 
            onkeyup="filterTable()" 
          />
        </div>

        <div class="table-container">
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
                    <th>BMI (kg/m2)</th>
                    <th>Home Address</th>
                    <th>Email Address</th>
                    <th>Contact Number</th>
                </tr>
            </thead>
            <tbody>
              <?php if (!empty($data['members'])): ?>
                <?php foreach ($data['members'] as $member) : ?>
                  <tr onclick="window.location.href='<?php echo URLROOT; ?>/trainer/members/viewMember?id=<?php echo $member->member_id; ?>';" style="cursor: pointer;">
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
                      <td>
                          <?php 
                              if ($member->height > 0) {
                                  echo round($member->weight / ($member->height * $member->height), 2);
                              } else {
                                  echo 'N/A';
                              }
                          ?>
                      </td>
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

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/trainer-script.js?v=<?php echo time();?>"></script>

    <script>
      function filterTable() {
        // Get the input field and its value
        const input = document.getElementById("memberSearch");
        const filter = input.value.toLowerCase(); // Convert input to lowercase for case-insensitive matching
        const table = document.querySelector(".user-table tbody"); // Get the table body
        const rows = table.getElementsByTagName("tr"); // Get all rows in the table body

        // Loop through all table rows and hide those that don't match the search query
        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName("td"); // Get all cells in the current row
            let match = false;

            // Check each cell in the row for a match
            for (let j = 0; j < cells.length; j++) {
                if (cells[j]) {
                    if (cells[j].textContent.toLowerCase().includes(filter)) {
                        match = true; // Found a match
                        break;
                    }
                }
            }

            // Show or hide the row based on whether there was a match
            rows[i].style.display = match ? "" : "none";
        }
    }
    </script>

  </body>
</html>