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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/admin-style.css?v=<?php echo time();?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/salary.css?v=<?php echo time();?>" />

    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>

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
      <?php require APPROOT.'/views/components/manager_sidebar.view.php' ?>
    </section>

    <main>

      <div class="title">

        <h1>Salary History</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>
        
      </div>

      <div class="view-user-container">

        <div class="navbar-container">

          <div class="navbar">

            <ul class="nav-links">

              <li><a href="" id="userDetailsLink"><i class="ph ph-user"></i>User Details</a></li>
              <li class="active"><a href="" id="salaryHistoryLink"><i class="ph ph-money"></i>Salary History</a></li>
              <li><a href="" id="trainerCalendarLink"><i class="ph ph-barbell"></i>Trainer Calendar</a></li>

            </ul>

          </div>

        </div>

        <div class="user-container">
          <div class="salary-header">
              <button id="addSalaryBtn" class="btn">+ Add Salary Payment</button>
            </div>
            <table id="salaryHistoryTable">
              <thead>
                  <tr>
                      <th>Payment Date</th>
                      <th>Salary</th>
                      <th>Bonus</th>
                      <th>Actions</th>
                  </tr>
              </thead>
              <tbody>
                  <!-- Salary records will be injected here by JavaScript -->
              </tbody>
            </table>
          </div>
      </div>

      <!-- Modal for Adding Salary -->
      <div id="addSalaryModal" class="modal">
          <div class="modal-content">
            <span id="closeModalBtn" class="close-btn">&times;</span>
            <h2>Add Salary Payment</h2>
            <form id="salaryForm">
              <div class="form-group">
                <label for="paymentDate">Payment Date</label>
                <input type="date" id="paymentDate" name="paymentDate" >
              </div>
              <div class="form-group">
                <label for="salary">Salary</label>
                <input type="number" id="salary" name="salary" >
              </div>
              <div class="form-group">
                <label for="bonus">Bonus</label>
                <input type="number" id="bonus" name="bonus" >
              </div>
              <button type="submit" class="btn">Submit</button>
            </form>
            <div id="errorMessages" class="error-messages"></div>
          </div>
        </div>

    </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time();?>"></script>

    <script>    
        document.addEventListener('DOMContentLoaded', () => {
            // Function to get URL parameter by name
            function getUrlParameter(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
            }

            // Get the 'id' parameter (member_id) from the URL
            const trainerId = getUrlParameter('id');

            if (trainerId) {
            // Member ID is available, use it in the navigation link
            document.getElementById('userDetailsLink').href = `<?php echo URLROOT; ?>/manager/trainers/viewTrainer?id=${trainerId}`;
            document.getElementById('salaryHistoryLink').href = `<?php echo URLROOT; ?>/manager/trainers/salaryHistory?id=${trainerId}`;
            document.getElementById('trainerCalendarLink').href = `<?php echo URLROOT; ?>/manager/trainers/trainerCalendar?id=${trainerId}`;
            } else {
            // No member_id in the URL, show a message or handle accordingly
            alert('No member selected.');
            }

            const addSalaryBtn = document.getElementById('addSalaryBtn');
            const modal = document.getElementById('addSalaryModal');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const salaryForm = document.getElementById('salaryForm');
            const errorMessages = document.getElementById('errorMessages');
            
            // Open modal
            addSalaryBtn.addEventListener('click', () => {
                modal.style.display = 'block';
            });

            // Close modal
            closeModalBtn.addEventListener('click', () => {
                modal.style.display = 'none';
                resetModalForm(); // Reset the form when modal is closed
            });

            // Close modal if the user clicks outside the modal content (on the overlay)
            modal.addEventListener('click', (e) => {
                // If the click is on the modal background (not the modal content), close the modal
                if (e.target === modal) {
                    modal.style.display = 'none';
                    resetModalForm(); // Reset the form when modal is closed
                }
            });

            // Reset form function
            function resetModalForm() {
                salaryForm.reset(); // Resets all the form fields to their default value
                errorMessages.innerHTML = ''; // Clear any error messages
            }

            // Handle form submission
            salaryForm.addEventListener('submit', async (e) => {
                e.preventDefault(); // Prevent form from submitting normally
                // Clear any previous error messages
                errorMessages.innerHTML = '';

                const paymentDate = document.getElementById('paymentDate').value;
                const salary = document.getElementById('salary').value;
                const bonus = document.getElementById('bonus').value;

                // Validate form inputs
                if (!paymentDate || !salary) {
                    errorMessages.innerHTML = '<p>Please fill in all fields.</p>';
                    return;
                }

                // If bonus is empty, set it to 0
                const bonusValue = bonus === '' ? 0 : parseFloat(bonus);

                // Validate if bonus is a number (if provided)
                if (isNaN(bonusValue)) {
                    errorMessages.innerHTML = '<p>Please enter a valid number for the bonus.</p>';
                    return;
                }

                try {
                    // Send data using fetch
                    const response = await fetch(`<?php echo URLROOT; ?>/TrainerSalary/addTrainerSalaryPayment`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            trainerId,
                            paymentDate,
                            salary,
                            bonus
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Close modal and refresh salary history
                        modal.style.display = 'none';
                        loadSalaryHistory();
                    } else {
                        errorMessages.innerHTML = `<p>${result.message}</p>`;
                    }
                } catch (error) {
                    errorMessages.innerHTML = '<p>An error occurred while submitting the form. Please try again.</p>';
                }
            });

            // Function to load salary history
            async function loadSalaryHistory() {
              try {
                  const response = await fetch(`<?php echo URLROOT; ?>/TrainerSalary/salaryHistory?id=${trainerId}`);
                  const salaryHistory = await response.json();
                  const historyTable = document.getElementById('salaryHistoryTable').getElementsByTagName('tbody')[0];
                  historyTable.innerHTML = ''; // Clear current table content

                  salaryHistory.forEach(salary => {
                      const row = document.createElement('tr');
                      row.innerHTML = `
                          <td>${salary.payment_date}</td>
                          <td>${salary.salary}</td>
                          <td>${salary.bonus}</td>
                          <td>
                              <button class="delete-btn" data-id="${salary.id}">Delete</button>
                          </td>
                      `;
                      historyTable.appendChild(row);
                  });

                  // Attach event listener to delete buttons
                  const deleteButtons = document.querySelectorAll('.delete-btn');
                  deleteButtons.forEach(button => {
                      button.addEventListener('click', async (e) => {
                          const salaryId = e.target.getAttribute('data-id');
                          if (confirm('Are you sure you want to delete this salary payment?')) {
                              try {
                                  const response = await fetch(`<?php echo URLROOT; ?>/TrainerSalary/deleteTrainerSalaryPayment`, {
                                      method: 'POST',
                                      headers: {
                                          'Content-Type': 'application/x-www-form-urlencoded',
                                      },
                                      body: new URLSearchParams({
                                          id: salaryId
                                      })
                                  });

                                  const result = await response.json();
                                  if (result.success) {
                                      alert('Salary payment deleted successfully.');
                                      loadSalaryHistory(); // Reload the salary history
                                  } else {
                                      alert('Failed to delete salary payment.');
                                  }
                              } catch (error) {
                                  console.error('Error deleting salary payment:', error);
                                  alert('An error occurred while deleting the salary.');
                              }
                          }
                      });
                  });
              } catch (error) {
                  console.error('Error loading salary history:', error);
              }
            }

            // Initial load of salary history
            loadSalaryHistory();
        });
    </script>


  </body>
</html>
