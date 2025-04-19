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
        <h1>Membership Plans</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>
      </div>
      <div class="cards">
        <div class="card shadow">
        </div>
      </div>
      <!-- <div class="paymentBox">
        <div class="payment-history">
          <h1 class="payment-title">Membership Plan Details</h1>
          <table class="paymentHistoryTable">
            <thead>
              <tr>
                <th>Membership plan</th>
                <th>Price</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div> -->

      
    </main>
      
    <!-- SCRIPT -->
    <script>
      document.addEventListener("DOMContentLoaded", () =>{ 
        fetch(`<?php echo URLROOT; ?>/member/membershipPlan/api`)
          .then(response => {
              console.log('Response Status:', response.status);
              return response.json();
            })
            .then(data =>{
              console.log('Plans:',data.plan);
              const plan = Array.isArray(data.plan) ? data.plan : [];
              planCards(plan);
            })
            .catch(error => console.error('Error fetching plans details:', error));
      });

      function planCards(plan){
        cardContainer = document.querySelector(".cards");
        cardContainer.innerHTML = "";

        plan.forEach(plan =>{
          const card = document.createElement("div");
          card.classList.add('card');
          card.innerHTML = `
            <ul class="card-ul">
                <li class="pack">${plan.plan}<li>
                <li id="basic" class="price bottom-bar">Rs ${plan.amount}.00<li>
                <li>
                    <button class="planBtn">Suscribe</button>
                <li>
            </ul`;

          cardContainer.appendChild(card);
        });
      }

    </script>
    <script src="<?php echo URLROOT; ?>/assets/js/member/member-script.js?v=<?php echo time();?>"></script>
  </body>
</html>

