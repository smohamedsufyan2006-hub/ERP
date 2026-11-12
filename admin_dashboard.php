<?php

// =========================================
// START SESSION
// =========================================

session_start();

// =========================================
// CHECK ADMIN LOGIN
// =========================================

if(!isset($_SESSION["admin_id"])){

    header("Location: login.php");

    exit();

}

// =========================================
// DATABASE CONNECTION
// =========================================

require "database.php";

// =========================================
// FETCH ADMIN DETAILS
// =========================================

$admin_id = $_SESSION["admin_id"];

$sql = "SELECT
            register_number,
            email
        FROM user
        WHERE user_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $admin_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$admin = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Dashboard</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <h1>Admin Dashboard</h1>

    <h2>

        Welcome , Administrator 

    </h2>

    <br>

    <div class="dashboard-buttons">

        <a href="manage_student.php" class="dashboard-btn blue">

            Manage Students

        </a>

        <a href="manage_teacher.php" class="dashboard-btn blue">

            Manage Teachers

        </a>

        <a href="manage_courses.php" class="dashboard-btn blue">

            Manage Courses

        </a>

        <a href="manage_class.php" class="dashboard-btn blue">

            Manage Classes

        </a>
        <a href="manage_subject.php" class="dashboard-btn blue">

            Manage Subject

        </a>
        <a href="manage_class_subject.php" class="dashboard-btn blue">

            Class Subject Allocation

        </a>
        <a href="manage_teacher_class_subject.php" class="dashboard-btn blue">

            Teacher Subject Allocation

        </a>
        
        <a href="manage_student_class.php" class="dashboard-btn blue">

            Assign Student

        </a>

        

        <a href="admin_logout.php" class="dashboard-btn blue">

            Logout

        </a>

    </div>

</div>

</body>

</html>