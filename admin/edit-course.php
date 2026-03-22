<?php
include '../config.php';

if(!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

if(!isset($_GET['id'])) {
    redirect('courses.php');
}

$course_id = (int)$_GET['id'];

// Get course details
$query = "SELECT * FROM courses WHERE id = $course_id";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0) {
    redirect('courses.php');
}

$course = mysqli_fetch_assoc($result);

$message = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_code = sanitize($_POST['course_code']);
    $course_name = sanitize($_POST['course_name']);
    $description = sanitize($_POST['description']);
    $instructor_name = sanitize($_POST['instructor_name']);
    $credits = (int)$_POST['credits'];
    $semester = sanitize($_POST['semester']);
    $status = sanitize($_POST['status']);
    
    // Check if course code already exists (excluding current course)
    $check_query = "SELECT * FROM courses WHERE course_code = '$course_code' AND id != $course_id";
    $check_result = mysqli_query($conn, $check_query);
    
    if(mysqli_num_rows($check_result) > 0) {
        $message = '<div class="alert alert-danger">Course code already exists!</div>';
    } else {
        $update_query = "UPDATE courses SET course_code = '$course_code', course_name = '$course_name', 
                        description = '$description', instructor_name = '$instructor_name', 
                        credits = $credits, semester = '$semester', status = '$status' 
                        WHERE id = $course_id";
        
        if(mysqli_query($conn, $update_query)) {
            $_SESSION['message'] = 'Course updated successfully!';
            $_SESSION['message_type'] = 'success';
            redirect('courses.php');
        } else {
            $message = '<div class="alert alert-danger">Failed to update course. Please try again.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course - University LMS</title>
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
                <li><a href="students.php"><i class='bx bx-group' style="color:#2ecc71;"></i>&nbsp; Students</a></li>
                <li><a href="courses.php" class="active"><i class='bx bxs-book-reader'></i>&nbsp; Courses</a></li>
                <li><a href="assignments.php"><i class='bx bxs-edit' style="color:#28A745;"></i>&nbsp; Assignments</a></li>
                <li><a href="grades.php"><i class='bx bxs-bar-chart-alt-2' style="color:#FFC107;"></i>&nbsp; Grades</a></li>
                <li><a href="announcements.php"><i class='bx bxs-megaphone' style="color:#6F42C1;"></i>&nbsp; Announcements</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h1 style="color: #667eea; margin-bottom: 2rem;">Edit Course</h1>
            
            <div class="card">
                <?php echo $message; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="course_code">Course Code *</label>
                        <input type="text" id="course_code" name="course_code" class="form-control" 
                               value="<?php echo htmlspecialchars($course['course_code']); ?>" required>
                        <small style="color: #666;">Must be unique (e.g., CS101, MATH201)</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="course_name">Course Name *</label>
                        <input type="text" id="course_name" name="course_name" class="form-control" 
                               value="<?php echo htmlspecialchars($course['course_name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Course Description</label>
                        <textarea id="description" name="description" class="form-control" rows="4"><?php echo htmlspecialchars($course['description']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="instructor_name">Instructor Name *</label>
                        <input type="text" id="instructor_name" name="instructor_name" class="form-control" 
                               value="<?php echo htmlspecialchars($course['instructor_name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="credits">Credits *</label>
                        <input type="number" id="credits" name="credits" class="form-control" 
                               min="1" max="10" value="<?php echo $course['credits']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="semester">Semester *</label>
                        <select id="semester" name="semester" class="form-control" required>
                            <option value="">Select Semester</option>
                            <option value="Spring 2024" <?php echo $course['semester'] == 'Spring 2024' ? 'selected' : ''; ?>>Spring 2024</option>
                            <option value="Summer 2024" <?php echo $course['semester'] == 'Summer 2024' ? 'selected' : ''; ?>>Summer 2024</option>
                            <option value="Fall 2024" <?php echo $course['semester'] == 'Fall 2024' ? 'selected' : ''; ?>>Fall 2024</option>
                            <option value="Winter 2025" <?php echo $course['semester'] == 'Winter 2025' ? 'selected' : ''; ?>>Winter 2025</option>
                            <option value="Spring 2025" <?php echo $course['semester'] == 'Spring 2025' ? 'selected' : ''; ?>>Spring 2025</option>
                            <option value="Summer 2025" <?php echo $course['semester'] == 'Summer 2025' ? 'selected' : ''; ?>>Summer 2025</option>
                            <option value="Fall 2025" <?php echo $course['semester'] == 'Fall 2025' ? 'selected' : ''; ?>>Fall 2025</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="active" <?php echo $course['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo $course['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update Course</button>
                    <a href="courses.php" class="btn" style="margin-left: 1rem;">Cancel</a>
                </form>
            </div>
            
            <!-- Course Statistics -->
            <div class="card">
                <h2><i class='bx  bx-file' style="color:#9C27B0;"></i> Course Statistics</h2>
                <?php
                $enrolled_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM enrollments WHERE course_id = $course_id AND status = 'active'"))['total'];
                $assignments_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM assignments WHERE course_id = $course_id"))['total'];
                $materials_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM course_materials WHERE course_id = $course_id"))['total'];
                $schedules_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM schedules WHERE course_id = $course_id"))['total'];
                ?>
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3><?php echo $enrolled_count; ?></h3>
                        <p>Enrolled Students</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo $assignments_count; ?></h3>
                        <p>Assignments</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo $materials_count; ?></h3>
                        <p>Course Materials</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo $schedules_count; ?></h3>
                        <p>Schedule Entries</p>
                    </div>
                </div>
                
                <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #ddd;">
                    <p><strong>Created:</strong> <?php echo date('F j, Y g:i A', strtotime($course['created_at'])); ?></p>
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