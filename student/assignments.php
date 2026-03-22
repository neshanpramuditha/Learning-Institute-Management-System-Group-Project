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
    <title>Assignments - University LMS</title>
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
            <h1 style="color: #667eea; margin-bottom: 2rem;">My Assignments</h1>
            
            <!-- Pending Assignments -->
            <div class="card">
                <h2><i class='bx bxs-edit' style="color:#28A745;"></i>&nbsp; Pending Assignments</h2>
                <?php
                $pending_query = "SELECT a.*, c.course_name, c.course_code 
                                 FROM assignments a
                                 INNER JOIN courses c ON a.course_id = c.id
                                 INNER JOIN enrollments e ON c.id = e.course_id
                                 WHERE e.student_id = $student_id 
                                 AND e.status = 'active'
                                 AND a.id NOT IN (SELECT assignment_id FROM submissions WHERE student_id = $student_id)
                                 ORDER BY a.due_date ASC";
                $pending_result = mysqli_query($conn, $pending_query);
                
                if(mysqli_num_rows($pending_result) > 0):
                ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Assignment</th>
                                <th>Due Date</th>
                                <th>Marks</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($assignment = mysqli_fetch_assoc($pending_result)): 
                                $due_date = strtotime($assignment['due_date']);
                                $now = time();
                                $is_overdue = $due_date < $now;
                            ?>
                                <tr style="<?php echo $is_overdue ? 'background: #ffebee;' : ''; ?>">
                                    <td><?php echo htmlspecialchars($assignment['course_code']); ?></td>
                                    <td><?php echo htmlspecialchars($assignment['title']); ?></td>
                                    <td><?php echo date('M j, Y g:i A', $due_date); ?></td>
                                    <td><?php echo $assignment['total_marks']; ?></td>
                                    <td>
                                        <?php if($is_overdue): ?>
                                            <span style="color: #d32f2f; font-weight: bold;">⚠ Overdue</span>
                                        <?php else: ?>
                                            <span style="color: #f57c00; font-weight: bold;">⏰ Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="submit-assignment.php?id=<?php echo $assignment['id']; ?>" class="btn btn-primary">Submit</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No pending assignments. Great work!</p>
                <?php endif; ?>
            </div>

            <!-- Submitted Assignments -->
            <div class="card">
                <h2><i class='bx  bx-check-square' style="color:#2ECC71;"></i>&nbsp; Submitted Assignments</h2>
                <?php
                $submitted_query = "SELECT a.*, c.course_name, c.course_code, s.submitted_at, s.status as submission_status, g.marks_obtained
                                   FROM assignments a
                                   INNER JOIN courses c ON a.course_id = c.id
                                   INNER JOIN submissions s ON a.id = s.assignment_id
                                   LEFT JOIN grades g ON s.id = g.submission_id
                                   WHERE s.student_id = $student_id
                                   ORDER BY s.submitted_at DESC";
                $submitted_result = mysqli_query($conn, $submitted_query);
                
                if(mysqli_num_rows($submitted_result) > 0):
                ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Assignment</th>
                                <th>Submitted On</th>
                                <th>Status</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($assignment = mysqli_fetch_assoc($submitted_result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($assignment['course_code']); ?></td>
                                    <td><?php echo htmlspecialchars($assignment['title']); ?></td>
                                    <td><?php echo date('M j, Y g:i A', strtotime($assignment['submitted_at'])); ?></td>
                                    <td>
                                        <?php if($assignment['submission_status'] == 'graded'): ?>
                                            <span style="color: #388e3c; font-weight: bold;">✓ Graded</span>
                                        <?php else: ?>
                                            <span style="color: #1976d2; font-weight: bold;">⏳ Under Review</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($assignment['marks_obtained']): ?>
                                            <strong><?php echo $assignment['marks_obtained']; ?>/<?php echo $assignment['total_marks']; ?></strong>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No submitted assignments yet.</p>
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