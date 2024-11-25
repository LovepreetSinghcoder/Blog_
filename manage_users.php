<?php
session_start();


require 'db.php'; // Include your database connection file
require 'utilities/utilities.php';



// checkRole('user');
checkRole(['admin']);
// Fetch all blogs from the database
// $user_id = $_SESSION['user_id']; 
// Get logged-in user's ID from the session

$stmt = $pdo->prepare("
    SELECT * 
    FROM users 
    
");
$stmt->execute();

// $stmt = $pdo->prepare("SELECT * FROM blogs");
// $stmt->execute();
$usersdata = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="Styles/adminstyle.css">
    <title>Admin - Users</title>
    <style>

    </style>
</head>

<body>

    <!-- Header Section   -->
    <?php require 'headerbar.php' ?>


    <!-- <h1>Writer Admin</h1> -->
    <div class="_container">
        <div class="_sidebarCont flex-shrink-0 p-3 text-white bg-dark">
            <a href="/"
                class="_ancLogo d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <span class="fs-4"><?php echo "{$_SESSION['user_name']}" ?></span>
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
                    <a href="blogs.html" class="nav-link text-white ">
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
                    <a href="analytics.php" class="nav-link text-white">
                        Analytics
                    </a>
                </li>

                <?php
                if ($_SESSION['role'] == 'admin') {

                    echo "<li>
          <a href='manage_blogs.php' class='nav-link text-white '>
            Manage Blogs
          </a>
        </li>
        <li>
        <a href='manage_users.php' class='nav-link text-white active' style='background-color: #6610f2' >
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
            <h1>Users ꔛ</h1>
            <br>
            <hr>
            <table class="table">
                <thead>
                    <tr>

                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th scope="col">Upgrade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($usersdata)): ?>
                        <div class="row">
                            <?php foreach ($usersdata as $users): ?>

                                <tr>

                                    <td><?= htmlspecialchars($users['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <th scope="row"><?= htmlspecialchars($users['email'], ENT_QUOTES, 'UTF-8') ?></th>
                                    <td><a href="#"
                                            class="btn text-danger border-danger p-0 px-2"><?= htmlspecialchars($users['role'], ENT_QUOTES, 'UTF-8') ?></a>
                                    </td>
                                    <td>
                                        <?php if ($users['role'] !== 'admin') {

                                            if ($users['role'] == 'user') { ?>

                                                <a href="upgrade_role.php?id=<?= $users['id'] ?>"
                                                    class="btn btn-warning p-0 px-2 me-2">UPGRADE</a>

                                            <?php } else { ?>
                                                <a href="degrade_role.php?id=<?= $users['id'] ?>"
                                                    class="btn btn-danger p-0 px-2 me-2">DEGRADE</a>
                                            <?php }
                                        } ?>
                                    </td>
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



    <!-- Bootstrap js file  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>