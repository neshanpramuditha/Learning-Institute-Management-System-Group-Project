<!-- box icon link -->
 <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

<!-- Footer -->
<footer class="footer">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 2rem 20px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
            
            <!-- About Section -->
            <div>
                <a href="index.php" class="logo" style="text-decoration:none; color:white;">
                <img src="/university_lms/uploads/Logo2.png" alt="Logo" style="height:40px;"></a>
                <p style="line-height: 1.6; color: #ddd;">
                    Empowering education through innovative technology. 
                    Your gateway to quality learning and academic excellence.
                </p>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h3 style="color: white; margin-bottom: 1rem;">Quick Links</h3>
                <ul style="list-style: none; padding: 0; line-height: 2;">
                    <li><a href="<?php echo isset($root_path) ? $root_path : ''; ?>index.php" style="color: #ddd; text-decoration: none;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#ddd'">Home</a></li>
                    <li><a href="<?php echo isset($root_path) ? $root_path : ''; ?>about.php" style="color: #ddd; text-decoration: none;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#ddd'">About Us</a></li>
                    <li><a href="<?php echo isset($root_path) ? $root_path : ''; ?>courses.php" style="color: #ddd; text-decoration: none;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#ddd'">Courses</a></li>
                    <li><a href="<?php echo isset($root_path) ? $root_path : ''; ?>contact.php" style="color: #ddd; text-decoration: none;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#ddd'">Contact</a></li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h3 style="color: white; margin-bottom: 1rem;">Contact Us</h3>
                <ul style="list-style: none; padding: 0; line-height: 2; color: #ddd;">
                    <li><i class='bx bx-map' style="color:green;"></i>&nbsp; 123 University Avenue</li>
                    <li><i class='bx bx-phone-call' style="color:red;"></i>&nbsp; +94 11 234 5678</li>
                    <li><i class='bx bx-envelope' style="color:#3b82f6;"></i>&nbsp;  info@university.edu</li>
                    <li><i class='bx bx-time-five' style="color:#667eea;"></i>&nbsp; Mon-Fri: 8AM - 5PM</li>
                </ul>
            </div>
            
            <!-- Social Media -->
            <div>
                <h3 style="color: white; margin-bottom: 1rem;">Follow Us</h3>
                <div style="display: flex; gap: 2rem; margin-bottom: 1rem; justify-content: center; align-items: center;">
                    <a href="#" style="color: white; font-size: 1.5rem; text-decoration: none;" title="Facebook"><i class='bx bxl-facebook-circle' style="color:#1877F2;"></i></a>
                    <a href="#" style="color: white; font-size: 1.5rem; text-decoration: none;" title="Twitter"><i class='bx bxl-twitter'style="color:#1DA1F2;"></i></a>
                    <a href="#" style="color: white; font-size: 1.5rem; text-decoration: none;" title="Instagram"><i class='bx bxl-instagram'style="color:#DD2A7B;"></i></a>
                    <a href="#" style="color: white; font-size: 1.5rem; text-decoration: none;" title="LinkedIn"><i class='bx bxl-linkedin-square'style="color:#0A66C2;"></i></a>
                </div>
                <?php if(!isset($_SESSION['user_id'])): ?>
                <div style="margin-top: 1rem;">
                    <a href="<?php echo isset($root_path) ? $root_path : ''; ?>register.php" class="btn" style="background: #FF3B3B; color: white; padding: 0.5rem 1rem; display: inline-block; text-decoration: none; border-radius: 5px;">Register Now</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Bottom Bar -->
        <div style="border-top: 1px solid rgba(255,255,255,0.2); padding-top: 1.5rem; text-align: center;">
            <p style="margin-bottom: 0.5rem;">&copy; <i><?php echo date('Y'); ?> National Institute of Tech & Engineering. All rights reserved.</i></p>
            <p style="font-size: 0.9rem; color: #ddd;">
                <a href="/university_lms/privacy-policy-page.php" style="color: #ddd; text-decoration: none; margin: 0 0.5rem;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#ddd'">Privacy Policy</a> |
                <a href="/university_lms/terms-of-service-final.php" style="color: #ddd; text-decoration: none; margin: 0 0.5rem;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#ddd'">Terms of Service</a> | 
                <a href="/university_lms/cookie-policy-page.php" style="color: #ddd; text-decoration: none; margin: 0 0.5rem;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#ddd'">Cookie Policy</a>
            </p>
        </div>
    </div>
</footer>