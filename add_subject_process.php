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

    header("Location: manage_subject.php");
    exit();

}

// =========================================
// GET FORM DATA
// =========================================

$subject_code = trim($_POST["subject_code"]);

$subject_name = trim($_POST["subject_name"]);

$credits = trim($_POST["credits"]);

// =========================================
// VALIDATION
// =========================================

if (

    empty($subject_code) ||

    empty($subject_name) ||

    empty($credits)

){

    echo "<script>

            alert('Please fill all required fields.');

            window.history.back();

          </script>";

    exit();

}

// =========================================
// CHECK DUPLICATE SUBJECT CODE
// =========================================

$sql = "SELECT subject_id
        FROM subjects
        WHERE subject_code = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "s",

    $subject_code

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) > 0){

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('Subject Code already exists.');

            window.history.back();

          </script>";

    exit();

}

mysqli_stmt_close($stmt);

// =========================================
// INSERT SUBJECT
// =========================================

$sql = "INSERT INTO subjects
        (
            subject_code,
            subject_name,
            credits
        )
        VALUES
        (?,?,?)";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "ssi",

    $subject_code,

    $subject_name,

    $credits

);

if(mysqli_stmt_execute($stmt)){

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('Subject registered successfully.');

            window.location='manage_subject.php';

          </script>";

}else{

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('Failed to register subject.');

            window.history.back();

          </script>";

}

?>