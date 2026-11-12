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
// CHECK ID
// =========================================

if (!isset($_GET["teacher_class_subject_id"])) {

    header("Location: manage_teacher_class_subject.php");
    exit();

}

$teacher_class_subject_id = $_GET["teacher_class_subject_id"];

// =========================================
// CHECK RECORD EXISTS
// =========================================

$sql = "SELECT teacher_class_subject_id

        FROM teacher_class_subjects

        WHERE teacher_class_subject_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $teacher_class_subject_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) == 0){

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('Teacher allocation not found.');

            window.location='manage_teacher_class_subject.php';

          </script>";

    exit();

}

mysqli_stmt_close($stmt);

// =========================================
// DELETE RECORD
// =========================================

$sql = "DELETE FROM teacher_class_subjects

        WHERE teacher_class_subject_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $teacher_class_subject_id

);

if(mysqli_stmt_execute($stmt)){

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('Teacher allocation deleted successfully.');

            window.location='manage_teacher_class_subject.php';

          </script>";

}else{

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('Unable to delete teacher allocation.');

            window.location='manage_teacher_class_subject.php';

          </script>";

}

?>