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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/trainer-style.css?v=<?php echo time();?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/sidebar-greeting.css?v=<?php echo time();?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>
    <section class="sidebar">
        <?php require APPROOT.'/views/components/trainer-sidebar.view.php' ?>
    </section>
    
    <main>
      <div class="title">
        <h1>Workouts</h1>
        <div class="greeting">
            <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>
      </div>

      <div class="workouts-container">
        <div class="workouts-header">
            <div class="searchBar">
                <input type="text" placeholder="Search for workouts" />
            </div>
            <button class="add-workout-btn">+ Add Workout</button>
        </div>

        <div class="workouts" id="workouts-container">
            <!-- Workout cards will be dynamically inserted here -->
        </div>
      </div>
    </main>

    <!-- Modal for Creating Workout -->
    <div class="modal" id="createWorkoutModal">
      <div class="modal-content">
        <span class="close-btn" id="closeModal">&times;</span>
        <h2>Create Workout</h2>
        <form id="createWorkoutForm">
          <div class="form-group">
            <label for="equipment">Select Equipment:</label>
            <select id="equipment" name="equipment" required>
              <option value="">-- Choose Equipment --</option>
            </select>
            <div id="selected-equipment">
              <img id="equipment-image" src="" alt="Equipment Image" style="width: 50px; height: 50px; display: none;" />
              <p id="equipment-name"></p>
              <p id="equipment-id"></p> <!-- Display equipment ID here -->
            </div>
          </div>

          <div class="form-group">
            <label for="workout-name">Workout Name:</label>
            <input type="text" id="workout-name" name="workout-name" placeholder="Enter workout name" required />
          </div>

          <div class="form-group">
            <label for="workout-description">Description:</label>
            <textarea id="workout-description" name="workout-description" placeholder="Enter description" required></textarea>
          </div>

          <div class="form-actions">
            <button type="button" id="cancelBtn">Cancel</button>
            <button type="submit" id="saveBtn">Save Workout</button>
          </div>
        </form>
      </div>
    </div>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/trainer-script.js?v=<?php echo time();?>"></script>
    <script>
      let equipmentData = [];
      let workoutData = [];

      document.addEventListener('DOMContentLoaded', function() {
          // Show the modal when the "Create Workout" button is clicked
          document.querySelector('.add-workout-btn').addEventListener('click', function() {
              document.getElementById('createWorkoutModal').style.display = 'flex'; 
              fetchEquipment();
              resetModal(); // Reset modal when opened
          });

          // Close the modal when the close button is clicked
          document.getElementById('closeModal').addEventListener('click', function() {
              document.getElementById('createWorkoutModal').style.display = 'none';
              resetModal(); // Reset modal when closed
          });

          // Close the modal when the cancel button is clicked
          document.getElementById('cancelBtn').addEventListener('click', function() {
              document.getElementById('createWorkoutModal').style.display = 'none';
              resetModal(); // Reset modal when canceled
          });

          // Handle form submission
          document.getElementById('createWorkoutForm').addEventListener('submit', function(event) {
              event.preventDefault();

              const workoutName = document.getElementById('workout-name').value;
              const workoutDescription = document.getElementById('workout-description').value;
              const equipmentId = document.getElementById('equipment').value;

              const workoutData = {
                  name: workoutName,
                  description: workoutDescription,
                  equipment_id: equipmentId
              };

              fetch('<?php echo URLROOT; ?>/workout/createWorkout', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                  },
                  body: JSON.stringify(workoutData)
              })
              .then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert('Workout created successfully!');
                      document.getElementById('createWorkoutModal').style.display = 'none';
                      resetModal();
                      fetchWorkouts();  // Refresh workouts list
                  } else {
                      alert('Failed to create workout');
                  }
              })
              .catch(error => {
                  console.error('Error creating workout:', error);
                  alert('There was an error creating the workout.');
              });
          });

          // Fetch workouts when page loads
          fetchWorkouts();
      });

      // Fetch equipment data to populate the dropdown
      function fetchEquipment() {
          fetch('<?php echo URLROOT; ?>/equipment/api')
              .then(response => response.json())
              .then(data => {
                  equipmentData = data;
                  populateEquipmentDropdown(data);
              })
              .catch(error => console.error('Error fetching equipment:', error));
      }

      // Fetch workouts from the database
      function fetchWorkouts() {
          fetch('<?php echo URLROOT; ?>/workout/api')
              .then(response => response.json())
              .then(data => {
                  workoutData = data;
                  displayWorkouts(data);
              })
              .catch(error => console.error('Error fetching workouts:', error));
      }

      // Display workouts as cards
      function displayWorkouts(workouts) {
          const container = document.getElementById('workouts-container');
          container.innerHTML = ''; // Clear the container before adding new cards

          workouts.forEach(workout => {
              const workoutCard = document.createElement('div');
              workoutCard.classList.add('workout-card');

              const equipment = equipmentData.find(item => item.equipment_id === workout.equipment_id);

              workoutCard.innerHTML = `
                  <div class="workout-image">
                      <img src="${equipment ? equipment.file : ''}" alt="Equipment Image" style="width: 50px; height: 50px;" />
                  </div>
                  <div class="workout-details">
                      <h3>${workout.workout_name}</h3>
                      <p><strong>Description:</strong> ${workout.workout_description}</p>
                      <p><strong>Equipment:</strong> ${equipment ? equipment.name : 'N/A'}</p>
                      <p><strong>Equipment ID:</strong> ${equipment ? equipment.equipment_id : 'N/A'}</p>
                      <p><strong>Workout ID:</strong> ${workout.workout_id}</p>
                  </div>
              `;

              container.appendChild(workoutCard);
          });
      }

      // Populate the equipment dropdown with fetched equipment
      function populateEquipmentDropdown(equipment) {
          const equipmentSelect = document.getElementById('equipment');
          equipmentSelect.innerHTML = '<option value="">-- Choose Equipment --</option>'; 
          equipment.forEach(item => {
              const option = document.createElement('option');
              option.value = item.equipment_id;
              option.textContent = item.name;
              equipmentSelect.appendChild(option);
          });
      }

      // Reset modal content
      function resetModal() {
          document.getElementById('equipment-image').style.display = 'none';
          document.getElementById('equipment-id').textContent = '';
          document.getElementById('equipment-name').textContent = '';
          document.getElementById('equipment').value = ''; // Reset dropdown
      }
    </script>
  </body>
</html>
