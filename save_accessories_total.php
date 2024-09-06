<?php
session_start();

// Check if the total amount is set in the POST request
if (isset($_POST['total'])) {
    $accessoriesTotal = floatval($_POST['total']);

    // Save the accessories total in the session
    $_SESSION['accessoriesamount'] = $accessoriesTotal;

    // Optionally send a response back
    echo "Accessories amount saved successfully!";
} else {
    echo "No amount data received.";
}
?>
