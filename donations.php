<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit;
}
include 'db.php';

// Fetch donations
$donations_query = "SELECT d.*, a.AlumniName FROM Donations d JOIN Alumni a ON d.AlumniID = a.AlumniID ORDER BY d.DonationDate DESC";
$donations_result = $conn->query($donations_query);

// Fetch alumni for dropdown
$alumni_query = "SELECT AlumniID, AlumniName FROM Alumni ORDER BY AlumniName";
$alumni_result = $conn->query($alumni_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Management - Alumni System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f5f7fa;
            color: #333;
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
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
            padding-bottom: 15px;
            border-bottom: 1px solid #34495e;
        }
        .sidebar ul {
            list-style: none;
        }
        .sidebar li {
            padding: 12px 20px;
            border-bottom: 1px solid #34495e;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .sidebar i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .sidebar a:hover {
            background-color: #34495e;
        }
        .main-content {
            flex: 1;
            padding: 30px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }
        .header h1 {
            color: #2c3e50;
            font-size: 28px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background-color: #3498db;
            color: white;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
        .btn-success {
            background-color: #2ecc71;
            color: white;
        }
        .btn-success:hover {
            background-color: #27ae60;
        }
        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }
        .btn-danger:hover {
            background-color: #c0392b;
        }
        .btn-warning {
            background-color: #f39c12;
            color: white;
        }
        .btn-warning:hover {
            background-color: #e67e22;
        }
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 25px;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .table tr:hover {
            background-color: #f8f9fa;
        }
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 8px;
            width: 60%;
            max-width: 600px;
            position: relative;
        }
        .close {
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 24px;
            cursor: pointer;
        }
        .notification {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            display: none;
        }
        .notification.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .notification.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Alumni System</h2>
            <ul>
                <li><a href="home.php"></i> Home</a></li>
                <li><a href="profile.php"></i> My Profile</a></li>
                <li><a href="alumni_directory.php"></i> Alumni Directory</a></li>
                <li><a href="events.php"></i> Events</a></li>
                <li><a href="donations.php" style="background-color: #34495e;"> Donations</a></li>   
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h1>Donation Management</h1>
                <button class="btn btn-primary" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Add New Donation
                </button>
            </div>
            
            <div id="notification" class="notification"></div>
            
            <div class="card">
                <h2 style="margin-bottom: 20px;">Donation Records</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Alumni</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Purpose</th>
                            <th>Payment Method</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($donations_result->num_rows > 0) {
                            while($row = $donations_result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["DonationID"] . "</td>";
                                echo "<td>" . $row["AlumniName"] . "</td>";
                                echo "<td>$" . number_format($row["DonationAmount"], 2) . "</td>";
                                echo "<td>" . date("M d, Y", strtotime($row["DonationDate"])) . "</td>";
                                echo "<td>" . $row["DonationPurpose"] . "</td>";
                                echo "<td>" . $row["PaymentMethod"] . "</td>";
                                echo "<td class='action-buttons'>";
                                echo "<button class='btn btn-warning' onclick='openEditModal(" . json_encode($row) . ")'><i class='fas fa-edit'></i> Edit</button>";
                                echo "<a href='process_donation.php?delete=" . $row["DonationID"] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this donation?\")'><i class='fas fa-trash'></i> Delete</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' style='text-align:center;'>No donation records found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Add Donation Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddModal()">&times;</span>
            <h2>Add New Donation</h2>
            <form action="process_donation.php" method="post">
                <div class="form-group">
                    <label for="alumni_id">Alumni</label>
                    <select class="form-control" id="alumni_id" name="alumni_id" required>
                        <option value="">Select Alumni</option>
                        <?php
                        if ($alumni_result->num_rows > 0) {
                            // Reset result pointer
                            $alumni_result->data_seek(0);
                            while($row = $alumni_result->fetch_assoc()) {
                                echo "<option value='" . $row["AlumniID"] . "'>" . $row["AlumniName"] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="amount">Amount ($)</label>
                    <input type="number" step="0.01" min="0.01" class="form-control" id="amount" name="amount" required>
                </div>
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
                <div class="form-group">
                    <label for="purpose">Purpose</label>
                    <input type="text" class="form-control" id="purpose" name="purpose" required>
                </div>
                <div class="form-group">
                    <label for="payment_method">Payment Method</label>
                    <select class="form-control" id="payment_method" name="payment_method" required>
                        <option value="">Select Payment Method</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Check">Check</option>
                        <option value="Cash">Cash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                    </select>
                </div>
                <button type="submit" name="add_donation" class="btn btn-success">Add Donation</button>
            </form>
        </div>
    </div>
    
    <!-- Edit Donation Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Donation</h2>
            <form action="process_donation.php" method="post">
                <input type="hidden" id="edit_donation_id" name="donation_id">
                <div class="form-group">
                    <label for="edit_alumni_id">Alumni</label>
                    <select class="form-control" id="edit_alumni_id" name="alumni_id" required>
                        <option value="">Select Alumni</option>
                        <?php
                        // Reset result pointer
                        $alumni_result->data_seek(0);
                        if ($alumni_result->num_rows > 0) {
                            while($row = $alumni_result->fetch_assoc()) {
                                echo "<option value='" . $row["AlumniID"] . "'>" . $row["AlumniName"] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit_amount">Amount ($)</label>
                    <input type="number" step="0.01" min="0.01" class="form-control" id="edit_amount" name="amount" required>
                </div>
                <div class="form-group">
                    <label for="edit_date">Date</label>
                    <input type="date" class="form-control" id="edit_date" name="date" required>
                </div>
                <div class="form-group">
                    <label for="edit_purpose">Purpose</label>
                    <input type="text" class="form-control" id="edit_purpose" name="purpose" required>
                </div>
                <div class="form-group">
                    <label for="edit_payment_method">Payment Method</label>
                    <select class="form-control" id="edit_payment_method" name="payment_method" required>
                        <option value="">Select Payment Method</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Check">Check</option>
                        <option value="Cash">Cash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                    </select>
                </div>
                <button type="submit" name="update_donation" class="btn btn-success">Update Donation</button>
            </form>
        </div>
    </div>
    
    <script>
        // Modal functions
        function openAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }
        
        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
        }
        
        function openEditModal(donation) {
            document.getElementById('edit_donation_id').value = donation.DonationID;
            document.getElementById('edit_alumni_id').value = donation.AlumniID;
            document.getElementById('edit_amount').value = donation.DonationAmount;
            document.getElementById('edit_date').value = donation.DonationDate;
            document.getElementById('edit_purpose').value = donation.DonationPurpose;
            document.getElementById('edit_payment_method').value = donation.PaymentMethod;
            document.getElementById('editModal').style.display = 'block';
        }
        
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        // Show notification
        function showNotification(message, type) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.className = 'notification ' + type;
            notification.style.display = 'block';
            
            setTimeout(() => {
                notification.style.display = 'none';
            }, 5000);
        }
        
        // Check for URL parameters to show notifications
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('added') === 'true') {
                showNotification('Donation added successfully!', 'success');
            }
            if (urlParams.get('updated') === 'true') {
                showNotification('Donation updated successfully!', 'success');
            }
            if (urlParams.get('deleted') === 'true') {
                showNotification('Donation deleted successfully!', 'success');
            }
            if (urlParams.get('error') === 'alumni') {
                showNotification('Error: Alumni ID not found', 'error');
            }
            if (urlParams.get('error') === 'insert') {
                showNotification('Error: Failed to add donation', 'error');
            }
            if (urlParams.get('error') === 'update') {
                showNotification('Error: Failed to update donation', 'error');
            }
            if (urlParams.get('error') === 'notfound') {
                showNotification('Error: Donation not found', 'error');
            }
        }
    </script>
</body>
</html>