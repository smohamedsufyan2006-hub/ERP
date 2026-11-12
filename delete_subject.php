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
// CHECK SUBJECT ID
// =========================================

if (!isset($_GET["subject_id"])) {

    header("Location: manage_subject.php");
    exit();

}

$subject_id = $_GET["subject_id"];

// =========================================
// CHECK SUBJECT EXISTS
// =========================================

$sql = "SELECT subject_id

        FROM subjects

        WHERE subject_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $subject_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) == 0){

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('Subject not found.');

            window.location='manage_subject.php';

          </script>";

    exit();

}

mysqli_stmt_close($stmt);

// =========================================
// DELETE SUBJECT
// =========================================

$sql = "DELETE FROM subjects

        WHERE subject_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $subject_id

);

if(mysqli_stmt_execute($stmt)){

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('Subject deleted successfully.');

            window.location='manage_subject.php';

          </script>";

}else{

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('Unable to delete subject.');

            window.location='manage_subject.php';

          </script>";

}

?>