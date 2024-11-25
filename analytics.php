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

$user_id = $_SESSION['user_id'];

// echo "$user_id";
$stmt = $pdo->prepare("
    SELECT * 
    FROM blogposts 
    WHERE user_id = ?
    ORDER BY views DESC 
    LIMIT 50 
");
$stmt->execute([$user_id]);
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

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="Styles/adminstyle.css">
    <title>Analytics</title>
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
        <li><a href="profile.php" class="nav-link text-white">Profile</a></li>
                <li>
                    <a href="analytics.php" class="nav-link active text-white" style="background-color: #6610f2">
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

            
           
            <!-- <hr> -->
            <h3>Trending Posts °❀⋆.ೃ࿔*:･</h3>
            <br>
            <canvas id="blogViewsChart" width="400" height="200"></canvas>
            <hr>
            <table class="table">
                <thead>
                    <tr>

                        <th scope="col">Title</th>
                        <th scope="col">Views</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($top_posts)): ?>
                        <div class="row ">
                            <?php foreach ($top_posts as $blog): ?>

                                <tr>

                                    <td><?= htmlspecialchars($blog['title'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <th scope="row"><?= htmlspecialchars($blog['views'], ENT_QUOTES, 'UTF-8') ?></th>
                                    <!-- <td><a href="#" class="btn text-danger border-danger p-0 px-2">Live</a></td>
                  <td>
                    <div class="btn-group"></div><a href="update_blog.php?id=<?= $blog['id'] ?>"
                      class="btn btn-warning p-0 px-2 me-2">Edit</a><a href="delete_blog.php?id=<?= $blog['id'] ?>"
                      class="btn btn-danger p-0 px-2">Delete</a>
                  </td> -->
                                </tr>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No blogs available at the moment.</p>
                    <?php endif; ?>

                </tbody>
            </table>



        </div>
    </div>


    <!-- Footer Section  -->
    <?php require 'footer.php' ?>


    <!-- Script -->
    <script>
        const blogTitles = <?= json_encode($blog_titles) ?>;
        const blogViews = <?= json_encode($blog_views) ?>;

      


            // Function to shorten titles
const truncateTitle = (title, wordLimit) => {
  const words = title.split(' ');
  if (words.length > wordLimit) {
    return words.slice(0, wordLimit).join(' ') + '...';
  }
  return title;
};


const ctx = document.getElementById('blogViewsChart').getContext('2d');


// Shorten each title to a maximum of 3 words (or adjust as needed)
const newblogTitles = blogTitles.map(title => truncateTitle(title, 3));

        const blogViewsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: newblogTitles,
                datasets: [{
                    label: 'Blog Views',
                    data: blogViews,
                    backgroundColor: '#8e53ed',
                    borderColor: '#6610f0',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <!-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js"></script>
        <script>
          tinymce.init({
            selector: '#floatingTextarea2',
            plugins: 'link image media table',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | link image media table',
          });
        </script> -->


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