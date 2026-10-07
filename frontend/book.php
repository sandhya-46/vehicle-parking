<?php
/**
 * FRONTEND + BACKEND hybrid
 * FRONTEND: booking form
 * BACKEND : queries available slots to fill dropdown
 */
session_start();
include('../backend/config/db_connect.php');

// BACKEND: Get available slots for dropdown
$available = $conn->query("SELECT * FROM parking_slots WHERE is_available = TRUE ORDER BY slot_number");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Parking Slot</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header>
    <div class="container">
        <h1><span>Parking</span> Management System</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="register.php">Register Vehicle</a></li>
                <li><a href="book.php" class="active">Book Slot</a></li>
                <li><a href="view_bookings.php">View Bookings</a></li>
            </ul>
        </nav>
    </div>
</header>

<main class="container">
    <div class="card" style="max-width:650px;margin:30px auto;">
        <h2>Book a Parking Slot</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['msg_type'] ?>">
                <?= $_SESSION['message'] ?>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['msg_type']); ?>
        <?php endif; ?>

        <form action="../backend/logic/book_process.php" method="POST">
            <div class="form-group">
                <label>License Plate Number</label>
                <input type="text" name="license_plate" placeholder="Enter registered license plate" required>
            </div>

            <div class="form-group">
                <label>Select Parking Slot</label>
                <select name="slot_id" required>
                    <option value="">-- Choose a slot --</option>
                    <?php while ($s = $available->fetch_assoc()): ?>
                        <option value="<?= $s['slot_id'] ?>">
                            <?= htmlspecialchars($s['slot_number']) ?> 
                            (<?= $s['slot_type'] ?>) — $<?= $s['rate_per_hour'] ?>/hr
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Entry Time</label>
                <input type="datetime-local" name="entry_time" required>
            </div>

            <div class="form-group">
                <label>Expected Exit Time</label>
                <input type="datetime-local" name="exit_time" required>
            </div>

            <button type="submit">Book Slot</button>
        </form>
    </div>
</main>

<footer>&copy; <?= date('Y') ?> Parking Management System</footer>
</body>
</html>