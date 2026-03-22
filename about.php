<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - University LMS</title>
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
            <h1 style="color: #667eea; text-align: center; margin-bottom: 2rem;">About Our University</h1>
            
            <h2>Our History</h2>
            <p>Founded in 1950, our university has been a beacon of academic excellence for over 70 years. We have consistently ranked among the top educational institutions, producing leaders, innovators, and change-makers who have made significant contributions to society.</p>
            
            <h2 style="margin-top: 2rem;">Our Mission</h2>
            <p>To provide world-class education that empowers students with knowledge, skills, and values necessary to succeed in a rapidly changing global environment. We are committed to fostering critical thinking, creativity, and lifelong learning.</p>
            
            <h2 style="margin-top: 2rem;">Our Vision</h2>
            <p>To be recognized globally as a leading institution of higher learning, distinguished by academic excellence, innovative research, and a commitment to serving our community and society at large.</p>
            
            <h2 style="margin-top: 2rem;">Core Values</h2>
            <ul style="line-height: 2; margin-left: 2rem;">
                <li><strong>Excellence:</strong> Pursuing the highest standards in all endeavors</li>
                <li><strong>Integrity:</strong> Acting with honesty, fairness, and transparency</li>
                <li><strong>Innovation:</strong> Encouraging creative thinking and new ideas</li>
                <li><strong>Diversity:</strong> Embracing and celebrating differences</li>
                <li><strong>Community:</strong> Building strong relationships and collaboration</li>
            </ul>
        </div>

        <div class="card">
            <h2 style="color: #667eea;">Leadership Team</h2>
            <div class="cards-grid">
                <div style="text-align: center;">
                    <h3>Dr. Sarah Johnson</h3>
                    <p><strong>President</strong></p>
                    <p>Leading the university with over 20 years of academic leadership experience.</p>
                </div>
                <div style="text-align: center;">
                    <h3>Prof. Michael Chen</h3>
                    <p><strong>Vice President Academic</strong></p>
                    <p>Overseeing academic programs and curriculum development.</p>
                </div>
                <div style="text-align: center;">
                    <h3>Dr. Emily Rodriguez</h3>
                    <p><strong>Dean of Students</strong></p>
                    <p>Supporting student welfare and campus life initiatives.</p>
                </div>
            </div>
        </div>

        <div class="card">
            <h2 style="color: #667eea;">By the Numbers</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>10,000+</h3>
                    <p>Students Enrolled</p>
                </div>
                <div class="stat-card">
                    <h3>500+</h3>
                    <p>Faculty Members</p>
                </div>
                <div class="stat-card">
                    <h3>100+</h3>
                    <p>Degree Programs</p>
                </div>
                <div class="stat-card">
                    <h3>50+</h3>
                    <p>Countries Represented</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>
</body>
</html>