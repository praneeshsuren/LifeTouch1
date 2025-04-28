<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/trainer-style.css?v=<?php echo time();?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/sidebar-greeting.css?v=<?php echo time();?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title><?php echo APP_NAME; ?></title>
</head>
<body>
    <section class="sidebar">
        <?php require APPROOT . '/views/components/trainer-sidebar.view.php'; ?>
    </section>

    <main>
        <div class="title">
            <h1>Workout Details</h1>
            <div class="greeting">
                <?php require APPROOT . '/views/components/user-greeting.view.php'; ?>
            </div>
        </div>

        <div class="view-workout-container">
    <?php if ($workout) : ?>
        <div class="workout-detail-card" id="workoutDetailCard">
            <h2>Workout: 
                <span id="workoutNameDisplay"><?php echo $workout->workout_name; ?></span>
                <input type="text" id="workoutNameInput" class="hidden" value="<?php echo $workout->workout_name; ?>" />
            </h2>

            <div class="equipment-image">
                <img src="<?php echo URLROOT . '/assets/images/Equipment/' . $workout->image; ?>" alt="Workout Image" />
            </div>

            <p>
                <strong>Equipment:</strong>
                <span id="workoutEquipmentDisplay"><?php echo $workout->equipment_name; ?></span>
                <input type="text" id="workoutEquipmentInput" class="hidden" value="<?php echo $workout->equipment_name; ?>" />
            </p>

            <p>
                <strong>Description:</strong>
                <span id="workoutDescriptionDisplay"><?php echo $workout->workout_description; ?></span>
                <textarea id="workoutDescriptionInput" class="hidden"><?php echo $workout->workout_description; ?></textarea>
            </p>
            
            <!-- Edit, Save, Cancel, and Delete buttons -->
            <div class="action-buttons">
                <a href="#" id="deleteBtn" data-id="<?php echo $workout->workout_id; ?>" class="btn delete-btn">Delete</a>
            </div>
        </div>
    <?php else : ?>
        <p>Workout not found.</p>
    <?php endif; ?>
</div>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
    const deleteBtn = document.getElementById('deleteBtn');

    deleteBtn.addEventListener('click', function (e) {
        e.preventDefault(); // important to prevent default <a> behavior
        const workoutId = deleteBtn.getAttribute('data-id');
        if (confirm("Are you sure you want to delete this workout?")) {
            fetch(`<?php echo URLROOT; ?>/workout/deleteWorkout?id=${workoutId}`, {
                method: 'POST',
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Workout deleted successfully.");
                    window.location.href = `<?php echo URLROOT; ?>/trainer/workouts`;
                } else {
                    alert("Failed to delete workout.");
                }
            })
            .catch(error => {
                console.error(error);
                alert("Error deleting workout: " + error);
            });
        }
    });
});


    </script>
</body>
</html>
