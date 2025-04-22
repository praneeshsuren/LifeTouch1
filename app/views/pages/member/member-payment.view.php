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
    <!-- STRIPE -->
    <script src="https://js.stripe.com/v3/"></script>

    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>

    <section class="sidebar">
    <?php require APPROOT.'/views/components/member-sidebar.view.php' ?>
    </section>
    
    <main>
      <div class="title">
        <h1>Payment</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>
      </div>
      <div class="paymentBox">
        <button id="payBtn" class="trainerviewbtn-Bookreservationbtn" style="float: left; margin-top: 5px;margin-bottom:3px; width:100px;" onclick="window.location.href='<?php echo URLROOT; ?>/member/membershipPlan'">Membership Plan</button>
        <button id="payBtn" class="trainerviewbtn-Bookreservationbtn" style="float: right; margin-top: 5px;margin-bottom:3px; width:100px;">Pay</button>

        <!-- Past Payment Details -->
        <div class="payment-history">
          <h1 class="payment-title">Payment Details</h1>
          <table class="paymentHistoryTable">
            <thead>
              <tr>
                <th>Date</th>
                <th>Email</th>
                <th>Package detail</th>
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
      const createPaymentURL = "<?php echo URLROOT; ?>/member/createPayment";
      const savePayment = "<?php echo URLROOT; ?>/member/payment/add";
      window.STRIPE_PUBLIC_KEY = "<?php echo STRIPE_PUBLISHABLE_KEY; ?>";
      document.addEventListener("DOMContentLoaded", () =>{ 
        fetch(`<?php echo URLROOT; ?>/member/payment/api`)
          .then(response => {
              console.log('Response Status:', response.status);
              return response.json();
            })
            .then(data =>{
              console.log('payments:',data.payment);
              const payment = Array.isArray(data.payment) ? data.payment : [];
              paymentTable(payment);
            })
            .catch(error => console.error('Error fetching payments details:', error));

          paymentModal();
      });

      function paymentTable(payment){
        tbody = document.querySelector(".paymentHistoryTable tbody");
        tbody.innerHTML = "";

        if (payment.length === 0) {
          const tr = document.createElement("tr");
          tr.innerHTML = `<td colspan="4" style="text-align: center;">No payment records found.</td>`;
          tbody.appendChild(tr);
          return; 
        }

        payment.forEach(payment =>{
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${payment.created_at}</td>
            <td>${payment.email}</td>
            <td>${payment.package_name}</td>
            <td>Rs ${payment.amount}.00</td>`;

          tbody.appendChild(tr);
        });
      }

      function paymentModal(){
        const payBtn = document.getElementById('payBtn');
        const modal = document.getElementById("bookingModal");
        const closeModal = document.querySelector(".close");
        const modalContent = document.querySelector(".modal-content");

        const paymentDateInput = document.getElementById("paymentDate");
        const today = new Date().toISOString().split('T')[0]; // format: YYYY-MM-DD
        paymentDateInput.value = today;

        payBtn.addEventListener('click', function () {
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
    </script>
    <script src="<?php echo URLROOT; ?>/assets/js/payment-script.js?v=<?php echo time();?>"></script>
    <script src="<?php echo URLROOT; ?>/assets/js/member/member-script.js?v=<?php echo time();?>"></script>

  </body>
</html>

