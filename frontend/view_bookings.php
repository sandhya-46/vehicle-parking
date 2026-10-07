<?php
/**
 * FRONTEND + BACKEND hybrid
 * FRONTEND: HTML table
 * BACKEND : SQL JOIN to fetch full booking details
 */
session_start();
include('../backend/config/db_connect.php');

// BACKEND: Join bookings + vehicles + slots
$sql = "SELECT b.booking_id, v.license_plate, v.owner_name, v.vehicle_type,
               p.slot_number, p.slot_type,
               b.booking_date, b.entry_time, b.exit_time,
               b.total_cost, b.status
        FROM bookings b
        JOIN vehicles v ON b.vehicle_id = v.vehicle_id
        JOIN parking_slots p ON b.slot_id = p.slot_id
        ORDER BY b.booking_date DESC, b.entry_time DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Bookings</title>
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
                <li><a href="book.php">Book Slot</a></li>
                <li><a href="view_bookings.php" class="active">View Bookings</a></li>
            </ul>
        </nav>
    </div>
</header>

<main class="container">
    <div class="card">
        <h2>All Parking Bookings</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['msg_type'] ?>">
                <?= $_SESSION['message'] ?>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['msg_type']); ?>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>License Plate</th>
                        <th>Owner</th>
                        <th>Slot</th>
                        <th>Booking Date</th>
                        <th>Entry Time</th>
                        <th>Exit Time</th>
                        <th>Cost</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['booking_id'] ?></td>
                            <td><?= htmlspecialchars($row['license_plate']) ?></td>
                            <td><?= htmlspecialchars($row['owner_name']) ?></td>
                            <td><?= htmlspecialchars($row['slot_number']) ?> (<?= $row['slot_type'] ?>)</td>
                            <td><?= date('d M Y', strtotime($row['booking_date'])) ?></td>
                            <td><?= date('d M Y, h:i A', strtotime($row['entry_time'])) ?></td>
                            <td><?= date('d M Y, h:i A', strtotime($row['exit_time'])) ?></td>
                            <td>$<?= number_format($row['total_cost'], 2) ?></td>
                            <td><?= ucfirst($row['status']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No bookings yet. <a href="book.php">Book your first slot</a>.</p>
        <?php endif; ?>
    </div>
</main>

<footer>&copy; <?= date('Y') ?> Parking Management System</footer>
</body>
</html>