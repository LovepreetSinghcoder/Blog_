<?php
session_start();

// Redirect to login if not logged in
// if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
//     header("Location: login.php");
//     exit;
// }







require 'db.php'; // Include your database connection file

function limitWords($text, $limit) {
  // Split the text into an array of words
  $words = explode(' ', $text);

  // If the number of words is less than or equal to the limit, return the text as is
  if (count($words) <= $limit) {
      return $text;
  }

  // Otherwise, slice the array to the limit and add ellipsis
  $limitedWords = array_slice($words, 0, $limit);
  return implode(' ', $limitedWords) . '...';
}

// Fetch all blogs from the database
// $stmt = $pdo->prepare("SELECT * FROM blogposts");
$stmt = $pdo->prepare("SELECT * FROM blogposts ORDER BY created_at DESC");

$stmt->execute();
$blogsdata = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Fetch all blogs from the database
$stmt = $pdo->prepare("SELECT * FROM categories");
$stmt->execute();
$categoriesdata = $stmt->fetchAll(PDO::FETCH_ASSOC);




// var_dump($_SESSION);
// echo "{$_SESSION['role']}";
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
  <style>
    ._cardImgFeat {
      /* width: 100%; */
      height: 100%;
      object-fit: cover;
      /* filter: blur(5px);  */
      /* Apply a subtle blur effect */
      /* clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 100%); */
      /* clip-path: polygon(0% 0%, 100% 0%, 100% 99%, 13% 88%); */
      clip-path: polygon(0% 2%, 100% 0%, 98% 100%, 13% 88%);
      /* Create a diagonal fade effect */
    }

    ._cardImg {
      width: 100%;
      height: 300px;
      object-fit: cover;
    }

    /* Flexbox container to align content */
    ._featuredCard {
      display: flex;
      justify-content: space-between;
      /* Pushes content to left, image to right */
      align-items: center;
      /* Vertically aligns the content */
    }

    /* Image container with fade effect on the left */
    .image-container {
      position: relative;
      width: 50%;
      /* Adjust as per your desired width */
    }

    /* Add fade effect on the left side of the image */
    .image-container img {
      width: 100%;
      height: auto;
      object-fit: cover;
    }

    .image-container::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 50%;
      /* The width of the fade */
      height: 100%;
      /* background: linear-gradient(
          to right,
          rgba(0, 0, 0, 0.7),
          rgba(0, 0, 0, 0)
        ); */
      pointer-events: none;
      /* Ensures that the fade does not interfere with image interactions */
    }

    /* Responsive Styles */
    @media (max-width: 992px) {
      ._featuredCard {
        padding: 20px !important;
      }

      ._featuredCard div h1 {
        font-size: 30px;
        font-weight: 400;
        /* background-color: red; */
        padding: 5px;
        border-radius: 12px;
        margin: 0 10px 0 0;
      }
    }

    @media (max-width: 768px) {
      ._featuredCard {
        flex-direction: column;
        /* Stack content and image vertically */
      }

      .col-md-6 {
        flex: none;
        /* Reset flex behavior */
        width: 100%;
        /* Take full width */
      }

      ._cardImgFeat {
        width: 100%;
        object-fit: cover;
        /* filter: blur(5px);  */
        /* Apply a subtle blur effect */
        /* clip-path: polygon(0 10%, 100% 100%, 100% 100%, 50% 100%); */
        /* clip-path: polygon(0 0,100% 100%, 100% 0,  50% 100%); logo of mahindra  */
        /* clip-path: polygon(0 0, 100% 0, 100% 50%, 0 50%); */
        /* clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 100%); */

        /* clip-path: polygon(0 0, 100% 0, 100% 100%, 0 50%); */
        /* clip-path: polygon(0 50%,50% 0  , 100% 100%, 50% 100%); */
        clip-path: polygon(0% 100%, 14% 19%, 99% 0%, 100% 100%);

        /* Create a diagonal fade effect */
      }

      .featured-img {
        height: auto;
        /* Ensure image scales properly */
      }

      .image-fade {
        width: 100%;
        /* Adjust fade to cover full width of smaller screens */
      }

      .image-container {
        width: 100%;
        /* Adjust as per your desired width */
      }

      /* Add fade effect on the left side of the image */
      /* .image-container img {
        width: 100%;
        height: auto;
        object-fit: cover;
      } */

      .image-container::before {
        content: "";
        width: 100%;
        /* The width of the fade */
        /* height: 100%; */
        /* background: linear-gradient(
          to right,
          rgba(0, 0, 0, 0.7),
          rgba(0, 0, 0, 0)
        ); */
        /* pointer-events: none; Ensures that the fade does not interfere with image interactions */
      }
    }
  </style>
  <title>Blog_</title>
</head>

