<?php 

require 'db.php';

session_start();

require 'utilities/utilities.php';

// Updgrading role

$newRole = 'editor';


// checkRole('user');
checkRole(['admin']);

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}


$users_id = $_GET['id'];
// $user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");


if ($stmt->execute([$newRole ,$users_id])) {
    header("Location: manage_users.php");
    exit;
} else {
    echo "<div class='alert alert-danger'>Failed to update user.</div>";
}



?>