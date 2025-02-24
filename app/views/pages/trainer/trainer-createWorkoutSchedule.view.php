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
    <form action="create_schedule.php" method="POST">
        <table id="workout-schedule">
            <thead>
                <tr>
                    <th>Workout ID</th>
                    <th>Workout Name</th>
                    <th>Equipment ID</th>
                    <th>Equipment Name</th>
                    <th>Sets</th>
                    <th>Reps</th>
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
                </tr>
                <!-- More rows can be added dynamically -->
            </tbody>
        </table>
        <button type="button" id="add-row">Add Row</button>
        <button type="submit">Save Schedule</button>
    </form>
</div>

    </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/trainer-script.js?v=<?php echo time();?>"></script>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
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
            workoutSelects.forEach(select => {
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
            });
        })
        .catch(error => console.error('Error fetching workout data:', error));

    // Add row functionality
    document.getElementById('add-row').addEventListener('click', function () {
        const tbody = document.querySelector('#workout-schedule tbody');
        const newRow = tbody.rows[0].cloneNode(true); // Clone the first row

        // Reset values in the new row
        newRow.querySelectorAll('input').forEach(input => input.value = '');
        newRow.querySelector('.workout-name-select').value = ''; // Clear the dropdown
        tbody.appendChild(newRow); // Append the new row
    });
});

    </script>
  </body>
</html>
