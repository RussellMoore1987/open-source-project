<!-- TODO: HTML and code for the devtoolkit layout goes here..... -->

<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Development Toolkit</title>
  <meta name="description" content="Dev ToolKit">
  <meta name="author" content="John P">

  <link href="https://fonts.googleapis.com/css?family=Raleway&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="devtoolkitstyles.css">

</head>

<body>

    <section class="messages">
        <div class="message">
            <div class="message-text"><b>Success!</b> Some success message.</div>
            <div class="close-icon-container">
                <img class="close-icon" src="img/close_icon_green.png">
            </div>
        </div>
    </section>

    <section class="list-forms">
        <div class="search-container">
            <input class="search" type="text" placeholder="Search...">
        </div>
        <div class="card-container">
            <div class="card">
                <div class="card-title">Table Name</div>
                <form class="buttons-container">
                    <button class="create-button" type="button">Create</button>
                    <button class="sample-button" type="button">Sample Data</button>
                    <button class="drop-button" type="button">Drop</button>
                    <button class="edit-button" type="button">Add/Edit Records</button>
                    <button class="insert-button" type="button">Insert</button>
                    <input class="number-records" type="number" placeholder="# of Records">
                    <button class="all-button" type="button">Drop/Create/Insert</button>
                </form>
            </div>
            <div class="card">
                <div class="card-title">Table Name</div>
                <form class="buttons-container">
                    <button class="create-button" type="button">Create</button>
                    <button class="sample-button" type="button">Sample Data</button>
                    <button class="drop-button" type="button">Drop</button>
                    <button class="edit-button" type="button">Add/Edit Records</button>
                    <button class="insert-button" type="button">Insert</button>
                    <input class="number-records" type="number" placeholder="# of Records">
                    <button class="all-button" type="button">Drop/Create/Insert</button>
                </form>
            </div>
            <div class="card">
                <div class="card-title">Table Name</div>
                <form class="buttons-container">
                    <button class="create-button" type="button">Create</button>
                    <button class="sample-button" type="button">Sample Data</button>
                    <button class="drop-button" type="button">Drop</button>
                    <button class="edit-button" type="button">Add/Edit Records</button>
                    <button class="insert-button" type="button">Insert</button>
                    <input class="number-records" type="number" placeholder="# of Records">
                    <button class="all-button" type="button">Drop/Create/Insert</button>
                </form>
            </div>
        </div>
        <div class="main-card-container">
        </div>
    </section>

    <section class="table">
    </section>

  <script src="devtoolkitjavascript.js"></script>
</body>
</html>