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
                    <!-- Rows will be dynamically populated here -->
                </tbody>
            </table>
            <button type="button" id="add-row">Add Row</button>

            <div>
                <label for="weight_beginning">Weight (kg) at Start:</label>
                <input type="number" id="weight_beginning" name="weight_beginning" min="1" required>
            </div>
            
            <div>
                <label for="weight_end">Weight (kg) at Completion:</label>
                <input type="number" id="weight_end" name="weight_end" min="1">
            </div>

            <div>
                <label for="chest_measurement_beginning">Chest Measurement (cm) at Start:</label>
                <input type="number" id="chest_measurement_beginning" name="chest_measurement_beginning" min="1" required>
            </div>

            <div>
                <label for="chest_measurement_end">Chest Measurement (cm) at Completion:</label>
                <input type="number" id="chest_measurement_end" name="chest_measurement_end" min="1">
            </div>

            <div>
                <label for="bicep_measurement_beginning">Bicep Measurement (cm) at Start:</label>
                <input type="number" id="bicep_measurement_beginning" name="bicep_measurement_beginning" min="1" required>
            </div>

            <div>
                <label for="bicep_measurement_end">Bicep Measurement (cm) at Completion:</label>
                <input type="number" id="bicep_measurement_end" name="bicep_measurement_end" min="1">
            </div>

            <div>
                <label for="thigh_measurement_beginning">Thigh Measurement (cm) at Start:</label>
                <input type="number" id="thigh_measurement_beginning" name="thigh_measurement_beginning" min="1" required>
            </div>

            <div>
                <label for="thigh_measurement_end">Thigh Measurement (cm) at Completion:</label>
                <input type="number" id="thigh_measurement_end" name="thigh_measurement_end" min="1">
            </div>

            <button type="button" id="mark-completed">Mark as Completed</button>
            <button type="button" id="save-schedule">Save Schedule</button>
            <button type="button" id="save-pdf">Save as PDF</button>

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

    // Get the 'id' parameter (schedule_id) from the URL
    const scheduleId = getUrlParameter('id');
    
    if (!scheduleId) {
        alert('No schedule ID found in the URL.');
        return;
    }

    // Fetch workout schedule data from the backend API
    fetch('<?php echo URLROOT; ?>/WorkoutSchedule/getWorkoutSchedule?id=' + scheduleId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const schedule = data.schedule;

                // Populate the form with the fetched schedule data
                document.getElementById('weight_beginning').value = schedule.weight_beginning || '';
                document.getElementById('weight_end').value = schedule.weight_end || '';
                document.getElementById('chest_measurement_beginning').value = schedule.chest_measurement_beginning || '';
                document.getElementById('chest_measurement_end').value = schedule.chest_measurement_end || '';
                document.getElementById('bicep_measurement_beginning').value = schedule.bicep_measurement_beginning || '';
                document.getElementById('bicep_measurement_end').value = schedule.bicep_measurement_end || '';
                document.getElementById('thigh_measurement_beginning').value = schedule.thigh_measurement_beginning || '';
                document.getElementById('thigh_measurement_end').value = schedule.thigh_measurement_end || '';

                // Populate the rows in the table with workout details
                const tbody = document.querySelector('#workout-schedule tbody');
                if (schedule.workout_details && Array.isArray(schedule.workout_details)) {
                    schedule.workout_details.forEach(detail => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="workout-id-cell"><input type="text" value="${detail.workout_id}" readonly></td>
                            <td class="workout-name-cell" data-id="">
                                <input type="text" value="${detail.workout_name}" readonly>
                            </td>
                            <td class="equipment-id-cell"><input type="text" value="${detail.equipment_id}" readonly></td>
                            <td class="equipment-name-cell"><input type="text" value="${detail.equipment_name}" readonly></td>
                            <td><input type="number" name="sets[]" value="${detail.sets}" min="1" required readonly></td>
                            <td><input type="number" name="reps[]" value="${detail.reps}" min="1" required readonly></td>
                        `;
                        tbody.appendChild(row);
                    });
                } else {
                    console.error("No workout details found.");
                }

            } else {
                alert('Schedule not found.');
            }
        })
        .catch(error => console.error('Error fetching workout data:', error));

    // Mark the schedule as completed
    document.getElementById('mark-completed').addEventListener('click', function () {
        fetch('<?php echo URLROOT; ?>/WorkoutSchedule/markCompleted', {
            method: 'POST',
            body: JSON.stringify({ schedule_id: scheduleId }),
            headers: { 'Content-Type': 'application/json' }
        }).then(response => response.json()).then(data => {
            if (data.success) {
                alert('Workout schedule marked as completed!');
            } else {
                alert('Failed to mark as completed.');
            }
        });
    });

    // Update schedule functionality (using updateSchedule API)
    document.getElementById('save-schedule').addEventListener('click', function (event) {
        event.preventDefault();

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

        const formData = {
            schedule_id: scheduleId,
            workout_details: workoutDetails,
            weight_beginning: document.getElementById('weight_beginning').value,
            weight_end: document.getElementById('weight_end').value,
            chest_measurement_beginning: document.getElementById('chest_measurement_beginning').value,
            chest_measurement_end: document.getElementById('chest_measurement_end').value,
            bicep_measurement_beginning: document.getElementById('bicep_measurement_beginning').value,
            bicep_measurement_end: document.getElementById('bicep_measurement_end').value,
            thigh_measurement_beginning: document.getElementById('thigh_measurement_beginning').value,
            thigh_measurement_end: document.getElementById('thigh_measurement_end').value
        };

        fetch('<?php echo URLROOT; ?>/WorkoutSchedule/updateSchedule', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Workout schedule updated successfully!');
            } else {
                alert('Error updating workout schedule.');
            }
        })
        .catch(error => console.error('Error updating workout schedule:', error));
    });

    // Delete entire schedule
    document.getElementById('delete-schedule').addEventListener('click', function () {
        fetch('<?php echo URLROOT; ?>/WorkoutSchedule/deleteSchedule', {
            method: 'POST',
            body: JSON.stringify({ schedule_id: scheduleId }),
            headers: { 'Content-Type': 'application/json' }
        }).then(response => response.json()).then(data => {
            if (data.success) {
                alert('Workout schedule deleted successfully!');
                window.location.href = '<?php echo URLROOT; ?>/trainer/members/workoutSchedules?id=' + scheduleId;
            } else {
                alert('Failed to delete schedule.');
            }
        });
    });

    // Save as PDF functionality (implement if required)
    document.getElementById('save-pdf').addEventListener('click', function () {
        alert('Save as PDF functionality is not implemented yet.');
    });
});


    </script>

  </body>
</html>
