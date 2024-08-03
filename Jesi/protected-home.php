<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: index.php");
    exit();
}

require 'conn.php';

$userId = $_SESSION['userId']; // Ensure userId is correctly fetched from session
$fullName = '';
$error = '';

try {
    $stmt = $pdo->prepare('SELECT fullName FROM tbl_users WHERE userId = ?');
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if ($user) {
        $fullName = $user['fullName'];
    } else {
        $error = 'User not found';
    }
} catch (PDOException $e) {
    $error = 'Error fetching profile: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Member Portal</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        ul {
            list-style-type: none; 
            padding: 0;
            margin: 0;
        }

        ul li {
            margin: 10px 0; 
            border-radius: 5px; 
        }

        ul li a {
            text-decoration: none; 
            color: #333; 
            font-size: 1.1em; 
            padding: 15px 20px; 
            display: block; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            transition: all 0.3s ease; 
        }

        ul li a:hover {
            background-color: #555; 
            color: #fff; 
            border-color: #555; 
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <br><br><br><br>

    <main>
        <h2>Protected Home</h2>
        <?php if (!empty($error)) : ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php else: ?>
            <p>Welcome, <?php echo htmlspecialchars($fullName); ?>!</p>
        <?php endif; ?>
        <ul>
            <li><a href="profile.php">Update Profile</a></li>
            <li><a href="account.php">Change Password</a></li>
            <li><a href="holiday.php">View Public Holidays</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
