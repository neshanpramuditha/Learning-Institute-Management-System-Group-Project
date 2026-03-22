<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookie Policy - University LMS</title>
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
            <h1 style="color: #667eea; margin-bottom: 1rem;">Cookie Policy</h1>
            <p style="color: #666; margin-bottom: 2rem;"><strong>Last Updated:</strong> <?php echo date('F j, Y'); ?></p>
            
            <div style="line-height: 1.8;">
                <h2 style="color: #667eea; margin-top: 2rem;">1. What Are Cookies?</h2>
                <p>Cookies are small text files that are placed on your computer or mobile device when you visit a website. They are widely used to make websites work more efficiently and provide information to website owners.</p>
                <p>Cookies help us:</p>
                <ul style="margin-left: 2rem;">
                    <li>Remember your login information</li>
                    <li>Understand how you use our platform</li>
                    <li>Improve your user experience</li>
                    <li>Keep your account secure</li>
                </ul>

                <h2 style="color: #667eea; margin-top: 2rem;">2. Types of Cookies We Use</h2>
                
                <h3 style="color: #667eea; margin-top: 1rem;">2.1 Essential Cookies (Required)</h3>
                <p>These cookies are necessary for the platform to function properly and cannot be disabled.</p>
                <table class="table" style="margin-top: 1rem;">
                    <thead>
                        <tr>
                            <th>Cookie Name</th>
                            <th>Purpose</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>PHPSESSID</strong></td>
                            <td>Maintains your login session</td>
                            <td>Session (deleted when browser closes)</td>
                        </tr>
                        <tr>
                            <td><strong>user_id</strong></td>
                            <td>Identifies your account</td>
                            <td>Session</td>
                        </tr>
                        <tr>
                            <td><strong>csrf_token</strong></td>
                            <td>Prevents cross-site request forgery attacks</td>
                            <td>Session</td>
                        </tr>
                    </tbody>
                </table>

                <h3 style="color: #667eea; margin-top: 1.5rem;">2.2 Functional Cookies (Optional)</h3>
                <p>These cookies enhance your experience by remembering your preferences.</p>
                <table class="table" style="margin-top: 1rem;">
                    <thead>
                        <tr>
                            <th>Cookie Name</th>
                            <th>Purpose</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>language_pref</strong></td>
                            <td>Remembers your language preference</td>
                            <td>1 year</td>
                        </tr>
                        <tr>
                            <td><strong>theme_mode</strong></td>
                            <td>Stores dark/light mode preference</td>
                            <td>6 months</td>
                        </tr>
                        <tr>
                            <td><strong>sidebar_state</strong></td>
                            <td>Remembers if you collapsed the sidebar</td>
                            <td>30 days</td>
                        </tr>
                    </tbody>
                </table>

                <h3 style="color: #667eea; margin-top: 1.5rem;">2.3 Analytics Cookies (Optional)</h3>
                <p>These cookies help us understand how users interact with our platform.</p>
                <table class="table" style="margin-top: 1rem;">
                    <thead>
                        <tr>
                            <th>Cookie Name</th>
                            <th>Purpose</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>_ga</strong></td>
                            <td>Google Analytics - tracks usage patterns</td>
                            <td>2 years</td>
                        </tr>
                        <tr>
                            <td><strong>_gid</strong></td>
                            <td>Google Analytics - distinguishes users</td>
                            <td>24 hours</td>
                        </tr>
                        <tr>
                            <td><strong>page_views</strong></td>
                            <td>Counts page visits</td>
                            <td>30 days</td>
                        </tr>
                    </tbody>
                </table>

                <h3 style="color: #667eea; margin-top: 1.5rem;">2.4 Security Cookies (Required)</h3>
                <p>These cookies protect your account and prevent unauthorized access.</p>
                <table class="table" style="margin-top: 1rem;">
                    <thead>
                        <tr>
                            <th>Cookie Name</th>
                            <th>Purpose</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>login_token</strong></td>
                            <td>Verifies your identity</td>
                            <td>Session</td>
                        </tr>
                        <tr>
                            <td><strong>remember_me</strong></td>
                            <td>Keeps you logged in (if selected)</td>
                            <td>30 days</td>
                        </tr>
                        <tr>
                            <td><strong>last_activity</strong></td>
                            <td>Tracks session timeout for security</td>
                            <td>Session</td>
                        </tr>
                    </tbody>
                </table>

                <h2 style="color: #667eea; margin-top: 2rem;">3. Third-Party Cookies</h2>
                <p>We may use services from third-party providers that set their own cookies:</p>
                <ul style="margin-left: 2rem;">
                    <li><strong>Google Analytics:</strong> Analyzes website traffic and user behavior</li>
                    <li><strong>CDN Providers:</strong> Deliver content faster (e.g., fonts, libraries)</li>
                    <li><strong>Video Platforms:</strong> Embed educational videos (e.g., YouTube)</li>
                </ul>
                <p>These third parties have their own privacy and cookie policies. We recommend reviewing them.</p>

                <h2 style="color: #667eea; margin-top: 2rem;">4. How to Manage Cookies</h2>
                
                <h3 style="color: #667eea; margin-top: 1rem;">4.1 Browser Settings</h3>
                <p>You can control cookies through your browser settings:</p>
                
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 5px; margin: 1rem 0;">
                    <p><strong>Google Chrome:</strong></p>
                    <ol style="margin-left: 1rem;">
                        <li>Click the three dots → Settings</li>
                        <li>Privacy and security → Cookies and other site data</li>
                        <li>Choose your preferred settings</li>
                    </ol>
                </div>

                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 5px; margin: 1rem 0;">
                    <p><strong>Mozilla Firefox:</strong></p>
                    <ol style="margin-left: 1rem;">
                        <li>Click the menu button → Settings</li>
                        <li>Privacy & Security</li>
                        <li>Under Cookies and Site Data, manage settings</li>
                    </ol>
                </div>

                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 5px; margin: 1rem 0;">
                    <p><strong>Microsoft Edge:</strong></p>
                    <ol style="margin-left: 1rem;">
                        <li>Click three dots → Settings</li>
                        <li>Cookies and site permissions</li>
                        <li>Manage and delete cookies</li>
                    </ol>
                </div>

                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 5px; margin: 1rem 0;">
                    <p><strong>Safari:</strong></p>
                    <ol style="margin-left: 1rem;">
                        <li>Safari → Preferences</li>
                        <li>Privacy tab</li>
                        <li>Manage cookies and website data</li>
                    </ol>
                </div>

                <h3 style="color: #667eea; margin-top: 1.5rem;">4.2 Important Notice</h3>
                <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 1rem; border-radius: 5px;">
                    <p style="margin: 0;"><strong>⚠️ Warning:</strong> Disabling essential cookies will prevent you from logging in and using University LMS. We recommend keeping essential cookies enabled.</p>
                </div>

                <h2 style="color: #667eea; margin-top: 2rem;">5. Cookie Consent</h2>
                <p>When you first visit University LMS, we will ask for your consent to use optional cookies (functional and analytics). You can:</p>
                <ul style="margin-left: 2rem;">
                    <li>Accept all cookies</li>
                    <li>Reject optional cookies (only essential cookies will be used)</li>
                    <li>Customize your preferences</li>
                    <li>Change your preferences at any time</li>
                </ul>

                <h2 style="color: #667eea; margin-top: 2rem;">6. How Long Do Cookies Last?</h2>
                <p>Cookies have different lifespans:</p>
                <ul style="margin-left: 2rem;">
                    <li><strong>Session Cookies:</strong> Deleted when you close your browser</li>
                    <li><strong>Persistent Cookies:</strong> Remain for a specified period (see tables above)</li>
                    <li><strong>Remember Me Cookies:</strong> Last up to 30 days if you check "Remember Me"</li>
                </ul>

                <h2 style="color: #667eea; margin-top: 2rem;">7. Do Not Track Signals</h2>
                <p>Some browsers support "Do Not Track" (DNT) signals. Currently, there is no industry standard for how to respond to DNT signals. We do not currently respond to DNT signals, but we respect your privacy choices made through browser settings.</p>

                <h2 style="color: #667eea; margin-top: 2rem;">8. Mobile Devices</h2>
                <p>On mobile devices, cookie management is typically done through:</p>
                <ul style="margin-left: 2rem;">
                    <li>Your mobile browser settings</li>
                    <li>Device privacy settings</li>
                    <li>App permissions (if using a mobile app)</li>
                </ul>

                <h2 style="color: #667eea; margin-top: 2rem;">9. Updates to This Policy</h2>
                <p>We may update this Cookie Policy from time to time to reflect:</p>
                <ul style="margin-left: 2rem;">
                    <li>Changes in technology</li>
                    <li>Legal requirements</li>
                    <li>Operational needs</li>
                    <li>New features or services</li>
                </ul>
                <p>We will notify you of significant changes by updating the "Last Updated" date at the top of this page.</p>

                <h2 style="color: #667eea; margin-top: 2rem;">10. More Information</h2>
                <p>For more information about cookies and how they work, visit:</p>
                <ul style="margin-left: 2rem;">
                    <li><a href="https://www.allaboutcookies.org" target="_blank" style="color: #667eea;">www.allaboutcookies.org</a></li>
                    <li><a href="https://www.youronlinechoices.com" target="_blank" style="color: #667eea;">www.youronlinechoices.com</a></li>
                </ul>

                <h2 style="color: #667eea; margin-top: 2rem;">11. Contact Us</h2>
                <p>If you have questions about our use of cookies, please contact us:</p>
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 5px; margin-top: 1rem;">
                    <p style="margin-bottom: 0.5rem;"><strong>Email:</strong> privacy@university.edu</p>
                    <p style="margin-bottom: 0.5rem;"><strong>Phone:</strong> +94 11 234 5678</p>
                    <p style="margin-bottom: 0.5rem;"><strong>Address:</strong> 123 University Avenue, Colombo, Sri Lanka</p>
                    <p style="margin-bottom: 0;"><strong>Office Hours:</strong> Monday-Friday, 9:00 AM - 5:00 PM</p>
                </div>

                <div style="margin-top: 2rem; padding: 1.5rem; background: #e3f2fd; border-left: 4px solid #2196f3; border-radius: 5px;">
                    <p style="margin: 0;"><strong>📌 Summary:</strong> Cookies help us provide you with a better learning experience. Essential cookies are required for the platform to work, while optional cookies improve functionality and help us understand usage patterns. You have control over optional cookies through your browser settings.</p>
                </div>
            </div>
        </div>

        <div style="margin-top: 2rem; text-align: center;">
            <a href="privacy-policy.php" style="color: #667eea; margin: 0 1rem;">Privacy Policy</a>
            <a href="terms-of-service.php" style="color: #667eea; margin: 0 1rem;">Terms of Service</a>
            <a href="index.php" style="color: #667eea; margin: 0 1rem;">Back to Home</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>