<body>
  <!-- Section for navbar  -->



  <?php require 'headerbar.php' ?>

  <main class="container">
    <!-- Section for categories -->
    <nav class="nav d-flex justify-content-between">
      <?php if (!empty($categoriesdata)): ?>
        <?php foreach ($categoriesdata as $category): ?>
          <a class="p-2 link-secondary"
            href="category.php?category=<?= $category['name'] ?>"><?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?></a>

        <?php endforeach; ?>

      <?php else: ?>
        <a class="p-2 link-secondary" href="#">World</a>
      <?php endif; ?>

    </nav>
    <!-- Setion for fetured post  -->


    <!-- Section for featured post -->
    <div class="p-4 p-md-5 my-4 text-white rounded bg-dark d-flex _featuredCard">
      <!-- Content on the left -->
      <div class="col-md-6 px-0">
        <h1 class="display-4 fst-italic">
          <?php echo limitWords($top_post['title'],7) ?>
        </h1>
        <p class="lead my-3">
          <?php //echo "{$top_post['body']}"; ?>
          <?php // echo limitWords($top_post['body'], 10); ?>
          ...
        </p>
        <p class="lead mb-0">
          <a href="blog.php?id=<?= $top_post['id'] ?>" class="text-white fw-bold">Continue reading...</a>
        </p>
      </div>

      <!-- Image on the right with fade effect -->
      <div class="image-container">

        <?php if (!empty($top_post['cover_image'])) {
          echo "<img src='{$top_post['cover_image']}' alt='Cover Image' class='card-img-top _cardImgFeat'>";
        } else { ?>
          <img src="Images/tshirtdesign2.jpg" class="card-img-top _cardImgFeat" alt="..." />
        <?php } ?>
      </div>
    </div>

    <!-- Section  divided  -->
    <div class="row g-5">
      <div class="col-md-8">
        <h3 class="pb-4 mb-4 fst-italic border-bottom">From the Blog_</h3>

        <article class="blog-post"></article>

        <div class="row row-cols-1 g-3">
          <div class="col">
            <?php if (!empty($blogsdata)): ?>
              <div class="row">
                <?php foreach ($blogsdata as $blog): ?>
                  <div class="card shadow-sm rounded-3">
                    <?php if (!empty($blog['cover_image'])) {
                      echo "<img src='{$blog['cover_image']}' alt='Cover Image' class='card-img-top _cardImg'>";
                    } else { ?>
                      <img src="Images/tshirtdesign2.jpg" class="card-img-top _cardImg" alt="..." />
                    <?php } ?>

                    <div class="card-body">
                      <h3><?= htmlspecialchars($blog['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                     
                      <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                          <!-- <button type="button" class="btn btn-sm btn-outline-secondary">
                      View
                    </button> -->
                          <a href="blog.php?id=<?= $blog['id'] ?>" class="btn text-light"
                            style="background-color: #6610f2">Read More</a>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>


            <?php else: ?>
              <p class="text-muted">No blogs available at the moment.</p>
            <?php endif; ?>

          </div>

        </div>

        <nav class="blog-pagination my-3" aria-label="Pagination">
          <a class="btn text-light" href="#" style="background-color: #6610f2">Older</a>
          <a class="btn btn-outline-secondary disabled" href="#" tabindex="-1" aria-disabled="true">Newer</a>
        </nav>
      </div>

      <div class="col-md-4">
        <div class="position-sticky" style="top: 2rem">
          <div class="p-4 mb-3 bg-light rounded">
            <h4 class="fst-italic">About</h4>
            <p class="mb-0">
              Customize this section to tell your visitors a little bit about
              your publication, writers, content, or something else entirely.
              Totally up to you.
            </p>
          </div>

          <div class="p-4">
            <h4 class="fst-italic">Archives</h4>
            <ol class="list-unstyled mb-0">
              <li><a href="#">March 2021</a></li>
              <li><a href="#">February 2021</a></li>
              <li><a href="#">January 2021</a></li>
              <li><a href="#">December 2020</a></li>
              <li><a href="#">November 2020</a></li>
              <li><a href="#">October 2020</a></li>
              <li><a href="#">September 2020</a></li>
              <li><a href="#">August 2020</a></li>
              <li><a href="#">July 2020</a></li>
              <li><a href="#">June 2020</a></li>
              <li><a href="#">May 2020</a></li>
              <li><a href="#">April 2020</a></li>
            </ol>
          </div>

          <div class="p-4">
            <h4 class="fst-italic">Elsewhere</h4>
            <ol class="list-unstyled">
              <li><a href="#">GitHub</a></li>
              <li><a href="#">Twitter</a></li>
              <li><a href="#">Facebook</a></li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </main>


  <!-- Footer Section  -->
  <?php require 'footer.php' ?>



  <!-- Bootstrap js file  -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>