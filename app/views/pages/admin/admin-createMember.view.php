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
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/admin-style.css?v=<?php echo time();?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/registration-style.css?v=<?php echo time(); ?>" />

    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>

    <section class="sidebar">
        <?php require APPROOT.'/views/components/admin-sidebar.view.php' ?>
    </section>

    <main>

        <div class="title">
            
            <h1>Members</h1>
            <div class="greeting">
            <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
            </div>

        </div>

        <div class="user-create-container">
            
            <div class="user-create-sidebar">

                <div class="section-nav">
                    
                    <h2>Member Registration</h2>

                    <div class="section-link-wrapper">
                        <div class="section-line-wrapper">
                            <span class="section-number">1</span>
                            <div class="section-line"></div>
                        </div>
                        <a href="#profile-picture" class="section-link">Profile Picture</a>
                    </div>      

                    <div class="section-link-wrapper">
                        <div class="section-line-wrapper">
                            <span class="section-number">2</span>
                            <div class="section-line"></div>
                        </div>
                        <a href="#personal-details" class="section-link">Personal Details</a>
                    </div>

                    <div class="section-link-wrapper">
                        <div class="section-line-wrapper">
                            <span class="section-number">3</span>
                            <div class="section-line"></div>
                        </div>
                        <a href="#contact-details" class="section-link">Contact Details</a>
                    </div>

                    <div class="section-link-wrapper">
                        <div class="section-line-wrapper">
                            <span class="section-number">4</span>
                            <div class="section-line"></div>
                        </div>
                        <a href="#health-details" class="section-link">Health Details</a>
                    </div>
                    <div class="section-link-wrapper">
                        <div class="section-line-wrapper">
                            <span class="section-number">5</span>
                            <div class="section-line"></div>
                        </div>
                        <a href="#membership-plan" class="section-link">Membership Plan</a>
                    </div>
                    <div class="section-link-wrapper">
                        <div class="section-line-wrapper">
                            <span class="section-number">6</span>
                        </div>
                        <a href="#login-credentials" class="section-link">Login Credentials</a>
                    </div>

                </div>

            </div>

            <div class="user-form">

                <form action="<?php echo URLROOT; ?>/user/member/registerMember" method="post">

                    <div class="form-container">

                        <div class="section-content" id="profile-picture">

                            <h3>Profile Picture</h3>

                            <div class="input-image-box">

                                <div class="image-container">
                                    <img id="profile-image" src="<?php echo URLROOT; ?>/assets/images/no_img.jpg" alt="Member Image">
                                </div>

                                <label for="image-upload" class="image-upload-label">Upload Image</label>

                                <input
                                    type="file"
                                    id="image-upload"
                                    class="image-upload-input"
                                    name="image"
                                    onchange="display_image(this.files[0])"
                                >

                            </div>

                        </div>
                        

                        <div class="section-content" id="personal-details">

                            <h3>Personal Details</h3>

                            <div class="input-container">
                                <div class="input-box">
                                    <input type="text" id="first-name" name="first_name" value="<?php echo $_POST['first_name'] ?? ''; ?>" required>
                                    <label class="floating-label">First Name</label>
                                    <?php if (!empty($data['errors']['first_name'])): ?>
                                        <span class="invalid-feedback"><?php echo $data['errors']['first_name']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="input-container">
                                <div class="input-box">
                                    <input type="text" id="last-name" name="last_name" value="<?php echo $_POST['last_name'] ?? ''; ?>" required>
                                    <label class="floating-label">Last Name</label>
                                    <?php if (!empty($data['errors']['last_name'])): ?>
                                        <span class="invalid-feedback"><?php echo $data['errors']['last_name']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="input-container">
                                <div class="input-box">
                                    <input type="text" id="NIC-no" name="NIC_no" value="<?php echo $_POST['NIC_no'] ?? ''; ?>" required>
                                    <label class="floating-label">NIC Number</label>
                                    <?php if (!empty($data['errors']['NIC_no'])): ?>
                                        <span class="invalid-feedback"><?php echo $data['errors']['NIC_no']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row">

                                <div class="input-container">
                                    <div class="input-box">
                                        <label>Date of Birth:</label>
                                        <input type="date" id="dob" name="date_of_birth" value="<?php echo $_POST['date_of_birth'] ?? ''; ?>" required>
                                        <?php if (!empty($data['errors']['date_of_birth'])): ?>
                                            <span class="invalid-feedback"><?php echo $data['errors']['date_of_birth']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="input-container">
                                    <div class="input-box">
                                        <select id="gender" name="gender" required>
                                            <option value="" disabled selected>Gender</option>
                                            <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                                            <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                                            <option value="Other" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                                        </select>
                                        <?php if (!empty($data['errors']['gender'])): ?>
                                            <span class="invalid-feedback"><?php echo $data['errors']['gender']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                            </div>

                            <div class="input-container">
                                <div class="input-box">
                                    <input type="text" id="home-address" name="home_address" value="<?php echo $_POST['home_address'] ?? ''; ?>" required>
                                    <label class="floating-label">Home Address</label>
                                    <?php if (!empty($data['errors']['home_address'])): ?>
                                        <span class="invalid-feedback"><?php echo $data['errors']['home_address']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                                        
                        </div>

                        <div class="section-content" id="contact-details">

                            <h3>Contact Details</h3>    

                            <div class="input-container">
                                <div class="input-box">
                                    <input type="tel" id="contact-number" name="contact_number" value="<?php echo $_POST['contact_number'] ?? ''; ?>" required>
                                    <label class="floating-label">Contact Number</label>
                                    <?php if (!empty($data['errors']['contact_number'])): ?>
                                        <span class="invalid-feedback"><?php echo $data['errors']['contact_number']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="input-container">
                                <div class="input-box">
                                    <input type="email" id="email-address" name="email_address" value="<?php echo $_POST['email_address'] ?? ''; ?>" required>
                                    <label class="floating-label">Email Address</label>
                                    <?php if (!empty($data['errors']['email_address'])): ?>
                                        <span class="invalid-feedback"><?php echo $data['errors']['email_address']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                
                        </div>

                        <div class="section-content" id="health-details">

                            <h3>Health Details</h3>

                            <div class="row">

                                <div class="input-container">
                                    <div class="input-box">
                                        <input type="number" id="weight" name="weight" value="<?php echo $_POST['weight'] ?? ''; ?>" step="0.001" required>
                                        <label class="floating-label">Weight (kg)</label>
                                        <?php if (!empty($data['errors']['weight'])): ?>
                                            <span class="invalid-feedback"><?php echo $data['errors']['weight']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>


                                <div class="input-container">
                                    <div class="input-box">
                                        <input type="number" id="height" name="height" value="<?php echo $_POST['height'] ?? ''; ?>" step="0.001" required>
                                        <label class="floating-label">Height (m)</label>
                                        <?php if (!empty($data['errors']['height'])): ?>
                                            <span class="invalid-feedback"><?php echo $data['errors']['height']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                            </div>
                                        
                        </div>
                        <div class="section-content" id="membership-plan">
                            <h3>Membership Plan</h3>
                            <div class="input-container">
                                <div class="input-box1">
                                    <select id="gender" name="membership_plan" required>
                                        <option value="" disabled selected>Plan</option>
                                        <option value="Monthly" <?php echo (isset($_POST['membership_plan']) && $_POST['membership_plan'] === 'Monthly') ? 'selected' : ''; ?>>Monthly</option>
                                        <option value="Quarterly" <?php echo (isset($_POST['membership_plan']) && $_POST['membership_plan'] === 'Quarterly') ? 'selected' : ''; ?>>Quarterly</option>
                                        <option value="Semi-Annually" <?php echo (isset($_POST['membership_plan']) && $_POST['membership_plan'] === 'Semi-Annually') ? 'selected' : ''; ?>>Semi-Annually</option>
                                        <option value="Anually" <?php echo (isset($_POST['membership_plan']) && $_POST['membership_plan'] === 'Anually') ? 'selected' : ''; ?>>Anually</option>
                                    </select>
                                    <?php if (!empty($data['errors']['membership_plan'])): ?>
                                        <span class="invalid-feedback"><?php echo $data['errors']['membership_plan']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="section-content" id="login-credentials">
                            
                            <h3>Login Credentials</h3>

                            <div class="input-container">
                                <div class="input-box">
                                    <input type="text" id="username" name="username" value="<?php echo $_POST['username'] ?? ''; ?>" required>
                                    <label class="floating-label">Username</label>
                                    <?php if (!empty($data['errors']['username'])): ?>
                                        <span class="invalid-feedback"><?php echo $data['errors']['username']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row">

                                <div class="input-container">
                                    <div class="input-box">
                                        <input type="password" id="password" name="password" required>
                                        <label class="floating-label">Password</label>
                                        <?php if (!empty($data['errors']['password'])): ?>
                                            <span class="invalid-feedback"><?php echo $data['errors']['password']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="input-container">
                                    <div class="input-box">
                                        <input type="password" id="confirm-password" name="confirm_password" required>
                                        <label class="floating-label">Confirm Password</label>
                                        <?php if (!empty($data['errors']['confirm_password'])): ?>
                                            <span class="invalid-feedback"><?php echo $data['errors']['confirm_password']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                            </div>
                            
                        </div>


                    </div>

                    <div class="btn-container">
                        <button type="submit" class="user-create-btn">Create Member</button>
                    </div>

                </form>

            </div>

        </div>

        <!-- SCRIPT -->
        <script src="<?php echo URLROOT; ?>/assets/js/admin-script.js?v=<?php echo time();?>"></script>

        <script>
            function display_image(file) {
                if (file) {
                    const img = document.getElementById("profile-image"); // Ensure the correct ID is used
                    img.src = URL.createObjectURL(file); // Create a temporary URL for the file
                    img.onload = () => URL.revokeObjectURL(img.src); // Revoke the URL after the image is loaded
                }
            }

            // Update the `onchange` event listener in the input
            document.querySelector(".image-upload-input").addEventListener("change", function () {
                const file = this.files[0]; // Get the selected file
                display_image(file); // Call the function to update the image
            });

            // Updated script to handle input focus
            document.addEventListener('DOMContentLoaded', function () {
                const sectionLinks = document.querySelectorAll('.section-link');
                const sectionContents = document.querySelectorAll('.section-content');
                const formInputs = document.querySelectorAll('.input-box input, .input-box select, .input-box textarea');
                const sectionNumbers = document.querySelectorAll('.section-number');
                const sectionLines = document.querySelectorAll('.section-line');
                let currentSection = 0;

                function markSectionCompleted(sectionIndex) {
                    sectionNumbers[sectionIndex].classList.remove('active');
                    sectionNumbers[sectionIndex].classList.add('completed');
                }

                function highlightLine(fromIndex, toIndex) {
                    if (fromIndex < toIndex) {
                        sectionLines[fromIndex].classList.add('active');
                    } else if (fromIndex > toIndex) {
                        sectionLines[toIndex].classList.remove('active');
                    }
                }

                function setActiveSection(sectionIndex) {
                    sectionLinks.forEach((link, index) => {
                        link.classList.toggle('active', index === sectionIndex);
                        sectionNumbers[index].classList.toggle('active', index === sectionIndex);
                    });
                    if (currentSection < sectionIndex) {
                        markSectionCompleted(currentSection);
                    }
                    highlightLine(currentSection, sectionIndex);
                    currentSection = sectionIndex;
                }

                function updateActiveSectionOnScroll() {
                    sectionContents.forEach((section, index) => {
                        const sectionRect = section.getBoundingClientRect();
                        if (sectionRect.top <= 150 && sectionRect.bottom >= 150) {
                            if (currentSection !== index) {
                                setActiveSection(index);
                            }
                        }
                    });
                }

                // Event Listener for Input Focus
                formInputs.forEach(input => {
                    input.addEventListener('focus', function () {
                        const parentSection = input.closest('.section-content');
                        if (parentSection) {
                            const sectionId = parentSection.id;
                            const activeSectionLink = document.querySelector(`a[href="#${sectionId}"]`);
                            const sectionIndex = Array.from(sectionLinks).indexOf(activeSectionLink);

                            if (sectionIndex !== -1) {
                                setActiveSection(sectionIndex);
                            }
                        }
                    });
                });

                // Event Listener for Scroll
                window.addEventListener('scroll', updateActiveSectionOnScroll);

                // Example usage: Marking the first section as completed on load
                markSectionCompleted(0);
            });


        </script>

    </main>

</body>
</html>
