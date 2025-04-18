<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/home-style.css?v=<?php echo time(); ?>" />
    <title><?php echo APP_NAME; ?></title>
</head>

<body>
    <!--Header section code-->
    <header>
        <a href="<?php echo URLROOT; ?>" class="logo">Life<span>Touch</span></a>

        <div class='bx bx-menu' id="menu-icon"></div>

        <ul class="navbar">
            <li><a href="<?php echo URLROOT; ?>">Home</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#about">About Us</a></li>
            <li><a href="#plans">Events</a></li>
            <li><a href="#review">Trainers</a></li>
            <li><a href="#contact">Contact Us</a></li>
        </ul>
        <div class="top-btn">
            <a href="<?php echo URLROOT; ?>/login" class="nav-btn">Sign in</a>
        </div>
    </header>

    <!--Home section code-->
    <section class="home" id="home">
        <div class="home-container">
            <h3>Build your</h3>
            <h1>Dream</h1>
            <h3><span class="multiple-text"></span></h3>

            <p>Want to look good, feel great and be healthy? Welcome to Life Touch Fitness, the most exclusive, and
                state-of-the-art Wellness Studio and Gymnasium in Sri Lanka! If you're looking for fitness, wellness,
                relaxation and exclusivity, then OSMO Fitness is the place to be in Sri Lanka!</p>

            <a href="#" class="btn">Join Us</a>


        </div>
        <div class="home-img">
            <img src="<?php echo URLROOT; ?>/assets/images/home/logo.png" alt="Home Image" class="trainer-image" />
        </div>
    </section>

    <!--Services section code-->
    <section class="services" id="services">
        <h2 class="heading">Our <span>Services</span></h2>

        <div class="services-content">
            <div class="row">
                <img src="<?php echo URLROOT; ?>/assets/images/home/image1.jpg" alt="" class="trainer-image" />

                <h4>Physical Fitness</h4>
            </div>
            <div class="row">
                <img src="<?php echo URLROOT; ?>/assets/images/home/image2.jpg" alt="" class="trainer-image" />

                <h4>Weight Gain</h4>
            </div>
            <div class="row">
                <img src="<?php echo URLROOT; ?>/assets/images/home/image3.jpg" alt="" class="trainer-image" style="height: 200px;" />

                <h4>Strength Training</h4>
            </div>
            <div class="row">
                <img src="<?php echo URLROOT; ?>/assets/images/home/image4.jpg" alt="" class="trainer-image" />

                <h4>Fat Loss</h4>
            </div>
            <div class="row">
                <img src="<?php echo URLROOT; ?>/assets/images/home/image5.jpg" alt="" class="trainer-image" style="height: 200px;" />

                <h4>Weight Lifting</h4>
            </div>
            <div class="row">
                <img src="<?php echo URLROOT; ?>/assets/images/home/about.jpg" alt="" class="trainer-image" />

                <h4>Running</h4>
            </div>
        </div>
    </section>

    <!--About section code-->
    <section class="about" id="about">
        <div class="about-img">
            <img src="<?php echo URLROOT; ?>/assets/images/home/about1.jpg" alt="" class="trainer-image" />
        </div>

        <div class="about-content">
            <h2 class="heading">Why Choose Us?</h2>

            <p>Our Divers membership base create a freindly and supportive atomsphere, where yout can make frienda and
                stay motivated </p>
            <p>Our dedicated trainers are here to guide you every step of the way, ensuring you achieve your fitness
                goals with confidence and precision.</p>
            <p>We organize variety of classes, personalized programs, and resources designed to keep you energized and
                engaged.</p>

            <a href="#" class="btn">Book a class</a>
        </div>
    </section>

    <!--Pricing section code-->
    <section class="plans" id="plans">

        <h2 class="heading">Our <span>Events</span></h2>

        <?php if (!empty($_SESSION['join_errors']) && is_array($_SESSION['join_errors'])): ?>
            <div class="error-container" style="color: red; margin-bottom: 10px; font-size: 15px;">
                <?php foreach ($_SESSION['join_errors'] as $error): ?>
                    <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['join_errors']); ?>
        <?php endif; ?>

        <div class="plans-content">
            <?php if (!empty($data['events'])): ?>
                <?php foreach ($data['events'] as $event): ?>
                    <div class="box">
                        <h3><?php echo ($event->name); ?></h3>
                        <h2><span>Rs.<?php echo number_format($event->price, 2); ?></span></h2>
                        <h2><?php echo date("F j", strtotime($event->event_date)); ?></h2>
                        <ul>
                            <li><?php echo ($event->description); ?></li>
                            <?php if ($event->free_for_members): ?>
                                <li><strong>Free for Members!</strong></li>
                            <?php endif; ?>
                        </ul>
                        <a href="#">Join Now<i class='bx bx-right-arrow-alt'></i></a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <h2>No Events are currently ongoing.</h2>
            <?php endif; ?>
        </div>

    </section>


    <!-- Join Event Popup Form -->
    <div id="joinForm" class="popup-form">
        <div class="popup-content">
            <span class="close-btn" onclick="closeForm()">&times;</span>
            <h2>Join Event</h2>

            <form id="eventForm" method="POST" action="<?php echo URLROOT; ?>/home/joinEvent">
                <input type="hidden" id="eventIdInput" name="event_id" />
                <div class="input-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" id="fullname" name="full_name" required />
                </div>
                <div class="input-group">
                    <label for="nic">NIC</label>
                    <input type="text" id="nic" name="nic" required />
                </div>
                <div class="input-group checkbox-group" style="display: flex; gap: 10px;">
                    <label for="isMember">Gym Member?</label>
                    <input type="checkbox" name="is_member" id="isMember" onclick="toggleMembership()">
                </div>

                <div class="input-group" id="membershipGroup" style="display: none;">
                    <label for="membershipNumber">Membership Number</label>
                    <input type="text" id="membershipNumber" name="membership_number"
                        pattern="^MB\/[MF]\/\d+$"
                        title="Format: MB/M/0001 or MB/F/0001"
                        placeholder="MB/M/0001" />
                    <small id="membershipError" style="color: red;"></small>
                </div>
                <div class="input-group">
                    <label for="contact">Contact Number</label>
                    <input type="text" id="contact" name="contact_no" required />
                </div>
                <div class="button-group">
                    <button type="button" class="btn">Pay Now</button>
                    <button type="submit" class="btn">Join</button>
                </div>
            </form>
        </div>
    </div>
    <!--Trainer section code-->
    <section class="review" id="review">
        <div class="review-box">
            <h2 class="heading">Our <span>Trainers</span></h2>
            <div class="wrapper">
                <div class="review-item">
                    <img src="<?php echo URLROOT; ?>/assets/images/home/1.jpg" alt="" class="trainer-image" />
                    <h2>Roshan Karunarathne</h2>
                    <div class="rating">
                        <i class='bx bxs-certification' id="star"></i>
                        <i class='bx bxs-certification' id="star"></i>
                        <i class='bx bxs-certification' id="star"></i>
                        <i class='bx bxs-certification' id="star"></i>
                        <i class='bx bxs-certification' id="star"></i>
                    </div>
                    <p>Trained and certified to develop personalized fitness plans, focusing on safe and effective
                        techniques.</p>
                </div>

                <div class="review-item">
                    <img src="<?php echo URLROOT; ?>/assets/images/home/2.jpg" alt="" class="trainer-image" />
                    <h2>Amila Dhanushka</h2>
                    <div class="rating">
                        <i class='bx bxs-certification' id="star"></i>
                        <i class='bx bxs-certification' id="star"></i>
                        <i class='bx bxs-certification' id="star"></i>
                        <i class='bx bxs-certification' id="star"></i>
                        <i class='bx bxs-certification' id="star"></i>
                    </div>
                    <p>Skilled in advanced techniques to boost strength, endurance, and athletic performance.</p>
                </div>

                <div class="review-item">
                    <img src="<?php echo URLROOT; ?>/assets/images/home/3.jpg" alt="" class="trainer-image" />
                    <h2>Thilina Romesh</h2>
                    <div class="rating">
                        <i class='bx bxs-certification' id="star"></i>
                        <i class='bx bxs-certification' id="star"></i>
                        <i class='bx bxs-certification' id="star"></i>
                        <i class='bx bxs-certification' id="star"></i>
                        <i class='bx bxs-certification' id="star"></i>
                    </div>
                    <p>Focuses on promoting mental well-being and keeping clients motivated throughout their fitness
                        journey.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Us Section -->
    <section class="contact" id="contact">
        <div class="contact-container">
            <h2 class="heading">Contact Us</h2>

            <form action="#" method="post" class="contact-form">
                <div class="input-group">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your name" required />
                </div>

                <div class="input-group">
                    <label for="email">Your Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required />
                </div>

                <div class="input-group">
                    <label for="message">Your Message</label>
                    <textarea id="message" name="message" rows="4" placeholder="Write your message" required></textarea>
                </div>

                <button type="submit" class="btn">Send Message</button>
            </form>
        </div>
    </section>


    <!-- Footer section code -->
    <footer class="footer">
        <div class="social">
            <a href=""><i class='bx bxl-instagram'></i></a>
            <a href="https://www.facebook.com/lifetouchphysicalfitness" target="_blank"><i class='bx bxl-facebook'></i></a>
            <a href="https://api.whatsapp.com/send?phone=%2B94779257773&context=ARBkh6ua3j8vfi6so0P814szJrlRZ4SKTx_Uho4ZRBJ6vHDxuryQnblFnLzUpzkGLjZJsfY26-zehb5gRxCYH-BZPExjt4u-uWRsGPGVfUeI3hKB2cMd--fMLyfAsaOFn5wQHXkm92GI_kTQQZj6PnpRSA&source=FB_Page&app=facebook&entry_point=page_cta&fbclid=IwY2xjawG1QI1leHRuA2FlbQIxMAABHYxNkxoER3sF_d2Z3qIC7KU3jpaZo0D6LaZoFkO2lpVh5yTnZoC2eyBSwg_aem_a0wUXS2YcROlNPikW5vs8Q" target="_blank"><i class='bx bxl-whatsapp'></i></a>
        </div>

        <!-- Location, Contact, and Mail section -->
        <div class="footer-info">
            <div class="footer-item">
                <h3>Location</h3>
                <p>123 Fitness St., Colombo, Sri Lanka</p>
            </div>
            <div class="footer-item">
                <h3>Contact</h3>
                <p>011 23 56 789</p>
            </div>
            <div class="footer-item">
                <h3>Email</h3>
                <p>lifetouchfitness@gmail.com</p>
            </div>
        </div>

        <p class="copyright">
            &copy; Life Touch Fitness 2024 - All Rights Reserved
        </p>
    </footer>


    <script src="<?php echo URLROOT; ?>/assets/js/home-script.js?v=<?php echo time(); ?>"></script>
