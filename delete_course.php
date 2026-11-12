<?php

// =========================================
// START SESSION
// =========================================

session_start();

// =========================================
// CHECK ADMIN LOGIN
// =========================================

if (!isset($_SESSION["admin_id"])) {

    header("Location: login.php");
    exit();

}

// =========================================
// DATABASE CONNECTION
// =========================================

require "database.php";

// =========================================
// CHECK DEPARTMENT ID
// =========================================

if (!isset($_GET["department_id"])) {

    header("Location: manage_course.php");
    exit();

}

$department_id = intval($_GET["department_id"]);

// =========================================
// CHECK STUDENT REFERENCE
// =========================================

$sql = "SELECT COUNT(*) AS total
        FROM students
        WHERE department_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $department_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$row = mysqli_fetch_assoc($result);

$student_count = $row["total"];

mysqli_stmt_close($stmt);

// =========================================
// CHECK TEACHER REFERENCE
// =========================================

$sql = "SELECT COUNT(*) AS total
        FROM teachers
        WHERE department_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $department_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$row = mysqli_fetch_assoc($result);

$teacher_count = $row["total"];

mysqli_stmt_close($stmt);

// =========================================
// PREVENT DELETE IF USED
// =========================================

if ($student_count > 0 || $teacher_count > 0) {

    echo "<script>

            alert('This course cannot be deleted because it is assigned to students or teachers.');

            window.location='manage_courses.php';

          </script>";

    exit();

}

// =========================================
// DELETE COURSE
// =========================================

$sql = "DELETE FROM departments
        WHERE department_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $department_id);

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    header("Location: manage_courses.php");
    exit();

}

mysqli_stmt_close($stmt);

mysqli_close($conn);

echo "<script>

        alert('Unable to delete course.');

        window.location='manage_courses.php';

      </script>";

?>