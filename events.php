<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit;
}
include 'db.php';

// Get current user's AlumniID for permission checks
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT AlumniID FROM Alumni WHERE UserID = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $user_data = $result_user->fetch_assoc();
    $current_alumni_id = $user_data['AlumniID'];
} else {
    // If no alumni record exists, create one
    $username = $_SESSION['username'];
    $insert_sql = "INSERT INTO Alumni (UserID, AlumniName) VALUES (?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("is", $user_id, $username);
    $insert_stmt->execute();
    
    // Get the newly created AlumniID
    $current_alumni_id = $conn->insert_id;
}

// Fetch all events with AlumniID explicitly selected
$sql = "SELECT e.EventID, e.EventName, e.Date, e.Location, e.Organizer, e.Description, e.AlumniID, a.AlumniName 
        FROM Events e 
        LEFT JOIN Alumni a ON e.AlumniID = a.AlumniID 
        ORDER BY e.Date ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - Alumni Management System</title>
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
        .events-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .events-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .btn {
            background-color: #1a2a6c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color: #0d1638;
        }
        .btn-danger {
            background-color: #e74c3c;
        }
        .btn-danger:hover {
            background-color: #c0392b;
        }
        .events-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .event-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            background-color: #f9f9f9;
            position: relative;
        }
        .event-card h3 {
            color: #1a2a6c;
            margin-bottom: 10px;
        }
        .event-card p {
            margin-bottom: 5px;
            color: #555;
        }
        .event-date {
            font-weight: bold;
            color: #e74c3c;
        }
        .event-actions {
            margin-top: 15px;
            display: flex;
            gap: 10px;
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
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .success {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        .error {
            background-color: #ffebee;
            color: #c62828;
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
                <li onclick="window.location.href='profile.php'">My Profile</li>
                <li onclick="window.location.href='alumni_directory.php'">Alumni Directory</li>
                <li class="active">Events</li>
                <li onclick="window.location.href='donations.php'">Donations</li>
            </ul>
        </div>
        
        <div class="content">
            <div class="events-card">
                <div class="events-header">
                    <h2>Upcoming Events</h2>
                    <button class="btn" onclick="window.location.href='add_event.php'">Add Event</button>
                </div>
                
                <?php if (isset($_SESSION['event_success'])): ?>
                    <div class="message success">
                        <?php echo $_SESSION['event_success']; 
                        unset($_SESSION['event_success']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['event_error'])): ?>
                    <div class="message error">
                        <?php echo $_SESSION['event_error']; 
                        unset($_SESSION['event_error']); ?>
                    </div>
                <?php endif; ?>
                
                <div class="events-list">
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<div class='event-card'>";
                            echo "<h3>" . htmlspecialchars($row['EventName']) . "</h3>";
                            echo "<p class='event-date'>" . date("F j, Y", strtotime($row['Date'])) . "</p>";
                            echo "<p><strong>Location:</strong> " . htmlspecialchars($row['Location']) . "</p>";
                            echo "<p><strong>Organizer:</strong> " . htmlspecialchars($row['Organizer']) . "</p>";
                            echo "<p>" . htmlspecialchars($row['Description']) . "</p>";
                            
                            // Show edit/delete buttons only if user is the event owner
                            if ($row['AlumniID'] == $current_alumni_id) {
                                echo "<div class='event-actions'>";
                                echo "<a href='edit_event.php?id=" . urlencode($row['EventID']) . "' class='btn'>Edit</a>";
                                echo "<a href='delete_event.php?id=" . urlencode($row['EventID']) . "' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to delete this event?');\">Delete</a>";
                                echo "</div>";
                            }
                            
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No upcoming events found.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>