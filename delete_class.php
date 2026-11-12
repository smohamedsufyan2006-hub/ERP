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
// CHECK CLASS ID
// =========================================

if (!isset($_GET["class_id"])) {

    header("Location: manage_class.php");
    exit();

}

$class_id = $_GET["class_id"];

// =========================================
// CHECK WHETHER CLASS EXISTS
// =========================================

$sql = "SELECT class_id
        FROM classes
        WHERE class_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $class_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    header("Location: manage_class.php");
    exit();

}

mysqli_stmt_close($stmt);

// =========================================
// CHECK STUDENTS ASSIGNED
// =========================================

$sql = "SELECT COUNT(*) AS total
        FROM students
        WHERE class_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $class_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$row = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if ($row["total"] > 0) {

    mysqli_close($conn);

    echo "<script>

            alert('This class cannot be deleted because students are assigned to it.');

            window.location='manage_class.php';

          </script>";

    exit();

}

// =========================================
// DELETE CLASS
// =========================================

$sql = "DELETE FROM classes
        WHERE class_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $class_id

);

if (mysqli_stmt_execute($stmt)) {

    echo "<script>

            alert('Class deleted successfully.');

            window.location='manage_class.php';

          </script>";

}
else{

    echo "<script>

            alert('Unable to delete class.');

            window.location='manage_class.php';

          </script>";

}

mysqli_stmt_close($stmt);

mysqli_close($conn);

?>