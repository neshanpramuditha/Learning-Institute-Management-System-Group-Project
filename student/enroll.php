<?php
include '../config.php';

if(!isLoggedIn() || !isStudent()) {
    redirect('../login.php');
}

if(!isset($_GET['course_id'])) {
    redirect('my-courses.php');
}

$student_id = $_SESSION['user_id'];
$course_id = (int)$_GET['course_id'];

// Check if already enrolled
$check_query = "SELECT * FROM enrollments WHERE student_id = $student_id AND course_id = $course_id";
$check_result = mysqli_query($conn, $check_query);

if(mysqli_num_rows($check_result) > 0) {
    $_SESSION['message'] = 'You are already enrolled in this course!';
    $_SESSION['message_type'] = 'info';
} else {
    // Enroll student
    $enroll_query = "INSERT INTO enrollments (student_id, course_id) VALUES ($student_id, $course_id)";
    if(mysqli_query($conn, $enroll_query)) {
        $_SESSION['message'] = 'Successfully enrolled in the course!';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Failed to enroll. Please try again.';
        $_SESSION['message_type'] = 'danger';
    }
}

redirect('my-courses.php');
?>