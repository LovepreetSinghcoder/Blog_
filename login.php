<?php

require 'db.php';

session_start();


// Redirect to index.php if logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
  header("Location: index.php");
  exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {




  $email = trim($_POST['email']);
  $password = $_POST['password'];

  $errors = [];

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address!";
  } else if (empty($password)) {
    $errors[] = "Password can't empty!";
  }


  if (empty($errors)) {

    $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');


    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      # code...

      //Now verifying the user
      if (password_verify($password, $user['password'])) {

        # code...

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        $_SESSION['logged_in'] = true;

        echo "<div class='alert alert-success'>Login Successfully!<div>";
        echo "<div class='alert alert-success'>loading...<div>";


        // header("Location: index.php");
        // exit;

        // Use JavaScript to redirect after session is set
        echo "<script type='text/javascript'>
         setTimeout(function(){
           window.location.href = 'index.php'; // Redirect to index.php after session is set
         }, 1000); // 1 second delay for the success message to be visible
       </script>";

        exit; // Ensure no further output is processed
      } else {
        echo "<div class='alert alert-danger'>Incorrect password.</div>";
      }
    } else {
      echo "<div class='alert alert-danger'>No account found with this email address!</div>";
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

  <title>Login</title>
</head>

<body>
  <!-- Section for navbar  -->

  <!-- Header Section -->
  <?php require 'headerbar.php' ?>

  <div class="container">
    <h1>Login </h1>
    <br>
    <hr>

    <form action="" method="POST">
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Email address</label>
        <input name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" />
        <div id="emailHelp" class="form-text">
          We'll never share your email with anyone else.
        </div>
      </div>
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Password</label>
        <input name="password" type="password" class="form-control" id="exampleInputPassword1" />
      </div>
      <div class="mb-3">
        <?php
        if (!empty($errors))
          foreach ($errors as $error) {
            echo "<p style='color:red'>$error</p>";
          }
        ?>
      </div>


      <button type="submit" class="btn text-light" style="background-color: #6610f2">Login</button>
    </form>
  </div>


  <!-- Footer Section  -->
  <?php require 'footer.php'?>
  


  <!-- Bootstrap js file  -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>