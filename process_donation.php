<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit;
}
include 'db.php';

// Process delete request
if (isset($_GET['delete'])) {
    $donation_id = $_GET['delete'];
    
    $sql = "DELETE FROM Donations WHERE DonationID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $donation_id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: donations.php?deleted=true");
    exit();
}

// Process form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add new donation
    if (isset($_POST['add_donation'])) {
        $alumni_id = $_POST['alumni_id'];
        $amount = $_POST['amount'];
        $date = $_POST['date'];
        $purpose = $_POST['purpose'];
        $payment = $_POST['payment_method'];
        
        $sql = "INSERT INTO Donations (AlumniID, DonationAmount, DonationDate, DonationPurpose, PaymentMethod) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsss", $alumni_id, $amount, $date, $purpose, $payment);
        
        if ($stmt->execute()) {
            header("Location: donations.php?added=true");
            exit();
        } else {
            header("Location: donations.php?error=insert");
            exit();
        }
        $stmt->close();
    }
    
    // Update donation
    if (isset($_POST['update_donation'])) {
        $donation_id = $_POST['donation_id'];
        $alumni_id = $_POST['alumni_id'];
        $amount = $_POST['amount'];
        $date = $_POST['date'];
        $purpose = $_POST['purpose'];
        $payment = $_POST['payment_method'];
        
        $sql = "UPDATE Donations SET AlumniID=?, DonationAmount=?, DonationDate=?, DonationPurpose=?, PaymentMethod=? 
                WHERE DonationID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsssi", $alumni_id, $amount, $date, $purpose, $payment, $donation_id);
        
        if ($stmt->execute()) {
            header("Location: donations.php?updated=true");
            exit();
        } else {
            header("Location: donations.php?error=update");
            exit();
        }
        $stmt->close();
    }
}

$conn->close();
?>