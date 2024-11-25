<?php
// function checkRole($requiredRole)
// {
//     if (!isset($_SESSION['role']) || $_SESSION['role'] !== $requiredRole) {
//         header("Location: unauthorized.php");
//         exit;
//     }
// }

function checkRole($allowedRoles) {
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowedRoles)) {
        header("Location: unauthorized.php");
        exit;
    }
}
?>