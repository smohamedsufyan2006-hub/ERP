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
// CHECK TEACHER ID
// =========================================

if (!isset($_GET["teacher_id"])) {

    die("Teacher ID not found.");

}

$teacher_id = intval($_GET["teacher_id"]);

// =========================================
// GET USER ID
// =========================================

$sql = "SELECT user_id
        FROM teachers
        WHERE teacher_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $teacher_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {

    die("Teacher not found.");

}

$teacher = mysqli_fetch_assoc($result);

$user_id = $teacher["user_id"];

mysqli_stmt_close($stmt);

// =========================================
// DELETE UPLOADED FILES
// =========================================

$sql = "SELECT file_name
        FROM teacher_documents
        WHERE teacher_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $teacher_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

while ($document = mysqli_fetch_assoc($result)) {

    $file = "uploads/teachers/" . $document["file_name"];

    if (file_exists($file)) {

        unlink($file);

    }

}

mysqli_stmt_close($stmt);

// =========================================
// DELETE DOCUMENT RECORDS
// =========================================

$sql = "DELETE FROM teacher_documents
        WHERE teacher_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $teacher_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

// =========================================
// DELETE QUALIFICATIONS
// =========================================

$sql = "DELETE FROM teacher_qualifications
        WHERE teacher_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $teacher_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

// =========================================
// DELETE EXPERIENCE
// =========================================

$sql = "DELETE FROM teacher_experience
        WHERE teacher_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $teacher_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

// =========================================
// DELETE EMERGENCY CONTACT
// =========================================

$sql = "DELETE FROM teacher_emergency_contacts
        WHERE teacher_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $teacher_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

// =========================================
// DELETE ADDRESS
// =========================================

$sql = "DELETE FROM teacher_addresses
        WHERE teacher_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $teacher_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

// =========================================
// DELETE TEACHER
// =========================================

$sql = "DELETE FROM teachers
        WHERE teacher_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $teacher_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

// =========================================
// DELETE USER ACCOUNT
// =========================================

$sql = "DELETE FROM user
        WHERE user_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $user_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

// =========================================
// CLOSE CONNECTION
// =========================================

mysqli_close($conn);

// =========================================
// REDIRECT
// =========================================

header("Location: manage_teacher.php");
exit();

?>