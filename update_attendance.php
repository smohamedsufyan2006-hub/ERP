<?php

// =========================================
// START SESSION
// =========================================

session_start();

// =========================================
// CHECK TEACHER LOGIN
// =========================================

if (!isset($_SESSION["teacher_id"])) {

    header("Location: teacher_login.php");
    exit();

}

// =========================================
// DATABASE CONNECTION
// =========================================

require "database.php";

// =========================================
// CHECK REQUEST METHOD
// =========================================

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: view_attendance.php");
    exit();

}

// =========================================
// VALIDATE INPUT
// =========================================

if (!isset($_POST["attendance_id"])) {

    header("Location: view_attendance.php");
    exit();

}

$attendance_ids = $_POST["attendance_id"];

// =========================================
// PREPARE UPDATE QUERY
// =========================================

$sql = "UPDATE attendance

        SET attendance_status = ?

        WHERE attendance_id = ?";

$stmt = mysqli_prepare($conn, $sql);

// =========================================
// UPDATE ALL STUDENTS
// =========================================

foreach ($attendance_ids as $attendance_id) {

    $status = $_POST["status_" . $attendance_id];

    mysqli_stmt_bind_param(

        $stmt,

        "si",

        $status,

        $attendance_id

    );

    mysqli_stmt_execute($stmt);

}

mysqli_stmt_close($stmt);

mysqli_close($conn);

// =========================================
// SUCCESS MESSAGE
// =========================================

echo "<script>

        alert('Attendance updated successfully.');

        window.location='view_attendance.php';

      </script>";

?>