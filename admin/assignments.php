<?php
include '../config.php';

if(!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Assignments - University LMS</title>
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
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h1 style="color: #667eea;">Manage Assignments</h1>
                <a href="add-assignment.php" class="btn btn-primary"><i class='bx bx-plus'></i>&nbsp; Create Assignment</a>
            </div>
            
            <?php if(isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
                    <?php 
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                    unset($_SESSION['message_type']);
                    ?>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <h2>All Assignments</h2>
                <?php
                $query = "SELECT a.*, c.course_code, c.course_name,
                         (SELECT COUNT(*) FROM submissions WHERE assignment_id = a.id) as submission_count,
                         (SELECT COUNT(*) FROM enrollments WHERE course_id = a.course_id AND status = 'active') as enrolled_count
                         FROM assignments a
                         INNER JOIN courses c ON a.course_id = c.id
                         ORDER BY a.due_date DESC";
                $result = mysqli_query($conn, $query);
                
                if(mysqli_num_rows($result) > 0):
                ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Assignment Title</th>
                                <th>Due Date</th>
                                <th>Total Marks</th>
                                <th>Submissions</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($assignment = mysqli_fetch_assoc($result)): 
                                $due_date = strtotime($assignment['due_date']);
                                $now = time();
                                $is_overdue = $due_date < $now;
                                $submission_rate = $assignment['enrolled_count'] > 0 ? 
                                    round(($assignment['submission_count'] / $assignment['enrolled_count']) * 100) : 0;
                            ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($assignment['course_code']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($assignment['title']); ?></td>
                                    <td style="<?php echo $is_overdue ? 'color: #d32f2f;' : ''; ?>">
                                        <?php echo date('M j, Y g:i A', $due_date); ?>
                                    </td>
                                    <td><?php echo $assignment['total_marks']; ?></td>
                                    <td>
                                        <?php echo $assignment['submission_count']; ?>/<?php echo $assignment['enrolled_count']; ?>
                                        <small style="color: #666;">(<?php echo $submission_rate; ?>%)</small>
                                    </td>
                                    <td>
                                        <?php if($is_overdue): ?>
                                            <span style="color: #d32f2f; font-weight: bold;">Closed</span>
                                        <?php else: ?>
                                            <span style="color: #388e3c; font-weight: bold;">Open</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="grades.php?assignment_id=<?php echo $assignment['id']; ?>" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.9rem;">View Submissions</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info">
                        No assignments found. Click "Create Assignment" to add your first assignment.
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