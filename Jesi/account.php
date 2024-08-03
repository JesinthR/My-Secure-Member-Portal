<?php
require 'conn.php'; // Ensure this path is correct

session_start();

$oldPassword = $newPassword = '';
$error = '';
$success = '';

$userId = $_SESSION['userId'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];

    if (!empty($oldPassword) && !empty($newPassword)) {
        try {
            $stmt = $pdo->prepare('SELECT password FROM tbl_users WHERE userId = ?');
            $stmt->execute([$userId]);
            $user = $stmt->fetch();

            if ($user) {
                if ($oldPassword === $user['password']) {
                    if (strlen($newPassword) >= 8 && preg_match('/[A-Za-z]/', $newPassword) && preg_match('/\d/', $newPassword)) {
                        $stmt = $pdo->prepare('UPDATE tbl_users SET password = ? WHERE userId = ?');
                        $stmt->execute([$newPassword, $userId]);

                        $success = 'Password updated successfully!';
                    } else {
                        $error = 'New password must be at least 8 characters long and include both letters and numbers';
                    }
                } else {
                    $error = 'Old password is incorrect';
                }
            } else {
                $error = 'User not found';
            }
        } catch (PDOException $e) {
            $error = 'Error updating password: ' . $e->getMessage();
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
    <title>Secure Member Portal</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<?php include 'header.php'; ?>
<br><br><br><br>

    <main>
        <div class="container">
            <h1>Update Password</h1>
            <?php if (!empty($error)) : ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <?php if (!empty($success)) : ?>
                <p class="success"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>
            <form action="account.php" method="post">
                <div class="form-group">
                    <label for="oldPassword">Old Password: </label>
                    <input type="password" name="oldPassword" id="oldPassword" required>
                </div>
                <div class="form-group">
                    <label for="newPassword">New Password: </label>
                    <input type="password" name="newPassword" id="newPassword" required>
                </div>
                <button type="submit">Update Password</button>
                <a href="protected-home.php"><button type="button" class="btn">Back</button></a>

            </form>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
