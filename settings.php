<?php


require 'db.php';

session_start();
require 'utilities/utilities.php';



// checkRole('user');
checkRole(['admin', 'editor']);



if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT * 
    FROM blogposts 
    ORDER BY views DESC 
    LIMIT 50
");
$stmt->execute();
$top_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for JavaScript
$blog_titles = array_column($top_posts, 'title');
$blog_views = array_column($top_posts, 'views');


$stmt = $pdo->prepare("SELECT SUM(views) AS total_views FROM blogposts");
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$total_views = $result['total_views'] ?? 0;


// $stmt = $pdo->prepare("
//     SELECT * 
//     FROM blogposts 
//     ORDER BY views DESC 
//     LIMIT 1
// ");
// $stmt->execute();
// $top_post = $stmt->fetch(PDO::FETCH_ASSOC);




$stmt = $pdo->prepare("
    SELECT * 
    FROM blogposts 
    ORDER BY views DESC 
    LIMIT 1
");
$stmt->execute();
$top_post = $stmt->fetch(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="Styles/adminstyle.css">
    <title>Admin - Analytics </title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>

    </style>
</head>

<body>
    <!-- Header Section -->
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
                    <a href="create_blog.php" class="nav-link text-light border " aria-current="page"
                        style="border-color: #6610f2">
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
          <a href='categories.php' class='nav-link text-white'>
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
                <li>
                    <a href="profile.php" class="nav-link text-white">
                        Profile
                    </a>
                </li>
                <li>
                    <a href="analytics.php" class="nav-link  text-white">
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
          <a href='website_analytics.php' class='nav-link  text-white' >
            Website Analytics
          </a>
        </li>
        <li>
        <a href='settings.php' class='nav-link text-white active' style='background-color: #6610f2'>
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


            <h3>Settings ⋆｡ﾟ☁︎｡⋆｡ ﾟ☾ ﾟ｡⋆</h3>
         


        </div>
    </div>


    <!-- Footer Section  -->
    <?php require 'footer.php' ?>


    <!-- Script -->


    <script>
        function previewPost() {
            const title = document.getElementById('InputText').value;
            const content = document.getElementById('floatingTextarea2').value;

            document.getElementById('previewTitle').textContent = title;
            document.getElementById('previewContent').textContent = content;

            document.getElementById('previewModal').style.display = 'block';
        }
    </script>


    <!-- Bootstrap js file  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>