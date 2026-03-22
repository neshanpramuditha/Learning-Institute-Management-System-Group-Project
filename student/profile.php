<?php
include '../config.php';

if(!isLoggedIn() || !isStudent()) {
    redirect('../login.php');
}

$student_id = $_SESSION['user_id'];

// Get student details
$query = "SELECT * FROM users WHERE id = $student_id";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);

$message = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['update_profile'])) {
        $full_name = sanitize($_POST['full_name']);
        $email = sanitize($_POST['email']);
        $phone = sanitize($_POST['phone']);
        $address = sanitize($_POST['address']);
        $date_of_birth = sanitize($_POST['date_of_birth']);
        
        // Check if email is already used by another user
        $email_check = "SELECT * FROM users WHERE email = '$email' AND id != $student_id";
        $email_result = mysqli_query($conn, $email_check);
        
        if(mysqli_num_rows($email_result) > 0) {
            $message = '<div class="alert alert-danger">Email is already in use by another account!</div>';
        } else {
            $update_query = "UPDATE users SET full_name = '$full_name', email = '$email', phone = '$phone', 
                           address = '$address', date_of_birth = '$date_of_birth' WHERE id = $student_id";
            
            if(mysqli_query($conn, $update_query)) {
                $_SESSION['full_name'] = $full_name;
                $message = '<div class="alert alert-success">Profile updated successfully!</div>';
                // Refresh student data
                $result = mysqli_query($conn, $query);
                $student = mysqli_fetch_assoc($result);
            } else {
                $message = '<div class="alert alert-danger">Failed to update profile. Please try again.</div>';
            }
        }
    }
    
    if(isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if(!password_verify($current_password, $student['password'])) {
            $message = '<div class="alert alert-danger">Current password is incorrect!</div>';
        } elseif($new_password !== $confirm_password) {
            $message = '<div class="alert alert-danger">New passwords do not match!</div>';
        } elseif(strlen($new_password) < 6) {
            $message = '<div class="alert alert-danger">Password must be at least 6 characters long!</div>';
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $password_query = "UPDATE users SET password = '$hashed_password' WHERE id = $student_id";
            
            if(mysqli_query($conn, $password_query)) {
                $message = '<div class="alert alert-success">Password changed successfully!</div>';
            } else {
                $message = '<div class="alert alert-danger">Failed to change password. Please try again.</div>';
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
    <title>My Profile - University LMS</title>
    <link rel="stylesheet" href="../style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="/university_lms/index.php" class="logo" style="text-decoration:none; color:white;">
            <div style="display:flex; align-items:center; gap:10px;">
                <!-- Main Logo -->
                <img src="/university_lms/uploads/Logo.png" alt="Logo" style="height:50px;">

                <!-- New logo instead of NITE text -->
                <img src="/university_lms/uploads/new-logo.png" alt="NITE Logo" style="height:40px; width:120px;">
            </div>
        </a>
            <ul class="nav-links">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="../logout.php">Logout (<?php echo $_SESSION['full_name']; ?>)</a></li>
            </ul>
        </div>
    </nav>

    <div class="dashboard-layout">
        <aside class="sidebar">
            <h3 style="margin-bottom: 1.5rem; color: #667eea;">Student Menu</h3>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php"><i class='bx bx-grid-alt' style="color:#1E88E5;"></i>&nbsp; Dashboard</a></li>
                <li><a href="my-courses.php"><i class='bx bxs-book-reader' style="color:#4A6CF7;"></i>&nbsp; My Courses</a></li>
                <li><a href="assignments.php"><i class='bx bxs-edit' style="color:#28A745;"></i>&nbsp; Assignments</a></li>
                <li><a href="grades.php"><i class='bx bxs-bar-chart-alt-2' style="color:#FFC107;"></i>&nbsp; Grades</a></li>
                <li><a href="schedule.php"><i class='bx bxs-calendar' style="color:#DC3545;"></i>&nbsp; Schedule</a></li>
                <li><a href="resources.php"><i class='bx bxs-book-content' style="color:#20C997;"></i>&nbsp; Resources</a></li>
                <li><a href="profile.php" class="active"><i class='bx bxs-user'></i>&nbsp; Profile</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h1 style="color: #667eea; margin-bottom: 2rem;">My Profile</h1>
            
            <?php echo $message; ?>
            
            <!-- Profile Information -->
            <div class="card">
                <h2>Personal Information</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="full_name">Full Name *</label>
                        <input type="text" id="full_name" name="full_name" class="form-control" 
                               value="<?php echo htmlspecialchars($student['full_name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($student['username']); ?>" disabled>
                        <small style="color: #666;">Username cannot be changed</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="<?php echo htmlspecialchars($student['email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="form-control" 
                               value="<?php echo htmlspecialchars($student['phone'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" 
                               value="<?php echo $student['date_of_birth']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" class="form-control" rows="3"><?php echo htmlspecialchars($student['address'] ?? ''); ?></textarea>
                    </div>
                    
                    <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
            
            <!-- Change Password -->
            <div class="card">
                <h2>Change Password</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="current_password">Current Password *</label>
                        <input type="password" id="current_password" name="current_password" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">New Password *</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" required>
                        <small style="color: #666;">Minimum 6 characters</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password *</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                    </div>
                    
                    <button type="submit" name="change_password" class="btn btn-warning">Change Password</button>
                </form>
            </div>
            
            <!-- Account Information -->
            <div class="card">
                <h2>Account Information</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div>
                        <p style="color: #666; margin-bottom: 0.3rem;">Account Created</p>
                        <p style="font-weight: 600;"><?php echo date('F j, Y', strtotime($student['created_at'])); ?></p>
                    </div>
                    <div>
                        <p style="color: #666; margin-bottom: 0.3rem;">Last Updated</p>
                        <p style="font-weight: 600;"><?php echo date('F j, Y g:i A', strtotime($student['updated_at'])); ?></p>
                    </div>
                    <div>
                        <p style="color: #666; margin-bottom: 0.3rem;">Account Type</p>
                        <p style="font-weight: 600;">Student Account</p>
                    </div>
                    <div>
                        <p style="color: #666; margin-bottom: 0.3rem;">Student ID</p>
                        <p style="font-weight: 600;"><?php echo str_pad($student['id'], 6, '0', STR_PAD_LEFT); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Academic Summary -->
            <div class="card">
                <h2>Academic Summary</h2>
                <?php
                $enrolled_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM enrollments WHERE student_id = $student_id AND status = 'active'"))['total'];
                $completed_assignments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM submissions WHERE student_id = $student_id"))['total'];
                $average_grade = mysqli_fetch_assoc(mysqli_query($conn, "SELECT AVG((g.marks_obtained / a.total_marks) * 100) as avg 
                                                                         FROM grades g 
                                                                         INNER JOIN assignments a ON g.assignment_id = a.id 
                                                                         WHERE g.student_id = $student_id"))['avg'];
                ?>
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3><?php echo $enrolled_count; ?></h3>
                        <p>Enrolled Courses</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo $completed_assignments; ?></h3>
                        <p>Submitted Assignments</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo $average_grade ? number_format($average_grade, 1) . '%' : 'N/A'; ?></h3>
                        <p>Average Grade</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo $average_grade >= 90 ? 'A' : ($average_grade >= 80 ? 'B' : ($average_grade >= 70 ? 'C' : ($average_grade >= 60 ? 'D' : 'F'))); ?></h3>
                        <p>Grade Point</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?php 
$root_path = '../';
include '../footer.php'; 
?>
</body>
</html>