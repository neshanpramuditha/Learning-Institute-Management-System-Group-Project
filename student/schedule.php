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
    <title>My Schedule - University LMS</title>
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
                <li><a href="grades.php"><i class='bx bxs-bar-chart-alt-2'></i>&nbsp; Grades</a></li>
                <li><a href="schedule.php" class="active"><i class='bx bxs-calendar'></i>&nbsp; Schedule</a></li>
                <li><a href="resources.php"><i class='bx bxs-book-content' style="color:#20C997;"></i>&nbsp; Resources</a></li>
                <li><a href="profile.php"><i class='bx bxs-user'style="color:#6610F2;"></i>&nbsp; Profile</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h1 style="color: #667eea; margin-bottom: 2rem;">My Class Schedule</h1>
            
            <?php
            // Get all schedules for enrolled courses
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            
            $has_schedule = false;
            
            foreach($days as $day):
                $schedule_query = "SELECT s.*, c.course_code, c.course_name, c.instructor_name
                                  FROM schedules s
                                  INNER JOIN courses c ON s.course_id = c.id
                                  INNER JOIN enrollments e ON c.id = e.course_id
                                  WHERE e.student_id = $student_id 
                                  AND e.status = 'active'
                                  AND s.day_of_week = '$day'
                                  ORDER BY s.start_time";
                $schedule_result = mysqli_query($conn, $schedule_query);
                
                if(mysqli_num_rows($schedule_result) > 0):
                    $has_schedule = true;
            ?>
                <div class="card">
                    <h2 style="color: #667eea; margin-bottom: 1.5rem;"><?php echo $day; ?></h2>
                    <?php while($schedule = mysqli_fetch_assoc($schedule_result)): ?>
                        <div style="padding: 1.5rem; background: #f8f9fa; border-left: 4px solid #667eea; margin-bottom: 1rem; border-radius: 5px;">
                            <div style="display: grid; grid-template-columns: auto 1fr; gap: 2rem;">
                                <div style="min-width: 150px;">
                                    <p style="font-size: 1.2rem; font-weight: bold; color: #667eea;">
                                        <?php echo date('g:i A', strtotime($schedule['start_time'])) . ' - ' . date('g:i A', strtotime($schedule['end_time'])); ?>
                                    </p>
                                    <p style="color: #666; margin-top: 0.3rem;"><i class='bx bx-buildings' style="color:#34495E;"></i>&nbsp; Room: <?php echo htmlspecialchars($schedule['room_number']); ?></p>
                                </div>
                                <div>
                                    <h3 style="margin-bottom: 0.5rem;"><?php echo htmlspecialchars($schedule['course_code']); ?> - <?php echo htmlspecialchars($schedule['course_name']); ?></h3>
                                    <p style="color: #666;"><i class='bx bx-user-voice' style="color:#6C63FF;"></i>&nbsp; Instructor: <?php echo htmlspecialchars($schedule['instructor_name']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php 
                endif;
            endforeach;
            
            if(!$has_schedule):
            ?>
                <div class="card">
                    <div class="alert alert-info">
                        <h3><i class='bx bxs-calendar' style="color:#DC3545;"></i>&nbsp; No Class Schedule Available</h3>
                        <p>Your class schedule will appear here once the administrator sets up the timetable for your enrolled courses.</p>
                        <p style="margin-top: 1rem;">Meanwhile, you can:</p>
                        <ul style="margin-left: 2rem; margin-top: 0.5rem;">
                            <li>View your enrolled courses</li>
                            <li>Check pending assignments</li>
                            <li>Review course materials</li>
                        </ul>
                        <a href="my-courses.php" class="btn btn-primary" style="margin-top: 1rem;">View My Courses</a>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Weekly Summary -->
            <?php if($has_schedule): ?>
                <div class="card">
                    <h2><i class='bx  bx-file' style="color:#9C27B0;"></i>&nbsp; Weekly Summary</h2>
                    <div class="stats-grid">
                        <?php
                        $total_classes = 0;
                        foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $summary_day) {
                            $day_count_query = "SELECT COUNT(*) as total FROM schedules s
                                               INNER JOIN enrollments e ON s.course_id = e.course_id
                                               WHERE e.student_id = $student_id 
                                               AND e.status = 'active'
                                               AND s.day_of_week = '$summary_day'";
                            $day_count_result = mysqli_query($conn, $day_count_query);
                            $day_count = mysqli_fetch_assoc($day_count_result)['total'];
                            $total_classes += $day_count;
                            
                            if($day_count > 0):
                        ?>
                            <div class="stat-card">
                                <h3><?php echo $day_count; ?></h3>
                                <p><?php echo substr($summary_day, 0, 3); ?></p>
                            </div>
                        <?php 
                            endif;
                        } 
                        ?>
                        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <h3><?php echo $total_classes; ?></h3>
                            <p>Total Classes/Week</p>
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