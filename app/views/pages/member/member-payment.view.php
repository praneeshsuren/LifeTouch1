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
    <script>
        document.addEventListener("DOMContentLoaded", function(){
            (function() {
                var savedMode = localStorage.getItem('mode');
                if (savedMode === 'dark') {
                document.body.classList.add('dark');
                }
            })();
          });
        </script>
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
        <form action="" class="payment-form">
            <h1 class="payment-title">Payment Details</h1>
            <div class="payment-method">
              <input type="radio" name="payment-method" id="method-1" checked>
              <label for="method-1" class="payment-method-item">
                <img src="<?php echo URLROOT; ?>/assets/images/visa.png" alt="">
              </label>
              <input type="radio" name="payment-method" id="method-2">
              <label for="method-2" class="payment-method-item">
                <img src="<?php echo URLROOT; ?>/assets/images/mastercard.png" alt="">
              </label>     
            </div>
            <div class="payment-form-group-flex1">
            <div class="payment-form-group">
              <input type="text" placeholder=" " class="payment-form-control" id="name" required>
              <label for="name" class="payment-form-label payment-form-label-required">Name on card</label>
            </div>
            <div class="payment-form-group">
              <input type="text" placeholder=" " class="payment-form-control" id="package" required>
              <label for="package" class="payment-form-label payment-form-label-required">Package name</label>
            </div>
            <div class="payment-form-group">
              <input type="email" placeholder=" " class="payment-form-control" id="email" required>
              <label for="email" class="payment-form-label payment-form-label-required">Email Address</label>
            </div>             
            <div class="payment-form-group">
              <input type="text" placeholder=" " class="payment-form-control" id="card-number" maxlength="19" onkeypress="cardspace()" required>
              <label for="card-number" class="payment-form-label payment-form-label-required">Card Number</label>
            </div>
            </div>
            <div class="payment-form-group-flex2">
              <span>
                <label for="month">Expiry Month:</label>
                <select name="month" id="month"></select>
              </span>
              <span>
                <label for="year">Expiry Year:</label>
                <select name="year" id="year"></select>
              </span>
            </div>
              <div class="payment-form-group">
                <input type="text" placeholder=" " class="payment-form-control" id="cvv" maxlength="3" required>
                <label for="cvv" class="payment-form-label payment-form-label-required">CVV</label>
              </div>
            <button type="submit" class="payment-form-submit-button">Pay</button>
        </form>

        <!-- Past Payment Details -->
        <div class="payment-history">
          <h1 class="payment-title">Past Payment Details</h1>
          <table class="paymentHistoryTable">
            <thead>
              <tr>
                <th>Date</th>
                <th>Package detail</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
                <tr>
                  <td>2024.10.12</td>
                  <td>Monthly package</td>
                  <td>Rs 3000.00</td>
                </tr>
                <tr>
                  <td>2024.10.12</td>
                  <td>Monthly package</td>
                  <td>Rs 3000.00</td>
                </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
      
    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/member/payment.js?v=<?php echo time();?>"></script>
    <script src="<?php echo URLROOT; ?>/assets/js/member/member-script.js?v=<?php echo time();?>"></script>

  </body>
</html>

