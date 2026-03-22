<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University LMS - Home</title>
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
                    <li><a href="register.php">Register</a></li>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

<!-- Hero Section -->
<section class="hero">
    <video autoplay muted loop>
            <source src="uploads/bgv1.mp4" type="video/mp4"> 
            Your browser does not support the video tag.
        </video>
      <h1>Welcome to <br>National Institute of Tech & Engineering</h1>
        <p><i>Empowering education through innovative technology</i></p>
        <div style="margin-top: 2rem;">
            <?php if(!isLoggedIn()): ?>
                <a href="register.php" class="btn" style="margin-right: 1rem;">Register Now</a>
                <a href="login.php" class="btn" style="background: transparent; border: 2px solid white;">Login</a>
            <?php else: ?>
                <a href="<?php echo isAdmin() ? 'admin/dashboard.php' : 'student/dashboard.php'; ?>" class="btn">Go to Dashboard</a>
            <?php endif; ?>
        </div>
</section>


<!-- Icon Section -->
 <div>
    <section class="icon-section">
        <div class="icon-box">
            <img src="uploads/icon-3.webp" alt="Government">
            <h3>Government</h3>
            <p>Recognition</p>
        </div>

        <div class="icon-box">
            <img src="uploads/icon-1.webp" alt="Student">
            <h3>Student</h3>
            <p>Activities</p>
        </div>

        <div class="icon-box">
            <img src="uploads/icon-2.webp" alt="Library">
            <h3>Library</h3>
            <p>Facilities</p>
        </div>

        <div class="icon-box">
            <img src="uploads/icon-4.webp" alt="Fascinating">
            <h3>Fascinating</h3>
            <p>Environment</p>
        </div>
    </section>
</div>
<!-- Icon Section end -->


   <!-- Features Section -->
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 2rem; color: #667eea;">Our Features</h2>
        <div class="cards-grid">
            <div class="card">
                <h3><i class='bx bxs-book-reader' style="color:#4A6CF7;"></i>&nbsp; Course Management</h3>
                <p>Access and manage all your courses in one place. View course materials, assignments, and schedules easily.</p>
            </div>
            <div class="card">
                <h3><i class='bx bxs-edit' style="color:#28A745;"></i>&nbsp; Assignment Submission</h3>
                <p>Submit assignments online and track your submissions. Get instant feedback from instructors.</p>
            </div>
            <div class="card">
                <h3><i class='bx bxs-bar-chart-alt-2' style="color:#FFC107;"></i>&nbsp; Grade Tracking</h3>
                <p>Monitor your academic progress with real-time grade updates and performance analytics.</p>
            </div>
            <div class="card">
                <h3><i class='bx bxs-calendar' style="color:#DC3545;"></i>&nbsp; Class Schedule</h3>
                <p>Never miss a class with our integrated scheduling system. Get reminders and updates.</p>
            </div>
            <div class="card">
                <h3><i class='bx bxs-megaphone' style="color:#6F42C1;"></i>&nbsp; Announcements</h3>
                <p>Stay informed with the latest university announcements and important updates.</p>
            </div>
            <div class="card">
                <h3><i class='bx bxs-book-content' style="color:#20C997;"></i>&nbsp;  Resource Library</h3>
                <p>Access a vast collection of study materials, lecture notes, and educational resources.</p>
            </div>
        </div>

        <!-- Recent Announcements -->
        <div class="card" style="margin-top: 3rem;">
            <h2><i class='bx bxs-megaphone' style="color:#6F42C1;"></i>&nbsp; Recent Announcements</h2>
            <?php
            $query = "SELECT * FROM announcements WHERE target_audience = 'all' ORDER BY created_at DESC LIMIT 3";
            $result = mysqli_query($conn, $query);
            
            if(mysqli_num_rows($result) > 0):
                while($announcement = mysqli_fetch_assoc($result)):
            ?>
                <div style="padding: 1rem; border-left: 4px solid #667eea; margin-bottom: 1rem; background: #f8f9fa;">
                    <h3 style="color: #667eea; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($announcement['title']); ?></h3>
                    <p><?php echo htmlspecialchars($announcement['content']); ?></p>
                    <small style="color: #666;">Posted on: <?php echo date('F j, Y', strtotime($announcement['created_at'])); ?></small>
                </div>
            <?php 
                endwhile;
            else:
            ?>
                <p>No announcements at this time.</p>
            <?php endif; ?>
        </div>

 <!-- Logo Section -->       
<div>         
    <section class="down-logo">
        <img src="uploads/logo1.webp" alt="Logo 1">
        <img src="uploads/logo2.webp" alt="Logo 2">
        <img src="uploads/logo3.webp" alt="Logo 3">
        <img src="uploads/logo4.webp" alt="Logo 4">
        <img src="uploads/logo5.webp" alt="Logo 5">
    </section>
</div>
<!-- Logo Section end --> 
        <!-- Call to Action -->
        <?php if(!isLoggedIn()): ?>
        <div class="card" style="margin-top: 3rem; text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h2>Ready to Get Started?</h2>
            <p style="font-size: 1.1rem; margin: 1.5rem 0;">Join thousands of students already learning on our platform</p>
            <a href="register.php" class="btn" style="background: white; color: #667eea; margin-right: 1rem;">Create Account</a>
            <a href="courses.php" class="btn" style="background: transparent; border: 2px solid white; color: white;">Browse Courses</a>
        </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>
</body>
</html>