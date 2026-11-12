<?php

// =========================================
// START SESSION
// =========================================

session_start();

// =========================================
// DATABASE CONNECTION
// =========================================

require "database.php";

// =========================================
// CHECK ADMIN LOGIN
// =========================================

if(!isset($_SESSION["admin_id"])){

    header("Location: login.php");
    exit();

}

// =========================================
// CHECK STUDENT ID
// =========================================

if(!isset($_GET["student_id"])){

    die("Invalid Student ID.");

}

$student_id = (int)$_GET["student_id"];

// =========================================
// GET USER ID
// =========================================

$sql = "SELECT user_id
        FROM students
        WHERE student_id = ?";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $student_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result)==0){

    die("Student not found.");

}

$student = mysqli_fetch_assoc($result);

$user_id = $student["user_id"];

mysqli_stmt_close($stmt);

// =========================================
// DELETE STUDENT DOCUMENTS
// =========================================

mysqli_query(
    $conn,
    "DELETE FROM student_documents
     WHERE student_id='$student_id'"
);

// =========================================
// DELETE FAMILY DETAILS
// =========================================

mysqli_query(
    $conn,
    "DELETE FROM family_details
     WHERE student_id='$student_id'"
);

// =========================================
// DELETE ADDRESS
// =========================================

mysqli_query(
    $conn,
    "DELETE FROM student_addresses
     WHERE student_id='$student_id'"
);

// =========================================
// DELETE ACADEMIC DETAILS
// =========================================

mysqli_query(
    $conn,
    "DELETE FROM student_academics
     WHERE student_id='$student_id'"
);

// =========================================
// DELETE STUDENT
// =========================================

mysqli_query(
    $conn,
    "DELETE FROM students
     WHERE student_id='$student_id'"
);

// =========================================
// DELETE LOGIN USER
// =========================================

mysqli_query(
    $conn,
    "DELETE FROM user
     WHERE user_id='$user_id'"
);

// =========================================
// SUCCESS MESSAGE
// =========================================

echo "

<script>

alert('Student deleted successfully.');

window.location='manage_student.php';

</script>

";

mysqli_close($conn);

?>