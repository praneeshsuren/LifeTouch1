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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/member-style.css?v=<?php echo time();?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/sidebar-greeting.css?v=<?php echo time();?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title><?php echo APP_NAME;; ?></title>
  </head>
  <body>

    <section class="sidebar">
    <?php require APPROOT.'/views/components/member-sidebar.view.php' ?>
    </section>
    
    <main>

      <div class="title">
        <h1>Supplements</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>
      </div>

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
    <script src="<?php echo URLROOT; ?>/assets/js/member/member-script.js?v=<?php echo time();?>"></script>
    
  </body>
</html>

