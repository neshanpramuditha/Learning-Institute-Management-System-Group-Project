<?php
include '../config.php';

if(!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$message = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize($_POST['title']);
    $content = sanitize($_POST['content']);
    $target_audience = sanitize($_POST['target_audience']);
    $course_id = $target_audience == 'specific_course' ? (int)$_POST['course_id'] : null;
    $posted_by = $_SESSION['user_id'];
    
    $course_clause = $course_id ? ", $course_id" : ", NULL";
    
    $insert_query = "INSERT INTO announcements (title, content, posted_by, target_audience, course_id) 
                    VALUES ('$title', '$content', $posted_by, '$target_audience' $course_clause)";
    
    if(mysqli_query($conn, $insert_query)) {
        $message = '<div class="alert alert-success">Announcement posted successfully!</div>';
    } else {
        $message = '<div class="alert alert-danger">Failed to post announcement. Please try again.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - University LMS</title>
    <link rel="stylesheet" href="../style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script>
        function toggleCourseSelect() {
            const audience = document.getElementById('target_audience').value;
            const courseSelect = document.getElementById('course_select_div');
            courseSelect.style.display = audience === 'specific_course' ? 'block' : 'none';
        }
    </script>
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
                <li><a href="grades.php"><i class='bx bxs-bar-chart-alt-2' style="color:#FFC107;"></i>&nbsp; Grades</a></li>
                <li><a href="announcements.php" class="active"><i class='bx bxs-megaphone'></i>&nbsp; Announcements</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h1 style="color: #667eea; margin-bottom: 2rem;">Manage Announcements</h1>
            
            <!-- Create Announcement Form -->
            <div class="card">
                <h2>Post New Announcement</h2>
                <?php echo $message; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="title">Title *</label>
                        <input type="text" id="title" name="title" class="form-control" required placeholder="e.g., Important: Midterm Schedule Update">
                    </div>
                    
                    <div class="form-group">
                        <label for="content">Content *</label>
                        <textarea id="content" name="content" class="form-control" rows="6" required placeholder="Write your announcement here..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="target_audience">Target Audience *</label>
                        <select id="target_audience" name="target_audience" class="form-control" required onchange="toggleCourseSelect()">
                            <option value="all">Everyone (All Users)</option>
                            <option value="students">Students Only</option>
                            <option value="specific_course">Specific Course</option>
                        </select>
                    </div>
                    
                    <div class="form-group" id="course_select_div" style="display: none;">
                        <label for="course_id">Select Course</label>
                        <select id="course_id" name="course_id" class="form-control">
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
                    
                    <button type="submit" class="btn btn-primary">Post Announcement</button>
                </form>
            </div>
            
            <!-- Recent Announcements -->
            <div class="card">
                <h2>Recent Announcements</h2>
                <?php
                $announcements_query = "SELECT a.*, u.full_name as posted_by_name, c.course_code
                                       FROM announcements a
                                       INNER JOIN users u ON a.posted_by = u.id
                                       LEFT JOIN courses c ON a.course_id = c.id
                                       ORDER BY a.created_at DESC";
                $announcements_result = mysqli_query($conn, $announcements_query);
                
                if(mysqli_num_rows($announcements_result) > 0):
                    while($announcement = mysqli_fetch_assoc($announcements_result)):
                ?>
                    <div style="padding: 1.5rem; border-left: 4px solid #667eea; margin-bottom: 1.5rem; background: #f8f9fa;">
                        <h3 style="color: #667eea; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($announcement['title']); ?></h3>
                        <p style="margin-bottom: 1rem;"><?php echo nl2br(htmlspecialchars($announcement['content'])); ?></p>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid #ddd;">
                            <div>
                                <small style="color: #666;">
                                    Posted by: <strong><?php echo htmlspecialchars($announcement['posted_by_name']); ?></strong> | 
                                    Date: <?php echo date('F j, Y g:i A', strtotime($announcement['created_at'])); ?> | 
                                    Target: <strong>
                                        <?php 
                                        if($announcement['target_audience'] == 'all') echo 'Everyone';
                                        elseif($announcement['target_audience'] == 'students') echo 'All Students';
                                        else echo 'Course: ' . $announcement['course_code'];
                                        ?>
                                    </strong>
                                </small>
                            </div>
                        </div>
                    </div>
                <?php 
                    endwhile;
                else:
                ?>
                    <p>No announcements posted yet.</p>
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