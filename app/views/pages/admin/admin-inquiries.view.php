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
                <h1>Inquiries</h1>
                <div class="greeting">
                <?php require APPROOT . '/views/components/user-greeting.view.php'; ?>
                </div>
            </div>

            <div class="content">
                <div class="inquiries-container">
                <div class="inquiries-header">
                    <h2>User Inquiries</h2>
                </div>
                
                <table class="table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                </div>
            </div>

        </main>
        
    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/admin-script.js?v=<?php echo time(); ?>"></script>
    <script>
        const tbody = document.querySelector('.table tbody');
        document.addEventListener("DOMContentLoaded", () => {
            fetch('<?php echo URLROOT; ?>/home/contact/api')
                .then(response => {
                    console.log("Response Status:", response.status);
                    return response.json();
                })
                .then(data =>{
                    const inquiries = Array.isArray(data.contact) ? data.contact :[];
                    if(inquiries.length === 0){
                        renderTable(null);
                    } else {
                        renderTable(inquiries);
                    }
                })
                .catch(error => {
                    console.error('Error fetching holidays:', error);
                    tableBody.innerHTML = `
                    <tr>
                        <td colspan="11" style="text-align: center;">Error loading data</td>
                    </tr>
                    `;
                });
        });

        function renderTable(inquiries) {
            tbody.innerHTML = "";

            if(inquiries.length === 0){
                const tr = document.createElement("tr");
                tr.innerHTML = `<td colspan="4" style="text-align: center;">No inquiries found.</td>`;
                tbody.appendChild(tr);
                return; 
            }

            inquiries.forEach(i=> {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${i.name}</td>
                    <td>${i.email}</td>
                    <td class="msg">${i.msg}</td>`;

                tbody.appendChild(tr);
            });
        }
    </script>
  </body>
</html>