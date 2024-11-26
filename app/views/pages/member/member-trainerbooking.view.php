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
            <h1>Book Reservations</h1>
            <div class="greeting">
            <span class="bell-container"><i class="ph ph-bell notification"></i></span>
            <h2>Hi, John!</h2>
            </div>
        </div>
        <div class="bookingBox">
            <div class="calendar-header">
                <div class="prevMonth">
                    <i class="ph ph-caret-circle-left"></i>
                </div>
                <div class="monthYear"></div>
                <div class="nextMonth">
                    <i class="ph ph-caret-circle-right"></i>
                </div>
            </div>
            <table class="calendar">
                <thead>
                    <tr>
                        <th>Sun</th>
                        <th>Mon</th>
                        <th>Tue</th>
                        <th>Wed</th>
                        <th>Thu</th>
                        <th>Fri</th>
                        <th>Sat</th>
                    </tr>
                </thead>
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
    </main>
    <div id="bookingForm"></div>
    <div id="addBooking">
        <h2>Add Booking</h2>
        <input type="text" id="bookingtitle" placeholder="Booking title"/>
        <button id="btnBook">Book</button>
        <button class="btnClose">Cancel</button>
    </div>
    <div id="viewBooking">
        <p id="bookingText">sample</p>
        <button id="btnDelete">Delete</button>
        <button class="btnClose">Close</button>
    </div>
    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/member/calendar.js?v=<?php echo time();?>"></script>
  </body>
</html>
