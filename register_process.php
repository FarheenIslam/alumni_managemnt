<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $alumni_name = $_POST['alumni_name'];
    $contact_no = $_POST['contact_no'];
    $address = $_POST['address'];
    $graduation_year = $_POST['graduation_year'];
    $date_of_birth = $_POST['date_of_birth'];
    $job = $_POST['job'];
    $department = $_POST['department'];
    
    // Generate AlumniID
    $alumni_id = 'ALM' . rand(10000, 99999);
    
    // Check if username or email already exists
    $check_user = $conn->prepare("SELECT * FROM Users WHERE Username = ? OR Email = ?");
    $check_user->bind_param("ss", $username, $email);
    $check_user->execute();
    $result = $check_user->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Username or email already exists']);
    } else {
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // Insert into Users table
            $stmt = $conn->prepare("INSERT INTO Users (Username, Password, Email, UserRole) VALUES (?, ?, ?, 'alumni')");
            $stmt->bind_param("sss", $username, $password, $email);
            $stmt->execute();
            $user_id = $stmt->insert_id;
            
            // Insert into Alumni table
            $stmt = $conn->prepare("INSERT INTO Alumni (AlumniID, AlumniName, Email, ContactNo, Address, GraduationYear, DateOfBirth, Job, DepartmentID, UserID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssisssi", $alumni_id, $alumni_name, $email, $contact_no, $address, $graduation_year, $date_of_birth, $job, $department, $user_id);
            $stmt->execute();
            
            // Commit transaction
            $conn->commit();
            
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()]);
        }
    }
    
    $check_user->close();
    $conn->close();
}
?>