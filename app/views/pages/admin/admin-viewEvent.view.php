<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- STYLESHEET -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/admin-style.css?v=<?php echo time(); ?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title><?php echo APP_NAME; ?></title>
  </head>

  <body>

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
        <?php require APPROOT . '/views/components/admin-sidebar.view.php'; ?>
    </section>
    
    <main>
        <div class="title">
        <h1>View Event</h1>
            <div class="greeting">
                <?php require APPROOT . '/views/components/user-greeting.view.php'; ?>
            </div>
        </div>

        <div class="event-detail-container">
            <div class="form-container">
                <form id="eventDetailForm" action="<?php echo URLROOT; ?>/event/updateEvent" method="POST">
                    <div class="event-header">
                        <input type="text" id="eventTitle" name="name" value="<?php echo $event->name; ?>" disabled>
                        <div class="event-status">
                            <select id="eventStatus" name="status" disabled>
                                <option value="Ongoing" <?php echo ($event->status == 'Ongoing') ? 'selected' : ''; ?>>Ongoing</option>
                                <option value="Completed" <?php echo ($event->status == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                            </select>
                        </div>
                    </div>
                    <input name="event_id" value="<?php echo $event->event_id; ?>" style="display: none;" disabled/>
                    <div class="event-details-grid">
                        <div class="detail-group">
                            <label for="event_date">Date</label>
                            <input type="date" id="event_date" name="event_date" value="<?php echo $event->event_date; ?>" disabled>
                        </div>
                        <div class="detail-group">
                            <label for="start_time">Time</label>
                            <input type="time" id="start_time" name="start_time" value="<?php echo $event->start_time; ?>" disabled>
                        </div>
                        <div class="detail-group">
                            <label for="location">Location</label>
                            <input type="text" id="location" name="location" value="<?php echo $event->location; ?>" placeholder="Enter venue or address" disabled>
                        </div>
                        <div class="detail-group">
                            <label for="duration">Duration (hrs)</label>
                            <input type="number" id="duration" name="duration" min="0.5" step="0.5" value="<?php echo $event->duration; ?>" disabled>
                        </div>
                        <div class="detail-group">
                            <label for="price">Price</label>
                            <input type="number" id="price" name="price" min="0" step="0.01" value="<?php echo $event->price; ?>" placeholder="0.00" disabled>
                        </div>
                    </div>

                    <div class="event-description">
                        <h2>Description</h2>
                        <textarea name="description" disabled><?php echo $event->description; ?></textarea>
                    </div>

                    <div class="event-actions">
                        <div class="view-actions">
                            <button type="button" class="btn edit-btn" id="editButton">Edit</button>
                            <button type="button" class="btn delete-btn" id="deleteButton" onclick="window.location.href='<?php echo URLROOT; ?>/event/deleteEvent?id=<?php echo $data['event']->event_id; ?>';">Delete</button>
                        </div>
                        <div class="edit-actions" style="display:none;">
                            <button type="submit" class="btn save-btn">Save</button>
                            <button type="button" class="btn cancel-btn" id="cancelButton">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

      
    </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/admin-script.js?v=<?php echo time(); ?>"></script>

    <script>

        const form = document.getElementById('eventDetailForm');
        const inputs = form.querySelectorAll('input, textarea, select');
        const viewActions = document.querySelector('.view-actions');
        const editActions = document.querySelector('.edit-actions');
        const editButton = document.getElementById('editButton');
        const cancelButton = document.getElementById('cancelButton');

        // Store original values for cancel functionality
        const originalValues = {};
        inputs.forEach(input => {
            originalValues[input.name] = input.value;
        });

        editButton.addEventListener('click', function() {
            inputs.forEach(input => input.disabled = false);
            viewActions.style.display = 'none';
            editActions.style.display = 'block';
        });

        cancelButton.addEventListener('click', function() {
            inputs.forEach(input => {
                input.value = originalValues[input.name];
                input.disabled = true;
            });
            viewActions.style.display = 'block';
            editActions.style.display = 'none';
        });

    </script>

  </body>
</html>