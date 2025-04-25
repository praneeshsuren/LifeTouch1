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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/trainer-style.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/sidebar-greeting.css?v=<?php echo time(); ?>" />
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
          <form id="workoutScheduleForm" method="POST" action="<?php echo URLROOT; ?>/WorkoutSchedule/createSchedule">
            
            <input type="hidden" id="member_id" name="member_id" value="" />
            <input type="hidden" name="created_by" value="<?php echo $_SESSION['user_id']; ?>" />

            <table id="workout-schedule">
              <thead>
                  <tr>
                      <th class="row-index">#</th>
                      <th>Workout ID</th>
                      <th>Workout Name</th>
                      <th>Equipment ID</th>
                      <th>Equipment Name</th>
                      <th>Description</th>
                      <th>Sets</th>
                      <th>Reps</th>
                      <th>Actions</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                    <td class="row-index">
                      <span class="row-number-display">1</span>
                      <input type="hidden" name="workout_details[0][row_no]" class="row-number-input" value="1">
                    </td>
                    <td class="workout-id-cell">
                        <input type="text" name="workout_details[0][workout_id]" class="workout-id-input" readonly>
                    </td>
                    <td class="workout-name-cell" data-id="">
                        <select class="workout-name-select" name="workout_details[0][workout_name]" required>
                            <option value="" disabled selected>Select Workout</option>
                        </select>
                    </td>
                    <td class="equipment-id-cell"><input type="text" name="workout_details[0][equipment_id]" readonly></td>
                    <td class="equipment-name-cell"><input type="text" name="workout_details[0][equipment_name]" readonly></td>
                    <td class="description-cell"><input type="text" name="workout_details[0][description]"></td>
                    <td><input type="number" name="workout_details[0][sets]" min="1"></td>
                    <td><input type="number" name="workout_details[0][reps]" min="1"></td>
                    <td><button type="button" class="delete-row-btn">Delete</button></td>
                  </tr>
              </tbody>
            </table>

            <button type="button" id="add-row">Add Row</button>

            <div>
                <label for="weight_beginning">Weight (kg):</label>
                <input type="number" id="weight_beginning" name="weight_beginning" min="1">
            </div>

            <div>
                <label for="chest_measurement_beginning">Chest Measurement (cm):</label>
                <input type="number" id="chest_measurement_beginning" name="chest_measurement_beginning" min="1">
            </div>

            <div>
                <label for="bicep_measurement_beginning">Bicep Measurement (cm):</label>
                <input type="number" id="bicep_measurement_beginning" name="bicep_measurement_beginning" min="1">
            </div>

            <div>
                <label for="thigh_measurement_beginning">Thigh Measurement (cm):</label>
                <input type="number" id="thigh_measurement_beginning" name="thigh_measurement_beginning" min="1">
            </div>

            <button type="submit" id="save-schedule">Save Schedule</button>
        </form>
    </div>
</main>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    let rowIndex = 1;

    function updateRowNumbers() {
      const rows = document.querySelectorAll('#workout-schedule tbody tr');
      rows.forEach((row, index) => {
        const rowNumber = index + 1;
        const span = row.querySelector('.row-number-display');
        const hiddenInput = row.querySelector('.row-number-input');

        span.textContent = rowNumber;
        hiddenInput.value = rowNumber;
      });
    }

    fetch('<?php echo URLROOT; ?>/workout/api')
      .then(response => response.json())
      .then(data => {
        function populateWorkoutSelect(select) {
          select.innerHTML = '<option value="" disabled selected>Select Workout</option>';
          data.forEach(workout => {
            const option = document.createElement('option');
            option.value = workout.workout_id;
            option.textContent = workout.workout_name;
            select.appendChild(option);
          });
        }

        function addSelectChangeListener(select) {
          select.addEventListener('change', function () {
            const selectedWorkout = data.find(w => w.workout_id == this.value);
            const row = this.closest('tr');

            row.querySelector('.workout-id-input').value = selectedWorkout ? selectedWorkout.workout_id : '';
            row.querySelector('.equipment-id-cell input').value = selectedWorkout ? selectedWorkout.equipment_id : '';
            row.querySelector('.equipment-name-cell input').value = selectedWorkout ? selectedWorkout.equipment_name : '';
          });
        }

        const tbody = document.querySelector('#workout-schedule tbody');
        const firstRow = tbody.querySelector('tr');
        populateWorkoutSelect(firstRow.querySelector('.workout-name-select'));
        addSelectChangeListener(firstRow.querySelector('.workout-name-select'));

        document.getElementById('add-row').addEventListener('click', function () {
          const newRow = tbody.rows[0].cloneNode(true);
          const index = rowIndex++;

          newRow.querySelectorAll('input, select').forEach(input => {
            if (!input.classList.contains('row-number-input')) input.value = '';
            const name = input.getAttribute('name');
            if (name) {
              const updatedName = name.replace(/\[\d+\]/, `[${index}]`);
              input.setAttribute('name', updatedName);
            }
          });

          populateWorkoutSelect(newRow.querySelector('.workout-name-select'));
          addSelectChangeListener(newRow.querySelector('.workout-name-select'));

          newRow.querySelector('.delete-row-btn').addEventListener('click', function () {
            const rows = tbody.querySelectorAll('tr');
            if (rows.length > 1) {
              newRow.remove();
              updateRowNumbers();
            } else {
              alert('At least one row is required.');
            }
          });

          tbody.appendChild(newRow);
          updateRowNumbers();
        });

        firstRow.querySelector('.delete-row-btn').addEventListener('click', function () {
          const rows = document.querySelectorAll('#workout-schedule tbody tr');
          if (rows.length > 1) {
            firstRow.remove();
            updateRowNumbers();
          } else {
            alert('At least one row is required.');
          }
        });

        const memberId = new URLSearchParams(window.location.search).get('id');
        if (memberId) {
          document.getElementById('member_id').value = memberId;
        }
      })
      .catch(error => console.error('Error fetching workout data:', error));
  });
</script>

</body>
</html>
