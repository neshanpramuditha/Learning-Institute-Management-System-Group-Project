<?php
include '../config.php';

if(!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

if(!isset($_GET['id'])) {
    redirect('students.php');
}

$student_id = (int)$_GET['id'];

// Get student details
$query = "SELECT * FROM users WHERE id = $student_id AND role = 'student'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0) {
    redirect('students.php');
}

$student = mysqli_fetch_assoc($result);

$message = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = sanitize($_POST['full_name']);
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $address = sanitize($_POST['address']);
    $date_of_birth = sanitize($_POST['date_of_birth']);
    
    // Check if username or email already exists (excluding current student)
    $check_query = "SELECT * FROM users WHERE (username = '$username' OR email = '$email') AND id != $student_id";
    $check_result = mysqli_query($conn, $check_query);
    
    if(mysqli_num_rows($check_result) > 0) {
        $message = '<div class="alert alert-danger">Username or email already exists!</div>';
    } else {
        $update_query = "UPDATE users SET full_name = '$full_name', username = '$username', email = '$email', 
                        phone = '$phone', address = '$address', date_of_birth = '$date_of_birth' 
                        WHERE id = $student_id";
        
        if(mysqli_query($conn, $update_query)) {
            // Update password if provided
            if(!empty($_POST['password'])) {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                mysqli_query($conn, "UPDATE users SET password = '$password' WHERE id = $student_id");
            }
            
            $_SESSION['message'] = 'Student updated successfully!';
            $_SESSION['message_type'] = 'success';
            redirect('students.php');
        } else {
            $message = '<div class="alert alert-danger">Failed to update student. Please try again.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - University LMS</title>
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
                <li><a href="dashboard.php">Admin Dashboard</a></li>
                <li><a href="../logout.php">Logout (<?php echo $_SESSION['full_name']; ?>)</a></li>
            </ul>
        </div>
    </nav>

    <div class="dashboard-layout">
        <aside class="sidebar">
            <h3 style="margin-bottom: 1.5rem; color: #667eea;">Admin Menu</h3>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php"><i class='bx bx-grid-alt' style="color:#1E88E5;"></i>&nbsp; Dashboard</a></li>
                <li><a href="students.php" class="active"><i class='bx bx-group'></i>&nbsp; Students</a></li>
                <li><a href="courses.php"><i class='bx bxs-book-reader' style="color:#4A6CF7;"></i>&nbsp; Courses</a></li>
                <li><a href="assignments.php"><i class='bx bxs-edit' style="color:#28A745;"></i>&nbsp; Assignments</a></li>
                <li><a href="grades.php"><i class='bx bxs-bar-chart-alt-2' style="color:#FFC107;"></i>&nbsp; Grades</a></li>
                <li><a href="announcements.php"><i class='bx bxs-megaphone' style="color:#6F42C1;"></i>&nbsp; Announcements</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h1 style="color: #667eea; margin-bottom: 2rem;">Edit Student</h1>
            
            <div class="card">
                <?php echo $message; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="full_name">Full Name *</label>
                        <input type="text" id="full_name" name="full_name" class="form-control" 
                               value="<?php echo htmlspecialchars($student['full_name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="username">Username *</label>
                        <input type="text" id="username" name="username" class="form-control" 
                               value="<?php echo htmlspecialchars($student['username']); ?>" required>
                        <small style="color: #666;">Must be unique</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="<?php echo htmlspecialchars($student['email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" id="password" name="password" class="form-control">
                        <small style="color: #666;">Leave blank to keep current password</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone</label>
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
                    
                    <button type="submit" class="btn btn-primary">Update Student</button>
                    <a href="students.php" class="btn" style="margin-left: 1rem;">Cancel</a>
                </form>
            </div>
            
            <!-- Student Statistics -->
            <div class="card">
                <h2>Student Statistics</h2>
                <?php
                $enrolled_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM enrollments WHERE student_id = $student_id AND status = 'active'"))['total'];
                $submissions_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM submissions WHERE student_id = $student_id"))['total'];
                $graded_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM grades WHERE student_id = $student_id"))['total'];
                ?>
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3><?php echo $enrolled_count; ?></h3>
                        <p>Enrolled Courses</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo $submissions_count; ?></h3>
                        <p>Submissions</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo $graded_count; ?></h3>
                        <p>Graded Assignments</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo date('M j, Y', strtotime($student['created_at'])); ?></h3>
                        <p>Joined Date</p>
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