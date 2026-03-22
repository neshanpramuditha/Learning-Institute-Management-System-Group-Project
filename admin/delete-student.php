<?php
include '../config.php';

if(!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

if(!isset($_GET['id'])) {
    redirect('students.php');
}

$student_id = (int)$_GET['id'];

// Check if student exists
$check_query = "SELECT * FROM users WHERE id = $student_id AND role = 'student'";
$check_result = mysqli_query($conn, $check_query);

if(mysqli_num_rows($check_result) == 0) {
    $_SESSION['message'] = 'Student not found!';
    $_SESSION['message_type'] = 'danger';
    redirect('students.php');
}

// Delete student (cascade will handle related records: enrollments, submissions, grades)
$delete_query = "DELETE FROM users WHERE id = $student_id";

if(mysqli_query($conn, $delete_query)) {
    $_SESSION['message'] = 'Student and all related data deleted successfully!';
    $_SESSION['message_type'] = 'success';
} else {
    $_SESSION['message'] = 'Failed to delete student. Please try again.';
    $_SESSION['message_type'] = 'danger';
}

redirect('students.php');
?>