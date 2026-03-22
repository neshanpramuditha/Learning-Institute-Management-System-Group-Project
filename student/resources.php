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
    <title>Resources - University LMS</title>
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
                <li><a href="grades.php"><i class='bx bxs-bar-chart-alt-2' style="color:#FFC107;"></i>&nbsp; Grades</a></li>
                <li><a href="schedule.php"><i class='bx bxs-calendar' style="color:#DC3545;"></i>&nbsp; Schedule</a></li>
                <li><a href="resources.php" class="active"><i class='bx bxs-book-content' ></i>&nbsp; Resources</a></li>
                <li><a href="profile.php"><i class='bx bxs-user'style="color:#6610F2;"></i>&nbsp; Profile</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h1 style="color: #667eea; margin-bottom: 2rem;"><i class='bx bxs-book-content' style="color:#20C997;"></i>&nbsp; Study Resources</h1>
            
            <!-- Course Materials by Course -->
            <?php
            $courses_query = "SELECT c.* FROM courses c
                            INNER JOIN enrollments e ON c.id = e.course_id
                            WHERE e.student_id = $student_id AND e.status = 'active'
                            ORDER BY c.course_code";
            $courses_result = mysqli_query($conn, $courses_query);
            
            $has_materials = false;
            
            while($course = mysqli_fetch_assoc($courses_result)):
                $course_id = $course['id'];
                
                $materials_query = "SELECT * FROM course_materials WHERE course_id = $course_id ORDER BY uploaded_at DESC";
                $materials_result = mysqli_query($conn, $materials_query);
                
                if(mysqli_num_rows($materials_result) > 0):
                    $has_materials = true;
            ?>
                <div class="card">
                    <h2 style="border-left: 4px solid #667eea; padding-left: 1rem;">
                        <?php echo htmlspecialchars($course['course_code']); ?> - <?php echo htmlspecialchars($course['course_name']); ?>
                    </h2>
                    
                    <table class="table">
                        <thead>
                            <tr>
                                <th><i class='bx bxs-file-doc' style="color:#2ecc71;"></i>&nbsp; Resource Title</th>
                                <th><i class='bx bxs-folder' style="color:#3498db;"></i>&nbsp; Type</th>
                                <th><i class='bx bxs-calendar' style="color:#f1c40f;"></i>&nbsp; Upload Date</th>
                                <th><i class='bx bxs-bolt' style="color:#e74c3c;"></i>&nbsp; Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($material = mysqli_fetch_assoc($materials_result)): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($material['title']); ?></strong></td>
                                    <td>
                                        <span style="background: #667eea; color: white; padding: 0.3rem 0.8rem; border-radius: 3px; font-size: 0.85rem; text-transform: uppercase;">
                                            <?php echo htmlspecialchars($material['file_type']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M j, Y g:i A', strtotime($material['uploaded_at'])); ?></td>
                                    <td>
                                        <a href="../<?php echo htmlspecialchars($material['file_path']); ?>" 
                                           class="btn btn-primary" 
                                           style="padding: 0.4rem 0.8rem; font-size: 0.9rem;"
                                           download>
                                            <i class='bx bx-download' style="color:#3498db;"></i>&nbsp; Download
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php 
                endif;
            endwhile;
            
            if(!$has_materials):
            ?>
                <div class="card">
                    <div class="alert alert-info">
                        <h3><i class='bx bxs-book-content' style="color:#20C997;"></i>&nbsp; No Study Materials Available Yet</h3>
                        <p>Study materials and course resources will appear here once your instructors upload them. Check back regularly for new materials!</p>
                        <p style="margin-top: 1rem;">In the meantime:</p>
                        <ul style="margin-left: 2rem; margin-top: 0.5rem;">
                            <li>Review your course syllabi</li>
                            <li>Complete pending assignments</li>
                            <li>Visit the library resources below</li>
                        </ul>
                        <a href="my-courses.php" class="btn btn-primary" style="margin-top: 1rem;">View My Courses</a>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Quick Links -->
            <div class="card">
                <h2><i class='bx bx-globe' style="color:#1E88E5;"></i>&nbsp; Library & External Resources</h2>
                <div class="cards-grid">
                    <div style="padding: 1.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px;">
                        <h3 style="margin-bottom: 0.5rem;"><i class='bx bx-book'></i>&nbsp; University Library</h3>
                        <p style="margin-bottom: 1rem;">Access digital library, journals, and research papers</p>
                        <a href="https://scholar.google.com" target="_blank" class="btn" style="background: white; color: #667eea;">Visit Library</a>
                    </div>
                    
                    <div style="padding: 1.5rem; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 8px;">
                        <h3 style="margin-bottom: 0.5rem;"><i class='bx bx-video'></i>&nbsp; Online Tutorials</h3>
                        <p style="margin-bottom: 1rem;">Free learning resources and video tutorials</p>
                        <a href="https://www.youtube.com/education" target="_blank" class="btn" style="background: white; color: #f5576c;">Browse Tutorials</a>
                    </div>
                    
                    <div style="padding: 1.5rem; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border-radius: 8px;">
                        <h3 style="margin-bottom: 0.5rem;"><i class='bx bx-group'></i>&nbsp; Study Groups</h3>
                        <p style="margin-bottom: 1rem;">Connect with classmates for group study</p>
                        <a href="#" class="btn" style="background: white; color: #4facfe;">Join Groups</a>
                    </div>
                </div>
            </div>
            
            <!-- Resources Statistics -->
            <?php if($has_materials): ?>
                <div class="card">
                    <h2><i class='bx bxs-bar-chart-alt-2' style="color:#FFC107;"></i>&nbsp; Your Resources Summary</h2>
                    <div class="stats-grid">
                        <?php
                        $total_materials_query = "SELECT COUNT(*) as total FROM course_materials cm
                                                 INNER JOIN enrollments e ON cm.course_id = e.course_id
                                                 WHERE e.student_id = $student_id AND e.status = 'active'";
                        $total_materials = mysqli_fetch_assoc(mysqli_query($conn, $total_materials_query))['total'];
                        
                        $pdf_count_query = "SELECT COUNT(*) as total FROM course_materials cm
                                           INNER JOIN enrollments e ON cm.course_id = e.course_id
                                           WHERE e.student_id = $student_id AND e.status = 'active' 
                                           AND cm.file_type = 'pdf'";
                        $pdf_count = mysqli_fetch_assoc(mysqli_query($conn, $pdf_count_query))['total'];
                        
                        $doc_count_query = "SELECT COUNT(*) as total FROM course_materials cm
                                           INNER JOIN enrollments e ON cm.course_id = e.course_id
                                           WHERE e.student_id = $student_id AND e.status = 'active' 
                                           AND cm.file_type IN ('doc', 'docx')";
                        $doc_count = mysqli_fetch_assoc(mysqli_query($conn, $doc_count_query))['total'];
                        
                        $courses_with_materials_query = "SELECT COUNT(DISTINCT cm.course_id) as total FROM course_materials cm
                                                        INNER JOIN enrollments e ON cm.course_id = e.course_id
                                                        WHERE e.student_id = $student_id AND e.status = 'active'";
                        $courses_with_materials = mysqli_fetch_assoc(mysqli_query($conn, $courses_with_materials_query))['total'];
                        ?>
                        <div class="stat-card">
                            <h3><?php echo $total_materials; ?></h3>
                            <p>Total Resources</p>
                        </div>
                        <div class="stat-card">
                            <h3><?php echo $pdf_count; ?></h3>
                            <p>PDF Documents</p>
                        </div>
                        <div class="stat-card">
                            <h3><?php echo $doc_count; ?></h3>
                            <p>Word Documents</p>
                        </div>
                        <div class="stat-card">
                            <h3><?php echo $courses_with_materials; ?></h3>
                            <p>Courses with Materials</p>
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