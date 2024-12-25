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
        <div class="bookingBox">
            <?php echo $calendar;?>
            <div class="gotoToday">
                <div class="goto">
                    <input type="text" placeholder="mm/yyyy" class="date-input" />
                    <button class="gotoBtn">Go</button>
                </div>
                <button class="todayBtn">Today</button>
            </div>
        </div> 
        <div id="dateModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Date Details</h2>
                <p id="modalDate"></p>
                <div>
                    <?php if(!empty($time_slots)): ?>
                        <?php foreach($time_slots as $slot): ?>
                            <button class="timeslot">
                                <?php echo htmlspecialchars($slot->slot); ?>
                            </button>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/member/member-script.js?v=<?php echo time();?>"></script>
    <script>
    // Helper to get query params
    function updateCalendarParams(month, year) {
        const url = new URL(window.location.href);
        url.searchParams.set('month', month);
        url.searchParams.set('year', year);
        window.location.href = url.toString();
    }

    // Handle Today button
    document.querySelector('.todayBtn').addEventListener('click', () => {
        const today = new Date();
        const currentMonth = today.getMonth() + 1; // Months are 0-based
        const currentYear = today.getFullYear();
        updateCalendarParams(currentMonth, currentYear);
    });

    // Handle Go button
    document.querySelector('.gotoBtn').addEventListener('click', () => {
        const dateInput = document.querySelector('.date-input').value.trim();
        const [month, year] = dateInput.split('/').map(Number);

        if (
            !isNaN(month) &&
            !isNaN(year) &&
            month >= 1 &&
            month <= 12 &&
            year >= 1000 &&
            year <= 9999
        ) {
            updateCalendarParams(month, year);
        } else {
            alert('Invalid date format. Please enter in mm/yyyy format.');
        }
    });
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('dateModal');
        const modalDate = document.getElementById('modalDate');
        const closeBtn = document.querySelector('.close');

        // Add event listener to all clickable calendar cells
        document.querySelectorAll('.calendar .clickable').forEach(cell => {
            cell.addEventListener('click', function () {
                const selectedDate = this.getAttribute('data-date');
                modalDate.textContent = `Selected Date: ${selectedDate}`;
                modal.style.display = 'block';
            });
        });

        // Close the modal when clicking on the 'x' button
        closeBtn.addEventListener('click', function () {
            modal.style.display = 'none';
        });
    });
</script>

    </body>
</html>

