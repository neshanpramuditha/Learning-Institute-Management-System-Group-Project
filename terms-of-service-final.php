<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - University LMS</title>
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
            <h1 style="color: #667eea; margin-bottom: 1rem;">Terms of Service</h1>
            <p style="color: #666; margin-bottom: 2rem;"><strong>Last Updated:</strong> <?php echo date('F j, Y'); ?></p>
            
            <div style="line-height: 1.8;">
                <h2 style="color: #667eea; margin-top: 2rem;">1. Acceptance of Terms</h2>
                <p>Welcome to University Learning Management System ("University LMS", "we", "us", or "our"). By accessing or using our platform, you agree to be bound by these Terms of Service and all applicable laws and regulations. If you do not agree with any of these terms, you are prohibited from using or accessing this platform.</p>

                <h2 style="color: #667eea; margin-top: 2rem;">2. User Accounts</h2>
                
                <h3 style="color: #667eea; margin-top: 1rem;">2.1 Account Registration</h3>
                <ul style="margin-left: 2rem;">
                    <li>You must provide accurate, complete, and current information during registration</li>
                    <li>You are responsible for maintaining the confidentiality of your account credentials</li>
                    <li>You must notify us immediately of any unauthorized access to your account</li>
                    <li>You must be at least 16 years old to create an account</li>
                    <li>One person may only maintain one account</li>
                </ul>

                <h3 style="color: #667eea; margin-top: 1rem;">2.2 Account Security</h3>
                <p>You are solely responsible for all activities that occur under your account. We recommend:</p>
                <ul style="margin-left: 2rem;">
                    <li>Using a strong, unique password</li>
                    <li>Not sharing your login credentials with others</li>
                    <li>Logging out after each session on shared computers</li>
                    <li>Changing your password regularly</li>
                </ul>

                <h2 style="color: #667eea; margin-top: 2rem;">3. Acceptable Use Policy</h2>
                
                <h3 style="color: #667eea; margin-top: 1rem;">3.1 Permitted Uses</h3>
                <p>You may use University LMS for:</p>
                <ul style="margin-left: 2rem;">
                    <li>Accessing enrolled course materials</li>
                    <li>Submitting assignments and coursework</li>
                    <li>Communicating with instructors and fellow students</li>
                    <li>Viewing grades and academic progress</li>
                    <li>Participating in online discussions and forums</li>
                </ul>

                <h3 style="color: #667eea; margin-top: 1rem;">3.2 Prohibited Activities</h3>
                <p>You agree NOT to:</p>
                <ul style="margin-left: 2rem;">
                    <li>Upload or share copyrighted materials without permission</li>
                    <li>Submit work that is not your own (plagiarism)</li>
                    <li>Harass, threaten, or abuse other users</li>
                    <li>Attempt to hack, disrupt, or damage the platform</li>
                    <li>Share your account credentials with others</li>
                    <li>Use the platform for commercial purposes without authorization</li>
                    <li>Upload viruses, malware, or malicious code</li>
                    <li>Impersonate another person or entity</li>
                    <li>Collect or harvest user data without consent</li>
                    <li>Violate any applicable laws or regulations</li>
                </ul>

                <h2 style="color: #667eea; margin-top: 2rem;">4. Academic Integrity</h2>
                <p>All students must adhere to academic integrity standards:</p>
                <ul style="margin-left: 2rem;">
                    <li>Submit only original work or properly cited sources</li>
                    <li>Do not engage in cheating or plagiarism</li>
                    <li>Do not share assignment answers with other students</li>
                    <li>Respect intellectual property rights</li>
                    <li>Follow exam and assignment guidelines</li>
                </ul>
                <p><strong>Violations may result in:</strong> Assignment failure, course failure, suspension, or expulsion as determined by university policy.</p>

                <h2 style="color: #667eea; margin-top: 2rem;">5. Intellectual Property Rights</h2>
                
                <h3 style="color: #667eea; margin-top: 1rem;">5.1 University Content</h3>
                <p>All course materials, including but not limited to lectures, videos, documents, assignments, and assessments, are the intellectual property of University LMS or its content providers. You may:</p>
                <ul style="margin-left: 2rem;">
                    <li>View and download materials for personal educational use only</li>
                    <li>Not redistribute, republish, or commercially exploit the content</li>
                    <li>Not remove copyright or proprietary notices</li>
                </ul>

                <h3 style="color: #667eea; margin-top: 1rem;">5.2 User Content</h3>
                <p>By submitting content (assignments, discussions, etc.), you grant University LMS a license to:</p>
                <ul style="margin-left: 2rem;">
                    <li>Store and display your submissions for educational purposes</li>
                    <li>Share with instructors and authorized university personnel</li>
                    <li>Use for plagiarism detection and academic integrity purposes</li>
                </ul>
                <p>You retain ownership of your original work but warrant that you have the right to submit it.</p>

                <h2 style="color: #667eea; margin-top: 2rem;">6. Course Enrollment and Participation</h2>
                <ul style="margin-left: 2rem;">
                    <li>Enrollment in courses is subject to availability and eligibility requirements</li>
                    <li>Course schedules, content, and instructors may change without notice</li>
                    <li>You are responsible for meeting assignment deadlines</li>
                    <li>Regular participation is expected and may affect your grade</li>
                    <li>Course withdrawal policies follow university academic calendar</li>
                </ul>

                <h2 style="color: #667eea; margin-top: 2rem;">7. Grading and Assessment</h2>
                <ul style="margin-left: 2rem;">
                    <li>Grades are determined by instructors based on published criteria</li>
                    <li>Grade appeals must follow university procedures</li>
                    <li>Final grades are subject to academic review and approval</li>
                    <li>We strive for accuracy but cannot guarantee error-free grade calculations</li>
                </ul>

                <h2 style="color: #667eea; margin-top: 2rem;">8. Privacy and Data Protection</h2>
                <p>Your privacy is important to us. Please review our <a href="privacy-policy.php" style="color: #667eea;">Privacy Policy</a> to understand how we collect, use, and protect your personal information.</p>

                <h2 style="color: #667eea; margin-top: 2rem;">9. Service Availability</h2>
                <p>While we strive to provide uninterrupted service:</p>
                <ul style="margin-left: 2rem;">
                    <li>We do not guarantee 100% uptime or availability</li>
                    <li>Scheduled maintenance will be announced in advance when possible</li>
                    <li>We are not liable for delays or failures due to circumstances beyond our control</li>
                    <li>Service may be temporarily suspended for maintenance or security purposes</li>
                </ul>

                <h2 style="color: #667eea; margin-top: 2rem;">10. Disclaimer of Warranties</h2>
                <p>University LMS is provided "AS IS" and "AS AVAILABLE" without warranties of any kind, either express or implied, including but not limited to:</p>
                <ul style="margin-left: 2rem;">
                    <li>Merchantability or fitness for a particular purpose</li>
                    <li>Accuracy, completeness, or reliability of content</li>
                    <li>Uninterrupted or error-free operation</li>
                    <li>Security or freedom from viruses</li>
                </ul>

                <h2 style="color: #667eea; margin-top: 2rem;">11. Limitation of Liability</h2>
                <p>To the fullest extent permitted by law, University LMS shall not be liable for:</p>
                <ul style="margin-left: 2rem;">
                    <li>Any indirect, incidental, special, or consequential damages</li>
                    <li>Loss of data, profits, or business opportunities</li>
                    <li>Damages resulting from unauthorized access to your account</li>
                    <li>Technical failures or interruptions of service</li>
                </ul>

                <h2 style="color: #667eea; margin-top: 2rem;">12. Termination</h2>
                
                <h3 style="color: #667eea; margin-top: 1rem;">12.1 By You</h3>
                <p>You may terminate your account at any time by contacting us. Upon termination, you will lose access to all course materials and data.</p>

                <h3 style="color: #667eea; margin-top: 1rem;">12.2 By Us</h3>
                <p>We reserve the right to suspend or terminate your account if you:</p>
                <ul style="margin-left: 2rem;">
                    <li>Violate these Terms of Service</li>
                    <li>Engage in fraudulent or illegal activities</li>
                    <li>Pose a security or legal risk</li>
                    <li>Fail to comply with academic integrity standards</li>
                </ul>

                <h2 style="color: #667eea; margin-top: 2rem;">13. Modifications to Terms</h2>
                <p>We reserve the right to modify these terms at any time. Changes will be effective when posted on this page. Your continued use of the platform after changes constitutes acceptance of the modified terms.</p>

                <h2 style="color: #667eea; margin-top: 2rem;">14. Governing Law</h2>
                <p>These terms shall be governed by and construed in accordance with the laws of Sri Lanka, without regard to its conflict of law provisions.</p>

                <h2 style="color: #667eea; margin-top: 2rem;">15. Dispute Resolution</h2>
                <p>Any disputes arising from these terms or your use of University LMS shall be resolved through:</p>
                <ul style="margin-left: 2rem;">
                    <li>First: Good faith negotiation</li>
                    <li>Second: Mediation (if negotiation fails)</li>
                    <li>Third: Arbitration or court proceedings as a last resort</li>
                </ul>

                <h2 style="color: #667eea; margin-top: 2rem;">16. Contact Information</h2>
                <p>For questions about these Terms of Service, please contact:</p>
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 5px; margin-top: 1rem;">
                    <p style="margin-bottom: 0.5rem;"><strong>Email:</strong> legal@university.edu</p>
                    <p style="margin-bottom: 0.5rem;"><strong>Phone:</strong> +94 11 234 5678</p>
                    <p style="margin-bottom: 0.5rem;"><strong>Address:</strong> 123 University Avenue, Colombo, Sri Lanka</p>
                    <p style="margin-bottom: 0;"><strong>Office Hours:</strong> Monday-Friday, 9:00 AM - 5:00 PM</p>
                </div>

                <div style="margin-top: 2rem; padding: 1.5rem; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 5px;">
                    <p style="margin: 0;"><strong>⚠️ Important:</strong> By using University LMS, you acknowledge that you have read, understood, and agree to be bound by these Terms of Service.</p>
                </div>
            </div>
        </div>

        <div style="margin-top: 2rem; text-align: center;">
            <a href="privacy-policy.php" style="color: #667eea; margin: 0 1rem;">Privacy Policy</a>
            <a href="cookie-policy.php" style="color: #667eea; margin: 0 1rem;">Cookie Policy</a>
            <a href="index.php" style="color: #667eea; margin: 0 1rem;">Back to Home</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>