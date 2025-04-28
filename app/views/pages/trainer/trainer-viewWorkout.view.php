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
                <button id="editBtn" class="btn">Edit</button>
                <button id="saveBtn" class="btn hidden">Save</button>
                <button id="cancelBtn" class="btn hidden">Cancel</button>
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
    const editBtn = document.getElementById('editBtn');
    const saveBtn = document.getElementById('saveBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const deleteBtn = document.getElementById('deleteBtn');
    const workoutName = document.getElementById('workoutName');
    const workoutDescription = document.getElementById('workoutDescription');
    const workoutEquipment = document.getElementById('workoutEquipment');
    const workoutStatus = document.getElementById('workoutStatus');
    const workoutId = new URLSearchParams(window.location.search).get('id');  // Get workout ID from URL

    // Store original values in data-original attributes to revert if needed
    workoutName.setAttribute('data-original', workoutName.value);
    workoutDescription.setAttribute('data-original', workoutDescription.value);
    workoutEquipment.setAttribute('data-original', workoutEquipment.value);
    workoutStatus.setAttribute('data-original', workoutStatus.value);

    // Show edit mode and hide edit button
    editBtn.addEventListener('click', function () {
        // Show Save/Cancel buttons, hide Edit/Delete buttons
        saveBtn.classList.remove('hidden');
        cancelBtn.classList.remove('hidden');
        deleteBtn.classList.add('hidden');
        editBtn.classList.add('hidden');

        // Make fields editable
        workoutName.removeAttribute('readonly');
        workoutDescription.removeAttribute('readonly');
        workoutEquipment.removeAttribute('readonly');
        workoutStatus.removeAttribute('readonly');
    });

    // Cancel edit
    cancelBtn.addEventListener('click', function () {
        // Hide Save/Cancel buttons, show Edit/Delete buttons
        saveBtn.classList.add('hidden');
        cancelBtn.classList.add('hidden');
        deleteBtn.classList.remove('hidden');
        editBtn.classList.remove('hidden');

        // Revert to original values
        workoutName.value = workoutName.getAttribute('data-original');
        workoutDescription.value = workoutDescription.getAttribute('data-original');
        workoutEquipment.value = workoutEquipment.getAttribute('data-original');
        workoutStatus.value = workoutStatus.getAttribute('data-original');

        // Make fields readonly again
        workoutName.setAttribute('readonly', true);
        workoutDescription.setAttribute('readonly', true);
        workoutEquipment.setAttribute('readonly', true);
        workoutStatus.setAttribute('readonly', true);
    });

    // Save updated workout
    saveBtn.addEventListener('click', function () {
        const updatedWorkout = {
            id: workoutId,
            name: workoutName.value.trim(),
            description: workoutDescription.value.trim(),
            equipment: workoutEquipment.value.trim(),
            status: workoutStatus.value.trim()
        };

        // Send updated data to backend via fetch
        fetch(`<?php echo URLROOT; ?>/workout/updateWorkout`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(updatedWorkout)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Workout updated successfully.");
                // Optionally, reload or update UI
            } else {
                alert("Failed to update workout.");
            }
        })
        .catch(error => alert("Error updating workout: " + error));
    });

    // Delete workout
    deleteBtn.addEventListener('click', function () {
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
            .catch(error => alert("Error deleting workout: " + error));
        }
    });
});

    </script>
</body>
</html>
