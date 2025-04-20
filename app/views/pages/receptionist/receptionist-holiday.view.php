<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- STYLESHEET -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/receptionist-style.css?v=<?php echo time();?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>
    <section class="sidebar">
        <?php require APPROOT.'/views/components/receptionist-sidebar.view.php' ?>
    </section>

    <main>
      <div class="title">
        <h1>Holidays</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>
      </div>
      <div class="table-container">
          <div class="filters"></div>

          <div class="user-table-header">
            <input type="text" placeholder="Search by Date" class="search-input">
            <button class="add-user-btn">+</button>
          </div>
          
          <div class="user-table-wrapper">
            <table class='user-table'>
              <thead>
                  <tr>
                      <th>Date</th>
                      <th>Reason</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
          <div id="bookingModal" class="bookingModal">
            <div class="bookingModal-content" >
                <div class="bookingModalClose">&times;</div>
                <div class="bookingModal-body" style = "color:black">
                  <form id="holidayForm">
                    <div class="select-wrapper" style="display:flex; align-items:center; width:200px; gap:10px;">
                      <label for="holidayDate" class="label" ><i class="ph ph-calendar"></i>Date</label>
                      <input type="date" name="holidayDate" id="holidayDate">
                    </div>
                    <div class="select-wrapper">
                      <label for="holidayReason" class="label" ><i class="ph ph-pencil-simple-line"></i>Reason</label>
                      <textarea name="holidayReason" id="holidayReason" rows="6" style="width: 100%; height: 130px; resize: none;"></textarea>
                    </div>
                    <div class="book-btn">
                      <button type="submit" id="submitBtn" name="submit">Add</button>
                    </div>
                  </form>
                </div>
            </div>
          </div>
      </div>
      </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/receptionist-script.js?v=<?php echo time();?>"></script>
    <script>
      const tableBody = document.querySelector('.user-table tbody');
      const searchInput = document.querySelector(".search-input");
      document.addEventListener("DOMContentLoaded", () =>{
        let allHolidays = [];
        let bookings = [];

        fetch('<?php echo URLROOT; ?>/receptionist/holiday/api')
          .then(response => {
              console.log("Response Status:", response.status);
              return response.json();
          })
          .then(data =>{
            console.log('Holidays:', data.holidays);
            console.log('Bookings:', data.bookings);
            allHolidays = data.holidays; 
            bookings = data.bookings;
            renderTable(allHolidays);
          })
          .catch(error => {
            console.error('Error fetching holidays:', error);
            tableBody.innerHTML = `
              <tr>
                <td colspan="11" style="text-align: center;">Error loading data</td>
              </tr>
            `;
          });
          //search
          searchInput.addEventListener("input", function() {
            const query = searchInput.value.trim().toLowerCase();
            const filteredHolidays = allHolidays.filter(holiday => holiday.date.includes(query));
            renderTable(filteredHolidays);
          });

          //submit
          document.getElementById("holidayForm").addEventListener("submit", function (event) {
            event.preventDefault();

            const holidayDateInput = document.getElementById("holidayDate").value;
            if (!holidayDateInput) {
                alert("Date is required.");
                return;
            }
            const today = new Date().toISOString().split("T")[0];
            if (holidayDateInput < today) {
                alert("You cannot add a holiday for a past date.");
                return;
            }
            const conflictingBooking = bookings.find(booking => booking.booking_date === holidayDateInput);
            if (conflictingBooking) {
              const userConfirmed = window.confirm("There is already a booking for this date. Do you want to delete the bookings and add the holiday?");
              if (!userConfirmed) {
                  return; // If user cancels, do not proceed
              }
              // update the bookings status rejected for the given date before adding the holiday
              const conflictingBookings = bookings.filter(b =>b.booking_date === holidayDateInput);
              const reject = "rejected";

              const rejectPromises = conflictingBookings.map(b => {
                return fetch('<?php echo URLROOT; ?>/receptionist/holiday/conflict',{
                  method :'POST',
                  headers: {
                    "Content-type": "application/x-www-form-urlencoded",
                  },
                  body: `id=${encodeURIComponent(b.id)}&status=${encodeURIComponent(reject)}`,
                });
                console.log("do");
              });

               // Wait for all rejections to complete before adding the holiday
              Promise.all(rejectPromises)
                  .then(responses => 
                    Promise.all(responses.map(async r => {
                      const text = await r.text(); // Get raw text
                      try {
                        return JSON.parse(text);   // Try to parse JSON
                      } catch (e) {
                        console.error("Invalid JSON response (probably HTML):", text);
                        throw new Error("Invalid response format");
                      }
                    }))
                  )
                  .then(() => {
                    const formData = new FormData(document.getElementById("holidayForm"));
                    return fetch('<?php echo URLROOT; ?>/receptionist/holiday/add', {
                      method: "POST",
                      body: formData
                    });
                  })
                  .then(response => response.json())
                  .then(result => {
                    if (result.success) {
                      alert("Holiday added successfully!");
                      location.reload();
                    } else {
                      alert("Error: " + result.message);
                    }
                  })
                  .catch(error => console.error("Error processing:", error));
              } else {
              const formData = new FormData(this);
              fetch('<?php echo URLROOT; ?>/receptionist/holiday/add', {
                  method: "POST",
                  body: formData
              })
              .then(response => response.json())
              .then(result => {
                if (result.success) {
                  alert("Holiday added successfully!");
                  location.reload();
                } else {
                  alert("Error: " + result.message);
                }
              })
              .catch(error => console.error("Error inserting holiday:", error));
            }
          });
        
        holidayModal();
      });

      function renderTable(holidays) {
        tableBody.innerHTML = '';

        const dateToday = new Date();

        const future =[];
        const past =[];

        holidays.forEach(holiday => {
          const holidayDate = new Date(holiday.date);

          if (holidayDate < dateToday){
            past.push(holiday);
          } else{
            future.push(holiday);
          }
        });

        if(holidays.length > 0){
          const append = (holidayList) => {
            holidayList.forEach(holiday => {
              const row = document.createElement('tr');
              row.style.cursor = 'pointer';

              let reason = "N/A";
              reason = holiday.reason === null ? reason : holiday.reason;

              row.innerHTML = `
                  <td class="table-cell">${holiday.date}</td>
                  <td class="table-cell">${reason}</td>
                  <td class="table-cell">
                    <div class="edit-dlt">
                      <div class="edit" onclick="editHoliday('${holiday.id}', '${holiday.date}', '${reason}')"><i class="ph ph-eraser"></i></div>
                      <div class="dlt" onclick="deleteHoliday('${holiday.id}')"><i class="ph ph-trash-simple"></i></div>
                    </div>
                  </td> 
              `;

              tableBody.appendChild(row);
            });
          };
          append(future);

          if(past.length > 0){
            const pastHeading = document.createElement('tr');
            pastHeading.innerHTML = `
              <td colspan="11" style="font-weight: bold; background: #f9f9f9;">Past Holidays</td>
            `; 
            tableBody.append(pastHeading);
            append(past);
          }
        } else {
          console.log('No holidays found.');
          tableBody.innerHTML = `
            <tr>
              <td colspan="11" style="text-align: center;">No Holidays available</td>
            </tr>
          `;
        }
      }

      function deleteHoliday(id){
        if (!confirm("Are you sure you want to delete this holiday?")) return;

        fetch('<?php echo URLROOT; ?>/receptionist/holiday/delete', {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `id=${encodeURIComponent(id)}`,
        })
        .then(response => response.json())
        .then(result => {
            if (!result.success) {
                alert("Holiday deleted successfully!");
                location.reload();
            } else {
                alert("Error: " + result.message);
            }
        })
        .catch(error => console.error("Error deleting holiday:", error));
      }

      const addHolidayBtn = document.querySelector(".add-user-btn");
      const modal = document.getElementById("bookingModal");
      const closeModal = document.querySelector(".bookingModalClose");
      const modalContent = document.querySelector(".bookingModal-content");

      function editHoliday(id, date, currentReason){
        modal.style.display= "block";

        const holidayReason = document.getElementById("holidayReason");
        holidayReason.value = currentReason || "";

        const dateInput = document.getElementById("holidayDate");
        dateInput.value = date;
        dateInput.disabled = true; 

        const submitBtn = document.getElementById("submitBtn");
        submitBtn.innerText = "Update";

        submitBtn.onclick = function(event) {
            event.preventDefault(); 
            const newReason = holidayReason.value.trim();
            
            if (!confirm("Are you sure you want to update this holiday's reason?")) return;
              fetch('<?php echo URLROOT; ?>/receptionist/holiday/edit', {
                method :'POST',
                headers: {
                  "Content-type": "application/x-www-form-urlencoded",
                },
                body: `id=${encodeURIComponent(id)}&reason=${encodeURIComponent(newReason)}`,
              })
              .then(response => response.json())
              .then(result =>{
                console.log(result);
                if (!result.success) {
                    alert("Holiday reason updated successfully!");
                    modal.style.display = "none";
                    location.reload();
                } else {
                    alert("Error: " + result.message);
                }
              })
              .catch(error => console.error("Error updating holiday:", error));

        };
      }

      function holidayModal(){
        addHolidayBtn.addEventListener('click', function() {
          document.getElementById("holidayForm").reset();
          modal.style.display = "block";
        });

        closeModal.addEventListener('click',function() {
          modal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
          if (event.target === modal) {
            modal.style.display = 'none';
          }
        });
      }

    </script>
  </body>
</html>
