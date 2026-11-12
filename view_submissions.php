<?php

session_start();


if(!isset($_SESSION["teacher_id"])){

    header("Location: teacher_login.php");
    exit();

}


require "database.php";


$teacher_id=$_SESSION["teacher_id"];


if(!isset($_GET["assignment_id"])){

    header("Location:manage_assignments.php");

    exit();

}


$assignment_id=$_GET["assignment_id"];




// FETCH SUBMISSIONS


$sql="

SELECT


s.student_name,

u.register_number,


asub.file_name,

asub.status,

asub.submission_date



FROM assignment_submissions asub



INNER JOIN students s

ON asub.student_id=s.student_id



INNER JOIN user u

ON s.user_id=u.user_id



INNER JOIN assignments a

ON asub.assignment_id=a.assignment_id



INNER JOIN teacher_class_subjects tcs

ON a.teacher_class_subject_id=tcs.teacher_class_subject_id



WHERE

asub.assignment_id=?

AND

tcs.teacher_id=?



ORDER BY

u.register_number


";



$stmt=mysqli_prepare($conn,$sql);



mysqli_stmt_bind_param(

$stmt,

"ii",

$assignment_id,

$teacher_id

);



mysqli_stmt_execute($stmt);


$result=mysqli_stmt_get_result($stmt);



?>


<!DOCTYPE html>

<html>

<head>


<title>

Assignment Submissions

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


<a href="manage_assignments.php"

class="back-btn">


<i class="fa-solid fa-arrow-left"></i>


</a>


<h1>

Assignment Submissions

</h1>


</div>




<div class="student-table-wrapper">


<table class="student-table">


<thead>


<tr>


<th>S.No</th>

<th>Register No</th>

<th>Student Name</th>

<th>Status</th>

<th>Date</th>

<th>File</th>


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

<?php echo htmlspecialchars($row["register_number"]); ?>

</td>


<td>

<?php echo htmlspecialchars($row["student_name"]); ?>

</td>


<td>

<?php echo $row["status"]; ?>

</td>


<td>

<?php echo $row["submission_date"]; ?>

</td>


<td>


<a

href="uploads/assignments/<?php echo $row["file_name"]; ?>"

target="_blank"

class="teacher-btn">


<i class="fa-solid fa-download"></i>

View File


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