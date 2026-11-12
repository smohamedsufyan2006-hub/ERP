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
// CHECK REQUEST
// =========================================

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: manage_attendance.php");
    exit();

}

// =========================================
// GET FORM DATA
// =========================================

$teacher_class_subject_id = intval($_POST["teacher_class_subject_id"]);

$attendance_date = $_POST["attendance_date"];

$period_number = intval($_POST["period_number"]);

$student_ids = $_POST["student_id"];

// =========================================
// CHECK DUPLICATE SESSION
// =========================================

$sql = "SELECT attendance_session_id

        FROM attendance_sessions

        WHERE teacher_class_subject_id = ?

        AND attendance_date = ?

        AND period_number = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "isi",

    $teacher_class_subject_id,

    $attendance_date,

    $period_number

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) > 0){

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('Attendance has already been taken for this Date and Period.');

            window.location='manage_attendance.php';

          </script>";

    exit();

}

mysqli_stmt_close($stmt);

// =========================================
// CREATE ATTENDANCE SESSION
// =========================================

$sql = "INSERT INTO attendance_sessions
(
    teacher_class_subject_id,
    attendance_date,
    period_number
)
VALUES
(
    ?, ?, ?
)";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "isi",

    $teacher_class_subject_id,

    $attendance_date,

    $period_number

);

mysqli_stmt_execute($stmt);

$attendance_session_id = mysqli_insert_id($conn);

mysqli_stmt_close($stmt);

// =========================================
// SAVE STUDENT ATTENDANCE
// =========================================

$sql = "INSERT INTO attendance
(
    attendance_session_id,
    student_id,
    attendance_status
)
VALUES
(
    ?, ?, ?
)";

$stmt = mysqli_prepare($conn, $sql);

foreach($student_ids as $student_id){

    $status = $_POST["attendance_" . $student_id];

    mysqli_stmt_bind_param(

        $stmt,

        "iis",

        $attendance_session_id,

        $student_id,

        $status

    );

    mysqli_stmt_execute($stmt);

}

mysqli_stmt_close($stmt);

mysqli_close($conn);

// =========================================
// SUCCESS
// =========================================

echo "<script>

        alert('Attendance saved successfully.');

        window.location='manage_attendance.php';

      </script>";

?>