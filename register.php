<?php
include 'config.php';

// Redirect if already logged in
if(isLoggedIn()) {
    if(isAdmin()) {
        redirect('admin/dashboard.php');
    } else {
        redirect('student/dashboard.php');
    }
}

$message = '';
$success = false;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = sanitize($_POST['full_name']);
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = sanitize($_POST['phone']);
    $date_of_birth = sanitize($_POST['date_of_birth']);
    $address = sanitize($_POST['address']);
    
    // Validation
    if(strlen($username) < 4) {
        $message = '<div class="alert alert-danger">Username must be at least 4 characters long!</div>';
    } elseif(strlen($password) < 6) {
        $message = '<div class="alert alert-danger">Password must be at least 6 characters long!</div>';
    } elseif($password !== $confirm_password) {
        $message = '<div class="alert alert-danger">Passwords do not match!</div>';
    } else {
        // Check if username or email already exists
        $check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $check_result = mysqli_query($conn, $check_query);
        
        if(mysqli_num_rows($check_result) > 0) {
            $existing = mysqli_fetch_assoc($check_result);
            if($existing['username'] == $username) {
                $message = '<div class="alert alert-danger">Username already taken! Please choose a different username.</div>';
            } else {
                $message = '<div class="alert alert-danger">Email already registered! Please use a different email.</div>';
            }
        } else {
            // Hash password and insert new student
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $insert_query = "INSERT INTO users (username, password, email, full_name, role, phone, date_of_birth, address) 
                           VALUES ('$username', '$hashed_password', '$email', '$full_name', 'student', '$phone', '$date_of_birth', '$address')";
            
            if(mysqli_query($conn, $insert_query)) {
                $success = true;
                $message = '<div class="alert alert-success">
                              <h3>✅ Registration Successful!</h3>
                              <p>Your account has been created successfully.</p>
                              <p><strong>Username:</strong> ' . htmlspecialchars($username) . '</p>
                              <p>You can now login with your credentials.</p>
                              <a href="login.php" class="btn btn-primary" style="margin-top: 1rem;">Go to Login Page</a>
                            </div>';
            } else {
                $message = '<div class="alert alert-danger">Registration failed! Please try again. Error: ' . mysqli_error($conn) . '</div>';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - University LMS</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .registration-container {
            max-width: 600px;
            margin: 3rem auto;
        }
        .password-strength {
            height: 5px;
            margin-top: 5px;
            border-radius: 3px;
            transition: all 0.3s;
        }
        .strength-weak { background: #dc3545; width: 33%; }
        .strength-medium { background: #ffc107; width: 66%; }
        .strength-strong { background: #28a745; width: 100%; }
    </style>
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
                <li><a href="login.php">Login</a></li>
            </ul>
        </div>
    </nav>

    <div class="registration-container">
        <div class="card">
            <h1 style="text-align: center; color: #667eea; margin-bottom: 1rem;">Student Registration</h1>
            <p style="text-align: center; color: #666; margin-bottom: 2rem;">Create your account to access the learning portal</p>
            
            <?php echo $message; ?>
            
            <?php if(!$success): ?>
            <form method="POST" action="" id="registrationForm">
                <div class="form-group">
                    <label for="full_name">Full Name *</label>
                    <input type="text" id="full_name" name="full_name" class="form-control" 
                           placeholder="Enter your full name" required 
                           value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="username">Username *</label>
                    <input type="text" id="username" name="username" class="form-control" 
                           placeholder="Choose a username (min 4 characters)" required minlength="4"
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                    <small style="color: #666;">This will be used for login</small>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" class="form-control" 
                           placeholder="your.email@example.com" required
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" class="form-control" 
                           placeholder="Enter password (min 6 characters)" required minlength="6"
                           onkeyup="checkPasswordStrength()">
                    <div id="passwordStrength" class="password-strength"></div>
                    <small id="strengthText" style="color: #666;"></small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" 
                           placeholder="Re-enter your password" required minlength="6">
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="form-control" 
                           placeholder="e.g., 0771234567"
                           value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="date_of_birth">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" class="form-control"
                           value="<?php echo isset($_POST['date_of_birth']) ? htmlspecialchars($_POST['date_of_birth']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" class="form-control" rows="3" 
                              placeholder="Enter your full address"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" required style="margin-right: 10px; width: auto;">
                        I agree to the <a href="#" style="color: #667eea; margin-left: 5px;">Terms and Conditions</a>
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Register</button>
                
                <p style="text-align: center; margin-top: 1.5rem; color: #666;">
                    Already have an account? <a href="login.php" style="color: #667eea; font-weight: 600;">Login here</a>
                </p>
            </form>
            <?php endif; ?>
        </div>
        
        <div class="card" style="margin-top: 2rem;">
            <h3 style="color: #667eea;">Why Register?</h3>
            <ul style="line-height: 2; color: #666; margin-left: 1.5rem;">
                <li>Access to all available courses</li>
                <li>Submit assignments online</li>
                <li>Track your academic progress</li>
                <li>View grades and feedback</li>
                <li>Download course materials</li>
                <li>Receive important announcements</li>
            </ul>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <script>
        // Password strength checker
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('passwordStrength');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            
            if(password.length >= 6) strength++;
            if(password.length >= 10) strength++;
            if(/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if(/\d/.test(password)) strength++;
            if(/[^a-zA-Z0-9]/.test(password)) strength++;
            
            strengthBar.className = 'password-strength';
            
            if(password.length === 0) {
                strengthBar.className = 'password-strength';
                strengthText.textContent = '';
            } else if(strength <= 2) {
                strengthBar.className = 'password-strength strength-weak';
                strengthText.textContent = '⚠️ Weak password';
                strengthText.style.color = '#dc3545';
            } else if(strength <= 4) {
                strengthBar.className = 'password-strength strength-medium';
                strengthText.textContent = '⚡ Medium strength';
                strengthText.style.color = '#ffc107';
            } else {
                strengthBar.className = 'password-strength strength-strong';
                strengthText.textContent = '✅ Strong password';
                strengthText.style.color = '#28a745';
            }
        }
        
        // Form validation
        document.getElementById('registrationForm')?.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if(password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
        });
    </script>
</body>
</html>