<?php
/**
 * FRONTEND + BACKEND hybrid
 * FRONTEND: HTML form
 * BACKEND : includes db_connect for later use
 */
session_start();
include('../backend/config/db_connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Vehicle</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header>
    <div class="container">
        <h1><span>Parking</span> Management System</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="register.php" class="active">Register Vehicle</a></li>
                <li><a href="book.php">Book Slot</a></li>
                <li><a href="view_bookings.php">View Bookings</a></li>
            </ul>
        </nav>
    </div>
</header>

<main class="container">
    <div class="card" style="max-width:600px;margin:30px auto;">
        <h2>Register Vehicle</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['msg_type'] ?>">
                <?= $_SESSION['message'] ?>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['msg_type']); ?>
        <?php endif; ?>

        <!-- FRONTEND: form sends data to BACKEND -->
        <form action="../backend/logic/register_process.php" method="POST">
            <div class="form-group">
                <label>License Plate Number</label>
                <input type="text" name="license_plate" placeholder="e.g., KA01AB1234" required>
            </div>

            <div class="form-group">
                <label>Vehicle Type</label>
                <select name="vehicle_type" required>
                    <option value="">-- Select --</option>
                    <option value="Car">Car</option>
                    <option value="Motorcycle">Motorcycle</option>
                    <option value="Truck">Truck</option>
                    <option value="Van">Van</option>
                </select>
            </div>

            <div class="form-group">
                <label>Owner Name</label>
                <input type="text" name="owner_name" required>
            </div>

            <div class="form-group">
                <label>Contact Number</label>
                <input type="tel" name="contact_number" pattern="[0-9]{10}" required>
            </div>

            <button type="submit">Register Vehicle</button>
        </form>
    </div>
</main>

<footer>&copy; <?= date('Y') ?> Parking Management System</footer>
</body>
</html>