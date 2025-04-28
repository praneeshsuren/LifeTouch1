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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/sidebar-greeting.css?v=<?php echo time();?>" />
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
        <h1>View Trainer</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>
      </div>

 
      <div class="trainers-grid"></div>
    </main>
    <script src="<?php echo URLROOT; ?>/assets/js/member/member-script.js?v=<?php echo time();?>"></script>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        fetchTrainers();
      });

      const today = new Date();
      const month = today.getMonth(); 
      const year = today.getFullYear();

      function fetchTrainers() {
        fetch('<?php echo URLROOT; ?>/member/Trainer/api')
          .then(response => {
            console.log('Response Status:', response.status); 
            return response.json();
          })
          .then(data => {
            console.log('Fetched Data:', data); 
            if (Array.isArray(data) && data.length > 0) {
              const container = document.querySelector('.trainers-grid');
              container.innerHTML = ''; 
              data.forEach(trainer => {
                const trainerDiv = document.createElement('div');
                trainerDiv.classList.add('trainer-card');
                trainerDiv.innerHTML += `
                  <div class="trainer-image">
                    <img src="<?php echo URLROOT; ?>/assets/images/${trainer.image || 'image.png'}" alt="${trainer.first_name}'s image"/>
                  </div>
                  <div class="trainer-info">
                    <h2 class="trainer-name">${trainer.first_name}</h2>
                    <p class="trainer-specialty">Strength & Conditioning</p>
                    <p class="trainer-bio">John specializes in strength training and functional fitness.</p>
                    <div class="trainer-contact">
                      <button class="btn btn-primary">Sechedule Session</button>
                      <button class="btn btn-outline">Profile</button>
                    </div>
                  </div>
                `;

                const scheduleBtn = trainerDiv.querySelector('.btn-primary');
                scheduleBtn.onclick = () =>{
                  window.location.href =`<?php echo URLROOT; ?>/member/Booking?id=${trainer.trainer_id}&month=${month+1}&year=${year}`;
                };

                const viewbutton = trainerDiv.querySelector('.btn-outline');
                viewbutton.onclick = () =>{
                  window.location.href =`<?php echo URLROOT; ?>/member/Trainer/viewTrainer?id=${trainer.trainer_id}`;
                };

                container.appendChild(trainerDiv);
              });
            } else{
              console.log('No trainers found.');
            }
          })
          .catch(error => console.error('Error fetching trainers:', error));
      }
    </script>
  </body>
</html>

