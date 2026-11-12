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
// CHECK REQUEST METHOD
// =========================================

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: manage_teacher_class_subject.php");
    exit();

}

// =========================================
// GET FORM DATA
// =========================================

$class_subject_id = trim($_POST["class_subject_id"]);

$teacher_id = trim($_POST["teacher_id"]);

// =========================================
// VALIDATION
// =========================================

if (

    empty($class_subject_id)

    ||

    empty($teacher_id)

){

    echo "<script>

            alert('Please select both Class Subject and Teacher.');

            window.history.back();

          </script>";

    exit();

}

// =========================================
// CHECK DUPLICATE
// =========================================

$sql = "SELECT teacher_class_subject_id

        FROM teacher_class_subjects

        WHERE class_subject_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $class_subject_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) > 0){

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('A teacher has already been assigned to this Class Subject.');

            window.location='manage_teacher_class_subject.php';

          </script>";

    exit();

}

mysqli_stmt_close($stmt);

// =========================================
// INSERT RECORD
// =========================================

$sql = "INSERT INTO teacher_class_subjects

        (

            class_subject_id,

            teacher_id

        )

        VALUES

        (?, ?)";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "ii",

    $class_subject_id,

    $teacher_id

);

if(mysqli_stmt_execute($stmt)){

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('Teacher assigned successfully.');

            window.location='manage_teacher_class_subject.php';

          </script>";

}else{

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('Failed to assign teacher.');

            window.history.back();

          </script>";

}

?>