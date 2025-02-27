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
            <input type="text" placeholder="Search" class="search-input">
            <button class="add-user-btn">+</button>
          </div>
          
          <div class="user-table-wrapper">
            <table class='user-table'>
              <thead>
                  <tr>
                      <th>Datde</th>
                      <th>Time</th>
                      <th>Trainer</th>
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
                  <form id="holidayForm" method="POST">
                    <div class="select-wrapper" style="display:flex; align-items:center; width:200px; gap:10px;">
                      <label for="holidayDate" class="label" ><i class="ph ph-calendar"></i>Date</label>
                      <input type="date" name="holidayDate" id="holidayDate">
                    </div>
                    <div class="select-wrapper time-wrapper">
                      <label for="holidayTime" class="label"><i class="ph ph-clock"></i>Time</label>
                      <div class="select-btn time-btn">
                        <span>Full Day</span>
                        <i class="ph ph-caret-down"></i>
                      </div>
                      <div class="select-content time-content">
                        <div class="select-search">
                          <input type="text" placeholder="Search">
                        </div>
                        <ul class="select-option time-option"></ul>
                      </div>
                    </div>
                    <div class="select-wrapper trainer-wrapper">
                      <label for="holidayTrainer" class="label"><i class="ph ph-user"></i>Trainer</label>
                      <div class="select-btn trainer-btn">
                        <span>All</span>
                        <i class="ph ph-caret-down"></i>
                      </div>
                      <div class="select-content trainer-content">
                        <div class="select-search">
                          <input type="text" placeholder="Search">
                        </div>
                        <ul class="select-option trainer-option"></ul>
                      </div>
                    </div>
                    <input type="hidden" name="holidayTimeId" id="holidayTimeId" required>
                    <input type="text" name="holidayTrainerValue" id="holidayTrainerValue" required>
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
      document.addEventListener("DOMContentLoaded", () =>{
        let allHolidays = [];
        let timeslots = [];
        let trainers = [];
        let noOftimeslots = 0;
        let noOftrainers = 0;

        fetch('<?php echo URLROOT; ?>/receptionist/holiday/api')
          .then(response => {
              console.log("Response Status:", response.status);
              return response.json();
          })
          .then(data =>{
            console.log('Holidays:', data.holidays);
            if(Array.isArray(data.holidays) && data.holidays.length > 0){
              allHolidays = data.holidays; 
            }
            console.log('Time Slots:', data.timeSlots);
            timeslots = data.timeSlots;
            noOftimeslots = timeslots.length;
            trainers = data.trainers;
            console.log('trainers:', data.trainers);
            noOftrainers = trainers.length;
            
            renderTable(allHolidays, noOftimeslots, noOftrainers);
            dropdownOptions(timeslots,trainers);
          })
          .catch(error => {
            console.error('Error fetching holidays:', error);
            tableBody.innerHTML = `
              <tr>
                <td colspan="11" style="text-align: center;">Error loading data</td>
              </tr>
            `;
          });

          document.getElementById("holidayForm").addEventListener("submit", function (event) {
            event.preventDefault();
              
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
            });

        holidayModal();
        dropdownToggle();
        updateModalHeight();
    
      });

      function renderTable(holidays, noOftimeslots, noOftrainers) {
        tableBody.innerHTML = '';

        if(holidays.length > 0){
          holidays.forEach(holiday => {
            const row = document.createElement('tr');
            row.style.cursor = 'pointer';
    
            let timeDisplay = "N/A";
            holiday.timeslots = holiday.timeslots.split(",").map(slot => slot.trim());
            timeDisplay = holiday.timeslots.length === noOftimeslots ? "Full Day" : holiday.timeslots.join(", ");
    
            let trainerDisplay = "N/A";
            holiday.trainer_ids = holiday.trainer_ids.split(",").map(trainer => trainer.trim());
            trainerDisplay = holiday.trainer_ids.length === noOftrainers ? "All" : holiday.trainer_ids.join(", ");
            
            row.innerHTML = `
                <td class="table-cell">${holiday.date}</td>
                <td class="table-cell">${timeDisplay}</td>
                <td class="table-cell">${trainerDisplay}</td>
                <td class="table-cell">
                  <div class="edit-dlt">
                    <div class="edit"><i class="ph ph-eraser"></i></div>
                    <div class="dlt"><i class="ph ph-trash-simple"></i></div>
                  </div>
                </td> 
            `;

            tableBody.appendChild(row);

            row.querySelector(".edit").addEventListener('click', ()=> {
              openEditModal(holiday);
            });

            document.querySelectorAll('.table-cell').forEach(cell => {
            cell.style.maxWidth = "240px";
            cell.style.overflow = "hidden";
            cell.style.textOverflow = "ellipsis";
            cell.style.wordWrap = "break-word";
            cell.style.whiteSpace = "normal";
        });
          });
        } else {
          console.log('No holidays found.');
          tableBody.innerHTML = `
            <tr>
              <td colspan="11" style="text-align: center;">No Holidays available</td>
            </tr>
          `;
        }
      }

      const addHolidayBtn = document.querySelector(".add-user-btn");
      const modal = document.getElementById("bookingModal");
      const closeModal = document.querySelector(".bookingModalClose");
      const modalContent = document.querySelector(".bookingModal-content");

      function holidayModal(){
        addHolidayBtn.addEventListener('click', function() {
          resetModalFields();
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

      const timeWrapper = document.querySelector(".time-wrapper");
      const trainerWrapper = document.querySelector(".trainer-wrapper");
      const timeBtn = document.querySelector(".time-btn");
      const trainerBtn = document.querySelector(".trainer-btn");
      const timeList = document.querySelector(".time-option");
      const trainerList = document.querySelector(".trainer-option");
      const timeText = document.querySelector(".time-btn span");
      const trainerText = document.querySelector(".trainer-btn span");
      const holidayTimeId = document.getElementById("holidayTimeId");
      const holidayTrainerValue = document.getElementById("holidayTrainerValue");

      function dropdownOptions(timeslots,trainers){
        let timeHtml = `<li data-id="ALL">Full Day</li>`;
        timeslots.forEach(slot =>{
          timeHtml += `<li data-id="${slot.id}">${slot.slot}</li>`;
        });
        timeList.innerHTML = timeHtml;

        let trainerHTML = `<li data-id="ALL">All</li>`; 
        trainers.forEach(trainer => {
          trainerHTML += `<li data-id="${trainer.trainer_id}">${trainer.trainer_id}</li>`;
        });
        trainerList.innerHTML = trainerHTML;

        
        document.querySelectorAll(".time-option li").forEach(item =>{
          item.addEventListener('click', function(){
            timeText.textContent = this.textContent;
            holidayTimeId.value = this.getAttribute("data-id");
            timeWrapper.classList.remove("active");
          });
        });

        document.querySelectorAll(".trainer-option li").forEach(item => {
            item.addEventListener("click", function () {
              trainerText.textContent = this.textContent;
              holidayTrainerValue.value = this.getAttribute("data-id");
              trainerWrapper.classList.remove("active");
            });
        });
      }

      function dropdownToggle() {
        timeBtn.addEventListener('click', () => {
          trainerWrapper.classList.remove("active");
          timeWrapper.classList.toggle("active");
        });

        trainerBtn.addEventListener('click', () => {
          timeWrapper.classList.remove("active");
          trainerWrapper.classList.toggle("active");
        });

        document.addEventListener("click", (event) => {
          if (!modal.contains(event.target)) {
            timeWrapper.classList.remove("active");
            trainerWrapper.classList.remove("active");
          }
        });
      }

      function updateModalHeight() {
        timeWrapper.addEventListener('click', () =>{
          if (timeWrapper.classList.contains("active")) {
            modalContent.style.height = "500px";
          } else {
              modalContent.style.height = "330px";
          }
        });

        trainerWrapper.addEventListener('click', () => {
          if (trainerWrapper.classList.contains("active")) {
            modalContent.style.height = "500px";
          } else {
              modalContent.style.height = "330px";
          }
        });
      }

      function resetModalFields() {
        document.getElementById("holidayDate").value = '';
        document.getElementById("holidayTimeId").value = '';
        document.getElementById("holidayTrainerValue").value = '';

        const timeText = document.querySelector(".time-btn span");
        const trainerText = document.querySelector(".trainer-btn span");

        timeText.textContent = "Full Day";
        trainerText.textContent = "All";
      }

    </script>
