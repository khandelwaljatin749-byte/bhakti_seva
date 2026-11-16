
<?php
require_once "config.php";

// Create a default admin if the admin table is empty.
// Demo credentials: admin / Admin@123

$count = $conn->query("SELECT COUNT(*) AS total FROM admins");
$total = $count->fetch_assoc()['total'];

if ($total == 0) {
    $username = "admin";
    $password = password_hash("Admin@123", PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "INSERT INTO admins (username,password) VALUES (?,?)"
    );
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
}

$msg = "";

// Admin login
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $admin = $stmt->get_result()->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $admin['id'];
        header("Location: admin.php");
        exit;
    } else {
        $msg = "Invalid admin username or password.";
    }
}

// Logout
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_id']);
    header("Location: admin.php");
    exit;
}

// Update booking status
if (isset($_POST['update_status']) && isset($_SESSION['admin_id'])) {
    $booking_id = (int)$_POST['booking_id'];
    $status = $_POST['status'];

    if (in_array($status, ['Approved', 'Rejected'], true)) {
        $stmt = $conn->prepare(
            "UPDATE bookings SET status=? WHERE id=?"
        );
        $stmt->bind_param("si", $status, $booking_id);
        $stmt->execute();

        $msg = "Booking status updated.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bhakti Seva Admin</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="logo">🪔 Bhakti Seva Admin</div>
    <nav>
        <a href="index.php">Website Home</a>
        <?php if(isset($_SESSION['admin_id'])) { ?>
            <a href="admin.php?logout=1">Logout</a>
        <?php } ?>
    </nav>
</header>

<?php if($msg != "") { ?>
<div class="message"><?php echo htmlspecialchars($msg); ?></div>
<?php } ?>

<?php if(!isset($_SESSION['admin_id'])) { ?>

<div class="form-box">
    <h2>Admin Login</h2>

    <form method="POST">
        <input type="text" name="username"
               placeholder="Admin Username" required>

        <input type="password" name="password"
               placeholder="Admin Password" required>

        <button type="submit" name="login">Login</button>
    </form>
    <p>Demo username: admin</p>
    <p>Demo password: Admin@123</p>
</div>

<?php } else { ?>

<section class="container">

<h2>Admin Dashboard</h2>

<div class="cards">

    <div class="card">
        <h3>Total Users</h3>
        <h2><?php
        echo $conn->query("SELECT COUNT(*) AS total FROM users")
                  ->fetch_assoc()['total'];
        ?></h2>
    </div>

    <div class="card">
        <h3>Total Temples</h3>
        <h2><?php
        echo $conn->query("SELECT COUNT(*) AS total FROM temples")
                  ->fetch_assoc()['total'];
        ?></h2>
    </div>

    <div class="card">
        <h3>Total Pandits</h3>
        <h2><?php
        echo $conn->query("SELECT COUNT(*) AS total FROM pandits")
                  ->fetch_assoc()['total'];
        ?></h2>
    </div>

    <div class="card">
        <h3>Total Bookings</h3>
        <h2><?php
        echo $conn->query("SELECT COUNT(*) AS total FROM bookings")
                  ->fetch_assoc()['total'];
        ?></h2>
    </div>

</div>

<h2>Manage Pandit Bookings</h2>

<table>
<tr>
    <th>ID</th>
    <th>User</th>
    <th>Pandit</th>
    <th>Puja</th>
    <th>Date</th>
    <th>Address</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php
$result = $conn->query(
    "SELECT b.*, u.name AS user_name, p.name AS pandit_name
     FROM bookings b
     JOIN users u ON b.user_id=u.id
     JOIN pandits p ON b.pandit_id=p.id
     ORDER BY b.id DESC"
);

while($row = $result->fetch_assoc()) {
?>

<tr>
    <td><?php echo (int)$row['id']; ?></td>
    <td><?php echo htmlspecialchars($row['user_name']); ?></td>
    <td><?php echo htmlspecialchars($row['pandit_name']); ?></td>
    <td><?php echo htmlspecialchars($row['puja_type']); ?></td>
    <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
    <td><?php echo htmlspecialchars($row['address']); ?></td>
    <td><?php echo htmlspecialchars($row['status']); ?></td>

    <td>
        <form method="POST">
            <input type="hidden" name="booking_id"
                   value="<?php echo (int)$row['id']; ?>">

            <select name="status">
                <option value="Approved">Approve</option>
                <option value="Rejected">Reject</option>
            </select>

            <button type="submit" name="update_status">
                Update
            </button>
        </form>
    </td>
</tr>

<?php } ?>
</table>

</section>

<?php } ?>

<footer>
    <p>Bhakti Seva Admin Panel</p>
</footer>

</body>
</html>
