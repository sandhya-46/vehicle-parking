<?php
/**
 * FRONTEND + BACKEND (hybrid)
 * FRONTEND: header, nav, cards
 * BACKEND : counts from DB
 */
session_start();
include('../backend/config/db_connect.php');

// BACKEND: Fetch statistics
$available_slots = $conn->query("SELECT COUNT(*) AS c FROM parking_slots WHERE is_available = TRUE")->fetch_assoc()['c'];
$occupied_slots  = $conn->query("SELECT COUNT(*) AS c FROM parking_slots WHERE is_available = FALSE")->fetch_assoc()['c'];
$total_vehicles  = $conn->query("SELECT COUNT(*) AS c FROM vehicles")->fetch_assoc()['c'];
$total_bookings  = $conn->query("SELECT COUNT(*) AS c FROM bookings")->fetch_assoc()['c'];

// BACKEND: All slots for display
$slots = $conn->query("SELECT * FROM parking_slots ORDER BY slot_number");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Parking Management System</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<!-- ================= FRONTEND ================= -->
<header>
    <div class="container">
        <h1><span>Parking</span> Management System</h1>
        <nav>
            <ul>
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="register.php">Register Vehicle</a></li>
                <li><a href="book.php">Book Slot</a></li>
                <li><a href="view_bookings.php">View Bookings</a></li>
            </ul>
        </nav>
    </div>
</header>

<main class="container">

    <div class="card">
        <h2>Dashboard Overview</h2>
        <div class="dashboard">
            <div class="stat-card">
                <h3>Available Slots</h3>
                <div class="value"><?= $available_slots ?></div>
            </div>
            <div class="stat-card">
                <h3>Occupied Slots</h3>
                <div class="value"><?= $occupied_slots ?></div>
            </div>
            <div class="stat-card">
                <h3>Registered Vehicles</h3>
                <div class="value"><?= $total_vehicles ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Bookings</h3>
                <div class="value"><?= $total_bookings ?></div>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Parking Slot Status</h2>
        <p>Green = available &nbsp;|&nbsp; Red = occupied</p>
        <div class="slot-grid">
            <?php while ($s = $slots->fetch_assoc()): ?>
                <div class="slot <?= $s['is_available'] ? 'available' : 'occupied' ?>">
                    <?= htmlspecialchars($s['slot_number']) ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</main>

<footer>&copy; <?= date('Y') ?> Parking Management System</footer>
</body>
</html>