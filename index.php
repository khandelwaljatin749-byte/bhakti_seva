
<?php
require_once "config.php";

$page = $_GET['page'] ?? 'home';

$allowed = ['home', 'temples', 'scriptures', 'mantras', 'aartis', 'about', 'contact'];

if (!in_array($page, $allowed, true)) {
    $page = 'home';
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bhakti Seva | Spiritual Journey</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="logo">🪔 Bhakti Seva</div>

    <nav>
        <a href="index.php">Home</a>
        <a href="index.php?page=temples">Temples</a>
        <a href="index.php?page=scriptures">Scriptures</a>
        <a href="index.php?page=mantras">Mantras</a>
        <a href="index.php?page=aartis">Aarti</a>
        <a href="user.php">Book Pandit</a>
        <a href="user.php?action=login">Login</a>
    </nav>
</header>

<?php if ($page == 'home') { ?>


<section class="hero">

    <h1>Welcome to Bhakti Seva</h1>

    <h2>Your Spiritual Journey Begins Here</h2>

    <p>
        Discover the divine beauty of Hindu temples,
        explore sacred scriptures, experience devotional
        mantras and connect with spiritual traditions.
    </p>

    <div>
        <a href="index.php?page=temples" class="btn">
            Explore Temples
        </a>

        <a href="user.php" class="btn">
            Book a Pandit
        </a>
    </div>

</section>


<section class="container">
    <h2>Explore Bhakti Seva</h2>

    <div class="cards">
        <div class="card">
            <h3>🛕 Famous Temples</h3>
            <p>Discover famous Hindu temples and their history.</p>
            <a href="index.php?page=temples">Explore</a>
        </div>

        <div class="card">
            <h3>📖 Hindu Scriptures</h3>
            <p>Learn about Bhagavad Gita, Ramayana and Vedas.</p>
            <a href="index.php?page=scriptures">Read More</a>
        </div>

        <div class="card">
            <h3>🙏 Mantras</h3>
            <p>Read sacred mantras and their meanings.</p>
            <a href="index.php?page=mantras">Explore</a>
        </div>

        <div class="card">
            <h3>🪔 Aarti</h3>
            <p>Access devotional aarti lyrics.</p>
            <a href="index.php?page=aartis">Read Aarti</a>
        </div>

        <div class="card">
            <h3>🧘 Pandit Booking</h3>
            <p>Book a pandit for your religious ceremonies.</p>
            <a href="user.php">Book Now</a>
        </div>
    </div>
</section>

<?php } elseif ($page == 'temples') { ?>

<section class="container">
    <h2>Famous Hindu Temples</h2>
    <div class="cards">
    <?php
    $result = $conn->query("SELECT * FROM temples ORDER BY id DESC");
    while ($row = $result->fetch_assoc()) {
    ?>
        <div class="card">
            <h3><?php echo e($row['name']); ?></h3>
            <p><b>Location:</b> <?php echo e($row['location']); ?></p>
            <p><?php echo e($row['description']); ?></p>
            <p><b>Timings:</b> <?php echo e($row['timings']); ?></p>
        </div>
    <?php } ?>
    </div>
</section>

<?php } elseif ($page == 'scriptures') { ?>

<section class="container">
    <h2>Hindu Scriptures</h2>
    <div class="cards">
    <?php
    $result = $conn->query("SELECT * FROM scriptures");
    while ($row = $result->fetch_assoc()) {
    ?>
        <div class="card">
            <h3><?php echo e($row['title']); ?></h3>
            <p><?php echo e($row['description']); ?></p>
            <p><?php echo e($row['content']); ?></p>
        </div>
    <?php } ?>
    </div>
</section>

<?php } elseif ($page == 'mantras') { ?>

<section class="container">
    <h2>Sacred Mantras</h2>
    <div class="cards">
    <?php
    $result = $conn->query("SELECT * FROM mantras");
    while ($row = $result->fetch_assoc()) {
    ?>
        <div class="card">
            <h3><?php echo e($row['title']); ?></h3>
            <p class="devotional"><?php echo e($row['lyrics']); ?></p>
            <p><b>Meaning:</b> <?php echo e($row['meaning']); ?></p>
        </div>
    <?php } ?>
    </div>
</section>

<?php } elseif ($page == 'aartis') { ?>

<section class="container">
    <h2>Devotional Aarti</h2>
    <div class="cards">
    <?php
    $result = $conn->query("SELECT * FROM aartis");
    while ($row = $result->fetch_assoc()) {
    ?>
        <div class="card">
            <h3><?php echo e($row['title']); ?></h3>
            <p class="devotional"><?php echo e($row['lyrics']); ?></p>
            <p><b>Meaning:</b> <?php echo e($row['meaning']); ?></p>
        </div>
    <?php } ?>
    </div>
</section>

<?php } elseif ($page == 'about') { ?>

<section class="container">
    <h2>About Bhakti Seva</h2>
    <p>Bhakti Seva is a devotional platform designed to provide
    information about Hindu temples, scriptures, mantras,
    aarti and religious services.</p>
</section>

<?php } elseif ($page == 'contact') { ?>

<section class="container">
    <h2>Contact Us</h2>
    <p>Email: support@bhaktiseva.com</p>
    <p>Thank you for visiting Bhakti Seva.</p>
</section>

<?php } ?>

<footer>
    <h3>🪔 Bhakti Seva</h3>
    <p>Faith • Devotion • Spirituality</p>
    <a href="index.php?page=about">About Us</a> |
    <a href="index.php?page=contact">Contact Us</a>
    <p>© 2026 Bhakti Seva. All Rights Reserved.</p>
</footer>

</body>
</html>
