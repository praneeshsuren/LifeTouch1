document.addEventListener("DOMContentLoaded", () => {
    // 1. Initialize Stripe with your publishable key (replace with your actual key if not embedded in HTML)
    const stripe = Stripe(window.STRIPE_PUBLISHABLE_KEY);
    console.log("Using Stripe public key w:", window.STRIPE_PUBLISHABLE_KEY);

    const elements = stripe.elements();

    const style = {
      base: {
        fontSize: '16px',
        color: '#32325d',
        '::placeholder': {
          color: '#a0aec0',
        },
      },
      invalid: {
        color: '#e53e3e',
      },
    };
    
    const cardNumber = elements.create('cardNumber', { style });
    cardNumber.mount('#card-number-element');
    
    const cardExpiry = elements.create('cardExpiry', { style });
    cardExpiry.mount('#card-expiry-element');
    
    const cardCvc = elements.create('cardCvc', { style });
    cardCvc.mount('#card-cvc-element');


    document.getElementById("payment-form").addEventListener("submit", async function (e){
      e.preventDefault();

      const cardError = document.getElementById("card-errors");
      cardError.textContent = "";

      const submitButton = this.querySelector("button[type=submit]");
      submitButton.disabled = true;

      const member_id = document.getElementById("member_id").value;
      const package_id = document.getElementById("package_id").value;
      const amount = document.getElementById("amount").value;

      try {
        // create paymentIntent from backend
        const res = await fetch(createPaymentURL,{
          method: "POST",
          headers: { "Content-Type": "application/json"},
          body: JSON.stringify({member_id,
            plan_id: package_id,
            amount: amount
          })
        });

        const data = await res.json();
        if(!data.clientSecret){
          alert("Failed to create PaymentIntent");
          return;
        }

        // Use raw card data from form
        const cardDetails = {
          card: cardNumber
        };

        // confirm payment with raw details
        const stripeResult = await stripe.confirmCardPayment(data.clientSecret, {
          payment_method: cardDetails
        });

        if(stripeResult.error) {
          cardError.textContent = stripeResult.error.message;
          submitButton.disabled = false;

          return;
        }

        if (stripeResult.paymentIntent.status === "succeeded") {
          const formData = new FormData(this);
          formData.append("payment_intent_id", stripeResult.paymentIntent.id);
          formData.append("status", stripeResult.paymentIntent.status);

          const savePayment = await fetch(savePlan,{
            method: "POST",
            body: formData
          });

          const result =  await savePayment.json();
          if(result.success) {
            console.log("Payment successful and saved!");
            alert("Payment successful and saved!");
            this.reset();  // Reset the form fields
            cardNumber.clear();  // Clear the card number
            cardExpiry.clear();  // Clear the expiry date
            cardCvc.clear();  // Clear the CVV
            window.location.href = redirect;
          } else {
            console.log("Payment succeeded, but failed to save payment info");
            alert("Payment succeeded, but failed to save payment info.");
          }
        } 
      } catch (err) {
        console.error("Error:", err);
        alert("Something went wrong during payment");
      } finally {
        submitButton.disabled = false;
      }
      
    });
});