<?php

// =========================================
// START SESSION
// =========================================

session_start();

// =========================================
// CHECK LOGIN
// =========================================

if(
    !isset($_SESSION["teacher_id"]) &&
    !isset($_SESSION["student_id"])
){

    header("Location: login.php");
    exit();

}

// =========================================
// CHECK ROLE
// =========================================

if($_SESSION["role_id"] != 2){

    header("Location: login.php");
    exit();

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Teacher Dashboard</title>

<link
rel="stylesheet"
href="style.css">

</head>

<body>

<div class="container">

<h1>

Teacher Dashboard

</h1>

<div class="welcome-card">

<h2>

Welcome,

<?php

echo htmlspecialchars($_SESSION ["teacher_name"]);

?>

</h2>

<p>

Teacher Portal

</p>

</div>

<div class="dashboard-buttons">

<a
href="teacher_profile.php"
class="dashboard-btn blue">

My Profile

</a>

<a
href="teacher_update.php"
class="dashboard-btn blue">

Update Profile

</a>
<a
href="manage_attendance.php"
class="dashboard-btn blue">

Manage Attendance

</a>
<a
href="attendance_report.php"
class="dashboard-btn blue">

Attendance Report

</a>
<a
href="manage_marks.php"
class="dashboard-btn blue">

Manage Marks

</a>
<a
href="manage_assignments.php"
class="dashboard-btn blue">

Manage Assignments

</a>
<a
href="student_performance.php"
class="dashboard-btn blue">

Student Performance

</a>




<a
href="logout.php"
class="dashboard-btn blue">

Logout

</a>

</div>

</div>

</body>

</html>