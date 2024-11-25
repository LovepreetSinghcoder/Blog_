
<header
    class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom bg-dark">
    <a href="/" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
      <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
        <use xlink:href="#bootstrap"></use>
      </svg>
    </a>

    <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
      <li>
        <a href="index.php" class="nav-link px-2 link-secondary">
          <h3><i>Blog_</i></h3>
        </a>
      </li>
      
    </ul>

    <?php

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
      // header("Location: index.php");
      // exit;
      echo "<div class='col-md-3 text-end mx-4'>
     
      <a href='profile.php'  class='btn text-light' style='background-color: #6610f2'> {$_SESSION['user_name']}</a>
    </div>";
    } else {
      // header("Location: index.php");
      // exit;
      echo "<div class='col-md-3 text-end mx-4'>
      <a href='login.php' class='btn btn-outline-primary me-2'>
        Login
      </a>
      <a href='signup.php' class='btn text-light' style='background-color: #6610f2'>Sign-up</a>
    </div>";
    }
    ?>
   
  </header>