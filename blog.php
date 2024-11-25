<?php
session_start();

require 'db.php';

//  $blog_id = $_GET['id'];
// $blog_id = $_GET['id'];
// echo "This is the id of the blog post: $blog_id";

// $stmt = $pdo->prepare("SELECT * FROM blogposts WHERE id = ?");


// $stmt->execute([$blog_id]);

// $blogdata = $stmt->fetch(PDO::FETCH_ASSOC);


// Validate the blog ID from the URL
$blog_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$blog_id) {
  echo "<div class='alert alert-danger'>Invalid blog ID!</div>";
  exit;
}

$stmt = $pdo->prepare("
    SELECT blogposts.*, users.name AS author_name, users.email AS author_email
    FROM blogposts
    INNER JOIN users ON blogposts.user_id = users.id
    WHERE blogposts.id = ?
");
$stmt->execute([$blog_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the blog exists
if (!$data) {
  echo "<div class='alert alert-danger'>Blog post not found!</div>";
  exit;
}

// Increment the view count
$updateStmt = $pdo->prepare("UPDATE blogposts SET views = views + 1 WHERE id = ?");
$updateStmt->execute([$blog_id]);


// if (!$blogdata) {
//   echo "<div class='alert alert-danger'>Blog post not found!</div>";
//   exit;
// } 
// else {
//   $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
//   $stmt->execute([$blogdata['user_id']]);
//   $authordata = $stmt->fetch(PDO::FETCH_ASSOC);
// }
// ;

// foreach()
// echo "THis is the blod data: {$blogdata['views']}";


// Fetch all blogs from the database
$stmt = $pdo->prepare("SELECT * FROM categories");
$stmt->execute();
$categoriesdata = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <title><?= $data['title'] ?></title>
</head>

<body>
  <!-- Section for navbar  -->

  <?php require 'headerbar.php' ?>

  <main class="container">
    <!-- Section for categories -->
    <!-- <nav class="nav d-flex justify-content-between">
      <a class="p-2 link-secondary" href="#">World</a>
      <a class="p-2 link-secondary" href="#">U.S.</a>
      <a class="p-2 link-secondary" href="#">Technology</a>
      <a class="p-2 link-secondary" href="#">Design</a>
      <a class="p-2 link-secondary" href="#">Culture</a>
      <a class="p-2 link-secondary" href="#">Business</a>
      <a class="p-2 link-secondary" href="#">Politics</a>
      <a class="p-2 link-secondary" href="#">Opinion</a>
      <a class="p-2 link-secondary" href="#">Science</a>
      <a class="p-2 link-secondary" href="#">Health</a>
      <a class="p-2 link-secondary" href="#">Style</a>
      <a class="p-2 link-secondary" href="#">Travel</a>
    </nav> -->
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


    <!-- Section  divided  -->
    <div class="row g-5">
      <div class="col-md-8 m-8">
        <h3 class="pb-4 my-4 fst-italic border-bottom">From the Blog_</h3>

        <article class="blog-post">
          <h2 class="blog-post-title"><?= $data['title'] ?></h2>

          <p class="blog-post-meta">
            <?= $data['created_at'] ?> by <?= htmlspecialchars($data['author_name']) ?> </a>
          </p>
          <?php if (!empty($data['cover_image'])) {
            echo "<img src='{$data['cover_image']}' alt='Cover Image' class='card-img-top _cardImgFeat'>";
          } else { ?>
            <img src="Images/tshirtdesign2.jpg" class="card-img-top _cardImgFeat" alt="..." />
          <?php } ?>

          <div class="my-4">

            <?php echo "{$data['body']}"; ?>
          </div>

        </article>





        <!-- <nav class="blog-pagination" aria-label="Pagination">
          <a class="btn btn-outline-ligh text-light" style="background-color: #6610f2" href="blog.php?id=<?= $blog['id'] - 1 ?>" >Older</a>
          <a class="btn btn-outline-secondary disabled" href="blog.php?id=<?= $blog['id'] + 1 ?>"  tabindex="-1" aria-disabled="true">Next</a>
        </nav> -->

        <nav class="blog-pagination" aria-label="Pagination">
          <?php if ($data['id'] > 1): ?>
            <a class="btn btn-outline-light text-light" style="background-color: #6610f2"
              href="blog.php?id=<?= $data['id'] - 1 ?>">Older</a>
          <?php else: ?>
            <a class="btn btn-outline-secondary disabled" tabindex="-1" aria-disabled="true">Older</a>
          <?php endif; ?>

          <?php
          // Fetch the next post to check if it exists
          $nextStmt = $pdo->prepare("SELECT id FROM blogposts WHERE id = ?");
          $nextStmt->execute([$data['id'] + 1]);
          $nextPostExists = $nextStmt->fetch();
          ?>

          <?php if ($nextPostExists): ?>
            <a class="btn btn-outline-light text-light" style="background-color: #6610f2"
              href="blog.php?id=<?= $data['id'] + 1 ?>">Next</a>
          <?php else: ?>
            <a class="btn btn-outline-secondary disabled" tabindex="-1" aria-disabled="true">Next</a>
          <?php endif; ?>
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

    <div class="my-5"></div>
  </main>


  <!-- Footer Section  -->
  <?php require 'footer.php' ?>


  <!-- Bootstrap js file  -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>