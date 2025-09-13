<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit;
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

include 'db.php';

// Check for success or error messages
$success_message = '';
$error_message = '';

if (isset($_SESSION['event_success']) && $_SESSION['event_success']) {
    $success_message = "Event added successfully!";
    unset($_SESSION['event_success']);
}
if (isset($_SESSION['event_error'])) {
    $error_message = $_SESSION['event_error'];
    unset($_SESSION['event_error']);
}

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['event_error'] = "Invalid request. Please try again.";
        header("Location: add_event.php");
        exit();
    }

    // Get form data
    $event_id = filter_input(INPUT_POST, 'event_id', FILTER_SANITIZE_STRING);
    $event_name = filter_input(INPUT_POST, 'event_name', FILTER_SANITIZE_STRING);
    $event_date = filter_input(INPUT_POST, 'event_date', FILTER_SANITIZE_STRING);
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
    $organizer = filter_input(INPUT_POST, 'organizer', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

    // Basic validation
    if (empty($event_id) || empty($event_name) || empty($event_date) || empty($location)) {
        $_SESSION['event_error'] = "Please fill in all required fields.";
        header("Location: add_event.php");
        exit();
    }

    // Get user's alumni ID
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT AlumniID FROM Alumni WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $alumni_id = $user['AlumniID'];

        // Insert event into database
        $insert_sql = "INSERT INTO Events (EventID, EventName, AlumniID, Date, Location, Organizer, Description) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sssssss", $event_id, $event_name, $alumni_id, $event_date, $location, $organizer, $description);

        if ($stmt->execute()) {
            $_SESSION['event_success'] = true;
            header("Location: events.php");
            exit();
        } else {
            $_SESSION['event_error'] = "Error adding event: " . $conn->error;
            header("Location: add_event.php");
            exit();
        }
    } else {
        $_SESSION['event_error'] = "User alumni record not found.";
        header("Location: add_event.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event - Alumni Management System</title>
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
            background-color: #2c3e50;
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
            background-color: #3498db;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .event-form-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #2c3e50;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-group textarea {
            height: 100px;
            resize: vertical;
        }
        .btn {
            background-color: #1a2a6c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
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
            <div class="event-form-card">
                <h2>Add New Event</h2>
                
                <?php if (!empty($success_message)): ?>
                    <div class="message success">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($error_message)): ?>
                    <div class="message error">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <form action="add_event.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="form-group">
                        <label for="event_id">Event ID</label>
                        <input type="text" id="event_id" name="event_id" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="event_name">Event Name</label>
                        <input type="text" id="event_name" name="event_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="event_date">Event Date</label>
                        <input type="date" id="event_date" name="event_date" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="organizer">Organizer</label>
                        <input type="text" id="organizer" name="organizer">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"></textarea>
                    </div>
                    
                    <button type="submit" class="btn">Add Event</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>