<?php
include '../config.php';

if(!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

if(!isset($_GET['id'])) {
    redirect('courses.php');
}

$course_id = (int)$_GET['id'];

// Check if course exists
$check_query = "SELECT * FROM courses WHERE id = $course_id";
$check_result = mysqli_query($conn, $check_query);

if(mysqli_num_rows($check_result) == 0) {
    $_SESSION['message'] = 'Course not found!';
    $_SESSION['message_type'] = 'danger';
    redirect('courses.php');
}

// Delete course (cascade will handle related records: enrollments, assignments, etc.)
$delete_query = "DELETE FROM courses WHERE id = $course_id";

if(mysqli_query($conn, $delete_query)) {
    $_SESSION['message'] = 'Course and all related data deleted successfully!';
    $_SESSION['message_type'] = 'success';
} else {
    $_SESSION['message'] = 'Failed to delete course. Please try again.';
    $_SESSION['message_type'] = 'danger';
}

redirect('courses.php');
?>