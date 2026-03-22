<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses - University LMS</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
           <a href="index.php" class="logo" style="text-decoration:none; color:white;">
            <div style="display:flex; align-items:center; gap:10px;">
                <!-- Main Logo -->
                <img src="uploads/Logo.png" alt="Logo" style="height:50px;">

                <!-- New logo instead of NITE text -->
                <img src="uploads/new-logo.png" alt="NITE Logo" style="height:40px; width:120px;">
            </div>
        </a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="courses.php">Courses</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php if(isLoggedIn()): ?>
                    <li><a href="<?php echo isAdmin() ? 'admin/dashboard.php' : 'student/dashboard.php'; ?>">Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <h1 style="color: #667eea; text-align: center; margin-bottom: 2rem;">Available Courses</h1>
            <p style="text-align: center; margin-bottom: 2rem;">Explore our diverse range of academic programs and courses</p>
            
            <?php
            $query = "SELECT * FROM courses WHERE status = 'active' ORDER BY course_code";
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
                            </div>
                            <?php if(isLoggedIn() && isStudent()): ?>
                                <?php
                                $student_id = $_SESSION['user_id'];
                                $course_id = $course['id'];
                                $enrolled_query = "SELECT * FROM enrollments WHERE student_id = $student_id AND course_id = $course_id AND status = 'active'";
                                $enrolled_result = mysqli_query($conn, $enrolled_query);
                                $is_enrolled = mysqli_num_rows($enrolled_result) > 0;
                                ?>
                                <div style="margin-top: 1rem;">
                                    <?php if($is_enrolled): ?>
                                        <button class="btn btn-success" disabled>✓ Enrolled</button>
                                    <?php else: ?>
                                        <a href="student/enroll.php?course_id=<?php echo $course['id']; ?>" class="btn btn-primary">Enroll Now</a>
                                    <?php endif; ?>
                                </div>
                            <?php elseif(!isLoggedIn()): ?>
                                <p style="margin-top: 1rem; color: #666;"><em>Login to enroll in this course</em></p>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <p>No courses are currently available. Please check back later.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>
</body>
</html>