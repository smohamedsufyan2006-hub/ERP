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
// CHECK CLASS SUBJECT ID
// =========================================

if (!isset($_GET["class_subject_id"])) {

    header("Location: manage_class_subject.php");
    exit();

}

$class_subject_id = $_GET["class_subject_id"];

// =========================================
// CHECK RECORD EXISTS
// =========================================

$sql = "SELECT class_subject_id

        FROM class_subjects

        WHERE class_subject_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $class_subject_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result)==0){

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('Record not found.');

            window.location='manage_class_subject.php';

          </script>";

    exit();

}

mysqli_stmt_close($stmt);

// =========================================
// DELETE RECORD
// =========================================

$sql = "DELETE FROM class_subjects

        WHERE class_subject_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $class_subject_id

);

if(mysqli_stmt_execute($stmt)){

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('Subject removed from class successfully.');

            window.location='manage_class_subject.php';

          </script>";

}else{

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('Unable to delete record.');

            window.location='manage_class_subject.php';

          </script>";

}

?>