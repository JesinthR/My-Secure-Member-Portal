<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: index.php");
    exit();
}

require 'conn.php';

$email = $fullName = $city = '';
$error = '';
$success = '';

$userId = $_SESSION['userId'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $fullName = $_POST['fullName'];
    $city = $_POST['city'];

    if (!empty($email) && !empty($fullName) && !empty($city)) {
        try {
            $stmt = $pdo->prepare('UPDATE tbl_users SET email = ?, fullName = ?, city = ? WHERE userId = ?');
            $stmt->execute([$email, $fullName, $city, $userId]);

            $success = 'Profile updated successfully!';
        } catch (PDOException $e) {
            $error = 'Error updating profile: ' . $e->getMessage();
        }
    } else {
        $error = 'Please fill in all fields';
    }
}

try {
    $stmt = $pdo->prepare('SELECT email, fullName, city FROM tbl_users WHERE userId = ?');
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if ($user) {
        $email = $user['email'];
        $fullName = $user['fullName'];
        $city = $user['city'];
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
    <title>Update Profile</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <br><br><br><br>

    <main>
        <center><h2>Update Profile</h2></center>
        <?php if (!empty($error)) : ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if (!empty($success)) : ?>
            <p class="success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            
            <label for="fullName">Full Name:</label>
            <input type="text" id="fullName" name="fullName" value="<?php echo htmlspecialchars($fullName); ?>" required>
            
            <label for="city">City:</label>
            <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($city); ?>" required>
            
            <button type="submit">Update</button>
            <a href="protected-home.php"><button type="button" class="btn">Back</button></a>
        </form>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
