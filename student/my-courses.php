<?php
include '../config.php';

if(!isLoggedIn() || !isStudent()) {
    redirect('../login.php');
}

$student_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses - University LMS</title>
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
                <li><a href="my-courses.php" class="active"><i class='bx bxs-book-reader'></i>&nbsp; My Courses</a></li>
                <li><a href="assignments.php"><i class='bx bxs-edit' style="color:#28A745;"></i>&nbsp; Assignments</a></li>
                <li><a href="grades.php"><i class='bx bxs-bar-chart-alt-2' style="color:#FFC107;"></i>&nbsp; Grades</a></li>
                <li><a href="schedule.php"><i class='bx bxs-calendar' style="color:#DC3545;"></i>&nbsp; Schedule</a></li>
                <li><a href="resources.php"><i class='bx bxs-book-content' style="color:#20C997;"></i>&nbsp; Resources</a></li>
                <li><a href="profile.php"><i class='bx bxs-user'style="color:#6610F2;"></i>&nbsp; Profile</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h1 style="color: #667eea; margin-bottom: 2rem;">My Courses</h1>
            
            <?php
            $query = "SELECT c.*, e.enrollment_date, e.status as enrollment_status
                     FROM courses c
                     INNER JOIN enrollments e ON c.id = e.course_id
                     WHERE e.student_id = $student_id AND e.status = 'active'
                     ORDER BY c.course_code";
            $result = mysqli_query($conn, $query);
            
            if(mysqli_num_rows($result) > 0):
            ?>
                <div class="cards-grid">
                    <?php while($course = mysqli_fetch_assoc($result)): ?>
                        <div class="card" style="border-left: 4px solid #667eea;">
                            <h3 style="color: #667eea;"><?php echo htmlspecialchars($course['course_code']); ?></h3>
                            <h4><?php echo htmlspecialchars($course['course_name']); ?></h4>
                            <p style="margin-top: 1rem;"><?php echo htmlspecialchars($course['description']); ?></p>
                            
                            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #ddd;">
                                <p><strong>Instructor:</strong> <?php echo htmlspecialchars($course['instructor_name']); ?></p>
                                <p><strong>Credits:</strong> <?php echo $course['credits']; ?></p>
                                <p><strong>Semester:</strong> <?php echo htmlspecialchars($course['semester']); ?></p>
                                <p><strong>Enrolled:</strong> <?php echo date('M j, Y', strtotime($course['enrollment_date'])); ?></p>
                            </div>
                            
                            <div style="margin-top: 1rem;">
                                <a href="course-details.php?id=<?php echo $course['id']; ?>" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="alert alert-info">
                        <p>You are not enrolled in any courses yet.</p>
                        <a href="../courses.php" class="btn btn-primary" style="margin-top: 1rem;">Browse Available Courses</a>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <?php 
$root_path = '../';
include '../footer.php'; 
?>
</body>
</html>