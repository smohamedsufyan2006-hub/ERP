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

    header("Location: manage_class_subject.php");
    exit();

}

// =========================================
// GET FORM DATA
// =========================================

$class_subject_id = trim($_POST["class_subject_id"]);

$class_id = trim($_POST["class_id"]);

$subject_id = trim($_POST["subject_id"]);

// =========================================
// VALIDATION
// =========================================

if (

    empty($class_subject_id)

    ||

    empty($class_id)

    ||

    empty($subject_id)

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

$sql = "SELECT class_subject_id

        FROM class_subjects

        WHERE class_id = ?

        AND subject_id = ?

        AND class_subject_id <> ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "iii",

    $class_id,

    $subject_id,

    $class_subject_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) > 0){

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('This subject is already assigned to the selected class.');

            window.location='manage_class_subject.php';

          </script>";

    exit();

}

mysqli_stmt_close($stmt);

// =========================================
// UPDATE RECORD
// =========================================

$sql = "UPDATE class_subjects

        SET

            class_id = ?,

            subject_id = ?

        WHERE class_subject_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "iii",

    $class_id,

    $subject_id,

    $class_subject_id

);

if(mysqli_stmt_execute($stmt)){

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('Class Subject updated successfully.');

            window.location='manage_class_subject.php';

          </script>";

}else{

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('Failed to update Class Subject.');

            window.history.back();

          </script>";

}

?>