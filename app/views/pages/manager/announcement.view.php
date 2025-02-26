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
      <h1>Create Announcement</h1>
      <div class="greeting">
        <?php require APPROOT . '/views/components/user-greeting.view.php' ?>
      </div>
    </div>

    <div class="create-announcement">
      <form action="<?php echo URLROOT; ?>/announcement/createAnnouncement" method="post" id="announcementForm">

        <?php if (!empty($errors)): ?>
          <div class="alert">
            <?= implode("<br>", $errors); ?>
          </div>
        <?php endif; ?>

        <div class="input-box">
          <label for="subject">Subject</label>
          <input type="text" id="subject" name="subject" placeholder="Announcement Subject" required />
        </div>

        <div class="input-box">
          <label for="description">Description</label>
          <textarea id="description" name="description" placeholder="Write your announcement here..." required></textarea>
        </div>

        <button type="submit" id="publishButton">Publish</button>

      </form>
    </div>
  </main>
  <script>
    document.getElementById('announcementForm').addEventListener('submit', function(event) {
      const confirmation = confirm("Are you sure you want to publish this Announcement?");
      if (!confirmation) {
        event.preventDefault(); // Prevent form submission if user cancels
      }
    });
  </script>
  <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time(); ?>"></script>
</body>

</html>