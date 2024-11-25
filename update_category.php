<?php


require 'db.php';

require 'utilities/utilities.php';


session_start();

// checkRole('user');
checkRole(['admin']);

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}





$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$category){
    echo "<div class='alert alert-danger'>Category not found!</div>";
    exit;
};


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
    $description = trim(filter_var($_POST['description'], FILTER_SANITIZE_STRING));
    $stmt = $pdo->prepare("UPDATE categories SET name = :name, description = :description WHERE id = :id");


    if ($stmt->execute([':name' => $name, ':description' => $description, ':id' => $id])) {
        header("Location: categories.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Failed to update category.</div>";
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
    <title>Update - Categories</title>
    <style>

    </style>
</head>

<body>
   

  <!-- Header Section -->
  <?php require 'headerbar.php'?>



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
                        Earning
                    </a>
                </li>
                <li>
                    <a href="profile.php" class="nav-link text-white">
                      
                        Profile
                    </a>
                </li>
                <li>
                    <a href="analytics.php" class="nav-link text-white">
                        Analytics
                    </a>
                </li>
                <?php
        if ($_SESSION['role'] == 'admin') {

          echo "<li>
          <a href='manage_users.php' class='nav-link text-white ' >
            Manage Blogs
          </a>
        </li>
        <li>
        <a href='manage_users.php' class='nav-link text-white ' >
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
            <h1 class="mb-5">Update category - <i><?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?></i>   ⊹ ࣪ ﹏𓊝﹏𓂁﹏⊹ ࣪ ˖</h1>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="InputText" class="form-label">Name</label>
                    <input name="name" type="text" class="form-control" id="InputText" value="<?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?>">

                </div>



                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" class="form-control" id="description" name="description"
                        placeholder="description of the category" value="<?= htmlspecialchars($category['description'], ENT_QUOTES, 'UTF-8') ?>">
                </div>


                <div class="mb-3">

                    <?php if (!empty($errors)) {
                        foreach ($errors as $error) {
                            echo "<p style='color:red'>$error</p>";

                        }
                    } ?>
                </div>

                <button type="submit" class="my-2 btn text-light" style="background-color: #6610f2">Update</button>



            </form>
            <br>
            <hr>

        
        </div>


    </div>
 
  <!-- Footer Section  -->
  <?php require 'footer.php'?>
  





    <!-- Bootstrap js file  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>