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
// CHECK REQUEST
// =========================================

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: manage_class.php");
    exit();

}

// =========================================
// GET FORM DATA
// =========================================

$class_id            = $_POST["class_id"];
$department_id       = $_POST["department_id"];
$year_of_study       = $_POST["year_of_study"];
$section             = strtoupper(trim($_POST["section"]));
$semester_id         = $_POST["semester_id"];
$academic_year_id    = $_POST["academic_year_id"];
$advisor_teacher_id  = $_POST["advisor_teacher_id"];

// =========================================
// CHECK DUPLICATE CLASS
// =========================================

$sql = "SELECT class_id

        FROM classes

        WHERE department_id = ?

        AND year_of_study = ?

        AND section = ?

        AND academic_year_id = ?

        AND class_id != ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "iisii",

    $department_id,

    $year_of_study,

    $section,

    $academic_year_id,

    $class_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) > 0){

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

            alert('This class already exists.');

            window.location='update_class.php?class_id=$class_id';

          </script>";

    exit();

}

mysqli_stmt_close($stmt);

// =========================================
// UPDATE CLASS
// =========================================

$sql = "UPDATE classes

        SET

            department_id = ?,

            year_of_study = ?,

            section = ?,

            semester_id = ?,

            academic_year_id = ?,

            advisor_teacher_id = ?

        WHERE class_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "iisiiii",

    $department_id,

    $year_of_study,

    $section,

    $semester_id,

    $academic_year_id,

    $advisor_teacher_id,

    $class_id

);

if(mysqli_stmt_execute($stmt)){

    echo "<script>

            alert('Class updated successfully.');

            window.location='manage_class.php';

          </script>";

}
else{

    echo "<script>

            alert('Unable to update class.');

            window.history.back();

          </script>";

}

mysqli_stmt_close($stmt);

mysqli_close($conn);

?>