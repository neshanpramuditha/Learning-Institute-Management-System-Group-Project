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
    <title>Grades - University LMS</title>
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
                <li><a href="assignments.php"><i class='bx bxs-edit' style="color:#28A745;"></i>&nbsp; Assignments</a></li>
                <li><a href="grades.php" class="active"><i class='bx bxs-bar-chart-alt-2'></i>&nbsp; Grades</a></li>
                <li><a href="schedule.php"><i class='bx bxs-calendar' style="color:#DC3545;"></i>&nbsp; Schedule</a></li>
                <li><a href="resources.php"><i class='bx bxs-book-content' style="color:#20C997;"></i>&nbsp; Resources</a></li>
                <li><a href="profile.php"><i class='bx bxs-user'style="color:#6610F2;"></i>&nbsp; Profile</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h1 style="color: #667eea; margin-bottom: 2rem;">My Grades</h1>
            
            <?php
            // Get grades by course
            $courses_query = "SELECT c.* FROM courses c
                            INNER JOIN enrollments e ON c.id = e.course_id
                            WHERE e.student_id = $student_id AND e.status = 'active'
                            ORDER BY c.course_code";
            $courses_result = mysqli_query($conn, $courses_query);
            
            if(mysqli_num_rows($courses_result) > 0):
                while($course = mysqli_fetch_assoc($courses_result)):
                    $course_id = $course['id'];
                    
                    // Get grades for this course
                    $grades_query = "SELECT a.title, a.total_marks, g.marks_obtained, g.feedback, g.graded_at
                                    FROM assignments a
                                    INNER JOIN submissions s ON a.id = s.assignment_id
                                    INNER JOIN grades g ON s.id = g.submission_id
                                    WHERE a.course_id = $course_id AND s.student_id = $student_id
                                    ORDER BY a.due_date DESC";
                    $grades_result = mysqli_query($conn, $grades_query);
                    
                    if(mysqli_num_rows($grades_result) > 0):
            ?>
                        <div class="card">
                            <h2><?php echo htmlspecialchars($course['course_code']) . ' - ' . htmlspecialchars($course['course_name']); ?></h2>
                            
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Assignment</th>
                                        <th>Score</th>
                                        <th>Percentage</th>
                                        <th>Feedback</th>
                                        <th>Graded On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total_marks = 0;
                                    $obtained_marks = 0;
                                    
                                    while($grade = mysqli_fetch_assoc($grades_result)): 
                                        $percentage = ($grade['marks_obtained'] / $grade['total_marks']) * 100;
                                        $total_marks += $grade['total_marks'];
                                        $obtained_marks += $grade['marks_obtained'];
                                        
                                        // Determine color based on percentage
                                        if($percentage >= 80) $color = '#388e3c';
                                        elseif($percentage >= 60) $color = '#f57c00';
                                        else $color = '#d32f2f';
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($grade['title']); ?></td>
                                            <td><strong><?php echo $grade['marks_obtained'] . '/' . $grade['total_marks']; ?></strong></td>
                                            <td><strong style="color: <?php echo $color; ?>;"><?php echo number_format($percentage, 1); ?>%</strong></td>
                                            <td><?php echo htmlspecialchars($grade['feedback'] ?? 'No feedback'); ?></td>
                                            <td><?php echo date('M j, Y', strtotime($grade['graded_at'])); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                                <tfoot>
                                    <tr style="background: #f8f9fa; font-weight: bold;">
                                        <td>Course Total</td>
                                        <td><?php echo $obtained_marks . '/' . $total_marks; ?></td>
                                        <td>
                                            <?php 
                                            $course_percentage = $total_marks > 0 ? ($obtained_marks / $total_marks) * 100 : 0;
                                            if($course_percentage >= 80) $color = '#388e3c';
                                            elseif($course_percentage >= 60) $color = '#f57c00';
                                            else $color = '#d32f2f';
                                            ?>
                                            <strong style="color: <?php echo $color; ?>;"><?php echo number_format($course_percentage, 1); ?>%</strong>
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
            <?php 
                    endif;
                endwhile;
            else:
            ?>
                <div class="card">
                    <div class="alert alert-info">
                        You are not enrolled in any courses yet. Enroll in courses to see your grades here.
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Calculate Overall GPA -->
            <?php
            $overall_query = "SELECT SUM(a.total_marks) as total, SUM(g.marks_obtained) as obtained
                             FROM grades g
                             INNER JOIN assignments a ON g.assignment_id = a.id
                             WHERE g.student_id = $student_id";
            $overall_result = mysqli_query($conn, $overall_query);
            $overall = mysqli_fetch_assoc($overall_result);
            
            if($overall['total'] > 0):
                $overall_percentage = ($overall['obtained'] / $overall['total']) * 100;
            ?>
                <div class="card">
                    <h2>Overall Performance</h2>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <h3><?php echo number_format($overall_percentage, 1); ?>%</h3>
                            <p>Overall Score</p>
                        </div>
                        <div class="stat-card">
                            <h3><?php echo $overall['obtained']; ?></h3>
                            <p>Marks Obtained</p>
                        </div>
                        <div class="stat-card">
                            <h3><?php echo $overall['total']; ?></h3>
                            <p>Total Marks</p>
                        </div>
                        <div class="stat-card">
                            <h3>
                                <?php 
                                if($overall_percentage >= 90) echo 'A';
                                elseif($overall_percentage >= 80) echo 'B';
                                elseif($overall_percentage >= 70) echo 'C';
                                elseif($overall_percentage >= 60) echo 'D';
                                else echo 'F';
                                ?>
                            </h3>
                            <p>Grade</p>
                        </div>
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