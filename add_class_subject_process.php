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

$class_id = trim($_POST["class_id"]);

$subject_id = trim($_POST["subject_id"]);

// =========================================
// VALIDATION
// =========================================

if (

    empty($class_id)

    ||

    empty($subject_id)

){

    echo "<script>

            alert('Please select both Class and Subject.');

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

        AND subject_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "ii",

    $class_id,

    $subject_id

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
// INSERT RECORD
// =========================================

$sql = "INSERT INTO class_subjects

        (

            class_id,

            subject_id

        )

        VALUES

        (?, ?)";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "ii",

    $class_id,

    $subject_id

);

if(mysqli_stmt_execute($stmt)){

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('Subject assigned to class successfully.');

            window.location='manage_class_subject.php';

          </script>";

}else{

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('Failed to assign subject.');

            window.history.back();

          </script>";

}

?>