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
    FROM categories 
");
$stmt->execute();

// $stmt = $pdo->prepare("SELECT * FROM blogs");
// $stmt->execute();
$categoriesdata = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // $title = trim($_POST['title']);
  $body = $_POST['content'];
  // $categories = $_POST['categories'];
  // $tags = $_POST['tags'];

  $title = trim(filter_var($_POST['title'], FILTER_SANITIZE_STRING));
  // $body = filter_var($_POST['content'], FILTER_SANITIZE_STRING);

  $categories = filter_var($_POST['categories'], FILTER_SANITIZE_STRING);
  $tags = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);
  $user_id = $_SESSION['user_id']; // Get logged-in user's ID from the session

  // File upload configuration
  $uploadDir = 'uploads/'; // Directory to store uploaded files
  $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; // Allowed MIME types


  $errors = [];

  if (strlen($title) < 3) {
    $errors[] = "Please enter a valid title";
  }

  // $testing= isset($_FILES['cover_image']);
  
  // echo "This is the value : $testing";
  // var_dump($_FILES);
  
  // echo "isset({$_FILES['cover_image']})";
  
  if (empty($errors)) {
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
      $fileTmpPath = $_FILES['cover_image']['tmp_name'];
      $fileName = $_FILES['cover_image']['name'];
      $fileSize = $_FILES['cover_image']['size'];
      $fileType = $_FILES['cover_image']['type'];
      $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
      $newFileName = uniqid() . '.' . $fileExt; // Unique filename
  
      // Validate file type
      if (!in_array($fileType, $allowedTypes)) {
          echo "Invalid file type. Please upload a JPG, PNG, or GIF image.";
          exit;
      }
  
      // Move the file to the upload directory
      $destPath = $uploadDir . $newFileName;
      if (move_uploaded_file($fileTmpPath, $destPath)) {
          // Save blog post details and file path to the database
          // $stmt = $pdo->prepare("INSERT INTO blogposts (user_id, title, content, cover_image) VALUES (?, ?, ?, ?)");
          // if ($stmt->execute([$user_id, $title, $content, $destPath])) {
          //     echo "Blog post created successfully!";
          // } else {
          //     echo "Failed to save blog post.";
          // }
  
          $stmt = $pdo->prepare("INSERT INTO blogposts (title, body,categories,tags, user_id,cover_image) VALUES (?,?,?,?,?,?)");
          $stmt->bindParam(':title', $title, PDO::PARAM_STR);
          $stmt->bindParam(':body', $body, PDO::PARAM_STR);
      
      
      
          if ($stmt->execute([$title, $body, $categories, $tags, $user_id, $destPath])) {
      
            echo "<div class='alert alert-success' role='alert'>Blog uploaded successful!</div>";
          } else {
      
            // $errorInfo = $stmt->errorInfo(); // Capture error info
            error_log("Database Error: " . $stmt->errorInfo()[2]);
            // echo "<div class='alert alert-danger' role='alert'> failed: " . $errorInfo[2] . "</div>";
            echo "<div class='alert alert-danger' role='alert'>An unexpected error occurred. Please try again later.</div>";
          }
          ;
  
  
      } else {
          echo "There was an error uploading the file.";
      }
  } else {
      echo "No file uploaded or upload error occurred.";
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

  <title>Admin</title>
  <style>
._cardImg {
      width: 100%;
      height: 300px;
      object-fit: cover;
    }
  </style>

<script loading="lazy" src="https://cdn.tiny.cloud/1/kfgmgtxouae8pyknv1sbz80i7imnfe4h7onw3s4iqkgbd1mp/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

</head>

<body>

  <!-- Header Section -->
  <?php require 'headerbar.php' ?>


  <!-- </section> -->
  <!-- <h1>Writer Admin</h1> -->
  <div class="_container">
    <div class="_sidebarCont flex-shrink-0 p-3 text-white bg-dark">
      <a href="/" class="_ancLogo d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <!-- <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg> -->
        <?php


        echo " <span class='fs-4'>{$_SESSION['user_name']}</span>";

        ?>
      </a>
      <hr />
      <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-2">
          <a href="create_blog.php" class="nav-link active" aria-current="page" style="background-color: #6610f2">
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
      <h1>Create post +</h1>

      <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="InputText" class="form-label">Title</label>
          <input name="title" type="text" class="form-control" id="InputText">

        </div>
        <div class="mb-3">
          <label for="cover_image" class="form-label">Post cover image</label>
          <input class="form-control" type="file" id="formFile" name="cover_image" required>
          <!-- <input type="file" name="cover_image"> -->
        </div>



        <div class="form-floating">
          <textarea name="content" class="form-control" placeholder="Leave a comment here" id="floatingTextarea2"
            style="height: 200px"></textarea>
          <!-- <label for="floatingTextarea2">Content</label> -->
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

        <button type="submit" class="my-2 btn text-light" style="background-color: #6610f2">Create</button>
        <button type="button" class="btn btn-secondary" onclick="previewPost()">Preview</button>

        <div id="previewModal"
          style="display: none; border: 1px solid #ddd; padding: 10px; margin-top: 20px; border-radius : 5px;">
          
          <img src="Images/tshirtdesign2.jpg" class="card-img-top _cardImg mb-2" alt="..." />
          <h2 id="previewTitle"></h2>
          <p id="previewContent"></p>
        </div>
      </form>
    </div>
  </div>

  <!-- Footer Section  -->
  <?php require 'footer.php' ?>



  <!--Script -->

  <script src="https://cdn.tiny.cloud/1/kfgmgtxouae8pyknv1sbz80i7imnfe4h7onw3s4iqkgbd1mp/tinymce/5/tinymce.min.js"></script>
        <script>
          tinymce.init({
            selector: '#floatingTextarea2',
            plugins: 'link image media table',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | link image media table',
          });
        </script>


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