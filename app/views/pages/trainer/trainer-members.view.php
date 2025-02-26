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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/trainer-style.css?v=<?php echo time();?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/sidebar-greeting.css?v=<?php echo time();?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>

    <section class="sidebar">
        <?php require APPROOT.'/views/components/trainer-sidebar.view.php' ?>
    </section>

    <main>
      <div class="title">
        
        <h1>Members</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>

      </div>

      <div class="user-table-container">

          <div class="filters">
            <button class="filter active">All Users</button>
            <button class="filter">Active Users</button>
            <button class="filter">Inactive Users</button>
          </div>

          <div class="user-table-header">
            <input type="text" placeholder="Search" class="search-input">
          </div>
          
          <div class="user-table-wrapper">
            <table class='user-table'>
              <thead>
                  <tr>
                      <th>Member Id</th>
                      <th>Profile Picture</th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>NIC Number</th>
                      <th>Gender</th>
                      <th>Date of Birth</th>
                      <th>Age</th>
                      <th>Height (m)</th>
                      <th>Weight (kg)</th>
                      <th>Home Address</th>
                      <th>Email Address</th>
                      <th>Contact Number</th>
                  </tr>
              </thead>
              <tbody>
                <!-- Data will be dynamically populated by JS -->
              </tbody>
            </table>
          </div>

      </div>
      
      </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/trainer-script.js?v=<?php echo time();?>"></script>

    <script>
document.addEventListener('DOMContentLoaded', () => {
  const tableBody = document.querySelector('.user-table tbody');

  // Fetch data from the API
  fetch('<?php echo URLROOT; ?>/trainer/members/api')
    .then(response => {
      console.log('Response Status:', response.status); // Log response status
      return response.json();
    })
    .then(data => {
      console.log('Fetched Data:', data); // Log the data received
      if (Array.isArray(data) && data.length > 0) {
        data.forEach(member => {
          console.log('Member:', member); // Log each member data
          const row = document.createElement('tr');
          row.style.cursor = 'pointer';
          row.onclick = () => {
            window.location.href = `<?php echo URLROOT; ?>/trainer/members/viewMember?id=${member.member_id}`;
          };

          row.innerHTML = `
            <td>${member.member_id}</td>
            <td>
              <img src="<?php echo URLROOT; ?>/assets/images/member/${member.image || 'default-placeholder.jpg'}" alt="Member Picture" class="user-image">
            </td>
            <td>${member.first_name}</td>
            <td>${member.last_name}</td>
            <td>${member.NIC_no}</td>
            <td>${member.gender}</td>
            <td>${member.date_of_birth}</td>
            <td>${calculateAge(new Date(member.date_of_birth))}</td>
            <td>${member.height}</td>
            <td>${member.weight}</td>
            <td>${member.home_address}</td>
            <td>${member.email_address}</td>
            <td>${member.contact_number}</td>
          `;

          tableBody.appendChild(row);
        });
      } else {
        console.log('No members found.');
        tableBody.innerHTML = `
          <tr>
            <td colspan="11" style="text-align: center;">No Members available</td>
          </tr>
        `;
      }
    })
    .catch(error => {
      console.error('Error fetching members:', error); // Log the error
      tableBody.innerHTML = `
        <tr>
          <td colspan="11" style="text-align: center;">Error loading data</td>
        </tr>
      `;
    });
});

function calculateAge(dob) {
  const today = new Date();
  const birthDate = new Date(dob);
  let age = today.getFullYear() - birthDate.getFullYear();
  const monthDiff = today.getMonth() - birthDate.getMonth();
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }
  return age;
}

</script>


  </body>
</html>
