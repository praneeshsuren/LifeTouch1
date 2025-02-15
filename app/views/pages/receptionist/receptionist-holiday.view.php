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
                      <th>Date</th>
                      <th>Time</th>
                      <th>Trainer</th>
                  </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>

          <div id="bookingModal" class="bookingModal">
            <div class="bookingModal-content" >
                <div class="bookingModalClose">&times;</div>
                <div class="bookingModal-body" style = "color:black">
                  <form>
                    <div class="select-wrapper" style="display:flex; align-items:center; width:200px; gap:10px;">
                      <label for="holidayDate" class="label" ><i class="ph ph-calendar"></i>Date</label>
                      <input type="date" id="holidayDate" required>
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
                    <div class="holidayTime"></div>
                    <div class="holidayTrainer"></div>
                    <div class="book-btn">
                      <button type="submit" id="btnBook" name="submit">Add</button>
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

        fetch('<?php echo URLROOT; ?>/receptionist/holiday/api')
          .then(response => {
              console.log("Response Status:", response.status);
              return response.json();
          })
          .then(data =>{
            console.log('Holidays:', data.holidays);
            if(Array.isArray(data.holidays) && data.holidays.length > 0){
              allHolidays = data.holidays; 
              renderTable(allHolidays);
            }
            console.log('Time Slots:', data.timeSlots);
            if(Array.isArray(data.timeSlots) && data.timeSlots.length > 0){
                timeslots = data.timeSlots;
                let allSlots = timeslots.map(timeslots => timeslots.slot);
                allSlots = ["Full Day", ...allSlots];
                displaytimeslots(allSlots);
            } else {
              console.log('No timeslots found.');
            }
            console.log('trainers:',data.trainers);
            if(Array.isArray(data.trainers) && data.trainers.length > 0){
              trainers = data.trainers;
              let allTrainerIds = trainers.map(trainer => trainer.trainer_id);
              allTrainerIds = ["All", ...allTrainerIds];
              displayTrainers(allTrainerIds);
            } else {
              console.log('No ttrainers found.');
            }
          })
          .catch(error => {
            console.error('Error fetching holidays:', error);
            tableBody.innerHTML = `
              <tr>
                <td colspan="11" style="text-align: center;">Error loading data</td>
              </tr>
            `;
          });

        holidayModal();
        dropdownToggle();
        updateModalHeight()

      });

      function renderTable(holidays){
        tableBody.innerHTML = '';

        if(holidays.length > 0){
          holidays.forEach(holiday => {
            const row = document.createElement('tr');
            row.style.cursor = 'pointer';

            row.innerHTML = `
              <td>${holiday.date}</td>
              <td>${holiday.slot}</td>
              <td>${holiday.trainer_id}</td>
            `;

            tableBody.appendChild(row);
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
      const timeBtn = document.querySelector(".time-btn");
      const timeOption = document.querySelector(".time-option")
      const timeText = document.querySelector(".time-btn span");

      function displaytimeslots(allSlots){
        const searchInput = document.querySelector(".select-search input");
        if(!timeOption) return;

        timeOption.innerHTML = "";

        if(allSlots.length > 0){
          allSlots.forEach(slot => {
            let li = document.createElement("li");
            li.textContent =slot;
            li.addEventListener('click', () => {
              timeText.textContent = slot;
              timeWrapper.classList.remove("active");
            });
            timeOption.appendChild(li);
          });
        }

        searchInput.addEventListener("keyup", () =>{
          let searchValue = searchInput.value.toLowerCase();
          let filteredTimeslots = allSlots.filter(data => {
            return data.toLowerCase().includes(searchValue);
          });

          timeOption.innerHTML = "";

          if (filteredTimeslots.length === 0){
            let li = document.createElement("li");
            li.textContent = "Timeslot Not Found";
            li.style.pointerEvents = "none"; 
            timeOption.appendChild(li);
          } else{
            filteredTimeslots.forEach(filteredTimeslot => {
            let li = document.createElement("li");
            li.textContent = filteredTimeslot;
            li.addEventListener('click', () => {
              timeText.textContent = filteredTimeslot;
              timeWrapper.classList.remove("active");
            });
            timeOption.appendChild(li);
          });
          }          
        });
      }

      const trainerWrapper = document.querySelector(".trainer-wrapper");
      const trainerBtn = document.querySelector(".trainer-btn");
      const trainerOption = document.querySelector(".trainer-option");
      const trainerText = document.querySelector(".trainer-btn span");

      function displayTrainers(allTrainerIds){
        const searchInput = document.querySelector(".trainer-content .select-search input");
        if (!trainerOption) return;

        trainerOption.innerHTML = "";

        if (allTrainerIds.length > 0) {
          allTrainerIds.forEach(trainerId => {
            let li = document.createElement("li");
            li.textContent = trainerId;
            li.addEventListener("click", () => {
              trainerText.textContent = trainerId;
              trainerWrapper.classList.remove("active");
            });
            trainerOption.appendChild(li);
          });
        }

        searchInput.addEventListener("keyup", () => {
          let searchValue = searchInput.value.toLowerCase();
          let filteredTrainers = allTrainerIds.filter(data => {
            return data.toLowerCase().includes(searchValue);
          });

          trainerOption.innerHTML = "";

          if(filteredTrainers.length === 0){
            let li = document.createElement("li");
            li.textContent = "Trainer Not Found";
            li.style.pointerEvents = "none"; 
            trainerOption.appendChild(li);
          } else{
            filteredTrainers.forEach(filteredTrainerId => {
            let li = document.createElement("li");
            li.textContent = filteredTrainerId;
            li.addEventListener("click", () => {
              trainerText.textContent = filteredTrainerId;
              trainerWrapper.classList.remove("active");
            });
            trainerOption.appendChild(li);
          });
          }
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
          console.log(modalContent.style.height);
        });

        trainerWrapper.addEventListener('click', () => {
          if (trainerWrapper.classList.contains("active")) {
            modalContent.style.height = "500px";
          } else {
              modalContent.style.height = "330px";
          }
          console.log(modalContent.style.height);
        });
      }

    </script>
