
<?php
require_once "config.php";

$msg = "";
$action = $_GET['action'] ?? 'booking';

if (isset($_GET['logout'])) {
    unset($_SESSION['user_id']);
    header("Location: user.php?action=login");
    exit;
}

// Registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
    isset($_POST['register'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)
        || strlen($password) < 8) {
        $msg = "Enter valid details. Password must have 8 characters.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO users (name,email,password) VALUES (?,?,?)"
        );
        $stmt->bind_param("sss", $name, $email, $hash);

        $msg = $stmt->execute()
            ? "Registration successful. Please login."
            : "Email already registered.";
    }
}

// Login
if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
    isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        header("Location: user.php?action=booking");
        exit;
    } else {
        $msg = "Invalid email or password.";
    }
}

// Book pandit
if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
    isset($_POST['book'])) {

    if (!isset($_SESSION['user_id'])) {
        header("Location: user.php?action=login");
        exit;
    }

    $pandit = (int)$_POST['pandit_id'];
    $puja = trim($_POST['puja_type']);
    $date = $_POST['booking_date'];
    $address = trim($_POST['address']);

    if ($puja === '' || $address === '' ||
        $date < date('Y-m-d')) {
        $msg = "Please enter valid booking details.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO bookings
            (user_id,pandit_id,puja_type,booking_date,address)
            VALUES (?,?,?,?,?)"
        );

        $stmt->bind_param(
            "iisss",
            $_SESSION['user_id'],
            $pandit,
            $puja,
            $date,
            $address
        );

        if ($stmt->execute()) {
            $msg = "Your pandit booking request has been submitted!";
        } else {
            $msg = "Booking failed. Please try again.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bhakti Seva - User Services</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="logo">🪔 Bhakti Seva</div>
    <nav>
        <a href="index.php">Home</a>
        <a href="index.php?page=temples">Temples</a>
        <a href="user.php?action=booking">Book Pandit</a>
        <a href="user.php?action=mybookings">My Bookings</a>

        <?php if(isset($_SESSION['user_id'])) { ?>
            <a href="user.php?logout=1">Logout</a>
        <?php } else { ?>
            <a href="user.php?action=login">Login</a>
        <?php } ?>
    </nav>
</header>

<?php if($msg != "") { ?>
    <div class="message"><?php echo htmlspecialchars($msg); ?></div>
<?php } ?>

<?php if($action == 'register') { ?>

<div class="form-box">
<h2>Create Account</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email Address" required>
    <input type="password" name="password"
           placeholder="Password (minimum 8 characters)"
           minlength="8" required>
    <button type="submit" name="register">Register</button>
</form>
<p>Already registered? <a href="user.php?action=login">Login</a></p>
</div>

<?php } elseif($action == 'login') { ?>

<div class="form-box">
<h2>User Login</h2>
<form method="POST">
    <input type="email" name="email" placeholder="Email Address" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
</form>
<p>New user? <a href="user.php?action=register">Register Here</a></p>
</div>

<?php } elseif($action == 'mybookings') { ?>

<section class="container">
<h2>My Puja Bookings</h2>

<?php if(isset($_SESSION['user_id'])) {
    $id = (int)$_SESSION['user_id'];

    $stmt = $conn->prepare(
        "SELECT b.*,p.name AS pandit_name
         FROM bookings b
         JOIN pandits p ON b.pandit_id=p.id
         WHERE b.user_id=? ORDER BY b.id DESC"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
?>

<table>
<tr>
    <th>Puja</th>
    <th>Pandit</th>
    <th>Date</th>
    <th>Status</th>
</tr>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?php echo htmlspecialchars($row['puja_type']); ?></td>
    <td><?php echo htmlspecialchars($row['pandit_name']); ?></td>
    <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
    <td><?php echo htmlspecialchars($row['status']); ?></td>
</tr>
<?php } ?>
</table>

<?php } else { ?>
<p>Please <a href="user.php?action=login">login</a> to see your bookings.</p>
<?php } ?>
</section>

<?php } else { ?>

<?php if(!isset($_SESSION['user_id'])) { ?>

<div class="form-box">
<h2>Book a Pandit</h2>
<p>Please login or register before booking a pandit.</p>
<a href="user.php?action=login" class="btn">Login</a>
<a href="user.php?action=register" class="btn">Register</a>
</div>

<?php } else { ?>

<div class="form-box">
<h2>Book a Pandit</h2>
<p>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>

<form method="POST">
    <label>Select Pandit</label>
    <select name="pandit_id" required>
        <option value="">Choose Pandit</option>
        <?php
        $result = $conn->query("SELECT * FROM pandits");
        while($p = $result->fetch_assoc()) {
        ?>
        <option value="<?php echo (int)$p['id']; ?>">
            <?php echo htmlspecialchars($p['name']); ?>
            - <?php echo htmlspecialchars($p['specialization']); ?>
        </option>
        <?php } ?>
    </select>

    <label>Puja Type</label>
    <input type="text" name="puja_type"
           placeholder="Example: Griha Pravesh Puja" required>

    <label>Booking Date</label>
    <input type="date" name="booking_date"
           min="<?php echo date('Y-m-d'); ?>" required>

    <label>Address</label>
    <textarea name="address" placeholder="Enter your address" required></textarea>

    <button type="submit" name="book">Submit Booking</button>
</form>
</div>

<?php } ?>
<?php } ?>

<footer>
    <h3>Bhakti Seva</h3>
    <p>Faith • Devotion • Spirituality</p>
</footer>
</body>
</html>
