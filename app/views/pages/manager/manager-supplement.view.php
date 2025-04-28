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
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/manager-style.css?v=<?php echo time(); ?>" />
  <!-- ICONS -->
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
  <!-- CHART.JS -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <title><?php echo APP_NAME; ?></title>
</head>

<body>

    <section class="sidebar">
        <?php require APPROOT . '/views/components/manager_sidebar.view.php' ?>
    </section>

    <main>
        <div class="title">
            <h1>Supplements</h1>
            <div class="greeting">
                <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
            </div>
        </div>

        <div class="newMember">
            <div class="retrieve-users" style="margin-left: 20px;">
                <div class="searchBar" style="width: 300px;">
                    <input
                        type="text"
                        id="supplementSearch"
                        placeholder="Search by Supplement name..."
                        onkeyup="filterTable()" />
                </div>
            </div>

            <div class="heading">
                <a href="createSupplement" class="newMember-btn"><i class=" ph ph-plus"></i> Add Supplement</a>
            </div>
        </div>

        <!-- View-Supplement-section-->

        <div class="member-view-trainer">

            <?php if (!empty($supplement)): ?>
                <?php foreach ($supplement as $item): ?>
                    <div class="trainer">
                        <img src="<?php echo URLROOT; ?>/assets/images/Supplement/<?php echo htmlspecialchars($item->file); ?>" alt="Supplement Image" class="trainer-image" />
                        <h3><?php echo htmlspecialchars($item->name); ?></h3>
                        <a href="supplement_view/<?php echo $item->supplement_id; ?>">
                            <button class="member-view-trainer-btn">View</button>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No supplements available.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time();?>"></script>
    <script>
    function filterTable() {
        const input = document.getElementById("supplementSearch");
        const filter = input.value.toLowerCase();
        const trainers = document.querySelectorAll(".member-view-trainer .trainer");

        trainers.forEach(trainer => {
            const name = trainer.querySelector("h3").textContent.toLowerCase();
            trainer.style.display = name.includes(filter) ? "" : "none";
        });
    }
</script>

</body>