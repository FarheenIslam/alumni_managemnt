<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contact = $_POST['contact'];
    $verification_code = $_POST['verification_code'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    
    // Verify the code
    if (!isset($_SESSION['verification_code']) || $_SESSION['verification_code'] != $verification_code) {
        echo json_encode(['success' => false, 'message' => 'Invalid verification code']);
        exit;
    }
    
    // Update password
    $stmt = $conn->prepare("UPDATE Users SET Password = ? WHERE Email = ? OR Username = ?");
    $stmt->bind_param("sss", $new_password, $contact, $contact);
    
    if ($stmt->execute()) {
        // Clear the verification code
        unset($_SESSION['verification_code']);
        unset($_SESSION['contact']);
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to reset password']);
    }
    
    $stmt->close();
    $conn->close();
}
?>