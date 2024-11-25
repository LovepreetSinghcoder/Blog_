<?php
session_start();


require 'db.php'; // Include your database connection file

// Fetch all blogs from the database
$stmt = $pdo->prepare("SELECT * FROM blogs");
$stmt->execute();
$blogsdata = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Fetch all blogs from the database
$stmt = $pdo->prepare("SELECT * FROM categories");
$stmt->execute();
$categoriesdata = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>
<!DOCTYPE html>
<html>

<head>
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
    <title>Unauthorized</title>
</head>

<body>
    <!-- Section for navbar  -->

    <!-- Header Section -->
    <?php require 'headerbar.php'?>


    <div class="container">

        <h1>Access Denied</h1>
        <p>You do not have permission to access this page.</p>
        <a href="index.php">Return to Home</a>
    </div>

   
  <!-- Footer Section  -->
  <?php require 'footer.php'?>
  


    <!-- Bootstrap js file  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>