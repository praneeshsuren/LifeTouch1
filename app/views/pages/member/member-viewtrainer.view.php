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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title><?php echo APP_NAME; ?></title>
</head>

<body>

    <section class="sidebar">
    <?php require APPROOT.'/views/components/member-sidebar.view.php' ?>
    </section>

    <main>
        <div class="title">
            <h1>Trainer Details</h1>
            <div class="greeting">
                <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
            </div>
        </div>
        <div class="trainerviewbtnBox"></div>         
    </main>
    <script src="<?php echo URLROOT; ?>/assets/js/member/member-script.js?v=<?php echo time();?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const trainerId = urlParams.get('id'); 
            if (trainerId) {
                const apiUrl = `<?php echo URLROOT; ?>/member/Trainer/viewTrainerapi?id=${trainerId}`;  // This will be replaced by PHP when rendering the page
            console.log("API URL:", apiUrl);
            fetch(apiUrl)
                    .then(response => {
                        console.log('Response Status:', response.status); // Log response status
                        return response.json();
                    })
                    .then(trainer => {
                        console.log('Fetched Data:', trainer); 
                        fetchTrainerDetails(trainer, trainerId);
                    })
                    .catch(error => console.error('Error fetching trainer details:', error));
            }
        });

        function fetchTrainerDetails(trainer, trainerId) {
            const container = document.querySelector('.trainerviewbtnBox');
            container.innerHTML = ''; 

            const bookigbuttonDiv = document.createElement('div');
            bookigbuttonDiv.innerHTML=`
                <button class="trainerviewbtn-Bookreservationbtn" style="float: right; margin-top: -10px;margin-bottom:3px;">Booking Reservation</button>
            `;
                        
            const bookingbutton = bookigbuttonDiv.querySelector('.trainerviewbtn-Bookreservationbtn');
            bookingbutton.onclick = () =>{
                const today = new Date();
                const month = today.getMonth(); // Zero-based index
                const year = today.getFullYear();
                window.location.href =`<?php echo URLROOT; ?>/member/Booking?id=${trainerId}&month=${month+1}&year=${year}`;
            };
            container.appendChild(bookigbuttonDiv);

            // Create profile section
            const profileDiv = document.createElement('div');
            profileDiv.classList.add('trainerviewbtn-profile');
            const profileImgDiv = document.createElement('div');
            profileImgDiv.classList.add('trainerviewbtn-profile-img');
            profileImgDiv.innerHTML = `
                <img src="<?php echo URLROOT; ?>/assets/images/${trainer.image || 'image.png'}" alt="${trainer.first_name}'s image" class="trainer-image"/>
            `;

            const profileDetailsDiv = document.createElement('div');
            profileDetailsDiv.classList.add('trainerviewbtn-profile-detail');
            profileDetailsDiv.innerHTML = `
                <h2>${trainer.first_name} ${trainer.last_name}</h2>
                <p>Personal trainer</p>
            `;

            profileDiv.appendChild(profileImgDiv);
            profileDiv.appendChild(profileDetailsDiv);
            container.appendChild(profileDiv);

            // Create profile table
            const tableContainer = document.createElement('div');
            tableContainer.classList.add('trainerviewbtn-profileTable-container');
            const table = document.createElement('table');

            const rows = [
                { label: 'Age', value: `${calculateAge(trainer.date_of_birth)} years` },
                { label: 'NIC No', value: trainer.NIC_no },
                { label: 'Email', value: trainer.email_address },
                { label: 'Gender', value: trainer.gender },
                { label: 'Contact', value: trainer.contact_number },
                { label: 'Home address', value: trainer.home_address },
                { label: 'Specification', value: 'Specialized in weight training' },
            ];
            rows.forEach(row => {
                const tr = document.createElement('tr');
                const tdLabel = document.createElement('td');
                tdLabel.className = 'first';
                tdLabel.innerHTML = `<h4>${row.label}</h4>`;
                const tdValue = document.createElement('td');
                tdValue.textContent = row.value;
                tr.appendChild(tdLabel);
                tr.appendChild(tdValue);
                table.appendChild(tr);
            });

            tableContainer.appendChild(table);
            container.appendChild(tableContainer);
        }
        function calculateAge(dateOfBirth) {
            const birthDate = new Date(dateOfBirth);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age -= 1;
            }
            return age;
        }
    </script>
</body>
</html>