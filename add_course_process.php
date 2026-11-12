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

    header("Location: manage_course.php");
    exit();

}

// =========================================
// GET FORM DATA
// =========================================

$department_name = trim($_POST["department_name"]);
$program_id = intval($_POST["program_id"]);

// =========================================
// VALIDATION
// =========================================

if ($department_name == "" || $program_id == 0) {

    echo "<script>

            alert('Please fill all fields.');

            window.history.back();

          </script>";

    exit();

}

// =========================================
// CHECK DUPLICATE COURSE
// =========================================

$sql = "SELECT department_id
        FROM departments
        WHERE department_name = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "s",
    $department_name
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {

    mysqli_stmt_close($stmt);

    echo "<script>

            alert('Course already exists.');

            window.history.back();

          </script>";

    exit();

}

mysqli_stmt_close($stmt);

// =========================================
// INSERT COURSE
// =========================================

$sql = "INSERT INTO departments
        (
            department_name,
            program_id
        )
        VALUES
        (?, ?)";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "si",
    $department_name,
    $program_id
);

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    echo "<script>

alert('Course registered successfully.');

window.location='manage_courses.php';

</script>";

exit();

} else {

    echo "<script>

            alert('Failed to add course.');

            window.history.back();

          </script>";

}

mysqli_stmt_close($stmt);

mysqli_close($conn);

?>