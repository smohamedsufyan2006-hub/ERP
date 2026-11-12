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
// CHECK ID
// =========================================

if (!isset($_GET["attendance_session_id"])) {

    header("Location: view_attendance.php");
    exit();

}

$attendance_session_id = intval($_GET["attendance_session_id"]);

// =========================================
// FETCH SESSION DETAILS
// =========================================

$sql = "SELECT

            ats.attendance_date,

            ats.period_number,

            c.year_of_study,

            c.section,

            d.department_name,

            s.subject_code,

            s.subject_name

        FROM attendance_sessions ats

        INNER JOIN teacher_class_subjects tcs
        ON ats.teacher_class_subject_id=tcs.teacher_class_subject_id

        INNER JOIN class_subjects cs
        ON tcs.class_subject_id=cs.class_subject_id

        INNER JOIN classes c
        ON cs.class_id=c.class_id

        INNER JOIN departments d
        ON c.department_id=d.department_id

        INNER JOIN subjects s
        ON cs.subject_id=s.subject_id

        WHERE ats.attendance_session_id=?";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param($stmt,"i",$attendance_session_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result)==0){

    die("Attendance session not found.");

}

$session = mysqli_fetch_assoc($result);
$attendance_date = $session["attendance_date"];

if ($attendance_date != date("Y-m-d")) {

    echo "<script>

            alert('Attendance can only be edited on the same day.');

            window.location='view_attendance.php';

          </script>";

    exit();

}

// =========================================
// FETCH STUDENTS
// =========================================

$sql = "SELECT

            a.attendance_id,

            a.student_id,

            a.attendance_status,

            st.student_name,

            u.register_number

        FROM attendance a

        INNER JOIN students st
        ON a.student_id=st.student_id

        INNER JOIN users u
        ON st.user_id=u.user_id

        WHERE a.attendance_session_id=?

        ORDER BY u.register_number";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param($stmt,"i",$attendance_session_id);

mysqli_stmt_execute($stmt);

$students = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Edit Attendance</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="teacher.css">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="form-card">

<div class="page-header">

<a href="view_attendance.php" class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>

<h1>Edit Attendance</h1>

</div>

<form action="update_attendance.php" method="POST">

<input
type="hidden"
name="attendance_session_id"
value="<?php echo $attendance_session_id; ?>">

<div class="form-grid">

<div class="form-group">

<label>Class</label>

<input
type="text"
readonly
value="<?php

$roman=[

1=>"I",

2=>"II",

3=>"III",

4=>"IV"

];

echo $roman[$session["year_of_study"]]." ";

echo $session["department_name"]." ";

echo $session["section"];

?>">

</div>

<div class="form-group">

<label>Subject</label>

<input
type="text"
readonly
value="<?php

echo $session["subject_code"];

echo " - ";

echo $session["subject_name"];

?>">

</div>

<div class="form-group">

<label>Date</label>

<input
type="text"
readonly
value="<?php echo date("d-m-Y",strtotime($session["attendance_date"])); ?>">

</div>

<div class="form-group">

<label>Period</label>

<input
type="text"
readonly
value="<?php echo $session["period_number"]; ?>">

</div>

</div>

<br>

<table class="student-table">

<thead>

<tr>

<th>Register No</th>

<th>Student Name</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php

while($row=mysqli_fetch_assoc($students)){

?>

<tr>

<td>

<?php echo htmlspecialchars($row["register_number"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["student_name"]); ?>

</td>

<td>

<input
type="hidden"
name="attendance_id[]"
value="<?php echo $row["attendance_id"]; ?>">

<label>

<input
type="radio"
name="status_<?php echo $row["attendance_id"]; ?>"
value="Present"

<?php

if($row["attendance_status"]=="Present")

echo "checked";

?>

>

Present

</label>

&nbsp;&nbsp;

<label>

<input
type="radio"
name="status_<?php echo $row["attendance_id"]; ?>"
value="Absent"

<?php

if($row["attendance_status"]=="Absent")

echo "checked";

?>

>

Absent

</label>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

<br>

<div class="form-buttons">

<button
type="submit"
class="save-btn">

Update Attendance

</button>

<a
href="view_attendance.php"
class="cancel-btn">

Cancel

</a>

</div>

</form>

</div>

</body>

</html>

<?php

mysqli_close($conn);

?>