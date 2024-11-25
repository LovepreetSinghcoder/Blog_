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


$blog_id = $_GET['id'];
$user_id = $_SESSION['user_id']; // Ensure the logged-in user owns the blog
$stmt = $pdo->prepare("SELECT * FROM blogposts WHERE id = ? AND user_id = ?");
$stmt->execute([$blog_id, $user_id]);
$blog = $stmt->fetch(PDO::FETCH_ASSOC);


// $id = $_GET['id'] ?? 0;

// $stmt = $pdo->prepare("SELECT * FROM blogs WHERE id = ?");
// $stmt->execute([$id]);
// $blog = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$blog) {
    echo "<div class='alert alert-danger'>Blog post not found!</div>";
    exit;
}
;

$stmt = $pdo->prepare("
    SELECT * 
    FROM categories 
");
$stmt->execute();

// $stmt = $pdo->prepare("SELECT * FROM blogs");
// $stmt->execute();
$categoriesdata = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim(filter_var($_POST['title'], FILTER_SANITIZE_STRING));
    $body = filter_var($_POST['content'], FILTER_SANITIZE_STRING);
    $categories = filter_var($_POST['categories'], FILTER_SANITIZE_STRING);
    $tags = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

    $errors = [];
    if (strlen($title) < 3) {
        $errors[] = "Please enter a valid title";
    }

    if (empty($errors)) {

        // $stmt = $pdo->prepare("UPDATE blogs SET title = :title, body = :body, categories = :categories,tags=:tags WHERE id = :id");
        $stmt = $pdo->prepare("UPDATE blogposts SET title = ?, body = ?, categories=?, tags=? WHERE id = ? AND user_id = ?");


        // if ($stmt->execute([':title' => $title, ':body' => $body, ':categories' => $categories, ':tags' => $tags, ':id' => $id])) {
        if ($stmt->execute([$title, $body, $categories, $tags, $blog_id, $user_id])) {
            header("Location: blogs.php");
            exit;
        } else {
            echo "<div class='alert alert-danger'>Failed to update blog.</div>";
        }
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
    <title>Update - Blog</title>
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
                        <!-- <svg class="bi me-2" width="16" height="16"><use xlink:href="#home"></use></svg> -->
                        Create +
                    </a>
                </li>
                <li>
                    <a href="dashboard.php" class="nav-link text-white">
                        <!-- <svg class="bi me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg> -->
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="blogs.html" class="nav-link text-white active" style="background-color: #6610f2">
                        <!-- <svg class="bi me-2" width="16" height="16"><use xlink:href="#table"></use></svg> -->
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
                    <a href="analytics.php" class="nav-link text-white">
                        <!-- <svg class="bi me-2" width="16" height="16"><use xlink:href="#people-circle"></use></svg> -->
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
            <h1>Update blog post  𓍯𓂃𓏧♡ </h1>
            <br>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="InputText" class="form-label">Title</label>
                    <input name="title" type="text" class="form-control" id="InputText"
                        value="<?php echo "{$blog['title']}" ?>">

                </div>
                <!-- <div class="mb-3">
          <label for="formFile" class="form-label">Post cover image</label>
          <input class="form-control" type="file" id="formFile">
        </div> -->


                <div class="form-floating">
                    <textarea name="content" class="form-control" placeholder="Leave a comment here"
                        id="floatingTextarea2" style="height: 200px"><?php echo "{$blog['body']}" ?></textarea>
                    <label for="floatingTextarea2">Content</label>
                </div>

                <div class="mb-3">
                    <label for="InputTags" class="form-label">Tags</label>
                    <input type="text" class="form-control" id="InputTags" name="tags"
                        placeholder="e.g., Technology, Coding (recomended min 3 tags)">
                </div>

                <div class="mb-3">
          <label for="categories" class="form-label">Categories</label>
          <select name="categories" id="categories" class="form-control">

            <?php foreach ($categoriesdata as $category) {
              echo "<option value='{$category['name']}' >{$category['name']}</option>";
            } ?>

          </select>
        </div>
                <div class="mb-3">

                    <?php if (!empty($errors)) {
                        foreach ($errors as $error) {
                            echo "<p style='color:red'>$error</p>";

                        }
                    } ?>
                </div>

                <button type="submit" class="my-2 btn text-light" style="background-color: #6610f2">Update</button>
                <button type="button" class="btn btn-secondary" onclick="previewPost()">Preview</button>

                <div id="previewModal"
                    style="display: none; border: 1px solid #ddd; padding: 10px; margin-top: 20px; border-radius : 5px;">
                    <h2 id="previewTitle"></h2>
                    <p id="previewContent"></p>
                </div>
            </form>
        </div>
    </div>
  
  <!-- Footer Section  -->
  <?php require 'footer.php'?>
  


    <!--Script -->

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