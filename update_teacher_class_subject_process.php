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

$teacher_class_subject_id = trim($_POST["teacher_class_subject_id"]);

$class_subject_id = trim($_POST["class_subject_id"]);

$teacher_id = trim($_POST["teacher_id"]);

// =========================================
// VALIDATION
// =========================================

if (

    empty($teacher_class_subject_id)

    ||

    empty($class_subject_id)

    ||

    empty($teacher_id)

){

    echo "<script>

            alert('Please fill all required fields.');

            window.history.back();

          </script>";

    exit();

}

// =========================================
// CHECK DUPLICATE
// =========================================

$sql = "SELECT teacher_class_subject_id

        FROM teacher_class_subjects

        WHERE class_subject_id = ?

        AND teacher_class_subject_id <> ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "ii",

    $class_subject_id,

    $teacher_class_subject_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) > 0){

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('This Class Subject already has a teacher assigned.');

            window.location='manage_teacher_class_subject.php';

          </script>";

    exit();

}

mysqli_stmt_close($stmt);

// =========================================
// UPDATE RECORD
// =========================================

$sql = "UPDATE teacher_class_subjects

        SET

            class_subject_id = ?,

            teacher_id = ?

        WHERE teacher_class_subject_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "iii",

    $class_subject_id,

    $teacher_id,

    $teacher_class_subject_id

);

if(mysqli_stmt_execute($stmt)){

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('Teacher allocation updated successfully.');

            window.location='manage_teacher_class_subject.php';

          </script>";

}else{

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('Failed to update teacher allocation.');

            window.history.back();

          </script>";

}

?>