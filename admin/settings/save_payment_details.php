<?php
// Include your database connection file
$conn = mysqli_connect('localhost', 'root', '', 'aes');

header('Content-Type: application/json');

// Check if both the key and instructions were sent via POST
if (isset($_POST['key']) && isset($_POST['instructions'])) {

    // Sanitize the input to prevent SQL Injection
    $method_key = mysqli_real_escape_string($conn, $_POST['key']);
    $instructions = mysqli_real_escape_string($conn, $_POST['instructions']);

    // Update the instructions for the specific method_key
    // Note: Using 'aes' database as per your settings loop
    $sql = "UPDATE aes.payment_settings 
            SET instructions = '$instructions' 
            WHERE method_key = '$method_key'";

    if (mysqli_query($conn, $sql)) {
        // If successful, return a success response
        echo json_encode([
            'success' => true,
            'message' => 'Instructions updated successfully!'
        ]);
    } else {
        // If the query fails, return the error
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . mysqli_error($conn)
        ]);
    }
} else {
    // If data is missing from the request
    echo json_encode([
        'success' => false,
        'message' => 'Required fields missing.'
    ]);
}
