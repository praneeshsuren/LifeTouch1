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
        <a href="#" class="logo">Life<span>Touch</span></a>

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

        <div class="plans-content">
            <div class="box">
                <h3>Healthy Cooking Workshop</h3>
                <h2><span>Rs.5000</span></h2>
                <h2>December 31</h2>
                <ul>
                    <li>Learn to prepare delicious, nutritious meals with a professional chef.</li>
                    <li>Receive a recipe booklet and a nutrition guide.</li>
                </ul>
                <a href="#">Join Now<i class='bx bx-right-arrow-alt'></i></a>
            </div>
            <div class="box">
                <h3>Yoga</h3>
                <h2><span>Rs.5000</span></h2>
                <h2>December 3</h2>
                <ul>
                    <li>Relax and rejuvenate with guided yoga and breathing exercises.</li>
                    <li>Learn mindfulness techniques to reduce stress.</li>
                </ul>
                <a href="#">Join Now<i class='bx bx-right-arrow-alt'></i></a>
            </div>
            <div class="box">
                <h3>Fitness Expo Day</h3>
                <h2><span>Rs.1000</span></h2>
                <h2>December 5</h2>
                <ul>
                    <li>Explore fitness gear and products at vendor stalls.</li>
                    <li>Get expert advice on fitness and health trends.</li>
                </ul>
                <a href="#">Join Now<i class='bx bx-right-arrow-alt'></i></a>
            </div>
        </div>
    </section>
    <!--Trainer section code-->
    <section class="review" id="review">
        <div class="review-box">
            <h2 class="heading">Our <span>Trainers</span></h2>
            <div class="wrapper">
                <div class="review-item">
                    <img src="<?php echo URLROOT; ?>/assets/images/home/1.jpg" alt="" class="trainer-image" />
                    <h2>John</h2>
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
                    <h2>John</h2>
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
                    <h2>John</h2>
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
            <a href="#"><i class='bx bxl-instagram'></i></a>
            <a href="#"><i class='bx bxl-facebook'></i></a>
            <a href="#"><i class='bx bxl-linkedin'></i></a>
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

</html>