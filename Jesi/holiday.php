<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

$holidays = [];

try {
    $response = file_get_contents("https://data.gov.sg/api/action/datastore_search?resource_id=6228c3c5-03bd-4747-bb10-85140f87168b&limit=10");
    $data = json_decode($response, true);
    if (isset($data['result']['records'])) {
        $holidays = $data['result']['records'];
    }
} catch (Exception $e) {
    echo "<p>Error fetching holidays: " . $e->getMessage() . "</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Holiday</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Button styles */
        .btn {
            background-color: #333;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #555;
        }
    </style>
</head>
<?php include 'header.php'; ?>
<br><br><br><br>

<main>
    <center><h2>Public Holidays</h2></center>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Holiday Name</th>
                <th>Day</th>
            </tr>
        </thead>
        <tbody>
        <a href="protected-home.php"><button type="button" class="btn">Back</button></a>
            <?php foreach ($holidays as $holiday): ?>
            <tr>
                <td><?php echo htmlspecialchars($holiday['date']); ?></td>
                <td><?php echo htmlspecialchars($holiday['holiday']); ?></td>
                <td><?php echo htmlspecialchars($holiday['day']); ?></td>
            </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
</main>
<br><br><br><br>

<?php include 'footer.php'; ?>
