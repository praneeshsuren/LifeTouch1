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

    <section class="sidebar">
      <?php require APPROOT.'/views/components/trainer-sidebar.view.php' ?>
    </section>

    <main>
      <div class="title">
        <h1>Workout Schedule Details</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>
      </div>

      <div class="workout-schedule-container">
        <!-- Display Workout Rows (Row No, Description) -->
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
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['workout_details'] as $workout): ?>
                <tr>
                    <td class="row-index"><input type="number" value="<?php echo $workout->row_no; ?>" readonly></td>
                    <td class="workout-id-cell"><input type="text" value="<?php echo $workout->workout_id; ?>" readonly></td>
                    <td class="workout-id-cell"><input type="text" value="<?php echo $workout->workout_name; ?>" readonly></td>
                    <td class="equipment-id-cell"><input type="text" value="<?php echo $workout->equipment_id; ?>" readonly></td>
                    <td class="equipment-name-cell"><input type="text" value="<?php echo $workout->equipment_name; ?>" readonly></td>
                    <td class="description-cell"><input type="text" value="<?php echo $workout->description; ?>" readonly></td>
                    <td><input type="number" value="<?php echo $workout->sets; ?>" min="1" readonly></td>
                    <td><input type="number" value="<?php echo $workout->reps; ?>" min="1" readonly></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
                
        <!-- Schedule Details Section -->
        <form id="update-schedule-form" method="POST" action="<?php echo URLROOT; ?>/WorkoutSchedule/updateWorkoutSchedule">

            <input type="hidden" name="schedule_id" value="<?php echo $data['schedule']->schedule_id; ?>">
            <input type="hidden" name="member_id" value="<?php echo $data['schedule']->member_id; ?>">

            <div class="measurements">
                <div class="measurement-group">
                    <div>
                        <label for="weight_beginning">Weight (kg) at Start:</label>
                        <input type="number" id="weight_beginning" name="weight_beginning" value="<?php echo $data['schedule']->weight_beginning; ?>" readonly>
                    </div>
                    <div>
                        <label for="weight_ending">Weight (kg) at Completion:</label>
                        <input type="number" id="weight_end" name="weight_end" value="<?php echo $data['schedule']->weight_ending; ?>">
                    </div>
                </div>

                <div class="measurement-group">
                    <div>
                        <label for="chest_measurement_beginning">Chest Measurement (cm) at Start:</label>
                        <input type="number" id="chest_measurement_beginning" name="chest_measurement_beginning" value="<?php echo $data['schedule']->chest_measurement_beginning; ?>" readonly>
                    </div>
                    <div>
                        <label for="chest_measurement_ending">Chest Measurement (cm) at Completion:</label>
                        <input type="number" id="chest_measurement_ending" name="chest_measurement_ending" value="<?php echo $data['schedule']->chest_measurement_ending; ?>">
                    </div>
                </div>
                <div class="measurement-group">
                    <div>
                        <label for="bicep_measurement_beginning">Bicep Measurement (cm) at Start:</label>
                        <input type="number" id="bicep_measurement_beginning" name="bicep_measurement_beginning" value="<?php echo $data['schedule']->bicep_measurement_beginning; ?>" readonly>
                    </div>
                    <div>
                        <label for="bicep_measurement_ending">Chest Measurement (cm) at Completion:</label>
                        <input type="number" id="bicep_measurement_ending" name="bicep_measurement_ending" value="<?php echo $data['schedule']->bicep_measurement_ending; ?>">
                    </div>
                </div>
                <div class="measurement-group">
                    <div>
                        <label for="thigh_measurement_beginning">Chest Measurement (cm) at Start:</label>
                        <input type="number" id="thigh_measurement_beginning" name="thigh_measurement_beginning" value="<?php echo $data['schedule']->chest_measurement_beginning; ?>" readonly>
                    </div>
                    <div>
                        <label for="thigh_measurement_end">Chest Measurement (cm) at Completion:</label>
                        <input type="number" id="thigh_measurement_ending" name="thigh_measurement_ending" value="<?php echo $data['schedule']->chest_measurement_ending; ?>">
                    </div>
                </div>
            </div>

            <div class="action-buttons-update-schedule">
                <button type="button" id="mark-completed">Mark as Completed</button>
                <button type="button" id="save-pdf">Save as PDF</button>
                <button type="button" id="delete-schedule">Delete Schedule</button>
                <button type="submit" id="update-schedule">Update Schedule</button>
            </div>

        </form>
</div>


    </main>
    <script src="<?php echo URLROOT; ?>/assets/js/trainer-script.js?v=<?php echo time(); ?>"></script>
    <script>
    // Mark as Completed button click event
    document.getElementById('mark-completed').addEventListener('click', function() {
        const scheduleId = document.querySelector('input[name="schedule_id"]').value;

        fetch('<?php echo URLROOT; ?>/WorkoutSchedule/markCompleted', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ schedule_id: scheduleId }),
        })
        .then(response => response.json())  // Assuming the response is JSON
        .then(data => {
            if (data.success) {
                alert('Workout schedule marked as completed!');
                window.location.reload();  // Reload the page to reflect the changes
            } else {
                alert('Failed to mark as completed!');
            }
        })
        .catch(error => {
            alert('An error occurred: ' + error);
        });
    });

    // Delete Schedule button click event
    document.getElementById('delete-schedule').addEventListener('click', function() {
        const scheduleId = document.querySelector('input[name="schedule_id"]').value;
        const memberID = "<?php echo $data['schedule']->member_id; ?>";  // Getting the member ID from PHP

        if (confirm('Are you sure you want to delete this workout schedule?')) {
            fetch('<?php echo URLROOT; ?>/WorkoutSchedule/deleteSchedule', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ schedule_id: scheduleId }),
            })
            .then(response => response.json())  // Assuming the response is JSON
            .then(data => {
                if (data.success) {
                    alert('Workout schedule deleted!');
                    window.location.href = '<?php echo URLROOT; ?>trainer/members/workoutSchedules?id=' + memberID;  // Redirect to schedule list page
                } else {
                    alert('Failed to delete schedule!');
                }
            })
            .catch(error => {
                alert('An error occurred: ' + error);
            });
        }
    });
</script>

  </body>
</html>
