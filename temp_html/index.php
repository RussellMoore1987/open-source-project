<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="icon" type="image/png" href="images/logo-02.png">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<!-- pattern_bg full_bg -->
<body class="full_bg">
    <div class="dashboard_container box">
        <header>
            <div>
                <i class="far fa-bookmark header_bookmark"></i>
                <a href=""><img class="logo" src="images/logo.png" alt=""></a>
            </div>
            <div>
                <div class="header_default_icons">
                    <i class="fas fa-search"></i>
                    <i class="far fa-check-circle"></i>
                </div>
                <div class="dropdown">
                    <div>
                        <h4>Russell Moore</h4>
                        <span>Developer</span>
                        <div class="dropdown_menu">
                            <div>
                                <a href=""><i class="fas fa-user"></i> My Profile</a>
                                <a href="" class="my_posts"><i class="fas fa-scroll"></i> My Posts</a>
                                <a href=""><i class="fas fa-file-invoice"></i> My Contents</a>
                            </div>
                            <hr>
                            <div>
                                <h5>Book Marks</h5>
                                <a href=""><i class="fas fa-bookmark"></i> add_edit_post 2/3/19 <i class="fas fa-times"></i></a>
                                <a href=""><i class="fas fa-bookmark"></i> add_edit_post 2/3/19 <i class="fas fa-times"></i></a>
                                <a href=""><i class="fas fa-bookmark"></i> add_edit_post 2/3/19 <i class="fas fa-times"></i></a>
                            </div>
                            <hr>
                            <div>
                                <a href=""><i class="fas fa-power-off"></i> Log Out</a>
                            </div>
                        </div>
                    </div>
                    <img class="profile" src="images/profile.png" alt="">
                    <small><i class="fas fa-chevron-down"></i></small>
                </div>
            </div>
        </header>
        <div class="dashboard_sidebar">
            <div class="dashboard_sidebar_top"></div>
            <div class="dashboard_sidebar_middle"></div>
            <div class="dashboard_sidebar_bottom"></div>
        </div>
        <!-- right now just the to do list -->
        <div class="dashboard_sidebar_options"></div>
        <div class="dashboard_body"></div>
    </div>
    
    <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

    <script src="general.js"></script>
</body>
</html>