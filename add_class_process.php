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
// CHECK FORM SUBMISSION
// =========================================

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: add_class.php");
    exit();

}

// =========================================
// GET FORM VALUES
// =========================================

$department_id = $_POST["department_id"];
$year_of_study = $_POST["year_of_study"];
$section = strtoupper(trim($_POST["section"]));
$semester_id = $_POST["semester_id"];
$academic_year_id = $_POST["academic_year_id"];
$advisor_teacher_id = $_POST["advisor_teacher_id"];

// =========================================
// CHECK DUPLICATE CLASS
// =========================================

$sql = "SELECT class_id

        FROM classes

        WHERE department_id = ?

        AND year_of_study = ?

        AND section = ?

        AND academic_year_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "iisi",

    $department_id,

    $year_of_study,

    $section,

    $academic_year_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    echo "<script>

            alert('This class already exists.');

            window.location='add_class.php';

          </script>";

    exit();

}

mysqli_stmt_close($stmt);

// =========================================
// INSERT CLASS
// =========================================

$sql = "INSERT INTO classes
(
    department_id,
    year_of_study,
    section,
    semester_id,
    academic_year_id,
    advisor_teacher_id
)
VALUES
(
    ?, ?, ?, ?, ?, ?
)";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "iisiii",

    $department_id,

    $year_of_study,

    $section,

    $semester_id,

    $academic_year_id,

    $advisor_teacher_id

);

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    echo "<script>

            alert('Class added successfully.');

            window.location='manage_class.php';

          </script>";

    exit();

} else {

    echo "<script>

            alert('Failed to add class.');

            window.history.back();

          </script>";

}

mysqli_stmt_close($stmt);

mysqli_close($conn);

?>