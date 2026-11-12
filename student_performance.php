<?php

session_start();


if(!isset($_SESSION["teacher_id"])){

    header("Location: teacher_login.php");
    exit();

}


require "database.php";


$teacher_id=$_SESSION["teacher_id"];


// FETCH TEACHER ASSIGNED CLASSES


$sql="

SELECT DISTINCT

c.class_id,

c.year_of_study,

c.section


FROM teacher_class_subjects tcs


INNER JOIN class_subjects cs

ON tcs.class_subject_id=cs.class_subject_id


INNER JOIN classes c

ON cs.class_id=c.class_id


WHERE tcs.teacher_id=?


ORDER BY

c.year_of_study,
c.section


";


$stmt=mysqli_prepare($conn,$sql);


mysqli_stmt_bind_param(

$stmt,

"i",

$teacher_id

);


mysqli_stmt_execute($stmt);


$classes=mysqli_stmt_get_result($stmt);


?>


<!DOCTYPE html>

<html>

<head>

<title>
Student Performance
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

Student Performance

</h1>


</div>



<div class="student-table-wrapper">


<table class="student-table">


<thead>

<tr>

<th>S.No</th>

<th>Class</th>

<th>Action</th>

</tr>

</thead>



<tbody>


<?php


$i=1;


while($row=mysqli_fetch_assoc($classes)){


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


<a

class="teacher-btn"

href="student_performance_view.php?class_id=<?php echo $row['class_id']; ?>">


<i class="fa-solid fa-chart-line"></i>

View Students


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