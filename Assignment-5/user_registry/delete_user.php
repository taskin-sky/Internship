<?php
session_start();

// Check if a user index is provided
if (isset($_POST['user_index'])) {
    $index = $_POST['user_index'];

    // Remove the user from the session
    if (isset($_SESSION['users'][$index])) {
        unset($_SESSION['users'][$index]);
    }

    $_SESSION['users'] = array_values($_SESSION['users']);

    // Update the cookie with the new session data
    setcookie('user_registry', serialize($_SESSION['users']), time() + (86400 * 30), "/"); // 30 days
}

// Redirect back to the user list page
header('Location: index.php');
exit();
?>
