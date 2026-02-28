<?php
// Include your database connection file
$conn = mysqli_connect('localhost', 'root', '', 'aes');

header('Content-Type: application/json');

if (isset($_GET['key'])) {
    // Sanitize the input
    $method_key = mysqli_real_escape_string($conn, $_GET['key']);

    // Query to get the instructions column
    // Using 'aes' database as per your previous snippet
    $query = "SELECT instructions FROM aes.payment_settings WHERE method_key = '$method_key' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Return the data as JSON
        echo json_encode([
            'success' => true,
            'instructions' => $row['instructions'] ?? ''
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Payment method not found.',
            'instructions' => ''
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No method key provided.'
    ]);
}
