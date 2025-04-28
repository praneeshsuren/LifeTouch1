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
        <?php if (!empty($data['workouts'])): ?>
            <?php foreach ($data['workouts'] as $workout): ?>
                <div class="workout-card" onclick="window.location.href='<?php echo URLROOT; ?>/trainer/viewWorkout?id=<?php echo $workout->workout_id; ?>'">
                    <div class="workout-image">
                        <img src="<?php echo URLROOT; ?>/assets/images/Equipment/<?php echo $workout->image; ?>" alt="Equipment Image" />
                    </div>
                    <div class="workout-details">
                        <h3><?php echo htmlspecialchars($workout->workout_name); ?></h3>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($workout->workout_description); ?></p>
                        <p><strong>Equipment:</strong> <?php echo htmlspecialchars($workout->equipment_name); ?></p>
                        <p><strong>Workout ID:</strong> <?php echo $workout->workout_id; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No workouts found.</p>
        <?php endif; ?>
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
            <label for="equipment-name">Equipment Name:</label>
            <input type="text" id="equipment-name" name="equipment-name" placeholder="Enter equipment name" autocomplete="off" />
            <div id="equipment-suggestions-list" class="suggestions-dropdown" style="display: none;"></div> <!-- Suggestions dropdown -->
            <small id="equipment-name-error" style="color: red; display: none;">Equipment name is required.</small> <!-- Error message for equipment name -->
          </div>

          <div id="equipmentImageContainer" class="image-container" style="display: none;">
            <img 
              id="equipmentImage" class="equipment-image" 
              alt="Equipment Image">
          </div>
          <input type="hidden" id="equipment_id" name="equipment_id" value="" /> <!-- Hidden input for equipment ID -->

          <div class="form-group">
            <label for="workout-name">Workout Name:</label>
            <input type="text" id="workout-name" name="workout-name" placeholder="Enter workout name"  />
            <small id="workout-name-error" style="color: red; display: none;">Workout name is Required.</small> <!-- Error message for workout name -->
          </div>

          <div class="form-group">
            <label for="workout-description">Description:</label>
            <textarea id="workout-description" name="workout-description" placeholder="Enter description" ></textarea>
            <small id="workout-description-error" style="color: red; display: none;">Description is required.</small> <!-- Error message for description -->
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
            resetModal(); // Reset modal when opened
        });

        // Close the modal when the close button is clicked
        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('createWorkoutModal').style.display = 'none';
            resetModal(); // Reset modal when closed
        });

        window.onclick = function(event) {
            const modal = document.getElementById('createWorkoutModal');
            if (event.target == modal) {
                modal.style.display = 'none';
                resetModal(); // Reset modal when clicked outside
            }
        }

        // Close the modal when the cancel button is clicked
        document.getElementById('cancelBtn').addEventListener('click', function() {
            document.getElementById('createWorkoutModal').style.display = 'none';
            resetModal(); // Reset modal when canceled
        });

        // Handle form submission
        document.getElementById('createWorkoutForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const workoutName = document.getElementById('workout-name').value.trim();
            const workoutDescription = document.getElementById('workout-description').value.trim();
            const equipmentName = document.getElementById('equipment-name').value.trim();
            const equipmentId = document.getElementById('equipment_id').value.trim();

            // Reset error messages
            resetErrorMessages();

            let hasError = false;

            // Validate inputs
            if (!workoutName) {
                document.getElementById('workout-name-error').style.display = 'block';
                hasError = true;
            }

            if (!workoutDescription) {
                document.getElementById('workout-description-error').style.display = 'block';
                hasError = true;
            }

            if (!equipmentName) {
                document.getElementById('equipment-name-error').style.display = 'block';
                hasError = true;
            }

            if (hasError) return;

            const workoutData = {
                name: workoutName,
                description: workoutDescription,
                equipment_name: equipmentName,
                equipment_id: equipmentId
            };

            // Send data to server
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
                    fetchWorkouts();  // Refresh the workout list
                } else {
                    alert(data.message || 'Failed to create workout');
                }
            })
            .catch(error => {
                console.error('Error creating workout:', error);
            });
        });

        // Equipment Name Input Suggestion
        const equipmentNameInput = document.getElementById('equipment-name');
        const equipmentSuggestionsList = document.getElementById('equipment-suggestions-list');
        const equipmentImage = document.getElementById('equipmentImage');
        const equipmentImageContainer = document.getElementById('equipmentImageContainer');
        const suggestionsDiv = document.getElementById('equipment-suggestions-list');
        const equipmentIdInput = document.getElementById('equipment_id');

        equipmentNameInput.addEventListener('input', function() {
            const query = equipmentNameInput.value.trim();
            if (query.length > 0) {  // Only search after 3 characters
                fetchEquipmentSuggestions(query);
            } else {
                equipmentSuggestionsList.style.display = 'none';  // Hide suggestions if input is less than 3 characters
                equipmentImageContainer.style.display = 'none';  // Hide image container
            }
        });

        function fetchEquipmentSuggestions(query) {
            fetch(`<?php echo URLROOT; ?>/workout/getEquipmentSuggestions?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    if(suggestionsDiv){
                        suggestionsDiv.innerHTML = '';  // Clear previous suggestions
                        if (data.length > 0) {
                            suggestionsDiv.style.display = 'block'; // Show suggestions
                            data.forEach(equipment => {
                                const suggestionItem = document.createElement('div');
                                suggestionItem.classList.add('suggestion-item');
                                suggestionItem.textContent = equipment.name;
                                suggestionItem.addEventListener('click', function() {
                                    equipmentNameInput.value = equipment.name;
                                    equipmentIdInput.value = equipment.equipment_id; // Set hidden input value
                                    equipmentImage.src = "<?php echo URLROOT;?>/assets/images/Equipment/" + equipment.file; // Show image container
                                    equipmentSuggestionsList.style.display = 'none';  // Hide suggestions
                                });
                                suggestionsDiv.appendChild(suggestionItem);
                            });
                            equipmentSuggestionsList.style.display = 'block';  // Show suggestions
                        } else {
                            equipmentSuggestionsList.style.display = 'none';  // Hide suggestions if no data
                            equipmentImageContainer.style.display = 'none';  // Hide image container
                        }
                    }

                })
                .catch(error => console.error('Error fetching equipment suggestions:', error));
        }

        function resetModal() {
            document.getElementById('workout-name').value = '';
            document.getElementById('workout-description').value = '';
            document.getElementById('equipment-name').value = '';
            document.getElementById('equipment-suggestions-list').style.display = 'none';
        }

        // Reset error messages
        function resetErrorMessages() {
            document.getElementById('workout-name-error').style.display = 'none';
            document.getElementById('workout-description-error').style.display = 'none';
            document.getElementById('equipment-name-error').style.display = 'none';
        }
      });
    </script>
  </body>
</html>
