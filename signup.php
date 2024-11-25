<?php

require 'db.php';


session_start();



// Redirect to index.php if logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
  header("Location: index.php");
  exit;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $role = 'user'; // Default role
  if (isset($_POST['role'])) {
    $role = in_array($_POST['role'], ['admin', 'editor', 'user']) ? $_POST['role'] : 'user';
  }
  $name = trim($_POST['name']); // Remove unnecessary spaces
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  $errors = []; // Array to store validation errors


  // echo "THis is the data from the form: $name , $email and $password" ;

  if (empty($name) || strlen($name) < 3) {
    $errors[] = "Name must be at least 3 characters long.";
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format";
  } else if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }

  // else {
  //   echo "OK";
  //   echo "THis is the data from the form: $name , $email and $password";
  // }

  if (empty($errors)) {

    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');


    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // echo "This is the data after validation: $name, $email and $hashedPassword";

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password,role) VALUES (?,?,?,?)");

    if ($stmt->execute([$name, $email, $hashedPassword, $role])) {


      // Retrieve the ID of the newly created user
      $userId = $pdo->lastInsertId();

      // $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_name'] = $name;
      $_SESSION['id'] = $userId;

      $_SESSION['role'] = $role;
      $_SESSION['logged_in'] = true;
      echo "<div class='alert alert-success' role='alert'>Registration successful!</div>";
      echo "<div class='alert alert-success'>loading...<div>";

      // Use JavaScript to redirect after session is set
      echo "<script type='text/javascript'>
       setTimeout(function(){
         window.location.href = 'index.php'; // Redirect to index.php after session is set
       }, 1000); // 1 second delay for the success message to be visible
     </script>";

      exit; // Ensure no further output is processed

    } else {
      // echo "<div class='alert alert-danger' role='alert'>Registration failed!</div>";
      $errorInfo = $stmt->errorInfo(); // Capture error info
      echo "<div class='alert alert-danger' role='alert'>Registration failed: " . $errorInfo[2] . "</div>";
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

  <title>Sign Up</title>
</head>

<body>
  <!-- Section for navbar  -->

  <!-- Header Section -->
  <?php require 'headerbar.php'?>

  <div class="container">
    <h1>Sign Up</h1>
    <br />
    <hr />

    <form action="" method="POST">
      <div class="mb-3">
        <label for="InputName" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="InputName" name="name" />
        <br />

        <label for="InputEmail1" class="form-label">Email address</label>
        <input type="email" class="form-control" id="InputEmail1" name="email" aria-describedby="emailHelp" />
        <div id="emailHelp" class="form-text">
          We'll never share your email with anyone else.
        </div>
      </div>
      <div class="mb-3">
        <label for="InputPassword1" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" id="InputPassword1" />
      </div>
      <div>
        <?php if (!empty($errors)) {
          foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
          }
        } ?>
      </div>

      <button type="submit" class="btn text-light" style="background-color: #6610f2">Create account</button>
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