<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Management System - Register</title>
    <style>
        /* Similar styles to login page */
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background: #1a3a44ff;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .register-container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }
        h1 {
            color: #1a2a6c;
            margin-bottom: 30px;
            text-align: center;
        }
        .input-group {
            margin-bottom: 20px;
        }
        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        .input-group input, .input-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn {
            background-color: #1a2a6c;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0d1638;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #1a2a6c;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
            display: none;
        }
        .error {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ef9a9a;
        }
        .success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>Alumni Registration</h1>
        <form id="registerForm" action="register_process.php" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="alumni_name">Full Name</label>
                <input type="text" id="alumni_name" name="alumni_name" required>
            </div>
            <div class="input-group">
                <label for="contact_no">Contact Number</label>
                <input type="text" id="contact_no" name="contact_no">
            </div>
            <div class="input-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address">
            </div>
            <div class="input-group">
                <label for="graduation_year">Graduation Year</label>
                <input type="number" id="graduation_year" name="graduation_year" min="1950" max=">=2025" required>
            </div>
            <div class="input-group">
                <label for="date_of_birth">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth">
            </div>
            <div class="input-group">
                <label for="job">Current Job</label>
                <input type="text" id="job" name="job">
            </div>
            <div class="input-group">
                <label for="department">Department</label>
                <select id="department" name="department" required>
                    <option value="">Select Department</option>
                    <option value="CS">Computer Science</option>
                    <option value="IT">Information Technology</option>
                    <option value="EC">Electronics & Communication</option>
                    <option value="ME">Mechanical Engineering</option>
                    <option value="CE">Civil Engineering</option>
                </select>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
        
        <div id="message" class="message"></div>
        
        <a href="index.php" class="back-link">Back to Login</a>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const confirm_password = document.getElementById('confirm_password').value;
            const messageDiv = document.getElementById('message');
            
            if (password !== confirm_password) {
                messageDiv.className = 'message error';
                messageDiv.textContent = 'Passwords do not match';
                messageDiv.style.display = 'block';
                return;
            }
            
            const formData = new FormData(this);
            
            fetch('register_process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageDiv.className = 'message success';
                    messageDiv.textContent = 'Registration successful! Redirecting to login...';
                    messageDiv.style.display = 'block';
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 2000);
                } else {
                    messageDiv.className = 'message error';
                    messageDiv.textContent = data.message;
                    messageDiv.style.display = 'block';
                }
            })
            .catch(error => {
                messageDiv.className = 'message error';
                messageDiv.textContent = 'An error occurred. Please try again.';
                messageDiv.style.display = 'block';
            });
        });
    </script>
</body>
</html>