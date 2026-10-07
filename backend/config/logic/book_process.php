<?php
/**
 * BACKEND FILE — Handles slot booking + cost calculation
 */

session_start();
include('../config/db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $license_plate = mysqli_real_escape_string($conn, $_POST['license_plate']);
    $slot_id       = intval($_POST['slot_id']);
    $entry_time    = $_POST['entry_time'];
    $exit_time     = $_POST['exit_time'];
    $booking_date  = date('Y-m-d');

    // 1. Find vehicle by license plate
    $res = $conn->query("SELECT vehicle_id FROM vehicles WHERE license_plate = '$license_plate'");

    if ($res->num_rows == 0) {
        $_SESSION['message'] = "Vehicle not found. Please register first.";
        $_SESSION['msg_type'] = "error";
        header("Location: ../../frontend/book.php");
        exit();
    }

    $vehicle_id = $res->fetch_assoc()['vehicle_id'];

    // 2. Calculate parking duration in hours
    $entry = new DateTime($entry_time);
    $exit  = new DateTime($exit_time);

    if ($exit <= $entry) {
        $_SESSION['message'] = "Exit time must be after entry time.";
        $_SESSION['msg_type'] = "error";
        header("Location: ../../frontend/book.php");
        exit();
    }

    $diff  = $entry->diff($exit);
    $hours = ($diff->days * 24) + $diff->h + ($diff->i > 0 ? 1 : 0); // round up

    // 3. Get rate per hour
    $rate_res = $conn->query("SELECT rate_per_hour FROM parking_slots WHERE slot_id = $slot_id AND is_available = TRUE");

    if ($rate_res->num_rows == 0) {
        $_SESSION['message'] = "Slot not available.";
        $_SESSION['msg_type'] = "error";
        header("Location: ../../frontend/book.php");
        exit();
    }

    $rate = $rate_res->fetch_assoc()['rate_per_hour'];

    // 4. Calculate cost
    $total_cost = $hours * $rate;

    // 5. Insert booking
    $sql = "INSERT INTO bookings (vehicle_id, slot_id, booking_date, entry_time, exit_time, total_cost) 
            VALUES ($vehicle_id, $slot_id, '$booking_date', '$entry_time', '$exit_time', $total_cost)";

    if ($conn->query($sql)) {
        // 6. Mark slot as unavailable
        $conn->query("UPDATE parking_slots SET is_available = FALSE WHERE slot_id = $slot_id");
        
        $_SESSION['message'] = "Booking successful! Total cost: $" . number_format($total_cost, 2);
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['message'] = "Booking failed: " . $conn->error;
        $_SESSION['msg_type'] = "error";
    }

    header("Location: ../../frontend/view_bookings.php");
    exit();
}
?>