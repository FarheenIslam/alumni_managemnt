<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit;
}
include 'db.php';

// Fetch all alumni
$sql = "SELECT a.*, d.DepartmentName FROM Alumni a JOIN Departments d ON a.DepartmentID = d.DepartmentID";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Directory - Alumni Management System</title>
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
        .directory-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .search-bar {
            margin-bottom: 20px;
        }
        .search-bar input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .alumni-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .alumni-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            background-color: #f9f9f9;
        }
        .alumni-card h3 {
            color: #1a2a6c;
            margin-bottom: 10px;
        }
        .alumni-card p {
            margin-bottom: 5px;
            color: #555;
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
                <li onclick="window.location.href='profile.php'">My Profile</li>
                <li class="active">Alumni Directory</li>
                <li onclick="window.location.href='events.php'">Events</li>
                <li onclick="window.location.href='donations.php'">Donations</li>
            </ul>
        </div>
        
        <div class="content">
            <div class="directory-card">
                <h2>Alumni Directory</h2>
                <div class="search-bar">
                    <input type="text" placeholder="Search alumni by name, department, or graduation year..." id="searchInput">
                </div>
                <div class="alumni-list" id="alumniList">
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<div class='alumni-card'>";
                            echo "<h3>" . htmlspecialchars($row['AlumniName']) . "</h3>";
                            echo "<p><strong>Alumni ID:</strong> " . htmlspecialchars($row['AlumniID']) . "</p>";
                            echo "<p><strong>Department:</strong> " . htmlspecialchars($row['DepartmentName']) . "</p>";
                            echo "<p><strong>Graduation Year:</strong> " . htmlspecialchars($row['GraduationYear']) . "</p>";
                            echo "<p><strong>Email:</strong> " . htmlspecialchars($row['Email']) . "</p>";
                            echo "<p><strong>Contact:</strong> " . htmlspecialchars($row['ContactNo']) . "</p>";
                            echo "<p><strong>Job:</strong> " . htmlspecialchars($row['Job']) . "</p>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No alumni found.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simple search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let searchValue = this.value.toLowerCase();
            let alumniCards = document.querySelectorAll('.alumni-card');
            
            alumniCards.forEach(card => {
                let text = card.textContent.toLowerCase();
                if (text.indexOf(searchValue) > -1) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>