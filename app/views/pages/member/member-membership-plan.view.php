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
        <h1>Membership Plans</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>
      </div>
      <div class="cards">
        <div class="card shadow">
        </div>
      </div>
      <div class="paymentBox">
        <div class="payment-history">
          <h1 class="payment-title">Your Current Plan</h1>
          <table class="paymentHistoryTable">
            <thead>
              <tr>
                <th>Membership plan</th>
                <th>Price</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
          
        </div>
        <div>
          <button type="submit" id ="renew-btn" class="payment-form-submit-button">Renew</button>
          <button type="submit" id="cancel-btn" class="payment-form-submit-button">Cancel</button>
          </div>
      </div>
      <div id="bookingModal" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <div class="bookingModal-body">
            <form id="payment-form" class="payment-form" method="POST" action="<?php echo URLROOT; ?>/member/checkout">
              <h1 class="payment-title">Payment Details</h1>
              <input type="text" id="member_id" value="<?php echo htmlspecialchars($member_id); ?>" name="member_id" required>
              <input type="text" name="package_id" id="package_id"><input type="text" name="payment_type" id="payment_type">
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
      const today = new Date().toISOString().split("T")[0];
      const modal = document.getElementById("bookingModal");
      const closeModal = document.querySelector(".close");
      document.addEventListener("DOMContentLoaded", () => { 
       
        fetch(`<?php echo URLROOT; ?>/member/membershipPlan/api`)
          .then(response => {
              console.log('Response Status:', response.status);
              return response.json();
            })
            .then(data =>{
              console.log('Plans:',data.plan);
              console.log("Subscription:", data.subscription);
              const plan = Array.isArray(data.plan) ? data.plan : [];
              let subscription = [];

if (Array.isArray(data.subscription)) {
  subscription = data.subscription;
} else if (typeof data.subscription === "object" && data.subscription !== null) {
  subscription = [data.subscription]; // wrap single object in array
}

if (subscription.length === 0) {
  console.log("No subscription found");
  subscriptionTable(null, null);
  planCards(plan);
} else {
  const activeSubscription = subscription.find(sub => sub.status === 'active'); // add this check too
  if (!activeSubscription) {
    console.log("No active subscription found");
    subscriptionTable(null, null);
    planCards(plan);
    return;
  }

  const selectedPlan = plan.find(p => p.id === activeSubscription.plan_id);
  const mergedSubscription = {
    ...activeSubscription,
    plan_name: selectedPlan?.plan,
    amount: selectedPlan?.amount,
  };

  window.mergedSubscriptions = mergedSubscription;
  planCards(plan);
  subscriptionTable(selectedPlan, activeSubscription);
}
            })
            .catch(error => console.error('Error fetching plans details:', error));

        paymentModal();
            
        const submitButton = document.querySelector(".payment-form-submit-button");
        const paymentForm = document.querySelector("form");

        paymentForm.addEventListener('submit', function(e) {
          e.preventDefault();
          if(window.mergedSubscriptions){
            alert("You have existing subscriptions.");
            modal.style.display = "none";
            return;
          }

          submitButton.disabled = true;
          this.submit();
        });

        const renewBtn = document.getElementById("renew-btn");

        renewBtn.addEventListener("click", function (e) {

          if(!window.mergedSubscriptions){
            alert("You have no active subscription to renew.");
            return;
          }
          const expiry = calculateExpiryDate(today,window.mergedSubscriptions.plan_name);
          console.log("ex",expiry)
      
          document.getElementById('package').value =window.mergedSubscriptions.plan_name;
          document.getElementById('amount').value = window.mergedSubscriptions.amount;
          document.getElementById('paymentStartDate').value = today;
          document.getElementById('paymentExpireDate').value = expiry;
          document.getElementById('package_id').value = window.mergedSubscriptions.plan_id
          document.getElementById('payment_type').value = 'renew';  

          bookingModal.style.display = 'block';
        });

      });

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

                startDateInput.value = today;
                
                // Set values in modal
                packageInput.value = plan.plan;
                amountInput.value = plan.amount;
                document.getElementById("package_id").value = plan.id;
                document.getElementById("payment_type").value = "new";

                const expiry = calculateExpiryDate(today,plan.plan);
                if (expiry){
                    expiryDateInput.value = expiry;
                }

                modal.style.display = "block";
            });
        
            cardContainer.appendChild(card);
        });
      }

      function subscriptionTable(plan, subscription){
        tbody = document.querySelector(".paymentHistoryTable tbody");
        tbody.innerHTML = "";

        if (!subscription) {
          const tr = document.createElement("tr");
          tr.innerHTML = `<td colspan="4" style="text-align: center;">No subscription records found.</td>`;
          tbody.appendChild(tr);
          return;
        } else {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${plan.plan}</td>
            <td>Rs ${plan.amount}.00</td>
            <td>${subscription.start_date}</td>
            <td>${subscription.end_date}</td>
            <td>${subscription.status}</td>`;

          tbody.appendChild(tr);
        }
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

