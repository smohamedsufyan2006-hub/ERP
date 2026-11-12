<?php

// =========================================
// START SESSION
// =========================================

session_start();

// =========================================
// CHECK TEACHER LOGIN
// =========================================

if (!isset($_SESSION["teacher_id"])) {

    header("Location: teacher_login.php");
    exit();

}

// =========================================
// DATABASE CONNECTION
// =========================================

require "database.php";

// =========================================
// LOGGED IN TEACHER
// =========================================

$teacher_id = $_SESSION["teacher_id"];

// =========================================
// FETCH ATTENDANCE SESSIONS
// =========================================

$sql = "SELECT

            ats.attendance_session_id,

            ats.attendance_date,

            ats.period_number,

            c.year_of_study,

            c.section,

            d.department_name,

            s.subject_code,

            s.subject_name

        FROM attendance_sessions ats

        INNER JOIN teacher_class_subjects tcs

            ON ats.teacher_class_subject_id = tcs.teacher_class_subject_id

        INNER JOIN class_subjects cs

            ON tcs.class_subject_id = cs.class_subject_id

        INNER JOIN classes c

            ON cs.class_id = c.class_id

        INNER JOIN departments d

            ON c.department_id = d.department_id

        INNER JOIN subjects s

            ON cs.subject_id = s.subject_id

        WHERE tcs.teacher_id = ?

        ORDER BY

            ats.attendance_date DESC,

            ats.period_number DESC";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param($stmt,"i",$teacher_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$total = mysqli_num_rows($result);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

View Attendance

</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet" href="teacher.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="manage-student-container">

<div class="page-header">

<a

href="teacher_dashboard.php"

class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>

<h1>

Attendance Records

</h1>

</div>

<br>

<div class="student-count-card">

<h3>

Total Sessions

</h3>

<p>

<?php echo $total; ?>

</p>

</div>

<br>

<div class="student-table-wrapper">

<table class="student-table">

<thead>

<tr>

<th>S.No</th>

<th>Class</th>

<th>Subject</th>

<th>Date</th>

<th>Period</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php

$roman = [

1=>"I",

2=>"II",

3=>"III",

4=>"IV"

];

$serial = 1;

if($total>0){

while($row=mysqli_fetch_assoc($result)){

?>

<tr>

<td>

<?php echo $serial++; ?>

</td>

<td>

<?php

echo $roman[$row["year_of_study"]]." ";

echo htmlspecialchars($row["department_name"])." ";

echo htmlspecialchars($row["section"]);

?>

</td>

<td>

<?php

echo htmlspecialchars($row["subject_code"]);

echo " - ";

echo htmlspecialchars($row["subject_name"]);

?>

</td>

<td>

<?php echo date("d-m-Y",strtotime($row["attendance_date"])); ?>

</td>

<td>

<?php echo $row["period_number"]; ?>

</td>

<td class="action-buttons">

<a

href="edit_attendance.php?attendance_session_id=<?php echo $row["attendance_session_id"]; ?>"

class="student-edit-btn">

<i class="fa-solid fa-pen-to-square"></i>

Edit

</a>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="6">

No attendance records found.

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

</body>

</html>

<?php

mysqli_stmt_close($stmt);

mysqli_close($conn);

?>