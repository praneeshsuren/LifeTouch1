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
    <!-- STYLESHEET -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/trainer-style.css?v=<?php echo time();?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/sidebar-greeting.css?v=<?php echo time();?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- CHART.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <title><?php echo APP_NAME; ?></title>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  </head>
  <body>

  <?php
      if (isset($_SESSION['success'])) {
          echo "<script>alert('" . $_SESSION['success'] . "');</script>";
          unset($_SESSION['success']);
      }

      if (isset($_SESSION['error'])) {
          echo "<script>alert('" . $_SESSION['error'] . "');</script>";
          unset($_SESSION['error']);
      }
  ?>

    <section class="sidebar">
      <?php require APPROOT.'/views/components/trainer-sidebar.view.php' ?>
    </section>

    <main>
      <div class="title">
        <h1>Create Workout Schedule</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>
      </div>

      <div class="workout-schedule-container">
        <form id="workoutScheduleForm" method="POST">
            <table id="workout-schedule">
                <thead>
                    <tr>
                        <th>Workout ID</th>
                        <th>Workout Name</th>
                        <th>Equipment ID</th>
                        <th>Equipment Name</th>
                        <th>Sets</th>
                        <th>Reps</th>
                        <th>Actions</th> <!-- New Actions column -->
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="workout-id-cell"><input type="text" readonly></td>
                        <td class="workout-name-cell" data-id="">
                            <select class="workout-name-select" required>
                                <option value="" disabled selected>Select Workout</option>
                                <!-- Workouts will be dynamically loaded here -->
                            </select>
                        </td>
                        <td class="equipment-id-cell"><input type="text" readonly></td>
                        <td class="equipment-name-cell"><input type="text" readonly></td>
                        <td><input type="number" name="sets[]" min="1" required></td>
                        <td><input type="number" name="reps[]" min="1" required></td>
                        <td><button type="button" class="delete-row-btn">Delete</button></td> <!-- Delete button -->
                    </tr>
                    <!-- More rows can be added dynamically -->
                </tbody>
            </table>
            <button type="button" id="add-row">Add Row</button>

            <div>
                <label for="weight_beginning">Weight (kg):</label>
                <input type="number" id="weight_beginning" name="weight_beginning" min="1" required>
            </div>
            
            <div>
                <label for="chest_measurement_beginning">Chest Measurement (cm):</label>
                <input type="number" id="chest_measurement_beginning" name="chest_measurement_beginning" min="1" required>
            </div>

            <div>
                <label for="bicep_measurement_beginning">Bicep Measurement (cm):</label>
                <input type="number" id="bicep_measurement_beginning" name="bicep_measurement_beginning" min="1" required>
            </div>

            <div>
                <label for="thigh_measurement_beginning">Thigh Measurement (cm):</label>
                <input type="number" id="thigh_measurement_beginning" name="thigh_measurement_beginning" min="1" required>
            </div>

            <button type="button" id="save-schedule">Save Schedule</button>

        </form>
    </div>

    </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/trainer-script.js?v=<?php echo time();?>"></script>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        // Function to get URL parameter by name
        function getUrlParameter(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }

        // Get the 'id' parameter (member_id) from the URL
        const memberId = getUrlParameter('id');
        
        if (!memberId) {
            alert('No member ID found in the URL.');
            return;
        }

        // Fetch workout data via API
        fetch('<?php echo URLROOT; ?>/workout/api') // Adjust the URL to your API endpoint
            .then(response => response.json())
            .then(data => {
                const workoutSelects = document.querySelectorAll('.workout-name-select');
                
                // Populate the dropdowns with workout data
                workoutSelects.forEach(select => {
                    data.forEach(workout => {
                        const option = document.createElement('option');
                        option.value = workout.workout_id;
                        option.textContent = workout.workout_name;
                        select.appendChild(option);
                    });
                });

                // Add event listener to each workout select
                function addWorkoutSelectEventListener(select) {
                    select.addEventListener('change', function () {
                        const selectedOption = this.options[this.selectedIndex];
                        const workoutId = selectedOption.value;

                        // Find the corresponding workout data
                        const selectedWorkout = data.find(workout => workout.workout_id == workoutId);

                        // Auto-populate the workout_id, equipment_id, and equipment_name fields
                        const row = this.closest('tr');
                        row.querySelector('.workout-id-cell input').value = selectedWorkout ? selectedWorkout.workout_id : '';
                        row.querySelector('.equipment-id-cell input').value = selectedWorkout ? selectedWorkout.equipment_id : '';
                        row.querySelector('.equipment-name-cell input').value = selectedWorkout ? selectedWorkout.equipment_name : '';
                    });
                }

                workoutSelects.forEach(select => {
                    addWorkoutSelectEventListener(select); // Add event listener to each select
                });

                // Add row functionality
                document.getElementById('add-row').addEventListener('click', function () {
                    const tbody = document.querySelector('#workout-schedule tbody');
                    const newRow = tbody.rows[0].cloneNode(true); // Clone the first row

                    // Reset values in the new row
                    newRow.querySelectorAll('input').forEach(input => input.value = '');
                    newRow.querySelector('.workout-name-select').value = ''; // Clear the dropdown

                    // Re-add event listener to the new row's workout select
                    const newSelect = newRow.querySelector('.workout-name-select');
                    addWorkoutSelectEventListener(newSelect);

                    // Add the delete button functionality for the new row
                    const newDeleteButton = newRow.querySelector('.delete-row-btn');
                    newDeleteButton.addEventListener('click', function () {
                        const rows = tbody.querySelectorAll('tr');
                        if (rows.length > 1) {
                            newRow.remove(); // Remove the row when delete button is clicked
                        } else {
                            alert('At least one row is required.');
                        }
                    });

                    tbody.appendChild(newRow); // Append the new row
                });

                // Add delete button event listeners for existing rows
                document.querySelectorAll('.delete-row-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const row = this.closest('tr');
                        const rows = row.closest('tbody').querySelectorAll('tr');
                        if (rows.length > 1) {
                            row.remove(); // Remove the row when delete button is clicked
                        } else {
                            alert('At least one row is required.');
                        }
                    });
                });
            })
            .catch(error => console.error('Error fetching workout data:', error));

        // Save schedule functionality
        document.getElementById('save-schedule').addEventListener('click', function (event) {
            event.preventDefault(); // Prevent the default form submission

            // Collect workout details from the table
            const workoutDetails = [];
            const rows = document.querySelectorAll('#workout-schedule tbody tr');
            rows.forEach(row => {
                const workoutId = row.querySelector('.workout-id-cell input').value;
                const sets = row.querySelector('input[name="sets[]"]').value;
                const reps = row.querySelector('input[name="reps[]"]').value;
                workoutDetails.push({ workout_id: workoutId, sets: sets, reps: reps });
            });

            if (workoutDetails.length === 0) {
                alert('Please add at least one workout.');
                return;
            }

            const weightBeginning = document.getElementById('weight_beginning').value;
            const chestMeasurementBeginning = document.getElementById('chest_measurement_beginning').value;
            const bicepMeasurementBeginning = document.getElementById('bicep_measurement_beginning').value;
            const thighMeasurementBeginning = document.getElementById('thigh_measurement_beginning').value;

            // Prepare the data for submission
            const formData = {
                member_id: memberId,
                workout_details: workoutDetails,
                weight_beginning: weightBeginning,
                chest_measurement_beginning: chestMeasurementBeginning,
                bicep_measurement_beginning: bicepMeasurementBeginning,
                thigh_measurement_beginning: thighMeasurementBeginning
            };

            // Send the data to the server via fetch (AJAX)
            fetch('<?php echo URLROOT; ?>/WorkoutSchedule/createSchedule', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())  // Assuming the response is JSON
            .then(data => {
                if (data.success) {
                    alert('Workout schedule created successfully!');
                    window.location.href = '<?php echo URLROOT; ?>/trainer/members/workoutSchedules?id=' + memberId; // Redirect after success
                } else {
                    alert('Error creating workout schedule: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error saving workout schedule:', error);
                alert('An error occurred while saving the workout schedule.');
            });
        });
      });

    </script>

  </body>
</html>
