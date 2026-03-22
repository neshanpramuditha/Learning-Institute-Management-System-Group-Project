<?php
include '../config.php';

if(!isLoggedIn() || !isStudent()) {
    redirect('../login.php');
}

if(!isset($_GET['id'])) {
    redirect('my-courses.php');
}

$student_id = $_SESSION['user_id'];
$course_id = (int)$_GET['id'];

// Check if student is enrolled
$enrollment_check = "SELECT * FROM enrollments WHERE student_id = $student_id AND course_id = $course_id AND status = 'active'";
$enrollment_result = mysqli_query($conn, $enrollment_check);

if(mysqli_num_rows($enrollment_result) == 0) {
    redirect('my-courses.php');
}

// Get course details
$course_query = "SELECT * FROM courses WHERE id = $course_id";
$course_result = mysqli_query($conn, $course_query);
$course = mysqli_fetch_assoc($course_result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['course_name']); ?> - University LMS</title>
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
            <div style="margin-bottom: 1rem;">
                <a href="my-courses.php" style="color: #667eea; text-decoration: none;">← Back to My Courses</a>
            </div>
            
            <div class="card">
                <h1 style="color: #667eea; margin-bottom: 1rem;"><?php echo htmlspecialchars($course['course_code']); ?></h1>
                <h2><?php echo htmlspecialchars($course['course_name']); ?></h2>
                
                <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #ddd;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                        <div>
                            <p style="color: #666; margin-bottom: 0.3rem;">Instructor</p>
                            <p style="font-weight: 600;"><?php echo htmlspecialchars($course['instructor_name']); ?></p>
                        </div>
                        <div>
                            <p style="color: #666; margin-bottom: 0.3rem;">Credits</p>
                            <p style="font-weight: 600;"><?php echo $course['credits']; ?> Credits</p>
                        </div>
                        <div>
                            <p style="color: #666; margin-bottom: 0.3rem;">Semester</p>
                            <p style="font-weight: 600;"><?php echo htmlspecialchars($course['semester']); ?></p>
                        </div>
                    </div>
                </div>
                
                <div style="margin-top: 2rem;">
                    <h3 style="color: #667eea; margin-bottom: 1rem;">Course Description</h3>
                    <p style="line-height: 1.8;"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
                </div>
            </div>

            <!-- Course Materials -->
            <div class="card">
                <h2><i class='bx bxs-book-reader' style="color:#4A6CF7;"></i>&nbsp; Course Materials</h2>
                <?php
                $materials_query = "SELECT * FROM course_materials WHERE course_id = $course_id ORDER BY uploaded_at DESC";
                $materials_result = mysqli_query($conn, $materials_query);
                
                if(mysqli_num_rows($materials_result) > 0):
                ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Material Name</th>
                                <th>Type</th>
                                <th>Uploaded Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($material = mysqli_fetch_assoc($materials_result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($material['title']); ?></td>
                                    <td><?php echo strtoupper($material['file_type']); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($material['uploaded_at'])); ?></td>
                                    <td>
                                        <a href="../<?php echo htmlspecialchars($material['file_path']); ?>" 
                                           class="btn btn-primary" 
                                           style="padding: 0.4rem 0.8rem; font-size: 0.9rem;" 
                                           download>
                                            <i class='bx bxs-download' style="color:#4CAF50;"></i> Download
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info">
                        No course materials have been uploaded yet.
                    </div>
                <?php endif; ?>
            </div>

            <!-- Course Assignments -->
            <div class="card">
                <h2><i class='bx bxs-edit' style="color:#28A745;"></i>&nbsp; Assignments for this Course</h2>
                <?php
                $assignments_query = "SELECT a.*, 
                                     (SELECT COUNT(*) FROM submissions WHERE assignment_id = a.id AND student_id = $student_id) as is_submitted
                                     FROM assignments a
                                     WHERE a.course_id = $course_id
                                     ORDER BY a.due_date DESC";
                $assignments_result = mysqli_query($conn, $assignments_query);
                
                if(mysqli_num_rows($assignments_result) > 0):
                ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Assignment Title</th>
                                <th>Due Date</th>
                                <th>Marks</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($assignment = mysqli_fetch_assoc($assignments_result)): 
                                $due_date = strtotime($assignment['due_date']);
                                $now = time();
                                $is_overdue = $due_date < $now;
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($assignment['title']); ?></td>
                                    <td style="<?php echo $is_overdue ? 'color: #d32f2f;' : ''; ?>">
                                        <?php echo date('M j, Y g:i A', $due_date); ?>
                                    </td>
                                    <td><?php echo $assignment['total_marks']; ?></td>
                                    <td>
                                        <?php if($assignment['is_submitted'] > 0): ?>
                                            <span style="color: #388e3c; font-weight: bold;">✓ Submitted</span>
                                        <?php elseif($is_overdue): ?>
                                            <span style="color: #d32f2f; font-weight: bold;">⚠ Overdue</span>
                                        <?php else: ?>
                                            <span style="color: #f57c00; font-weight: bold;">⏰ Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($assignment['is_submitted'] == 0): ?>
                                            <a href="submit-assignment.php?id=<?php echo $assignment['id']; ?>" 
                                               class="btn btn-primary" 
                                               style="padding: 0.4rem 0.8rem; font-size: 0.9rem;">
                                                Submit
                                            </a>
                                        <?php else: ?>
                                            <span style="color: #388e3c;">Submitted</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info">
                        No assignments have been created for this course yet.
                    </div>
                <?php endif; ?>
            </div>

            <!-- Course Schedule -->
            <div class="card">
                <h2><i class='bx bxs-calendar' style="color:#DC3545;"></i>&nbsp; Class Schedule</h2>
                <?php
                $schedule_query = "SELECT * FROM schedules WHERE course_id = $course_id ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";
                $schedule_result = mysqli_query($conn, $schedule_query);
                
                if(mysqli_num_rows($schedule_result) > 0):
                ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Room</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($schedule = mysqli_fetch_assoc($schedule_result)): ?>
                                <tr>
                                    <td><strong><?php echo $schedule['day_of_week']; ?></strong></td>
                                    <td><?php echo date('g:i A', strtotime($schedule['start_time'])) . ' - ' . date('g:i A', strtotime($schedule['end_time'])); ?></td>
                                    <td><?php echo htmlspecialchars($schedule['room_number']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info">
                        Class schedule will be announced soon.
                    </div>
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