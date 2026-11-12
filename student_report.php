<?php

session_start();


if(!isset($_SESSION["teacher_id"])){

    header("Location: teacher_login.php");
    exit();

}


require "database.php";


if(!isset($_GET["student_id"])){

    header("Location: student_performance.php");
    exit();

}


$student_id=$_GET["student_id"];


// =====================================
// FETCH STUDENT DETAILS
// =====================================


$sql="

SELECT

s.student_name,

u.register_number,

c.year_of_study,

c.section


FROM students s


INNER JOIN user u

ON s.user_id=u.user_id


INNER JOIN classes c

ON s.class_id=c.class_id


WHERE s.student_id=?


";


$stmt=mysqli_prepare($conn,$sql);


mysqli_stmt_bind_param(

$stmt,

"i",

$student_id

);


mysqli_stmt_execute($stmt);


$student=mysqli_fetch_assoc(

mysqli_stmt_get_result($stmt)

);



if(!$student){

    die("Student not found");

}



// =====================================
// ATTENDANCE REPORT
// =====================================


$attendance_sql="

SELECT


sub.subject_name,


ROUND(

SUM(

CASE

WHEN a.attendance_status='Present'

THEN 1 ELSE 0 END

)

/

COUNT(a.attendance_id)

*100,

2

) AS percentage



FROM attendance a


INNER JOIN attendance_sessions ats

ON a.attendance_session_id=

ats.attendance_session_id


INNER JOIN teacher_class_subjects tcs

ON ats.teacher_class_subject_id=

tcs.teacher_class_subject_id


INNER JOIN class_subjects cs

ON tcs.class_subject_id=

cs.class_subject_id


INNER JOIN subjects sub

ON cs.subject_id=sub.subject_id


WHERE

a.student_id=?


GROUP BY

sub.subject_id


";


$stmt2=mysqli_prepare($conn,$attendance_sql);


mysqli_stmt_bind_param(

$stmt2,

"i",

$student_id

);


mysqli_stmt_execute($stmt2);


$attendance=mysqli_stmt_get_result($stmt2);



// =====================================
// MARKS REPORT
// =====================================


$marks_sql="

SELECT


sub.subject_name,

mt.mark_type_name,

sm.marks_obtained,

sm.max_marks


FROM student_marks sm


INNER JOIN mark_types mt

ON sm.mark_type_id=mt.mark_type_id


INNER JOIN teacher_class_subjects tcs

ON sm.teacher_class_subject_id=

tcs.teacher_class_subject_id


INNER JOIN class_subjects cs

ON tcs.class_subject_id=

cs.class_subject_id


INNER JOIN subjects sub

ON cs.subject_id=sub.subject_id


WHERE

sm.student_id=?


ORDER BY

sub.subject_name


";


$stmt3=mysqli_prepare($conn,$marks_sql);


mysqli_stmt_bind_param(

$stmt3,

"i",

$student_id

);


mysqli_stmt_execute($stmt3);


$marks=mysqli_stmt_get_result($stmt3);


?>



<!DOCTYPE html>

<html>

<head>


<title>

Student Report

</title>


<link rel="stylesheet" href="style.css">

<link rel="stylesheet" href="teacher.css">


<link rel="stylesheet"

href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


</head>



<body>



<div class="manage-student-container">


<div class="teacher-page-card">



<div class="page-header">


<a href="student_performance_view.php"

class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>


<h1>

Student Report

</h1>


</div>




<div class="student-count-card">


<h3>

Student Details

</h3>


<p style="font-size:18px">


<?php echo htmlspecialchars($student["student_name"]); ?>


<br>


<?php echo $student["register_number"]; ?>


<br>


Year <?php echo $student["year_of_study"]; ?>

-

<?php echo $student["section"]; ?>


</p>


</div>



<br>



<h2>

Attendance Summary

</h2>



<div class="student-table-wrapper">


<table class="student-table">


<tr>

<th>Subject</th>

<th>Attendance %</th>

</tr>



<?php while($row=mysqli_fetch_assoc($attendance)){ ?>


<tr>


<td>

<?php echo $row["subject_name"]; ?>

</td>


<td>

<?php echo $row["percentage"]; ?> %

</td>


</tr>


<?php } ?>


</table>


</div>



<br>



<h2>

Internal Marks

</h2>



<div class="student-table-wrapper">


<table class="student-table">


<tr>


<th>Subject</th>

<th>Exam</th>

<th>Marks</th>


</tr>



<?php while($row=mysqli_fetch_assoc($marks)){ ?>


<tr>


<td>

<?php echo $row["subject_name"]; ?>

</td>


<td>

<?php echo $row["mark_type_name"]; ?>

</td>


<td>

<?php

echo $row["marks_obtained"]

."/"

.$row["max_marks"];

?>

</td>


</tr>


<?php } ?>


</table>


</div>




</div>


</div>


</body>


</html>