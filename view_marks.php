<?php

session_start();


if(!isset($_SESSION["teacher_id"])){

    header("Location: teacher_login.php");
    exit();

}


require "database.php";


$teacher_id=$_SESSION["teacher_id"];



if(!isset($_GET["teacher_class_subject_id"])){

    header("Location: manage_marks.php");
    exit();

}


$teacher_class_subject_id=$_GET["teacher_class_subject_id"];


// =====================================
// FETCH MARK DETAILS
// =====================================


$sql="

SELECT

sm.mark_id,

sm.marks_obtained,

sm.max_marks,

s.student_name,

u.register_number,

mt.mark_type_name,

sub.subject_name,

c.year_of_study,

c.section


FROM student_marks sm


INNER JOIN students s

ON sm.student_id=s.student_id


INNER JOIN user u

ON s.user_id=u.user_id


INNER JOIN mark_types mt

ON sm.mark_type_id=mt.mark_type_id


INNER JOIN teacher_class_subjects tcs

ON sm.teacher_class_subject_id=tcs.teacher_class_subject_id


INNER JOIN class_subjects cs

ON tcs.class_subject_id=cs.class_subject_id


INNER JOIN subjects sub

ON cs.subject_id=sub.subject_id


INNER JOIN classes c

ON cs.class_id=c.class_id


WHERE

sm.teacher_class_subject_id=? 

AND

tcs.teacher_id=?


ORDER BY

u.register_number


";


$stmt=mysqli_prepare($conn,$sql);


mysqli_stmt_bind_param(

$stmt,

"ii",

$teacher_class_subject_id,

$teacher_id

);


mysqli_stmt_execute($stmt);


$result=mysqli_stmt_get_result($stmt);



$total=mysqli_num_rows($result);


?>



<!DOCTYPE html>

<html>

<head>


<title>
View Marks
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


<a href="manage_marks.php"
class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>


<h1>

Marks Details

</h1>


</div>



<div class="student-count-card">


<h3>

Total Records

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

<th>Register Number</th>

<th>Student Name</th>

<th>Subject</th>

<th>Mark Type</th>

<th>Marks</th>

<th>Action</th>


</tr>


</thead>



<tbody>



<?php


$i=1;


if($total>0){


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

<?php echo htmlspecialchars($row["subject_name"]); ?>

</td>



<td>

<?php echo htmlspecialchars($row["mark_type_name"]); ?>

</td>



<td>

<?php echo $row["marks_obtained"]." / ".$row["max_marks"]; ?>

</td>



<td>


<a

href="edit_marks.php?mark_id=<?php echo $row['mark_id']; ?>"

class="edit-btn">


<i class="fa-solid fa-pen"></i>

Edit


</a>


</td>


</tr>


<?php


}


}

else{


?>


<tr>

<td colspan="7">

No marks found.

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


<?php

mysqli_stmt_close($stmt);

mysqli_close($conn);

?>