<?php
include '../config.php';

if(!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

// Get filter
$assignment_filter = isset($_GET['assignment_id']) ? (int)$_GET['assignment_id'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Submissions - University LMS</title>
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
                <li><a href="announcements.php"><i class='bx bxs-megaphone' style="color:#6F42C1;"></i>&nbsp; Announcements</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h1 style="color: #667eea; margin-bottom: 2rem;">Grade Submissions</h1>
            
            <?php if(isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
                    <?php 
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                    unset($_SESSION['message_type']);
                    ?>
                </div>
            <?php endif; ?>
            
            <!-- Filter -->
            <div class="card">
                <h3>Filter by Assignment</h3>
                <form method="GET" action="">
                    <div style="display: flex; gap: 1rem; align-items: end;">
                        <div class="form-group" style="flex: 1; margin-bottom: 0;">
                            <select name="assignment_id" class="form-control">
                                <option value="0">All Assignments</option>
                                <?php
                                $assignments_query = "SELECT a.id, a.title, c.course_code 
                                                     FROM assignments a
                                                     INNER JOIN courses c ON a.course_id = c.id
                                                     ORDER BY a.due_date DESC";
                                $assignments_result = mysqli_query($conn, $assignments_query);
                                while($assignment = mysqli_fetch_assoc($assignments_result)):
                                ?>
                                    <option value="<?php echo $assignment['id']; ?>" <?php echo $assignment_filter == $assignment['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($assignment['course_code']) . ' - ' . htmlspecialchars($assignment['title']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
            
            <!-- Submissions List -->
            <div class="card">
                <h2>Submissions</h2>
                <?php
                $where_clause = $assignment_filter > 0 ? "AND s.assignment_id = $assignment_filter" : "";
                
                $query = "SELECT s.*, a.title as assignment_title, a.total_marks, 
                         c.course_code, u.full_name as student_name,
                         g.marks_obtained, g.feedback
                         FROM submissions s
                         INNER JOIN assignments a ON s.assignment_id = a.id
                         INNER JOIN courses c ON a.course_id = c.id
                         INNER JOIN users u ON s.student_id = u.id
                         LEFT JOIN grades g ON s.id = g.submission_id
                         WHERE 1=1 $where_clause
                         ORDER BY s.submitted_at DESC";
                $result = mysqli_query($conn, $query);
                
                if(mysqli_num_rows($result) > 0):
                ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Assignment</th>
                                <th>Submitted</th>
                                <th>Grade</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($submission = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($submission['student_name']); ?></td>
                                    <td><?php echo htmlspecialchars($submission['course_code']); ?></td>
                                    <td><?php echo htmlspecialchars($submission['assignment_title']); ?></td>
                                    <td><?php echo date('M j, Y g:i A', strtotime($submission['submitted_at'])); ?></td>
                                    <td>
                                        <?php if($submission['marks_obtained'] !== null): ?>
                                            <strong><?php echo $submission['marks_obtained']; ?>/<?php echo $submission['total_marks']; ?></strong>
                                        <?php else: ?>
                                            <span style="color: #666;">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($submission['status'] == 'graded'): ?>
                                            <span style="color: #388e3c; font-weight: bold;">✓ Graded</span>
                                        <?php else: ?>
                                            <span style="color: #f57c00; font-weight: bold;">⏳ Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="grade-submission.php?id=<?php echo $submission['id']; ?>" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.9rem;">
                                            <?php echo $submission['status'] == 'graded' ? 'View/Edit' : 'Grade'; ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info">
                        <?php if($assignment_filter > 0): ?>
                            No submissions found for the selected assignment.
                        <?php else: ?>
                            No submissions to grade yet.
                        <?php endif; ?>
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