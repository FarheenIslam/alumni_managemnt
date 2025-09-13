<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit;
}
include 'db.php';

$user_id = $_SESSION['user_id'];

// Fetch user and alumni data
$sql = "SELECT u.*, a.* FROM Users u JOIN Alumni a ON u.UserID = a.UserID WHERE u.UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Alumni Management System</title>
    <style>
        /* Include the same styles as home.php for consistency */
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }
        body {
            background-color: #f5f5f5;
        }
        .header {
            background-color: #1a2a6c;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
        }
        .user-info {
            display: flex;
            align-items: center;
        }
        .container {
            display: flex;
            min-height: calc(100vh - 70px);
        }
        .sidebar {
            width: 250px;
            background-color: #3e6185ff;
            color: white;
            padding-top: 20px;
        }
        .sidebar-menu {
            list-style: none;
        }
        .sidebar-menu li {
            padding: 12px 20px;
            border-bottom: 1px solid #34495e;
        }
        .sidebar-menu li:hover {
            background-color: #34495e;
            cursor: pointer;
        }
        .sidebar-menu li.active {
            background-color: #162935ff;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .profile-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: #1a2a6c;
            margin-right: 20px;
        }
        .profile-info h2 {
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .profile-info p {
            color: #7f8c8d;
        }
        .profile-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
        }
        .detail-group {
            margin-bottom: 15px;
        }
        .detail-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        .detail-group input, .detail-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            background-color: #1a2a6c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #0d1638;
        }
        .logout-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 15px;
        }
        .logout-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Alumni Management System</div>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
        </div>
    </div>
    
    <div class="container">
        <div class="sidebar">
            <ul class="sidebar-menu">
                <li onclick="window.location.href='home.php'">Dashboard</li>
                <li class="active">My Profile</li>
                <li onclick="window.location.href='alumni_directory.php'">Alumni Directory</li>
                <li onclick="window.location.href='events.php'">Events</li>
                <li onclick="window.location.href='donations.php'">Donations</li>
            </ul>
        </div>
        
        <div class="content">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <?php echo strtoupper(substr($user['AlumniName'], 0, 1)); ?>
                    </div>
                    <div class="profile-info">
                        <h2><?php echo htmlspecialchars($user['AlumniName']); ?></h2>
                        <p>Alumni ID: <?php echo htmlspecialchars($user['AlumniID']); ?></p>
                    </div>
                </div>
                
                <div class="profile-details">
                    <div class="detail-group">
                        <label>Full Name</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['AlumniName']); ?>" readonly>
                    </div>
                    <div class="detail-group">
                        <label>Email</label>
                        <input type="email" value="<?php echo htmlspecialchars($user['Email']); ?>" readonly>
                    </div>
                    <div class="detail-group">
                        <label>Contact Number</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['ContactNo']); ?>" readonly>
                    </div>
                    <div class="detail-group">
                        <label>Address</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['Address']); ?>" readonly>
                    </div>
                    <div class="detail-group">
                        <label>Graduation Year</label>
                        <input type="number" value="<?php echo htmlspecialchars($user['GraduationYear']); ?>" readonly>
                    </div>
                    <div class="detail-group">
                        <label>Date of Birth</label>
                        <input type="date" value="<?php echo htmlspecialchars($user['DateOfBirth']); ?>" readonly>
                    </div>
                    <div class="detail-group">
                        <label>Current Job</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['Job']); ?>" readonly>
                    </div>
                    <div class="detail-group">
                        <label>Department</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['DepartmentID']); ?>" readonly>
                    </div>
                </div>
                
                <button class="btn" onclick="window.location.href='edit_profile.php'">Edit Profile</button>
            </div>
        </div>
    </div>
</body>
</html>