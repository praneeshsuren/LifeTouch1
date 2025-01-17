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
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function(){
            (function() {
                var savedMode = localStorage.getItem('mode');
                if (savedMode === 'dark') {
                document.body.classList.add('dark');
                }
            })();
          });
    </script>
    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>
    <section class="sidebar">
    <?php require APPROOT.'/views/components/member-sidebar.view.php' ?>
    </section>
    <main>
        <div class="title">
            <h1>View Calendar</h1>
            <div class="greeting">
                <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
            </div>
        </div>
        <div class="calendarContainer">
            <div class="calendar-header">
                <i class='prevMonth ph ph-caret-circle-left'></i>
                <div class="monthYear"></div>
                <i class='nextMonth ph ph-caret-circle-right'></i>
            </div>
            <table class="calendar">
                <th>Sun</th>
                <th>Mon</th>
                <th>Tues</th>
                <th>Wed</th>
                <th>Thur</th>
                <th>Fri</th>
                <th>Sat</th>
                <tbody class="calendarBody"></tbody>
            </table>
            <div class="gotoToday">
                <div class="goto">
                    <input type="text" placeholder="mm/yyyy" class="date-input" />
                    <button class="gotoBtn">Go</button>
                </div>
                <button class="todayBtn">Today</button>
            </div>
        </div> 
        <div class="recent-announcements">
            <div class="announcements">
            <h2 style="font-size:1.5rem; font-weight:500; padding-top:1rem;text-align:center">Details</h2>
                <div class="announcement">hi</div>
                <div class="announcement"></div>
            </div>
        </div>
        <div id="bookingModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="title">
                    <h3>Booking : <span id="modalDate"></span></h3>
                </div>
                <div class="timeslots"></div>
                <div class="bookingForm">
                    <form action="<?php echo URLROOT;?>/member/memberTrainerbooking" method="POST">
                        <div class="input">
                            <input type="text" id="loggedMember" readonly name="loggedMember" value="<?php echo htmlspecialchars($member_id); ?>"required>
                            <input type="text" id="selectedTrainerId" readonly name="selectedTrainerId" value="<?php echo htmlspecialchars($trainer_id); ?>"required>
                            <input type="text" id="selectedTimeslotId" readonly name="selectedTimeslotId" required> 
                            <div class="input-container">
                                <label for="selectedDate" class="label"><i class="ph ph-calendar"></i>Date</label>
                                <input type="text" id="selectedDate" readonly name="selectedDate" required>
                            </div>
                            <div class="input-container">
                                <label for="selectedTimeslot" class="label"><i class="ph ph-clock-countdown"></i>Time</label>
                                <input type="text" id="selectedTimeslot" readonly name="selectedTimeslot" placeholder="Select the Timeslot" required>  
                            </div>
                        </div>
                        <div class="book-btn">
                            <button type="submit" id="btnBook" name="submit">Book</button>
                        </div>
                    </form>
                </div> 
            </div>
        </div>
    </main>
    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/member/member-script.js?v=<?php echo time();?>"></script>
    <script src="<?php echo URLROOT; ?>/assets/js/member/calendar.js?v=<?php echo time();?>"></script>
    </body>
</html>

