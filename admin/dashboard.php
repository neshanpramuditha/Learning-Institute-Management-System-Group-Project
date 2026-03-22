<?php
include '../config.php';

if(!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

// Get statistics
$students_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'student'"))['total'];
$courses_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM courses"))['total'];
$assignments_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM assignments"))['total'];
$submissions_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM submissions"))['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - University LMS</title>
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
                <li><a href="dashboard.php" class="active"><i class='bx bx-grid-alt'></i>&nbsp; Dashboard</a></li>
                <li><a href="students.php"><i class='bx bx-group' style="color:#2ecc71;"></i>&nbsp;  Students</a></li>
                <li><a href="courses.php"><i class='bx bxs-book-reader' style="color:#4A6CF7;"></i>&nbsp; Courses</a></li>
                <li><a href="assignments.php"><i class='bx bxs-edit' style="color:#28A745;"></i>&nbsp; Assignments</a></li>
                <li><a href="grades.php"><i class='bx bxs-bar-chart-alt-2' style="color:#FFC107;"></i>&nbsp; Grades</a></li>
                <li><a href="announcements.php"><i class='bx bxs-megaphone' style="color:#6F42C1;"></i>&nbsp; Announcements</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h1 style="color: #667eea; margin-bottom: 2rem;">Admin Dashboard</h1>
            
            <?php if(isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
                    <?php 
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                    unset($_SESSION['message_type']);
                    ?>
                </div>
            <?php endif; ?>
            
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3><?php echo $students_count; ?></h3>
                    <p>Total Students</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $courses_count; ?></h3>
                    <p>Total Courses</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $assignments_count; ?></h3>
                    <p>Total Assignments</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $submissions_count; ?></h3>
                    <p>Submissions</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <h2>Quick Actions</h2>
                   <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.5rem; margin-top: 0.5rem;">
                      <a href="add-student.php" class="btn btn-primary" 
                         style="height:60px; display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center; font-size:1rem; padding:0;">
                        <i class='bx bx-plus' style="font-size:1rem; margin:0;"></i>Add Student</a>

                        <a href="add-course.php" class="btn btn-primary"
                         style="height:60px; display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center; font-size:1rem; padding:0;">
                        <i class='bx bx-plus' style="font-size:1rem; margin:0;"></i>Add Course</a>

                        <a href="add-assignment.php" class="btn btn-primary"
                         style="height:60px; display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center; font-size:1rem; padding:0;">
                        <i class='bx bx-plus' style="font-size:1rem; margin:0;"></i>Add Assignment</a>

                        <a href="announcements.php" class="btn btn-primary"
                         style="height:60px; display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center; font-size:1rem; padding:0;">
                        <i class='bx bxs-megaphone' style="font-size:1rem; margin:0;"></i>Post Announcement</a>
                    </div>
            </div>

            <!-- Recent Students -->
            <div class="card">
                <h2>Recent Students</h2>
                <?php
                $recent_students = mysqli_query($conn, "SELECT * FROM users WHERE role = 'student' ORDER BY created_at DESC LIMIT 5");
                if(mysqli_num_rows($recent_students) > 0):
                ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($student = mysqli_fetch_assoc($recent_students)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                                    <td><?php echo htmlspecialchars($student['username']); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($student['created_at'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No students registered yet.</p>
                <?php endif; ?>
            </div>

            <!-- Pending Grading -->
            <div class="card">
                <h2>Pending Submissions</h2>
                <?php
                $pending = mysqli_query($conn, "SELECT s.*, a.title as assignment_title, c.course_code, u.full_name as student_name
                                               FROM submissions s
                                               INNER JOIN assignments a ON s.assignment_id = a.id
                                               INNER JOIN courses c ON a.course_id = c.id
                                               INNER JOIN users u ON s.student_id = u.id
                                               WHERE s.status = 'submitted'
                                               ORDER BY s.submitted_at DESC LIMIT 10");
                if(mysqli_num_rows($pending) > 0):
                ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Assignment</th>
                                <th>Submitted</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($submission = mysqli_fetch_assoc($pending)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($submission['student_name']); ?></td>
                                    <td><?php echo htmlspecialchars($submission['course_code']); ?></td>
                                    <td><?php echo htmlspecialchars($submission['assignment_title']); ?></td>
                                    <td><?php echo date('M j, Y g:i A', strtotime($submission['submitted_at'])); ?></td>
                                    <td><a href="grade-submission.php?id=<?php echo $submission['id']; ?>" class="btn btn-primary">Grade</a></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No pending submissions to grade.</p>
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