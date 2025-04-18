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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/receptionist-style.css?v=<?php echo time();?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>

    <section class="sidebar">
        <?php require APPROOT.'/views/components/receptionist-sidebar.view.php' ?>
    </section>
    
    <main>
      <div class="title">
        <h1>Payment</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>
      </div>

      <div class="table-container">
        <div class="user-table-wrapper">
            <table class='user-table'>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Member Id</th>
                        <th>Member Profile</th>
                        <th>Package Name</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
      </div>
    
    </main>

    <!-- SCRIPT -->
    <script>
        document.addEventListener('DOMContentLoaded', () =>{
            const searchInput = document.querySelector('.search-input'); 
            let payment = [];

            fetch('<?php echo URLROOT; ?>/receptionist/payment/api')
                .then(response => {
                    console.log('Response Status:', response.status); // Log response status
                    return response.json();
                })
                .then(data =>{
                    console.log('payment:',data.payment);
                    if(Array.isArray(data.payment) && data.payment.length > 0){
                        payment = data.payment;
                        renderTable(payment);
                    }
                })
                .catch(error => {
                    console.error('Error fetching payment:', error); // Log the error
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="11" style="text-align: center;">Error loading data</td>
                        </tr>
                    `;
                });

            searchInput.addEventListener('input', search);

            function search(){
                let searchQuery = searchInput.value.toLowerCase();

                let filteredResults = payment.filter(payment => {
                    const formattedAmount = `rs ${parseFloat(payment.amount).toFixed(2)}`;

                    return payment.created_at.toLowerCase().includes(searchQuery) ||
                    payment.member_id.toLowerCase().includes(searchQuery) ||
                    payment.member_name.toLowerCase().includes(searchQuery) ||
                    formattedAmount.includes(searchQuery) ||
                    payment.package_name.toLowerCase().includes(searchQuery);
                });

                renderTable(filteredResults);
            }

            function renderTable(payments){
                const tableBody = document.querySelector('.user-table tbody');
                tableBody.innerHTML = '';

                if(payments.length > 0){
                    payments.forEach(payment => {
                        const row = document.createElement('tr');
                        row.style.cursor = 'pointer';
        
                        row. innerHTML = `
                            <td>${payment.created_at}</td>
                            <td>${payment.member_id}</td>
                            <td>
                                <div>
                                    <img src="<?php echo URLROOT; ?>/assets/images/member/${payment.member_image || 'default-placeholder.jpg'}" alt="member Picture" class="user-image">
                                    <div>${payment.member_name}</div>
                                </div>
                            </td>
                            <td>${payment.package_name}</td>
                            <td>Rs ${payment.amount}.00</td>
                        `;

                        tableBody.appendChild(row);

                    });
                } else {
                    console.log('No payments found.');
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="11" style="text-align: center;">No payments available</td>
                        </tr>
                        `;
                }
            }
        });
    </script>
    <script src="<?php echo URLROOT; ?>/assets/js/receptionist-script.js?v=<?php echo time();?>"></script>
   
  </body>
</html>

