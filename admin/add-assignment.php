<?php
include '../config.php';

if(!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$message = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = (int)$_POST['course_id'];
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $due_date = sanitize($_POST['due_date']);
    $total_marks = (int)$_POST['total_marks'];
    
    $insert_query = "INSERT INTO assignments (course_id, title, description, due_date, total_marks) 
                    VALUES ($course_id, '$title', '$description', '$due_date', $total_marks)";
    
    if(mysqli_query($conn, $insert_query)) {
        $_SESSION['message'] = 'Assignment created successfully!';
        $_SESSION['message_type'] = 'success';
        redirect('assignments.php');
    } else {
        $message = '<div class="alert alert-danger">Failed to create assignment. Please try again.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Assignment - University LMS</title>
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
                <li><a href="courses.php"><i class='bx bxs-book-reader' style="color:#4A6CF7;"></i>&nbsp; Courses</a></li>
                <li><a href="assignments.php" class="active"><i class='bx bxs-edit'></i>&nbsp; Assignments</a></li>
                <li><a href="grades.php"><i class='bx bxs-bar-chart-alt-2' style="color:#FFC107;"></i>&nbsp; Grades</a></li>
                <li><a href="announcements.php"><i class='bx bxs-megaphone' style="color:#6F42C1;"></i>&nbsp; Announcements</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h1 style="color: #667eea; margin-bottom: 2rem;">Create New Assignment</h1>
            
            <div class="card">
                <?php echo $message; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="course_id">Select Course *</label>
                        <select id="course_id" name="course_id" class="form-control" required>
                            <option value="">Choose a course...</option>
                            <?php
                            $courses_query = "SELECT * FROM courses WHERE status = 'active' ORDER BY course_code";
                            $courses_result = mysqli_query($conn, $courses_query);
                            while($course = mysqli_fetch_assoc($courses_result)):
                            ?>
                                <option value="<?php echo $course['id']; ?>">
                                    <?php echo htmlspecialchars($course['course_code']) . ' - ' . htmlspecialchars($course['course_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="title">Assignment Title *</label>
                        <input type="text" id="title" name="title" class="form-control" required placeholder="e.g., Midterm Project - Web Development">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Assignment Description *</label>
                        <textarea id="description" name="description" class="form-control" rows="6" required placeholder="Provide detailed instructions, requirements, and any special notes..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="due_date">Due Date & Time *</label>
                        <input type="datetime-local" id="due_date" name="due_date" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="total_marks">Total Marks *</label>
                        <input type="number" id="total_marks" name="total_marks" class="form-control" min="1" max="1000" value="100" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Create Assignment</button>
                    <a href="assignments.php" class="btn" style="margin-left: 1rem;">Cancel</a>
                </form>
            </div>
        </main>
    </div>

    <?php 
$root_path = '../';
include '../footer.php'; 
?>
</body>
</html>