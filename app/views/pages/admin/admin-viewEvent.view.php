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
                        <?php if (!empty($errors['name'])): ?>
                            <small class="error"><?php echo $errors['name']; ?></small>
                        <?php endif; ?>
                        <div class="event-status">
                            <select id="eventStatus" name="status" disabled>
                                <option value="Ongoing" <?php echo ($event->status == 'Ongoing') ? 'selected' : ''; ?>>Ongoing</option>
                                <option value="Completed" <?php echo ($event->status == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                            </select>
                        </div>
                    </div>
                    <input name="event_id" value="<?php echo $event->event_id; ?>" style="display: none;" disabled hidden/>
                    <div class="event-details-grid">

                        <div class="detail-group">
                            <label for="event_date">Date</label>
                            <input type="date" id="event_date" name="event_date" value="<?php echo $event->event_date; ?>" disabled>
                            <?php if (!empty($errors['event_date'])): ?>
                                <small class="error"><?php echo $errors['event_date']; ?></small>
                            <?php endif; ?>
                        </div>
                        
                        <div class="detail-group">
                            <label for="start_time">Time</label>
                            <input type="time" id="start_time" name="start_time" value="<?php echo $event->start_time; ?>" disabled>
                            <?php if (!empty($errors['start_time'])): ?>
                                <small class="error"><?php echo $errors['start_time']; ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="detail-group">
                            <label for="location">Location</label>
                            <input type="text" id="location" name="location" value="<?php echo $event->location; ?>" placeholder="Enter venue or address" disabled>
                            <?php if (!empty($errors['location'])): ?>
                                <small class="error"><?php echo $errors['location']; ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="detail-group">
                            <label for="duration">Duration (hrs)</label>
                            <input type="number" id="duration" name="duration" min="0.5" step="0.5" value="<?php echo $event->duration; ?>" disabled>
                            <?php if (!empty($errors['duration'])): ?>
                                <small class="error"><?php echo $errors['duration']; ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="detail-group">
                            <label for="price">Price</label>
                            <input type="number" id="price" name="price" min="0" step="0.01" value="<?php echo $event->price; ?>" placeholder="0.00" disabled>
                            <?php if (!empty($errors['price'])): ?>
                                <small class="error"><?php echo $errors['price']; ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="detail-group checkbox-group">
                            <input type="checkbox" id="free_for_members" name="free_for_members" <?php echo ($event->free_for_members) ? 'checked' : ''; ?> disabled>
                            <label for="free_for_members">Free for Members</label>
                        </div>
                    </div>

                    <div class="event-description">
                        <h2>Description</h2>
                        <textarea name="description" disabled><?php echo $event->description; ?></textarea>
                        <?php if (!empty($errors['description'])): ?>
                            <small class="error"><?php echo $errors['description']; ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="event-actions">
                        <div class="view-actions">
                            <button type="button" class="btn edit-btn" id="editButton">Edit</button>
                            <button type="button" class="btn delete-btn" id="deleteButton">Delete</button>
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
        const deleteButton = document.getElementById('deleteButton');

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
        
        deleteButton.addEventListener('click', function() {
            const deleteUrl = this.getAttribute('data-delete-url');
            if (confirm('Are you sure you want to delete this event? This action cannot be undone.')) {
                window.location.href = "<?php echo URLROOT; ?>/event/deleteEvent?id=<?php echo $data['event']->event_id; ?>";
            }
        });

        form.addEventListener('submit', function(e) {
            let hasErrors = false;

            // Clear existing error messages
            const existingErrors = form.querySelectorAll('.error');
            existingErrors.forEach(error => error.remove());

            inputs.forEach(input => {
                if (!input.disabled) { // Only check enabled fields
                    if (input.name === 'name' || input.name === 'description' || input.name === 'location') {
                        if (input.value.trim() === '') {
                            showError(input, "This field is required.");
                            hasErrors = true;
                        }
                    }
                    if (input.name === 'event_date') {
                        if (input.value.trim() === '') {
                            showError(input, "Event date is required.");
                            hasErrors = true;
                        } else if (new Date(input.value) < new Date().setHours(0,0,0,0)) {
                            showError(input, "Event date must be today or future date.");
                            hasErrors = true;
                        }
                    }
                    if (input.name === 'start_time') {
                        if (input.value.trim() === '') {
                            showError(input, "Start time is required.");
                            hasErrors = true;
                        }
                    }
                    if (input.name === 'duration') {
                        if (input.value.trim() === '' || isNaN(input.value) || Number(input.value) <= 0) {
                            showError(input, "Duration must be a positive number.");
                            hasErrors = true;
                        }
                    }
                    if (input.name === 'price') {
                        if (input.value.trim() === '' || isNaN(input.value) || Number(input.value) < 0) {
                            showError(input, "Ticket price must be a non-negative number.");
                            hasErrors = true;
                        }
                    }
                }
            });

            if (hasErrors) {
                e.preventDefault(); // STOP submitting the form
            }
        });

        // Helper to show error
        function showError(input, message) {
            const error = document.createElement('small');
            error.classList.add('error');
            error.innerText = message;
            input.parentNode.appendChild(error);
        }


    </script>

  </body>
</html>