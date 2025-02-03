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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/admin-style.css?v=<?php echo time(); ?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title><?php echo APP_NAME; ?></title>
</head>

<body>

    <!-- PHP Alerts for Success/Error Messages -->
    <?php
      if (isset($_SESSION['success'])) {
          echo "<script>alert('" . $_SESSION['success'] . "');</script>";
          unset($_SESSION['success']); // Clear the message after showing it
      }

      if (isset($_SESSION['error'])) {
          echo "<script>alert('" . $_SESSION['error'] . "');</script>";
          unset($_SESSION['error']); // Clear the message after showing it
      }
    ?>

    <section class="sidebar">
        <?php require APPROOT . '/views/components/admin-sidebar.view.php' ?>
    </section>

    <main>

        <div class="title">
            <h1>Announcements</h1>
            <div class="greeting">
                <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
            </div>
        </div>

        <div class="announcements-container">
            <div class="searchBar">
                <input 
                    type="text" 
                    id="announcementSearch" 
                    placeholder="Search here.." 
                    onkeyup="filterTable()" 
                />
            </div>

            <div class="table-container">
                <div class="heading">
                    <h2>Announcements</h2>
                    <a href="<?php echo URLROOT; ?>/admin/announcements/createAnnouncement" class="btn">+ New Announcement</a>
                </div>

                <!-- Table with scrolling -->
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <td>Posted By</td>
                                <td>Announcement ID</td>
                                <td>Subject</td>
                                <td>Date</td>
                            </tr>
                        </thead>
                        <tbody id="announcementTableBody">
                            <?php if (!empty($data['announcements'])): ?>
                                <?php foreach ($data['announcements'] as $announcement): ?>
                                    <tr onclick="openModal(
                                        '<?php echo $announcement->subject; ?>',
                                        '<?php echo $announcement->description; ?>',
                                        '<?php echo $announcement->announcement_id; ?>',
                                        '<?php echo $announcement->created_date; ?>',
                                        '<?php echo $announcement->created_time; ?>',
                                        '<?php echo $announcement->created_by; ?>'
                                    )">
                                        <td>
                                            <div class="profile-pic">
                                                <img class="preview-image" src="<?php echo URLROOT; ?>/assets/images/image.png" alt="">
                                                <div class="person-info">
                                                    <h4>Kavishka</h4>
                                                    <small class="email">kavishka@gmail.com</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo $announcement->announcement_id; ?></td>
                                        <td><?php echo $announcement->subject; ?></td>
                                        <td><?php echo $announcement->created_date; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">No announcements found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Content -->
        <div id="announcementModal" class="modal">

            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <form id="announcementForm" method="POST" action="<?php echo URLROOT; ?>/announcement/updateAnnouncement">
                    <div class="modal-body">
                        <div class="details">
                            <div class="profile-img">
                                <img src="<?php echo URLROOT; ?>/assets/images/image.png" alt="">
                            </div>
                            <div class="name-and-title">
                                <h3 id="modalCreatedBy"></h3>
                                <input id="modalSubject" name="subject" type="text" class="modal-input" placeholder="Announcement Subject" disabled />
                            </div>
                        </div>
                        <input id="modalId" name="announcement_id" style="display: none;"/>
                        <textarea id="modalDescription" name="description" class="modal-input" rows="5" placeholder="Announcement Description" disabled></textarea>
                        <div class="date-time">
                            <div class="announcement-date">
                                <i class="ph ph-calendar"></i>
                                <p><span id="modalDate"></span></p>
                            </div>
                            <div class="announcement-time">
                                <i class="ph ph-clock"></i>
                                <p><span id="modalTime"></span></p>
                            </div>
                        </div>

                        <!-- Action buttons -->
                        <div class="modal-actions">
                            <button type="button" class="btn edit-btn" onclick="editAnnouncement()">Edit</button>
                            <button type="button" class="btn delete-btn" onclick="deleteAnnouncement()">Delete</button>
                            <button type="submit" class="btn save-btn" style="display:none;">Save</button>
                            <button type="button" class="btn cancel-btn" onclick="cancelEdit()" style="display:none;">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </main>

    <script src="<?php echo URLROOT; ?>/assets/js/admin-script.js?v=<?php echo time(); ?>"></script>

    <script>

        let isEditing = false; // Track whether we are in edit mode or not
        let currentAnnouncement = {}; // Store the current announcement data for editing

        function openModal(subject, description, ann_id, date, time, createdBy) {

            console.log(description);
            // Populate modal content
            document.getElementById('modalSubject').value = subject;
            document.getElementById('modalDescription').value = description;
            document.getElementById('modalId').value = ann_id
            document.getElementById('modalDate').textContent = date;
            document.getElementById('modalTime').textContent = time;
            document.getElementById('modalCreatedBy').textContent = createdBy;

            // Store the current announcement data
            currentAnnouncement = { subject, description, ann_id, date, time, createdBy };

            // Show the Edit and Delete buttons, and hide Save and Cancel buttons initially
            toggleActionButtons(true);

            // Display the modal
            const modal = document.getElementById('announcementModal');
            modal.style.display = 'flex';

            document.body.style.overflow = 'hidden';

            // Close the modal when clicked outside the content area
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeModal();
                }
            });
        }

        function closeModal() {

            // Disable editing
            document.getElementById('modalSubject').disabled = true;
            document.getElementById('modalDescription').disabled = true;

            document.body.style.overflow = 'auto';

            // Hide the modal
            document.getElementById('announcementModal').style.display = 'none';
        }

        function toggleActionButtons(isEditMode) {
            // Toggle visibility of the action buttons based on whether the modal is in edit mode
            document.querySelector('.edit-btn').style.display = isEditMode ? 'inline-block' : 'none';
            document.querySelector('.delete-btn').style.display = isEditMode ? 'inline-block' : 'none';
            document.querySelector('.save-btn').style.display = isEditMode ? 'none' : 'inline-block';
            document.querySelector('.cancel-btn').style.display = isEditMode ? 'none' : 'inline-block';
        }

        function editAnnouncement() {
            // Enable editing by making the subject and description editable
            document.getElementById('modalSubject').disabled = false;
            document.getElementById('modalDescription').disabled = false;

            // Change button states
            toggleActionButtons(false);
            isEditing = true;
        }

        function saveAnnouncement() {
            document.getElementById('announcementForm').submit();
        }

        function cancelEdit() {
            // Restore the original data and disable editing
            document.getElementById('modalSubject').value = currentAnnouncement.subject;
            document.getElementById('modalDescription').value = currentAnnouncement.description;

            // Disable editing
            document.getElementById('modalSubject').disabled = true;
            document.getElementById('modalDescription').disabled = true;

            // Show the original action buttons
            toggleActionButtons(true);
            isEditing = false;
        }

        function deleteAnnouncement() {
            // Confirm before deleting
            if (confirm('Are you sure you want to delete this announcement?')) {
                // Set the form action to the delete endpoint
                const form = document.getElementById('announcementForm');
                form.action = "<?php echo URLROOT; ?>/announcement/deleteAnnouncement";

                // Submit the form to delete the announcement
                form.submit();
            }
        }

        function filterTable() {
            // Get the input field and its value
            const input = document.getElementById("announcementSearch");
            const filter = input.value.toLowerCase(); // Convert input to lowercase for case-insensitive matching
            const tableBody = document.getElementById("announcementTableBody"); // Get the correct table body
            const rows = tableBody.getElementsByTagName("tr"); // Get all rows in the table body

            // Loop through all table rows and hide those that don't match the search query
            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName("td"); // Get all cells in the current row
                let match = false;

                // Check each cell in the row for a match
                for (let j = 0; j < cells.length; j++) {
                    if (cells[j]) {
                        if (cells[j].textContent.toLowerCase().includes(filter)) {
                            match = true; // Found a match
                            break;
                        }
                    }
                }

                // Show or hide the row based on whether there was a match
                rows[i].style.display = match ? "" : "none";
            }
        }

    </script>

</body>

</html>