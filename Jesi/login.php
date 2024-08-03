<?php
// Include the connection file
require 'conn.php'; // Ensure this path is correct

// Start the session
session_start();

// Initialize variables
$email = $password = '';
$remember_me = false;
$error = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']);

    // Validation
    if (!empty($email) && !empty($password)) {
        try {
            // Prepare and execute the query
            $stmt = $pdo->prepare('SELECT userId, fullName, password FROM tbl_users WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                // Check the password
                if ($password === $user['password']) {
                    $_SESSION['userId'] = $user['userId'];
                    $_SESSION['fullName'] = $user['fullName'];

                    // Set cookies if "Remember Me" is checked
                    if ($remember_me) {
                        setcookie('email', $email, time() + (86400 * 30), "/"); // 30 days
                        setcookie('password', $password, time() + (86400 * 30), "/"); // 30 days
                    }

                    // Redirect to protected home page
                    header('Location: protected-home.php');
                    exit();
                } else {
                    $error = 'Invalid login';
                }
            } else {
                $error = 'No user found';
            }
        } catch (PDOException $e) {
            $error = 'Database query failed: ' . $e->getMessage();
        }
    } else {
        $error = 'Please fill in all fields';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<?php include 'header.php'; ?>
<br><br><br><br>

<main>
    <center><h2>Login</h2></center>
    <form action="" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <label>
            <input type="checkbox" name="remember_me"> Remember me
        </label>
        
        <button type="submit">Login</button>
    </form>
</main>
<?php include 'footer.php'; ?>
