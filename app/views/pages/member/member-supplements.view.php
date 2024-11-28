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
    <title><?php echo APP_NAME;; ?></title>
  </head>
  <body>

    <section class="sidebar">
    <?php require APPROOT.'/views/components/member-sidebar.view.php' ?>
    </section>
    
    <main>
      <div class="title">
        <h1>View Supplements</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>
      </div>
      <div class="searchbar">
      <form class="search">
                <button>
                    <svg width="17" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="search">
                        <path d="M7.667 12.667A5.333 5.333 0 107.667 2a5.333 5.333 0 000 10.667zM14.334 14l-2.9-2.9" stroke="currentColor" stroke-width="1.333" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
                <div class="search-input">
                    <input class="input" placeholder="Search" required="" type="text">
                </div>
                <button class="reset" type="reset">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </form>
      </div>

      <!-- View-supplements-section-->
        <?php
          $supplements=[
            ['name' => 'Mass Tech', 'image' => 'supplement.jpg', 'price' => 'Rs 1000.00'],
            ['name' => 'Mass Tech', 'image' => 'supplement.jpg', 'price' => 'Rs 1000.00'],
            ['name' => 'Mass Tech', 'image' => 'supplement.jpg', 'price' => 'Rs 1000.00'],
            ['name' => 'Mass Tech', 'image' => 'supplement.jpg', 'price' => 'Rs 1000.00'],
            ['name' => 'Mass Tech', 'image' => 'supplement.jpg', 'price' => 'Rs 1000.00'],
            ['name' => 'Mass Tech', 'image' => 'supplement.jpg', 'price' => 'Rs 1000.00'],
          ];
          ?>
      <div class="member-supplements-grid-container">
      <div class="member-supplements">
        <?php foreach($supplements as $supplement):?>
        <div class="supplement">
          <img src="<?php echo URLROOT; ?>/assets/images/<?php echo $supplement['image']; ?>" alt="" class="supplement-image"/>
           <h3><?php echo $supplement['name']; ?></h3>
           <p><?php echo $supplement['price']; ?></p>
           <div class="quantity-counter">
              <p>Quantity</p>
              <div class="quantity-counter-btn">
                <button class="btn-decrease"><i class="ph ph-minus"></i></button>
                <input type="number" class="quantity-input" value="1" min="1" />
                <button class="btn-increase"><i class="ph ph-plus"></i></button>
              </div>
          </div>
           <button class="member-supplements-btn">Add</button>
        </div>
        <?php endforeach; ?>
      </div>
        </div>
    </main>
    <script src="<?php echo URLROOT; ?>/assets/js/member/member-script.js?v=<?php echo time();?>"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
        const decreaseBtn = document.querySelector(".btn-decrease");
        const increaseBtn = document.querySelector(".btn-increase");
        const quantityInput = document.querySelector(".quantity-input");

        // Decrease quantity
        decreaseBtn.addEventListener("click", () => {
          let currentValue = parseInt(quantityInput.value);
          if (currentValue > parseInt(quantityInput.min)) {
            quantityInput.value = currentValue - 1;
          }
        });

        // Increase quantity
        increaseBtn.addEventListener("click", () => {
          let currentValue = parseInt(quantityInput.value);
          quantityInput.value = currentValue + 1;
        });

        // Prevent invalid values
        quantityInput.addEventListener("input", () => {
          let currentValue = parseInt(quantityInput.value);
          if (isNaN(currentValue) || currentValue < parseInt(quantityInput.min)) {
            quantityInput.value = quantityInput.min;
          }
        });
      });
    </script>
  </body>
</html>

