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
    <title>Manage Courses - University LMS</title>
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
                <li><a href="students.php"><i class='bx bx-group' style="color:#2ecc71;"></i>&nbsp;  Students</a></li>
                <li><a href="courses.php" class="active"><i class='bx bxs-book-reader'></i>&nbsp; Courses</a></li>
                <li><a href="assignments.php"><i class='bx bxs-edit' style="color:#28A745;"></i>&nbsp; Assignments</a></li>
                <li><a href="grades.php"><i class='bx bxs-bar-chart-alt-2' style="color:#FFC107;"></i>&nbsp; Grades</a></li>
                <li><a href="announcements.php"><i class='bx bxs-megaphone' style="color:#6F42C1;"></i>&nbsp; Announcements</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h1 style="color: #667eea;">Manage Courses</h1>
                <a href="add-course.php" class="btn btn-primary" style="margin-right:32px;"><i class='bx bx-plus'></i>&nbsp;  Add New Course</a>
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
                <h2>All Courses</h2>
                <?php
                $query = "SELECT c.*, 
                         (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id AND status = 'active') as enrollment_count,
                         (SELECT COUNT(*) FROM assignments WHERE course_id = c.id) as assignment_count
                         FROM courses c ORDER BY c.course_code ASC";
                $result = mysqli_query($conn, $query);
                
                if(mysqli_num_rows($result) > 0):
                ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>Instructor</th>
                                <th>Credits</th>
                                <th>Semester</th>
                                <th>Students</th>
                                <th>Assignments</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($course = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($course['course_code']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                                    <td><?php echo htmlspecialchars($course['instructor_name']); ?></td>
                                    <td><?php echo $course['credits']; ?></td>
                                    <td><?php echo htmlspecialchars($course['semester']); ?></td>
                                    <td><?php echo $course['enrollment_count']; ?></td>
                                    <td><?php echo $course['assignment_count']; ?></td>
                                    <td>
                                        <?php if($course['status'] == 'active'): ?>
                                            <span style="color: #388e3c; font-weight: bold;">&nbsp;&nbsp;&nbsp;✓ Active</span>
                                        <?php else: ?>
                                            <span style="color: #d32f2f; font-weight: bold;">&nbsp;&nbsp;&nbsp;✗ Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="edit-course.php?id=<?php echo $course['id']; ?>" class="btn btn-primary" style="padding: 0.1rem 0.8rem; font-size: 0.9rem; margin-bottom:8px"><i class='bx bx-pencil' style="color:white;"></i> Edit</a>
                                        <a href="delete-course.php?id=<?php echo $course['id']; ?>" class="btn btn-danger" style="padding: 0.1rem 0.8rem; font-size: 0.9rem;" onclick="return confirm('Are you sure? This will delete all related assignments and enrollments.');"><i class='bx bx-trash' style="color:white;"></i> Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info">
                        No courses found. Click "Add New Course" to create your first course.
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