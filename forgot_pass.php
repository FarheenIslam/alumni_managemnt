<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contact = $_POST['contact'];
    
    // Check if email or phone exists in database
    $stmt = $conn->prepare("SELECT * FROM Users WHERE Email = ? OR Username = ?");
    $stmt->bind_param("ss", $contact, $contact);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Generate a random 6-digit code
        $verification_code = rand(100000, 999999);
        
        // In a real application, you would send this code via email or SMS
        // For this example, we'll store it in session
        session_start();
        $_SESSION['verification_code'] = $verification_code;
        $_SESSION['contact'] = $contact;
        
        echo json_encode(['success' => true, 'contact' => $contact]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No account found with this email or username']);
    }
    
    $stmt->close();
    $conn->close();
}
?>