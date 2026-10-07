<?php
/**
 * BACKEND FILE — Handles vehicle registration
 * Receives POST data from frontend/register.php
 * Returns result via session message
 */

session_start();
include('../config/db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $license_plate  = mysqli_real_escape_string($conn, $_POST['license_plate']);
    $vehicle_type   = mysqli_real_escape_string($conn, $_POST['vehicle_type']);
    $owner_name     = mysqli_real_escape_string($conn, $_POST['owner_name']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);

    // Check for duplicate license plate
    $check = $conn->query("SELECT * FROM vehicles WHERE license_plate = '$license_plate'");
    
    if ($check->num_rows > 0) {
        $_SESSION['message'] = "Vehicle with this license plate already registered!";
        $_SESSION['msg_type'] = "error";
    } else {
        $sql = "INSERT INTO vehicles (license_plate, vehicle_type, owner_name, contact_number) 
                VALUES ('$license_plate', '$vehicle_type', '$owner_name', '$contact_number')";
        
        if ($conn->query($sql)) {
            $_SESSION['message'] = "Vehicle registered successfully!";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "Error: " . $conn->error;
            $_SESSION['msg_type'] = "error";
        }
    }
    
    header("Location: ../../frontend/register.php");
    exit();
}
?>