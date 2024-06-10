<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Fetch uploaded files
$directory = "uploads/";
$files = array_diff(scandir($directory), array('..', '.'));

// Handle file deletion
if (isset($_GET['delete'])) {
    $fileToDelete = $_GET['delete'];
    $filePath = $directory . $fileToDelete;
    if (file_exists($filePath)) {
        unlink($filePath);
        header('Location: dashboard.php');
    } else {
        echo "File not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
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
        <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
        <p>This is your dashboard.</p>

        <div class="thumbnail-container">
            <?php
            foreach ($files as $file) {
                echo '<div class="thumbnail-item">';
                echo '<img src="uploads/' . $file . '" class="thumbnail" alt="Uploaded Image">';
                echo '<div class="thumbnail-actions">';
                echo '<a href="dashboard.php?delete=' . $file . '" class="delete-button">Delete</a>';
				?>
				&nbsp
				  <?php
                echo '<a href="uploads/' . $file . '" download class="download-button">Download</a>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>
