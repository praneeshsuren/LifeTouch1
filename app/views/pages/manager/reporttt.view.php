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
        <h1 class="title">Reports</h1>

        <div class="ann">
            <section class="container">
                <form method="post" class="form">

                    <div class="input-box">
                        <label>Start Date</label>
                        <input type="date" placeholder="MM/DD/YY" name="date" />
                    </div>

                    <div class="input-box">
                        <label>End Date</label>
                        <input type="date" placeholder="MM/DD/YY" name="date" />
                    </div>

                    <div class="row">
                        <label for="type-list">Report Type</label><br>
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



                        <button type="submit" style="margin-top: 100px;">Generate Reprot</button>

                </form>
            </section>
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