<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - University LMS</title>
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

    <div class="container" style="max-width: 900px; margin: 3rem auto;">
        <div class="card">
            <h1 style="color: #667eea; margin-bottom: 1rem;">Privacy Policy</h1>
            <p style="color: #666; margin-bottom: 2rem;"><strong>Last Updated:</strong> <?php echo date('F j, Y'); ?></p>
            
            <div style="line-height: 1.8;">
                <h2 style="color: #667eea; margin-top: 2rem;">1. Introduction</h2>
                <p>Welcome to University LMS. We respect your privacy and are committed to protecting your personal data. This privacy policy will inform you about how we look after your personal data when you visit our platform and tell you about your privacy rights.</p>

                <h2 style="color: #667eea; margin-top: 2rem;">2. Information We Collect</h2>
                <p>We collect and process the following types of personal information:</p>
                <ul style="margin-left: 2rem;">
                    <li><strong>Account Information:</strong> Name, username, email address, password (encrypted), phone number, date of birth, and address</li>
                    <li><strong>Academic Information:</strong> Enrolled courses, assignment submissions, grades, attendance records</li>
                    <li><strong>Usage Data:</strong> Login times, pages visited, features used, IP address</li>
                    <li><strong>Files and Documents:</strong> Assignment submissions, course materials you upload</li>
                </ul>

                <h2 style="color: #667eea; margin-top: 2rem;">3. How We Use Your Information</h2>
                <p>We use your personal data for the following purposes:</p>
                <ul style="margin-left: 2rem;">
                    <li>To provide and manage your account</li>
                    <li>To deliver educational services and course content</li>
                    <li>To process and grade assignments</li>
                    <li>To communicate important announcements and updates</li>
                    <li>To improve our platform and user experience</li>
                    <li>To maintain security and prevent fraud</li>
                    <li>To comply with legal obligations</li>
                </ul>

                <h2 style="color: #667eea; margin-top: 2rem;">4. Data Security</h2>
                <p>We implement appropriate technical and organizational measures to protect your personal data:</p>
                <ul style="margin-left: 2rem;">
                    <li>Passwords are encrypted using industry-standard hashing algorithms</li>
                    <li>Secure database connections and access controls</li>
                    <li>Regular security updates and monitoring</li>
                    <li>Limited access to personal data by authorized personnel only</li>
                    <li>Secure file upload and storage systems</li>
                </ul>

                <h2 style="color: #667eea; margin-top: 2rem;">5. Data Sharing and Disclosure</h2>
                <p>We do not sell your personal information. We may share your data only in the following circumstances:</p>
                <ul style="margin-left: 2rem;">
                    <li><strong>With Instructors:</strong> Your academic information is shared with your course instructors for educational purposes</li>
                    <li><strong>With University Administration:</strong> For academic record-keeping and institutional reporting</li>
                    <li><strong>Legal Requirements:</strong> When required by law or to protect rights and safety</li>
                    <li><strong>Service Providers:</strong> With trusted third-party service providers who assist in operating our platform (under strict confidentiality agreements)</li>
                </ul>

                <h2 style="color: #667eea; margin-top: 2rem;">6. Your Rights</h2>
                <p>Under data protection laws, you have the following rights:</p>
                <ul style="margin-left: 2rem;">
                    <li><strong>Right to Access:</strong> Request a copy of your personal data</li>
                    <li><strong>Right to Rectification:</strong> Correct inaccurate or incomplete data</li>
                    <li><strong>Right to Erasure:</strong> Request deletion of your data (subject to legal requirements)</li>
                    <li><strong>Right to Restrict Processing:</strong> Limit how we use your data</li>
                    <li><strong>Right to Data Portability:</strong> Receive your data in a structured format</li>
                    <li><strong>Right to Object:</strong> Object to certain types of processing</li>
                </ul>

                <h2 style="color: #667eea; margin-top: 2rem;">7. Data Retention</h2>
                <p>We retain your personal data for as long as necessary to:</p>
                <ul style="margin-left: 2rem;">
                    <li>Provide our services to you</li>
                    <li>Comply with legal obligations (e.g., academic record retention requirements)</li>
                    <li>Resolve disputes and enforce agreements</li>
                </ul>
                <p>Generally, student records are retained for a minimum of 7 years after graduation or last enrollment date.</p>

                <h2 style="color: #667eea; margin-top: 2rem;">8. Cookies and Tracking</h2>
                <p>We use cookies and similar technologies to:</p>
                <ul style="margin-left: 2rem;">
                    <li>Keep you logged in during your session</li>
                    <li>Remember your preferences</li>
                    <li>Analyze platform usage and performance</li>
                    <li>Enhance security</li>
                </ul>
                <p>You can control cookie settings through your browser. For more information, see our <a href="cookie-policy.php" style="color: #667eea;">Cookie Policy</a>.</p>

                <h2 style="color: #667eea; margin-top: 2rem;">9. Third-Party Links</h2>
                <p>Our platform may contain links to external websites. We are not responsible for the privacy practices of these third-party sites. We encourage you to review their privacy policies before providing any personal information.</p>

                <h2 style="color: #667eea; margin-top: 2rem;">10. Children's Privacy</h2>
                <p>Our platform is intended for users aged 16 and above. We do not knowingly collect personal information from children under 16. If we become aware that we have collected data from a child under 16, we will take steps to delete such information.</p>

                <h2 style="color: #667eea; margin-top: 2rem;">11. International Data Transfers</h2>
                <p>Your data may be transferred to and stored in countries outside your country of residence. We ensure appropriate safeguards are in place to protect your data in accordance with this privacy policy.</p>

                <h2 style="color: #667eea; margin-top: 2rem;">12. Changes to This Policy</h2>
                <p>We may update this privacy policy from time to time. We will notify you of any significant changes by:</p>
                <ul style="margin-left: 2rem;">
                    <li>Posting the new policy on this page</li>
                    <li>Updating the "Last Updated" date</li>
                    <li>Sending you an email notification (for material changes)</li>
                </ul>

                <h2 style="color: #667eea; margin-top: 2rem;">13. Contact Us</h2>
                <p>If you have any questions about this privacy policy or wish to exercise your rights, please contact us:</p>
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 5px; margin-top: 1rem;">
                    <p style="margin-bottom: 0.5rem;"><strong>Email:</strong> privacy@university.edu</p>
                    <p style="margin-bottom: 0.5rem;"><strong>Phone:</strong> +94 11 234 5678</p>
                    <p style="margin-bottom: 0.5rem;"><strong>Address:</strong> 123 University Avenue, Colombo, Sri Lanka</p>
                    <p style="margin-bottom: 0;"><strong>Office Hours:</strong> Monday-Friday, 9:00 AM - 5:00 PM</p>
                </div>

                <div style="margin-top: 2rem; padding: 1.5rem; background: #e3f2fd; border-left: 4px solid #2196f3; border-radius: 5px;">
                    <p style="margin: 0;"><strong>📌 Important Note:</strong> By using University LMS, you acknowledge that you have read and understood this privacy policy and consent to the collection, use, and disclosure of your personal information as described herein.</p>
                </div>
            </div>
        </div>

        <div style="margin-top: 2rem; text-align: center;">
            <a href="terms-of-service.php" style="color: #667eea; margin: 0 1rem;">Terms of Service</a>
            <a href="cookie-policy.php" style="color: #667eea; margin: 0 1rem;">Cookie Policy</a>
            <a href="index.php" style="color: #667eea; margin: 0 1rem;">Back to Home</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>