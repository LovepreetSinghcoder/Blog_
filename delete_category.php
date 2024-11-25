<?php
require 'db.php';
session_start();
require 'utilities/utilities.php';




// checkRole('user');
checkRole(['admin']);

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// $id = $_GET['id'] ?? 0; // Fetch category ID from query parameter
// $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
// if ($stmt->execute([$id])) {
//     header("Location: categories.php");
//     exit;
// } else {
//     echo "<div class='alert alert-danger'>Failed to delete category.</div>";
// }





$id = $_GET['id'] ?? 0;

echo "this is the ID: $id";

$stmt = $pdo->prepare("DELETE FROM categories WHERE id =? ");

if($stmt->execute([$id])){
    // header('Location : categories.php');
    header("Location: categories.php");
    exit;
}
else {
    echo "<div class='alert alert-danger'>Failed to delete category.</div>";
}
?>
