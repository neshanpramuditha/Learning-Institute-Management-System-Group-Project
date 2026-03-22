<?php
include '../config.php';

if(!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

if(!isset($_GET['id'])) {
    redirect('grades.php');
}

$submission_id = (int)$_GET['id'];

// Get submission details
$query = "SELECT s.*, a.title as assignment_title, a.total_marks, a.description as assignment_description,
         c.course_code, c.course_name, u.full_name as student_name, u.email as student_email,
         g.marks_obtained, g.feedback, g.graded_at
         FROM submissions s
         INNER JOIN assignments a ON s.assignment_id = a.id
         INNER JOIN courses c ON a.course_id = c.id
         INNER JOIN users u ON s.student_id = u.id
         LEFT JOIN grades g ON s.id = g.submission_id
         WHERE s.id = $submission_id";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0) {
    redirect('grades.php');
}

$submission = mysqli_fetch_assoc($result);

$message = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $marks_obtained = (int)$_POST['marks_obtained'];
    $feedback = sanitize($_POST['feedback']);
    $graded_by = $_SESSION['user_id'];
    
    // Validate marks
    if($marks_obtained > $submission['total_marks']) {
        $message = '<div class="alert alert-danger">Marks cannot exceed total marks!</div>';
    } else {
        // Check if already graded
        if($submission['marks_obtained'] !== null) {
            // Update existing grade
            $update_grade = "UPDATE grades SET marks_obtained = $marks_obtained, feedback = '$feedback', graded_at = NOW() 
                           WHERE submission_id = $submission_id";
            mysqli_query($conn, $update_grade);
        } else {
            // Insert new grade
            $insert_grade = "INSERT INTO grades (submission_id, student_id, assignment_id, marks_obtained, feedback, graded_by) 
                           VALUES ($submission_id, {$submission['student_id']}, {$submission['assignment_id']}, $marks_obtained, '$feedback', $graded_by)";
            mysqli_query($conn, $insert_grade);
        }
        
        // Update submission status
        $update_submission = "UPDATE submissions SET status = 'graded' WHERE id = $submission_id";
        mysqli_query($conn, $update_submission);
        
        $_SESSION['message'] = 'Submission graded successfully!';
        $_SESSION['message_type'] = 'success';
        redirect('grades.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Submission - University LMS</title>
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
                <li><a href="assignments.php"><i class='bx bxs-edit' style="color:#28A745;"></i>&nbsp; Assignments</a></li>
                <li><a href="grades.php" class="active"><i class='bx bxs-bar-chart-alt-2'></i>&nbsp; Grades</a></li>
                <li><a href="announcements.php"><i class='bx bxs-megaphone' style="color:#6F42C1;"></i>&nbsp;Announcements</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h1 style="color: #667eea; margin-bottom: 2rem;">Grade Submission</h1>
            
            <!-- Student & Assignment Info -->
            <div class="card">
                <h2>Submission Details</h2>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                    <div>
                        <p><strong>Student:</strong> <?php echo htmlspecialchars($submission['student_name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($submission['student_email']); ?></p>
                        <p><strong>Submitted:</strong> <?php echo date('F j, Y g:i A', strtotime($submission['submitted_at'])); ?></p>
                    </div>
                    <div>
                        <p><strong>Course:</strong> <?php echo htmlspecialchars($submission['course_code']) . ' - ' . htmlspecialchars($submission['course_name']); ?></p>
                        <p><strong>Assignment:</strong> <?php echo htmlspecialchars($submission['assignment_title']); ?></p>
                        <p><strong>Total Marks:</strong> <?php echo $submission['total_marks']; ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Assignment Description -->
            <div class="card">
                <h2>Assignment Description</h2>
                <p><?php echo nl2br(htmlspecialchars($submission['assignment_description'])); ?></p>
            </div>
            
            <!-- Student Submission -->
            <div class="card">
                <h2>Student's Submission</h2>
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 5px; margin-bottom: 1rem;">
                    <p style="white-space: pre-wrap;"><?php echo htmlspecialchars($submission['submission_text']); ?></p>
                </div>
                
                <?php if($submission['file_path']): ?>
                    <p><strong>Attached File:</strong> 
                        <a href="../<?php echo htmlspecialchars($submission['file_path']); ?>" target="_blank" class="btn btn-primary" style="padding: 0.4rem 1rem;">
                            <i class='bx bxs-download' style="color:#4CAF50;"></i> Download Submission File
                        </a>
                    </p>
                <?php endif; ?>
            </div>
            
            <!-- Grading Form -->
            <div class="card">
                <h2><?php echo $submission['marks_obtained'] !== null ? 'Update Grade' : 'Assign Grade'; ?></h2>
                <?php echo $message; ?>
                
                <?php if($submission['graded_at']): ?>
                    <div class="alert alert-info">
                        Previously graded on: <?php echo date('F j, Y g:i A', strtotime($submission['graded_at'])); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="marks_obtained">Marks Obtained (out of <?php echo $submission['total_marks']; ?>) *</label>
                        <input type="number" id="marks_obtained" name="marks_obtained" class="form-control" 
                               min="0" max="<?php echo $submission['total_marks']; ?>" 
                               value="<?php echo $submission['marks_obtained'] ?? ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="feedback">Feedback for Student</label>
                        <textarea id="feedback" name="feedback" class="form-control" rows="6" placeholder="Provide constructive feedback..."><?php echo htmlspecialchars($submission['feedback'] ?? ''); ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <?php echo $submission['marks_obtained'] !== null ? 'Update Grade' : 'Submit Grade'; ?>
                    </button>
                    <a href="grades.php" class="btn" style="margin-left: 1rem;">Back to Submissions</a>
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