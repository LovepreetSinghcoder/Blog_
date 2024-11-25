<?php




require 'db.php'; // Include your database connection file
require 'utilities/utilities.php';


session_start();

// checkRole('user');
checkRole(['admin', 'editor', 'user']);


if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// echo "{$_SESSION}";
// Fetch all blogs from the database
$user_id = $_SESSION['user_id']; // Get logged-in user's ID from the session


$stmt = $pdo->prepare("
    SELECT * 
    FROM users 
    WHERE id = ?
");
$stmt->execute([$user_id]);

// $stmt = $pdo->prepare("SELECT * FROM blogs");
// $stmt->execute();
$userdata = $stmt->fetch(PDO::FETCH_ASSOC);






// Prepare data for JavaScript




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // $userId = $_POST['user_id'];
    $newRole = $_POST['role'];

    if (in_array($newRole, ['admin', 'editor', 'user'])) {
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$newRole, $user_id]);
        $_SESSION['role'] = $newRole;

        echo "<div class='alert alert-success'> Role updated successfully!</div>";
    } else {
        echo "Invalid role!";
    }
}

// $userId = $_GET['id'];
// $stmt = $pdo->prepare("SELECT id, name, role FROM users WHERE id = ?");
// $stmt->execute([$userId]);
// $user = $stmt->fetch(PDO::FETCH_ASSOC);



// $user['role'] = 'user';

// echo "{$_SESSION['role']}";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="Styles/adminstyle.css">
    <title>Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <a href="profile.php" class="nav-link active text-white" style="background-color: #6610f2">
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
          <a href='manage_blogs.php' class='nav-link text-white'>
            Manage Blogs
          </a>
        </li>
        <li>
        <a href='manage_    users.php' class='nav-link text-white'>
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

            <h3>Profile ₊ ⊹</h3>
            <br>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="InputText" class="form-label">Name</label>
                    <input value="<?= $userdata['name'] ?> " name="name" type="text" class="form-control" id="InputText"
                        readonly>

                </div>



                <div class="mb-3">
                    <label for="description" class="form-label">Email</label>
                    <input type="text" class="form-control" id="description" name="description"
                        value="<?= $userdata['email'] ?>" readonly>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Select Role:</label>
                    <select name="role" id="role" class="form-control">
                        <!-- <option value="editor">Editor</option>
                        <option value="user" >User</option> -->

                        <option value="editor" <?= $userdata['role'] === 'editor' ? 'selected' : '' ?>>Editor</option>
                        <option value="user" <?= $userdata['role'] === 'user' ? 'selected' : '' ?>>User</option>
                    </select>
                </div>




                <button type="submit" class="my-2 btn text-light" style="background-color: #6610f2">Update role</button>



            </form>

            <br>
            <!-- <hr>
            <h3>Trending Post ✧₊⁺</h3>
            <br> -->

            <a href="logout.php" class="my-2 btn text-light" style="background-color: #6610f2">Log out</a>





        </div>
    </div>
 
  <!-- Footer Section  -->
  <?php require 'footer.php'?>
  


    <!-- Script -->







    <!-- Bootstrap js file  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>