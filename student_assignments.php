<?php

session_start();


if(!isset($_SESSION["student_id"])){

    header("Location: student_login.php");
    exit();

}


require "database.php";


$student_id=$_SESSION["student_id"];


// GET STUDENT CLASS


$class_sql="

SELECT class_id

FROM students

WHERE student_id=?

";


$class_stmt=mysqli_prepare($conn,$class_sql);


mysqli_stmt_bind_param(

$class_stmt,

"i",

$student_id

);


mysqli_stmt_execute($class_stmt);


$class_result=mysqli_stmt_get_result($class_stmt);


$student=mysqli_fetch_assoc($class_result);



$class_id=$student["class_id"];




// FETCH ASSIGNMENTS


$sql="

SELECT


a.assignment_id,

a.title,

a.description,

a.due_date,


sub.subject_name



FROM assignments a



INNER JOIN teacher_class_subjects tcs

ON a.teacher_class_subject_id=tcs.teacher_class_subject_id



INNER JOIN class_subjects cs

ON tcs.class_subject_id=cs.class_subject_id



INNER JOIN subjects sub

ON cs.subject_id=sub.subject_id



WHERE

cs.class_id=?



ORDER BY

a.due_date



";



$stmt=mysqli_prepare($conn,$sql);



mysqli_stmt_bind_param(

$stmt,

"i",

$class_id

);



mysqli_stmt_execute($stmt);



$result=mysqli_stmt_get_result($stmt);


?>


<!DOCTYPE html>

<html>

<head>


<title>

My Assignments

</title>


<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="student.css">


<link rel="stylesheet"

href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


</head>


<body>


<div class="student-assignment-container">


<div class="student-assignment-card">


<div class="page-header">


<a href="dashboard.php"

class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>


<h1>

My Assignments

</h1>


</div>



<div class="student-table-wrapper">


<table class="student-table">


<thead>


<tr>

<th>S.No</th>

<th>Subject</th>

<th>Assignment</th>

<th>Due Date</th>

<th>Action</th>

</tr>


</thead>


<tbody>


<?php


$i=1;


while($row=mysqli_fetch_assoc($result)){


?>


<tr>


<td>

<?php echo $i++; ?>

</td>


<td>

<?php echo htmlspecialchars($row["subject_name"]); ?>

</td>


<td>

<?php echo htmlspecialchars($row["title"]); ?>

</td>


<td>

<?php echo $row["due_date"]; ?>

</td>


<td>


<a

href="submit_assignment.php?assignment_id=<?php echo $row["assignment_id"]; ?>"

class="teacher-btn">


<i class="fa-solid fa-upload"></i>

Submit


</a>


</td>


</tr>


<?php


}


?>


</tbody>


</table>


</div>



</div>


</div>


</body>

</html>