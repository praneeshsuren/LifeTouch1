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

    <!-- STYLES -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/trainer-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/sidebar-greeting.css?v=<?php echo time(); ?>">

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
                    
                    <strong>Workout:</strong> 
                    <span id="workoutNameDisplay"><?php echo htmlspecialchars($workout->workout_name); ?></span>
                    <input type="text" id="workoutNameInput" name="workout_name" class="hidden" value="<?php echo htmlspecialchars($workout->workout_name); ?>" />

                    <div class="equipment-image">
                        <img src="<?php echo URLROOT . '/assets/images/Equipment/' . $workout->image; ?>" alt="Workout Image" />
                    </div>
                    <input type="hidden" id="equipmentIdInput" value="<?php echo htmlspecialchars($workout->equipment_id); ?>" />

                    <strong>Equipment:</strong>
                    <span><?php echo htmlspecialchars($workout->equipment_name); ?></span> <!-- Not editable -->
                    <br />
                    <strong>Description:</strong>
                    <span id="workoutDescriptionDisplay"><?php echo htmlspecialchars($workout->workout_description); ?></span>
                    <textarea id="workoutDescriptionInput" name="workout_description" class="hidden"><?php echo htmlspecialchars($workout->workout_description); ?></textarea>

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

        const workoutNameDisplay = document.getElementById('workoutNameDisplay');
        const workoutNameInput = document.getElementById('workoutNameInput');
        const workoutDescriptionDisplay = document.getElementById('workoutDescriptionDisplay');
        const workoutDescriptionInput = document.getElementById('workoutDescriptionInput');

        const workoutId = new URLSearchParams(window.location.search).get('id');

        let originalName = workoutNameInput.value;
        let originalDescription = workoutDescriptionInput.value;

        editBtn.addEventListener('click', function () {
            editBtn.classList.add('hidden');
            deleteBtn.classList.add('hidden');
            saveBtn.classList.remove('hidden');
            cancelBtn.classList.remove('hidden');

            workoutNameDisplay.classList.add('hidden');
            workoutDescriptionDisplay.classList.add('hidden');
            workoutNameInput.classList.remove('hidden');
            workoutDescriptionInput.classList.remove('hidden');
        });

        cancelBtn.addEventListener('click', function () {
            saveBtn.classList.add('hidden');
            cancelBtn.classList.add('hidden');
            editBtn.classList.remove('hidden');
            deleteBtn.classList.remove('hidden');

            workoutNameDisplay.classList.remove('hidden');
            workoutDescriptionDisplay.classList.remove('hidden');
            workoutNameInput.classList.add('hidden');
            workoutDescriptionInput.classList.add('hidden');

            workoutNameInput.value = originalName;
            workoutDescriptionInput.value = originalDescription;
        });

        saveBtn.addEventListener('click', function () {
            const updatedWorkout = {
                id: workoutId,
                name: workoutNameInput.value.trim(),
                description: workoutDescriptionInput.value.trim()
            };

            fetch(`<?php echo URLROOT; ?>/workout/updateWorkout?id=${deleteBtn.dataset.id}`, {
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
                    workoutNameDisplay.textContent = updatedWorkout.name;
                    workoutDescriptionDisplay.textContent = updatedWorkout.description;

                    originalName = updatedWorkout.name;
                    originalDescription = updatedWorkout.description;

                    cancelBtn.click();
                } else {
                    alert("Failed to update workout.");
                }
            })
            .catch(error => {
                alert("Error updating workout: " + error);
            });
        });

        deleteBtn.addEventListener('click', function () {
            const confirmDelete = confirm("Are you sure you want to delete this workout?");
            if (!confirmDelete) return;

            fetch(`<?php echo URLROOT; ?>/workout/deleteWorkout?id=${deleteBtn.dataset.id}`, {
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
                alert("Error deleting workout: " + error);
            });
        });
    });
    </script>
</body>
</html>
