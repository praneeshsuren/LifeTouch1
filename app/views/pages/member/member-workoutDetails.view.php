<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- STYLESHEET -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/member-style.css?v=<?php echo time();?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/sidebar-greeting.css?v=<?php echo time();?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>

    <section class="sidebar">
    <?php require APPROOT.'/views/components/member-sidebar.view.php' ?>
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
                            <td class="row-index"><input name="row_no" type="number" value="<?php echo $workout->row_no; ?>" readonly></td>
                            <td class="workout-id-cell"><input name="workout_id" type="text" value="<?php echo $workout->workout_id; ?>" readonly></td>
                            <td class="workout-id-cell"><input name="workout_name" type="text" value="<?php echo $workout->workout_name; ?>" readonly></td>
                            <td class="equipment-id-cell"><input name="equipment_id" type="text" value="<?php echo $workout->equipment_id; ?>" readonly></td>
                            <td class="equipment-name-cell"><input type="text" name="equipment_name" value="<?php echo $workout->equipment_name; ?>" readonly></td>
                            <td class="description-cell"><input name="description" type="text" value="<?php echo $workout->description; ?>" readonly></td>
                            <td><input type="number" name="sets" value="<?php echo $workout->sets; ?>" min="1" readonly></td>
                            <td><input type="number" name="reps" value="<?php echo $workout->reps; ?>" min="1" readonly></td>
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
                            <p>Weight (kg) at Start:</p>
                            <input type="number" id="weight_beginning" name="weight_beginning" value="<?php echo $data['schedule']->weight_beginning; ?>" readonly>
                        </div>
                        <div>
                            <p>Weight (kg) at Completion:</p>
                            <input type="number" id="weight_end" name="weight_end" value="<?php echo $data['schedule']->weight_ending; ?>">
                        </div>
                    </div>

                    <div class="measurement-group">
                        <div>
                            <p>Chest Measurement (cm) at Start:</p>
                            <input type="number" id="chest_measurement_beginning" name="chest_measurement_beginning" value="<?php echo $data['schedule']->chest_measurement_beginning; ?>" readonly>
                        </div>
                        <div>
                        <p>Chest Measurement (cm) at Completion:</p>
                            <input type="number" id="chest_measurement_ending" name="chest_measurement_ending" value="<?php echo $data['schedule']->chest_measurement_ending; ?>">
                        </div>
                    </div>
                    <div class="measurement-group">
                        <div>
                        <p>Bicep Measurement (cm) at Start:</p>
                            <input type="number" id="bicep_measurement_beginning" name="bicep_measurement_beginning" value="<?php echo $data['schedule']->bicep_measurement_beginning; ?>" readonly>
                        </div>
                        <div>
                        <p>Bicep Measurement (cm) at Completion:</p>
                            <input type="number" id="bicep_measurement_ending" name="bicep_measurement_ending" value="<?php echo $data['schedule']->bicep_measurement_ending; ?>">
                        </div>
                    </div>
                    <div class="measurement-group">
                        <div>
                        <p>Thigh Measurement (cm) at Start:</p>
                            <input type="number" id="thigh_measurement_beginning" name="thigh_measurement_beginning" value="<?php echo $data['schedule']->chest_measurement_beginning; ?>" readonly>
                        </div>
                        <div>
                        <p>Thigh Measurement (cm) at Completion:</p>
                            <input type="number" id="thigh_measurement_ending" name="thigh_measurement_ending" value="<?php echo $data['schedule']->chest_measurement_ending; ?>">
                        </div>
                    </div>
                </div>

                <div class="action-buttons-update-schedule">
                    <button type="button" id="downloadPDF" data-schedule-id="<?php echo $data['schedule']->schedule_id; ?>">Save as PDF</button>
                </div>
            </form>
        </div>



      
    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/member/member-script.js?v=<?php echo time();?>"></script>
    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time(); ?>"></script>
    <script>
        document.getElementById("downloadPDF").addEventListener("click", function(e) {
            e.preventDefault();
            const scheduleId = this.getAttribute("data-schedule-id");
            window.open(`<?php echo URLROOT; ?>/workout_pdf/generate/${scheduleId}`, '_blank');
        });

    </script>
</html>

