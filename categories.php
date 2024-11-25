<?php


require 'db.php';
require 'utilities/utilities.php';


session_start();

checkRole(['admin']);

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Fetch all blogs from the database
$stmt = $pdo->prepare("SELECT * FROM categories");
$stmt->execute();
$categoriesdata = $stmt->fetchAll(PDO::FETCH_ASSOC);





if ($_SERVER['REQUEST_METHOD'] === 'POST') {



    $name = trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $errors = [];

    if (strlen($name) < 2) {
        $errors[] = "Please enter a valid name";
    }

    if (empty($errors)) {

        $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?,?)");


        if ($stmt->execute([$name, $description])) {

            echo "<div class='alert alert-success' role='alert'>Category created successfuly!</div>";
        } else {

            // $errorInfo = $stmt->errorInfo(); // Capture error info
            error_log("Database Error: " . $stmt->errorInfo()[2]);
            // echo "<div class='alert alert-danger' role='alert'> failed: " . $errorInfo[2] . "</div>";
            echo "<div class='alert alert-danger' role='alert'>An unexpected error occurred. Please try again later.</div>";
        }
        ;


    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="Styles/adminstyle.css">
    <title>Categories</title>
    <style>

    </style>
</head>

<body>
    <!-- Header section -->
    <?php require 'headerbar.php' ?>

    <!-- </section> -->
    <!-- <h1>Writer Admin</h1> -->
    <div class="_container">
        <div class="_sidebarCont flex-shrink-0 p-3 text-white bg-dark">
            <a href="/"
                class="_ancLogo d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <!-- <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg> -->
                <?php


                echo " <span class='fs-4'>{$_SESSION['user_name']}</span>";

                ?>
            </a>
            <hr />
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item mb-2">
                    <a href="create_blog.php" class="nav-link text-light border " aria-current="page">
                        Create +
                    </a>
                </li>
                <li>
                    <a href="dashboard.php" class="nav-link text-white">
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="blogs.php" class="nav-link text-white">
                        Blogs
                    </a>
                </li>

                <?php
                if ($_SESSION['role'] == 'admin') {

                    echo "<li>
          <a href='categories.php' class='nav-link text-white active' style='background-color:#6610f2'>
            Categories
          </a>
        </li>";
                }
                ;

                ?>

                <li>
                    <a href="#" class="nav-link text-white">
                        Earnings
                    </a>
                </li>
                <li><a href="profile.php" class="nav-link text-white">Profile</a></li>

                <li>
                    <a href="analytics.php" class="nav-link text-white">
                        Analytics
                    </a>
                </li>
                <?php
                if ($_SESSION['role'] == 'admin') {

                    echo "<li>
          <a href='manage_blogs.php' class='nav-link text-white'>
            Manage Blogs
          </a>
        </li>
        <li>
        <a href='manage_users.php' class='nav-link text-white'>
            Manage Users
          </a>
        </li>
        <li>
          <a href='website_analytics.php' class='nav-link text-white'>
            Website Analytics
          </a>
        </li>
        <li>
        <a href='settings.php' class='nav-link text-white'>
            Settings
          </a>
        </li>";
                }
                ;

                ?>
            </ul>
            <hr />

        </div>
        <div class="mainCont container mx-2 py-4 px-4">
            <h1 class="mb-5">Ꮺ Create category + </h1>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="InputText" class="form-label">Name</label>
                    <input name="name" type="text" class="form-control" id="InputText">

                </div>



                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" class="form-control" id="description" name="description"
                        placeholder="description of the category">
                </div>


                <div class="mb-3">

                    <?php if (!empty($errors)) {
                        foreach ($errors as $error) {
                            echo "<p style='color:red'>$error</p>";

                        }
                    } ?>
                </div>

                <button type="submit" class="my-2 btn text-light" style="background-color: #6610f2">Create</button>



            </form>
            <br>
            <hr>

            <div class="my-4">
                <h1 class="mb-5">Categories</h1>

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Edit/Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($categoriesdata)): ?>
                            <div class="row">
                                <?php foreach ($categoriesdata as $category): ?>

                                    <tr>
                                        <th scope="row"></th>
                                        <td><?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td> <?= htmlspecialchars(substr($category['description'], 0, 30), ENT_QUOTES, 'UTF-8') ?>...
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-secondary">
                                                    View
                                                </button>
                                                <button
                                                    onclick="{ window.location.href = 'update_category.php?id=<?= $category['id'] ?>'; }"
                                                    type="button" class="btn btn-sm btn-outline-warning ">
                                                    Edit
                                                </button>
                                                <button
                                                    onclick="if(confirm('Are you sure you want to delete this category?')) { window.location.href = 'delete_category.php?id=<?= $category['id'] ?>'; }"
                                                    type="button" class="btn btn-sm btn-outline-danger">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No data available at the moment.</p>
                        <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div>


    </div>

    <!-- Footer Section  -->
    <?php require 'footer.php' ?>






    <!-- Bootstrap js file  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>