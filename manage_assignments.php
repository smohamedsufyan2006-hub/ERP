<?php

session_start();


if(!isset($_SESSION["teacher_id"])){

    header("Location: teacher_login.php");
    exit();

}


require "database.php";


$teacher_id=$_SESSION["teacher_id"];


// FETCH ASSIGNMENTS


$sql="

SELECT

a.assignment_id,

a.title,

a.due_date,

c.year_of_study,

c.section,

sub.subject_name


FROM assignments a


INNER JOIN teacher_class_subjects tcs

ON a.teacher_class_subject_id=tcs.teacher_class_subject_id


INNER JOIN class_subjects cs

ON tcs.class_subject_id=cs.class_subject_id


INNER JOIN classes c

ON cs.class_id=c.class_id


INNER JOIN subjects sub

ON cs.subject_id=sub.subject_id


WHERE

tcs.teacher_id=?


ORDER BY

a.created_at DESC


";


$stmt=mysqli_prepare($conn,$sql);


mysqli_stmt_bind_param(

$stmt,

"i",

$teacher_id

);


mysqli_stmt_execute($stmt);


$result=mysqli_stmt_get_result($stmt);



?>


<!DOCTYPE html>

<html>

<head>


<title>
Manage Assignments
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


<a href="teacher_dashboard.php"

class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>


<h1>

Manage Assignments

</h1>



<a href="create_assignment.php"

class="add-btn">

<i class="fa-solid fa-plus"></i>

</a>


</div>




<div class="student-table-wrapper">


<table class="student-table">


<thead>


<tr>

<th>S.No</th>

<th>Class</th>

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

<?php

echo "Year ".$row["year_of_study"]

." - "

.$row["section"];

?>

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


<a href="view_assignments.php?assignment_id=<?php echo $row['assignment_id']; ?>"

class="teacher-btn">


<i class="fa-solid fa-eye"></i>

View


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