<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: list_users.php');
    exit();
}

include 'config.php';

// Define variables and initialize with empty values
$new_username = $new_password = $confirm_password = '';
$new_username_err = $new_password_err = $confirm_password_err = '';

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate new username
    if (empty(trim($_POST["new_username"]))) {
        $new_username_err = "Please enter a new username.";
    } else {
        $new_username = trim($_POST["new_username"]);
    }

    // Validate new password
    if (empty(trim($_POST['new_password']))) {
        $new_password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST['new_password'])) < 6) {
        $new_password_err = "Password must have at least 6 characters.";
    } else {
        $new_password = trim($_POST['new_password']);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if (empty($new_username_err) && empty($new_password_err) && empty($confirm_password_err)) {

        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_new_username);

            // Set parameters
            $param_new_username = $new_username;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // store result
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $new_username_err = "This username is already taken.";
                } else {
                    // Prepare an insert statement
                    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

                    if ($stmt = $conn->prepare($sql)) {
                        // Bind variables to the prepared statement as parameters
                        $stmt->bind_param("ss", $param_new_username, $param_new_password);

                        // Set parameters
                        $param_new_username = $new_username;
                        $param_new_password = password_hash($new_password, PASSWORD_DEFAULT); // Creates a password hash

                        // Attempt to execute the prepared statement
                        if ($stmt->execute()) {
                            // Redirect to login page
                            header("location: login.php");
                        } else {
                            echo "Something went wrong. Please try again later.";
                        }

                        // Close statement
                        $stmt->close();
                    }
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New User</title>
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
        <h2>Add New User</h2>
        <p>Please fill this form to create a new user.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($new_username_err)) ? 'has-error' : ''; ?>">
                <label>New Username</label>
                <input type="text" name="new_username" class="form-control" value="<?php echo $new_username; ?>">
                <span class="help-block"><?php echo $new_username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
                <span class="help-block"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
        </form>
    </div>
</body>
</html>
