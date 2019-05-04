<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="icon" type="image/png" href="images/logo-02.png">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700i" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<!-- body bg options are, pattern_bg full_bg -->
<body class="full_bg">
    <!-- dashboard end -->
    <div class="d_container d_box">
        <!-- dashboard header -->
        <header>
            <div>
                <i class="far fa-bookmark header_bookmark"></i>
                <a href=""><img class="header_logo" src="images/logo.png" alt=""></a>
            </div>
            <div>
                <div class="header_default_icons">
                    <i class="fas fa-search"></i>
                    <i class="far fa-check-circle"></i>
                </div>
                <div class="header_dropdown">
                    <div>
                        <h4>Russell Moore</h4>
                        <span class="header_job_title">Developer</span>
                        <div class="header_dropdown_menu">
                            <div>
                                <a href=""><i class="fas fa-user"></i> My Profile</a>
                                <a href=""><i class="fas fa-file-alt"></i> My Posts</a>
                                <a href=""><i class="fas fa-file-invoice"></i> My Contents</a>
                            </div>
                            <hr>
                            <div>
                                <h5>Book Marks</h5>
                                <div class="header_bookmark_list">
                                    <a href=""><i class="fas fa-bookmark"></i> add_edit_post 2/3/19</a><i class="fas fa-times"></i>
                                    <a href=""><i class="fas fa-bookmark"></i> add_edit_post 2/3/19</a><i class="fas fa-times"></i>
                                    <a href=""><i class="fas fa-bookmark"></i> add_edit_post 2/3/19</a><i class="fas fa-times"></i>
                                </div>
                            </div>
                            <hr>
                            <div>
                                <a href=""><i class="fas fa-power-off"></i> Log Out</a>
                            </div>
                        </div>
                    </div>
                    <img class="header_profile" src="images/profile.png" alt="">
                    <small><i class="fas fa-chevron-down"></i></small>
                </div>
            </div>
        </header>
        <!-- dashboard header end -->

        <!-- dashboard sidebar -->
        <div class="ds">
            <!-- sidebar top -->
            <div class="dst">
                <i class="fas fa-align-left"></i>
            </div>
            <!-- sidebar top end -->

            <!-- sidebar middle, class="active" -->
            <div class="dsm">
                <!-- //todo: not what final structure will look like -->
                <a href=""><i class="fas fa-th-large"></i><span> Main Dashboard</span></a>
                <a href="" class="active"><i class="fas fa-file-invoice"></i><span> Content</span></a>
                <a href=""><i class="fas fa-user-friends"></i><span> Users</span></a>
                <a href=""><i class="fas fa-file-alt"></i><span> Posts</span></a>
                <a href=""><i class="fas fa-folder"></i><span> Categories</span></a>
                <a href=""><i class="fas fa-tags"></i><span> Tags</span></a>
                <a href=""><i class="fas fa-list-alt"></i><span> Labels</span></a>
                <a href=""><i class="fas fa-comment-dots"></i><span> Comments</span></a>
                <a href=""><i class="fas fa-bookmark"></i><span> BookMarks</span></a>
                <a href=""><i class="fas fa-search"></i><span> Search</span></a>
                <!-- //todo: finish drop-down menus at some point -->
                <!-- <div class="dsm_dropdown">
                    <a href="" class="active"><i class="fas fa-file-invoice"></i><span> Content</span></a>
                    <div class="dsm_dropdown_menu">
                        <a href=""><i class="fas fa-file-invoice"></i><span> Main Dashboard</span></a>
                        <a href=""><i class="fas fa-file-invoice"></i><span> Main Dashboard</span></a>
                    </div>
                </div> -->
            </div>
            <!-- sidebar middle end -->

            <!-- sidebar bottom -->
            <div class="dsb">
                <a href="" class="btn_box"><i class="fas fa-lightbulb"></i></a>
                <a href="" class="btn_box ds_btn_expand"><i class="fas fa-expand-arrows-alt"></i></a>
            </div>
            <!-- sidebar bottom end -->
        </div>
        <!-- dashboard sidebar end -->

        <!-- dashboard body-->
        <div class="db">
        </div>
        <!-- dashboard body end -->

        <!-- dashboard modals -->
        <div class="dm">
            <div class="dmc_modal"></div>    
        </div>

        <!-- dashboard modals full dashboard layout -->
        <div class="dmf">
            <div class="dso_modal"></div>    
        </div>

        <!-- dashboard right sidebar options, right now just the to do list -->
        <div class="dso">
            <div class="todo_list">
                <div class="todo_list_i text_center">
                    <i class="far fa-check-circle"></i>
                    <hr>
                </div>
                <div class="todo_list_body">
                    <div class="todo_list_item">
                        <label class="checkbox_label">
                            <input type="checkbox" class="checkbox" /> <span>Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem, laboriosam.</span>
                        </label>
                        <a class=""><i class="fas fa-pencil-alt"></i></a>
                        <a class=""><i class="fas fa-trash"></i></a>
                    </div>
                    <div class="todo_list_item">
                        <label class="checkbox_label">
                            <input type="checkbox" class="checkbox" /> <span>Lorem ipsum dolor sit amet consectetur.</span>
                        </label>
                        <a class=""><i class="fas fa-pencil-alt"></i></a>
                        <a class=""><i class="fas fa-trash"></i></a>
                    </div>
                    <div class="todo_list_item">
                        <label class="checkbox_label">
                            <input type="checkbox" class="checkbox" checked/> <span>Lorem ipsum dolor sit amet conse.</span>
                        </label>
                        <a class=""><i class="fas fa-pencil-alt"></i></a>
                        <a class=""><i class="fas fa-trash"></i></a>
                    </div>
                    <div class="todo_list_item">
                        <label class="checkbox_label">
                            <input type="checkbox" class="checkbox" checked/> <span>Look up web site stuff.</span>
                        </label>
                        <a class=""><i class="fas fa-pencil-alt"></i></a>
                        <a class=""><i class="fas fa-trash"></i></a>
                    </div>
                    <div class="todo_list_item">
                        <label class="checkbox_label">
                            <input type="checkbox" class="checkbox" /> <span>Ask about routing.</span>
                        </label>
                        <a class=""><i class="fas fa-pencil-alt"></i></a>
                        <a class=""><i class="fas fa-trash"></i></a>
                    </div>
                    <div class="todo_list_item">
                        <label class="checkbox_label">
                            <input type="checkbox" class="checkbox" /> <span>Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem, laboriosam.</span>
                        </label>
                        <a class=""><i class="fas fa-pencil-alt"></i></a>
                        <a class=""><i class="fas fa-trash"></i></a>
                    </div>
                    <div class="todo_list_item">
                        <label class="checkbox_label">
                            <input type="checkbox" class="checkbox" /> <span>Lorem ipsum dolor sit amet consectetur.</span>
                        </label>
                        <a class=""><i class="fas fa-pencil-alt"></i></a>
                        <a class=""><i class="fas fa-trash"></i></a>
                    </div>
                    <div class="todo_list_item">
                        <label class="checkbox_label">
                            <input type="checkbox" class="checkbox" checked/> <span>Lorem ipsum dolor sit amet conse.</span>
                        </label>
                        <a class=""><i class="fas fa-pencil-alt"></i></a>
                        <a class=""><i class="fas fa-trash"></i></a>
                    </div>
                    <div class="todo_list_item">
                        <label class="checkbox_label">
                            <input type="checkbox" class="checkbox" checked/> <span>Look up web site stuff.</span>
                        </label>
                        <a class=""><i class="fas fa-pencil-alt"></i></a>
                        <a class=""><i class="fas fa-trash"></i></a>
                    </div>
                    <div class="todo_list_item">
                        <label class="checkbox_label">
                            <input type="checkbox" class="checkbox" /> <span>Ask about routing.</span>
                        </label>
                        <a class=""><i class="fas fa-pencil-alt"></i></a>
                        <a class=""><i class="fas fa-trash"></i></a>
                    </div>
                    <div class="todo_list_item">
                        <label class="checkbox_label">
                            <input type="checkbox" class="checkbox" /> <span>Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem, laboriosam.</span>
                        </label>
                        <a class=""><i class="fas fa-pencil-alt"></i></a>
                        <a class=""><i class="fas fa-trash"></i></a>
                    </div>
                    <div class="todo_list_item">
                        <label class="checkbox_label">
                            <input type="checkbox" class="checkbox" /> <span>Lorem ipsum dolor sit amet consectetur.</span>
                        </label>
                        <a class=""><i class="fas fa-pencil-alt"></i></a>
                        <a class=""><i class="fas fa-trash"></i></a>
                    </div>
                    <div class="todo_list_item">
                        <label class="checkbox_label">
                            <input type="checkbox" class="checkbox" checked/> <span>Lorem ipsum dolor sit amet conse.</span>
                        </label>
                        <a class=""><i class="fas fa-pencil-alt"></i></a>
                        <a class=""><i class="fas fa-trash"></i></a>
                    </div>
                    <div class="todo_list_item">
                        <label class="checkbox_label">
                            <input type="checkbox" class="checkbox" checked/> <span>Look up web site stuff.</span>
                        </label>
                        <a class=""><i class="fas fa-pencil-alt"></i></a>
                        <a class=""><i class="fas fa-trash"></i></a>
                    </div>
                    <div class="todo_list_item">
                        <label class="checkbox_label">
                            <input type="checkbox" class="checkbox" /> <span>Ask about routing.</span>
                        </label>
                        <a class=""><i class="fas fa-pencil-alt"></i></a>
                        <a class=""><i class="fas fa-trash"></i></a>
                    </div>
                    <div class="todo_list_item">
                        <label class="checkbox_label">
                            <input type="checkbox" class="checkbox" checked/> <span>Lorem ipsum dolor sit amet conse.</span>
                        </label>
                        <a class=""><i class="fas fa-pencil-alt"></i></a>
                        <a class=""><i class="fas fa-trash"></i></a>
                    </div>
                    <div class="todo_list_item">
                        <label class="checkbox_label">
                            <input type="checkbox" class="checkbox" checked/> <span>Look up web site stuff.</span>
                        </label>
                        <a class=""><i class="fas fa-pencil-alt"></i></a>
                        <a class=""><i class="fas fa-trash"></i></a>
                    </div>
                    <div class="todo_list_item">
                        <label class="checkbox_label">
                            <input type="checkbox" class="checkbox" /> <span>Ask about routing.</span>
                        </label>
                        <a class=""><i class="fas fa-pencil-alt"></i></a>
                        <a class=""><i class="fas fa-trash"></i></a>
                    </div>
                    <div class="todo_list_item">
                        <label class="checkbox_label">
                            <input type="checkbox" class="checkbox" checked/> <span>Lorem ipsum dolor sit amet conse.</span>
                        </label>
                        <a class=""><i class="fas fa-pencil-alt"></i></a>
                        <a class=""><i class="fas fa-trash"></i></a>
                    </div>
                    <div class="todo_list_item">
                        <label class="checkbox_label">
                            <input type="checkbox" class="checkbox" checked/> <span>Look up web site stuff.</span>
                        </label>
                        <a class=""><i class="fas fa-pencil-alt"></i></a>
                        <a class=""><i class="fas fa-trash"></i></a>
                    </div>
                    <div class="todo_list_item">
                        <label class="checkbox_label">
                            <input type="checkbox" class="checkbox" /> <span>Ask about routing.</span>
                        </label>
                        <a class=""><i class="fas fa-pencil-alt"></i></a>
                        <a class=""><i class="fas fa-trash"></i></a>
                    </div>
                </div>
                <div class="todo_list_bottom">
                    <form>
                        <input type="text">
                    </form>
                </div>
            </div>
        </div>
        <!-- dashboard right sidebar options end -->

    </div>
    <!-- dashboard end -->
    
    <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

    <script src="general.js"></script>
</body>
</html>