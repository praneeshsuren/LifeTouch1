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

      <!-- Current Membership Section -->
      <div class="current-membership">
        <h2 class="section-title">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 6L9 17l-5-5"></path>
          </svg>
          Your Current Membership
        </h2>
        <div class="membership-details" id="membershipDetails">
          <!-- Dynamically populated -->
        </div>
        <div class="membership-progress">
          <div class="progress-bar-container">
            <div class="progress-bar" id="progressBar"></div>
          </div>
          <div class="progress-text" id="progressText">
            <span id="progressPercentage"></span>
            <span id="daysRemaining"></span>
          </div>
        </div>
      </div>

      <!-- memberships -->
      <div class="cards">
        <div class="card shadow">
        </div>
      </div>

      <div id="bookingModal" class="modal" >
        <div class="modal-content">
          <span class="close">&times;</span>
          <div class="bookingModal-body">
            <form id="payment-form" class="payment-form" method="POST" action="<?php echo URLROOT; ?>/member/checkout">
              <h1 class="payment-title">Payment Details</h1>
              <input type="hidden" id="member_id" value="<?php echo htmlspecialchars($member_id); ?>" name="member_id" required>
              <input type="hidden" name="package_id" id="package_id"><input type="hidden" name="payment_type" id="payment_type">
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
              // console.log('Plans:',data.plan);
              // console.log("Subscription:", data.subscription);
              const plan = Array.isArray(data.plan) ? data.plan : [];
              planCards(plan);
              const subscription = Array.isArray(data.subscription) ? data.subscription[0] : null;
              if (!subscription) {
                console.log("No subscription found");
                subscriptionTable(null, null);
              } else {
                const selectedPlan = plan.find(p => p.id === subscription.plan_id);

                if(selectedPlan) {
                  const mergedSubscription = {
                    ...subscription, ...selectedPlan,id: subscription.id
                  };
                //  console.log("mergedsubscription",mergedSubscription);
                  window.mergedSubscriptions = mergedSubscription;
                  subscriptionTable(window.mergedSubscriptions);
                } else {
                  console.log("No plan found matching the subscription plan id.");
                }
              }

            })
            .catch(error => console.error('Error fetching plans details:', error));

        paymentModal();
            
        const submitButton = document.querySelector(".payment-form-submit-button");
        const paymentForm = document.querySelector("form");

        paymentForm.addEventListener('submit', function(e) {
          e.preventDefault();
          if(window.mergedSubscriptions) {
            if(window.mergedSubscriptions.status == 'active'){
              alert("You have active subscription.");
              modal.style.display = "none";
              return;
            } 
          } else {
            console.log("Merged subscription is not available.");
          }

          submitButton.disabled = true;
          this.submit();
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
                if (window.mergedSubscriptions && window.mergedSubscriptions.status == 'inactive') {
                  document.getElementById("payment_type").value = "renew";
                }else {
                  document.getElementById("payment_type").value = "new";
                }

                const expiry = calculateExpiryDate(today,plan.plan);
                if (expiry){
                    expiryDateInput.value = expiry;
                }

                modal.style.display = "block";
            });
        
            cardContainer.appendChild(card);
        });
      }

      function subscriptionTable(currentMembership){
        const membershipDetails = document.getElementById('membershipDetails');
        const progressBarContainer = document.querySelector('.progress-bar-container');
        const progressText = document.getElementById('progressText');
        const progressBar = document.getElementById('progressBar');
        // Case 1: No membership
        if (!currentMembership) {
          membershipDetails.innerHTML = `
            <div class="no-membership-message">
              <div class="no-membership-text">You have no active membership.</div>
            </div>
          `;
          progressBarContainer.style.display = 'none';
          progressText.style.display = 'none';
          return;
        }

        // Case 3: Active membership (original behavior)
        const statusClass = currentMembership.status.toLowerCase() === 'inactive' ? 'status-inactive' : 'status-active';
        const billingLabel = currentMembership.status.toLowerCase() === 'inactive' ? 'End Date' : 'Next Billing';

        membershipDetails.innerHTML = `
          <div class="detail-item">
            <div class="detail-label">Plan</div>
            <div class="detail-value">${currentMembership.plan}</div>
          </div>
          <div class="detail-item">
            <div class="detail-label">Status</div>
            <div class="detail-value">
              <span class="status-badge ${statusClass}">${currentMembership.status}</span>
            </div>
          </div>
          <div class="detail-item">
            <div class="detail-label">Start Date</div>
            <div class="detail-value">${currentMembership.start_date}</div>
          </div>
          <div class="detail-item">
            <div class="detail-label">${billingLabel}</div>
            <div class="detail-value">${currentMembership.end_date}</div>
          </div>
          ${currentMembership.status.toLowerCase() === 'inactive' ? `
            <div class="inactive-membership-message">
              <button class="renew-plan-button">Renew Plan</button>
            </div>
          ` : ''}
        `;

        // Case 2: Inactive membership
        if (currentMembership.status.toLowerCase() === 'inactive') {
          progressBarContainer.style.display = 'block';
          progressText.style.display = 'flex';
          progressText.style.display = 'none';
          progressBar.classList.add('progress-inactive'); 
          progressBar.style.width = '100%';

          const renewPlanButton = membershipDetails.querySelector('.renew-plan-button');
          if (renewPlanButton) {
            renewPlanButton.addEventListener('click', () => {
              if(!window.mergedSubscriptions){
                alert("You have no active subscription to renew.");
                return;
              }
              const expiry = calculateExpiryDate(today,window.mergedSubscriptions.plan);
          
              document.getElementById('package').value =window.mergedSubscriptions.plan;
              document.getElementById('amount').value = window.mergedSubscriptions.amount;
              document.getElementById('paymentStartDate').value = today;
              document.getElementById('paymentExpireDate').value = expiry;
              document.getElementById('package_id').value = window.mergedSubscriptions.plan_id
              document.getElementById('payment_type').value = 'renew';  

              bookingModal.style.display = 'block';
            });
          }
          return;
        }

        // active
        progressBarContainer.style.display = 'block';
        progressText.style.display = 'flex';

        // Calculate and display progress
        const startDate = new Date(currentMembership.start_date);
        const endDate = new Date(currentMembership.end_date);
        const now = new Date();
        now.setHours(0,0,0,0);
        startDate.setHours(0,0,0,0);
        endDate.setHours(0,0,0,0);
        
        const todalDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 *24));
        const pastDays = Math.ceil((now - startDate)/ (1000 * 60 * 60 *24));
        const progress = Math.min(100, Math.max(0, (pastDays / todalDays) * 100));
        const daysRemain = Math.max(0, todalDays - pastDays);
        document.getElementById('progressBar').style.width = `${progress}%`;
        document.getElementById('progressPercentage').textContent = `${Math.round(progress)}% of membership period completed`;
        document.getElementById('daysRemaining').textContent = `${daysRemain} days remaining`;
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

