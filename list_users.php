<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

include 'config.php';

// Fetch users from database
$sql = "SELECT id, username FROM users";
$result = $conn->query($sql);
$users = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List of Users</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="sidebar">
        <a href="dashboard.php">User</a>
        <a href="upload.php">Upload</a>
        <a href="add_user.php">Add New User</a>
        <a href="edit_user.php">Edit User</a>
        <a href="list_users.php">List of Users</a>
        <a href="dashboard.php?action=logout">Logout</a>
    </div>
    <div class="content">
        <h2>List of Users</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
            </tr>
            <?php foreach ($users as $user) : ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['username']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
