<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qty_bn']) && isset($_POST['pmethod'])) {
    $qty_bn = intval($_POST['qty_bn']); // Ensure the value is an integer
    $pmethod = htmlspecialchars($_POST['pmethod']); // Sanitize the payment method

    // Update the session variables
    if (isset($_SESSION['product_details'])) {
        $_SESSION['product_details']['qty'] = $qty_bn;
        $_SESSION['product_details']['pmethod'] = $pmethod; // Add payment method to the session
        echo "Session updated: qty = $qty_bn, pmethod = $pmethod";
    } else {
        echo "Error: Product details not found in session.";
    }
} else {
    echo "Invalid request.";
}
