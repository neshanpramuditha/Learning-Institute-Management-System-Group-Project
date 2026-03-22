<?php
include '../config.php';

if(!isLoggedIn() || !isStudent()) {
    redirect('../login.php');
}

if(!isset($_GET['id'])) {
    redirect('assignments.php');
}

$student_id = $_SESSION['user_id'];
$assignment_id = (int)$_GET['id'];

// Get assignment details
$query = "SELECT a.*, c.course_name, c.course_code 
         FROM assignments a
         INNER JOIN courses c ON a.course_id = c.id
         INNER JOIN enrollments e ON c.id = e.course_id
         WHERE a.id = $assignment_id AND e.student_id = $student_id AND e.status = 'active'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0) {
    redirect('assignments.php');
}

$assignment = mysqli_fetch_assoc($result);

// Check if already submitted
$check_query = "SELECT * FROM submissions WHERE assignment_id = $assignment_id AND student_id = $student_id";
$check_result = mysqli_query($conn, $check_query);
$already_submitted = mysqli_num_rows($check_result) > 0;

$message = '';
if($_SERVER['REQUEST_METHOD'] == 'POST' && !$already_submitted) {
    $submission_text = sanitize($_POST['submission_text']);
    $file_path = '';
    
    // Handle file upload
    if(isset($_FILES['submission_file']) && $_FILES['submission_file']['error'] == 0) {
        $upload_dir = '../uploads/';
        
        // Create uploads directory if it doesn't exist
        if(!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_name = time() . '_' . basename($_FILES['submission_file']['name']);
        $target_file = $upload_dir . $file_name;
        
        if(move_uploaded_file($_FILES['submission_file']['tmp_name'], $target_file)) {
            $file_path = 'uploads/' . $file_name;
        }
    }
    
    // Insert submission
    $insert_query = "INSERT INTO submissions (assignment_id, student_id, submission_text, file_path) 
                    VALUES ($assignment_id, $student_id, '$submission_text', '$file_path')";
    
    if(mysqli_query($conn, $insert_query)) {
        $_SESSION['message'] = 'Assignment submitted successfully!';
        $_SESSION['message_type'] = 'success';
        redirect('assignments.php');
    } else {
        $message = '<div class="alert alert-danger">Failed to submit assignment. Please try again.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Assignment - University LMS</title>
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
                <li><a href="assignments.php" class="active"><i class='bx bxs-edit'></i>&nbsp; Assignments</a></li>
                <li><a href="grades.php"><i class='bx bxs-bar-chart-alt-2' style="color:#FFC107;"></i>&nbsp; Grades</a></li>
                <li><a href="schedule.php"><i class='bx bxs-calendar' style="color:#DC3545;"></i>&nbsp; Schedule</a></li>
                <li><a href="resources.php"><i class='bx bxs-book-content' style="color:#20C997;"></i>&nbsp; Resources</a></li>
                <li><a href="profile.php"><i class='bx bxs-user'style="color:#6610F2;"></i>&nbsp; Profile</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h1 style="color: #667eea; margin-bottom: 2rem;">Submit Assignment</h1>
            
            <div class="card">
                <h2>Assignment Details</h2>
                <p><strong>Course:</strong> <?php echo htmlspecialchars($assignment['course_code']) . ' - ' . htmlspecialchars($assignment['course_name']); ?></p>
                <p><strong>Assignment:</strong> <?php echo htmlspecialchars($assignment['title']); ?></p>
                <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($assignment['description'])); ?></p>
                <p><strong>Due Date:</strong> <?php echo date('F j, Y g:i A', strtotime($assignment['due_date'])); ?></p>
                <p><strong>Total Marks:</strong> <?php echo $assignment['total_marks']; ?></p>
                
                <?php
                $due_date = strtotime($assignment['due_date']);
                $now = time();
                if($due_date < $now):
                ?>
                    <div class="alert alert-danger">
                        ⚠ This assignment is overdue! Late submissions may receive reduced marks.
                    </div>
                <?php endif; ?>
            </div>

            <div class="card">
                <?php if($already_submitted): ?>
                    <div class="alert alert-info">
                        You have already submitted this assignment. Check the Assignments page for submission status.
                    </div>
                    <a href="assignments.php" class="btn btn-primary">Back to Assignments</a>
                <?php else: ?>
                    <h2>Submit Your Work</h2>
                    <?php echo $message; ?>
                    
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="submission_text">Written Response</label>
                            <textarea id="submission_text" name="submission_text" class="form-control" rows="8" placeholder="Type your response here..." required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="submission_file">Upload File (Optional)</label>
                            <input type="file" id="submission_file" name="submission_file" class="form-control">
                            <small style="color: #666;">Accepted formats: PDF, DOC, DOCX, ZIP (Max 5MB)</small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Submit Assignment</button>
                        <a href="assignments.php" class="btn" style="margin-left: 1rem;">Cancel</a>
                    </form>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <?php 
$root_path = '../';
include '../footer.php'; 
?>
</body>
</html>