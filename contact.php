<?php 
include 'config.php';

$message = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $subject = sanitize($_POST['subject']);
    $msg = sanitize($_POST['message']);
    
    // In a real application, you would send email or save to database
    // For now, we'll just show a success message
    $message = '<div class="alert alert-success">Thank you for contacting us! We will get back to you soon.</div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - University LMS</title>
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
        <div style="max-width: 800px; margin: 0 auto;">
            <div class="card">
                <h1 style="color: #667eea; text-align: center; margin-bottom: 2rem;">Contact Us</h1>
                
                <?php echo $message; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" class="form-control" rows="6" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>

            <div class="card">
                <h2 style="color: #667eea;">Contact Information</h2>
                <div class="cards-grid">
                    <div>
                        <h3><i class='bx bx-map' style="color:green;"></i>&nbsp; Address</h3>
                        <p>123 University Avenue<br>
                        Colombo 00700<br>
                        Sri Lanka</p>
                    </div>
                    <div>
                        <h3><i class='bx bx-phone-call' style="color:red;"></i>&nbsp; Phone</h3>
                        <p>Main: +94 11 234 5678<br>
                        Admissions: +94 11 234 5679<br>
                        Support: +94 11 234 5680</p>
                    </div>
                    <div>
                        <h3><i class='bx bx-envelope' style="color:#3b82f6;"></i>&nbsp; Email</h3>
                        <p>Info: info@university.edu<br>
                        Admissions: admissions@university.edu<br>
                        Support: support@university.edu</p>
                    </div>
                    <div>
                        <h3><i class='bx bx-time-five' style="color:#667eea;"></i>&nbsp; Office Hours</h3>
                        <p>Monday - Friday: 8:00 AM - 5:00 PM<br>
                        Saturday: 9:00 AM - 1:00 PM<br>
                        Sunday: Closed</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>
</body>
</html>