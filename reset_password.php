<?php
// Password Reset Tool for University LMS
// Place this file in root: C:\xampp\htdocs\university_lms\reset_password.php
// Access: http://localhost/university_lms/reset_password.php
// DELETE THIS FILE AFTER RESETTING PASSWORDS!

include 'config.php';

$message = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $new_password = $_POST['new_password'];
    
    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update password
    $update_query = "UPDATE users SET password = '$hashed_password' WHERE username = '$username'";
    
    if(mysqli_query($conn, $update_query)) {
        if(mysqli_affected_rows($conn) > 0) {
            $message = "<div style='background: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;'>
                        ✅ Password updated successfully for user: <strong>$username</strong><br>
                        New password: <strong>$new_password</strong><br><br>
                        <a href='login.php' style='color: #155724; text-decoration: underline;'>Go to Login Page</a>
                        </div>";
        } else {
            $message = "<div style='background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;'>
                        ❌ User not found: <strong>$username</strong>
                        </div>";
        }
    } else {
        $message = "<div style='background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;'>
                    ❌ Error: " . mysqli_error($conn) . "
                    </div>";
    }
}

// Get all users
$users_query = "SELECT id, username, email, role FROM users ORDER BY role, username";
$users_result = mysqli_query($conn, $users_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Tool - University LMS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f4f7f9;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        h1 {
            color: #667eea;
            margin-bottom: 10px;
        }
        .warning {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #ffc107;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background: #667eea;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background: #5568d3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #667eea;
            color: white;
        }
        .quick-reset {
            background: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            text-decoration: none;
            font-size: 12px;
        }
        .quick-reset:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Password Reset Tool</h1>
        <p style="color: #666; margin-bottom: 20px;">Reset passwords for any user in the system</p>
        
        <div class="warning">
            <strong>⚠️ SECURITY WARNING:</strong> Delete this file (reset_password.php) after resetting passwords!<br>
            This tool should not be accessible in production.
        </div>
        
        <?php echo $message; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <select id="username" name="username" required>
                    <option value="">Select User...</option>
                    <?php 
                    mysqli_data_seek($users_result, 0);
                    while($user = mysqli_fetch_assoc($users_result)): 
                    ?>
                        <option value="<?php echo htmlspecialchars($user['username']); ?>">
                            <?php echo htmlspecialchars($user['username']); ?> 
                            (<?php echo htmlspecialchars($user['email']); ?>) 
                            - <?php echo ucfirst($user['role']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="text" id="new_password" name="new_password" required placeholder="Enter new password">
                <small style="color: #666;">Minimum 6 characters recommended</small>
            </div>
            
            <button type="submit">🔄 Reset Password</button>
        </form>
        
        <h2 style="margin-top: 40px; color: #667eea;">👥 All Users</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Quick Reset</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                mysqli_data_seek($users_result, 0);
                while($user = mysqli_fetch_assoc($users_result)): 
                ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo ucfirst($user['role']); ?></td>
                        <td>
                            <form method="POST" action="" style="margin: 0;">
                                <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                                <input type="hidden" name="new_password" value="<?php echo $user['role'] == 'admin' ? 'admin123' : 'student123'; ?>">
                                <button type="submit" class="quick-reset">Reset to Default</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 5px;">
            <h3 style="margin-top: 0; color: #667eea;">📋 Default Passwords:</h3>
            <ul style="line-height: 2;">
                <li><strong>Admin:</strong> admin123</li>
                <li><strong>Students:</strong> student123</li>
            </ul>
            <p style="color: #666; margin-top: 15px;">
                <strong>Note:</strong> After resetting, you can login with the username and new password at: 
                <a href="login.php" style="color: #667eea;">login.php</a>
            </p>
        </div>
    </div>
</body>
</html>