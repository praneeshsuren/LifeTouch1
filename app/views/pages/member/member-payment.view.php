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
        <h1>Payment History</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>
      </div>

      <div class="payment-history">
        <div>
            <button class="trainerviewbtn-Bookreservationbtn" style="float: right; margin-top: -10px;margin-bottom:3px;" onclick="window.location.href='<?php echo URLROOT; ?>/member/membershipPlan'">Purchase Membership Plan</button>
        </div>
        <table class="payment-table">
            <thead>
            <tr>
                <th>Date</th>
                <th>Membership Plan</th>
                <th>Amount</th>
            </tr>
            </thead>
            <tbody id="paymentHistoryBody">
            </tbody>
        </table>
    </div>
    </main>
      
    <!-- SCRIPT -->
    <script>
      const today = new Date().toISOString().split("T")[0];
      const modal = document.getElementById("bookingModal");
      const closeModal = document.querySelector(".close");
      document.addEventListener("DOMContentLoaded", () => { 
       
        fetch(`<?php echo URLROOT; ?>/member/payment/api`)
          .then(response => {
              console.log('Response Status:', response.status);
              return response.json();
            })
            .then(data =>{
              // console.log('Plans:',data.plan);
              // console.log("Subscription:", data.subscription);
              const plan = Array.isArray(data.plan) ? data.plan : [];
              const subscription = Array.isArray(data.subscription) ? data.subscription[0] : null;

              // console.log('payments:',data.payment);
              const payment = Array.isArray(data.payment) ? data.payment : [];
              if(payment.length == 0){
                console.log("no pyaments found");
                paymentTable(null);
              } else{
                const mergePayment = payment.map(p => {
                  const selectedPlan = plan.find(s =>s.id === p.plan_id);
                  return selectedPlan ? { ...p, ...selectedPlan } : p;
                })
                paymentTable(mergePayment);
              }

            })
            .catch(error => console.error('Error fetching plans details:', error));            
      });

      function paymentTable(payment){
        const tbody = document.getElementById('paymentHistoryBody');
        tbody.innerHTML = "";

        if (payment.length === 0) {
          const tr = document.createElement("tr");
          tr.innerHTML = `<td colspan="4" style="text-align: center;">No payment records found.</td>`;
          tbody.appendChild(tr);
          return; 
        }

        payment.sort((a,b) => new Date(a.start_date) - new Date(b.start_date));

        payment.forEach(payment =>{
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${payment.start_date}</td>
            <td>${payment.plan}</td>
            <td>Rs ${payment.amount}.00</td>`;

          tbody.appendChild(tr);
        });
      }

    </script>
    <script src="<?php echo URLROOT; ?>/assets/js/member/member-script.js?v=<?php echo time();?>"></script>
  </body>
</html>

