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
      <div id="bookingModal" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <div class="bookingModal-body">
            <form id="payment-form" class="payment-form" method="POST" action="<?php echo URLROOT; ?>/member/checkout">
              <h1 class="payment-title">Payment Details</h1>
              <input type="text" id="member_id" value="<?php echo htmlspecialchars($member_id); ?>" name="member_id" required>
              <input type="text" name="package_id" id="package_id">
              <div class="payment-form-group">
                <input type="text" id="package" placeholder=" " class="payment-form-control" name="package" readonly required />
                <label for="package" class="payment-form-label">Package Name</label>
              </div>
              <div class="payment-form-group">
                <input type="number" id="amount" placeholder=" " class="payment-form-control" name="amount" readonly required />
                <label for="amount" class="payment-form-label">Amount</label>
              </div>
              <div class="date-row">
                <div class="payment-form-group date-field">
                    <input type="date" id="paymentStartDate" class="payment-form-control" name="paymentStartDate" readonly required />
                    <label for="paymentStartDate" class="payment-form-label">Start Date</label>
                </div>

                <div class="payment-form-group date-field">
                    <input type="date" id="paymentExpireDate" class="payment-form-control" name="paymentExpireDate" readonly required />
                    <label for="paymentExpireDate" class="payment-form-label">Expiry Date</label>
                </div>
              </div>
              <button type="submit" class="payment-form-submit-button">Proceed</button>
            </form>
          </div>
        </div>
      </div>
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
        
        paymentModal();
      });

      const modal = document.getElementById("bookingModal");
      const closeModal = document.querySelector(".close");

      function planCards(plan){
        cardContainer = document.querySelector(".cards");
        cardContainer.innerHTML = "";

        plan.forEach((plan, index) =>{
            const card = document.createElement("div");

            if(index === 1)
            {
                card.classList.add('card', 'active');
            } else{
                card.classList.add('card', 'shadow');
            }

            card.innerHTML = `
                <ul class="card-ul">
                    <li class="pack">${plan.plan}</li>
                    <li id="basic" class="price">Rs ${plan.amount}.00</li>
                    <li class>
                        <button class="planBtn">Subscribe</button>
                    </li>
                </ul`;
            
            card.addEventListener('mouseenter', () => {
                document.querySelectorAll(".card").forEach(c =>{
                    c.classList.remove("active");
                    c.classList.add("shadow");
                });
                card.classList.remove("shadow");
                card.classList.add("active");
            });

            const subscribe = card.querySelector('.planBtn');
            subscribe.addEventListener("click", () => {
                const packageInput = document.getElementById("package");
                const amountInput = document.getElementById("amount");
                const startDateInput = document.getElementById("paymentStartDate");
                const expiryDateInput = document.getElementById("paymentExpireDate");

                const today = new Date().toISOString().split("T")[0];
                startDateInput.value = today;
                
                // Set values in modal
                packageInput.value = plan.plan;
                amountInput.value = plan.amount;
                document.getElementById("package_id").value = plan.id;

                const expiry = calculateExpiryDate(today,plan.plan);
                if (expiry){
                    expiryDateInput.value = expiry;
                }

                modal.style.display = "block";
            });
        
            cardContainer.appendChild(card);
        });
      }

      function paymentModal(){
        const modal = document.getElementById("bookingModal");
        const closeModal = document.querySelector(".close");

        closeModal.addEventListener("click", () => {
            modal.style.display = "none";
        });

        window.addEventListener("click", (event) => {
            if (event.target === modal) {
            modal.style.display = "none";
            }
        });
      }

      function calculateExpiryDate(startDateInput,planDur){
        const startDate = new Date(startDateInput);
        const expiryDate = new Date(startDate);

        switch(planDur.toLowerCase()){
            case 'monthly':
                expiryDate.setMonth(expiryDate.getMonth()+1);
                break;
            case 'quarterly':
                expiryDate.setMonth(expiryDate.getMonth() + 3);
                break;
            case 'semi annually':
                expiryDate.setMonth(expiryDate.getMonth() + 6);
                break;
            case 'annually':
                expiryDate.setFullYear(expiryDate.getFullYear() + 1);
                break;
            default:
                return null;
        }

        return expiryDate.toISOString().split('T')[0];
      }
    </script>
    <script src="<?php echo URLROOT; ?>/assets/js/member/member-script.js?v=<?php echo time();?>"></script>
  </body>
</html>

