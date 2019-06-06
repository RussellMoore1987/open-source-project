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

    <section class="messages-section">
        <div class="message">
            <div class="message-text"><b>Success!</b> Some success message.</div>
            <div class="close-icon-container">
                <img class="close-icon" src="img/close_icon_green.png">
            </div>
        </div>
    </section>

    <div class="main">
        <section class="list-forms-section">
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
                <div class="main-card">

                </div>
            </div>
        </section>

        <section class="table-section">
            <div class="table-title">Table Name Sample Data</div>
            <div class="table-container">
                <table>
                    <tr class="table-header">
                        <th>Somthing</th>
                        <th>Somthing</th>
                        <th>Somthing</th>
                        <th>Somthing</th>
                        <th>Somthing</th>
                        <th>Somthing</th>
                        <th>Somthing</th>
                        <th>Somthing</th>
                        <th>Somthing</th>
                        <th>Somthing</th>
                        <th>Somthing</th>
                        <th>Somthing</th>
                        <th>Somthing</th>
                        <th>Somthing</th>
                    </tr>
                    <tr>
                        <td>Data</td>
                        <td>Data</td>
                        <td>Data</td>
                        <td>Data</td>
                        <td>Data</td>
                        <!-- <td>Data</td>
                        <td>Data</td>
                        <td>Data</td>
                        <td>Data</td>
                        <td>Data</td>
                        <td>Data</td>
                        <td>Data</td>
                        <td>Data</td> -->
                    </tr>
                </table>
            </div>
            <div class="pagination-container"></div>
        </section>
    </div>

  <script src="devtoolkitjavascript.js"></script>
</body>
</html>