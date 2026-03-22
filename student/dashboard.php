<?php
include '../config.php';

if(!isLoggedIn() || !isStudent()) {
    redirect('../login.php');
}

$student_id = $_SESSION['user_id'];

// Get statistics
$courses_query = "SELECT COUNT(*) as total FROM enrollments WHERE student_id = $student_id AND status = 'active'";
$courses_result = mysqli_query($conn, $courses_query);
$total_courses = mysqli_fetch_assoc($courses_result)['total'];

$assignments_query = "SELECT COUNT(DISTINCT a.id) as total FROM assignments a 
                      INNER JOIN enrollments e ON a.course_id = e.course_id 
                      WHERE e.student_id = $student_id AND e.status = 'active'";
$assignments_result = mysqli_query($conn, $assignments_query);
$total_assignments = mysqli_fetch_assoc($assignments_result)['total'];

$submitted_query = "SELECT COUNT(*) as total FROM submissions WHERE student_id = $student_id";
$submitted_result = mysqli_query($conn, $submitted_query);
$total_submitted = mysqli_fetch_assoc($submitted_result)['total'];

$graded_query = "SELECT COUNT(*) as total FROM submissions WHERE student_id = $student_id AND status = 'graded'";
$graded_result = mysqli_query($conn, $graded_query);
$total_graded = mysqli_fetch_assoc($graded_result)['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - University LMS</title>
    <link rel="stylesheet" href="../style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <!-- Navigation -->
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
        <!-- Sidebar -->
        <aside class="sidebar">
            <h3 style="margin-bottom: 1.5rem; color: #667eea;">Student Menu</h3>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php" class="active"><i class='bx bx-grid-alt'></i>&nbsp; Dashboard</a></li>
                <li><a href="my-courses.php"><i class='bx bxs-book-reader' style="color:#4A6CF7;"></i>&nbsp; My Courses</a></li>
                <li><a href="assignments.php"><i class='bx bxs-edit' style="color:#28A745;"></i>&nbsp; Assignments</a></li>
                <li><a href="grades.php"><i class='bx bxs-bar-chart-alt-2' style="color:#FFC107;"></i>&nbsp; Grades</a></li>
                <li><a href="schedule.php"><i class='bx bxs-calendar' style="color:#DC3545;"></i>&nbsp; Schedule</a></li>
                <li><a href="resources.php"><i class='bx bxs-book-content' style="color:#20C997;"></i>&nbsp; Resources</a></li>
                <li><a href="profile.php"><i class='bx bxs-user'style="color:#6610F2;"></i>&nbsp; Profile</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1 style="color: #667eea; margin-bottom: 2rem;">Welcome, <?php echo $_SESSION['full_name']; ?>!</h1>
            
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3><?php echo $total_courses; ?></h3>
                    <p>Enrolled Courses</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $total_assignments; ?></h3>
                    <p>Total Assignments</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $total_submitted; ?></h3>
                    <p>Submitted</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $total_graded; ?></h3>
                    <p>Graded</p>
                </div>
            </div>

            <!-- Recent Announcements -->
            <div class="card">
                <h2><i class='bx bxs-megaphone' style="color:#6F42C1;"></i>&nbsp; Recent Announcements</h2>
                <?php
                $announcements_query = "SELECT * FROM announcements 
                                       WHERE target_audience IN ('all', 'students') 
                                       ORDER BY created_at DESC LIMIT 5";
                $announcements_result = mysqli_query($conn, $announcements_query);
                
                if(mysqli_num_rows($announcements_result) > 0):
                    while($announcement = mysqli_fetch_assoc($announcements_result)):
                ?>
                    <div style="padding: 1rem; border-left: 4px solid #667eea; margin-bottom: 1rem; background: #f8f9fa;">
                        <h3 style="color: #667eea; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($announcement['title']); ?></h3>
                        <p><?php echo htmlspecialchars($announcement['content']); ?></p>
                        <small style="color: #666;">Posted on: <?php echo date('F j, Y g:i A', strtotime($announcement['created_at'])); ?></small>
                    </div>
                <?php 
                    endwhile;
                else:
                ?>
                    <p>No announcements at this time.</p>
                <?php endif; ?>
            </div>

            <!-- Upcoming Assignments -->
            <div class="card">
                <h2><i class='bx bxs-edit' style="color:#28A745;"></i>&nbsp; Upcoming Assignments</h2>
                <?php
                $upcoming_query = "SELECT a.*, c.course_name, c.course_code 
                                  FROM assignments a
                                  INNER JOIN courses c ON a.course_id = c.id
                                  INNER JOIN enrollments e ON c.id = e.course_id
                                  WHERE e.student_id = $student_id 
                                  AND e.status = 'active'
                                  AND a.due_date > NOW()
                                  AND a.id NOT IN (SELECT assignment_id FROM submissions WHERE student_id = $student_id)
                                  ORDER BY a.due_date ASC LIMIT 5";
                $upcoming_result = mysqli_query($conn, $upcoming_query);
                
                if(mysqli_num_rows($upcoming_result) > 0):
                ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Assignment</th>
                                <th>Due Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($assignment = mysqli_fetch_assoc($upcoming_result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($assignment['course_code']); ?></td>
                                    <td><?php echo htmlspecialchars($assignment['title']); ?></td>
                                    <td><?php echo date('M j, Y g:i A', strtotime($assignment['due_date'])); ?></td>
                                    <td><a href="submit-assignment.php?id=<?php echo $assignment['id']; ?>" class="btn btn-primary">Submit</a></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No upcoming assignments. Great job staying on top of your work!</p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <?php 
$root_path = '../';
include '../footer.php'; 
?>
</body>
</html>