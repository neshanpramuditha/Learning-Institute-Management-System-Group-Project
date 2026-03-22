<?php
include 'config.php';

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    $role = sanitize($_POST['role']);
    
    $query = "SELECT * FROM users WHERE username = '$username' AND role = '$role'";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Verify password
        if(password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            
            if($role == 'admin') {
                redirect('admin/dashboard.php');
            } else {
                redirect('student/dashboard.php');
            }
        } else {
            $error = 'Invalid username or password!';
        }
    } else {
        $error = 'Invalid username or password!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - University LMS</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo" style="text-decoration:none; color:white;">
            <div style="display:flex; align-items:center; gap:10px;">
                <!-- Main Logo -->
                <img src="uploads/Logo.png" alt="Logo" style="height:50px;">

                <!-- New logo instead of NITE text -->
                <img src="uploads/new-logo.png" alt="NITE Logo" style="height:40px; width:120px;">
            </div>
        </a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="courses.php">Courses</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="register.php">Register</a></li>
            </ul>
        </div>
    </nav>

    <div class="login-container">
        <div class="card">
            <h1 style="text-align: center; color: #667eea; margin-bottom: 2rem;">Login to Your Account</h1>
            
            <?php if($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <!-- Login Tabs -->
            <div class="login-tabs">
                <button class="tab active" onclick="switchTab('student')">Student Login</button>
                <button class="tab" onclick="switchTab('admin')">Admin Login</button>
            </div>
            
            <!-- Student Login Form -->
            <div id="student-tab" class="tab-content active">
                <form method="POST" action="">
                    <input type="hidden" name="role" value="student">
                    
                    <div class="form-group">
                        <label for="student-username">Username</label>
                        <input type="text" id="student-username" name="username" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="student-password">Password</label>
                        <input type="password" id="student-password" name="password" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Login as Student</button>
                    
                    <p style="text-align: center; margin-top: 1.5rem; color: #666;">
                        Don't have an account? <a href="register.php" style="color: #667eea; font-weight: 600;">Register here</a>
                    </p>
                    
                </form>
            </div>
            
            <!-- Admin Login Form -->
            <div id="admin-tab" class="tab-content">
                <form method="POST" action="">
                    <input type="hidden" name="role" value="admin">
                    
                    <div class="form-group">
                        <label for="admin-username">Username</label>
                        <input type="text" id="admin-username" name="username" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="admin-password">Password</label>
                        <input type="password" id="admin-password" name="password" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Login as Admin</button>
                    
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
   <?php include 'footer.php'; ?>

    <script>
        function switchTab(role) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected tab
            if(role === 'student') {
                document.getElementById('student-tab').classList.add('active');
                document.querySelectorAll('.tab')[0].classList.add('active');
            } else {
                document.getElementById('admin-tab').classList.add('active');
                document.querySelectorAll('.tab')[1].classList.add('active');
            }
        }
    </script>

</body>
</html>