</body>
<script>
    // Show the popup form when "Join Now" is clicked
    document.querySelectorAll('.plans-content .box a').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default navigation
            document.getElementById('joinForm').style.display = 'flex';
        });
    });

    function closeForm() {
        document.getElementById('joinForm').style.display = 'none';
    }

    function toggleMembership() {
    const checkbox = document.getElementById('isMember');
    const memberField = document.getElementById('membershipGroup');
    const payNowBtn = document.querySelector('.button-group button[type="button"]');
    const eventForm = document.getElementById('eventForm');
    const isFree = eventForm.getAttribute('data-free') === '1';

    // Show/hide membership number input
    memberField.style.display = checkbox.checked ? 'block' : 'none';

    // Show Pay Now button if:
    // - Event is NOT free, regardless of membership
    // - User is NOT a member
    if (!isFree || !checkbox.checked) {
        payNowBtn.style.display = 'inline-block';
    } else {
        // Event is free and user is a member
        payNowBtn.style.display = 'none';
    }
}


</script>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const scrollTo = urlParams.get('scroll');
        if (scrollTo) {
            const section = document.getElementById(scrollTo);
            if (section) {
                section.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }
    });
</script>
<script>
    document.querySelectorAll('.plans-content .box a').forEach((button, index) => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const eventId = eventIds[index];
        const isFreeForMembers = freeEvents[index];

        document.getElementById('eventIdInput').value = eventId;
        document.getElementById('joinForm').style.display = 'flex';

        // Store free status as a custom data attribute
        const form = document.getElementById('eventForm');
        form.setAttribute('data-free', isFreeForMembers ? '1' : '0');

        // Reset checkbox and membership section
        document.getElementById('isMember').checked = false;
        document.getElementById('membershipGroup').style.display = 'none';

        // Decide default Pay Now visibility
        const payNowBtn = document.querySelector('.button-group button[type="button"]');
        payNowBtn.style.display = isFreeForMembers ? 'none' : 'inline-block';
    });
});

</script>

<script>
    const eventIds = <?php echo json_encode(array_column($data['events'], 'event_id')); ?>;
    const freeEvents = <?php echo json_encode(array_column($data['events'], 'free_for_members')); ?>;

    document.querySelectorAll('.plans-content .box a').forEach((button, index) => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const eventId = eventIds[index];
            const isFreeForMembers = freeEvents[index];

            document.getElementById('eventIdInput').value = eventId;
            document.getElementById('joinForm').style.display = 'flex';

            // Store the free status in a hidden attribute or global variable
            document.getElementById('eventForm').setAttribute('data-free', isFreeForMembers ? '1' : '0');

            toggleMembership(); // Call this to evaluate visibility of "Pay Now"
        });
    });
</script>

</html>