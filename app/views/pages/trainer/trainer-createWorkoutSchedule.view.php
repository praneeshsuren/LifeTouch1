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

      <form action="create_schedule.php" method="POST">
        <table id="workout-schedule" border="1">
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
              <td class="workout-name-cell" data-id=""><input type="text" class="workout-name-input" readonly></td>
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
    </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/trainer-script.js?v=<?php echo time();?>"></script>

    <script>
      $(document).ready(function() {
        // Fetch all workouts from the database
        $.ajax({
          url: 'get_workouts.php',
          method: 'GET',
          success: function(data) {
            const workouts = JSON.parse(data);
            // Create dropdown when clicking on the workout name cell
            $('.workout-name-cell').click(function() {
              const workoutNameCell = $(this);
              const dropdown = $('<select></select>').addClass('workout-dropdown');
              workouts.forEach(workout => {
                dropdown.append(`<option value="${workout.workout_id}" data-equipment="${workout.equipment_id}" data-equipment-name="${workout.equipment_name}">${workout.workout_name}</option>`);
              });

              workoutNameCell.html(dropdown);
              dropdown.focus();
              
              // When an option is selected
              dropdown.change(function() {
                const selectedOption = $(this).find('option:selected');
                const workoutId = selectedOption.val();
                const equipmentId = selectedOption.data('equipment');
                const equipmentName = selectedOption.data('equipment-name');

                // Update the table cells
                workoutNameCell.html(selectedOption.text());
                workoutNameCell.data('id', workoutId);
                workoutNameCell.next().text(equipmentName);
                workoutNameCell.closest('tr').find('.workout-id-cell input').val(workoutId);
                workoutNameCell.closest('tr').find('.equipment-id-cell input').val(equipmentId);
                workoutNameCell.closest('tr').find('input[name="sets[]"]').focus();
              });
            });
          }
        });

        // Add a new row to the table
        $('#add-row').click(function() {
          const newRow = $('#workout-schedule tbody tr:first').clone();
          newRow.find('input').val('');
          newRow.find('.workout-name-cell').data('id', '').html('<input type="text" class="workout-name-input" readonly>');
          newRow.find('.equipment-name-cell').html('<input type="text" readonly>');
          newRow.find('.workout-id-cell input').val('');
          newRow.find('.equipment-id-cell input').val('');
          $('#workout-schedule tbody').append(newRow);
        });
      });
    </script>
  </body>
</html>
