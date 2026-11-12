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
// CHECK PARAMETER
// =========================================

if (!isset($_GET["teacher_class_subject_id"])) {

    header("Location: manage_attendance.php");
    exit();

}

$teacher_class_subject_id = intval($_GET["teacher_class_subject_id"]);

// =========================================
// FETCH CLASS & SUBJECT DETAILS
// =========================================

$sql = "SELECT

            c.class_id,
            c.year_of_study,
            c.section,
            d.department_name,
            s.subject_code,
            s.subject_name

        FROM teacher_class_subjects tcs

        INNER JOIN class_subjects cs
        ON tcs.class_subject_id = cs.class_subject_id

        INNER JOIN classes c
        ON cs.class_id = c.class_id

        INNER JOIN departments d
        ON c.department_id = d.department_id

        INNER JOIN subjects s
        ON cs.subject_id = s.subject_id

        WHERE tcs.teacher_class_subject_id=?";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param($stmt,"i",$teacher_class_subject_id);

mysqli_stmt_execute($stmt);

$result=mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result)==0){

    die("Invalid Record.");

}

$data=mysqli_fetch_assoc($result);

$class_id=$data["class_id"];

// =========================================
// FETCH STUDENTS
// =========================================

$sql = "SELECT

            s.student_id,

            u.register_number,

            s.student_name

        FROM students s

        INNER JOIN user u

        ON s.user_id = u.user_id

        WHERE s.class_id = ?

        ORDER BY u.register_number";

$stmt=mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param($stmt,"i",$class_id);

mysqli_stmt_execute($stmt);

$students=mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Take Attendance</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet" href="teacher.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="form-card">

<div class="page-header">

<a href="manage_attendance.php" class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>

<h1>

Take Attendance

</h1>

</div>

<form

action="save_attendance.php"

method="POST">

<input

type="hidden"

name="teacher_class_subject_id"

value="<?php echo $teacher_class_subject_id; ?>">

<div class="form-grid">

<div class="form-group">

<label>

Class

</label>

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

echo $roman[$data["year_of_study"]]." ";

echo $data["department_name"]." ";

echo $data["section"];

?>">

</div>

<div class="form-group">

<label>

Subject

</label>

<input

type="text"

readonly

value="<?php

echo $data["subject_code"];

echo " - ";

echo $data["subject_name"];

?>">

</div>

<div class="form-group">

<label>

Attendance Date

</label>

<input

type="date"

name="attendance_date"

value="<?php echo date("Y-m-d"); ?>"

required>

</div>

<div class="form-group">

<label>

Period

</label>

<select

name="period_number"

required>

<option value="">Select</option>

<?php

for($i=1;$i<=8;$i++){

?>

<option value="<?php echo $i; ?>">

Period <?php echo $i; ?>

</option>

<?php

}

?>

</select>

</div>

</div>

<br>

<table class="student-table">

<thead>

<tr>

<th>Register No</th>

<th>Student Name</th>

<th>Attendance</th>

</tr>

</thead>

<tbody>

<?php

while($student=mysqli_fetch_assoc($students)){

?>

<tr>

<td>

<?php echo htmlspecialchars($student["register_number"]); ?>

</td>

<td>

<?php echo htmlspecialchars($student["student_name"]); ?>

</td>

<td>

<input

type="hidden"

name="student_id[]"

value="<?php echo $student["student_id"]; ?>">

<label>

<input

type="radio"

name="attendance_<?php echo $student["student_id"]; ?>"

value="Present"

checked>

Present

</label>

&nbsp;&nbsp;

<label>

<input

type="radio"

name="attendance_<?php echo $student["student_id"]; ?>"

value="Absent">

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

Save Attendance

</button>

<a

href="manage_attendance.php"

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