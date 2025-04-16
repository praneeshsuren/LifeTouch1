document.addEventListener("DOMContentLoaded", async () => {
    // 1. Initialize Stripe with your publishable key (replace with your actual key if not embedded in HTML)
    const stripePublicKey = window.STRIPE_PUBLIC_KEY;
    console.log("Using Stripe public key:", STRIPE_PUBLIC_KEY);

    const stripe = Stripe(stripePublicKey);
    const elements = stripe.elements();
  
    // 2. Create an instance of the card Element
    const card = elements.create("card", {
      style: {
        base: {
          color: "#32325d",
          fontFamily: "Poppins, sans-serif",
          fontSmoothing: "antialiased",
          fontSize: "16px",
          "::placeholder": {
            color: "#a0aec0"
          }
        },
        invalid: {
          color: "#fa755a",
          iconColor: "#fa755a"
        }
      }
    });
  
    // 3. Mount the card element into the `div#card-element`
    card.mount("#card-element");
  
    // 4. Handle real-time validation errors
    card.on("change", function (event) {
      const displayError = document.getElementById("card-errors");
      if (event.error) {
        displayError.textContent = event.error.message;
      } else {
        displayError.textContent = "";
      }
    });
  
    // 5. Handle form submission
    const form = document.getElementById("payment-form");
    form.addEventListener("submit", async function (event) {
      event.preventDefault();
      const submitButton = form.querySelector("button[type=submit]");
      submitButton.disabled = true;

      const member_id = document.getElementById("member_id").value;
      const paymentDate = document.getElementById("paymentDate").value;
      const email = document.getElementById("email").value;
      const packageName = document.getElementById("package").value;
      const amountInput = document.getElementById("amount").value;
      const amount = parseInt(amountInput);

      if (!amount || amount < 1) {
        alert("Please enter a valid amount.");
        return;
      }
  
      // 6. Send request to backend to create a PaymentIntent
      try {
        const res = await fetch(createPaymentURL, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            email: email,
            package: packageName,
            amount: amount 
          }),
        });
    
        const data = await res.json();
    
        if (data.error) {
          alert("Error creating payment intent: " + data.error);
          return;
        }
    
        // 7. Confirm the card payment
        const stripeResult = await stripe.confirmCardPayment(data.clientSecret, {
          payment_method: {
            card: card,
            billing_details: {
              name: packageName,
              email: email,
            },
          }
        });
        console.log("str",stripeResult);
        const id = stripeResult.paymentIntent.id;

        // 8. Handle payment result
        if (stripeResult.error) {
          document.getElementById("card-errors").textContent = stripeResult.error.message;
        } else if(stripeResult.paymentIntent.status === "succeeded") {
          const formData = new FormData(form);
          formData.append("email", email);
          formData.append("packageName", packageName);
          formData.append("amount", amount);
          formData.append("member_id", member_id);
          formData.append("paymentDate", paymentDate);
          formData.append("payment_intent_id", id);
          formData.append("status", stripeResult.paymentIntent.status);

          for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
          }
          
          const saveResponse = await fetch(savePayment, {
            method: "POST",
            body: formData
          });

          const result = await saveResponse.json();
          console.log("s:",result);
          if (result.success) {
            console.log("success");
            alert("Payment successful and saved!");
            const modal = document.getElementById('paymentModal');
            if (modal) {
              modal.style.display = 'none';
            }
            location.reload();
            form.reset();
            card.clear();
          } else {
            console.log("fail");
            alert("Payment succeeded, but failed to save payment info.");
          }
        }
      } catch (error) {
        console.error("Error:", error);
        alert("Something went wrong.");
      } finally {
        submitButton.disabled = false;
      }
    });
  });
  