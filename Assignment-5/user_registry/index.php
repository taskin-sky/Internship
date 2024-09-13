<?php
session_start();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // Store user data in the session
    if (!isset($_SESSION['users'])) {
        $_SESSION['users'] = [];
    }
    $_SESSION['users'][] = [
        'username' => $username,
        'email' => $email,
        'address' => $address
    ];

    // Store session data in a cookie
    setcookie('user_registry', serialize($_SESSION['users']), time() + (86400 * 30), "/"); // 30 days

    // Refresh the page to avoid form resubmission on reload
    header('Location: index.php');
    exit();
}

// Retrieve users from session or cookie
$users = isset($_SESSION['users']) ? $_SESSION['users'] : [];

if (isset($_COOKIE['user_registry'])) {
    $users = unserialize($_COOKIE['user_registry']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>User Registration</title>
    
</head>
<body>
    <div class="container">
        <h2>User Registration</h2>
        <form action="index.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="address">Address:</label>
            <textarea id="address" name="address" rows="4" required></textarea>
            
            <input type="submit" value="Register">
        </form>

        <!-- Display Registered Users -->
        <h2>Registered Users</h2>
        <?php if (!empty($users)): ?>
            <table>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($users as $index => $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['address']) ?></td>
                        <td>
                            <form action="delete_user.php" method="POST">
                                <input type="hidden" name="user_index" value="<?= $index ?>">
                                <input type="submit" class="delete-btn" value="Delete">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No users registered yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
