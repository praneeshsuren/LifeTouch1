<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/home-style.css?v=<?php echo time(); ?>" />
    <title><?php echo APP_NAME; ?></title>
</head>

<body  style="background-color:#F0F8FF; color:black;">
    <main> 
        <div class="container">
            <div class="header">
                <div class="secure-payment-title">
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
                            <input type="hidden" name="event_id" id="event_id"><input type="hidden" id="amount"><input type="hidden" id="name" name="name"><input type="hidden" id="nic" name="nic">
                            <input type="hidden" id="contact_no" name="contact_no"><input type="hidden" id="member_id" name="member_id">
                            <div class="payment-form-group-flex">
                                <button type="button" class="payment-form-cancel-button" onclick="window.location.href='<?php echo URLROOT; ?>/home/payment'"> Cancel</button>
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
        const createPaymentURL = "<?php echo URLROOT; ?>/home/createPayment";
        const savePlan = "<?php echo URLROOT; ?>/home/Payment/savePayment";
        const redirect = "<?php echo URLROOT; ?>/home/payment";

        document.addEventListener("DOMContentLoaded", () =>{
            let plan = [];
            let session = {}; 
            fetch(`<?php echo URLROOT?>/home/cardPayment/api`)
              .then(response => {
                console.log("Response status:", response.status);
                return response.json();
              })
              .then(data => {
                event = data.event;
                session = data.session;
                // console.log("event:",event);
                // console.log("session:",session);

                const selectedEvent = event.find(e => e.event_id.trim() == session.event_id.trim());

                if(selectedEvent){
                    document.getElementById("payment-summary-price").textContent = "Rs " + selectedEvent.price + ".00";                    
                    document.getElementById("payment-summary-name").textContent = selectedEvent.name;
                    document.getElementById("payment-summary-total").textContent = "Rs " + selectedEvent.price + ".00";

                    document.getElementById("event_id").value = selectedEvent.event_id;
                    document.getElementById("amount").value = selectedEvent.price;
                    document.getElementById("name").value = session.full_name;
                    document.getElementById("nic").value = session.nic;
                    document.getElementById("contact_no").value = session.contact_no;
                    document.getElementById("member_id").value = session.member_id;

                }
              })
              .catch(error => console.error("Error in fetching session data", error));
        });
    </script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="<?php echo URLROOT; ?>/assets/js/eventPayment.js?v=<?php echo time();?>"></script>
    </body>

</html>