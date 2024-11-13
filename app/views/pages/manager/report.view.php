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
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <!-- STYLESHEET -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/manager-style.css?v=<?php echo time(); ?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title><?php echo APP_NAME; ?></title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>

<body>

    <section class="sidebar">
        <?php require APPROOT . '/views/components/manager_sidebar.view.php' ?>
    </section>

    <main>

        <div class="top">
            <h1 class="title">Reports</h1>
            <div class="bell">
                <i class="ph ph-bell"></i>
                <p>Hi, John!</p>
            </div>
        </div>

        <div class="dropdown-container">
            <div class="heading">
                <h2>View Report</h2>
                <a href="report_main" class="btn">New Report</a>
            </div>

            <div class="row">
                <label for="type-list">Report Types</label><br>
                <div class="select">
                    <div
                        class="selected"
                        data-default="Type 01"
                        data-one="Type 02"
                        data-two="Type 03"
                        data-three="Type 04">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            height="1em"
                            viewBox="0 0 512 512"
                            class="arrow">
                            <path
                                d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"></path>
                        </svg>
                    </div>
                    <div class="options">
                        <div title="all">
                            <input id="all" name="option" type="radio" checked="" />
                            <label class="option" for="all" data-txt="Type 01"></label>
                        </div>
                        <div title="option-1">
                            <input id="option-1" name="option" type="radio" />
                            <label class="option" for="option-1" data-txt="Type 02"></label>
                        </div>
                        <div title="option-2">
                            <input id="option-2" name="option" type="radio" />
                            <label class="option" for="option-2" data-txt="Type 03"></label>
                        </div>
                        <div title="option-3">
                            <input id="option-3" name="option" type="radio" />
                            <label class="option" for="option-3" data-txt="Type 04"></label>
                        </div>
                    </div>
                </div>



            </div>
            <div class="row">
                <label for="type-list">Data Range</label><br>
                <select name="type" id="type-list" class="input-boxes">
                    <option value disabled selected>Select a data range</option>
                    <?php foreach ($dataRange as $type): ?>
                        <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="heading">
                <h3>Revenue by membership type</h3>
            </div>
            <!--chart-->
            <div class="chart">
                <ul class="numbers">
                    <li><span>100%</span></li>
                    <li><span>50%</span></li>
                    <li><span>0%</span></li>
                </ul>
                <ul class="bars">
                    <li>
                        <div class="bar" data-percentage="60"><span>Monthly</span></div>
                    </li>
                    <li>
                        <div class="bar" data-percentage="80"><span>Drop-In</span></div>
                    </li>
                    <li>
                        <div class="bar" data-percentage="30"><span>Annual</span></div>
                    </li>
                </ul>
            </div>

            <div class="heading1">
                <h3>Members age category</h3>
            </div>
            <!--chart-->
            <div class="chart">
                <ul class="numbers">
                    <li><span>100%</span></li>
                    <li><span>50%</span></li>
                    <li><span>0%</span></li>
                </ul>
                <ul class="bars">
                    <li>
                        <div class="bar" data-percentage="20"><span>Adult</span></div>
                    </li>
                    <li>
                        <div class="bar" data-percentage="60"><span>Kids</span></div>
                    </li>
                    <li>
                        <div class="bar" data-percentage="80"><span>Elder</span></div>
                    </li>
                </ul>
            </div>
        </div>



    </main>
    <script type="text/javascript">
        $(function() {
            $('.bars li .bar').each(function() {
                var percentage = $(this).data('percentage');
                $(this).animate({
                    height: percentage + '%'
                }, 1000);
            });
        });
    </script>
    <script src="<?php echo URLROOT; ?>/assets/js/manager-script.js?v=<?php echo time(); ?>"></script>
</body>

</html>