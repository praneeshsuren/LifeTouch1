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

    <section class="sidebar">
        <?php require APPROOT . '/views/components/admin-sidebar.view.php'; ?>
    </section>
    
    <main>

        <div class="title">
            <h1>Create Event</h1>
            <div class="greeting">
            <?php require APPROOT . '/views/components/user-greeting.view.php'; ?>
            </div>
        </div>
        
        <div class="create-event-container">
            <div class="form-container">
                <form action="<?php echo URLROOT; ?>/event/createEvent" method="POST" class="create-event-form">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Event Name</label>
                            <input type="text" id="name" name="name"  placeholder="Enter event name" >
                            <?php if (!empty($errors['name'])): ?>
                                <small class="error"><?php echo $errors['name']; ?></small>
                            <?php endif; ?>

                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="4" placeholder="Describe your event" ></textarea>
                            <?php if (!empty($errors['description'])): ?>
                                <small class="error"><?php echo $errors['description']; ?></small>
                            <?php endif; ?>

                        </div>

                        <div class="form-group">
                            <label for="event_date">Event Date</label>
                            <input type="date" id="event_date" name="event_date" >
                            <?php if (!empty($errors['event_date'])): ?>
                                <small class="error"><?php echo $errors['event_date']; ?></small>
                            <?php endif; ?>

                        </div>

                        <div class="form-group">
                            <label for="start_time">Start Time</label>
                            <input type="time" id="start_time" name="start_time" >
                            <?php if (!empty($errors['start_time'])): ?>
                                <small class="error"><?php echo $errors['start_time']; ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="duration">Duration (hours)</label>
                            <input type="number" id="duration" name="duration" min="0.5" step="0.5" >
                            <?php if (!empty($errors['duration'])): ?>
                                <small class="error"><?php echo $errors['duration']; ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" id="location" name="location"  placeholder="Enter venue or address" >
                            <?php if (!empty($errors['location'])): ?>
                                <small class="error"><?php echo $errors['location']; ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="price">Ticket Price ($)</label>
                            <input type="number" id="price" name="price" min="0" step="0.01" placeholder="0.00" >
                            <?php if (!empty($errors['price'])): ?>
                                <small class="error"><?php echo $errors['price']; ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group checkbox-group">
                            <input type="checkbox" id="free_for_members" name="free_for_members" value="1" onclick="this.value = this.checked ? 1 : 0">
                            <label for="free_for_members">Free for Members</label>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn submit-btn">Create Event</button>
                        <button type="reset" class="btn reset-btn">Reset</button>
                    </div>
                </form>
            </div>
        </div>
      
    </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/admin-script.js?v=<?php echo time(); ?>"></script>
  </body>
</html>