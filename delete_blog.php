<?php
require 'db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}


require 'utilities/utilities.php';



// checkRole('user');
checkRole(['admin', 'editor']);


// $id = $_GET['id'] ?? 0;

// echo "this is the ID: $id";

// $stmt = $pdo->prepare("DELETE FROM blogs WHERE id =? ");

// if ($stmt->execute([$id])) {
//     header("Location: blogs.php");
//     exit;
// } else {
//     echo "<div class='alert alert-danger'>Failed to delete blog.</div>";
// }


$blog_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("DELETE FROM blogposts WHERE id = ? AND user_id = ?");
if ($stmt->execute([$blog_id, $user_id])) {
    echo "Blog deleted successfully!";
    header("Location: blogs.php");
    exit;
} else {
    echo "<div class='alert alert-danger'>Failed to delete blog.</div>";

}

?>