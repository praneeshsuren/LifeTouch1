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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/receptionist-style.css?v=<?php echo time();?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/sidebar-greeting.css?v=<?php echo time();?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
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
      <?php require APPROOT.'/views/components/receptionist-sidebar.view.php'; ?>
    </section>

    <main>
      <div class="title">
        <h1>Supplement Records</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php'; ?>
        </div>
      </div>

      <div class="view-user-container">
        <div class="navbar-container">
          <div class="navbar">
            <ul class="nav-links">
              <li><a href="" id="userDetailsLink"><i class="ph ph-user"></i>User Details</a></li>
              <li><a href="" id="attendanceLink"><i class="ph ph-calendar-dots"></i>Attendance Records</a></li>
              <li><a href="" id="paymentHistoryLink"><i class="ph ph-money"></i>Payment History</a></li>
              <li class="active"><a href="" id="supplementRecordsLink"><i class="ph ph-barbell"></i>Supplement Records</a></li>
            </ul>
          </div>
        </div>

        <div class="user-container">
          <!-- Create Supplement Button -->
          <div class="supplement-header">
            <button id="addSupplementBtn" class="add-supplement-btn">+ Add Supplement</button>
          </div>

          <!-- Display Supplement Records in a Table -->
          <div id="supplement-table-container" class="supplement-table">
            <?php if (!empty($supplements)) : ?>
              <table>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Supplement Image</th>
                    <th>Supplement Name</th>
                    <th>Purchase Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $rowNumber = 1; ?>
                  <?php foreach ($supplements as $supplement) : ?>
                    <?php
                      $purchaseDate = date('d-m-Y', strtotime($supplement->sale_date));
                    ?>
                    <tr>
                      <td><?php echo $rowNumber++; ?></td>
                      <td><img src="<?php echo URLROOT . '/assets/images/Supplement/' . $supplement->file; ?>" alt="<?php echo $supplement->name; ?>" class="supplement-image"></td>
                      <td><?php echo $supplement->name; ?></td>
                      <td><?php echo $purchaseDate; ?></td>
                      <td>
                        <form action="<?php echo URLROOT; ?>/supplement/deleteSupplementSale" method="POST">
                          <!-- Hidden input to pass the sale_id -->
                          <input type="hidden" name="sale_id" value="<?php echo $supplement->sale_id; ?>">
                          <input type="hidden" name="member_id" value="<?php echo $supplement->member_id; ?>">
                          <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this supplement sale?')">Delete</button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php else : ?>
              <p>No supplement records found for this member.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </main>

    <!-- Modal for Adding Supplement -->
    <div id="addSupplementModal" class="modal">
      <div class="modal-content">
        <h2>Add Supplement</h2>
        <form action="<?php echo URLROOT; ?>/supplement/addSupplementSale" method="POST" enctype="multipart/form-data">
          <label for="supplementName">Supplement Name:</label>
          <input type="text" id="supplementName" name="name" required>
          <div id="suggestions" class="suggestions-dropdown" style="display: none;"></div>
          
          <!-- Hidden input to store the supplement ID -->
          <input type="hidden" id="supplementId" name="supplement_id">
          <input type="hidden" id="memberId" name="member_id">


          <!-- Image preview container -->
          <div id="supplementImageContainer" class="image-container" style="display: none;">
            <img 
              id="supplementImage" class="supplement-image" 
              alt="Supplement Image">
          </div>

          <!-- Display quantity available -->
          <label for="quantityAvailable">Quantity Available:</label>
          <input type="text" id="quantityAvailable" name="quantity_available" readonly>

          <label for="quantity">Quantity:</label>
          <input type="number" id="quantity" name="quantity" required>
          <div id="quantityError" class="error-message" style="color: red; display: none;"></div> <!-- Error Message -->

          <label for="price">Price of a Supplement:</label>
          <input type="number" id="price" name="price_of_a_supplement" required>
          <div id="priceError" class="error-message" style="color: red; display: none;"></div> <!-- Error Message -->

          <label for="saleDate">Sold Date:</label>
          <input type="date" name="sale_date" id="saleDate" required>
          <div id="saleDateError" class="error-message" style="color: red; display: none;"></div> <!-- Error Message -->

          <button type="submit" class="submit-btn">Add Supplement</button>
          <button type="button" class="cancel-btn">Cancel</button> <!-- Cancel Button -->
        </form>
      </div>
    </div>


    <script src="<?php echo URLROOT; ?>/assets/js/receptionist-script.js?v=<?php echo time();?>"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {

        function getUrlParameter(name) {
          const urlParams = new URLSearchParams(window.location.search);
          return urlParams.get(name);
        }

        // Get the 'id' parameter (member_id) from the URL
        const memberId = getUrlParameter('id');

        if (memberId) {
          // Member ID is available, use it in the navigation link
          document.getElementById('userDetailsLink').href = `<?php echo URLROOT; ?>/receptionist/members/viewMember?id=${memberId}`;
          document.getElementById('attendanceLink').href = `<?php echo URLROOT; ?>/receptionist/members/memberAttendance?id=${memberId}`;
          document.getElementById('paymentHistoryLink').href = `<?php echo URLROOT; ?>/receptionist/members/memberPaymentHistory?id=${memberId}`;
          document.getElementById('supplementRecordsLink').href = `<?php echo URLROOT; ?>/receptionist/members/memberSupplements?id=${memberId}`;

        } else {
          // No member_id in the URL, show a message or handle accordingly
          alert('No member selected.');
        }

        const supplementNameInput = document.getElementById('supplementName');
        const suggestionsDiv = document.getElementById('suggestions');
        const supplementIdInput = document.getElementById('supplementId');
        const availableQuantityInput = document.getElementById('quantityAvailable');
        const quantityInput = document.getElementById('quantity');
        const supplementImageContainer = document.getElementById('supplementImageContainer');
        const supplementImage = document.getElementById('supplementImage');
        const memberIdInput = document.getElementById('memberId');

        supplementNameInput.addEventListener('input', function() {
          const query = supplementNameInput.value.trim();
          if (query.length > 0) {
            fetchSuggestions(query);
          } else {
            suggestionsDiv.style.display = 'none'; // Hide suggestions if input is empty
            supplementImageContainer.style.display = 'none'; // Hide image if no supplement is selected
          }
        });

        function fetchSuggestions(query) {
          fetch(`<?php echo URLROOT; ?>/supplement/getSupplements?query=${query}`)
            .then(response => response.json())
            .then(data => {
              if (suggestionsDiv) {
                suggestionsDiv.innerHTML = ''; // Clear previous suggestions
                if (data.length > 0) {
                  suggestionsDiv.style.display = 'block'; // Show suggestions
                  data.forEach(supplement => {
                    const suggestionItem = document.createElement('div');
                    suggestionItem.classList.add('suggestion-item');
                    suggestionItem.textContent = supplement.name;
                    suggestionItem.addEventListener('click', function() {
                      supplementNameInput.value = supplement.name;
                      supplementIdInput.value = supplement.supplement_id;
                      availableQuantityInput.value = supplement.quantity_available;
                      quantityInput.setAttribute('max', supplement.quantity_available); // Set max attribute
                      memberIdInput.value = memberId; // Set the member ID


                      // Set the image dynamically
                      const imageUrl = `<?php echo URLROOT; ?>/assets/images/Supplement/${supplement.file}`;
                      console.log('Loading image from:', imageUrl); // Debugging line to check image URL

                      supplementImage.src = imageUrl;
                      supplementImageContainer.style.display = 'block'; // Show the image preview
                      suggestionsDiv.style.display = 'none'; // Hide suggestions after selection
                    });
                    suggestionsDiv.appendChild(suggestionItem);
                  });
                } else {
                  suggestionsDiv.style.display = 'none'; // Hide suggestions if no matches
                }
              }
            })
            .catch(error => {
              console.error('Error fetching suggestions:', error);
            });
        }

        // Modal open/close logic
        const addSupplementBtn = document.getElementById('addSupplementBtn');
        if (addSupplementBtn) {
          addSupplementBtn.addEventListener('click', function() {
            const modal = document.getElementById('addSupplementModal');
            modal.style.display = "block";
          });
        }

        // Modal close button
        const closeBtn = document.getElementById('cancelBtn');
        if (closeBtn) {
          closeBtn.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent form submission
            const modal = document.getElementById('addSupplementModal');
            modal.style.display = "none";
          });
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
          const modal = document.getElementById('addSupplementModal');
          if (event.target == modal) {
            modal.style.display = "none";
          }
        }
});

    </script>
  </body>
</html>
