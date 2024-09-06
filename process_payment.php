<?php
// Include database configuration
include 'includes/db.php';

// Start the session
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect POST data
    $paymentMethod = $_POST['paymentmethod'] ?? '';
    $paytmOption = $_POST['paytm_option'] ?? '';
    $upiId = $_POST['upi_id'] ?? '';
    $cardNumber = $_POST['cardnumber'] ?? '';
    $cardName = $_POST['cardname'] ?? '';
    
    $totalAmount = $_POST['pay'] ?? '';

    // Remove non-numeric characters from the total amount
    $totalAmount = preg_replace('/[^\d.]/', '', $totalAmount);

    // Validate total amount
    if (empty($totalAmount) || !is_numeric($totalAmount)) {
        echo 'Total amount is invalid.';
        exit;
    }

    // Validate other required fields
    if (empty($paymentMethod)) {
        echo 'Payment method is required.';
        exit;
    }

    // Fetch user details from the database
    $userEmail = $_SESSION['login']; // Assuming email is stored in session

    try {
        // Fetch user details
        $stmt = $conn->prepare('SELECT FullName, ID, EmailId, ContactNo FROM tblusers WHERE EmailId = :email');
        $stmt->bindParam(':email', $userEmail);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo 'User not found.';
            exit;
        }

        $fullName = $user['FullName'];
        $userId = $user['ID']; // Make sure 'ID' is the correct column name
        $userEmail = $user['EmailId'];
        $contactNo = $user['ContactNo'];

        // Insert payment data into the database
        $stmt = $conn->prepare('INSERT INTO payments (FullName, user_id, EmailId, ContactNo, amount, payment_method, paytm_option, upi_id, card_number, card_name, created_at) VALUES (:FullName, :user_id, :EmailId, :ContactNo, :amount, :payment_method, :paytm_option, :upi_id, :card_number, :card_name, NOW())');
        $stmt->bindParam(':FullName', $fullName);
        $stmt->bindParam(':user_id', $userId); // Ensure this is included in the INSERT statement
        $stmt->bindParam(':EmailId', $userEmail);
        $stmt->bindParam(':ContactNo', $contactNo);
        $stmt->bindParam(':amount', $totalAmount);
        $stmt->bindParam(':payment_method', $paymentMethod);
        $stmt->bindParam(':paytm_option', $paytmOption);
        $stmt->bindParam(':upi_id', $upiId);
        $stmt->bindParam(':card_number', $cardNumber);
        $stmt->bindParam(':card_name', $cardName);
        $stmt->execute();

        // Redirect to payment success page
        header('Location: payment_success.php');
        exit;
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
