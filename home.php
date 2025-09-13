<?php
session_start();
include 'db.php'; // Database connection

// Get statistics from database
$totalAlumni = 0;
$upcomingEvents = 0;
$totalDonations = 0;

// Get total alumni count
$sql = "SELECT COUNT(*) as count FROM Alumni";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalAlumni = $row['count'];
}

// Get upcoming events count
$sql = "SELECT COUNT(*) as count FROM Events WHERE Date >= CURDATE()";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $upcomingEvents = $row['count'];
}

// Get total donations
$sql = "SELECT SUM(DonationAmount) as total FROM Donations";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalDonations = $row['total'] ? $row['total'] : 0;
}

// Get recent activities (only Events and Donations)
$activities = [];
$sql = "SELECT 'Event' as type, EventName as title, Date as date, Description as description 
        FROM Events 
        UNION ALL
        SELECT 'Donation' as type, CONCAT('$', DonationAmount, ' Donation') as title, DonationDate as date, DonationPurpose as description 
        FROM Donations
        ORDER BY date DESC 
        LIMIT 4";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $activities[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Management System - Home</title>
    <style>
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
            padding: 15px;
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
        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
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
        .welcome-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .stats-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .stat-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 31%; /* Adjusted width for 3 cards instead of 4 */
            text-align: center;
        }
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #1a2a6c;
            margin-bottom: 10px;
        }
        .stat-label {
            color: #7f8c8d;
        }
        .recent-activities {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .section-title {
            font-size: 20px;
            margin-bottom: 15px;
            color: #2c3e50;
        }
        .activity-list {
            list-style: none;
        }
        .activity-item {
            padding: 10px 0;
            border-bottom: 1px solid #ecf0f1;
        }
        .activity-item:last-child {
            border-bottom: none;
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
            <span>Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User'; ?></span>
            <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
        </div>
    </div>
    
    <div class="container">
        <div class="sidebar">
            <ul class="sidebar-menu">
                <li class="active">Home</li>
                <li onclick="window.location.href='profile.php'">My Profile</li>
                <li onclick="window.location.href='alumni_directory.php'">Alumni Directory</li>
                <li onclick="window.location.href='events.php'">Events</li>
                <li onclick="window.location.href='donations.php'">Donations</li>
            </ul>
        </div>
        
        <div class="content">
            <div class="welcome-card">
                <h2>Welcome to Alumni Management System</h2>
                <p>Connect with fellow alumni, stay updated with events.</p>
            </div>
            
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $totalAlumni; ?></div>
                    <div class="stat-label">Total Alumni</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $upcomingEvents; ?></div>
                    <div class="stat-label">Upcoming Events</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">$<?php echo number_format($totalDonations, 2); ?></div>
                    <div class="stat-label">Total Donations</div>
                </div>
            </div>
            
            <div class="recent-activities">
                <h3 class="section-title">Recent Activities</h3>
                <ul class="activity-list">
                    <?php
                    if (count($activities) > 0) {
                        foreach ($activities as $activity) {
                            echo "<li class='activity-item'>";
                            echo htmlspecialchars($activity['title']) . " - " . date("F j, Y", strtotime($activity['date']));
                            echo "</li>";
                        }
                    } else {
                        echo "<li class='activity-item'>No recent activities found.</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>