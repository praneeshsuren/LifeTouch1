<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- STYLESHEET -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/admin-style.css?v=<?php echo time(); ?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title><?php echo APP_NAME; ?></title>
  </head>

  <body>
    <section class="sidebar">
        <?php require APPROOT . '/views/components/admin-sidebar.view.php'; ?>
    </section>
    
    <main>
        <div class="title">
        <h1>View Event</h1>
            <div class="greeting">
                <?php require APPROOT . '/views/components/user-greeting.view.php'; ?>
            </div>
        </div>

        <div class="event-detail-container">
            <div class="form-container">
                <form id="eventDetailForm" action="" method="POST">
                    <input type='hidden'id="id" name="id" disabled/>
                    <div class="event-details-grid">
                        <div class="detail-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" disabled>
                        </div>
                        <div class="detail-group">
                            <label for="eamil">Email</label>
                            <input type="text" id="email" name="email" disabled>
                        </div>
                    </div>

                    <div class="event-description">
                        <h2>Message</h2>
                        <textarea  id="msg" name="msg" disabled></textarea>
                    </div>

                    <div class="event-actions">
                        <div class="view-actions">
                            <button type="button" class="btn edit-btn" id="editButton">Reply</button>
                    </div>
                </form>
            </div>
        </div>

      
    </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/admin-script.js?v=<?php echo time(); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const inquiryId = urlParams.get('id');
            if(inquiryId) {
                fetch('<?php echo URLROOT; ?>/admin/inquiries/viewInquiryapi?id=' + inquiryId)
                    .then(response => {
                        console.log('Response Status:', response.status); // Log response status
                        return response.json();
                    })
                    .then(data => {
                        console.log("inquiry:",data);
                        document.getElementById("id").value = data.id;
                        document.getElementById("name").value = data.name;
                        document.getElementById("email").value = data.email;
                        document.getElementById("msg").value = data.msg;
                    })
                    .catch(error => console.error('Error fetching inquiry details:', error));
            } 
        });
    </script>

  </body>
</html>