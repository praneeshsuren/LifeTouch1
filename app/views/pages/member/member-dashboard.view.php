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
        <h1>Dashboard</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>
      </div>
      <div class="insights">
        <div class="members">
          <i class="ph ph-users"></i>
          <div class="middle">
            <div class="left">
              <h3>Total Attendance</h3>
              <h1>20</h1>
            </div>
            <div class="progress">
              <svg>
                <circle cx="38" cy="38" r="38"></circle>
              </svg>
              <div class="number">
                <p>75%</p>
              </div>
            </div>
          </div>
          <small class="text-muted">Last 30 days</small>
        </div>
        <!-- END OF MEMBERS -->

        <div class="bookings">
          <i class="ph ph-chart-bar"></i>
          <div class="middle">
            <div class="left">
              <h3>New workouts</h3>
              <h1>10</h1>
            </div>
            <div class="progress">
              <svg>
                <circle cx="38" cy="38" r="38"></circle>
              </svg>
              <div class="number">
                <p>75%</p>
              </div>
            </div>
          </div>
          <small class="text-muted">Last 30 days</small>
        </div>
          <!-- END OF NEW WORKOUTS -->

          <div class="workouts">
            <i class="ph ph-trend-up"></i>
            <div class="middle">
              <div class="left">
                <h3>Workouts completed</h3>
                <h1>10</h1>
              </div>
              <div class="progress">
                <svg>
                  <circle cx="38" cy="38" r="38"></circle>
                </svg>
                <div class="number">
                  <p>50%</p>
                </div>
              </div>
            </div>
            <small class="text-muted">Last 30 days</small>
          </div>
          <!-- END OF WORKOUTS -->
      </div>
      <div class="recent-announcements">
        <h2>Recent Announcements</h2>
        <div class="announcements">

          <div class="announcement">
            <div class="profile-img">
            <img src="<?php echo URLROOT; ?>/assets/images/image.png" alt="">
            </div>
            <div class="message">
              <p><b>Mark Anderson</b></br>GYM Renovation Notice for all Members and Trainers</p>
              <small class="text-muted">2 hours ago</small>
            </div>
          </div>

          <div class="announcement">
            <div class="profile-img">
            <img src="<?php echo URLROOT; ?>/assets/images/image.png" alt="">
            </div>
            <div class="message">
              <p><b>Mark Anderson</b></br>GYM Renovation Notice for all Members and Trainers</p>
              <small class="text-muted">2 hours ago</small>
            </div>
          </div>

          <div class="announcement">
            <div class="profile-img">
            <img src="<?php echo URLROOT; ?>/assets/images/image.png" alt="">
            </div>
            <div class="message">
              <p><b>Mark Anderson</b></br>GYM Renovation Notice for all Members and Trainers</p>
              <small class="text-muted">2 hours ago</small>
            </div>
          </div>
        </div>      
      </div>

      <div class="chart">
        <div class="chart-header">
          <h2>Bookings</h2>
        </div>
        <div class="chart-container">
          <table class="paymentHistoryTable">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Trainer's detail</th>
                  <th>Time</th>
                </tr>
              </thead>
              <tbody></tbody>
          </table>
        </div>
      </div>
    </main>
    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/member/member-script.js?v=<?php echo time();?>"></script>
    <script>
      const dateToday = new Date().toISOString().split('T')[0];

      document.addEventListener("DOMContentLoaded", () =>{ 
        fetch(`<?php echo URLROOT; ?>/member/index/api`)
          .then(response => {
            console.log('Response Status:', response.status);
            return response.json();
          })
          .then(data =>{
            console.log('bookings:',data);
            markBookings(data.bookings);
          })
          .catch(error => console.error('Error fetching bookings details:', error));
      });

      function markBookings(bookings) {
        const tbody = document.querySelector(".paymentHistoryTable tbody");
        tbody.innerHTML = "";
           
        // Filter bookings for "booked" 
        const filteredBookings = bookings.filter(
          booking => (booking.status === 'booked') && 
            new Date(booking.booking_date).getTime() >= new Date(dateToday).getTime()
        )
        .sort((a, b) => {
          const dateA = new Date(a.booking_date);
          const dateB = new Date(b.booking_date);

          if (dateA.getTime() !== dateB.getTime()) {
            return dateA - dateB; // sort by date ascending
          }

          const timeA = convertTo24hrs(a.timeslot.split(" - ")[0]); // "09:00 AM"
          const timeB = convertTo24hrs(b.timeslot.split(" - ")[0]); // "11:00 AM"

          return timeA.getTime() - timeB.getTime(); 
        });
        console.log(filteredBookings);
        filteredBookings.forEach(booking => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${booking.booking_date}</td>
            <td>${booking.trainer_name}</td>
            <td>${booking.timeslot}</td>
          `;
          tbody.appendChild(tr);
        });
      }

      function convertTo24hrs(time){
        const [hrMin, period] = time.trim().split(' '); //AM,PM
        let [hr, min] =hrMin.split(':');
        hr = parseInt(hr, 10);
        min = parseInt(min, 10);
        let hr24 = hr;
        if(period === 'PM' && hr24 < 12) {
          hr24 +=12;
        } else if (period === 'AM' && hr24 ===12) {
          hr24 = 0;
        }
        return new Date(1970, 0, 1, hr24, min);
      }
    </script>
  </body>
</html>

