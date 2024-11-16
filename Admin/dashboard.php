<?php include('../Data/user.php'); ?>
<?php
// Database connection and user count query
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "calbayog_go";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user data
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
$users = $result->fetch_all(MYSQLI_ASSOC);

// Retrieve login history
$sql = "SELECT lh.*, u.username FROM login_history lh JOIN users u ON lh.user_id = u.id ORDER BY login_time DESC";
$result = $conn->query($sql);
$login_history = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <section>
        <div class="dashboard">
            <div class="metric metric-user">
                <i class="fa-solid fa-user"></i>
                <p>Total of Users: <span><?php echo $total_users; ?></span></p>
            </div>
            <div class="metric metric-analytics">
                <i class="fa-solid fa-chart-simple"></i>
                <p>Analytics <span></span></p>
            </div>
            <div class="metric metric-contents">
                <i class="fa-solid fa-images"></i>
                <p>Contents</p>
            </div>
            <div class="metric metric-announce">
                <i class="fa-solid fa-bullhorn"></i>
                <p>Announcements</p>
            </div>
        </div>

        <div class="login-history">
            <h2>Login History</h2>
            <div class="login-table">
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Login Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($login_history)): ?>
                        <tr>
                            <td colspan="3">No login history available.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($login_history as $login): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($login['username']); ?></td>
                                <td><?php echo htmlspecialchars($login['login_time']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>
    </section>
</body>
</html>