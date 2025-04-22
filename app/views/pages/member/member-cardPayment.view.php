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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title><?php echo APP_NAME; ?></title>
</head>

<body>
    <main> 
        <div class="container">
            <div class="header">
                <div class="title">
                    <h1>Life Touch</h1>
                </div>
                <h5 class="secure-payment-title">Secure Payment<i class="ph ph-lock-key" style="float:left;"></i></h5>
            </div>
            <section class="payment-section">
                <div class="payment-wrapper">
                    <div class="payment-left">
                        <div class="payment-header">
                            <div class="payment-header-title">Summary</div>
                        </div>
                        <div class="payment-content">
                            <div class="payment-body">
                                <div class="payment-summary">
                                    <div class="payment-summary-item">
                                        <div id="payment-summary-name" class="payment-summary-name"></div>
                                        <div id="payment-summary-price"class="payment-summary-price"></div>
                                    </div>
                                    <div class="payment-summary-divider"></div>
                                    <div class="payment-summary-item payment-summary-total">
                                        <div id="payment-summary-name"class="payment-summary-name">Total</div>
                                        <div id="payment-summary-total" class="payment-summary-price"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="payment-right">
                        <form id="payment-form" class="payment-form">
                            <h1 class="payment-title">Payment Details</h1>
                            <!-- <div class="payment-method">
                                <label class="payment-option">
                                    <input type="radio" name="cardType" value="visa" checked />
                                    <img src="https://img.icons8.com/color/48/000000/visa.png" alt="Visa" />
                                    <span class="checkmark"></span>
                                </label>
                                <label class="payment-option">
                                    <input type="radio" name="cardType" value="mastercard" />
                                    <img src="https://img.icons8.com/color/48/000000/mastercard.png" alt="Mastercard" />
                                    <span class="checkmark"></span>
                                </label>
                            </div> -->
                            <div class="payment-form-group">
                                <label for="card-number-element" class="payment-form-label-required">Card Number</label>
                                <div id="card-number-element" class="payment-form-control"></div>
                            </div>
                            <div class="payment-form-group-flex">
                                <div class="payment-form-group">
                                    <label for="card-expiry-element" class="payment-form-label-required">Expiry Date</label>
                                    <div id="card-expiry-element" class="payment-form-control"></div>
                                </div>
                                <div class="payment-form-group">
                                    <label for="card-cvc-element" class="payment-form-label-required">CVV</label>
                                    <div id="card-cvc-element" class="payment-form-control"></div>
                                </div>
                            </div>
                            <div id="card-errors" class="card-error"></div>
                            <input type="text" name="member_id" id="member_id"><input type="text" name="plan_id" id="package_id"><input type="text" id="amount">
                            <input type="text" name="startDate" id="startDate"><input type="text" name="endDate" id="endDate"><input type="text" name="paymentType" id="payment_type">
                            <div class="payment-form-group-flex">
                                <button type="button" class="payment-form-cancel-button" onclick="window.location.href='<?php echo URLROOT; ?>/member/membershipPlan'"> Cancel</button>
                                <button type="submit" class="payment-form-submit-button"> Pay</button>
                            </div>
                        </form>
                    </div>      
                </div>
            </section>
                
        </div>
    </main>
    <script>
        window.STRIPE_PUBLISHABLE_KEY = "<?php echo STRIPE_PUBLISHABLE_KEY; ?>";
        const createPaymentURL = "<?php echo URLROOT; ?>/member/createPayment";
        const savePlan = "<?php echo URLROOT; ?>/member/Payment/savePayment";
        const redirect = "<?php echo URLROOT; ?>/member/membershipPlan";

        document.addEventListener("DOMContentLoaded", () =>{
            let plan = [];
            let session = {}; 
            fetch(`<?php echo URLROOT?>/member/cardPayment/api`)
              .then(response => {
                console.log("Response status:", response.status);
                return response.json();
              })
              .then(data => {
                plan = data.plan;
                session = data.session;
                console.log("plan:",plan);
                console.log("session:",session);

                const selectedPlan = plan.find(p => p.id == session.id);

                if(selectedPlan){
                    document.getElementById("payment-summary-price").textContent = "Rs " + selectedPlan.amount + ".00";                    document.getElementById("payment-summary-name").textContent = selectedPlan.plan;
                    document.getElementById("payment-summary-total").textContent = "Rs " + selectedPlan.amount + ".00";
                    document.getElementById("member_id").value = session.member_id;
                    document.getElementById("package_id").value = selectedPlan.id;
                    document.getElementById("amount").value = selectedPlan.amount;
                    document.getElementById("startDate").value = session.startDate;
                    document.getElementById("endDate").value = session.endDate;
                    document.getElementById("payment_type").value = session.type;

                }
              })
              .catch(error => console.error("Error in fetching session data", error));
        });
    </script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="<?php echo URLROOT; ?>/assets/js/payment-script.js?v=<?php echo time();?>"></script>
    <script src="<?php echo URLROOT; ?>/assets/js/member/member-script.js?v=<?php echo time();?>"></script>
</body>

</html>