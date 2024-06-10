<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, username, password FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            header('Location: dashboard.php');
        } else {
            echo "Invalid password";
        }
    } else {
        echo "No user found with that username";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
 <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h2>Login Page</h2>
    <form method="post" action="">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>
    <p>Don't have an account? <a href="signup.php">Signup here</a></p>
</body>
</html>
